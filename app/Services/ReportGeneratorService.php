<?php

namespace App\Services;

use App\Models\Integration;
use App\Models\Project;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReportGeneratorService
{
    public function generate(Project $project, Carbon $start, Carbon $end): Report
    {
        $report = Report::create([
            'user_id' => $project->user_id,
            'project_id' => $project->id,
            'client_id' => $project->client_id,
            'title' => "Weekly Report - {$project->name}",
            'period_start' => $start,
            'period_end' => $end,
            'status' => 'draft',
        ]);

        $integrations = $project->integrations()->where('active', true)->get();

        foreach ($integrations as $integration) {
            try {
                match ($integration->provider) {
                    'github' => $this->pullGitHub($report, $integration, $start, $end),
                    'linear' => $this->pullLinear($report, $integration, $start, $end),
                    'google_calendar' => $this->pullCalendar($report, $integration, $start, $end),
                    default => null,
                };
            } catch (\Throwable $e) {
                Log::warning("Integration pull failed [{$integration->provider}]: " . $e->getMessage());
            }
        }

        $report->update(['status' => 'ready']);

        return $report->fresh(['entries']);
    }

    private function pullGitHub(Report $report, Integration $integration, Carbon $start, Carbon $end): void
    {
        if (!$integration->access_token || !$integration->resource_name) {
            return;
        }

        $repoFullName = $integration->resource_name;
        $headers = [
            'Authorization' => "token {$integration->access_token}",
            'User-Agent' => 'ProofWork/1.0',
        ];

        $commitsRes = Http::withHeaders($headers)
            ->get("https://api.github.com/repos/{$repoFullName}/commits", [
                'since' => $start->toIso8601String(),
                'until' => $end->toIso8601String(),
                'per_page' => 50,
            ]);

        if ($commitsRes->successful()) {
            $commits = $commitsRes->json();

            foreach (array_slice($commits, 0, 20) as $commit) {
                $message = strtok($commit['commit']['message'] ?? 'Commit', "\n");

                $report->entries()->create([
                    'source' => 'github',
                    'type' => 'commit',
                    'title' => $message,
                    'source_url' => $commit['html_url'] ?? null,
                    'source_id' => $commit['sha'] ?? null,
                    'occurred_at' => $commit['commit']['author']['date'] ?? now(),
                    'metadata' => [
                        'sha' => substr($commit['sha'] ?? '', 0, 7),
                        'author' => $commit['commit']['author']['name'] ?? '',
                        'repo' => $repoFullName,
                    ],
                ]);
            }
        }

        $prsRes = Http::withHeaders($headers)
            ->get("https://api.github.com/repos/{$repoFullName}/pulls", [
                'state' => 'closed',
                'per_page' => 20,
                'sort' => 'updated',
            ]);

        if ($prsRes->successful()) {
            foreach ($prsRes->json() as $pr) {
                if (!($pr['merged_at'] ?? null)) {
                    continue;
                }

                $mergedAt = Carbon::parse($pr['merged_at']);

                if (!$mergedAt->between($start, $end)) {
                    continue;
                }

                $report->entries()->create([
                    'source' => 'github',
                    'type' => 'pull_request',
                    'title' => "PR merged: {$pr['title']}",
                    'description' => $pr['body'] ? substr($pr['body'], 0, 200) : null,
                    'source_url' => $pr['html_url'],
                    'source_id' => (string) $pr['number'],
                    'occurred_at' => $pr['merged_at'],
                    'metadata' => [
                        'number' => $pr['number'],
                        'author' => $pr['user']['login'] ?? '',
                    ],
                ]);
            }
        }
    }

    private function pullLinear(Report $report, Integration $integration, Carbon $start, Carbon $end): void
    {
        if (!$integration->access_token) {
            return;
        }

        $query = <<<GQL
        query {
          issues(filter: {
            completedAt: { gte: "{$start->toIso8601String()}", lte: "{$end->toIso8601String()}" }
            state: { type: { eq: "completed" } }
          }, first: 50) {
            nodes {
              id title description url completedAt
              assignee { name }
              labels { nodes { name } }
            }
          }
        }
        GQL;

        $res = Http::withHeaders([
            'Authorization' => $integration->access_token,
            'Content-Type' => 'application/json',
        ])->post('https://api.linear.app/graphql', ['query' => $query]);

        if ($res->successful()) {
            $issues = $res->json('data.issues.nodes', []);

            foreach ($issues as $issue) {
                $report->entries()->create([
                    'source' => 'linear',
                    'type' => 'task',
                    'title' => $issue['title'],
                    'description' => $issue['description'] ? substr($issue['description'], 0, 200) : null,
                    'source_url' => $issue['url'] ?? null,
                    'source_id' => $issue['id'],
                    'occurred_at' => $issue['completedAt'] ?? now(),
                    'metadata' => [
                        'assignee' => $issue['assignee']['name'] ?? null,
                        'labels' => collect($issue['labels']['nodes'] ?? [])->pluck('name')->toArray(),
                    ],
                ]);
            }
        }
    }

    private function pullCalendar(Report $report, Integration $integration, Carbon $start, Carbon $end): void
    {
        if (!$integration->access_token) {
            return;
        }

        $res = Http::withToken($integration->access_token)
            ->get('https://www.googleapis.com/calendar/v3/calendars/primary/events', [
                'timeMin' => $start->toRfc3339String(),
                'timeMax' => $end->toRfc3339String(),
                'singleEvents' => 'true',
                'orderBy' => 'startTime',
                'maxResults' => 30,
            ]);

        if ($res->successful()) {
            $events = $res->json('items', []);

            foreach ($events as $event) {
                if (($event['status'] ?? '') === 'cancelled') {
                    continue;
                }

                $report->entries()->create([
                    'source' => 'google_calendar',
                    'type' => 'meeting',
                    'title' => $event['summary'] ?? 'Meeting',
                    'description' => $event['description'] ? substr($event['description'], 0, 200) : null,
                    'source_url' => $event['htmlLink'] ?? null,
                    'source_id' => $event['id'],
                    'occurred_at' => $event['start']['dateTime'] ?? $event['start']['date'] ?? now(),
                    'metadata' => [
                        'attendees' => count($event['attendees'] ?? []),
                        'location' => $event['location'] ?? null,
                    ],
                ]);
            }
        }
    }
}
