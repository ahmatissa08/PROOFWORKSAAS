<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiSummaryService
{
    public function summarize(Report $report): string
    {
        $apiKey = config('proofwork.openai_api_key');

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

        $systemPrompt = "You are a senior technical consultant writing weekly status reports for enterprise clients.

Rules:
- Write 2-3 tight sentences maximum
- Lead with outcomes and business impact, not activities
- Use active voice, present tense
- No filler words: 'worked on', 'focused on', 'spent time'
- No meta-commentary about 'this week' or 'the period'
- Be specific: name features, systems, or deliverables
- Tone: confident, precise, no fluff

Bad: 'I worked on fixing bugs and improving the system.'
Good: 'Resolved critical auth bypass in OAuth flow and reduced API latency by 40%.'";

        $userPrompt = "Activity log:\n{$context}\n\nWrite the summary. Only the summary text, nothing else.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'max_tokens' => 200,
                'temperature' => 0.5,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');

                if (! empty($content)) {
                    return trim($content);
                }
            }

            Log::warning('OpenAI API error', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
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
