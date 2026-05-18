@extends('layouts.app')
@section('title', 'Projects')
@section('breadcrumb')
  <span class="current">Projects</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Projects</h1>
    <p class="page-sub">Manage your client projects and integrations.</p>
  </div>
  <a href="{{ route('projects.create') }}" class="btn btn-primary">+ New project</a>
</div>

@if($projects->isEmpty())
<div class="card">
  <div class="empty-state">
    <div class="empty-icon">P</div>
    <div class="empty-title">No projects yet</div>
    <div class="empty-sub">Create a project, connect your tools, and start generating reports automatically.</div>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">Create first project</a>
  </div>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.2rem">
  @foreach($projects as $project)
  <div class="card" style="transition:border-color .2s" onmouseover="this.style.borderColor='var(--border2)'" onmouseout="this.style.borderColor='var(--border)'">
    <div style="padding:1.4rem">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.2rem">
        <div style="display:flex;align-items:center;gap:.8rem">
          <div style="width:40px;height:40px;border-radius:9px;background:{{ $project->color }};display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:#000;flex-shrink:0">
            {{ $project->initials() }}
          </div>
          <div>
            <div style="font-size:.9rem;font-weight:600;color:var(--ink)">{{ $project->name }}</div>
            <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3);margin-top:.1rem">{{ $project->client?->name ?? 'No client' }}</div>
          </div>
        </div>
        <span class="badge {{ $project->status === 'active' ? 'badge-green' : 'badge-gray' }}">{{ $project->status }}</span>
      </div>

      @if($project->description)
      <p style="font-size:.8rem;color:var(--ink3);margin-bottom:1rem;line-height:1.55">{{ Str::limit($project->description, 80) }}</p>
      @endif

      <div style="display:flex;gap:1rem;margin-bottom:1.2rem">
        <div style="text-align:center">
          <div style="font-family:var(--serif);font-size:1.4rem;font-style:italic;color:var(--amber);line-height:1">{{ $project->reports_count }}</div>
          <div style="font-family:var(--mono);font-size:.55rem;color:var(--ink3);text-transform:uppercase;letter-spacing:.08em">Reports</div>
        </div>
        <div style="text-align:center">
          <div style="font-family:var(--serif);font-size:1.4rem;font-style:italic;color:var(--ink);line-height:1">{{ $project->integrations_count }}</div>
          <div style="font-family:var(--mono);font-size:.55rem;color:var(--ink3);text-transform:uppercase;letter-spacing:.08em">Integrations</div>
        </div>
        @if($project->latestReport)
        <div style="text-align:center">
          <div style="font-family:var(--serif);font-size:1rem;font-style:italic;color:var(--ink2);line-height:1.2">{{ $project->latestReport->period_end->format('M d') }}</div>
          <div style="font-family:var(--mono);font-size:.55rem;color:var(--ink3);text-transform:uppercase;letter-spacing:.08em">Last report</div>
        </div>
        @endif
      </div>

      <div style="display:flex;gap:.5rem">
        <a href="{{ route('projects.show', $project) }}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">View</a>
        <a href="{{ route('projects.edit', $project) }}" class="btn btn-ghost btn-sm">Edit</a>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endif
@endsection
