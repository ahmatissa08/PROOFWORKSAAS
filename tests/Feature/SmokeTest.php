<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_root_shows_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('ProofWork');
        $response->assertSee(route('register'), false);
        $response->assertSee(route('login'), false);
    }

    public function test_authenticated_root_redirects_to_dashboard(): void
    {
        $user = User::create([
            'name' => 'Verified User',
            'email' => 'verified-root@example.com',
            'password' => Hash::make('StrongPass123!'),
            'plan' => 'free',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_admin_entry_redirects_to_login_for_guests(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('admin.login'));
        $this->get(route('admin.login'))->assertOk()->assertSee('ProofWork');
    }

    public function test_contact_page_includes_csrf_token_for_ajax_form(): void
    {
        $response = $this->get('/contact');

        $response->assertOk();
        $response->assertSee('name="csrf-token"', false);
        $response->assertSee(route('contact.store'), false);
    }
}
