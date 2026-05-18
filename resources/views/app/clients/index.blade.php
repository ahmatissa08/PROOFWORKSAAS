@extends('layouts.app')
@section('title', 'Clients')
@section('breadcrumb')
  <span class="current">Clients</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Clients</h1>
    <p class="page-sub">Manage the clients you generate reports for.</p>
  </div>
  <a href="{{ route('clients.create') }}" class="btn btn-primary">+ Add client</a>
</div>

@if($clients->isEmpty())
<div class="card">
  <div class="empty-state">
    <div class="empty-icon">C</div>
    <div class="empty-title">No clients yet</div>
    <div class="empty-sub">Add a client so you can assign projects and send reports directly to them.</div>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">Add first client</a>
  </div>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.2rem">
  @foreach($clients as $client)
  <div class="card" style="transition:border-color .2s" onmouseover="this.style.borderColor='var(--border2)'" onmouseout="this.style.borderColor='var(--border)'">
    <div style="padding:1.4rem">
      <div style="display:flex;align-items:center;gap:.9rem;margin-bottom:1.2rem">
        <div style="width:42px;height:42px;border-radius:50%;background:{{ $client->avatar_color }};display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;color:#000;flex-shrink:0">
          {{ $client->initials() }}
        </div>
        <div>
          <div style="font-size:.9rem;font-weight:600;color:var(--ink)">{{ $client->name }}</div>
          @if($client->company)
          <div style="font-family:var(--mono);font-size:.62rem;color:var(--ink3)">{{ $client->company }}</div>
          @endif
          @if($client->email)
          <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $client->email }}</div>
          @endif
        </div>
      </div>

      <div style="display:flex;gap:1.2rem;margin-bottom:1.2rem">
        <div>
          <div style="font-family:var(--serif);font-size:1.3rem;font-style:italic;color:var(--amber);line-height:1">{{ $client->projects_count }}</div>
          <div style="font-family:var(--mono);font-size:.55rem;color:var(--ink3);text-transform:uppercase;letter-spacing:.08em">Projects</div>
        </div>
        <div>
          <div style="font-family:var(--serif);font-size:1.3rem;font-style:italic;color:var(--ink);line-height:1">{{ $client->reports_count }}</div>
          <div style="font-family:var(--mono);font-size:.55rem;color:var(--ink3);text-transform:uppercase;letter-spacing:.08em">Reports</div>
        </div>
      </div>

      <div style="display:flex;gap:.5rem">
        <a href="{{ route('clients.show', $client) }}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">View</a>
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-ghost btn-sm">Edit</a>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endif
@endsection
