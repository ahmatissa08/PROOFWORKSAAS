@extends('layouts.app')
@section('title', $project->name)
@section('breadcrumb')
  <a href="{{ route('projects.index') }}">Projects</a>
  <span class="sep">/</span>
  <span class="current">{{ $project->name }}</span>
@endsection

@push('styles')
<style>
  /* ── Layout ── */
  .show-grid {
    display: grid;
    grid-template-columns: 1fr 290px;
    gap: 1.5rem;
    align-items: start;
  }
  @media(max-width:900px){ .show-grid { grid-template-columns: 1fr; } }

  /* ── Project hero header ── */
  .proj-hero {
    display: flex; align-items: center; gap: 1rem;
    padding: 1.4rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    margin-bottom: 1.5rem;
    position: relative; overflow: hidden;
  }
  .proj-hero::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: var(--accent-color, var(--amber));
  }
  .proj-hero-avatar {
    width: 52px; height: 52px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: .95rem; font-weight: 700; color: #000; flex-shrink: 0;
  }
  .proj-hero-name {
    font-family: var(--serif); font-size: 1.5rem;
    font-style: italic; font-weight: 400; color: var(--ink);
    letter-spacing: -.02em; line-height: 1.2;
  }
  .proj-hero-meta {
    display: flex; align-items: center; gap: .6rem;
    margin-top: .3rem; flex-wrap: wrap;
  }
  .proj-hero-meta-item {
    font-family: var(--mono); font-size: .6rem; color: var(--ink3);
    display: flex; align-items: center; gap: 4px;
  }
  .proj-hero-meta-item i { font-size: 11px; }
  .proj-hero-meta-sep { font-size: .5rem; color: var(--border2); }
  .proj-hero-actions { margin-left: auto; display: flex; gap: .5rem; flex-shrink: 0; }

  /* ── Generate report card ── */
  .gen-card { margin-bottom: 1.5rem; }
  .gen-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: .8rem; align-items: end;
  }
  @media(max-width:600px){ .gen-grid { grid-template-columns: 1fr; } }

  /* ── Report rows ── */
  .rpt-row {
    display: flex; align-items: center; gap: 1rem;
    padding: .85rem 1.4rem; text-decoration: none;
    border-bottom: 1px solid var(--border);
    transition: background .12s;
  }
  .rpt-row:hover { background: rgba(255,255,255,.02); }
  .rpt-row:last-child { border-bottom: none; }
  .rpt-row-icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: var(--surface2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .rpt-row-icon i { font-size: 15px; color: var(--ink3); }
  .rpt-row-title { font-size: .83rem; font-weight: 500; color: var(--ink); }
  .rpt-row-meta {
    font-family: var(--mono); font-size: .58rem; color: var(--ink3);
    margin-top: .1rem; display: flex; align-items: center; gap: 5px;
  }
  .rpt-row-meta i { font-size: 10px; }

  /* ── Badge (pill style) ── */
  .pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 99px;
    font-family: var(--mono); font-size: .58rem; font-weight: 500;
  }
  .pill i { font-size: 8px; }
  .pill-green { background: rgba(39,201,63,.1);  color: var(--green); border: 1px solid rgba(39,201,63,.2); }
  .pill-amber { background: rgba(232,163,37,.1); color: var(--amber); border: 1px solid rgba(232,163,37,.2); }
  .pill-gray  { background: rgba(255,255,255,.04); color: var(--ink3); border: 1px solid var(--border2); }

  /* ── Sidebar cards ── */
  .info-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .55rem 1.2rem; border-bottom: 1px solid var(--border);
  }
  .info-row:last-child { border-bottom: none; }
  .info-row-label {
    font-family: var(--mono); font-size: .58rem; color: var(--ink3);
    display: flex; align-items: center; gap: 5px;
  }
  .info-row-label i { font-size: 11px; }
  .info-row-val { font-size: .78rem; color: var(--ink2); }

  /* ── Integration rows ── */
  .int-row {
    display: flex; align-items: center; gap: .75rem;
    padding: .75rem 1.2rem; border-bottom: 1px solid var(--border);
  }
  .int-row:last-child { border-bottom: none; }
  .int-icon {
    width: 30px; height: 30px; border-radius: 7px;
    border: 1px solid var(--border2); background: var(--surface2);
    display: flex; align-items: center; justify-content: center;
    font-size: .82rem; flex-shrink: 0;
  }
  .int-name { font-size: .8rem; font-weight: 500; color: var(--ink); }
  .int-resource { font-family: var(--mono); font-size: .58rem; color: var(--ink3); margin-top: .1rem; }

  /* ── Empty small ── */
  .empty-sm {
    padding: 1.8rem 1.2rem; text-align: center;
  }
  .empty-sm-icon {
    width: 36px; height: 36px; border-radius: 9px;
    background: rgba(255,255,255,.04); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .7rem; font-size: 16px; color: var(--ink3);
  }
  .empty-sm-title { font-size: .8rem; color: var(--ink2); margin-bottom: .5rem; }
</style>
@endpush

@section('content')

  {{-- ── Project hero ── --}}
  <div class="proj-hero" style="--accent-color:{{ $project->color }}">
    <div class="proj-hero-avatar" style="background:{{ $project->color }}">
      {{ $project->initials() }}
    </div>
    <div style="flex:1;min-width:0">
      <div class="proj-hero-name">{{ $project->name }}</div>
      <div class="proj-hero-meta">
        <span class="proj-hero-meta-item">
          <i class="ti ti-user"></i> {{ $project->client?->name ?? 'No client' }}
        </span>
        <span class="proj-hero-meta-sep">●</span>
        <span class="proj-hero-meta-item">
          <i class="ti ti-refresh"></i> {{ ucfirst($project->report_frequency) }}
        </span>
        <span class="proj-hero-meta-sep">●</span>
        <span class="proj-hero-meta-item">
          <i class="ti ti-calendar"></i> Created {{ $project->created_at->format('M d, Y') }}
        </span>
      </div>
    </div>
    <div class="proj-hero-actions">
      <span class="pill {{ $project->status === 'active' ? 'pill-green' : 'pill-gray' }}">
        <i class="ti ti-circle-filled"></i> {{ ucfirst($project->status) }}
      </span>
      <a href="{{ route('projects.edit', $project) }}" class="btn btn-ghost btn-sm">
        <i class="ti ti-pencil"></i> Edit
      </a>
    </div>
  </div>

  {{-- ── Main grid ── --}}
  <div class="show-grid">

    {{-- Left column --}}
    <div>

      {{-- Generate report --}}
      <div class="card gen-card">
        <div class="card-header">
          <div class="card-title">
            <i class="ti ti-sparkles"></i> Generate new report
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('reports.generate', $project) }}" method="POST">
            @csrf
            <div class="gen-grid">
              <div>
                <label class="form-label"><i class="ti ti-calendar-event"></i> Period start</label>
                <input type="date" name="period_start" class="form-input"
                  value="{{ now()->startOfWeek()->format('Y-m-d') }}" required>
              </div>
              <div>
                <label class="form-label"><i class="ti ti-calendar-event"></i> Period end</label>
                <input type="date" name="period_end" class="form-input"
                  value="{{ now()->format('Y-m-d') }}" required>
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="ti ti-bolt"></i> Generate
              </button>
            </div>
          </form>
        </div>
      </div>

      {{-- Reports list --}}
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <i class="ti ti-file-analytics"></i>
            Reports
            <span style="font-family:var(--mono);font-size:.7rem;color:var(--ink3);font-weight:400">({{ $project->reports->count() }})</span>
          </div>
          <a href="{{ route('reports.index') }}" class="btn btn-ghost btn-sm">
            <i class="ti ti-list"></i> View all
          </a>
        </div>

        @forelse($project->reports as $report)
        <a href="{{ route('reports.show', $report) }}" class="rpt-row">
          <div class="rpt-row-icon">
            <i class="ti ti-file-text"></i>
          </div>
          <div style="flex:1;min-width:0">
            <div class="rpt-row-title">{{ $report->title }}</div>
            <div class="rpt-row-meta">
              <i class="ti ti-calendar"></i> {{ $report->periodLabel() }}
              <span style="opacity:.3">·</span>
              <i class="ti ti-list-details"></i> {{ $report->entries_count ?? $report->entries->count() }} entries
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:.6rem;flex-shrink:0">
            <span class="pill {{ match($report->status) { 'sent' => 'pill-green', 'ready' => 'pill-amber', default => 'pill-gray' } }}">
              @if($report->status === 'sent')   <i class="ti ti-send"></i>
              @elseif($report->status === 'ready') <i class="ti ti-clock"></i>
              @else                              <i class="ti ti-pencil"></i>
              @endif
              {{ $report->status }}
            </span>
            @if($report->view_count > 0)
            <span style="font-family:var(--mono);font-size:.58rem;color:var(--ink3);display:flex;align-items:center;gap:3px">
              <i class="ti ti-eye" style="font-size:11px;"></i> {{ $report->view_count }}
            </span>
            @endif
          </div>
        </a>

        @empty
        <div class="empty-sm">
          <div class="empty-sm-icon"><i class="ti ti-file-off"></i></div>
          <div class="empty-sm-title">No reports yet</div>
          <p style="font-size:.75rem;color:var(--ink3)">Use the form above to generate your first report.</p>
        </div>
        @endforelse
      </div>

    </div>

    {{-- Right column --}}
    <div style="display:flex;flex-direction:column;gap:1rem">

      {{-- Project info --}}
      <div class="card">
        <div class="card-header">
          <div class="card-title"><i class="ti ti-info-circle"></i> Project info</div>
        </div>
        <div style="padding:.3rem 0">
          @foreach([
            ['ti-user',        'Client',      $project->client?->name ?? '—'],
            ['ti-circle-dot',  'Status',      ucfirst($project->status)],
            ['ti-refresh',     'Frequency',   ucfirst($project->report_frequency)],
            ['ti-calendar-week','Report day', ucfirst($project->report_day)],
            ['ti-send',        'Auto-send',   $project->auto_send ? 'Enabled' : 'Disabled'],
            ['ti-clock',       'Created',     $project->created_at->format('M d, Y')],
          ] as [$icon, $label, $value])
          <div class="info-row">
            <span class="info-row-label"><i class="ti {{ $icon }}"></i> {{ $label }}</span>
            <span class="info-row-val">{{ $value }}</span>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Integrations --}}
      <div class="card">
        <div class="card-header">
          <div class="card-title"><i class="ti ti-plug-connected"></i> Integrations</div>
          <a href="{{ route('integrations.index', ['project_id' => $project->id]) }}" class="btn btn-ghost btn-sm">
            <i class="ti ti-settings"></i> Manage
          </a>
        </div>

        @forelse($project->integrations as $integration)
        <div class="int-row">
          <div class="int-icon">{{ $integration->providerIcon() }}</div>
          <div style="flex:1;min-width:0">
            <div class="int-name">{{ $integration->providerLabel() }}</div>
            @if($integration->resource_name)
            <div class="int-resource">{{ $integration->resource_name }}</div>
            @endif
          </div>
          <span class="pill {{ $integration->active ? 'pill-green' : 'pill-gray' }}">
            <i class="ti ti-circle-filled"></i>
            {{ $integration->active ? 'active' : 'off' }}
          </span>
        </div>

        @empty
        <div class="empty-sm">
          <div class="empty-sm-icon"><i class="ti ti-plug"></i></div>
          <div class="empty-sm-title">No integrations connected</div>
          <a href="{{ route('integrations.index', ['project_id' => $project->id]) }}" class="btn btn-ghost btn-sm">
            <i class="ti ti-plus"></i> Connect tools
          </a>
        </div>
        @endforelse
      </div>

    </div>
  </div>

@endsection