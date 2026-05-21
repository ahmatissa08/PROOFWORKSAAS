@extends('layouts.app')
@section('title', 'Reports')
@section('breadcrumb')
  <span class="current">Reports</span>
@endsection

@push('styles')
<style>
  /* ── Reports list ── */
  .rpt-list { display: flex; flex-direction: column; gap: 0; }

  .rpt-item {
    display: flex; align-items: center; gap: 1rem;
    padding: .9rem 1.4rem;
    border-bottom: 1px solid var(--border);
    text-decoration: none;
    transition: background .12s;
    position: relative;
  }
  .rpt-item:last-child { border-bottom: none; }
  .rpt-item:hover { background: rgba(255,255,255,.02); }

  /* Left icon */
  .rpt-item-icon {
    width: 36px; height: 36px; border-radius: 9px;
    background: var(--surface2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .rpt-item-icon i { font-size: 16px; color: var(--ink3); }

  /* Project dot */
  .rpt-item-dot {
    width: 8px; height: 8px; border-radius: 50%;
    flex-shrink: 0; margin-top: 1px;
  }

  /* Text */
  .rpt-item-title {
    font-size: .84rem; font-weight: 500; color: var(--ink); line-height: 1.3;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .rpt-item-meta {
    font-family: var(--mono); font-size: .58rem; color: var(--ink3);
    margin-top: .15rem; display: flex; align-items: center; gap: 5px; flex-wrap: wrap;
  }
  .rpt-item-meta i { font-size: 10px; }
  .rpt-item-meta-sep { opacity: .3; }

  /* Right side */
  .rpt-item-right {
    margin-left: auto; display: flex; align-items: center; gap: .6rem; flex-shrink: 0;
  }
  .rpt-views {
    font-family: var(--mono); font-size: .58rem; color: var(--ink3);
    display: flex; align-items: center; gap: 3px;
  }
  .rpt-views i { font-size: 11px; }

  /* Pill badge */
  .pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 99px;
    font-family: var(--mono); font-size: .58rem; font-weight: 500; white-space: nowrap;
  }
  .pill i { font-size: 8px; }
  .pill-green { background: rgba(39,201,63,.1);  color: var(--green); border: 1px solid rgba(39,201,63,.2); }
  .pill-amber { background: rgba(232,163,37,.1); color: var(--amber); border: 1px solid rgba(232,163,37,.2); }
  .pill-gray  { background: rgba(255,255,255,.04); color: var(--ink3); border: 1px solid var(--border2); }

  /* Actions */
  .rpt-item-actions { display: flex; gap: .35rem; }
  .rpt-item-actions .btn { font-size: .72rem; padding: .32rem .75rem; border-radius: 6px; }

  /* Pagination */
  .pw-pagination { padding: 1rem 1.4rem; border-top: 1px solid var(--border); }
  .pw-pagination nav { display: flex; justify-content: center; }

  /* Empty */
  .pw-empty-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: rgba(255,255,255,.04); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem; font-size: 22px; color: var(--ink3);
  }

  /* Filter bar */
  .rpt-filter {
    display: flex; align-items: center; gap: .5rem;
    padding: .75rem 1.4rem; border-bottom: 1px solid var(--border);
    background: var(--surface2);
  }
  .rpt-filter-label {
    font-family: var(--mono); font-size: .6rem; color: var(--ink3);
    letter-spacing: .08em; text-transform: uppercase; margin-right: .25rem;
  }
  .rpt-count {
    font-family: var(--mono); font-size: .62rem; color: var(--ink3);
    margin-left: auto;
  }
</style>
@endpush

@section('content')

  {{-- ── Header ── --}}
  <div class="page-header">
    <div>
      <h1 class="page-title">Reports</h1>
      <p class="page-sub">All generated proof of work reports.</p>
    </div>
    <a href="{{ route('projects.index') }}" class="btn btn-ghost">
      <i class="ti ti-folder"></i> Go to projects
    </a>
  </div>

  {{-- ── Empty ── --}}
  @if($reports->isEmpty())
  <div class="card">
    <div class="empty-state">
      <div class="pw-empty-icon"><i class="ti ti-file-off"></i></div>
      <div class="empty-title">No reports yet</div>
      <div class="empty-sub">Generate your first report from a project page.</div>
      <a href="{{ route('projects.index') }}" class="btn btn-primary">
        <i class="ti ti-folder"></i> Go to projects
      </a>
    </div>
  </div>

  {{-- ── List ── --}}
  @else
  <div class="card">

    {{-- Filter bar --}}
    <div class="rpt-filter">
      <span class="rpt-filter-label"><i class="ti ti-filter" style="font-size:11px;vertical-align:-1px;margin-right:3px;"></i> All reports</span>
      <span class="rpt-count">{{ $reports->total() }} total</span>
    </div>

    <div class="rpt-list">
      @foreach($reports as $report)
      <div class="rpt-item">

        {{-- Icon --}}
        <div class="rpt-item-icon">
          @if($report->status === 'sent')
            <i class="ti ti-file-check" style="color:var(--green);"></i>
          @elseif($report->status === 'ready')
            <i class="ti ti-file-time" style="color:var(--amber);"></i>
          @else
            <i class="ti ti-file-analytics"></i>
          @endif
        </div>

        {{-- Project color dot --}}
        @if($report->project?->color)
        <div class="rpt-item-dot" style="background:{{ $report->project->color }}"></div>
        @endif

        {{-- Info --}}
        <div style="flex:1;min-width:0">
          <div class="rpt-item-title">
            <a href="{{ route('reports.show', $report) }}" style="color:var(--ink);text-decoration:none;">
              {{ $report->title }}
            </a>
          </div>
          <div class="rpt-item-meta">
            @if($report->project)
            <i class="ti ti-folder"></i> {{ $report->project->name }}
            <span class="rpt-item-meta-sep">·</span>
            @endif
            @if($report->client)
            <i class="ti ti-user"></i> {{ $report->client->name }}
            <span class="rpt-item-meta-sep">·</span>
            @endif
            <i class="ti ti-calendar"></i> {{ $report->periodLabel() }}
          </div>
        </div>

        {{-- Right --}}
        <div class="rpt-item-right">
          @if($report->view_count > 0)
          <span class="rpt-views">
            <i class="ti ti-eye"></i> {{ $report->view_count }}
          </span>
          @endif

          <span class="pill {{ match($report->status) { 'sent' => 'pill-green', 'ready' => 'pill-amber', default => 'pill-gray' } }}">
            @if($report->status === 'sent')   <i class="ti ti-send"></i>
            @elseif($report->status === 'ready') <i class="ti ti-clock"></i>
            @else                                <i class="ti ti-pencil"></i>
            @endif
            {{ $report->status }}
          </span>

          <div class="rpt-item-actions">
            <a href="{{ route('reports.show', $report) }}" class="btn btn-ghost btn-sm">
              <i class="ti ti-arrow-right"></i> View
            </a>
            @if($report->share_enabled)
            <a href="{{ $report->shareUrl() }}" target="_blank" class="btn btn-ghost btn-sm">
              <i class="ti ti-external-link"></i>
            </a>
            @endif
          </div>
        </div>

      </div>
      @endforeach
    </div>

    <div class="pw-pagination">
      {{ $reports->links() }}
    </div>

  </div>
  @endif

@endsection