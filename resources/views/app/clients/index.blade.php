@extends('layouts.app')
@section('title', 'Clients')
@section('breadcrumb')
  <span class="current">Clients</span>
@endsection

@push('styles')
<style>
  .cl-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    gap: 1.2rem;
  }

  .cl-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    transition: border-color .2s, transform .15s;
    display: flex; flex-direction: column;
  }
  .cl-card:hover { border-color: var(--border2); transform: translateY(-2px); }

  /* Accent top bar */
  .cl-card-accent { height: 3px; width: 100%; flex-shrink: 0; }

  .cl-card-body { padding: 1.3rem; flex: 1; display: flex; flex-direction: column; gap: 1rem; }

  /* Avatar + info */
  .cl-card-head { display: flex; align-items: center; gap: .85rem; }
  .cl-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .82rem; font-weight: 700; color: #000; flex-shrink: 0;
    box-shadow: 0 0 0 3px rgba(255,255,255,.06);
  }
  .cl-name { font-size: .9rem; font-weight: 600; color: var(--ink); line-height: 1.3; }
  .cl-company {
    font-family: var(--mono); font-size: .6rem; color: var(--ink3);
    margin-top: .1rem; display: flex; align-items: center; gap: 4px;
  }
  .cl-company i { font-size: 10px; }
  .cl-email {
    font-family: var(--mono); font-size: .58rem; color: var(--ink3);
    margin-top: .05rem; display: flex; align-items: center; gap: 4px;
  }
  .cl-email i { font-size: 10px; }

  /* Stats */
  .cl-stats { display: flex; gap: .5rem; }
  .cl-stat {
    flex: 1; background: var(--surface2); border: 1px solid var(--border);
    border-radius: 8px; padding: .6rem .8rem; text-align: center;
  }
  .cl-stat-val {
    font-family: var(--serif); font-size: 1.3rem; font-style: italic;
    color: var(--ink); line-height: 1; margin-bottom: .1rem;
  }
  .cl-stat-val.amber { color: var(--amber); }
  .cl-stat-label {
    font-family: var(--mono); font-size: .52rem; color: var(--ink3);
    text-transform: uppercase; letter-spacing: .08em;
    display: flex; align-items: center; justify-content: center; gap: 3px;
  }
  .cl-stat-label i { font-size: 10px; }

  /* Actions */
  .cl-actions { display: flex; gap: .5rem; margin-top: auto; }
  .cl-actions .btn { font-size: .75rem; padding: .42rem .9rem; border-radius: 7px; }

  /* Empty */
  .cl-empty-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: rgba(255,255,255,.04); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem; font-size: 22px; color: var(--ink3);
  }
</style>
@endpush

@section('content')

  <div class="page-header">
    <div>
      <h1 class="page-title">Clients</h1>
      <p class="page-sub">Manage the clients you generate reports for.</p>
    </div>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">
      <i class="ti ti-plus"></i> Add client
    </a>
  </div>

  @if($clients->isEmpty())
  <div class="card">
    <div class="empty-state">
      <div class="cl-empty-icon"><i class="ti ti-users-group"></i></div>
      <div class="empty-title">No clients yet</div>
      <div class="empty-sub">Add a client so you can assign projects and send reports directly to them.</div>
      <a href="{{ route('clients.create') }}" class="btn btn-primary">
        <i class="ti ti-plus"></i> Add first client
      </a>
    </div>
  </div>

  @else
  <div class="cl-grid">
    @foreach($clients as $client)
    <div class="cl-card">
      <div class="cl-card-accent" style="background:{{ $client->avatar_color }}"></div>
      <div class="cl-card-body">

        <div class="cl-card-head">
          <div class="cl-avatar" style="background:{{ $client->avatar_color }}">{{ $client->initials() }}</div>
          <div style="min-width:0">
            <div class="cl-name">{{ $client->name }}</div>
            @if($client->company)
            <div class="cl-company"><i class="ti ti-building"></i> {{ $client->company }}</div>
            @endif
            @if($client->email)
            <div class="cl-email"><i class="ti ti-mail"></i> {{ $client->email }}</div>
            @endif
          </div>
        </div>

        <div class="cl-stats">
          <div class="cl-stat">
            <div class="cl-stat-val amber">{{ $client->projects_count }}</div>
            <div class="cl-stat-label"><i class="ti ti-folder"></i> Projects</div>
          </div>
          <div class="cl-stat">
            <div class="cl-stat-val">{{ $client->reports_count }}</div>
            <div class="cl-stat-label"><i class="ti ti-file-analytics"></i> Reports</div>
          </div>
        </div>

        <div class="cl-actions">
          <a href="{{ route('clients.show', $client) }}" class="btn btn-primary" style="flex:1;justify-content:center">
            <i class="ti ti-arrow-right"></i> View
          </a>
          <a href="{{ route('clients.edit', $client) }}" class="btn btn-ghost">
            <i class="ti ti-pencil"></i> Edit
          </a>
        </div>

      </div>
    </div>
    @endforeach
  </div>
  @endif

@endsection