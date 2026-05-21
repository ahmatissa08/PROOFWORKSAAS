@extends('layouts.app')
@section('title', 'Edit client')
@section('breadcrumb')
  <a href="{{ route('clients.index') }}">Clients</a>
  <span class="sep">/</span>
  <a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a>
  <span class="sep">/</span>
  <span class="current">Edit</span>
@endsection

@push('styles')
<style>
  .cl-form-wrap { max-width: 560px; }

  /* Color picker */
  .cl-color-grid { display: flex; gap: .6rem; flex-wrap: wrap; }
  .cl-color-swatch {
    width: 30px; height: 30px; border-radius: 50%;
    cursor: pointer; transition: transform .15s, box-shadow .15s;
    position: relative;
  }
  .cl-color-swatch:hover { transform: scale(1.15); }
  .cl-color-swatch.selected {
    box-shadow: 0 0 0 2px var(--surface), 0 0 0 4px currentColor;
    transform: scale(1.12);
  }
  .cl-color-swatch input { display: none; }
  .cl-color-swatch .check {
    position: absolute; inset: 0;
    display: none; align-items: center; justify-content: center;
    font-size: 13px; color: #000;
  }
  .cl-color-swatch.selected .check { display: flex; }

  /* Preview card */
  .cl-preview {
    background: var(--surface2); border: 1px solid var(--border);
    border-radius: 10px; padding: 1rem 1.2rem;
    display: flex; align-items: center; gap: .85rem;
    margin-bottom: 1.5rem;
  }
  .cl-preview-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; font-weight: 700; color: #000;
    flex-shrink: 0; transition: background .2s;
    box-shadow: 0 0 0 3px rgba(255,255,255,.06);
  }
  .cl-preview-name { font-size: .88rem; font-weight: 500; color: var(--ink); }
  .cl-preview-company { font-family: var(--mono); font-size: .6rem; color: var(--ink3); margin-top: .1rem; }
  .cl-preview-label {
    font-family: var(--mono); font-size: .55rem; color: var(--ink3);
    letter-spacing: .08em; text-transform: uppercase; margin-right: auto;
  }

  /* Section divider */
  .cl-section-title {
    font-family: var(--mono); font-size: .6rem; color: var(--ink3);
    letter-spacing: .1em; text-transform: uppercase;
    margin: 1.5rem 0 .9rem; display: flex; align-items: center; gap: .6rem;
  }
  .cl-section-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }
  .cl-section-title i { font-size: 12px; }

  /* Danger zone */
  .cl-danger-zone {
    margin-top: 2rem; background: rgba(232,92,58,.04);
    border: 1px solid rgba(232,92,58,.15); border-radius: 10px;
    padding: 1.1rem 1.3rem;
    display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; flex-wrap: wrap;
  }
  .cl-danger-zone-title {
    font-size: .85rem; font-weight: 600; color: var(--coral);
    display: flex; align-items: center; gap: 6px; margin-bottom: .2rem;
  }
  .cl-danger-zone-title i { font-size: 15px; }
  .cl-danger-zone-sub { font-size: .78rem; color: var(--ink3); }
</style>
@endpush

@section('content')

  <div class="page-header">
    <div>
      <h1 class="page-title">Edit client</h1>
      <p class="page-sub">Update the details for {{ $client->name }}.</p>
    </div>
    <a href="{{ route('clients.show', $client) }}" class="btn btn-ghost">
      <i class="ti ti-arrow-left"></i> Back to client
    </a>
  </div>

  <div class="cl-form-wrap">

    {{-- Live preview --}}
    <div class="cl-preview">
      <div class="cl-preview-avatar" id="preview-avatar"
        style="background:{{ $client->avatar_color }}">{{ $client->initials() }}</div>
      <div style="flex:1;min-width:0">
        <div class="cl-preview-name" id="preview-name">{{ $client->name }}</div>
        <div class="cl-preview-company" id="preview-company">{{ $client->company ?? 'No company' }}</div>
      </div>
      <span class="cl-preview-label">Preview</span>
    </div>

    <div class="card">
      <div class="card-body">
        <form action="{{ route('clients.update', $client) }}" method="POST">
          @csrf @method('PATCH')

          {{-- Basic info --}}
          <div class="cl-section-title"><i class="ti ti-user"></i> Basic info</div>

          <div class="form-group">
            <label class="form-label"><i class="ti ti-user"></i> Client name *</label>
            <input type="text" name="name" id="cl-name" class="form-input"
              value="{{ old('name', $client->name) }}" required autofocus>
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div class="form-group">
              <label class="form-label"><i class="ti ti-building"></i> Company</label>
              <input type="text" name="company" id="cl-company" class="form-input"
                value="{{ old('company', $client->company) }}" placeholder="Company name">
            </div>
            <div class="form-group">
              <label class="form-label"><i class="ti ti-mail"></i> Email address</label>
              <input type="email" name="email" class="form-input"
                value="{{ old('email', $client->email) }}" placeholder="client@company.com">
            </div>
          </div>

          {{-- Avatar color --}}
          <div class="cl-section-title"><i class="ti ti-palette"></i> Avatar color</div>

          <div class="form-group">
            <div class="cl-color-grid" id="color-grid">
              @php
                $colors   = ['#e8a325','#4a9eff','#27c93f','#e85c3a','#a855f7','#f97316','#06b6d4','#ec4899'];
                $selected = old('avatar_color', $client->avatar_color);
              @endphp
              @foreach($colors as $color)
              <label class="cl-color-swatch {{ $selected === $color ? 'selected' : '' }}"
                style="background:{{ $color }};color:{{ $color }};"
                data-color="{{ $color }}">
                <input type="radio" name="avatar_color" value="{{ $color }}"
                  {{ $selected === $color ? 'checked' : '' }}>
                <span class="check"><i class="ti ti-check"></i></span>
              </label>
              @endforeach
            </div>
          </div>

          {{-- Notes --}}
          <div class="cl-section-title"><i class="ti ti-notes"></i> Notes</div>

          <div class="form-group">
            <label class="form-label"><i class="ti ti-align-left"></i> Internal notes</label>
            <textarea name="notes" class="form-textarea"
              placeholder="Internal notes about this client...">{{ old('notes', $client->notes) }}</textarea>
          </div>

          <div style="display:flex;gap:.8rem;margin-top:.5rem">
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-check"></i> Save changes
            </button>
            <a href="{{ route('clients.show', $client) }}" class="btn btn-ghost">
              <i class="ti ti-x"></i> Cancel
            </a>
          </div>

        </form>
      </div>
    </div>

    {{-- Danger zone --}}
    <div class="cl-danger-zone">
      <div>
        <div class="cl-danger-zone-title"><i class="ti ti-alert-triangle"></i> Danger zone</div>
        <div class="cl-danger-zone-sub">Permanently delete this client and all associated data.</div>
      </div>
      <form action="{{ route('clients.destroy', $client) }}" method="POST">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm"
          data-confirm-form
          data-confirm-title="Delete client"
          data-confirm-message="Delete {{ $client->name }} permanently? This cannot be undone."
          data-confirm-submit-label="Delete client">
          <i class="ti ti-trash"></i> Delete client
        </button>
      </form>
    </div>

  </div>

@endsection

@push('scripts')
<script>
  // Live preview
  const nameInput    = document.getElementById('cl-name');
  const companyInput = document.getElementById('cl-company');
  const previewName  = document.getElementById('preview-name');
  const previewComp  = document.getElementById('preview-company');
  const previewAv    = document.getElementById('preview-avatar');

  function initials(str) {
    if (!str.trim()) return '?';
    return str.trim().split(/\s+/).slice(0, 2).map(w => w[0].toUpperCase()).join('');
  }

  nameInput.addEventListener('input', () => {
    previewName.textContent = nameInput.value || 'Client name';
    previewAv.textContent   = initials(nameInput.value || '??');
  });

  companyInput.addEventListener('input', () => {
    previewComp.textContent = companyInput.value || 'No company';
  });

  // Color picker
  document.querySelectorAll('.cl-color-swatch').forEach(swatch => {
    swatch.addEventListener('click', () => {
      document.querySelectorAll('.cl-color-swatch').forEach(s => s.classList.remove('selected'));
      swatch.classList.add('selected');
      swatch.querySelector('input').checked = true;
      const color = swatch.dataset.color;
      previewAv.style.background = color;
    });
  });
</script>
@endpush