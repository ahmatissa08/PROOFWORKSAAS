<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use App\Models\SocialAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirect(string $provider)
    {
        abort_unless(in_array($provider, ['github', 'google']), 404);

        return match ($provider) {
            'github' => Socialite::driver('github')
                ->scopes(['read:user', 'user:email'])
                ->redirectUrl(route('social.callback', 'github'))
                ->redirect(),
            'google' => Socialite::driver('google')
                ->redirectUrl(route('social.callback', 'google'))
                ->redirect(),
        };
    }

    public function callback(string $provider)
    {
        abort_unless(in_array($provider, ['github', 'google']), 404);

        try {
            $socialUser = match ($provider) {
                'github' => Socialite::driver('github')
                    ->redirectUrl(route('social.callback', 'github'))
                    ->user(),
                'google' => Socialite::driver('google')
                    ->redirectUrl(route('social.callback', 'google'))
                    ->user(),
            };
        } catch (Throwable $e) {
            return redirect()->route('login')->withErrors(['social' => 'OAuth failed. Please try again.']);
        }

        $email = $socialUser->getEmail();

        if (! $email) {
            return redirect()->route('login')->withErrors([
                'social' => 'This provider did not return an email address. Allow email access and try again.',
            ]);
        }

        if (session('oauth_intent') === 'integration') {
            return $this->handleIntegrationCallback($provider, $socialUser);
        }

        // Find existing social account
        $account = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($account) {
            // Always ensure email is verified for OAuth users
            $account->user->forceFill(['email_verified_at' => now()])->save();
            $account->update([
                'access_token' => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken,
            ]);
            Auth::login($account->user);

            return redirect()->intended(route('dashboard'));
        }

        // Find user by email or create new one
        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                'email' => $email,
                'avatar' => $socialUser->getAvatar(),
                'plan' => 'free',
                'trial_ends_at' => Carbon::now()->addDays(14),
                'email_verified_at' => now(),
                'password' => Str::random(32),
            ]);
        } else {
            // Always verify email for OAuth users
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        // Link social account
        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'access_token' => $socialUser->token,
            'refresh_token' => $socialUser->refreshToken,
        ]);

        Auth::login($user);

        return redirect()->intended(route('dashboard'));
    }

    private function handleIntegrationCallback(string $provider, object $socialUser)
    {
        $user = Auth::user();
        $integrationProvider = session('integration_provider', $provider);
        $projectId = session('integration_project_id');

        if (! $user) {
            session()->forget(['oauth_intent', 'integration_provider', 'integration_project_id']);

            return redirect()->route('login')->withErrors([
                'social' => 'You must be signed in before connecting an integration.',
            ]);
        }

        try {
            $existingIntegration = Integration::query()
                ->where('user_id', $user->id)
                ->where('provider', $integrationProvider)
                ->when($integrationProvider === 'github', function ($query) use ($projectId) {
                    $query->where('project_id', $projectId);
                })
                ->first();

            $resource = match ($integrationProvider) {
                'github' => [
                    'id' => $existingIntegration?->resource_id,
                    'name' => $existingIntegration?->resource_name,
                ],
                'google_calendar' => ['id' => 'primary', 'name' => 'Primary calendar'],
                default => ['id' => null, 'name' => null],
            };

            Integration::updateOrCreate(
                array_filter([
                    'user_id' => $user->id,
                    'provider' => $integrationProvider,
                    'project_id' => $integrationProvider === 'github' ? $projectId : null,
                ], fn ($value, $key) => ! ($key === 'project_id' && $integrationProvider !== 'github'), ARRAY_FILTER_USE_BOTH),
                [
                    'project_id' => $projectId,
                    'provider_account_id' => $socialUser->getId(),
                    'provider_account_name' => $socialUser->getName() ?? $socialUser->getNickname() ?? $socialUser->getEmail(),
                    'resource_id' => $resource['id'],
                    'resource_name' => $resource['name'],
                    'access_token' => $socialUser->token,
                    'refresh_token' => $socialUser->refreshToken,
                    'active' => true,
                ]
            );
        } catch (Throwable $e) {
            session()->forget(['oauth_intent', 'integration_provider', 'integration_project_id']);

            return redirect()->route('integrations.index')->withErrors([
                'integration' => 'Integration failed: '.$e->getMessage(),
            ]);
        }

        session()->forget(['oauth_intent', 'integration_provider', 'integration_project_id']);

        if ($integrationProvider === 'github') {
            return redirect()->route('integrations.index', array_filter([
                'project_id' => $projectId,
            ]))
                ->with('success', 'GitHub connected. Choose the repository to track.');
        }

        return redirect()->route('integrations.index')
            ->with('success', ucfirst(str_replace('_', ' ', $integrationProvider)).' connected successfully.');
    }
}
