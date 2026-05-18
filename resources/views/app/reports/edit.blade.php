@extends('layouts.app')
@section('title', 'Edit report')
@section('breadcrumb')
  <a href="{{ route('reports.index') }}">Reports</a>
  <span class="sep">›</span>
  <a href="{{ route('reports.show', $report) }}">{{ $report->title }}</a>
  <span class="sep">›</span>
  <span class="current">Edit</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Edit report</h1>
    <p class="page-sub">{{ $report->periodLabel() }}</p>
  </div>
</div>

<div style="max-width:760px">
  <div class="card">
    <div class="card-body">
      <form action="{{ route('reports.update', $report) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="form-group">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-input" value="{{ old('title', $report->title) }}" required>
          @error('title')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            @foreach(['draft' => 'Draft', 'ready' => 'Ready', 'sent' => 'Sent'] as $value => $label)
            <option value="{{ $value }}" {{ old('status', $report->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">AI summary</label>
          <textarea name="ai_summary" class="form-textarea" style="min-height:180px">{{ old('ai_summary', $report->ai_summary) }}</textarea>
          @error('ai_summary')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div style="display:flex;gap:.8rem">
          <button type="submit" class="btn btn-primary">Save report</button>
          <a href="{{ route('reports.show', $report) }}" class="btn btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
