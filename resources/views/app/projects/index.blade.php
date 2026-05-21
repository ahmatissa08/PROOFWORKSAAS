@extends('layouts.app')
@section('title', 'Projects')
@section('breadcrumb')
  <span class="current">Projects</span>
@endsection

@push('styles')
<style>
  /* ── Project cards grid ── */
  .proj-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.2rem;
  }

  .proj-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    transition: border-color .2s, transform .15s;
    display: flex;
    flex-direction: column;
  }
  .proj-card:hover {
    border-color: var(--border2);
    transform: translateY(-2px);
  }

  /* Color accent bar top */
  .proj-card-accent {
    height: 3px;
    width: 100%;
    flex-shrink: 0;
  }

  .proj-card-body { padding: 1.3rem; flex: 1; display: flex; flex-direction: column; gap: 1rem; }

  /* Header row */
  .proj-card-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: .8rem;
  }
  .proj-card-avatar-wrap { display: flex; align-items: center; gap: .75rem; }
  .proj-card-avatar {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; font-weight: 700; color: #000; flex-shrink: 0;
  }
  .proj-card-name { font-size: .88rem; font-weight: 600; color: var(--ink); line-height: 1.3; }
  .proj-card-client {
    font-family: var(--mono); font-size: .58rem; color: var(--ink3);
    margin-top: .15rem; display: flex; align-items: center; gap: 4px;
  }
  .proj-card-client i { font-size: 10px; }

  /* Badge */
  .proj-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 99px;
    font-family: var(--mono); font-size: .58rem; font-weight: 500;
    white-space: nowrap; flex-shrink: 0;
  }
  .proj-badge i { font-size: 7px; }
  .proj-badge-green { background: rgba(39,201,63,.1); color: var(--green); border: 1px solid rgba(39,201,63,.2); }
  .proj-badge-gray  { background: rgba(255,255,255,.04); color: var(--ink3); border: 1px solid var(--border2); }

  /* Description */
  .proj-card-desc { font-size: .79rem; color: var(--ink3); line-height: 1.6; margin: -.2rem 0; }

  /* Stats row */
  .proj-stats {
    display: flex;
    gap: .5rem;
  }
  .proj-stat {
    flex: 1;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: .6rem .8rem;
    text-align: center;
  }
  .proj-stat-val {
    font-family: var(--serif); font-size: 1.3rem;
    font-style: italic; line-height: 1; margin-bottom: .15rem;
    color: var(--ink);
  }
  .proj-stat-val.amber { color: var(--amber); }
  .proj-stat-label {
    font-family: var(--mono); font-size: .52rem;
    color: var(--ink3); text-transform: uppercase; letter-spacing: .08em;
  }

  /* Actions */
  .proj-card-actions { display: flex; gap: .5rem; margin-top: auto; }
  .proj-card-actions .btn { font-size: .75rem; padding: .42rem .9rem; border-radius: 7px; }

  /* Empty state */
  .proj-empty-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: rgba(255,255,255,.04); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem; font-size: 22px; color: var(--ink3);
  }
</style>
@endpush

@section('content')

  {{-- ── Header ── --}}
  <div class="page-header">
    <div>
      <h1 class="page-title">Projects</h1>
      <p class="page-sub">Manage your client projects and integrations.</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">
      <i class="ti ti-plus"></i> New project
    </a>
  </div>

  {{-- ── Empty ── --}}
  @if($projects->isEmpty())
  <div class="card">
    <div class="empty-state">
      <div class="proj-empty-icon"><i class="ti ti-folder-open"></i></div>
      <div class="empty-title">No projects yet</div>
      <div class="empty-sub">Create a project, connect your tools, and start generating reports automatically.</div>
      <a href="{{ route('projects.create') }}" class="btn btn-primary">
        <i class="ti ti-plus"></i> Create first project
      </a>
    </div>
  </div>

  {{-- ── Grid ── --}}
  @else
  <div class="proj-grid">
    @foreach($projects as $project)
    <div class="proj-card">

      {{-- Accent bar --}}
      <div class="proj-card-accent" style="background:{{ $project->color }}"></div>

      <div class="proj-card-body">

        {{-- Head --}}
        <div class="proj-card-head">
          <div class="proj-card-avatar-wrap">
            <div class="proj-card-avatar" style="background:{{ $project->color }}">
              {{ $project->initials() }}
            </div>
            <div>
              <div class="proj-card-name">{{ $project->name }}</div>
              <div class="proj-card-client">
                <i class="ti ti-user"></i> {{ $project->client?->name ?? 'No client' }}
              </div>
            </div>
          </div>
          <span class="proj-badge {{ $project->status === 'active' ? 'proj-badge-green' : 'proj-badge-gray' }}">
            <i class="ti ti-circle-filled"></i> {{ $project->status }}
          </span>
        </div>

        {{-- Description --}}
        @if($project->description)
        <p class="proj-card-desc">{{ Str::limit($project->description, 90) }}</p>
        @endif

        {{-- Stats --}}
        <div class="proj-stats">
          <div class="proj-stat">
            <div class="proj-stat-val amber">{{ $project->reports_count }}</div>
            <div class="proj-stat-label"><i class="ti ti-file-analytics" style="font-size:10px;vertical-align:-1px;"></i> Reports</div>
          </div>
          <div class="proj-stat">
            <div class="proj-stat-val">{{ $project->integrations_count }}</div>
            <div class="proj-stat-label"><i class="ti ti-plug-connected" style="font-size:10px;vertical-align:-1px;"></i> Integrations</div>
          </div>
          @if($project->latestReport)
          <div class="proj-stat">
            <div class="proj-stat-val" style="font-size:1rem;color:var(--ink2);">{{ $project->latestReport->period_end->format('M d') }}</div>
            <div class="proj-stat-label"><i class="ti ti-calendar" style="font-size:10px;vertical-align:-1px;"></i> Last report</div>
          </div>
          @endif
        </div>

        {{-- Actions --}}
        <div class="proj-card-actions">
          <a href="{{ route('projects.show', $project) }}" class="btn btn-primary" style="flex:1;justify-content:center;">
            <i class="ti ti-arrow-right"></i> View
          </a>
          <a href="{{ route('projects.edit', $project) }}" class="btn btn-ghost">
            <i class="ti ti-pencil"></i> Edit
          </a>
        </div>

      </div>
    </div>
    @endforeach
  </div>
  @endif

@endsection