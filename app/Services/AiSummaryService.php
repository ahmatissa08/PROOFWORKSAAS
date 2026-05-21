<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiSummaryService
{
    public function summarize(Report $report): string
    {
        $apiKey = config('proofwork.anthropic_api_key');

        if (! $apiKey) {
            return $this->fallbackSummary($report);
        }

        $report->loadMissing('entries');
        $entries = $report->entries;

        if ($entries->isEmpty()) {
            return 'No activity recorded for this period.';
        }

        // Build a concise activity context for the summarizer.
        $context = $entries->map(function ($entry) {
            return "[{$entry->source}] {$entry->type}: {$entry->title}"
                .($entry->description ? " - {$entry->description}" : '');
        })->join("\n");

        $prompt = "You are writing a weekly proof-of-work summary for a freelancer to send to their client.
Based on the following activity log, write a clear, professional 2-3 sentence summary that highlights what was accomplished.
Be specific. Use active voice. Do NOT start with 'This week'.

Activity log:
{$context}

Write only the summary, nothing else.";

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-haiku-4-5-20251001',
                'max_tokens' => 300,
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ]);

            if ($response->successful()) {
                return $response->json('content.0.text', $this->fallbackSummary($report));
            }
        } catch (\Throwable $e) {
            Log::error('AI summary error: '.$e->getMessage());
        }

        return $this->fallbackSummary($report);
    }

    private function fallbackSummary(Report $report): string
    {
        $report->loadMissing('entries');
        $counts = $report->entries->groupBy('source')->map->count();
        $parts = [];

        foreach ($counts as $source => $count) {
            $parts[] = match ($source) {
                'github' => "{$count} GitHub ".($count > 1 ? 'activities' : 'activity'),
                'linear' => "{$count} task".($count > 1 ? 's' : '').' completed',
                'google_calendar' => "{$count} meeting".($count > 1 ? 's' : '').' logged',
                default => "{$count} {$source} ".($count > 1 ? 'entries' : 'entry'),
            };
        }

        if (empty($parts)) {
            return 'No activity recorded for this period.';
        }

        return ucfirst(implode(', ', $parts)).' during this period.';
    }
}
