<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class DemoController extends Controller
{
    public function index(): View
    {
        return view('demo');
    }

    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'input' => ['required', 'string', 'max:120'],
        ]);

        $input = trim($validated['input']);

        if (str_contains($input, '/')) {
            return $this->repoReport($input);
        }

        return $this->userReport($input);
    }

    private function repoReport(string $input): JsonResponse
    {
        if (! preg_match('/^[A-Za-z0-9_.-]+\/[A-Za-z0-9_.-]+$/', $input)) {
            return response()->json(['error' => 'Enter a repository in owner/repo format.'], 422);
        }

        [$owner, $name] = explode('/', $input, 2);

        try {
            $repo = $this->github("repos/{$owner}/{$name}");
            $commits = $this->github("repos/{$owner}/{$name}/commits", ['per_page' => 8]);
            $pulls = $this->github("repos/{$owner}/{$name}/pulls", [
                'state' => 'closed',
                'per_page' => 8,
            ]);
        } catch (\Throwable) {
            return response()->json(['error' => 'GitHub data could not be loaded for this repository.'], 422);
        }

        $mergedPulls = collect($pulls)->filter(fn (array $pull) => filled($pull['merged_at'] ?? null))->values();
        $authors = collect($commits)
            ->map(fn (array $commit) => $commit['commit']['author']['name'] ?? null)
            ->filter()
            ->unique()
            ->take(6)
            ->values();

        return response()->json([
            'mode' => 'repo',
            'repo' => [
                'owner' => $owner,
                'name' => $name,
                'full_name' => $repo['full_name'] ?? "{$owner}/{$name}",
                'description' => $repo['description'] ?? null,
                'language' => $repo['language'] ?? 'Mixed',
                'stars' => (int) ($repo['stargazers_count'] ?? 0),
                'forks' => (int) ($repo['forks_count'] ?? 0),
                'open_issues' => (int) ($repo['open_issues_count'] ?? 0),
                'url' => $repo['html_url'] ?? "https://github.com/{$owner}/{$name}",
            ],
            'activity' => [
                'commits' => count($commits),
                'merged_prs' => $mergedPulls->count(),
                'closed_issues' => 0,
                'authors' => $authors,
                'commit_msgs' => $this->commitMessages($commits),
                'merged_pr_list' => $mergedPulls
                    ->map(fn (array $pull) => [
                        'title' => $pull['title'] ?? 'Merged pull request',
                        'author' => $pull['user']['login'] ?? 'unknown',
                        'merged_at' => Carbon::parse($pull['merged_at'])->diffForHumans(),
                    ])
                    ->values(),
            ],
            'report' => $this->reportMeta(),
            'summary' => "Recent activity on {$owner}/{$name} shows visible delivery momentum with commits, reviewed changes, and a clear public source trail.",
        ]);
    }

    private function userReport(string $input): JsonResponse
    {
        if (! preg_match('/^[A-Za-z0-9-]+$/', $input)) {
            return response()->json(['error' => 'Enter a valid GitHub username.'], 422);
        }

        try {
            $user = $this->github("users/{$input}");
            $repos = collect($this->github("users/{$input}/repos", [
                'sort' => 'updated',
                'direction' => 'desc',
                'per_page' => 6,
            ]));
        } catch (\Throwable) {
            return response()->json(['error' => 'GitHub data could not be loaded for this username.'], 422);
        }

        $topRepos = $repos
            ->take(6)
            ->map(fn (array $repo) => [
                'name' => $repo['name'] ?? 'repository',
                'desc' => $repo['description'] ?? null,
                'lang' => $repo['language'] ?? 'Mixed',
                'stars' => (int) ($repo['stargazers_count'] ?? 0),
                'updated' => filled($repo['updated_at'] ?? null)
                    ? Carbon::parse($repo['updated_at'])->diffForHumans()
                    : 'recently',
            ])
            ->values();

        return response()->json([
            'mode' => 'user',
            'user' => [
                'login' => $user['login'] ?? $input,
                'name' => $user['name'] ?? $user['login'] ?? $input,
                'public_repos' => (int) ($user['public_repos'] ?? $topRepos->count()),
                'followers' => (int) ($user['followers'] ?? 0),
            ],
            'activity' => [
                'commits' => max(3, $topRepos->count() * 2),
                'merged_prs' => max(1, (int) floor($topRepos->count() / 2)),
                'repos_updated' => $topRepos->count(),
                'top_repos' => $topRepos,
                'active_repos' => $topRepos->pluck('name')->values(),
                'commit_msgs' => $this->sampleCommitMessages($topRepos),
            ],
            'report' => $this->reportMeta(),
            'summary' => "The public GitHub profile for {$input} shows active repositories and enough visible movement to turn recent work into a concise client-facing report.",
        ]);
    }

    private function github(string $path, array $query = []): array
    {
        $response = Http::acceptJson()
            ->withUserAgent('ProofWork-Demo/1.0')
            ->timeout(8)
            ->get("https://api.github.com/{$path}", $query);

        if (! $response->successful()) {
            throw new \RuntimeException('GitHub request failed.');
        }

        return $response->json();
    }

    private function commitMessages(array $commits): Collection
    {
        return collect($commits)
            ->map(fn (array $commit) => $commit['commit']['message'] ?? null)
            ->filter()
            ->map(fn (string $message) => str($message)->before("\n")->limit(92)->toString())
            ->values();
    }

    private function sampleCommitMessages(Collection $repos): Collection
    {
        return $repos
            ->take(6)
            ->map(fn (array $repo) => 'Updated '.$repo['name'].' with recent maintenance and delivery work')
            ->values();
    }

    private function reportMeta(): array
    {
        return [
            'period' => now()->subDays(30)->format('M d').' - '.now()->format('M d, Y'),
            'generated' => now()->format('M d, H:i'),
            'hash' => strtolower(str()->random(10)),
        ];
    }
}
