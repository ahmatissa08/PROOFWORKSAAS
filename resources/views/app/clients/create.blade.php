@extends('layouts.app')
@section('title', 'Add client')
@section('breadcrumb')
  <a href="{{ route('clients.index') }}">Clients</a>
  <span class="sep">/</span>
  <span class="current">Add client</span>
@endsection

@section('content')
<div class="page-header">
  <h1 class="page-title">Add client</h1>
</div>

<div style="max-width:540px">
  <div class="card">
    <div class="card-body">
      <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div class="form-group">
          <label class="form-label">Client name *</label>
          <input type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="e.g. Acme Corp" required autofocus>
          @error('name')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Email address</label>
          <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="client@company.com">
          <div class="form-hint">Used to send reports directly. Optional.</div>
        </div>
        <div class="form-group">
          <label class="form-label">Company</label>
          <input type="text" name="company" class="form-input" value="{{ old('company') }}" placeholder="Company name">
        </div>
        <div class="form-group">
          <label class="form-label">Avatar color</label>
          <div style="display:flex;gap:.6rem;flex-wrap:wrap">
            @foreach(['#e8a325','#4a9eff','#27c93f','#e85c3a','#a855f7','#f97316','#06b6d4','#ec4899'] as $color)
            <label style="cursor:pointer">
              <input type="radio" name="avatar_color" value="{{ $color }}" style="display:none" {{ old('avatar_color','#e8a325') === $color ? 'checked' : '' }}>
              <div style="width:28px;height:28px;border-radius:50%;background:{{ $color }};cursor:pointer;transition:transform .15s" onclick="this.style.transform='scale(1.2)';document.querySelectorAll('[name=avatar_color]').forEach(r=>{if(r.value!=='{{ $color }}') r.nextElementSibling.style.transform='scale(1)'})"></div>
            </label>
            @endforeach
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-textarea" placeholder="Internal notes about this client...">{{ old('notes') }}</textarea>
        </div>
        <div style="display:flex;gap:.8rem">
          <button type="submit" class="btn btn-primary">Add client</button>
          <a href="{{ route('clients.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
