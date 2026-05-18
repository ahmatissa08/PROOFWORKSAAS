@extends('layouts.app')
@section('title', $report->title)
@section('breadcrumb')
  <a href="{{ route('reports.index') }}">Reports</a>
  <span class="sep">/</span>
  <span class="current">{{ $report->title }}</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">{{ $report->title }}</h1>
    <p class="page-sub">{{ $report->periodLabel() }} - {{ $report->entries->count() }} entries</p>
  </div>
  <div style="display:flex;gap:.6rem;flex-wrap:wrap">
    @if($report->share_enabled)
    <a href="{{ $report->shareUrl() }}" target="_blank" class="btn btn-ghost">Client view</a>
    @endif
    <a href="{{ route('reports.edit', $report) }}" class="btn btn-ghost">Edit</a>
    @if($report->client?->email && $report->status !== 'sent')
    <form action="{{ route('reports.send', $report) }}" method="POST" style="display:inline">
      @csrf
      <button type="submit" class="btn btn-primary" data-confirm-form data-confirm-title="Send report" data-confirm-message="Send this report to {{ $report->client->email }} now?" data-confirm-submit-label="Send report">
        Send to client
      </button>
    </form>
    @elseif($report->status === 'sent')
    <span class="badge badge-green" style="padding:.5rem .85rem;font-size:.72rem">Sent {{ $report->sent_at?->diffForHumans() }}</span>
    @endif
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 280px;gap:1.5rem;align-items:start">
  <div>
    @if($report->ai_summary)
    <div style="background:rgba(232,163,37,.04);border:1px solid rgba(232,163,37,.15);border-left:3px solid var(--amber);border-radius:0 8px 8px 0;padding:1.2rem 1.5rem;margin-bottom:1.5rem">
      <div style="font-family:var(--mono);font-size:.58rem;color:var(--amber);letter-spacing:.12em;text-transform:uppercase;opacity:.8;margin-bottom:.4rem">AI Summary</div>
      <p style="font-size:.9rem;color:var(--ink2);line-height:1.7;font-style:italic">{{ $report->ai_summary }}</p>
    </div>
    @endif

    @php $bySource = $report->entries->groupBy('source'); @endphp

    @forelse($bySource as $source => $entries)
    @php
      $sourceConfig = match($source) {
        'github' => ['icon' => 'G', 'label' => 'GitHub'],
        'linear' => ['icon' => 'L', 'label' => 'Linear'],
        'notion' => ['icon' => 'N', 'label' => 'Notion'],
        'google_calendar' => ['icon' => 'C', 'label' => 'Calendar'],
        'manual' => ['icon' => 'M', 'label' => 'Manual'],
        default => ['icon' => '*', 'label' => ucfirst($source)],
      };
    @endphp
    <div class="card" style="margin-bottom:1.2rem">
      <div class="card-header">
        <div style="display:flex;align-items:center;gap:.6rem">
          <span style="font-size:.9rem">{{ $sourceConfig['icon'] }}</span>
          <span class="card-title">{{ $sourceConfig['label'] }}</span>
          <span class="badge badge-gray">{{ $entries->count() }}</span>
        </div>
      </div>
      @foreach($entries as $entry)
      <div style="display:grid;grid-template-columns:1fr auto;gap:1rem;padding:.9rem 1.4rem;border-bottom:1px solid rgba(255,255,255,.03);align-items:start">
        <div>
          <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.2rem">
            <span style="font-family:var(--mono);font-size:.58rem;color:var(--ink3);background:var(--surface2);border:1px solid var(--border2);padding:.1rem .4rem;border-radius:3px">{{ $entry->type }}</span>
          </div>
          <div style="font-size:.85rem;font-weight:500;color:var(--ink);margin-bottom:.2rem">{{ $entry->title }}</div>
          @if($entry->description)
          <div style="font-size:.78rem;color:var(--ink3);line-height:1.5">{{ $entry->description }}</div>
          @endif
          @if($entry->source_url)
          <a href="{{ $entry->source_url }}" target="_blank" style="font-family:var(--mono);font-size:.6rem;color:var(--sky);text-decoration:none;margin-top:.3rem;display:inline-block">View source</a>
          @endif
        </div>
        <div style="text-align:right">
          @if($entry->occurred_at)
          <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $entry->occurred_at->format('M d') }}</div>
          <div style="font-family:var(--mono);font-size:.58rem;color:var(--ink3);opacity:.5">{{ $entry->occurred_at->format('H:i') }}</div>
          @endif
          <form action="{{ route('reports.entries.delete', [$report, $entry]) }}" method="POST" style="margin-top:.4rem">
            @csrf
            @method('DELETE')
            <button type="submit" style="background:transparent;border:none;color:var(--ink3);cursor:pointer;font-size:.7rem;opacity:.5;transition:opacity .15s" onmouseover="this.style.opacity='1';this.style.color='var(--coral)'" onmouseout="this.style.opacity='.5';this.style.color='var(--ink3)'" data-confirm-form data-confirm-title="Remove entry" data-confirm-message="Remove this entry from the report?" data-confirm-submit-label="Remove">
              X
            </button>
          </form>
        </div>
      </div>
      @endforeach
    </div>
    @empty
    <div class="card">
      <div class="empty-state">
        <div class="empty-icon">E</div>
        <div class="empty-title">No entries yet</div>
        <div class="empty-sub">Add manual entries or connect integrations to auto-populate reports.</div>
      </div>
    </div>
    @endforelse

    <div class="card">
      <div class="card-header">
        <div class="card-title">+ Add manual entry</div>
      </div>
      <div class="card-body">
        <form action="{{ route('reports.entries.add', $report) }}" method="POST">
          @csrf
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div class="form-group">
              <label class="form-label">Title</label>
              <input type="text" name="title" class="form-input" placeholder="What was done" required>
            </div>
            <div class="form-group">
              <label class="form-label">Type</label>
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
              <label class="form-label">Source</label>
              <select name="source" class="form-select">
                <option value="manual">Manual</option>
                <option value="github">GitHub</option>
                <option value="linear">Linear</option>
                <option value="notion">Notion</option>
                <option value="google_calendar">Calendar</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Date (optional)</label>
              <input type="datetime-local" name="occurred_at" class="form-input">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Description (optional)</label>
            <textarea name="description" class="form-textarea" style="min-height:70px" placeholder="Additional details..."></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Source URL (optional)</label>
            <input type="url" name="source_url" class="form-input" placeholder="https://...">
          </div>
          <button type="submit" class="btn btn-primary btn-sm">Add entry</button>
        </form>
      </div>
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:1rem;position:sticky;top:80px">
    <div class="card">
      <div class="card-header"><div class="card-title">Report details</div></div>
      <div style="padding:.8rem 1.2rem;display:flex;flex-direction:column;gap:.6rem">
        @foreach([
          ['Period',  $report->periodLabel()],
          ['Project', $report->project?->name ?? '-'],
          ['Client',  $report->client?->name ?? '-'],
          ['Status',  ucfirst($report->status)],
          ['Views',   $report->view_count . ' client views'],
          ['Created', $report->created_at->format('M d, Y')],
        ] as [$label, $value])
        <div style="display:flex;justify-content:space-between;gap:.5rem">
          <span style="font-family:var(--mono);font-size:.62rem;color:var(--ink3)">{{ $label }}</span>
          <span style="font-size:.78rem;color:var(--ink2);text-align:right">{{ $value }}</span>
        </div>
        @endforeach
      </div>
    </div>

    @if($report->share_enabled)
    <div class="card">
      <div class="card-header"><div class="card-title">Share link</div></div>
      <div class="card-body">
        <div style="background:var(--bg);border:1px solid var(--border2);border-radius:5px;padding:.6rem .8rem;font-family:var(--mono);font-size:.62rem;color:var(--ink3);word-break:break-all;margin-bottom:.8rem">
          {{ $report->shareUrl() }}
        </div>
        <button onclick="navigator.clipboard.writeText('{{ $report->shareUrl() }}').then(() => this.textContent = 'Copied')" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center">
          Copy link
        </button>
      </div>
    </div>
    @endif

    <div class="card">
      <div class="card-header"><div class="card-title">Actions</div></div>
      <div style="padding:.8rem;display:flex;flex-direction:column;gap:.4rem">
        <a href="{{ route('reports.edit', $report) }}" class="btn btn-ghost" style="justify-content:center;width:100%">Edit report</a>
        @if($report->client?->email && $report->status !== 'sent')
        <form action="{{ route('reports.send', $report) }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center" data-confirm-form data-confirm-title="Send report" data-confirm-message="Send this report to {{ $report->client->email }} now?" data-confirm-submit-label="Send report">
            Send to client
          </button>
        </form>
        @endif
        <form action="{{ route('reports.destroy', $report) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center" data-confirm-form data-confirm-title="Delete report" data-confirm-message="Delete this report permanently?" data-confirm-submit-label="Delete report">
            Delete report
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
