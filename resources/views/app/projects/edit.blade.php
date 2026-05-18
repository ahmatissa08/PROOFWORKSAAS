@extends('layouts.app')
@section('title', 'Edit project')
@section('breadcrumb')
  <a href="{{ route('projects.index') }}">Projects</a>
  <span class="sep">›</span>
  <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
  <span class="sep">›</span>
  <span class="current">Edit</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Edit project</h1>
    <p class="page-sub">Update project details and reporting settings.</p>
  </div>
</div>

<div style="max-width:640px">
  <div class="card">
    <div class="card-body">
      <form action="{{ route('projects.update', $project) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="form-group">
          <label class="form-label" for="name">Project name *</label>
          <input id="name" name="name" type="text" class="form-input" value="{{ old('name', $project->name) }}" required autofocus>
          @error('name')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="description">Description</label>
          <textarea id="description" name="description" class="form-textarea">{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="form-group">
          <label class="form-label" for="client_id">Client</label>
          <select id="client_id" name="client_id" class="form-select">
            <option value="">No client</option>
            @foreach($clients as $client)
            <option value="{{ $client->id }}" {{ (string) old('client_id', $project->client_id) === (string) $client->id ? 'selected' : '' }}>
              {{ $client->name }} {{ $client->company ? "({$client->company})" : '' }}
            </option>
            @endforeach
          </select>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
          <div class="form-group">
            <label class="form-label" for="report_frequency">Report frequency</label>
            <select id="report_frequency" name="report_frequency" class="form-select">
              @foreach(['weekly' => 'Weekly', 'biweekly' => 'Bi-weekly', 'monthly' => 'Monthly'] as $value => $label)
              <option value="{{ $value }}" {{ old('report_frequency', $project->report_frequency) === $value ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label class="form-label" for="report_day">Report day</label>
            <select id="report_day" name="report_day" class="form-select">
              @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
              <option value="{{ $day }}" {{ old('report_day', $project->report_day) === $day ? 'selected' : '' }}>{{ ucfirst($day) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
          <div class="form-group">
            <label class="form-label" for="status">Status</label>
            <select id="status" name="status" class="form-select">
              @foreach(['active' => 'Active', 'paused' => 'Paused', 'completed' => 'Completed'] as $value => $label)
              <option value="{{ $value }}" {{ old('status', $project->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Auto-send</label>
            <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer;height:42px">
              <input type="checkbox" name="auto_send" value="1" {{ old('auto_send', $project->auto_send) ? 'checked' : '' }} style="accent-color:var(--amber)">
              <span style="font-size:.85rem;color:var(--ink2)">Send to client automatically</span>
            </label>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="color">Project color</label>
          <input id="color" name="color" type="text" class="form-input" value="{{ old('color', $project->color) }}" placeholder="#e8a325">
        </div>

        <div style="display:flex;gap:.8rem;margin-top:.5rem">
          <button type="submit" class="btn btn-primary">Save changes</button>
          <a href="{{ route('projects.show', $project) }}" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
