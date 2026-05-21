@extends('admin.layout')
@section('title', 'Settings')
@section('breadcrumb', '<span class="current">Settings</span>')

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">System settings</h1>
    <p class="page-sub">ProofWork SaaS configuration overview.</p>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">

  <!-- Environment -->
  <div class="card">
    <div class="card-header"><div class="card-title">Environment</div></div>
    <div style="padding:.8rem 1.2rem;display:flex;flex-direction:column;gap:.55rem">
      @foreach([
        ['App name',     config('app.name')],
        ['Environment',  config('app.env')],
        ['Debug mode',   config('app.debug') ? 'ON ⚠' : 'OFF ✓'],
        ['App URL',      config('app.url')],
        ['PHP version',  phpversion()],
        ['Laravel',      app()->version()],
        ['DB connection',config('database.default')],
        ['Queue driver', config('queue.default')],
        ['Cache driver', config('cache.default')],
        ['Mail driver',  config('mail.default')],
      ] as [$label, $value])
      <div style="display:flex;justify-content:space-between;gap:.5rem;padding:.35rem 0;border-bottom:1px solid rgba(255,255,255,.03)">
        <span style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $label }}</span>
        <span style="font-family:var(--mono);font-size:.68rem;color:{{ str_contains($value ?? '', '⚠') ? 'var(--coral)' : 'var(--ink2)' }}">
          {{ $value ?? '—' }}
        </span>
      </div>
      @endforeach
    </div>
  </div>

  <!-- Stripe config -->
  <div class="card">
    <div class="card-header"><div class="card-title">Stripe / Billing</div></div>
    <div style="padding:.8rem 1.2rem;display:flex;flex-direction:column;gap:.55rem">
      @foreach([
        ['Stripe key',       config('cashier.key') ? '✓ Set (' . substr(config('cashier.key'), 0, 8) . '...)' : '✗ Missing'],
        ['Stripe secret',    config('cashier.secret') ? '✓ Set' : '✗ Missing'],
        ['Webhook secret',   config('cashier.webhook.secret') ? '✓ Set' : '✗ Missing'],
        ['Price Pro',        config('proofwork.stripe_prices.pro') ?? '✗ Not set'],
        ['Price Agency',     config('proofwork.stripe_prices.agency') ?? '✗ Not set'],
        ['Currency',         strtoupper(config('cashier.currency', 'usd'))],
      ] as [$label, $value])
      <div style="display:flex;justify-content:space-between;gap:.5rem;padding:.35rem 0;border-bottom:1px solid rgba(255,255,255,.03)">
        <span style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $label }}</span>
        <span style="font-family:var(--mono);font-size:.68rem;color:{{ str_contains($value, '✗') ? 'var(--coral)' : 'var(--green)' }}">
          {{ $value }}
        </span>
      </div>
      @endforeach
    </div>
  </div>

  <!-- OAuth -->
  <div class="card">
    <div class="card-header"><div class="card-title">OAuth providers</div></div>
    <div style="padding:.8rem 1.2rem;display:flex;flex-direction:column;gap:.55rem">
      @foreach([
        ['GitHub client ID',     config('services.github.client_id') ? '✓ Set' : '✗ Missing'],
        ['GitHub secret',        config('services.github.client_secret') ? '✓ Set' : '✗ Missing'],
        ['GitHub redirect',      config('services.github.redirect') ?? '✗ Not set'],
        ['Google client ID',     config('services.google.client_id') ? '✓ Set' : '✗ Missing'],
        ['Google secret',        config('services.google.client_secret') ? '✓ Set' : '✗ Missing'],
        ['Google redirect',      config('services.google.redirect') ?? '✗ Not set'],
      ] as [$label, $value])
      <div style="display:flex;justify-content:space-between;gap:.5rem;padding:.35rem 0;border-bottom:1px solid rgba(255,255,255,.03)">
        <span style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $label }}</span>
        <span style="font-family:var(--mono);font-size:.65rem;color:{{ str_contains($value, '✗') ? 'var(--coral)' : 'var(--green)' }};word-break:break-all;text-align:right;max-width:180px">
          {{ $value }}
        </span>
      </div>
      @endforeach
    </div>
  </div>

  <!-- AI / Integrations -->
  <div class="card">
    <div class="card-header"><div class="card-title">AI & Integrations</div></div>
    <div style="padding:.8rem 1.2rem;display:flex;flex-direction:column;gap:.55rem">
      @foreach([
        ['Anthropic API',    config('proofwork.anthropic_api_key') ? '✓ Set' : '✗ Missing — AI summaries disabled'],
        ['Admin email',      config('proofwork.admin_email') ?? '✗ Not set'],
        ['Admin password',   config('proofwork.admin_password') ? '✓ Set' : '✗ Missing'],
        ['Plausible domain', config('proofwork.plausible_domain') ?? 'Not set — analytics disabled'],
      ] as [$label, $value])
      <div style="display:flex;justify-content:space-between;gap:.5rem;padding:.35rem 0;border-bottom:1px solid rgba(255,255,255,.03)">
        <span style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $label }}</span>
        <span style="font-family:var(--mono);font-size:.65rem;color:{{ str_contains($value, '✗') ? 'var(--coral)' : (str_contains($value, '✓') ? 'var(--green)' : 'var(--ink2)') }};text-align:right;max-width:200px">
          {{ $value }}
        </span>
      </div>
      @endforeach
    </div>
  </div>

  <!-- Quick actions -->
  <div class="card" style="grid-column:1/-1">
    <div class="card-header"><div class="card-title">Quick actions</div></div>
    <div style="padding:1.2rem;display:flex;gap:.8rem;flex-wrap:wrap">
      <a href="{{ route('admin.users') }}" class="btn btn-ghost">👤 Manage users</a>
      <a href="{{ route('admin.broadcast') }}" class="btn btn-ghost">📢 Send broadcast</a>
      <a href="{{ route('admin.projects') }}" class="btn btn-ghost">◈ View all projects</a>
      <a href="{{ route('admin.reports') }}" class="btn btn-ghost">📄 View all reports</a>
      <a href="{{ route('dashboard') }}" class="btn btn-ghost">← Back to app</a>
    </div>
  </div>

</div>
@endsection
