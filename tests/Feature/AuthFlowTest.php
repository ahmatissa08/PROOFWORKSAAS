<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_is_redirected_to_verification_notice(): void
    {
        Notification::fake();

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
        Notification::assertSentTo($user, VerifyEmail::class);
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
