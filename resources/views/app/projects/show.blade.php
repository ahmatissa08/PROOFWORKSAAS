@extends('layouts.app')
@section('title', $project->name)
@section('breadcrumb')
  <a href="{{ route('projects.index') }}">Projects</a>
  <span class="sep">/</span>
  <span class="current">{{ $project->name }}</span>
@endsection

@section('content')
<div class="page-header">
  <div style="display:flex;align-items:center;gap:1rem">
    <div style="width:48px;height:48px;border-radius:10px;background:{{ $project->color }};display:flex;align-items:center;justify-content:center;font-size:.9rem;font-weight:700;color:#000;flex-shrink:0">
      {{ $project->initials() }}
    </div>
    <div>
      <h1 class="page-title" style="font-size:1.6rem">{{ $project->name }}</h1>
      <p class="page-sub">{{ $project->client?->name ?? 'No client' }} - {{ ucfirst($project->status) }}</p>
    </div>
  </div>
  <div style="display:flex;gap:.6rem">
    <a href="{{ route('projects.edit', $project) }}" class="btn btn-ghost">Edit project</a>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start">
  <div>
    <div class="card" style="margin-bottom:1.5rem">
      <div class="card-header">
        <div class="card-title">Generate new report</div>
      </div>
      <div class="card-body">
        <form action="{{ route('reports.generate', $project) }}" method="POST">
          @csrf
          <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:.8rem;align-items:end">
            <div>
              <label class="form-label">Period start</label>
              <input type="date" name="period_start" class="form-input" value="{{ now()->startOfWeek()->format('Y-m-d') }}" required>
            </div>
            <div>
              <label class="form-label">Period end</label>
              <input type="date" name="period_end" class="form-input" value="{{ now()->format('Y-m-d') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate</button>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-title">Reports ({{ $project->reports->count() }})</div>
        <a href="{{ route('reports.index') }}" class="btn btn-ghost btn-sm">View all</a>
      </div>
      @forelse($project->reports as $report)
      <a href="{{ route('reports.show', $report) }}" style="display:flex;align-items:center;gap:1rem;padding:.9rem 1.4rem;text-decoration:none;border-bottom:1px solid rgba(255,255,255,.03);transition:background .12s" onmouseover="this.style.background='rgba(255,255,255,.015)'" onmouseout="this.style.background='transparent'">
        <div style="flex:1">
          <div style="font-size:.84rem;font-weight:500;color:var(--ink)">{{ $report->title }}</div>
          <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3);margin-top:.1rem">
            {{ $report->periodLabel() }} - {{ $report->entries_count ?? $report->entries->count() }} entries
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:.6rem">
          <span class="badge {{ match($report->status) { 'sent' => 'badge-green', 'ready' => 'badge-amber', default => 'badge-gray' } }}">{{ $report->status }}</span>
          @if($report->view_count > 0)
          <span style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $report->view_count }} views</span>
          @endif
        </div>
      </a>
      @empty
      <div class="empty-state" style="padding:2.5rem">
        <div class="empty-icon">R</div>
        <div class="empty-title">No reports yet</div>
        <div class="empty-sub">Use the form above to generate your first report.</div>
      </div>
      @endforelse
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:1rem">
    <div class="card">
      <div class="card-header"><div class="card-title">Project info</div></div>
      <div style="padding:.8rem 1.2rem;display:flex;flex-direction:column;gap:.65rem">
        @foreach([
          ['Client',     $project->client?->name ?? '-'],
          ['Status',     ucfirst($project->status)],
          ['Frequency',  ucfirst($project->report_frequency)],
          ['Report day', ucfirst($project->report_day)],
          ['Auto-send',  $project->auto_send ? 'Enabled' : 'Disabled'],
          ['Created',    $project->created_at->format('M d, Y')],
        ] as [$label, $value])
        <div style="display:flex;justify-content:space-between;gap:.5rem">
          <span style="font-family:var(--mono);font-size:.62rem;color:var(--ink3)">{{ $label }}</span>
          <span style="font-size:.78rem;color:var(--ink2)">{{ $value }}</span>
        </div>
        @endforeach
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-title">Integrations</div>
        <a href="{{ route('integrations.index', ['project_id' => $project->id]) }}" class="btn btn-ghost btn-sm">Manage</a>
      </div>
      @forelse($project->integrations as $integration)
      <div style="display:flex;align-items:center;gap:.8rem;padding:.8rem 1.2rem;border-bottom:1px solid rgba(255,255,255,.03)">
        <div style="width:28px;height:28px;border:1px solid var(--border2);border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:.78rem;flex-shrink:0">
          {{ $integration->providerIcon() }}
        </div>
        <div style="flex:1">
          <div style="font-size:.8rem;font-weight:500;color:var(--ink)">{{ $integration->providerLabel() }}</div>
          @if($integration->resource_name)
          <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $integration->resource_name }}</div>
          @endif
        </div>
        <span class="badge {{ $integration->active ? 'badge-green' : 'badge-gray' }}">
          {{ $integration->active ? 'active' : 'off' }}
        </span>
      </div>
      @empty
      <div style="padding:1.2rem;text-align:center">
        <div style="font-size:.8rem;color:var(--ink3);margin-bottom:.7rem">No integrations connected</div>
        <a href="{{ route('integrations.index', ['project_id' => $project->id]) }}" class="btn btn-ghost btn-sm">Connect tools</a>
      </div>
      @endforelse
    </div>
  </div>
</div>
@endsection
