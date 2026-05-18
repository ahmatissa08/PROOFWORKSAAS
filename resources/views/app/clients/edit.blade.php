@extends('layouts.app')
@section('title', 'Edit client')
@section('breadcrumb')
  <a href="{{ route('clients.index') }}">Clients</a>
  <span class="sep">›</span>
  <a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a>
  <span class="sep">›</span>
  <span class="current">Edit</span>
@endsection

@section('content')
<div class="page-header">
  <h1 class="page-title">Edit client</h1>
</div>

<div style="max-width:540px">
  <div class="card">
    <div class="card-body">
      <form action="{{ route('clients.update', $client) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="form-group">
          <label class="form-label">Client name *</label>
          <input type="text" name="name" class="form-input" value="{{ old('name', $client->name) }}" required autofocus>
          @error('name')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Email address</label>
          <input type="email" name="email" class="form-input" value="{{ old('email', $client->email) }}">
        </div>
        <div class="form-group">
          <label class="form-label">Company</label>
          <input type="text" name="company" class="form-input" value="{{ old('company', $client->company) }}">
        </div>
        <div class="form-group">
          <label class="form-label">Avatar color</label>
          <input type="text" name="avatar_color" class="form-input" value="{{ old('avatar_color', $client->avatar_color) }}" placeholder="#e8a325">
        </div>
        <div class="form-group">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-textarea">{{ old('notes', $client->notes) }}</textarea>
        </div>
        <div style="display:flex;gap:.8rem">
          <button type="submit" class="btn btn-primary">Save changes</button>
          <a href="{{ route('clients.show', $client) }}" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
