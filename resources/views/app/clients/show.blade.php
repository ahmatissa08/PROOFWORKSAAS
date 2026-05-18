@extends('layouts.app')
@section('title', $client->name)
@section('breadcrumb')
  <a href="{{ route('clients.index') }}">Clients</a>
  <span class="sep">/</span>
  <span class="current">{{ $client->name }}</span>
@endsection

@section('content')
<div class="page-header">
  <div style="display:flex;align-items:center;gap:1rem">
    <div style="width:48px;height:48px;border-radius:50%;background:{{ $client->avatar_color }};display:flex;align-items:center;justify-content:center;font-size:.9rem;font-weight:700;color:#000;flex-shrink:0">
      {{ $client->initials() }}
    </div>
    <div>
      <h1 class="page-title" style="font-size:1.6rem">{{ $client->name }}</h1>
      <p class="page-sub">
        {{ $client->company ?? '' }}
        @if($client->email) - {{ $client->email }} @endif
      </p>
    </div>
  </div>
  <div style="display:flex;gap:.6rem">
    <a href="{{ route('clients.edit', $client) }}" class="btn btn-ghost">Edit</a>
    <form action="{{ route('clients.destroy', $client) }}" method="POST">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger btn-sm" data-confirm-form data-confirm-title="Delete client" data-confirm-message="Delete this client permanently?" data-confirm-submit-label="Delete client">Delete</button>
    </form>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 280px;gap:1.5rem;align-items:start">
  <div style="display:flex;flex-direction:column;gap:1.2rem">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Projects ({{ $client->projects->count() }})</div>
        <a href="{{ route('projects.create') }}" class="btn btn-ghost btn-sm">+ New project</a>
      </div>
      @forelse($client->projects as $project)
      <a href="{{ route('projects.show', $project) }}"
         style="display:flex;align-items:center;gap:.9rem;padding:.9rem 1.4rem;text-decoration:none;border-bottom:1px solid rgba(255,255,255,.03);transition:background .12s"
         onmouseover="this.style.background='rgba(255,255,255,.015)'"
         onmouseout="this.style.background='transparent'">
        <div style="width:32px;height:32px;border-radius:7px;background:{{ $project->color }};display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#000;flex-shrink:0">
          {{ $project->initials() }}
        </div>
        <div style="flex:1">
          <div style="font-size:.84rem;font-weight:500;color:var(--ink)">{{ $project->name }}</div>
          <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ ucfirst($project->status) }}</div>
        </div>
        <span class="badge {{ $project->status === 'active' ? 'badge-green' : 'badge-gray' }}">
          {{ $project->status }}
        </span>
      </a>
      @empty
      <div class="empty-state" style="padding:2rem">
        <div class="empty-icon">P</div>
        <div class="empty-title">No projects</div>
        <div class="empty-sub">Assign a project to this client.</div>
        <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">Create project</a>
      </div>
      @endforelse
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-title">Reports ({{ $client->reports->count() }})</div>
      </div>
      @forelse($client->reports as $report)
      <a href="{{ route('reports.show', $report) }}"
         style="display:flex;align-items:center;gap:.9rem;padding:.85rem 1.4rem;text-decoration:none;border-bottom:1px solid rgba(255,255,255,.03);transition:background .12s"
         onmouseover="this.style.background='rgba(255,255,255,.015)'"
         onmouseout="this.style.background='transparent'">
        <div style="flex:1">
          <div style="font-size:.82rem;font-weight:500;color:var(--ink)">{{ $report->title }}</div>
          <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $report->periodLabel() }}</div>
        </div>
        <span class="badge {{ match($report->status) { 'sent' => 'badge-green', 'ready' => 'badge-amber', default => 'badge-gray' } }}">
          {{ $report->status }}
        </span>
      </a>
      @empty
      <div class="empty-state" style="padding:2rem">
        <div class="empty-icon">R</div>
        <div class="empty-title">No reports yet</div>
        <div class="empty-sub">Generate a report from a project.</div>
      </div>
      @endforelse
    </div>
  </div>

  <div class="card">
    <div class="card-header"><div class="card-title">Client info</div></div>
    <div style="padding:.8rem 1.2rem;display:flex;flex-direction:column;gap:.65rem">
      @foreach([
        ['Name',    $client->name],
        ['Company', $client->company ?? '-'],
        ['Email',   $client->email ?? '-'],
        ['Projects',$client->projects->count()],
        ['Reports', $client->reports->count()],
        ['Added',   $client->created_at->format('M d, Y')],
      ] as [$label, $value])
      <div style="display:flex;justify-content:space-between;align-items:center;padding:.4rem 0;border-bottom:1px solid rgba(255,255,255,.03)">
        <span style="font-family:var(--mono);font-size:.62rem;color:var(--ink3)">{{ $label }}</span>
        <span style="font-size:.8rem;color:var(--ink2)">{{ $value }}</span>
      </div>
      @endforeach

      @if($client->notes)
      <div style="margin-top:.4rem">
        <div style="font-family:var(--mono);font-size:.62rem;color:var(--ink3);margin-bottom:.4rem">Notes</div>
        <div style="font-size:.8rem;color:var(--ink2);line-height:1.55">{{ $client->notes }}</div>
      </div>
      @endif
    </div>
    <div style="padding:.8rem 1.2rem;border-top:1px solid var(--border)">
      <a href="{{ route('clients.edit', $client) }}" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center">
        Edit client
      </a>
    </div>
  </div>
</div>
@endsection
