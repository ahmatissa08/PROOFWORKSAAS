<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class IntegrationController extends Controller
{
    private array $providers = [
        'github' => [
            'label' => 'GitHub',
            'icon' => 'G',
            'desc' => 'Pull commits, PRs, and code reviews automatically.',
            'color' => '#f2f0eb',
            'scopes' => ['repo', 'read:user'],
        ],
        'google_calendar' => [
            'label' => 'Google Calendar',
            'icon' => 'C',
            'desc' => 'Log meetings, decisions, and action items.',
            'color' => '#4a9eff',
            'scopes' => ['https://www.googleapis.com/auth/calendar.readonly'],
        ],
        'linear' => [
            'label' => 'Linear',
            'icon' => 'L',
            'desc' => 'Sync completed tasks and closed issues.',
            'color' => '#a855f7',
            'scopes' => [],
        ],
        'notion' => [
            'label' => 'Notion',
            'icon' => 'N',
            'desc' => 'Pull updated pages and database entries.',
            'color' => '#f2f0eb',
            'scopes' => [],
        ],
    ];

    public function index()
    {
        $user = Auth::user();
        $allIntegrations = $user->integrations()->with('project')->orderBy('provider')->orderBy('created_at')->get();
        $projects = $user->projects()->orderBy('name')->get();
        $currentProject = null;
        $currentProjectId = request()->integer('project_id');

        if ($currentProjectId) {
            $currentProject = $user->projects()->find($currentProjectId);
        }

        $integrations = $currentProject
            ? $allIntegrations->where('project_id', $currentProject->id)->keyBy('provider')
            : $allIntegrations->groupBy('provider')->map(fn (Collection $items) => $items->first());

        $providers = collect($this->providers)->map(function (array $provider, string $key) {
            $provider['available'] = in_array($key, ['github', 'google_calendar'], true);
            $provider['configured'] = $this->providerIsConfigured($key);

            return $provider;
        })->all();

        $githubRepositories = collect();
        $githubRepositoryError = null;
        $githubConnections = $allIntegrations
            ->where('provider', 'github')
            ->whereNotNull('resource_name')
            ->values();

        $githubIntegration = $currentProject
            ? $allIntegrations->first(fn (Integration $integration) => $integration->provider === 'github' && $integration->project_id === $currentProject->id)
            : $allIntegrations->firstWhere('provider', 'github');

        if ($githubIntegration && $githubIntegration->access_token) {
            try {
                $githubRepositories = $this->listGitHubRepositories($githubIntegration->access_token);
            } catch (Throwable $e) {
                report($e);
                $githubRepositoryError = 'GitHub connected, but the repository list could not be loaded right now.';
            }
        }

        return view('app.integrations.index', compact(
            'integrations',
            'providers',
            'projects',
            'currentProject',
            'githubRepositories',
            'githubRepositoryError',
            'githubConnections'
        ));
    }

    public function connect(Request $request, string $provider)
    {
        abort_unless(isset($this->providers[$provider]), 404);

        if (!in_array($provider, ['github', 'google_calendar'], true)) {
            return back()->withErrors([
                'integration' => "{$this->providers[$provider]['label']} is not available yet.",
            ]);
        }

        if (!$this->providerIsConfigured($provider)) {
            return back()->withErrors([
                'integration' => "{$this->providers[$provider]['label']} is not configured yet.",
            ]);
        }

        session([
            'oauth_intent' => 'integration',
            'integration_provider' => $provider,
            'integration_project_id' => $request->query('project_id'),
        ]);

        return match ($provider) {
            'github' => Socialite::driver('github')
                ->redirectUrl(route('social.callback', 'github'))
                ->scopes(['repo', 'read:user', 'user:email'])
                ->redirect(),
            'google_calendar' => Socialite::driver('google')
                ->redirectUrl(route('social.callback', 'google'))
                ->scopes(['https://www.googleapis.com/auth/calendar.readonly'])
                ->with(['access_type' => 'offline', 'prompt' => 'consent'])
                ->redirect(),
        };
    }

    public function callback(Request $request, string $provider): RedirectResponse
    {
        $socialProvider = match ($provider) {
            'github' => 'github',
            'google_calendar' => 'google',
            default => abort(404),
        };

        return redirect()->route('social.callback', array_merge(
            ['provider' => $socialProvider],
            $request->query()
        ));
    }

    public function updateResource(Request $request, Integration $integration): RedirectResponse
    {
        $this->authorize('update', $integration);
        abort_unless($integration->provider === 'github', 404);

        $validated = $request->validate([
            'resource_id' => ['required', 'string'],
            'project_id' => ['nullable', 'integer'],
        ]);

        $projectId = null;

        if (filled($validated['project_id'] ?? null)) {
            $projectId = Auth::user()->projects()
                ->whereKey($validated['project_id'])
                ->value('id');

            if (!$projectId) {
                return back()->withErrors([
                    'project_id' => 'Selected project is invalid.',
                ]);
            }
        }

        $repository = $this->listGitHubRepositories($integration->access_token)
            ->firstWhere('id', (string) $validated['resource_id']);

        if (!$repository) {
            return back()->withErrors([
                'resource_id' => 'Selected GitHub repository is invalid or no longer accessible.',
            ]);
        }

        $targetIntegration = $integration;

        if ($projectId) {
            $existingProjectIntegration = Integration::query()
                ->where('user_id', Auth::id())
                ->where('provider', 'github')
                ->where('project_id', $projectId)
                ->whereKeyNot($integration->id)
                ->first();

            if ($existingProjectIntegration) {
                $targetIntegration = $existingProjectIntegration;
            }
        }

        $targetIntegration->update([
            'project_id' => $projectId,
            'provider_account_id' => $integration->provider_account_id,
            'provider_account_name' => $integration->provider_account_name,
            'resource_id' => $repository['id'],
            'resource_name' => $repository['full_name'],
            'access_token' => $integration->access_token,
            'refresh_token' => $integration->refresh_token,
            'active' => true,
        ]);

        if ($targetIntegration->id !== $integration->id) {
            $integration->delete();
        }

        return back()->with('success', 'GitHub repository linked to project.');
    }

    public function destroy(Integration $integration)
    {
        $this->authorize('delete', $integration);
        $integration->delete();

        return back()->with('success', 'Integration removed.');
    }

    private function providerIsConfigured(string $provider): bool
    {
        return match ($provider) {
            'github' => filled(config('services.github.client_id')) && filled(config('services.github.client_secret')),
            'google_calendar' => filled(config('services.google.client_id')) && filled(config('services.google.client_secret')),
            'linear' => filled(config('services.linear.client_id')) && filled(config('services.linear.client_secret')),
            default => false,
        };
    }

    private function listGitHubRepositories(?string $token): Collection
    {
        if (!$token) {
            return collect();
        }

        $response = Http::withHeaders([
            'Authorization' => "token {$token}",
            'Accept' => 'application/vnd.github+json',
            'User-Agent' => 'ProofWork/1.0',
        ])->get('https://api.github.com/user/repos', [
            'sort' => 'updated',
            'direction' => 'desc',
            'per_page' => 100,
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException('Unable to fetch GitHub repositories.');
        }

        return collect($response->json())
            ->filter(fn (array $repo) => filled($repo['id'] ?? null) && filled($repo['full_name'] ?? null))
            ->map(fn (array $repo) => [
                'id' => (string) $repo['id'],
                'full_name' => $repo['full_name'],
                'private' => (bool) ($repo['private'] ?? false),
            ])
            ->values();
    }
}
