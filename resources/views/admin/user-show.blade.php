@extends('admin.layout')
@section('title', $user->name)
@section('breadcrumb')
  <a href="{{ route('admin.users') }}">Users</a>
  <span class="sep">›</span>
  <span class="current">{{ $user->name }}</span>
@endsection

@section('content')
<div class="page-header">
  <div style="display:flex;align-items:center;gap:1rem">
    <div style="width:46px;height:46px;border-radius:50%;background:var(--amber);display:flex;align-items:center;justify-content:center;font-size:.9rem;font-weight:700;color:#000;flex-shrink:0;font-family:var(--sans)">
      {{ $user->initials() }}
    </div>
    <div>
      <h1 class="page-title" style="font-size:1.5rem">{{ $user->name }}</h1>
      <p class="page-sub">{{ $user->email }} · Joined {{ $user->created_at->format('d M Y') }}</p>
    </div>
  </div>
  <div style="display:flex;gap:.5rem;flex-wrap:wrap">
    <form action="{{ route('admin.users.impersonate', $user) }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-ghost btn-sm">👤 Login as user</button>
    </form>
    <form action="{{ route('admin.users.delete', $user) }}" method="POST">
      @csrf @method('DELETE')
      <button type="submit" class="btn btn-danger btn-sm"
        onclick="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
        Delete user
      </button>
    </form>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start">

  <!-- Left -->
  <div style="display:flex;flex-direction:column;gap:1.2rem">

    <!-- Stats -->
    <div class="stats-grid" style="margin-bottom:0">
      <div class="stat">
        <div class="stat-label">Plan</div>
        <div class="stat-val {{ match($user->plan) { 'pro' => 'amber', 'agency' => 'sky', default => '' } }}">
          {{ ucfirst($user->plan) }}
        </div>
      </div>
      <div class="stat">
        <div class="stat-label">Projects</div>
        <div class="stat-val">{{ $user->projects->count() }}</div>
      </div>
      <div class="stat">
        <div class="stat-label">Reports</div>
        <div class="stat-val">{{ $user->reports->count() }}</div>
      </div>
      <div class="stat">
        <div class="stat-label">Clients</div>
        <div class="stat-val">{{ $user->clients->count() }}</div>
      </div>
      <div class="stat">
        <div class="stat-label">Integrations</div>
        <div class="stat-val">{{ $user->integrations->count() }}</div>
      </div>
      <div class="stat">
        <div class="stat-label">Report views</div>
        <div class="stat-val">{{ $user->reports->sum('view_count') }}</div>
      </div>
    </div>

    <!-- Projects -->
    <div class="card">
      <div class="card-header"><div class="card-title">Projects ({{ $user->projects->count() }})</div></div>
      @forelse($user->projects as $project)
      <div style="display:flex;align-items:center;gap:.9rem;padding:.8rem 1.2rem;border-bottom:1px solid rgba(255,255,255,.03)">
        <div style="width:30px;height:30px;border-radius:6px;background:{{ $project->color }};display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#000;flex-shrink:0">
          {{ $project->initials() }}
        </div>
        <div style="flex:1">
          <div style="font-size:.82rem;font-weight:500;color:var(--ink)">{{ $project->name }}</div>
          <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $project->client?->name ?? 'No client' }}</div>
        </div>
        <span class="badge {{ $project->status === 'active' ? 'badge-green' : 'badge-gray' }}">{{ $project->status }}</span>
      </div>
      @empty
      <div style="padding:1.5rem;text-align:center;color:var(--ink3);font-size:.8rem">No projects.</div>
      @endforelse
    </div>

    <!-- Recent reports -->
    <div class="card">
      <div class="card-header"><div class="card-title">Recent reports</div></div>
      @forelse($user->reports->take(5) as $report)
      <div style="display:flex;align-items:center;gap:.9rem;padding:.8rem 1.2rem;border-bottom:1px solid rgba(255,255,255,.03)">
        <div style="flex:1">
          <div style="font-size:.82rem;font-weight:500;color:var(--ink)">{{ $report->title }}</div>
          <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $report->periodLabel() }}</div>
        </div>
        <div style="display:flex;align-items:center;gap:.5rem">
          <span class="badge {{ match($report->status) { 'sent' => 'badge-green', 'ready' => 'badge-amber', default => 'badge-gray' } }}">{{ $report->status }}</span>
          @if($report->view_count > 0)
          <span style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $report->view_count }}👁</span>
          @endif
        </div>
      </div>
      @empty
      <div style="padding:1.5rem;text-align:center;color:var(--ink3);font-size:.8rem">No reports.</div>
      @endforelse
    </div>

  </div>

  <!-- Right sidebar -->
  <div style="display:flex;flex-direction:column;gap:1.2rem">

    <!-- User info -->
    <div class="card">
      <div class="card-header"><div class="card-title">Account info</div></div>
      <div style="padding:.8rem 1.2rem;display:flex;flex-direction:column;gap:.6rem">
        @foreach([
          ['ID',         $user->id],
          ['Email',      $user->email],
          ['Verified',   $user->email_verified_at ? '✓ ' . $user->email_verified_at->format('d M Y') : '✗ Not verified'],
          ['Plan',       ucfirst($user->plan)],
          ['Trial ends', $user->trial_ends_at ? $user->trial_ends_at->format('d M Y') : '—'],
          ['Stripe ID',  $user->stripe_id ?? '—'],
          ['Timezone',   $user->timezone],
          ['Joined',     $user->created_at->format('d M Y H:i')],
        ] as [$label, $value])
        <div style="display:flex;justify-content:space-between;gap:.5rem;padding:.3rem 0;border-bottom:1px solid rgba(255,255,255,.03)">
          <span style="font-family:var(--mono);font-size:.6rem;color:var(--ink3);flex-shrink:0">{{ $label }}</span>
          <span style="font-size:.75rem;color:var(--ink2);text-align:right;word-break:break-all">{{ $value }}</span>
        </div>
        @endforeach
      </div>
    </div>

    <!-- Change plan -->
    <div class="card">
      <div class="card-header"><div class="card-title">Change plan</div></div>
      <div class="card-body">
        <form action="{{ route('admin.users.plan', $user) }}" method="POST">
          @csrf
          <div class="form-group">
            <label class="form-label">Plan</label>
            <select name="plan" class="form-select">
              @foreach(['free','pro','agency'] as $plan)
              <option value="{{ $plan }}" {{ $user->plan === $plan ? 'selected' : '' }}>
                {{ ucfirst($plan) }}
              </option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center">
            Update plan
          </button>
        </form>
      </div>
    </div>

    <!-- Integrations -->
    @if($user->integrations->count() > 0)
    <div class="card">
      <div class="card-header"><div class="card-title">Integrations</div></div>
      @foreach($user->integrations as $integration)
      <div style="display:flex;align-items:center;gap:.7rem;padding:.7rem 1.2rem;border-bottom:1px solid rgba(255,255,255,.03)">
        <span style="font-size:.9rem">{{ $integration->providerIcon() }}</span>
        <div style="flex:1">
          <div style="font-size:.78rem;font-weight:500;color:var(--ink)">{{ $integration->providerLabel() }}</div>
          <div style="font-family:var(--mono);font-size:.58rem;color:var(--ink3)">{{ $integration->provider_account_name ?? '—' }}</div>
        </div>
        <span class="badge {{ $integration->active ? 'badge-green' : 'badge-gray' }}">
          {{ $integration->active ? 'active' : 'off' }}
        </span>
      </div>
      @endforeach
    </div>
    @endif

  </div>
</div>
@endsection
