@extends('layouts.app')
@section('title', $report->title)
@section('breadcrumb')
  <a href="{{ route('reports.index') }}">Reports</a>
  <span class="sep">/</span>
  <span class="current">{{ $report->title }}</span>
@endsection

@push('styles')
<style>
  /* ── Layout ── */
  .show-grid {
    display: grid;
    grid-template-columns: 1fr 270px;
    gap: 1.5rem;
    align-items: start;
  }
  @media(max-width:900px){ .show-grid { grid-template-columns: 1fr; } }

  /* ── Report hero ── */
  .rpt-hero {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.4rem;
    margin-bottom: 1.5rem;
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 1rem; flex-wrap: wrap;
    position: relative; overflow: hidden;
  }
  .rpt-hero::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--amber), var(--sky));
  }
  .rpt-hero-title {
    font-family: var(--serif); font-size: 1.5rem;
    font-style: italic; font-weight: 400;
    letter-spacing: -.02em; color: var(--ink); margin-bottom: .4rem;
  }
  .rpt-hero-meta {
    display: flex; align-items: center; gap: .6rem; flex-wrap: wrap;
  }
  .rpt-hero-meta-item {
    font-family: var(--mono); font-size: .6rem; color: var(--ink3);
    display: flex; align-items: center; gap: 4px;
  }
  .rpt-hero-meta-item i { font-size: 11px; }
  .rpt-hero-meta-sep { color: var(--border2); font-size: .5rem; }
  .rpt-hero-actions { display: flex; gap: .5rem; flex-wrap: wrap; align-items: center; flex-shrink: 0; }

  /* Pill */
  .pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 99px;
    font-family: var(--mono); font-size: .58rem; font-weight: 500; white-space: nowrap;
  }
  .pill i { font-size: 8px; }
  .pill-green { background: rgba(39,201,63,.1);  color: var(--green); border: 1px solid rgba(39,201,63,.2); }
  .pill-amber { background: rgba(232,163,37,.1); color: var(--amber); border: 1px solid rgba(232,163,37,.2); }
  .pill-gray  { background: rgba(255,255,255,.04); color: var(--ink3); border: 1px solid var(--border2); }
  .pill-sent  { background: rgba(39,201,63,.08);  color: var(--green); border: 1px solid rgba(39,201,63,.2); padding: .42rem 1rem; font-size: .7rem; }

  /* ── AI Summary ── */
  .ai-summary {
    background: rgba(232,163,37,.04);
    border: 1px solid rgba(232,163,37,.15);
    border-left: 3px solid var(--amber);
    border-radius: 0 10px 10px 0;
    padding: 1.2rem 1.5rem;
    margin-bottom: 1.5rem;
    display: flex; gap: .9rem; align-items: flex-start;
  }
  .ai-summary-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: rgba(232,163,37,.12);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: .1rem;
  }
  .ai-summary-icon i { font-size: 14px; color: var(--amber); }
  .ai-summary-label {
    font-family: var(--mono); font-size: .56rem; color: var(--amber);
    letter-spacing: .12em; text-transform: uppercase; opacity: .8; margin-bottom: .35rem;
  }
  .ai-summary-text {
    font-size: .88rem; color: var(--ink2); line-height: 1.75; font-style: italic;
  }

  /* ── GitHub card ── */
  .gh-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1px; background: var(--border);
    border-radius: 8px; overflow: hidden; margin-bottom: 1rem;
  }
  .gh-stat {
    background: var(--surface2); padding: .85rem 1rem;
    display: flex; align-items: center; gap: .6rem;
  }
  .gh-stat-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: rgba(255,255,255,.05); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .gh-stat-icon i { font-size: 13px; color: var(--ink3); }
  .gh-stat-val { font-size: 1.2rem; font-weight: 700; color: var(--ink); line-height: 1; }
  .gh-stat-label { font-family: var(--mono); font-size: .56rem; color: var(--ink3); text-transform: uppercase; letter-spacing: .08em; margin-top: .1rem; }

  .repo-tag {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--bg); border: 1px solid var(--border2);
    border-radius: 99px; padding: .3rem .7rem;
    font-family: var(--mono); font-size: .6rem; color: var(--ink2);
  }
  .repo-tag i { font-size: 11px; color: var(--ink3); }

  .gh-empty-notice {
    background: rgba(232,92,58,.05); border: 1px solid rgba(232,92,58,.18);
    border-radius: 8px; padding: .85rem 1rem;
    display: flex; gap: .65rem; align-items: flex-start; margin-top: .75rem;
  }
  .gh-empty-notice i { font-size: 15px; color: var(--coral); flex-shrink: 0; margin-top: 1px; }
  .gh-empty-notice-title { font-size: .82rem; font-weight: 600; color: var(--coral); margin-bottom: .15rem; }
  .gh-empty-notice-sub { font-size: .76rem; color: var(--ink3); line-height: 1.55; }

  /* ── Source sections ── */
  .src-section { margin-bottom: 1.2rem; }

  .src-header {
    display: flex; align-items: center; gap: .65rem;
    padding: .85rem 1.4rem; border-bottom: 1px solid var(--border);
  }
  .src-header-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: var(--surface2); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: .82rem; flex-shrink: 0;
  }
  .src-header-title { font-size: .86rem; font-weight: 600; color: var(--ink); }
  .src-header-count {
    margin-left: auto; font-family: var(--mono); font-size: .6rem; color: var(--ink3);
    background: var(--surface2); border: 1px solid var(--border2);
    padding: .12rem .5rem; border-radius: 5px;
  }

  /* Entry rows */
  .entry-row {
    display: grid; grid-template-columns: 1fr auto;
    gap: 1rem; align-items: start;
    padding: .9rem 1.4rem; border-bottom: 1px solid var(--border);
    transition: background .12s;
  }
  .entry-row:last-child { border-bottom: none; }
  .entry-row:hover { background: rgba(255,255,255,.02); }

  .entry-type-tag {
    display: inline-flex; align-items: center; gap: 4px;
    font-family: var(--mono); font-size: .56rem; color: var(--ink3);
    background: var(--surface2); border: 1px solid var(--border2);
    padding: .1rem .45rem; border-radius: 5px; margin-bottom: .3rem;
  }
  .entry-type-tag i { font-size: 10px; }
  .entry-title { font-size: .84rem; font-weight: 500; color: var(--ink); margin-bottom: .2rem; }
  .entry-desc { font-size: .77rem; color: var(--ink3); line-height: 1.55; }
  .entry-link {
    font-family: var(--mono); font-size: .6rem; color: var(--sky);
    text-decoration: none; margin-top: .3rem; display: inline-flex; align-items: center; gap: 3px;
    transition: opacity .15s;
  }
  .entry-link:hover { opacity: .75; text-decoration: underline; }
  .entry-link i { font-size: 10px; }

  .entry-date { text-align: right; flex-shrink: 0; }
  .entry-date-main { font-family: var(--mono); font-size: .6rem; color: var(--ink3); }
  .entry-date-time { font-family: var(--mono); font-size: .56rem; color: var(--ink3); opacity: .45; }
  .entry-del-btn {
    background: transparent; border: none;
    color: var(--ink3); cursor: pointer; font-size: .7rem;
    opacity: .45; transition: opacity .15s, color .15s;
    padding: .2rem .35rem; border-radius: 4px; margin-top: .4rem;
    display: flex; align-items: center; gap: 3px;
  }
  .entry-del-btn:hover { opacity: 1; color: var(--coral); background: rgba(232,92,58,.08); }
  .entry-del-btn i { font-size: 13px; }

  /* ── Add entry form ── */
  .add-entry-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
  }
  @media(max-width:600px){ .add-entry-grid { grid-template-columns: 1fr; } }

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
  .info-row-val { font-size: .78rem; color: var(--ink2); text-align: right; }

  .share-url-box {
    background: var(--bg); border: 1px solid var(--border2); border-radius: 6px;
    padding: .6rem .85rem; font-family: var(--mono); font-size: .6rem;
    color: var(--ink3); word-break: break-all; margin-bottom: .75rem;
    display: flex; align-items: center; gap: 6px;
  }
  .share-url-box i { font-size: 12px; flex-shrink: 0; }

  .sidebar-actions { padding: .8rem; display: flex; flex-direction: column; gap: .4rem; }
  .sidebar-actions .btn { justify-content: center; width: 100%; }
</style>
@endpush

@section('content')
@php
  $bySource = $report->entries->groupBy('source');
  $githubEntries = $bySource->get('github', collect());
  $githubCommits = $githubEntries->where('type', 'commit')->count();
  $githubPullRequests = $githubEntries->where('type', 'pull_request')->count();
  $githubRepositories = $report->project?->integrations
    ? $report->project->integrations->where('provider', 'github')->where('active', true)->pluck('resource_name')->filter()->unique()->values()
    : collect();
@endphp

  {{-- ── Hero ── --}}
  <div class="rpt-hero">
    <div style="flex:1;min-width:0">
      <div class="rpt-hero-title">{{ $report->title }}</div>
      <div class="rpt-hero-meta">
        <span class="rpt-hero-meta-item"><i class="ti ti-calendar"></i> {{ $report->periodLabel() }}</span>
        <span class="rpt-hero-meta-sep">●</span>
        <span class="rpt-hero-meta-item"><i class="ti ti-list-details"></i> {{ $report->entries->count() }} entries</span>
        @if($report->view_count > 0)
        <span class="rpt-hero-meta-sep">●</span>
        <span class="rpt-hero-meta-item"><i class="ti ti-eye"></i> {{ $report->view_count }} views</span>
        @endif
      </div>
    </div>
    <div class="rpt-hero-actions">
      @if($report->status === 'sent')
        <span class="pill pill-sent"><i class="ti ti-circle-check"></i> Sent {{ $report->sent_at?->diffForHumans() }}</span>
      @endif
      @if($report->share_enabled)
      <a href="{{ $report->shareUrl() }}" target="_blank" class="btn btn-ghost btn-sm">
        <i class="ti ti-external-link"></i> Client view
      </a>
      @endif
      <a href="{{ route('reports.edit', $report) }}" class="btn btn-ghost btn-sm">
        <i class="ti ti-pencil"></i> Edit
      </a>
      <a href="{{ route('reports.download', $report) }}" class="btn btn-ghost btn-sm">
  <i class="ti ti-file-download"></i> Download PDF
</a>
      @if($report->client?->email && $report->status !== 'sent')
      <form action="{{ route('reports.send', $report) }}" method="POST" style="display:inline">
        @csrf
        <button type="submit" class="btn btn-primary btn-sm"
          data-confirm-form
          data-confirm-title="Send report"
          data-confirm-message="Send this report to {{ $report->client->email }} now?"
          data-confirm-submit-label="Send report">
          <i class="ti ti-send"></i> Send to client
        </button>
      </form>
      @endif
    </div>
  </div>

  {{-- ── Main grid ── --}}
  <div class="show-grid">

    {{-- Left column --}}
    <div>

      {{-- AI summary --}}
      @if($report->ai_summary)
      <div class="ai-summary">
        <div class="ai-summary-icon"><i class="ti ti-sparkles"></i></div>
        <div>
          <div class="ai-summary-label">AI Summary</div>
          <div class="ai-summary-text">{{ $report->ai_summary }}</div>
        </div>
      </div>
      @endif

      {{-- GitHub activity --}}
      @if($githubRepositories->isNotEmpty())
      <div class="card src-section">
        <div class="src-header">
          <div class="src-header-icon"><i class="ti ti-brand-github"></i></div>
          <div class="src-header-title">GitHub activity</div>
          @if(!$githubEntries->isEmpty())
          <div class="src-header-count">{{ $githubEntries->count() }} items</div>
          @endif
        </div>
        <div class="card-body">
          <div class="gh-stats">
            <div class="gh-stat">
              <div class="gh-stat-icon"><i class="ti ti-git-commit"></i></div>
              <div>
                <div class="gh-stat-val">{{ $githubCommits }}</div>
                <div class="gh-stat-label">Commits</div>
              </div>
            </div>
            <div class="gh-stat">
              <div class="gh-stat-icon"><i class="ti ti-git-pull-request"></i></div>
              <div>
                <div class="gh-stat-val">{{ $githubPullRequests }}</div>
                <div class="gh-stat-label">Pull requests</div>
              </div>
            </div>
          </div>

          <div style="font-family:var(--mono);font-size:.58rem;color:var(--ink3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.5rem;display:flex;align-items:center;gap:5px;">
            <i class="ti ti-git-branch" style="font-size:12px;"></i> Repositories
          </div>
          <div style="display:flex;flex-wrap:wrap;gap:.4rem">
            @foreach($githubRepositories as $repository)
            <span class="repo-tag"><i class="ti ti-folder-code"></i> {{ $repository }}</span>
            @endforeach
          </div>

          @if($githubEntries->isEmpty())
          <div class="gh-empty-notice">
            <i class="ti ti-alert-circle"></i>
            <div>
              <div class="gh-empty-notice-title">No GitHub activity found for this period</div>
              <div class="gh-empty-notice-sub">
                The repository is connected, but no commits or merged pull requests were detected between
                {{ $report->period_start->format('M d, Y') }} and {{ $report->period_end->format('M d, Y') }}.
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>
      @endif

      {{-- Entries by source --}}
      @forelse($bySource as $source => $entries)
      @php
        $srcConfig = match($source) {
          'github'          => ['icon' => 'ti-brand-github',    'label' => 'GitHub'],
          'linear'          => ['icon' => 'ti-triangle-square-circle', 'label' => 'Linear'],
          'notion'          => ['icon' => 'ti-notebook',         'label' => 'Notion'],
          'google_calendar' => ['icon' => 'ti-calendar-event',  'label' => 'Google Calendar'],
          'manual'          => ['icon' => 'ti-pencil',           'label' => 'Manual entries'],
          default           => ['icon' => 'ti-plug',             'label' => ucfirst($source)],
        };
      @endphp
      <div class="card src-section">
        <div class="src-header">
          <div class="src-header-icon"><i class="ti {{ $srcConfig['icon'] }}"></i></div>
          <div class="src-header-title">{{ $srcConfig['label'] }}</div>
          <div class="src-header-count">{{ $entries->count() }} {{ Str::plural('item', $entries->count()) }}</div>
        </div>

        @foreach($entries as $entry)
        <div class="entry-row">
          <div>
            <div class="entry-type-tag">
              <i class="ti ti-tag"></i> {{ $entry->type }}
            </div>
            <div class="entry-title">{{ $entry->title }}</div>
            @if($entry->description)
            <div class="entry-desc">{{ $entry->description }}</div>
            @endif
            @if($entry->source_url)
            <a href="{{ $entry->source_url }}" target="_blank" class="entry-link">
              <i class="ti ti-external-link"></i> View source
            </a>
            @endif
          </div>
          <div class="entry-date">
            @if($entry->occurred_at)
            <div class="entry-date-main">{{ $entry->occurred_at->format('M d') }}</div>
            <div class="entry-date-time">{{ $entry->occurred_at->format('H:i') }}</div>
            @endif
            <form action="{{ route('reports.entries.delete', [$report, $entry]) }}" method="POST" style="margin-top:.4rem">
              @csrf @method('DELETE')
              <button type="button" class="entry-del-btn"
                data-confirm-form
                data-confirm-title="Remove entry"
                data-confirm-message="Remove this entry from the report?"
                data-confirm-submit-label="Remove">
                <i class="ti ti-trash"></i>
              </button>
            </form>
          </div>
        </div>
        @endforeach
      </div>

      @empty
      <div class="card">
        <div class="empty-state">
          <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,.04);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:22px;color:var(--ink3)">
            <i class="ti ti-file-off"></i>
          </div>
          <div class="empty-title">No entries yet</div>
          <div class="empty-sub">Add manual entries or connect integrations to auto-populate reports.</div>
        </div>
      </div>
      @endforelse

      {{-- Add entry form --}}
      <div class="card">
        <div class="card-header">
          <div class="card-title">
            <i class="ti ti-plus"></i> Add manual entry
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('reports.entries.add', $report) }}" method="POST">
            @csrf
            <div class="add-entry-grid">
              <div class="form-group">
                <label class="form-label"><i class="ti ti-text-size"></i> Title</label>
                <input type="text" name="title" class="form-input" placeholder="What was done" required>
              </div>
              <div class="form-group">
                <label class="form-label"><i class="ti ti-tag"></i> Type</label>
                <select name="type" class="form-select">
                  <option value="task">Task</option>
                  <option value="commit">Commit</option>
                  <option value="meeting">Meeting</option>
                  <option value="decision">Decision</option>
                  <option value="review">Review</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label"><i class="ti ti-plug-connected"></i> Source</label>
                <select name="source" class="form-select">
                  <option value="manual">Manual</option>
                  <option value="github">GitHub</option>
                  <option value="linear">Linear</option>
                  <option value="notion">Notion</option>
                  <option value="google_calendar">Calendar</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label"><i class="ti ti-calendar-event"></i> Date (optional)</label>
                <input type="datetime-local" name="occurred_at" class="form-input">
              </div>
            </div>
            <div class="form-group">
              <label class="form-label"><i class="ti ti-align-left"></i> Description (optional)</label>
              <textarea name="description" class="form-textarea" style="min-height:70px" placeholder="Additional details..."></textarea>
            </div>
            <div class="form-group">
              <label class="form-label"><i class="ti ti-link"></i> Source URL (optional)</label>
              <input type="url" name="source_url" class="form-input" placeholder="https://...">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
              <i class="ti ti-plus"></i> Add entry
            </button>
          </form>
        </div>
      </div>

    </div>

    {{-- Right column --}}
    <div style="display:flex;flex-direction:column;gap:1rem;position:sticky;top:80px">

      {{-- Report details --}}
      <div class="card">
        <div class="card-header">
          <div class="card-title"><i class="ti ti-info-circle"></i> Report details</div>
        </div>
        <div style="padding:.3rem 0">
          @foreach([
            ['ti-calendar',      'Period',   $report->periodLabel()],
            ['ti-folder',        'Project',  $report->project?->name ?? '—'],
            ['ti-user',          'Client',   $report->client?->name ?? '—'],
            ['ti-circle-dot',    'Status',   ucfirst($report->status)],
            ['ti-eye',           'Views',    $report->view_count . ' views'],
            ['ti-clock',         'Created',  $report->created_at->format('M d, Y')],
          ] as [$icon, $label, $value])
          <div class="info-row">
            <span class="info-row-label"><i class="ti {{ $icon }}"></i> {{ $label }}</span>
            <span class="info-row-val">{{ $value }}</span>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Share link --}}
      @if($report->share_enabled)
      <div class="card">
        <div class="card-header">
          <div class="card-title"><i class="ti ti-share"></i> Share link</div>
        </div>
        <div class="card-body">
          <div class="share-url-box">
            <i class="ti ti-link"></i>
            {{ $report->shareUrl() }}
          </div>
          <button
            onclick="navigator.clipboard.writeText('{{ $report->shareUrl() }}').then(() => { this.innerHTML = '<i class=\'ti ti-check\'></i> Copied'; setTimeout(() => this.innerHTML = '<i class=\'ti ti-copy\'></i> Copy link', 2000) })"
            class="btn btn-ghost btn-sm" style="width:100%;justify-content:center">
            <i class="ti ti-copy"></i> Copy link
          </button>
        </div>
      </div>
      @endif

      {{-- Actions --}}
      <div class="card">
        <div class="card-header">
          <div class="card-title"><i class="ti ti-bolt"></i> Actions</div>
        </div>
        <div class="sidebar-actions">
          <a href="{{ route('reports.edit', $report) }}" class="btn btn-ghost">
            <i class="ti ti-pencil"></i> Edit report
          </a>
          @if($report->client?->email && $report->status !== 'sent')
          <form action="{{ route('reports.send', $report) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary"
              data-confirm-form
              data-confirm-title="Send report"
              data-confirm-message="Send this report to {{ $report->client->email }} now?"
              data-confirm-submit-label="Send report">
              <i class="ti ti-send"></i> Send to client
            </button>
          </form>
          @endif
          <form action="{{ route('reports.destroy', $report) }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm"
              data-confirm-form
              data-confirm-title="Delete report"
              data-confirm-message="Delete this report permanently?"
              data-confirm-submit-label="Delete report">
              <i class="ti ti-trash"></i> Delete report
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>

@endsection