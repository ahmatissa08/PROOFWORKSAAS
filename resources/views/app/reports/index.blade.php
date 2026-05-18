@extends('layouts.app')
@section('title', 'Reports')
@section('breadcrumb')
  <span class="current">Reports</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Reports</h1>
    <p class="page-sub">All generated proof of work reports.</p>
  </div>
</div>

@if($reports->isEmpty())
<div class="card">
  <div class="empty-state">
    <div class="empty-icon">R</div>
    <div class="empty-title">No reports yet</div>
    <div class="empty-sub">Generate your first report from a project page.</div>
    <a href="{{ route('projects.index') }}" class="btn btn-primary">Go to projects</a>
  </div>
</div>
@else
<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>Report</th>
        <th>Project</th>
        <th>Client</th>
        <th>Period</th>
        <th>Status</th>
        <th>Views</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($reports as $report)
      <tr>
        <td class="td-main">
          <a href="{{ route('reports.show', $report) }}" style="color:var(--ink);text-decoration:none">
            {{ $report->title }}
          </a>
        </td>
        <td>{{ $report->project?->name ?? '-' }}</td>
        <td>{{ $report->client?->name ?? '-' }}</td>
        <td>
          <span style="font-family:var(--mono);font-size:.72rem">{{ $report->periodLabel() }}</span>
        </td>
        <td>
          <span class="badge {{ match($report->status) { 'sent' => 'badge-green', 'ready' => 'badge-amber', default => 'badge-gray' } }}">
            {{ $report->status }}
          </span>
        </td>
        <td>
          <span style="font-family:var(--mono);font-size:.72rem">{{ $report->view_count }}</span>
        </td>
        <td>
          <div style="display:flex;gap:.4rem">
            <a href="{{ route('reports.show', $report) }}" class="btn btn-ghost btn-sm">View</a>
            @if($report->share_enabled)
            <a href="{{ $report->shareUrl() }}" target="_blank" class="btn btn-ghost btn-sm">Share</a>
            @endif
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div style="padding:1rem 1.4rem;border-top:1px solid var(--border)">
    {{ $reports->links() }}
  </div>
</div>
@endif
@endsection
