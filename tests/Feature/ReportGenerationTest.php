<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Integration;
use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use App\Services\AiSummaryService;
use App\Services\ReportGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReportGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_generate_report_for_project(): void
    {
        $user = User::create([
            'name' => 'Report User',
            'email' => 'report@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $client = Client::create([
            'user_id' => $user->id,
            'name' => 'Client',
        ]);

        $project = Project::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'name' => 'Project',
            'color' => '#e8a325',
            'status' => 'active',
            'report_frequency' => 'weekly',
            'report_day' => 'friday',
        ]);

        $this->app->instance(ReportGeneratorService::class, new class($user, $project, $client) extends ReportGeneratorService {
            public function __construct(private User $user, private Project $project, private Client $client) {}

            public function generate(Project $project, \Carbon\Carbon $start, \Carbon\Carbon $end): Report
            {
                $report = Report::create([
                    'user_id' => $this->user->id,
                    'project_id' => $this->project->id,
                    'client_id' => $this->client->id,
                    'title' => 'Generated Report',
                    'period_start' => $start,
                    'period_end' => $end,
                    'status' => 'draft',
                ]);

                $report->entries()->create([
                    'source' => 'manual',
                    'type' => 'task',
                    'title' => 'Generated entry',
                ]);

                return $report;
            }
        });

        $this->app->instance(AiSummaryService::class, new class extends AiSummaryService {
            public function summarize(Report $report): string
            {
                return 'Generated summary.';
            }
        });

        $response = $this->actingAs($user)->post(route('reports.generate', $project), [
            'period_start' => now()->subWeek()->startOfWeek()->format('Y-m-d'),
            'period_end' => now()->subWeek()->endOfWeek()->format('Y-m-d'),
        ]);

        $report = Report::where('project_id', $project->id)->latest('id')->first();

        $response->assertRedirect(route('reports.show', $report));
        $this->assertSame('Generated summary.', $report->fresh()->ai_summary);
        $this->assertDatabaseHas('report_entries', [
            'report_id' => $report->id,
            'title' => 'Generated entry',
        ]);
    }

    public function test_public_shared_report_can_be_viewed_and_increments_view_count(): void
    {
        $user = User::create([
            'name' => 'Shared Report User',
            'email' => 'shared@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $client = Client::create([
            'user_id' => $user->id,
            'name' => 'Shared Client',
        ]);

        $project = Project::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'name' => 'Shared Project',
            'color' => '#4a9eff',
            'status' => 'active',
            'report_frequency' => 'weekly',
            'report_day' => 'friday',
        ]);

        $report = Report::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'client_id' => $client->id,
            'title' => 'Shared Report',
            'period_start' => now()->subWeek()->startOfWeek(),
            'period_end' => now()->subWeek()->endOfWeek(),
            'status' => 'sent',
            'share_enabled' => true,
            'view_count' => 0,
        ]);

        $report->entries()->create([
            'source' => 'manual',
            'type' => 'task',
            'title' => 'Shared entry',
        ]);

        $response = $this->get(route('reports.public', $report->share_token));

        $response->assertOk();
        $response->assertSee('Shared Report');
        $response->assertSee('Shared entry');
        $this->assertSame(1, $report->fresh()->view_count);
    }

    public function test_report_generation_pulls_github_activity_for_project_repository(): void
    {
        Http::fake([
            'https://api.github.com/repos/owner/repo/commits*' => Http::response([
                [
                    'sha' => 'abcdef1234567890',
                    'html_url' => 'https://github.com/owner/repo/commit/abcdef1',
                    'commit' => [
                        'message' => "Implement auth flow\n\nMore details",
                        'author' => [
                            'name' => 'Dev One',
                            'date' => now()->subDay()->toIso8601String(),
                        ],
                    ],
                ],
            ], 200),
            'https://api.github.com/repos/owner/repo/pulls*' => Http::response([
                [
                    'number' => 17,
                    'title' => 'Ship dashboard polish',
                    'body' => 'Improves dashboard layout and fixes edge cases.',
                    'html_url' => 'https://github.com/owner/repo/pull/17',
                    'merged_at' => now()->subDay()->toIso8601String(),
                    'user' => ['login' => 'dev-one'],
                ],
            ], 200),
        ]);

        $user = User::create([
            'name' => 'GitHub Report User',
            'email' => 'github-report@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $client = Client::create([
            'user_id' => $user->id,
            'name' => 'GitHub Client',
        ]);

        $project = Project::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'name' => 'Repo Project',
            'color' => '#e8a325',
            'status' => 'active',
            'report_frequency' => 'weekly',
            'report_day' => 'friday',
        ]);

        Integration::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'provider' => 'github',
            'provider_account_id' => 'github-user-1',
            'provider_account_name' => 'GitHub User',
            'resource_id' => '42',
            'resource_name' => 'owner/repo',
            'access_token' => 'token-123',
            'active' => true,
        ]);

        $this->app->instance(AiSummaryService::class, new class extends AiSummaryService {
            public function summarize(Report $report): string
            {
                return 'GitHub-backed summary.';
            }
        });

        $response = $this->actingAs($user)->post(route('reports.generate', $project), [
            'period_start' => now()->subWeek()->startOfWeek()->format('Y-m-d'),
            'period_end' => now()->endOfDay()->format('Y-m-d'),
        ]);

        $report = Report::where('project_id', $project->id)->latest('id')->firstOrFail();

        $response->assertRedirect(route('reports.show', $report));
        $this->assertSame('Weekly Report - Repo Project', $report->title);
        $this->assertSame('ready', $report->status);
        $this->assertSame('GitHub-backed summary.', $report->fresh()->ai_summary);
        $this->assertDatabaseHas('report_entries', [
            'report_id' => $report->id,
            'source' => 'github',
            'type' => 'commit',
            'title' => 'Implement auth flow',
        ]);
        $this->assertDatabaseHas('report_entries', [
            'report_id' => $report->id,
            'source' => 'github',
            'type' => 'pull_request',
            'title' => 'PR merged: Ship dashboard polish',
        ]);
    }

    public function test_report_generation_only_uses_github_repo_attached_to_current_project(): void
    {
        Http::fake([
            'https://api.github.com/repos/owner/repo-one/commits*' => Http::response([
                [
                    'sha' => 'repoone123456',
                    'html_url' => 'https://github.com/owner/repo-one/commit/repoone1',
                    'commit' => [
                        'message' => 'Repo one commit',
                        'author' => [
                            'name' => 'Dev One',
                            'date' => now()->subDay()->toIso8601String(),
                        ],
                    ],
                ],
            ], 200),
            'https://api.github.com/repos/owner/repo-one/pulls*' => Http::response([], 200),
            'https://api.github.com/repos/owner/repo-two/commits*' => Http::response([
                [
                    'sha' => 'repotwo123456',
                    'html_url' => 'https://github.com/owner/repo-two/commit/repotwo1',
                    'commit' => [
                        'message' => 'Repo two commit',
                        'author' => [
                            'name' => 'Dev Two',
                            'date' => now()->subDay()->toIso8601String(),
                        ],
                    ],
                ],
            ], 200),
            'https://api.github.com/repos/owner/repo-two/pulls*' => Http::response([], 200),
        ]);

        $user = User::create([
            'name' => 'Multi Repo User',
            'email' => 'multi-repo@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $client = Client::create([
            'user_id' => $user->id,
            'name' => 'Multi Repo Client',
        ]);

        $projectOne = Project::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'name' => 'Project One',
            'color' => '#e8a325',
            'status' => 'active',
            'report_frequency' => 'weekly',
            'report_day' => 'friday',
        ]);

        $projectTwo = Project::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'name' => 'Project Two',
            'color' => '#4a9eff',
            'status' => 'active',
            'report_frequency' => 'weekly',
            'report_day' => 'friday',
        ]);

        Integration::create([
            'user_id' => $user->id,
            'project_id' => $projectOne->id,
            'provider' => 'github',
            'provider_account_id' => 'github-user-1',
            'provider_account_name' => 'GitHub User',
            'resource_id' => '42',
            'resource_name' => 'owner/repo-one',
            'access_token' => 'token-123',
            'active' => true,
        ]);

        Integration::create([
            'user_id' => $user->id,
            'project_id' => $projectTwo->id,
            'provider' => 'github',
            'provider_account_id' => 'github-user-1',
            'provider_account_name' => 'GitHub User',
            'resource_id' => '43',
            'resource_name' => 'owner/repo-two',
            'access_token' => 'token-123',
            'active' => true,
        ]);

        $this->app->instance(AiSummaryService::class, new class extends AiSummaryService {
            public function summarize(Report $report): string
            {
                return 'Scoped summary.';
            }
        });

        $response = $this->actingAs($user)->post(route('reports.generate', $projectOne), [
            'period_start' => now()->subWeek()->startOfWeek()->format('Y-m-d'),
            'period_end' => now()->endOfDay()->format('Y-m-d'),
        ]);

        $report = Report::where('project_id', $projectOne->id)->latest('id')->firstOrFail();

        $response->assertRedirect(route('reports.show', $report));
        $this->assertDatabaseHas('report_entries', [
            'report_id' => $report->id,
            'title' => 'Repo one commit',
        ]);
        $this->assertDatabaseMissing('report_entries', [
            'report_id' => $report->id,
            'title' => 'Repo two commit',
        ]);
    }

    public function test_report_generation_includes_github_activity_from_the_end_date(): void
    {
        $todayActivity = now()->setTime(15, 45, 0);

        Http::fake([
            'https://api.github.com/repos/owner/end-date-repo/commits*' => Http::response([
                [
                    'sha' => 'enddate123456',
                    'html_url' => 'https://github.com/owner/end-date-repo/commit/enddate1',
                    'commit' => [
                        'message' => 'Ship end of day fix',
                        'author' => [
                            'name' => 'Dev Late',
                            'date' => $todayActivity->toIso8601String(),
                        ],
                        'committer' => [
                            'name' => 'Dev Late',
                            'date' => $todayActivity->toIso8601String(),
                        ],
                    ],
                ],
            ], 200),
            'https://api.github.com/repos/owner/end-date-repo/pulls*' => Http::response([], 200),
        ]);

        $user = User::create([
            'name' => 'End Date User',
            'email' => 'end-date@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $client = Client::create([
            'user_id' => $user->id,
            'name' => 'End Date Client',
        ]);

        $project = Project::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'name' => 'End Date Project',
            'color' => '#e8a325',
            'status' => 'active',
            'report_frequency' => 'weekly',
            'report_day' => 'friday',
        ]);

        Integration::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'provider' => 'github',
            'provider_account_id' => 'github-user-1',
            'provider_account_name' => 'GitHub User',
            'resource_id' => '44',
            'resource_name' => 'owner/end-date-repo',
            'access_token' => 'token-123',
            'active' => true,
        ]);

        $this->app->instance(AiSummaryService::class, new class extends AiSummaryService {
            public function summarize(Report $report): string
            {
                return 'End date summary.';
            }
        });

        $response = $this->actingAs($user)->post(route('reports.generate', $project), [
            'period_start' => now()->subDays(2)->format('Y-m-d'),
            'period_end' => now()->format('Y-m-d'),
        ]);

        $report = Report::where('project_id', $project->id)->latest('id')->firstOrFail();

        $response->assertRedirect(route('reports.show', $report));
        $this->assertDatabaseHas('report_entries', [
            'report_id' => $report->id,
            'source' => 'github',
            'title' => 'Ship end of day fix',
        ]);
    }

    public function test_report_generation_fetches_multiple_github_pages(): void
    {
        Http::fake(function ($request) {
            if (str_starts_with($request->url(), 'https://api.github.com/repos/owner/paginated-repo/commits')) {
                $page = (int) data_get($request->data(), 'page', 1);

                if ($page === 1) {
                    return Http::response(array_map(
                        fn (int $index) => [
                            'sha' => 'page1-' . $index,
                            'html_url' => "https://github.com/owner/paginated-repo/commit/page1-{$index}",
                            'commit' => [
                                'message' => "Page one commit {$index}",
                                'author' => [
                                    'name' => 'Dev Page One',
                                    'date' => now()->subDay()->toIso8601String(),
                                ],
                                'committer' => [
                                    'name' => 'Dev Page One',
                                    'date' => now()->subDay()->toIso8601String(),
                                ],
                            ],
                        ],
                        range(1, 100)
                    ), 200);
                }

                if ($page === 2) {
                    return Http::response([
                        [
                            'sha' => 'page2-special',
                            'html_url' => 'https://github.com/owner/paginated-repo/commit/page2-special',
                            'commit' => [
                                'message' => 'Page two commit',
                                'author' => [
                                    'name' => 'Dev Page Two',
                                    'date' => now()->subDay()->toIso8601String(),
                                ],
                                'committer' => [
                                    'name' => 'Dev Page Two',
                                    'date' => now()->subDay()->toIso8601String(),
                                ],
                            ],
                        ],
                    ], 200);
                }

                return Http::response([], 200);
            }

            if (str_starts_with($request->url(), 'https://api.github.com/repos/owner/paginated-repo/pulls')) {
                return Http::response([], 200);
            }

            return Http::response([], 404);
        });

        $user = User::create([
            'name' => 'Paginated Repo User',
            'email' => 'paginated@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $client = Client::create([
            'user_id' => $user->id,
            'name' => 'Paginated Client',
        ]);

        $project = Project::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'name' => 'Paginated Project',
            'color' => '#4a9eff',
            'status' => 'active',
            'report_frequency' => 'weekly',
            'report_day' => 'friday',
        ]);

        Integration::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'provider' => 'github',
            'provider_account_id' => 'github-user-2',
            'provider_account_name' => 'GitHub User',
            'resource_id' => '45',
            'resource_name' => 'owner/paginated-repo',
            'access_token' => 'token-456',
            'active' => true,
        ]);

        $this->app->instance(AiSummaryService::class, new class extends AiSummaryService {
            public function summarize(Report $report): string
            {
                return 'Paginated summary.';
            }
        });

        $this->actingAs($user)->post(route('reports.generate', $project), [
            'period_start' => now()->subWeek()->format('Y-m-d'),
            'period_end' => now()->format('Y-m-d'),
        ]);

        $report = Report::where('project_id', $project->id)->latest('id')->firstOrFail();

        $this->assertDatabaseHas('report_entries', [
            'report_id' => $report->id,
            'title' => 'Page two commit',
        ]);
    }
}
