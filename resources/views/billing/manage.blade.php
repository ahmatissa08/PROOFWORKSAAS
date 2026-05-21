@extends('layouts.app')

@section('title', 'Billing')

@section('breadcrumb')
  <span class="current">Billing</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Billing</h1>
    <p class="page-sub">Manage your subscription, usage, and invoices.</p>
  </div>
</div>

<div class="billing-container">
  {{-- Current Plan Card --}}
  <div class="card plan-card">
    <div class="card-header">
      <div class="card-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
        Current plan
      </div>
    </div>
    <div class="card-body">
      <div class="plan-info">
        <div class="plan-details">
          <div class="plan-name">
            {{ ucfirst($user->plan) }}
            @if($user->onTrial())
              <span class="badge badge-amber">Trial</span>
            @endif
          </div>

          @if($subscription && $subscription->active())
            <div class="plan-status">
              Status: <span class="status-active">{{ ucfirst($subscription->stripe_status) }}</span>
              @if($subscription->ends_at)
                <span class="status-cancels">· Cancels {{ $subscription->ends_at->format('M d, Y') }}</span>
              @endif
            </div>
          @elseif($user->plan === 'free')
            <div class="plan-status">Free forever — Limited to 1 project</div>
          @endif

          @if($user->onTrial())
            <div class="trial-info">
              Trial ends {{ $user->trial_ends_at->format('M d, Y') }} ({{ $user->trial_ends_at->diffForHumans() }})
            </div>
          @endif
        </div>

        <div class="plan-actions">
          @if($user->isPro())
            <a href="{{ route('billing.portal') }}" class="btn btn-ghost">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
              Manage on Stripe
            </a>
          @else
            <a href="{{ route('billing.plans') }}" class="btn btn-primary">
              Upgrade to Pro
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Plan Limits Card --}}
  <div class="card limits-card">
    <div class="card-header">
      <div class="card-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        Plan limits
      </div>
    </div>
    <div class="limits-body">
      @php $limits = $user->planLimits(); @endphp
      @php
        $limitItems = [
          ['Projects', $user->projects()->count(), $limits['projects'], 'folder'],
          ['Clients', $user->clients()->count(), $limits['clients'], 'users'],
          ['Integrations', count(config('proofwork.integrations', [])), $limits['integrations'], 'plug'],
          ['Auto-send', $limits['auto_send'] ? true : false, null, 'send'],
          ['AI summaries', $user->isPro() ? true : false, null, 'sparkles'],
        ];
      @endphp

      @foreach($limitItems as [$label, $current, $max, $icon])
        <div class="limit-row">
          <div class="limit-label">
            @if($icon === 'folder')
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            @elseif($icon === 'users')
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            @elseif($icon === 'plug')
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22v-5"/><path d="M9 8V2"/><path d="M15 8V2"/><path d="M18 8v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8z"/></svg>
            @elseif($icon === 'send')
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            @elseif($icon === 'sparkles')
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
            @endif
            {{ $label }}
          </div>
          <div class="limit-value {{ is_bool($current) && !$current ? 'limit-upgrade' : '' }}">
            @if(is_bool($current))
              {{ $current ? 'Included' : 'Upgrade required' }}
            @else
              {{ $current }} / {{ $max >= 999 ? 'Unlimited' : $max }}
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- Invoice History Card --}}
  <div class="card invoices-card">
    <div class="card-header">
      <div class="card-title">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Invoice history
      </div>
    </div>
    @if(count($invoices) > 0)
      <div class="table-wrapper">
        <table class="invoices-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Amount</th>
              <th>Status</th>
              <th class="text-right">Invoice</th>
            </tr>
          </thead>
          <tbody>
            @foreach($invoices as $invoice)
              <tr>
                <td data-label="Date">
                  <span class="invoice-date">{{ $invoice->date()->format('M d, Y') }}</span>
                </td>
                <td data-label="Amount">
                  <span class="invoice-amount">{{ $invoice->total() }}</span>
                </td>
                <td data-label="Status">
                  <span class="badge badge-green">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Paid
                  </span>
                </td>
                <td data-label="Invoice" class="text-right">
                  <a href="{{ $invoice->pdf() }}" class="btn btn-ghost btn-sm" target="_blank" rel="noopener noreferrer">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    PDF
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="empty-state">
        <div class="empty-icon">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        </div>
        <div class="empty-title">No invoices yet</div>
        <div class="empty-sub">Your invoices will appear here after your first payment.</div>
      </div>
    @endif
  </div>
</div>

@push('styles')
<style>
.billing-container {
  max-width: 720px;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.plan-card .plan-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1.25rem;
}

.plan-card .plan-details {
  flex: 1;
  min-width: 200px;
}

.plan-card .plan-name {
  font-family: var(--serif);
  font-size: 1.5rem;
  font-style: italic;
  color: var(--ink);
  margin-bottom: 0.35rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.plan-card .plan-status {
  font-family: var(--mono);
  font-size: 0.72rem;
  color: var(--ink3);
  line-height: 1.5;
}

.plan-card .status-active {
  color: var(--green);
  font-weight: 500;
}

.plan-card .status-cancels {
  color: var(--ink3);
}

.plan-card .trial-info {
  font-family: var(--mono);
  font-size: 0.72rem;
  color: var(--amber);
  margin-top: 0.35rem;
}

.plan-card .plan-actions {
  display: flex;
  gap: 0.6rem;
  flex-wrap: wrap;
}

.plan-card .btn {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
}

.limits-body {
  padding: 0.5rem 0;
}

.limit-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.85rem 1.4rem;
  border-bottom: 1px solid rgba(255,255,255,0.03);
  transition: background-color 0.15s ease;
}

.limit-row:hover {
  background-color: rgba(255,255,255,0.015);
}

.limit-row:last-child {
  border-bottom: none;
}

.limit-label {
  font-size: 0.86rem;
  color: var(--ink2);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.limit-label svg {
  opacity: 0.5;
  flex-shrink: 0;
}

.limit-value {
  font-family: var(--mono);
  font-size: 0.76rem;
  color: var(--ink);
  font-weight: 500;
}

.limit-value.limit-upgrade {
  color: var(--ink3);
  font-weight: 400;
}

.invoices-card .table-wrapper {
  overflow-x: auto;
}

.invoices-table {
  width: 100%;
  border-collapse: collapse;
}

.invoices-table thead th {
  text-align: left;
  padding: 0.85rem 1.4rem;
  font-size: 0.72rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--ink3);
  border-bottom: 1px solid var(--border);
  white-space: nowrap;
}

.invoices-table thead th.text-right {
  text-align: right;
}

.invoices-table tbody td {
  padding: 1rem 1.4rem;
  border-bottom: 1px solid rgba(255,255,255,0.03);
  vertical-align: middle;
}

.invoices-table tbody tr:last-child td {
  border-bottom: none;
}

.invoices-table tbody tr:hover td {
  background-color: rgba(255,255,255,0.015);
}

.invoice-date {
  font-size: 0.88rem;
  color: var(--ink);
}

.invoice-amount {
  font-family: var(--mono);
  font-size: 0.82rem;
  color: var(--ink);
  font-weight: 500;
}

.empty-state {
  padding: 3rem 2rem;
  text-align: center;
}

.empty-state .empty-icon {
  color: var(--ink3);
  margin-bottom: 1rem;
  opacity: 0.5;
}

.empty-state .empty-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--ink);
  margin-bottom: 0.35rem;
}

.empty-state .empty-sub {
  font-size: 0.82rem;
  color: var(--ink3);
}

.badge {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
}

@media (max-width: 640px) {
  .plan-card .plan-info {
    flex-direction: column;
    align-items: flex-start;
  }

  .plan-card .plan-actions {
    width: 100%;
  }

  .plan-card .plan-actions .btn {
    flex: 1;
    justify-content: center;
  }

  .invoices-table thead {
    display: none;
  }

  .invoices-table tbody td {
    display: block;
    padding: 0.75rem 1rem;
    border-bottom: none;
  }

  .invoices-table tbody td::before {
    content: attr(data-label);
    display: block;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--ink3);
    margin-bottom: 0.25rem;
  }

  .invoices-table tbody tr {
    display: block;
    border-bottom: 1px solid rgba(255,255,255,0.03);
    padding: 0.5rem 0;
  }

  .invoices-table tbody td.text-right {
    text-align: left;
  }
}
</style>
@endpush
@endsection