@extends('layouts.app')
@section('title', $client->name)
@section('breadcrumb')
  <a href="{{ route('clients.index') }}">Clients</a>
  <span class="sep">/</span>
  <span class="current">{{ $client->name }}</span>
@endsection

@push('styles')
<style>
  .cl-show-grid {
    display: grid;
    grid-template-columns: 1fr 270px;
    gap: 1.5rem; align-items: start;
  }
  @media(max-width:900px){ .cl-show-grid { grid-template-columns: 1fr; } }

  /* Hero */
  .cl-hero {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 12px; padding: 1.5rem;
    display: flex; align-items: center; gap: 1.1rem;
    flex-wrap: wrap; margin-bottom: 1.5rem;
    position: relative; overflow: hidden;
  }
  .cl-hero::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: var(--cl-color, var(--amber));
  }
  .cl-hero-avatar {
    width: 56px; height: 56px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; font-weight: 700; color: #000; flex-shrink: 0;
    box-shadow: 0 0 0 4px rgba(255,255,255,.06);
  }
  .cl-hero-name {
    font-family: var(--serif); font-size: 1.5rem;
    font-style: italic; font-weight: 400;
    letter-spacing: -.02em; color: var(--ink); margin-bottom: .3rem;
  }
  .cl-hero-meta {
    display: flex; align-items: center; gap: .5rem; flex-wrap: wrap;
  }
  .cl-hero-meta-item {
    font-family: var(--mono); font-size: .6rem; color: var(--ink3);
    display: flex; align-items: center; gap: 4px;
  }
  .cl-hero-meta-item i { font-size: 11px; }
  .cl-hero-meta-sep { color: var(--border2); font-size: .5rem; }
  .cl-hero-actions { margin-left: auto; display: flex; gap: .5rem; flex-shrink: 0; }

  /* Rows */
  .cl-proj-row {
    display: flex; align-items: center; gap: .9rem;
    padding: .8rem 1.4rem; text-decoration: none;
    border-bottom: 1px solid var(--border); transition: background .12s;
  }
  .cl-proj-row:hover { background: rgba(255,255,255,.02); }
  .cl-proj-row:last-child { border-bottom: none; }
  .cl-proj-avatar {
    width: 32px; height: 32px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; font-weight: 700; color: #000; flex-shrink: 0;
  }
  .cl-proj-name { font-size: .82rem; font-weight: 500; color: var(--ink); }
  .cl-proj-meta {
    font-family: var(--mono); font-size: .58rem; color: var(--ink3);
    margin-top: .1rem; display: flex; align-items: center; gap: 4px;
  }
  .cl-proj-meta i { font-size: 10px; }

  .cl-rpt-row {
    display: flex; align-items: center; gap: .9rem;
    padding: .75rem 1.4rem; text-decoration: none;
    border-bottom: 1px solid var(--border); transition: background .12s;
  }
  .cl-rpt-row:hover { background: rgba(255,255,255,.02); }
  .cl-rpt-row:last-child { border-bottom: none; }
  .cl-rpt-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: var(--surface2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .cl-rpt-icon i { font-size: 13px; color: var(--ink3); }
  .cl-rpt-title { font-size: .8rem; font-weight: 500; color: var(--ink); }
  .cl-rpt-period {
    font-family: var(--mono); font-size: .57rem; color: var(--ink3);
    margin-top: .1rem; display: flex; align-items: center; gap: 4px;
  }
  .cl-rpt-period i { font-size: 10px; }

  /* Pills */
  .pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 99px;
    font-family: var(--mono); font-size: .58rem; font-weight: 500; white-space: nowrap;
  }
  .pill i { font-size: 8px; }
  .pill-green { background: rgba(39,201,63,.1);  color: var(--green); border: 1px solid rgba(39,201,63,.2); }
  .pill-amber { background: rgba(232,163,37,.1); color: var(--amber); border: 1px solid rgba(232,163,37,.2); }
  .pill-gray  { background: rgba(255,255,255,.04); color: var(--ink3); border: 1px solid var(--border2); }

  /* Sidebar */
  .cl-info-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .55rem 1.2rem; border-bottom: 1px solid var(--border);
  }
  .cl-info-row:last-child { border-bottom: none; }
  .cl-info-label {
    font-family: var(--mono); font-size: .58rem; color: var(--ink3);
    display: flex; align-items: center; gap: 5px;
  }
  .cl-info-label i { font-size: 11px; }
  .cl-info-val { font-size: .78rem; color: var(--ink2); text-align: right; }

  .cl-notes-box {
    padding: .9rem 1.2rem; border-top: 1px solid var(--border);
  }
  .cl-notes-label {
    font-family: var(--mono); font-size: .58rem; color: var(--ink3);
    display: flex; align-items: center; gap: 5px; margin-bottom: .5rem;
  }
  .cl-notes-label i { font-size: 11px; }
  .cl-notes-text { font-size: .79rem; color: var(--ink2); line-height: 1.6; }

  /* Empty small */
  .cl-empty-sm {
    padding: 2rem 1.5rem; text-align: center;
  }
  .cl-empty-sm-icon {
    width: 36px; height: 36px; border-radius: 9px;
    background: rgba(255,255,255,.04); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .65rem; font-size: 16px; color: var(--ink3);
  }
  .cl-empty-sm-title { font-size: .8rem; color: var(--ink2); margin-bottom: .5rem; }
</style>
@endpush

@section('content')

  {{-- Hero --}}
  <div class="cl-hero" style="--cl-color:{{ $client->avatar_color }}">
    <div class="cl-hero-avatar" style="background:{{ $client->avatar_color }}">{{ $client->initials() }}</div>
    <div style="flex:1;min-width:0">
      <div class="cl-hero-name">{{ $client->name }}</div>
      <div class="cl-hero-meta">
        @if($client->company)
        <span class="cl-hero-meta-item"><i class="ti ti-building"></i> {{ $client->company }}</span>
        @endif
        @if($client->company && $client->email)
        <span class="cl-hero-meta-sep">●</span>
        @endif
        @if($client->email)
        <span class="cl-hero-meta-item"><i class="ti ti-mail"></i> {{ $client->email }}</span>
        @endif
        <span class="cl-hero-meta-sep">●</span>
        <span class="cl-hero-meta-item"><i class="ti ti-clock"></i> Added {{ $client->created_at->format('M d, Y') }}</span>
      </div>
    </div>
    <div class="cl-hero-actions">
      <a href="{{ route('clients.edit', $client) }}" class="btn btn-ghost btn-sm">
        <i class="ti ti-pencil"></i> Edit
      </a>
      <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display:inline">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm"
          data-confirm-form
          data-confirm-title="Delete client"
          data-confirm-message="Delete this client permanently?"
          data-confirm-submit-label="Delete client">
          <i class="ti ti-trash"></i> Delete
        </button>
      </form>
    </div>
  </div>

  {{-- Main grid --}}
  <div class="cl-show-grid">

    {{-- Left --}}
    <div style="display:flex;flex-direction:column;gap:1.2rem">

      {{-- Projects --}}
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <i class="ti ti-folder"></i> Projects
            <span style="font-family:var(--mono);font-size:.7rem;color:var(--ink3);font-weight:400">({{ $client->projects->count() }})</span>
          </div>
          <a href="{{ route('projects.create') }}" class="btn btn-ghost btn-sm">
            <i class="ti ti-plus"></i> New project
          </a>
        </div>

        @forelse($client->projects as $project)
        <a href="{{ route('projects.show', $project) }}" class="cl-proj-row">
          <div class="cl-proj-avatar" style="background:{{ $project->color }}">{{ $project->initials() }}</div>
          <div style="flex:1;min-width:0">
            <div class="cl-proj-name">{{ $project->name }}</div>
            <div class="cl-proj-meta">
              <i class="ti ti-refresh"></i> {{ ucfirst($project->report_frequency ?? 'manual') }}
            </div>
          </div>
          <span class="pill {{ $project->status === 'active' ? 'pill-green' : 'pill-gray' }}">
            <i class="ti ti-circle-filled"></i> {{ $project->status }}
          </span>
        </a>
        @empty
        <div class="cl-empty-sm">
          <div class="cl-empty-sm-icon"><i class="ti ti-folder-open"></i></div>
          <div class="cl-empty-sm-title">No projects yet</div>
          <a href="{{ route('projects.create') }}" class="btn btn-ghost btn-sm">
            <i class="ti ti-plus"></i> Create project
          </a>
        </div>
        @endforelse
      </div>

      {{-- Reports --}}
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <i class="ti ti-file-analytics"></i> Reports
            <span style="font-family:var(--mono);font-size:.7rem;color:var(--ink3);font-weight:400">({{ $client->reports->count() }})</span>
          </div>
        </div>

        @forelse($client->reports as $report)
        <a href="{{ route('reports.show', $report) }}" class="cl-rpt-row">
          <div class="cl-rpt-icon">
            @if($report->status === 'sent')
              <i class="ti ti-file-check" style="color:var(--green);"></i>
            @elseif($report->status === 'ready')
              <i class="ti ti-file-time" style="color:var(--amber);"></i>
            @else
              <i class="ti ti-file-analytics"></i>
            @endif
          </div>
          <div style="flex:1;min-width:0">
            <div class="cl-rpt-title">{{ $report->title }}</div>
            <div class="cl-rpt-period">
              <i class="ti ti-calendar"></i> {{ $report->periodLabel() }}
            </div>
          </div>
          <span class="pill {{ match($report->status) { 'sent' => 'pill-green', 'ready' => 'pill-amber', default => 'pill-gray' } }}">
            @if($report->status === 'sent') <i class="ti ti-send"></i>
            @elseif($report->status === 'ready') <i class="ti ti-clock"></i>
            @else <i class="ti ti-pencil"></i>
            @endif
            {{ $report->status }}
          </span>
        </a>
        @empty
        <div class="cl-empty-sm">
          <div class="cl-empty-sm-icon"><i class="ti ti-file-off"></i></div>
          <div class="cl-empty-sm-title">No reports yet</div>
          <p style="font-size:.75rem;color:var(--ink3)">Generate a report from a project.</p>
        </div>
        @endforelse
      </div>

    </div>

    {{-- Right sidebar --}}
    <div style="display:flex;flex-direction:column;gap:1rem;position:sticky;top:80px">
      <div class="card">
        <div class="card-header">
          <div class="card-title"><i class="ti ti-info-circle"></i> Client info</div>
        </div>
        <div style="padding:.3rem 0">
          @foreach([
            ['ti-user',           'Name',     $client->name],
            ['ti-building',       'Company',  $client->company ?? '—'],
            ['ti-mail',           'Email',    $client->email ?? '—'],
            ['ti-folder',         'Projects', $client->projects->count()],
            ['ti-file-analytics', 'Reports',  $client->reports->count()],
            ['ti-clock',          'Added',    $client->created_at->format('M d, Y')],
          ] as [$icon, $label, $value])
          <div class="cl-info-row">
            <span class="cl-info-label"><i class="ti {{ $icon }}"></i> {{ $label }}</span>
            <span class="cl-info-val">{{ $value }}</span>
          </div>
          @endforeach
        </div>

        @if($client->notes)
        <div class="cl-notes-box">
          <div class="cl-notes-label"><i class="ti ti-notes"></i> Notes</div>
          <div class="cl-notes-text">{{ $client->notes }}</div>
        </div>
        @endif

        <div style="padding:.8rem 1.2rem;border-top:1px solid var(--border)">
          <a href="{{ route('clients.edit', $client) }}" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center">
            <i class="ti ti-pencil"></i> Edit client
          </a>
        </div>
      </div>
    </div>

  </div>

@endsection