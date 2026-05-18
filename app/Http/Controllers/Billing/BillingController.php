<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Throwable;

class BillingController extends Controller
{
    // Pricing page
    public function plans()
    {
        $user = Auth::user();

        return view('billing.plans', compact('user'));
    }

    // Create Stripe Checkout session
    public function checkout(Request $request)
    {
        $request->validate([
            'plan' => ['required', 'in:pro,agency'],
        ]);

        $user = Auth::user();
        $prices = config('proofwork.stripe_prices');
        $price = $prices[$request->plan] ?? null;

        if (!$price) {
            abort(400, 'Invalid plan.');
        }

        if (!$this->stripeIsConfigured()) {
            return back()->withErrors([
                'billing' => 'Stripe is not configured yet. Add valid Stripe keys and prices before checkout.',
            ]);
        }

        try {
            return $user->newSubscription('default', $price)
                ->trialDays(14)
                ->allowPromotionCodes()
                ->checkout([
                    'success_url' => route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('billing.plans'),
                    'metadata' => ['plan' => $request->plan],
                ]);
        } catch (IncompletePayment $e) {
            return redirect()->route('cashier.payment', [$e->payment->id, 'redirect' => route('billing.success')]);
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'billing' => 'Checkout could not be started. Verify Stripe configuration and try again.',
            ]);
        }
    }

    // Post-checkout success
    public function success(Request $request)
    {
        return view('billing.success');
    }

    // Stripe Customer Portal (manage subscription)
    public function portal()
    {
        $user = Auth::user();

        return $user->redirectToBillingPortal(route('billing.manage'));
    }

    // Billing management page
    public function manage()
    {
        $user = Auth::user();
        $subscription = $user->subscription('default');
        $invoices = $user->invoices();

        return view('billing.manage', compact('user', 'subscription', 'invoices'));
    }

    // Stripe webhook handler
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('cashier.webhook.secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Throwable $e) {
            return response('Webhook signature verification failed.', 400);
        }

        match ($event->type) {
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($event->data->object),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event->data->object),
            'invoice.payment_succeeded' => $this->handleInvoicePaid($event->data->object),
            default => null,
        };

        return response('OK', 200);
    }

    private function handleSubscriptionUpdated($subscription): void
    {
        $user = User::where('stripe_id', $subscription->customer)->first();

        if (!$user) {
            return;
        }

        // Sync plan from Stripe price.
        $prices = config('proofwork.stripe_prices');
        $plan = array_search($subscription->items->data[0]->price->id ?? '', $prices, true);

        if ($plan) {
            $user->update(['plan' => $plan]);
        }
    }

    private function handleSubscriptionDeleted($subscription): void
    {
        $user = User::where('stripe_id', $subscription->customer)->first();

        if ($user) {
            $user->update(['plan' => 'free']);
        }
    }

    private function handleInvoicePaid($invoice): void
    {
        $user = User::where('stripe_id', $invoice->customer)->first();

        if (!$user) {
            return;
        }

        \App\Models\CustomerInvoice::updateOrCreate(
            ['stripe_invoice_id' => $invoice->id],
            [
                'user_id' => $user->id,
                'amount_paid' => $invoice->amount_paid,
                'currency' => $invoice->currency,
                'status' => $invoice->status,
                'invoice_pdf' => $invoice->invoice_pdf,
                'hosted_invoice_url' => $invoice->hosted_invoice_url,
                'paid_at' => now(),
            ]
        );
    }

    private function stripeIsConfigured(): bool
    {
        $secret = (string) config('cashier.secret');
        $prices = array_filter((array) config('proofwork.stripe_prices'));

        return $secret !== '' && !str_contains($secret, 'xxx') && count($prices) >= 2;
    }
}
