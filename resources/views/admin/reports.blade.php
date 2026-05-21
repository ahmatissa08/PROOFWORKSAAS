@extends('admin.layout')
@section('title', 'Reports')
@section('breadcrumb', '<span class="current">Reports</span>')

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Reports</h1>
    <p class="page-sub">{{ $reports->total() }} total reports generated</p>
  </div>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Owner</th>
        <th>Client</th>
        <th>Period</th>
        <th>Status</th>
        <th>Views</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($reports as $report)
      <tr>
        <td style="font-family:var(--mono);font-size:.7rem;color:var(--ink3)">{{ $report->id }}</td>
        <td class="td-main" style="max-width:200px">
          <div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $report->title }}</div>
        </td>
        <td>
          <a href="{{ route('admin.users.show', $report->user) }}"
             style="color:var(--sky);text-decoration:none;font-size:.78rem">
            {{ $report->user?->name ?? '—' }}
          </a>
        </td>
        <td style="font-size:.78rem;color:var(--ink2)">{{ $report->client?->name ?? '—' }}</td>
        <td style="font-family:var(--mono);font-size:.68rem;color:var(--ink3)">
          {{ $report->periodLabel() }}
        </td>
        <td>
          <span class="badge {{ match($report->status) {
            'sent'  => 'badge-green',
            'ready' => 'badge-amber',
            default => 'badge-gray'
          } }}">{{ $report->status }}</span>
        </td>
        <td style="font-family:var(--mono);font-size:.72rem;text-align:center">{{ $report->view_count }}</td>
        <td style="font-family:var(--mono);font-size:.68rem;color:var(--ink3)">
          {{ $report->created_at->format('d M Y') }}
        </td>
        <td>
          @if($report->share_enabled && $report->share_token)
          <a href="{{ $report->shareUrl() }}" target="_blank" class="btn btn-ghost btn-sm">↗ View</a>
          @endif
        </td>
      </tr>
      @empty
      <tr><td colspan="9" style="text-align:center;padding:2.5rem;color:var(--ink3)">No reports found.</td></tr>
      @endforelse
    </tbody>
  </table>
  @if($reports->hasPages())
  <div class="pagination">{{ $reports->links() }}</div>
  @endif
</div>
@endsection
