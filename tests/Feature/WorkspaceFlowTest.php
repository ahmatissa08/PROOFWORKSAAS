<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WorkspaceFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_manage_clients_projects_reports_and_settings(): void
    {
        config([
            'services.gmail_api.client_id' => 'test-client-id',
            'services.gmail_api.client_secret' => 'test-client-secret',
            'services.gmail_api.refresh_token' => 'test-refresh-token',
            'services.gmail_api.from' => 'ProofWork <hello@gmail.com>',
        ]);

        Http::fake([
            'https://oauth2.googleapis.com/token' => Http::response(['access_token' => 'test-access-token'], 200),
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/send' => Http::response(['id' => 'gmail-message'], 200),
        ]);

        $user = $this->verifiedUser();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();

        $this->actingAs($user)
            ->patch(route('settings.profile'), [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'timezone' => 'Africa/Casablanca',
            ])
            ->assertRedirect();

        $user->refresh();
        $this->assertSame('Updated Name', $user->name);
        $this->assertSame('updated@example.com', $user->email);
        $this->assertSame('Africa/Casablanca', $user->timezone);

        $this->actingAs($user)
            ->patch(route('settings.notifications'), [
                'report_generated' => '1',
                'report_viewed' => '0',
                'weekly_digest' => '1',
            ])
            ->assertRedirect();

        $user->refresh();
        $this->assertTrue($user->notification_preferences['report_generated']);
        $this->assertFalse($user->notification_preferences['report_viewed']);

        $this->actingAs($user)
            ->patch(route('settings.password'), [
                'current_password' => 'password123',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ])
            ->assertRedirect();

        $user->refresh();
        $this->assertTrue(Hash::check('NewPassword123!', $user->password));

        $this->actingAs($user)
            ->post(route('clients.store'), [
                'name' => 'Acme',
                'email' => 'client@example.com',
                'company' => 'Acme Inc',
                'avatar_color' => '#e8a325',
                'notes' => 'Important client',
            ])
            ->assertRedirect();

        $client = Client::where('name', 'Acme')->firstOrFail();

        $this->actingAs($user)->get(route('clients.show', $client))->assertOk();

        $this->actingAs($user)
            ->patch(route('clients.update', $client), [
                'name' => 'Acme Updated',
                'email' => 'client@example.com',
                'company' => 'Acme Inc',
                'avatar_color' => '#4a9eff',
                'notes' => 'Updated notes',
            ])
            ->assertRedirect(route('clients.show', $client));

        $client->refresh();
        $this->assertSame('Acme Updated', $client->name);

        $this->actingAs($user)
            ->post(route('projects.store'), [
                'name' => 'Main Project',
                'description' => 'Project description',
                'client_id' => $client->id,
                'color' => '#e8a325',
                'report_frequency' => 'weekly',
                'report_day' => 'friday',
                'auto_send' => '1',
            ])
            ->assertRedirect();

        $project = Project::where('name', 'Main Project')->firstOrFail();
        $this->assertSame($client->id, $project->client_id);
        $this->assertFalse($project->auto_send);

        $this->actingAs($user)->get(route('projects.show', $project))->assertOk();
        $this->actingAs($user)->get(route('projects.edit', $project))->assertOk();

        $this->actingAs($user)
            ->patch(route('projects.update', $project), [
                'name' => 'Main Project Updated',
                'description' => 'Updated description',
                'client_id' => $client->id,
                'color' => '#27c93f',
                'report_frequency' => 'monthly',
                'report_day' => 'monday',
                'status' => 'paused',
                'auto_send' => '1',
            ])
            ->assertRedirect(route('projects.show', $project));

        $project->refresh();
        $this->assertSame('Main Project Updated', $project->name);
        $this->assertSame('paused', $project->status);

        $report = Report::create([
            'user_id' => $user->id,
            'project_id' => $project->id,
            'client_id' => $client->id,
            'title' => 'Weekly Report',
            'period_start' => now()->subWeek()->startOfWeek(),
            'period_end' => now()->subWeek()->endOfWeek(),
            'status' => 'draft',
        ]);

        $this->actingAs($user)->get(route('reports.show', $report))->assertOk();
        $this->actingAs($user)->get(route('reports.edit', $report))->assertOk();

        $this->actingAs($user)
            ->patch(route('reports.update', $report), [
                'title' => 'Weekly Report Updated',
                'ai_summary' => 'Summary body',
                'status' => 'ready',
            ])
            ->assertRedirect(route('reports.show', $report));

        $report->refresh();
        $this->assertSame('Weekly Report Updated', $report->title);
        $this->assertSame('ready', $report->status);

        $this->actingAs($user)
            ->post(route('reports.entries.add', $report), [
                'title' => 'Implemented feature',
                'description' => 'Added core functionality',
                'source' => 'manual',
                'type' => 'task',
                'source_url' => 'https://example.com/task',
                'occurred_at' => now()->format('Y-m-d H:i:s'),
            ])
            ->assertRedirect();

        $entry = $report->entries()->firstOrFail();

        $this->actingAs($user)
            ->delete(route('reports.entries.delete', [$report, $entry]))
            ->assertRedirect();

        $this->assertDatabaseMissing('report_entries', ['id' => $entry->id]);

        $this->actingAs($user)
            ->post(route('reports.send', $report))
            ->assertRedirect();

        Http::assertSent(fn ($request) => $request->url() === 'https://gmail.googleapis.com/gmail/v1/users/me/messages/send'
            && $request->hasHeader('Authorization', 'Bearer test-access-token')
            && filled($request['raw']));

        $report->refresh();
        $this->assertSame('sent', $report->status);
        $this->assertNotNull($report->sent_at);
    }

    private function verifiedUser(): User
    {
        $user = User::create([
            'name' => 'Workspace User',
            'email' => 'workspace@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
            'timezone' => 'UTC',
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }
}
