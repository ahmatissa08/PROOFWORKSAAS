<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Services\AiSummaryService;
use App\Services\GmailApiEmailService;
use App\Services\ReportGeneratorService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateWeeklyReports extends Command
{
    protected $signature = 'proofwork:weekly-reports';

    protected $description = 'Auto-generate weekly reports for all active projects';

    public function handle(
        ReportGeneratorService $generator,
        AiSummaryService $ai,
        GmailApiEmailService $emailService
    ): int {
        $today = now()->dayOfWeekIso; // 1=Mon...7=Sun
        $dayNames = ['', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $todayName = $dayNames[$today] ?? 'friday';

        // Find projects whose report day matches today
        $projects = Project::where('status', 'active')
            ->where('auto_report', true)
            ->where('report_day', $todayName)
            ->with(['integrations', 'client', 'user'])
            ->get();

        $this->info("Found {$projects->count()} projects due for a report today ({$todayName}).");

        foreach ($projects as $project) {
            try {
                $end = Carbon::today();
                $start = match ($project->report_frequency) {
                    'biweekly' => Carbon::today()->subDays(13),
                    'monthly' => Carbon::today()->subDays(29),
                    default => Carbon::today()->subDays(6), // weekly
                };

                $this->line("Generating report for: {$project->name}");

                $report = $generator->generate($project, $start, $end);

                // AI summary
                try {
                    $summary = $ai->summarize($report);
                    $report->update(['ai_summary' => $summary, 'status' => 'ready']);
                } catch (\Throwable $e) {
                    Log::warning("AI summary failed for project {$project->id}: ".$e->getMessage());
                    $report->update(['status' => 'ready']);
                }

                // Auto-send to client
                if ($project->auto_send && $project->client?->email && $project->user->isPro()) {
                    $report->loadMissing(['user', 'client', 'entries']);
                    $emailService->send(
                        $project->client->email,
                        "Your weekly update from {$report->user->name} - {$report->periodLabel()}",
                        view('emails.report-shared', compact('report'))->render(),
                        "Your proof of work report is ready:\n\n{$report->shareUrl()}"
                    );
                    $report->update(['status' => 'sent', 'sent_at' => now()]);
                    $this->line("  → Sent to {$project->client->email}");
                }

                $this->info("  ✓ Report generated: #{$report->id}");

            } catch (\Throwable $e) {
                Log::error("Weekly report failed for project {$project->id}: ".$e->getMessage());
                $this->error("  ✗ Failed: {$e->getMessage()}");
            }
        }

        $this->info('Done.');

        return Command::SUCCESS;
    }
}
