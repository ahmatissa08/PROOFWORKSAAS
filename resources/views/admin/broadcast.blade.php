@extends('admin.layout')
@section('title', 'Broadcast')
@section('breadcrumb', '<span class="current">Broadcast</span>')

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Broadcast email</h1>
    <p class="page-sub">Send an email to all users or a specific plan segment.</p>
  </div>
</div>

<div style="display:grid;grid-template-columns:1.4fr 1fr;gap:1.5rem;align-items:start">

  <!-- Form -->
  <div class="card">
    <div class="card-header"><div class="card-title">Compose message</div></div>
    <div class="card-body">
      <form action="{{ route('admin.broadcast.send') }}" method="POST">
        @csrf

        <div class="form-group">
          <label class="form-label">Recipients</label>
          <select name="plan" class="form-select">
            <option value="all">All users ({{ $count }})</option>
            <option value="pro">Pro users only</option>
            <option value="agency">Agency users only</option>
            <option value="free">Free users only</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Subject line</label>
          <input type="text" name="subject" class="form-input"
            placeholder="e.g. ProofWork update — new features this week"
            value="{{ old('subject') }}" required>
        </div>

        <div class="form-group">
          <label class="form-label">Message body</label>
          <textarea name="body" class="form-textarea" style="min-height:200px"
            placeholder="Write your message here...&#10;&#10;You can use plain text. Line breaks are preserved." required>{{ old('body') }}</textarea>
          <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3);margin-top:.4rem">
            Plain text only. Line breaks are preserved. No HTML.
          </div>
        </div>

        <div style="background:rgba(232,163,37,.05);border:1px solid rgba(232,163,37,.15);border-radius:6px;padding:.8rem 1rem;margin-bottom:1.2rem">
          <div style="font-family:var(--mono);font-size:.62rem;color:var(--amber);margin-bottom:.3rem">⚠ Before sending</div>
          <div style="font-size:.78rem;color:var(--ink2);line-height:1.55">
            This will send a real email to all selected users. Double-check the subject and body before confirming.
          </div>
        </div>

        <button type="submit" class="btn btn-primary"
          onclick="return confirm('Send this email to the selected users? This cannot be undone.')">
          📢 Send broadcast →
        </button>
      </form>
    </div>
  </div>

  <!-- Preview & Tips -->
  <div style="display:flex;flex-direction:column;gap:1.2rem">

    <!-- Stats -->
    <div class="card">
      <div class="card-header"><div class="card-title">Audience breakdown</div></div>
      <div style="padding:.8rem 1.2rem;display:flex;flex-direction:column;gap:.6rem">
        @php
          $planCounts = \App\Models\User::selectRaw('plan, count(*) as cnt')->groupBy('plan')->pluck('cnt','plan');
        @endphp
        @foreach(['all' => 'All users', 'free' => 'Free', 'pro' => 'Pro', 'agency' => 'Agency'] as $key => $label)
        <div style="display:flex;justify-content:space-between;padding:.35rem 0;border-bottom:1px solid rgba(255,255,255,.03)">
          <span style="font-size:.8rem;color:var(--ink2)">{{ $label }}</span>
          <span style="font-family:var(--mono);font-size:.72rem;color:var(--ink)">
            @if($key === 'all')
              {{ $count }}
            @else
              {{ $planCounts[$key] ?? 0 }}
            @endif
          </span>
        </div>
        @endforeach
      </div>
    </div>

    <!-- Tips -->
    <div class="card">
      <div class="card-header"><div class="card-title">Tips</div></div>
      <div style="padding:1rem 1.2rem;display:flex;flex-direction:column;gap:.7rem">
        @foreach([
          ['✓', 'Keep subject lines under 50 characters'],
          ['✓', 'Start with the user\'s benefit, not your feature'],
          ['✓', 'One clear call-to-action per email'],
          ['✓', 'Test with a small segment first'],
          ['✓', 'Send on Tuesday–Thursday, 9am–11am'],
        ] as [$icon, $tip])
        <div style="display:flex;gap:.6rem;font-size:.78rem;color:var(--ink2)">
          <span style="color:var(--green);flex-shrink:0">{{ $icon }}</span>
          {{ $tip }}
        </div>
        @endforeach
      </div>
    </div>

  </div>
</div>
@endsection
