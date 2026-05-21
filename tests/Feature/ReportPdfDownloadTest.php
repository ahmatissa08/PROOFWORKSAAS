<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use App\Services\ReportPdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReportPdfDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_download_report_pdf()
    {
        [$user, $report] = $this->reportFixture();

        $response = $this->actingAs($user)
            ->get(route('reports.download', $report));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition');
    }

    public function test_pdf_contains_digital_signature()
    {
        [, $report] = $this->reportFixture('signed-report-owner@example.com');

        $path = app(ReportPdfService::class)->generate($report);

        try {
            $content = file_get_contents($path);

            $this->assertStringContainsString('/Type /Sig', $content);
            $this->assertStringContainsString('ProofWork', $content);
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    public function test_unauthorized_user_cannot_download_pdf()
    {
        $user = $this->verifiedUser('pdf-outsider@example.com');
        [, $report] = $this->reportFixture('pdf-owner@example.com');

        $response = $this->actingAs($user)
            ->get(route('reports.download', $report));

        $response->assertStatus(403);
    }

    private function reportFixture(string $email = 'pdf-user@example.com'): array
    {
        $user = $this->verifiedUser($email);

        $client = Client::create([
            'user_id' => $user->id,
            'name' => 'PDF Client',
        ]);

        $project = Project::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'name' => 'PDF Project',
            'color' => '#e8a325',
            'status' => 'active',
            'report_frequency' => 'weekly',
            'report_day' => 'friday',
        ]);

        $report = $user->reports()->create([
            'project_id' => $project->id,
            'client_id' => $client->id,
            'title' => 'Signed PDF Report',
            'period_start' => now()->subWeek()->startOfWeek(),
            'period_end' => now()->subWeek()->endOfWeek(),
            'status' => 'ready',
            'ai_summary' => 'A concise summary for the PDF.',
        ]);

        $report->entries()->create([
            'source' => 'manual',
            'type' => 'task',
            'title' => 'Prepared signed report',
            'occurred_at' => now()->subDay(),
        ]);

        return [$user, $report];
    }

    private function verifiedUser(string $email): User
    {
        $user = User::create([
            'name' => 'PDF User',
            'email' => $email,
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }
}
