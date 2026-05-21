@extends('layouts.app')
@section('title', 'Add client')
@section('breadcrumb')
  <a href="{{ route('clients.index') }}">Clients</a>
  <span class="sep">/</span>
  <span class="current">Add client</span>
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
</style>
@endpush

@section('content')

  <div class="page-header">
    <div>
      <h1 class="page-title">Add client</h1>
      <p class="page-sub">Create a new client to assign projects and send reports.</p>
    </div>
  </div>

  <div class="cl-form-wrap">

    {{-- Live preview --}}
    <div class="cl-preview" id="cl-preview">
      <div class="cl-preview-avatar" id="preview-avatar" style="background:#e8a325">AC</div>
      <div style="flex:1;min-width:0">
        <div class="cl-preview-name" id="preview-name">Client name</div>
        <div class="cl-preview-company" id="preview-company">Company name</div>
      </div>
      <span class="cl-preview-label">Preview</span>
    </div>

    <div class="card">
      <div class="card-body">
        <form action="{{ route('clients.store') }}" method="POST" id="cl-form">
          @csrf

          {{-- Basic info --}}
          <div class="cl-section-title"><i class="ti ti-user"></i> Basic info</div>

          <div class="form-group">
            <label class="form-label"><i class="ti ti-user"></i> Client name *</label>
            <input type="text" name="name" id="cl-name" class="form-input"
              value="{{ old('name') }}" placeholder="e.g. Acme Corp" required autofocus>
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div class="form-group">
              <label class="form-label"><i class="ti ti-building"></i> Company</label>
              <input type="text" name="company" id="cl-company" class="form-input"
                value="{{ old('company') }}" placeholder="Company name">
            </div>
            <div class="form-group">
              <label class="form-label"><i class="ti ti-mail"></i> Email address</label>
              <input type="email" name="email" class="form-input"
                value="{{ old('email') }}" placeholder="client@company.com">
              <div class="form-hint">Used to send reports. Optional.</div>
            </div>
          </div>

          {{-- Avatar color --}}
          <div class="cl-section-title"><i class="ti ti-palette"></i> Avatar color</div>

          <div class="form-group">
            <div class="cl-color-grid" id="color-grid">
              @php
                $colors = ['#e8a325','#4a9eff','#27c93f','#e85c3a','#a855f7','#f97316','#06b6d4','#ec4899'];
                $selected = old('avatar_color', '#e8a325');
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
              placeholder="Internal notes about this client...">{{ old('notes') }}</textarea>
          </div>

          <div style="display:flex;gap:.8rem;margin-top:.5rem">
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-plus"></i> Add client
            </button>
            <a href="{{ route('clients.index') }}" class="btn btn-ghost">
              <i class="ti ti-x"></i> Cancel
            </a>
          </div>

        </form>
      </div>
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
    previewAv.textContent   = initials(nameInput.value || 'CL');
  });

  companyInput.addEventListener('input', () => {
    previewComp.textContent = companyInput.value || 'Company name';
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