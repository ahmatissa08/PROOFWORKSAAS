@extends('layouts.app')

@section('title', 'Upgrade your plan')

@section('breadcrumb')
  <a href="{{ route('billing.manage') }}">Billing</a>
  <span class="sep">/</span>
  <span class="current">Plans</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Choose your plan</h1>
    <p class="page-sub">Start with a 14-day free trial. Cancel anytime, no questions asked.</p>
  </div>
</div>

@if($user->isPro())
<div class="alert alert-info current-plan-banner">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
  <span>You're on the <strong>{{ ucfirst($user->plan) }}</strong> plan.</span>
  <a href="{{ route('billing.portal') }}">Manage billing →</a>
</div>
@endif

<div class="plans-grid">
  @php
    $plans = [
      [
        'key' => 'free',
        'name' => 'Free',
        'price' => '$0',
        'cycle' => 'forever',
        'top' => 'free',
        'badge' => null,
        'description' => 'For personal projects and testing',
        'features' => [
          ['1 project', true],
          ['1 client', true],
          ['GitHub + 1 integration', true],
          ['Manual report send', true],
          ['Shareable link', true],
          ['AI summaries', false],
          ['Auto weekly send', false],
          ['Custom branding', false],
        ],
      ],
      [
        'key' => 'pro',
        'name' => 'Pro',
        'price' => '$19',
        'cycle' => 'per month',
        'top' => 'pro',
        'badge' => 'Most popular',
        'description' => 'For freelancers and small teams',
        'features' => [
          ['Unlimited projects', true],
          ['Unlimited clients', true],
          ['All 6 integrations', true],
          ['AI-generated summaries', true],
          ['Auto weekly report send', true],
          ['Custom branding', true],
          ['Priority support', true],
          ['White-label reports', false],
        ],
      ],
      [
        'key' => 'agency',
        'name' => 'Agency',
        'price' => '$49',
        'cycle' => 'per month · 5 seats',
        'top' => 'agency',
        'badge' => null,
        'description' => 'For agencies and growing teams',
        'features' => [
          ['Everything in Pro', true],
          ['5 team members', true],
          ['White-label reports', true],
          ['Custom domain', true],
          ['Dedicated support', true],
          ['Team analytics', true],
          ['API access', true],
          ['SLA guarantee', true],
        ],
      ],
    ];
  @endphp

  @foreach($plans as $plan)
  <div class="plan-card {{ $plan['key'] === 'pro' ? 'featured' : '' }} {{ $user->plan === $plan['key'] ? 'current' : '' }}">
    <div class="plan-card-top {{ $plan['top'] }}"></div>
    <div class="plan-card-body">
      <div class="plan-badges">
        @if($plan['badge'])
          <div class="plan-badge plan-badge-popular">{{ $plan['badge'] }}</div>
        @endif
        @if($user->plan === $plan['key'])
          <div class="plan-badge plan-badge-current">Current plan</div>
        @endif
      </div>

      <div class="plan-header">
        <div class="plan-name">{{ $plan['name'] }}</div>
        <div class="plan-description">{{ $plan['description'] }}</div>
      </div>

      <div class="plan-pricing">
        <div class="plan-price">{{ $plan['price'] }}</div>
        <div class="plan-cycle">{{ $plan['cycle'] }}</div>
      </div>

      <ul class="plan-features">
        @foreach($plan['features'] as [$label, $included])
          <li class="{{ !$included ? 'disabled' : '' }}">
            @if($included)
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            @else
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            @endif
            {{ $label }}
          </li>
        @endforeach
      </ul>

      <div class="plan-action">
        @if($plan['key'] === 'free')
          @if($user->plan === 'free')
            <button class="btn btn-ghost" disabled>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              Current plan
            </button>
          @else
            <a href="{{ route('billing.portal') }}" class="btn btn-ghost">
              Downgrade
            </a>
          @endif
        @else
          @if($user->plan === $plan['key'])
            <a href="{{ route('billing.portal') }}" class="btn btn-ghost">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Manage subscription
            </a>
          @else
            <form action="{{ route('billing.checkout') }}" method="POST">
              @csrf
              <input type="hidden" name="plan" value="{{ $plan['key'] }}">
              <button type="submit" class="btn {{ $plan['key'] === 'pro' ? 'btn-primary' : 'btn-ghost' }}">
                {{ $plan['key'] === 'pro' ? 'Start free trial' : 'Get Agency' }}
                @if($plan['key'] === 'pro')
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                @endif
              </button>
            </form>
          @endif
        @endif
      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="trust-badges">
  @foreach([
    ['14-day free trial', 'Try any plan risk-free for two weeks.'],
    ['Cancel anytime', 'No long-term contracts or hidden fees.'],
    ['Secure payments', 'All transactions processed securely via Stripe.'],
  ] as [$title, $desc])
    <div class="trust-badge">
      <div class="trust-icon">
        @if($loop->index === 0)
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        @elseif($loop->index === 1)
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/></svg>
        @else
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        @endif
      </div>
      <div class="trust-title">{{ $title }}</div>
      <div class="trust-desc">{{ $desc }}</div>
    </div>
  @endforeach
</div>

@push('styles')
<style>
.plans-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  max-width: 900px;
}

.plan-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 14px;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
  position: relative;
}

.plan-card:hover {
  transform: translateY(-4px);
  border-color: rgba(255,255,255,0.08);
  box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.plan-card.current {
  border-color: var(--green);
}

.plan-card.current:hover {
  border-color: var(--green);
  box-shadow: 0 0 0 1px var(--green), 0 12px 40px rgba(0,0,0,0.15);
}

.plan-card.featured {
  border-color: rgba(232,163,37,0.35);
  background: linear-gradient(180deg, rgba(232,163,37,0.04) 0%, var(--surface) 200px);
}

.plan-card.featured:hover {
  border-color: rgba(232,163,37,0.5);
  box-shadow: 0 0 0 1px rgba(232,163,37,0.2), 0 12px 40px rgba(0,0,0,0.2);
}

.plan-card-top {
  height: 4px;
  flex-shrink: 0;
}

.plan-card-top.free {
  background: linear-gradient(90deg, var(--border), var(--ink3));
}

.plan-card-top.pro {
  background: linear-gradient(90deg, var(--amber), #f5c842, var(--amber));
}

.plan-card-top.agency {
  background: linear-gradient(90deg, var(--sky), var(--purple), var(--sky));
}

.plan-card-body {
  padding: 1.8rem;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.plan-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
  margin-bottom: 1rem;
  min-height: 26px;
}

.plan-badge {
  display: inline-flex;
  align-items: center;
  font-family: var(--mono);
  font-size: 0.55rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  padding: 0.25rem 0.65rem;
  border-radius: 4px;
  font-weight: 600;
}

.plan-badge-popular {
  background: rgba(232,163,37,0.12);
  color: var(--amber);
  border: 1px solid rgba(232,163,37,0.25);
}

.plan-badge-current {
  background: rgba(39,201,63,0.1);
  color: var(--green);
  border: 1px solid rgba(39,201,63,0.2);
}

.plan-header {
  margin-bottom: 1.2rem;
}

.plan-name {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 0.3rem;
}

.plan-description {
  font-size: 0.78rem;
  color: var(--ink3);
  line-height: 1.4;
}

.plan-pricing {
  margin-bottom: 1.8rem;
}

.plan-price {
  font-family: var(--serif);
  font-size: 3rem;
  font-style: italic;
  font-weight: 400;
  letter-spacing: -0.04em;
  line-height: 1;
  color: var(--ink);
  margin-bottom: 0.3rem;
}

.plan-cycle {
  font-family: var(--mono);
  font-size: 0.7rem;
  color: var(--ink3);
}

.plan-features {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
  margin-bottom: 2rem;
  flex: 1;
}

.plan-features li {
  font-size: 0.84rem;
  color: var(--ink2);
  display: flex;
  gap: 0.6rem;
  align-items: flex-start;
  line-height: 1.45;
}

.plan-features li svg {
  flex-shrink: 0;
  margin-top: 0.15rem;
}

.plan-features li:not(.disabled) svg {
  color: var(--green);
}

.plan-features li.disabled {
  color: var(--ink3);
}

.plan-features li.disabled svg {
  color: var(--ink3);
  opacity: 0.4;
}

.plan-action {
  margin-top: auto;
}

.plan-action .btn,
.plan-action form,
.plan-action a {
  width: 100%;
  display: flex;
  justify-content: center;
}

.plan-action .btn {
  gap: 0.4rem;
}

.current-plan-banner {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  margin-bottom: 2rem;
  max-width: 900px;
  padding: 1rem 1.2rem;
  border-radius: 10px;
  background: rgba(56, 189, 248, 0.08);
  border: 1px solid rgba(56, 189, 248, 0.15);
  color: var(--sky);
  font-size: 0.9rem;
}

.current-plan-banner a {
  color: var(--sky);
  font-weight: 600;
  margin-left: auto;
  text-decoration: none;
  transition: opacity 0.2s;
}

.current-plan-banner a:hover {
  opacity: 0.8;
}

.trust-badges {
  margin-top: 2.5rem;
  padding: 1.8rem;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 12px;
  max-width: 900px;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  text-align: center;
}

.trust-badge {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.trust-icon {
  color: var(--ink3);
  opacity: 0.6;
}

.trust-title {
  font-size: 0.88rem;
  font-weight: 600;
  color: var(--ink);
}

.trust-desc {
  font-size: 0.78rem;
  color: var(--ink3);
  line-height: 1.4;
  max-width: 200px;
}

@media (max-width: 768px) {
  .plans-grid {
    grid-template-columns: 1fr;
    max-width: 400px;
    margin: 0 auto;
  }

  .plan-card.featured {
    order: -1;
  }

  .trust-badges {
    grid-template-columns: 1fr;
    gap: 1.2rem;
  }

  .current-plan-banner {
    flex-direction: column;
    text-align: center;
    gap: 0.5rem;
  }

  .current-plan-banner a {
    margin-left: 0;
  }
}
</style>
@endpush
@endsection