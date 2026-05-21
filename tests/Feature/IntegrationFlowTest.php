<?php

namespace Tests\Feature;

use App\Models\Integration;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class IntegrationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_github_integration_redirect_uses_registered_auth_callback(): void
    {
        config()->set('services.github.client_id', 'github-client');
        config()->set('services.github.client_secret', 'github-secret');

        Socialite::shouldReceive('driver->redirectUrl->scopes->redirect')
            ->once()
            ->andReturn(redirect('https://github.com/login/oauth/authorize?redirect_uri=http://127.0.0.1:8000/auth/github/callback'));

        $user = $this->verifiedUser();

        $response = $this->actingAs($user)->get(route('integrations.connect', 'github'));

        $response->assertRedirect('https://github.com/login/oauth/authorize?redirect_uri=http://127.0.0.1:8000/auth/github/callback');
        $this->assertSame('integration', session('oauth_intent'));
        $this->assertSame('github', session('integration_provider'));
    }

    public function test_github_integration_redirect_preserves_project_context(): void
    {
        config()->set('services.github.client_id', 'github-client');
        config()->set('services.github.client_secret', 'github-secret');

        Socialite::shouldReceive('driver->redirectUrl->scopes->redirect')
            ->once()
            ->andReturn(redirect('https://github.com/login/oauth/authorize?redirect_uri=http://127.0.0.1:8000/auth/github/callback'));

        $user = $this->verifiedUser();
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Project Context',
            'color' => '#e8a325',
            'status' => 'active',
            'report_frequency' => 'weekly',
            'report_day' => 'friday',
        ]);

        $response = $this->actingAs($user)->get(route('integrations.connect', [
            'provider' => 'github',
            'project_id' => $project->id,
        ]));

        $response->assertRedirect('https://github.com/login/oauth/authorize?redirect_uri=http://127.0.0.1:8000/auth/github/callback');
        $this->assertSame((string) $project->id, (string) session('integration_project_id'));
    }

    public function test_github_integration_callback_creates_integration(): void
    {
        $user = $this->verifiedUser();

        $socialUser = new class
        {
            public string $token = 'token-123';

            public ?string $refreshToken = 'refresh-123';

            public function getId()
            {
                return 'github-user-1';
            }

            public function getName()
            {
                return 'GitHub User';
            }

            public function getNickname()
            {
                return 'ghuser';
            }

            public function getEmail()
            {
                return 'gh@example.com';
            }
        };

        Socialite::shouldReceive('driver->redirectUrl->user')
            ->once()
            ->andReturn($socialUser);

        $response = $this->actingAs($user)
            ->withSession([
                'oauth_intent' => 'integration',
                'integration_provider' => 'github',
                'integration_project_id' => null,
            ])
            ->get(route('social.callback', 'github'));

        $response->assertRedirect(route('integrations.index'));

        $integration = Integration::where('user_id', $user->id)->where('provider', 'github')->first();
        $this->assertNotNull($integration);
        $this->assertSame('github-user-1', $integration->provider_account_id);
        $this->assertNull($integration->resource_name);
    }

    public function test_github_integration_callback_preserves_selected_project(): void
    {
        $user = $this->verifiedUser();
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Integrated Project',
            'color' => '#e8a325',
            'status' => 'active',
            'report_frequency' => 'weekly',
            'report_day' => 'friday',
        ]);

        $socialUser = new class
        {
            public string $token = 'token-456';

            public ?string $refreshToken = 'refresh-456';

            public function getId()
            {
                return 'github-user-2';
            }

            public function getName()
            {
                return 'GitHub User 2';
            }

            public function getNickname()
            {
                return 'ghuser2';
            }

            public function getEmail()
            {
                return 'gh2@example.com';
            }
        };

        Socialite::shouldReceive('driver->redirectUrl->user')
            ->once()
            ->andReturn($socialUser);

        $response = $this->actingAs($user)
            ->withSession([
                'oauth_intent' => 'integration',
                'integration_provider' => 'github',
                'integration_project_id' => $project->id,
            ])
            ->get(route('social.callback', 'github'));

        $response->assertRedirect(route('integrations.index', ['project_id' => $project->id]));

        $integration = Integration::where('user_id', $user->id)
            ->where('provider', 'github')
            ->first();

        $this->assertNotNull($integration);
        $this->assertSame($project->id, $integration->project_id);
    }

    public function test_connected_github_integration_shows_repository_choices(): void
    {
        Http::fake([
            'https://api.github.com/user/repos*' => Http::response([
                ['id' => 42, 'full_name' => 'owner/repo-one', 'private' => false],
                ['id' => 43, 'full_name' => 'owner/repo-two', 'private' => true],
            ], 200),
        ]);

        $user = $this->verifiedUser();

        Integration::create([
            'user_id' => $user->id,
            'provider' => 'github',
            'provider_account_id' => 'github-user-1',
            'provider_account_name' => 'GitHub User',
            'access_token' => 'token-123',
            'active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('integrations.index'));

        $response->assertOk();
        $response->assertSee('Choose a repository');
        $response->assertSee('owner/repo-one');
        $response->assertSee('owner/repo-two');
    }

    public function test_user_can_select_github_repository_for_connected_integration(): void
    {
        Http::fake([
            'https://api.github.com/user/repos*' => Http::response([
                ['id' => 42, 'full_name' => 'owner/repo-one', 'private' => false],
                ['id' => 43, 'full_name' => 'owner/repo-two', 'private' => true],
            ], 200),
        ]);

        $user = $this->verifiedUser();
        $project = Project::create([
            'user_id' => $user->id,
            'name' => 'Assigned Project',
            'color' => '#e8a325',
            'status' => 'active',
            'report_frequency' => 'weekly',
            'report_day' => 'friday',
        ]);

        $integration = Integration::create([
            'user_id' => $user->id,
            'provider' => 'github',
            'provider_account_id' => 'github-user-1',
            'provider_account_name' => 'GitHub User',
            'access_token' => 'token-123',
            'active' => true,
        ]);

        $response = $this->actingAs($user)->patch(route('integrations.resource.update', $integration), [
            'resource_id' => '43',
            'project_id' => $project->id,
        ]);

        $response->assertRedirect();

        $integration->refresh();
        $this->assertSame('43', $integration->resource_id);
        $this->assertSame('owner/repo-two', $integration->resource_name);
        $this->assertSame($project->id, $integration->project_id);
    }

    public function test_same_user_can_keep_multiple_github_project_integrations(): void
    {
        $user = $this->verifiedUser();

        $projectOne = Project::create([
            'user_id' => $user->id,
            'name' => 'Project One',
            'color' => '#e8a325',
            'status' => 'active',
            'report_frequency' => 'weekly',
            'report_day' => 'friday',
        ]);

        $projectTwo = Project::create([
            'user_id' => $user->id,
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

        $this->assertSame(2, Integration::where('user_id', $user->id)->where('provider', 'github')->count());
        $this->assertDatabaseHas('integrations', [
            'user_id' => $user->id,
            'project_id' => $projectOne->id,
            'resource_name' => 'owner/repo-one',
        ]);
        $this->assertDatabaseHas('integrations', [
            'user_id' => $user->id,
            'project_id' => $projectTwo->id,
            'resource_name' => 'owner/repo-two',
        ]);
    }

    private function verifiedUser(): User
    {
        $user = User::create([
            'name' => 'Integration User',
            'email' => 'integration@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }
}
