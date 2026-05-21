<?php

namespace App\Services;

use App\Models\Integration;
use App\Models\Project;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReportGeneratorService
{
    public function generate(Project $project, Carbon $start, Carbon $end): Report
    {
        $start = $start->copy()->startOfDay();
        $end = $end->copy()->endOfDay();

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
                Log::warning("Integration pull failed [{$integration->provider}]: ".$e->getMessage());
            }
        }

        $report->update(['status' => 'ready']);

        return $report->fresh(['entries']);
    }

    private function pullGitHub(Report $report, Integration $integration, Carbon $start, Carbon $end): void
    {
        if (! $integration->access_token || ! $integration->resource_name) {
            return;
        }

        $repoFullName = $integration->resource_name;
        $headers = [
            'Authorization' => "token {$integration->access_token}",
            'Accept' => 'application/vnd.github+json',
            'User-Agent' => 'ProofWork/1.0',
        ];

        $commits = $this->fetchGitHubPages(
            "https://api.github.com/repos/{$repoFullName}/commits",
            $headers,
            [
                'since' => $start->toIso8601String(),
                'until' => $end->toIso8601String(),
                'per_page' => 100,
            ],
            3
        );

        foreach ($commits->take(250) as $commit) {
            $sha = (string) ($commit['sha'] ?? '');

            if ($sha === '') {
                continue;
            }

            $message = strtok($commit['commit']['message'] ?? 'Commit', "\n");
            $occurredAt = $commit['commit']['committer']['date']
                ?? $commit['commit']['author']['date']
                ?? now();

            $this->createReportEntryIfMissing($report, [
                'source' => 'github',
                'type' => 'commit',
                'title' => $message,
                'source_url' => $commit['html_url'] ?? null,
                'source_id' => $sha,
                'occurred_at' => $occurredAt,
                'metadata' => [
                    'sha' => substr($sha, 0, 7),
                    'author' => $commit['commit']['author']['name'] ?? '',
                    'repo' => $repoFullName,
                ],
            ]);
        }

        $pullRequests = $this->fetchGitHubPages(
            "https://api.github.com/repos/{$repoFullName}/pulls",
            $headers,
            [
                'state' => 'closed',
                'sort' => 'updated',
                'direction' => 'desc',
                'per_page' => 100,
            ],
            3
        );

        foreach ($pullRequests as $pr) {
            if (! ($pr['merged_at'] ?? null)) {
                continue;
            }

            $mergedAt = Carbon::parse($pr['merged_at']);

            if ($mergedAt->lt($start) || $mergedAt->gt($end)) {
                continue;
            }

            $number = (string) ($pr['number'] ?? '');

            if ($number === '') {
                continue;
            }

            $this->createReportEntryIfMissing($report, [
                'source' => 'github',
                'type' => 'pull_request',
                'title' => "PR merged: {$pr['title']}",
                'description' => filled($pr['body'] ?? null) ? str($pr['body'])->limit(200, '')->toString() : null,
                'source_url' => $pr['html_url'] ?? null,
                'source_id' => $number,
                'occurred_at' => $pr['merged_at'],
                'metadata' => [
                    'number' => $pr['number'],
                    'author' => $pr['user']['login'] ?? '',
                    'repo' => $repoFullName,
                ],
            ]);
        }
    }

    private function pullLinear(Report $report, Integration $integration, Carbon $start, Carbon $end): void
    {
        if (! $integration->access_token) {
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
                    'description' => filled($issue['description'] ?? null) ? str($issue['description'])->limit(200, '')->toString() : null,
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
        if (! $integration->access_token) {
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
                    'description' => filled($event['description'] ?? null) ? str($event['description'])->limit(200, '')->toString() : null,
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

    private function fetchGitHubPages(string $url, array $headers, array $query, int $pages): Collection
    {
        $results = collect();

        for ($page = 1; $page <= $pages; $page++) {
            $response = Http::withHeaders($headers)->get($url, array_merge($query, ['page' => $page]));

            if (! $response->successful()) {
                throw new \RuntimeException('Unable to fetch GitHub activity.');
            }

            $items = collect($response->json());

            if ($items->isEmpty()) {
                break;
            }

            $results = $results->concat($items);

            if ($items->count() < (int) ($query['per_page'] ?? 100)) {
                break;
            }
        }

        return $results->values();
    }

    private function createReportEntryIfMissing(Report $report, array $attributes): void
    {
        $exists = $report->entries()
            ->where('source', $attributes['source'])
            ->where('type', $attributes['type'])
            ->where('source_id', $attributes['source_id'] ?? null)
            ->exists();

        if ($exists) {
            return;
        }

        $report->entries()->create($attributes);
    }
}
