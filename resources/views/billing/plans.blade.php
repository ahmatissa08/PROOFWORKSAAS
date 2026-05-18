@extends('layouts.app')
@section('title', 'Upgrade your plan')
@section('breadcrumb')
  <a href="{{ route('billing.manage') }}">Billing</a>
  <span class="sep">/</span>
  <span class="current">Plans</span>
@endsection

@push('styles')
<style>
.plans-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;max-width:860px}
.plan-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;transition:border-color .2s}
.plan-card.current{border-color:var(--green)}
.plan-card.featured{border-color:rgba(232,163,37,.4);background:rgba(232,163,37,.03)}
.plan-card-top{height:3px}
.plan-card-top.free{background:var(--border)}
.plan-card-top.pro{background:linear-gradient(90deg,var(--amber),#f1c135)}
.plan-card-top.agency{background:linear-gradient(90deg,var(--sky),var(--purple))}
.plan-card-body{padding:1.8rem}
.plan-badge{display:inline-block;font-family:var(--mono);font-size:.55rem;letter-spacing:.1em;text-transform:uppercase;padding:.2rem .6rem;border-radius:3px;margin-bottom:1rem}
.plan-name{font-size:1rem;font-weight:600;margin-bottom:.2rem}
.plan-price{font-family:var(--serif);font-size:2.8rem;font-style:italic;font-weight:400;letter-spacing:-.05em;line-height:1;margin-bottom:.2rem}
.plan-cycle{font-family:var(--mono);font-size:.68rem;color:var(--ink3);margin-bottom:1.6rem}
.plan-features{list-style:none;display:flex;flex-direction:column;gap:.5rem;margin-bottom:2rem}
.plan-features li{font-size:.82rem;color:var(--ink2);display:flex;gap:.5rem;align-items:flex-start;line-height:1.45}
.plan-features li::before{content:'+';color:var(--amber);font-family:var(--mono);font-size:.7rem;flex-shrink:0;margin-top:.1rem}
.plan-features li.disabled{color:var(--ink3)}
.plan-features li.disabled::before{content:'-';color:var(--ink3)}
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Choose your plan.</h1>
    <p class="page-sub">All plans include a 14-day free trial. Cancel anytime.</p>
  </div>
</div>

@if($user->isPro())
<div class="alert alert-info" style="margin-bottom:2rem">
  You're on the <strong>{{ ucfirst($user->plan) }}</strong> plan.
  <a href="{{ route('billing.portal') }}" style="color:var(--sky)">Manage billing</a>
</div>
@endif

<div class="plans-grid">
  @foreach([
    ['key' => 'free','name' => 'Free','price' => '$0','cycle' => 'forever','top' => 'free','badge' => null,'features' => [['1 project', true],['1 client', true],['GitHub + 1 integration', true],['Manual report send', true],['Shareable link', true],['AI summaries', false],['Auto weekly send', false],['Custom branding', false]]],
    ['key' => 'pro','name' => 'Pro','price' => '$19','cycle' => 'per month','top' => 'pro','badge' => 'Most popular','features' => [['Unlimited projects', true],['Unlimited clients', true],['All 6 integrations', true],['AI-generated summaries', true],['Auto weekly report send', true],['Custom branding', true],['Priority support', true],['White-label reports', false]]],
    ['key' => 'agency','name' => 'Agency','price' => '$49','cycle' => 'per month - 5 seats','top' => 'agency','badge' => null,'features' => [['Everything in Pro', true],['5 team members', true],['White-label reports', true],['Custom domain', true],['Dedicated support', true],['Team analytics', true],['API access', true],['SLA guarantee', true]]],
  ] as $plan)
  <div class="plan-card {{ $plan['key'] === 'pro' ? 'featured' : '' }} {{ $user->plan === $plan['key'] ? 'current' : '' }}">
    <div class="plan-card-top {{ $plan['top'] }}"></div>
    <div class="plan-card-body">
      @if($plan['badge'])
      <div class="plan-badge" style="background:rgba(232,163,37,.1);color:var(--amber);border:1px solid rgba(232,163,37,.2)">{{ $plan['badge'] }}</div>
      @endif
      @if($user->plan === $plan['key'])
      <div class="plan-badge" style="background:rgba(39,201,63,.08);color:var(--green);border:1px solid rgba(39,201,63,.15)">Current plan</div>
      @endif
      <div class="plan-name">{{ $plan['name'] }}</div>
      <div class="plan-price">{{ $plan['price'] }}</div>
      <div class="plan-cycle">{{ $plan['cycle'] }}</div>
      <ul class="plan-features">
        @foreach($plan['features'] as [$label, $included])
        <li class="{{ !$included ? 'disabled' : '' }}">{{ $label }}</li>
        @endforeach
      </ul>
      @if($plan['key'] === 'free')
        @if($user->plan === 'free')
        <button class="btn btn-ghost" style="width:100%" disabled>Current plan</button>
        @else
        <a href="{{ route('billing.portal') }}" class="btn btn-ghost" style="width:100%;justify-content:center">Downgrade</a>
        @endif
      @else
        @if($user->plan === $plan['key'])
        <a href="{{ route('billing.portal') }}" class="btn btn-ghost" style="width:100%;justify-content:center">Manage subscription</a>
        @else
        <form action="{{ route('billing.checkout') }}" method="POST">
          @csrf
          <input type="hidden" name="plan" value="{{ $plan['key'] }}">
          <button type="submit" class="btn {{ $plan['key'] === 'pro' ? 'btn-primary' : 'btn-ghost' }}" style="width:100%;justify-content:center">
            {{ $plan['key'] === 'pro' ? 'Start free trial' : 'Get Agency' }}
          </button>
        </form>
        @endif
      @endif
    </div>
  </div>
  @endforeach
</div>

<div style="margin-top:2.5rem;padding:1.5rem;background:var(--surface);border:1px solid var(--border);border-radius:10px;max-width:860px">
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;text-align:center">
    @foreach(['14-day free trial on all plans','Cancel anytime - no lock-in','Secure payments via Stripe'] as $item)
    <div><div style="font-size:.88rem;font-weight:500;margin-bottom:.2rem">{{ $item }}</div></div>
    @endforeach
  </div>
</div>
@endsection
