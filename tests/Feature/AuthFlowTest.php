<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_is_redirected_to_verification_notice(): void
    {
        config([
            'services.resend.key' => 'test-resend-key',
            'services.resend.from' => 'ProofWork <hello@example.com>',
        ]);

        Http::fake([
            'https://api.resend.com/emails' => Http::response(['id' => 'email_test'], 200),
        ]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'StrongPass123!',
            'password_confirmation' => 'StrongPass123!',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $this->assertAuthenticated();

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        Http::assertSent(fn ($request) => $request->url() === 'https://api.resend.com/emails'
            && $request->hasHeader('Authorization', 'Bearer test-resend-key')
            && $request['to'] === ['test@example.com']
            && str_contains($request['html'], (string) $user->id));
    }

    public function test_user_cannot_register_with_weak_password(): void
    {
        $response = $this->from('/register')->post('/register', [
            'name' => 'Weak Password',
            'email' => 'weak@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'weak@example.com']);
    }

    public function test_user_can_login_and_reach_dashboard_when_verified(): void
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $response = $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_verified_user_is_redirected_away_from_verification_notice(): void
    {
        $user = User::create([
            'name' => 'Verified User',
            'email' => 'verified@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_user_can_resend_verification_email_via_resend(): void
    {
        config([
            'services.resend.key' => 'test-resend-key',
            'services.resend.from' => 'ProofWork <hello@example.com>',
        ]);

        Http::fake([
            'https://api.resend.com/emails' => Http::response(['id' => 'email_test'], 200),
        ]);

        $user = User::create([
            'name' => 'Needs Verification',
            'email' => 'resend-verification@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'verification-link-sent');
        Http::assertSent(fn ($request) => $request->url() === 'https://api.resend.com/emails'
            && $request['to'] === ['resend-verification@example.com']);
    }

    public function test_user_can_resend_verification_email_via_gmail_api(): void
    {
        config([
            'services.verification_email.provider' => 'gmail_api',
            'services.gmail_api.client_id' => 'test-client-id',
            'services.gmail_api.client_secret' => 'test-client-secret',
            'services.gmail_api.refresh_token' => 'test-refresh-token',
            'services.gmail_api.from' => 'ProofWork <hello@gmail.com>',
        ]);

        Http::fake([
            'https://oauth2.googleapis.com/token' => Http::response(['access_token' => 'test-access-token'], 200),
            'https://gmail.googleapis.com/gmail/v1/users/me/messages/send' => Http::response(['id' => 'gmail_message'], 200),
        ]);

        $user = User::create([
            'name' => 'Needs Verification',
            'email' => 'gmail-verification@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'verification-link-sent');
        Http::assertSent(fn ($request) => $request->url() === 'https://oauth2.googleapis.com/token'
            && $request['grant_type'] === 'refresh_token');
        Http::assertSent(fn ($request) => $request->url() === 'https://gmail.googleapis.com/gmail/v1/users/me/messages/send'
            && $request->hasHeader('Authorization', 'Bearer test-access-token')
            && filled($request['raw']));
    }

    public function test_resend_verification_shows_warning_when_email_provider_fails(): void
    {
        config([
            'services.resend.key' => 'test-resend-key',
            'services.resend.from' => 'ProofWork <hello@example.com>',
        ]);

        Http::fake([
            'https://api.resend.com/emails' => Http::response(['message' => 'domain is not verified'], 403),
        ]);

        $user = User::create([
            'name' => 'Needs Verification',
            'email' => 'resend-fails@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertRedirect();
        $response->assertSessionHas('warning');
    }

    public function test_user_can_verify_email_from_signed_link_and_is_redirected_to_onboarding(): void
    {
        $user = User::create([
            'name' => 'Needs Verification',
            'email' => 'needs-verification@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect(route('onboarding'));
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
