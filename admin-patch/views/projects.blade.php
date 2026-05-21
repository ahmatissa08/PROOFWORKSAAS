@extends('admin.layout')
@section('title', 'Projects')
@section('breadcrumb', '<span class="current">Projects</span>')

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Projects</h1>
    <p class="page-sub">{{ $projects->total() }} total projects across all users</p>
  </div>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Project</th>
        <th>Owner</th>
        <th>Client</th>
        <th>Status</th>
        <th>Reports</th>
        <th>Auto-send</th>
        <th>Created</th>
      </tr>
    </thead>
    <tbody>
      @forelse($projects as $project)
      <tr>
        <td style="font-family:var(--mono);font-size:.7rem;color:var(--ink3)">{{ $project->id }}</td>
        <td class="td-main">
          <div style="display:flex;align-items:center;gap:.6rem">
            <div style="width:24px;height:24px;border-radius:5px;background:{{ $project->color }};flex-shrink:0"></div>
            {{ $project->name }}
          </div>
        </td>
        <td>
          <a href="{{ route('admin.users.show', $project->user) }}"
             style="color:var(--sky);text-decoration:none;font-size:.78rem">
            {{ $project->user?->name ?? '—' }}
          </a>
        </td>
        <td style="font-size:.78rem;color:var(--ink2)">{{ $project->client?->name ?? '—' }}</td>
        <td>
          <span class="badge {{ match($project->status) {
            'active'    => 'badge-green',
            'paused'    => 'badge-amber',
            'completed' => 'badge-gray',
            default     => 'badge-gray'
          } }}">{{ $project->status }}</span>
        </td>
        <td style="font-family:var(--mono);font-size:.72rem;text-align:center">{{ $project->reports_count }}</td>
        <td style="text-align:center">
          @if($project->auto_send)
          <span class="badge badge-green">on</span>
          @else
          <span class="badge badge-gray">off</span>
          @endif
        </td>
        <td style="font-family:var(--mono);font-size:.68rem;color:var(--ink3)">
          {{ $project->created_at->format('d M Y') }}
        </td>
      </tr>
      @empty
      <tr><td colspan="8" style="text-align:center;padding:2.5rem;color:var(--ink3)">No projects found.</td></tr>
      @endforelse
    </tbody>
  </table>
  @if($projects->hasPages())
  <div class="pagination">{{ $projects->links() }}</div>
  @endif
</div>
@endsection
