@extends('admin.layout')
@section('title', 'Dashboard')
@section('breadcrumb', '<span class="current">Dashboard</span>')

@push('styles')
<style>
.mrr-card{background:linear-gradient(135deg,rgba(232,163,37,.08),rgba(74,158,255,.05));border:1px solid rgba(232,163,37,.2);border-radius:10px;padding:1.4rem 1.8rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem}
.mrr-label{font-family:var(--mono);font-size:.62rem;color:var(--amber);letter-spacing:.12em;text-transform:uppercase;margin-bottom:.3rem;opacity:.8}
.mrr-val{font-family:var(--serif);font-size:3rem;font-style:italic;color:var(--amber);line-height:1}
.mrr-sub{font-family:var(--mono);font-size:.62rem;color:var(--ink3);margin-top:.2rem}
</style>
@endpush

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Admin dashboard</h1>
    <p class="page-sub">ProofWork SaaS overview · {{ now()->format('d M Y') }}</p>
  </div>
</div>

<!-- MRR highlight -->
<div class="mrr-card">
  <div>
    <div class="mrr-label">Monthly Recurring Revenue</div>
    <div class="mrr-val">${{ number_format($stats['mrr']) }}</div>
    <div class="mrr-sub">{{ $stats['users_pro'] }} Pro × $19 + {{ $stats['users_agency'] }} Agency × $49</div>
  </div>
  <div style="text-align:right">
    <div style="font-family:var(--mono);font-size:.65rem;color:var(--ink3)">ARR estimate</div>
    <div style="font-family:var(--serif);font-size:1.8rem;font-style:italic;color:var(--ink)">${{ number_format($stats['mrr'] * 12) }}</div>
  </div>
</div>

<!-- Stats -->
<div class="stats-grid">
  <div class="stat">
    <div class="stat-label">Total users</div>
    <div class="stat-val amber">{{ number_format($stats['users_total']) }}</div>
    <div class="stat-sub">+{{ $stats['users_today'] }} today</div>
  </div>
  <div class="stat">
    <div class="stat-label">Pro users</div>
    <div class="stat-val green">{{ $stats['users_pro'] }}</div>
    <div class="stat-sub">paying customers</div>
  </div>
  <div class="stat">
    <div class="stat-label">Agency</div>
    <div class="stat-val sky">{{ $stats['users_agency'] }}</div>
    <div class="stat-sub">$49/month each</div>
  </div>
  <div class="stat">
    <div class="stat-label">Free users</div>
    <div class="stat-val">{{ $stats['users_free'] }}</div>
    <div class="stat-sub">conversion targets</div>
  </div>
  <div class="stat">
    <div class="stat-label">Projects</div>
    <div class="stat-val">{{ $stats['projects_total'] }}</div>
    <div class="stat-sub">active projects</div>
  </div>
  <div class="stat">
    <div class="stat-label">Reports</div>
    <div class="stat-val">{{ $stats['reports_total'] }}</div>
    <div class="stat-sub">{{ $stats['reports_sent'] }} sent to clients</div>
  </div>
</div>

<!-- Grid -->
<div class="grid-2">

  <!-- Signups chart -->
  <div class="card">
    <div class="card-header"><div class="card-title">Signups — last 14 days</div></div>
    <div class="card-body" style="height:200px;position:relative">
      <canvas id="signupsChart"></canvas>
    </div>
  </div>

  <!-- Plan breakdown -->
  <div class="card">
    <div class="card-header"><div class="card-title">Plan breakdown</div></div>
    <div style="padding:1rem 1.3rem;display:flex;flex-direction:column;gap:.9rem">
      @php $total = max(1, $stats['users_total']); @endphp
      @foreach(['pro' => ['Pro', 'var(--amber)', $stats['users_pro']], 'agency' => ['Agency', 'var(--sky)', $stats['users_agency']], 'free' => ['Free', 'var(--ink3)', $stats['users_free']]] as [$label, $color, $count])
      <div style="display:flex;align-items:center;gap:.8rem">
        <div style="font-family:var(--mono);font-size:.62rem;color:var(--ink3);width:50px">{{ $label }}</div>
        <div style="flex:1;background:var(--bg);border-radius:3px;height:6px;overflow:hidden">
          <div style="height:100%;border-radius:3px;background:{{ $color }};width:{{ round(($count/$total)*100) }}%;transition:width 1s ease"></div>
        </div>
        <div style="font-family:var(--mono);font-size:.62rem;color:var(--ink3);width:25px;text-align:right">{{ $count }}</div>
      </div>
      @endforeach
    </div>
  </div>
</div>

<!-- Recent signups -->
<div class="table-wrap">
  <div class="card-header">
    <div class="card-title">Recent signups</div>
    <a href="{{ route('admin.users') }}" class="btn btn-ghost btn-sm">View all →</a>
  </div>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Plan</th>
        <th>Verified</th>
        <th>Joined</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($recentUsers as $user)
      <tr>
        <td class="td-main">{{ $user->name }}</td>
        <td style="font-family:var(--mono);font-size:.72rem">{{ $user->email }}</td>
        <td><span class="badge {{ match($user->plan) { 'pro' => 'badge-amber', 'agency' => 'badge-sky', default => 'badge-gray' } }}">{{ $user->plan }}</span></td>
        <td>
          @if($user->email_verified_at)
          <span class="badge badge-green">✓</span>
          @else
          <span class="badge badge-coral">✗</span>
          @endif
        </td>
        <td style="font-family:var(--mono);font-size:.7rem">{{ $user->created_at->diffForHumans() }}</td>
        <td>
          <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost btn-sm">View</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection

@push('scripts')
<script>
new Chart(document.getElementById('signupsChart'), {
  type: 'bar',
  data: {
    labels: @json($chartLabels),
    datasets: [{
      data: @json($chartData),
      backgroundColor: 'rgba(232,163,37,0.2)',
      borderColor: '#e8a325',
      borderWidth: 1.5,
      borderRadius: 3,
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#5a5855', font: { family: 'Geist Mono', size: 9 }, maxRotation: 0 } },
      y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#5a5855', font: { family: 'Geist Mono', size: 9 }, stepSize: 1 }, beginAtZero: true }
    }
  }
});
</script>
@endpush
