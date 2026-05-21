<?php

namespace Tests\Feature;

use App\Models\CustomerInvoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_payment_succeeded_creates_customer_invoice(): void
    {
        config()->set('cashier.webhook.secret', 'whsec_test_secret');

        $user = $this->userWithStripeId('cus_invoice_owner');
        $payload = $this->stripePayload('invoice.payment_succeeded', [
            'id' => 'in_paid_123',
            'customer' => $user->stripe_id,
            'amount_paid' => 2900,
            'currency' => 'usd',
            'status' => 'paid',
            'invoice_pdf' => 'https://pay.stripe.com/invoice.pdf',
            'hosted_invoice_url' => 'https://pay.stripe.com/invoice',
        ]);

        $response = $this->postSignedStripePayload($payload, [
            'Stripe-Signature' => $this->stripeSignature($payload),
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('customer_invoices', [
            'user_id' => $user->id,
            'stripe_invoice_id' => 'in_paid_123',
            'amount_paid' => 2900,
            'currency' => 'usd',
            'status' => 'paid',
        ]);
    }

    public function test_subscription_updated_syncs_user_plan_from_price(): void
    {
        config()->set('cashier.webhook.secret', 'whsec_test_secret');
        config()->set('proofwork.stripe_prices.pro', 'price_pro_test');

        $user = $this->userWithStripeId('cus_subscription_owner');
        $payload = $this->stripePayload('customer.subscription.updated', [
            'id' => 'sub_123',
            'customer' => $user->stripe_id,
            'items' => [
                'data' => [
                    ['price' => ['id' => 'price_pro_test']],
                ],
            ],
        ]);

        $response = $this->postSignedStripePayload($payload, [
            'Stripe-Signature' => $this->stripeSignature($payload),
        ]);

        $response->assertOk();
        $this->assertSame('pro', $user->fresh()->plan);
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        config()->set('cashier.webhook.secret', 'whsec_test_secret');

        $payload = $this->stripePayload('invoice.payment_succeeded', [
            'id' => 'in_invalid',
            'customer' => 'cus_missing',
        ]);

        $response = $this->postSignedStripePayload($payload, [
            'Stripe-Signature' => 't='.time().',v1=invalid',
        ]);

        $response->assertStatus(400);
        $this->assertSame(0, CustomerInvoice::count());
    }

    private function userWithStripeId(string $stripeId): User
    {
        return User::create([
            'name' => 'Stripe User',
            'email' => $stripeId.'@example.com',
            'password' => Hash::make('password123'),
            'plan' => 'free',
            'stripe_id' => $stripeId,
        ]);
    }

    private function stripePayload(string $type, array $object): string
    {
        return json_encode([
            'id' => 'evt_'.str_replace('.', '_', $type),
            'object' => 'event',
            'type' => $type,
            'data' => ['object' => $object],
        ], JSON_THROW_ON_ERROR);
    }

    private function postSignedStripePayload(string $payload, array $headers)
    {
        return $this->call(
            'POST',
            route('stripe.webhook'),
            [],
            [],
            [],
            array_merge([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_STRIPE_SIGNATURE' => $headers['Stripe-Signature'],
            ]),
            $payload
        );
    }

    private function stripeSignature(string $payload): string
    {
        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'whsec_test_secret');

        return "t={$timestamp},v1={$signature}";
    }
}
