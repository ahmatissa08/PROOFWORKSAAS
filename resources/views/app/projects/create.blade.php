@extends('layouts.app')
@section('title', 'New project')
@section('breadcrumb')
  <a href="{{ route('projects.index') }}">Projects</a>
  <span class="sep">/</span>
  <span class="current">New project</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">New project</h1>
    <p class="page-sub">Set up a project to start generating proof of work reports.</p>
  </div>
</div>

<div style="max-width:640px">
  <div class="card">
    <div class="card-body">
      <form action="{{ route('projects.store') }}" method="POST">
        @csrf

        <div class="form-group">
          <label class="form-label" for="name">Project name *</label>
          <input id="name" name="name" type="text" class="form-input" value="{{ old('name') }}" placeholder="e.g. Acme Corp - Dashboard rebuild" required autofocus>
          @error('name')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="description">Description</label>
          <textarea id="description" name="description" class="form-textarea" placeholder="Brief description of this project...">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
          <label class="form-label" for="client_id">Client</label>
          <select id="client_id" name="client_id" class="form-select">
            <option value="">No client</option>
            @foreach($clients as $client)
            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
              {{ $client->name }} {{ $client->company ? "({$client->company})" : '' }}
            </option>
            @endforeach
          </select>
          <div class="form-hint">Don't see your client? <a href="{{ route('clients.create') }}" style="color:var(--amber)">Add client first</a></div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
          <div class="form-group">
            <label class="form-label" for="report_frequency">Report frequency</label>
            <select id="report_frequency" name="report_frequency" class="form-select">
              <option value="weekly" {{ old('report_frequency', 'weekly') === 'weekly' ? 'selected' : '' }}>Weekly</option>
              <option value="biweekly" {{ old('report_frequency') === 'biweekly' ? 'selected' : '' }}>Bi-weekly</option>
              <option value="monthly" {{ old('report_frequency') === 'monthly' ? 'selected' : '' }}>Monthly</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label" for="report_day">Report day</label>
            <select id="report_day" name="report_day" class="form-select">
              @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
              <option value="{{ $day }}" {{ old('report_day', 'friday') === $day ? 'selected' : '' }}>{{ ucfirst($day) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="color">Project color</label>
          <div style="display:flex;gap:.6rem;flex-wrap:wrap">
            @foreach(['#e8a325','#4a9eff','#27c93f','#e85c3a','#a855f7','#f97316','#06b6d4','#ec4899'] as $color)
            <label style="cursor:pointer">
              <input type="radio" name="color" value="{{ $color }}" style="display:none" {{ old('color', '#e8a325') === $color ? 'checked' : '' }}>
              <div style="width:28px;height:28px;border-radius:6px;background:{{ $color }};transition:transform .15s;cursor:pointer" onclick="document.querySelectorAll('[name=color]').forEach(r=>r.nextElementSibling.style.transform='scale(1)');this.style.transform='scale(1.2)'"></div>
            </label>
            @endforeach
          </div>
        </div>

        @auth
        @if(auth()->user()->isPro())
        <div class="form-group">
          <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer">
            <input type="checkbox" name="auto_send" value="1" {{ old('auto_send') ? 'checked' : '' }} style="accent-color:var(--amber)">
            <span style="font-size:.85rem;color:var(--ink2)">Auto-send reports to client when generated</span>
          </label>
          <div class="form-hint">Pro feature. Requires a client with an email address.</div>
        </div>
        @endif
        @endauth

        <div style="display:flex;gap:.8rem;margin-top:.5rem">
          <button type="submit" class="btn btn-primary">Create project</button>
          <a href="{{ route('projects.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
