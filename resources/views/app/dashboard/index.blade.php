@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb')
  <span class="current">Dashboard</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ explode(' ', $user->name)[0] }}.</h1>
    <p class="page-sub">Here's what's happening with your projects.</p>
  </div>
  <a href="{{ route('projects.create') }}" class="btn btn-primary">New project</a>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-label">Active projects</div>
    <div class="stat-value amber">{{ $stats['projects'] }}</div>
    <div class="stat-sub">{{ $user->isPro() ? 'Unlimited' : '1 on free plan' }}</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Reports generated</div>
    <div class="stat-value">{{ $stats['reports_total'] }}</div>
    <div class="stat-sub">{{ $stats['reports_sent'] }} sent to clients</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Clients</div>
    <div class="stat-value sky">{{ $stats['clients'] }}</div>
    <div class="stat-sub">Active clients</div>
  </div>
  <div class="stat-card">
    <div class="stat-label">Report views</div>
    <div class="stat-value green">{{ number_format($stats['views_total']) }}</div>
    <div class="stat-sub">By your clients</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1.4fr 1fr;gap:1.5rem">
  <div class="card">
    <div class="card-header">
      <div class="card-title">Your projects</div>
      <a href="{{ route('projects.create') }}" class="btn btn-ghost btn-sm">Add project</a>
    </div>
    @if($projects->isEmpty())
    <div class="empty-state">
      <div class="empty-icon">P</div>
      <div class="empty-title">No projects yet</div>
      <div class="empty-sub">Create your first project to start generating proof of work reports.</div>
      <a href="{{ route('projects.create') }}" class="btn btn-primary">Create first project</a>
    </div>
    @else
    <div style="padding:.5rem 0">
      @foreach($projects as $project)
      <a href="{{ route('projects.show', $project) }}" style="display:flex;align-items:center;gap:1rem;padding:.9rem 1.4rem;text-decoration:none;transition:background .12s;border-bottom:1px solid var(--border)" onmouseover="this.style.background='rgba(255,255,255,.02)'" onmouseout="this.style.background='transparent'">
        <div style="width:36px;height:36px;border-radius:8px;background:{{ $project->color }};display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;color:#000;flex-shrink:0">
          {{ $project->initials() }}
        </div>
        <div style="flex:1;min-width:0">
          <div style="font-size:.84rem;font-weight:500;color:var(--ink)">{{ $project->name }}</div>
          <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3);margin-top:.1rem">
            {{ $project->client?->name ?? 'No client' }} - {{ $project->reports_count }} reports
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:.5rem">
          <span class="badge {{ $project->status === 'active' ? 'badge-green' : 'badge-gray' }}">{{ $project->status }}</span>
          @if($project->latestReport)
          <span style="font-family:var(--mono);font-size:.58rem;color:var(--ink3)">{{ $project->latestReport->period_end->diffForHumans() }}</span>
          @endif
        </div>
      </a>
      @endforeach
    </div>
    @endif
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title">Recent reports</div>
      <a href="{{ route('reports.index') }}" class="btn btn-ghost btn-sm">View all</a>
    </div>
    @if($recentReports->isEmpty())
    <div class="empty-state" style="padding:2.5rem 1.5rem">
      <div class="empty-icon">R</div>
      <div class="empty-title">No reports yet</div>
      <div class="empty-sub">Generate your first report from a project.</div>
    </div>
    @else
    <div style="padding:.5rem 0">
      @foreach($recentReports as $report)
      <a href="{{ route('reports.show', $report) }}" style="display:flex;align-items:center;gap:.9rem;padding:.85rem 1.4rem;text-decoration:none;transition:background .12s;border-bottom:1px solid var(--border)" onmouseover="this.style.background='rgba(255,255,255,.02)'" onmouseout="this.style.background='transparent'">
        <div style="flex:1;min-width:0">
          <div style="font-size:.82rem;font-weight:500;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $report->title }}</div>
          <div style="font-family:var(--mono);font-size:.58rem;color:var(--ink3);margin-top:.1rem">{{ $report->periodLabel() }}</div>
        </div>
        <span class="badge {{ $report->status === 'sent' ? 'badge-green' : ($report->status === 'ready' ? 'badge-amber' : 'badge-gray') }}">{{ $report->status }}</span>
      </a>
      @endforeach
    </div>
    @endif
  </div>
</div>

@if(!$user->isPro())
<div style="margin-top:1.5rem;background:rgba(232,163,37,.05);border:1px solid rgba(232,163,37,.15);border-radius:10px;padding:1.5rem 2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem">
  <div>
    <div style="font-size:.9rem;font-weight:600;margin-bottom:.25rem">Upgrade to Pro</div>
    <div style="font-size:.82rem;color:var(--ink2)">Unlimited projects, all 6 integrations, AI summaries, and auto weekly reports.</div>
  </div>
  <a href="{{ route('billing.plans') }}" class="btn btn-primary">Upgrade - $19/month</a>
</div>
@endif
@endsection
