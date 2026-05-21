@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
  <span class="current">Dashboard</span>
@endsection

@section('content')

  {{-- Page header --}}
  <div class="dash-header">
    <div>
      <h1 class="dash-greeting">
        Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, 
        <span class="dash-name">{{ explode(' ', $user->name)[0] }}</span>.
      </h1>
      <p class="dash-sub">Here's what's happening with your projects.</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      New project
    </a>
  </div>

  {{-- Stats grid --}}
  <div class="dash-stats">
    <div class="stat-box">
      <div class="stat-icon-wrap" style="background:rgba(232,163,37,.15)">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#e8a325" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
      </div>
      <div class="stat-label">Active projects</div>
      <div class="stat-num amber">{{ $stats['projects'] }}</div>
      <div class="stat-hint">{{ $user->isPro() ? 'Unlimited' : '1 on free plan' }}</div>
    </div>

    <div class="stat-box">
      <div class="stat-icon-wrap" style="background:rgba(255,255,255,.06)">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--ink2)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
      </div>
      <div class="stat-label">Reports generated</div>
      <div class="stat-num">{{ $stats['reports_total'] }}</div>
      <div class="stat-hint">{{ $stats['reports_sent'] }} sent to clients</div>
    </div>

    <div class="stat-box">
      <div class="stat-icon-wrap" style="background:rgba(74,158,255,.12)">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#58a6ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </div>
      <div class="stat-label">Clients</div>
      <div class="stat-num blue">{{ $stats['clients'] }}</div>
      <div class="stat-hint">Active clients</div>
    </div>

    <div class="stat-box">
      <div class="stat-icon-wrap" style="background:rgba(52,211,153,.12)">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
      </div>
      <div class="stat-label">Report views</div>
      <div class="stat-num green">{{ number_format($stats['views_total']) }}</div>
      <div class="stat-hint">By your clients</div>
    </div>
  </div>

  {{-- Two columns: Projects + Reports --}}
  <div class="dash-grid">

    {{-- Projects --}}
    <div class="dash-card">
      <div class="dash-card-head">
        <div class="dash-card-title">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          Your projects
        </div>
        <a href="{{ route('projects.create') }}" class="btn btn-ghost btn-sm">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
          Add
        </a>
      </div>

      @if($projects->isEmpty())
        <div class="dash-empty">
          <div class="dash-empty-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
          </div>
          <div class="dash-empty-title">No projects yet</div>
          <div class="dash-empty-sub">Create your first project to start generating proof of work reports.</div>
          <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
            Create first project
          </a>
        </div>
      @else
        @foreach($projects as $project)
        <a href="{{ route('projects.show', $project) }}" class="dash-proj-row">
          <div class="proj-avatar" style="background:{{ $project->color }}">
            {{ $project->initials() }}
          </div>
          <div class="proj-info">
            <div class="proj-name">{{ $project->name }}</div>
            <div class="proj-meta">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              {{ $project->client?->name ?? 'No client' }} — {{ $project->reports_count }} reports
            </div>
          </div>
          <div class="proj-badges">
            <span class="badge {{ $project->status === 'active' ? 'badge-green' : 'badge-gray' }}">
              <svg width="8" height="8" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
              {{ $project->status }}
            </span>
            @if($project->latestReport)
            <span class="proj-time">{{ $project->latestReport->period_end->diffForHumans() }}</span>
            @endif
          </div>
        </a>
        @endforeach
      @endif
    </div>

    {{-- Recent reports --}}
    <div class="dash-card">
      <div class="dash-card-head">
        <div class="dash-card-title">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
          Recent reports
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-ghost btn-sm">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
          View all
        </a>
      </div>

      @if($recentReports->isEmpty())
        <div class="dash-empty">
          <div class="dash-empty-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
          </div>
          <div class="dash-empty-title">No reports yet</div>
          <div class="dash-empty-sub">Generate your first report from a project.</div>
        </div>
      @else
        @foreach($recentReports as $report)
        <a href="{{ route('reports.show', $report) }}" class="dash-report-row">
          <div class="report-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
          </div>
          <div class="report-info">
            <div class="report-title">{{ $report->title }}</div>
            <div class="report-period">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              {{ $report->periodLabel() }}
            </div>
          </div>
          <span class="badge {{ $report->status === 'sent' ? 'badge-green' : ($report->status === 'ready' ? 'badge-amber' : 'badge-gray') }}">
            @if($report->status === 'sent')
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            @elseif($report->status === 'ready')
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            @else
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            @endif
            {{ $report->status }}
          </span>
        </a>
        @endforeach
      @endif
    </div>
  </div>

  {{-- Upgrade bar --}}
  @if(!$user->isPro())
  <div class="upgrade-bar">
    <div class="upgrade-info">
      <div class="upgrade-title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg>
        Upgrade to Pro
      </div>
      <div class="upgrade-sub">
        Unlimited projects, all 6 integrations, AI summaries, and auto weekly reports.
      </div>
    </div>
    <a href="{{ route('billing.plans') }}" class="btn btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      Upgrade — $19/month
    </a>
  </div>
  @endif

@endsection

@push('styles')
<style>
/* ── Header ── */
.dash-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.75rem;
  padding-bottom: 1.25rem;
  border-bottom: 1px solid var(--border);
  flex-wrap: wrap;
  gap: 1rem;
}
.dash-greeting {
  font-family: var(--serif);
  font-size: 1.8rem;
  font-style: italic;
  font-weight: 400;
  letter-spacing: -0.02em;
  color: var(--ink);
  margin: 0;
}
.dash-name {
  color: var(--amber);
}
.dash-sub {
  font-family: var(--sans);
  font-size: 0.85rem;
  color: var(--ink3);
  margin-top: 0.2rem;
}

/* ── Stats ── */
.dash-stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-bottom: 1.5rem;
}
.stat-box {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 1rem 1.1rem;
  transition: transform 0.2s ease, border-color 0.2s ease;
}
.stat-box:hover {
  transform: translateY(-2px);
  border-color: rgba(255,255,255,0.08);
}
.stat-icon-wrap {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 0.75rem;
}
.stat-label {
  font-family: var(--mono);
  font-size: 0.58rem;
  color: var(--ink3);
  letter-spacing: 0.1em;
  text-transform: uppercase;
  margin-bottom: 0.35rem;
  font-weight: 500;
}
.stat-num {
  font-family: var(--serif);
  font-size: 1.7rem;
  font-style: italic;
  font-weight: 400;
  line-height: 1;
  margin-bottom: 0.3rem;
  color: var(--ink);
}
.stat-num.amber { color: var(--amber); }
.stat-num.blue  { color: #58a6ff; }
.stat-num.green { color: #34d399; }
.stat-hint {
  font-family: var(--mono);
  font-size: 0.58rem;
  color: var(--ink3);
}

/* ── Grid ── */
.dash-grid {
  display: grid;
  grid-template-columns: 1.4fr 1fr;
  gap: 1.25rem;
  margin-bottom: 1.5rem;
}

/* ── Card ── */
.dash-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 10px;
  overflow: hidden;
}
.dash-card-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.9rem 1.25rem;
  border-bottom: 1px solid var(--border);
}
.dash-card-title {
  font-family: var(--sans);
  font-size: 0.88rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--ink);
}
.dash-card-title svg {
  color: var(--ink3);
}

/* ── Project rows ── */
.dash-proj-row {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  padding: 0.8rem 1.25rem;
  text-decoration: none;
  border-bottom: 1px solid var(--border);
  transition: background 0.12s;
}
.dash-proj-row:hover {
  background: rgba(255,255,255,0.03);
}
.dash-proj-row:last-child {
  border-bottom: none;
}
.proj-avatar {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.68rem;
  font-weight: 700;
  color: #000;
  flex-shrink: 0;
  font-family: var(--sans);
}
.proj-info {
  flex: 1;
  min-width: 0;
}
.proj-name {
  font-family: var(--sans);
  font-size: 0.82rem;
  font-weight: 500;
  color: var(--ink);
}
.proj-meta {
  font-family: var(--mono);
  font-size: 0.58rem;
  color: var(--ink3);
  margin-top: 0.1rem;
  display: flex;
  align-items: center;
  gap: 4px;
}
.proj-meta svg {
  opacity: 0.5;
}
.proj-badges {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-shrink: 0;
}
.proj-time {
  font-family: var(--mono);
  font-size: 0.57rem;
  color: var(--ink3);
}

/* ── Report rows ── */
.dash-report-row {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  padding: 0.75rem 1.25rem;
  text-decoration: none;
  border-bottom: 1px solid var(--border);
  transition: background 0.12s;
}
.dash-report-row:hover {
  background: rgba(255,255,255,0.03);
}
.dash-report-row:last-child {
  border-bottom: none;
}
.report-icon {
  width: 30px;
  height: 30px;
  border-radius: 7px;
  background: rgba(255,255,255,0.05);
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  color: var(--ink3);
}
.report-info {
  flex: 1;
  min-width: 0;
}
.report-title {
  font-family: var(--sans);
  font-size: 0.8rem;
  font-weight: 500;
  color: var(--ink);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.report-period {
  font-family: var(--mono);
  font-size: 0.57rem;
  color: var(--ink3);
  margin-top: 0.1rem;
  display: flex;
  align-items: center;
  gap: 4px;
}
.report-period svg {
  opacity: 0.5;
}

/* ── Upgrade bar ── */
.upgrade-bar {
  background: rgba(232,163,37,0.06);
  border: 1px solid rgba(232,163,37,0.2);
  border-left: 3px solid var(--amber);
  border-radius: 10px;
  padding: 1.1rem 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
}
.upgrade-title {
  font-family: var(--sans);
  font-size: 0.9rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 0.25rem;
  color: var(--ink);
}
.upgrade-title svg {
  color: var(--amber);
}
.upgrade-sub {
  font-family: var(--sans);
  font-size: 0.8rem;
  color: var(--ink2);
}

/* ── Empty state ── */
.dash-empty {
  padding: 2.5rem 1.5rem;
  text-align: center;
}
.dash-empty-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: rgba(255,255,255,0.05);
  border: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 0.75rem;
  color: var(--ink3);
}
.dash-empty-title {
  font-family: var(--serif);
  font-size: 1.2rem;
  font-style: italic;
  font-weight: 400;
  margin-bottom: 0.25rem;
  color: var(--ink2);
}
.dash-empty-sub {
  font-family: var(--sans);
  font-size: 0.78rem;
  color: var(--ink3);
  margin-bottom: 1rem;
}

/* ── Responsive ── */
@media (max-width: 900px) {
  .dash-stats {
    grid-template-columns: repeat(2, 1fr);
  }
  .dash-grid {
    grid-template-columns: 1fr;
  }
}
@media (max-width: 480px) {
  .dash-stats {
    grid-template-columns: 1fr;
  }
  .dash-proj-row {
    flex-wrap: wrap;
  }
  .proj-badges {
    width: 100%;
    margin-top: 0.3rem;
  }
}
</style>
@endpush