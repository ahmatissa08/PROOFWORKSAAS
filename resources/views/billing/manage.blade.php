@extends('layouts.app')
@section('title', 'Billing')
@section('breadcrumb')
  <span class="current">Billing</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Billing</h1>
    <p class="page-sub">Manage your subscription and invoices.</p>
  </div>
</div>

<div style="max-width:700px;display:flex;flex-direction:column;gap:1.5rem">
  <div class="card">
    <div class="card-header"><div class="card-title">Current plan</div></div>
    <div class="card-body">
      <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem">
        <div>
          <div style="font-family:var(--serif);font-size:1.5rem;font-style:italic;color:var(--ink);margin-bottom:.3rem">
            {{ ucfirst($user->plan) }}
            @if($user->onTrial())
            <span class="badge badge-amber" style="font-size:.65rem;vertical-align:middle">Trial</span>
            @endif
          </div>
          @if($subscription && $subscription->active())
          <div style="font-family:var(--mono);font-size:.68rem;color:var(--ink3)">
            Status: <span style="color:var(--green)">{{ ucfirst($subscription->stripe_status) }}</span>
            @if($subscription->ends_at)
            - Cancels {{ $subscription->ends_at->format('M d, Y') }}
            @endif
          </div>
          @elseif($user->plan === 'free')
          <div style="font-family:var(--mono);font-size:.68rem;color:var(--ink3)">Free forever - Limited to 1 project</div>
          @endif
          @if($user->onTrial())
          <div style="font-family:var(--mono);font-size:.68rem;color:var(--amber);margin-top:.3rem">
            Trial ends {{ $user->trial_ends_at->format('M d, Y') }} ({{ $user->trial_ends_at->diffForHumans() }})
          </div>
          @endif
        </div>
        <div style="display:flex;gap:.6rem;flex-wrap:wrap">
          @if($user->isPro())
          <a href="{{ route('billing.portal') }}" class="btn btn-ghost">Manage on Stripe</a>
          @else
          <a href="{{ route('billing.plans') }}" class="btn btn-primary">Upgrade to Pro</a>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><div class="card-title">Plan limits</div></div>
    <div style="padding:.8rem 0">
      @php $limits = $user->planLimits(); @endphp
      @foreach([
        ['Projects', $user->projects()->count() . ' / ' . ($limits['projects'] >= 999 ? 'Unlimited' : $limits['projects'])],
        ['Clients', $user->clients()->count() . ' / ' . ($limits['clients'] >= 999 ? 'Unlimited' : $limits['clients'])],
        ['Integrations', count(config('proofwork.integrations', [])) . ' / ' . $limits['integrations']],
        ['Auto-send', $limits['auto_send'] ? 'Enabled' : 'Upgrade required'],
        ['AI summaries', $user->isPro() ? 'Included' : 'Upgrade required'],
      ] as [$label, $value])
      <div style="display:flex;justify-content:space-between;align-items:center;padding:.75rem 1.4rem;border-bottom:1px solid rgba(255,255,255,.03)">
        <span style="font-size:.84rem;color:var(--ink2)">{{ $label }}</span>
        <span style="font-family:var(--mono);font-size:.72rem;color:{{ str_contains($value, 'Upgrade') ? 'var(--ink3)' : 'var(--ink)' }}">{{ $value }}</span>
      </div>
      @endforeach
    </div>
  </div>

  <div class="card">
    <div class="card-header"><div class="card-title">Invoice history</div></div>
    @if(count($invoices) > 0)
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Invoice</th>
        </tr>
      </thead>
      <tbody>
        @foreach($invoices as $invoice)
        <tr>
          <td>{{ $invoice->date()->format('M d, Y') }}</td>
          <td><span style="font-family:var(--mono)">{{ $invoice->total() }}</span></td>
          <td><span class="badge badge-green">Paid</span></td>
          <td>
            <a href="{{ $invoice->invoicePdf() }}" class="btn btn-ghost btn-sm" target="_blank">PDF</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
    <div class="empty-state" style="padding:2.5rem">
      <div class="empty-icon">I</div>
      <div class="empty-title">No invoices yet</div>
      <div class="empty-sub">Your invoices will appear here after your first payment.</div>
    </div>
    @endif
  </div>
</div>
@endsection
