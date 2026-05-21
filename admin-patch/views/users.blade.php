@extends('admin.layout')
@section('title', 'Users')
@section('breadcrumb', '<span class="current">Users</span>')

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Users</h1>
    <p class="page-sub">{{ $users->total() }} total users</p>
  </div>
</div>

<!-- Search & Filter -->
<form method="GET" action="{{ route('admin.users') }}">
  <div class="search-bar">
    <input type="text" name="search" class="search-input" placeholder="Search name or email..."
      value="{{ request('search') }}">
    <select name="plan" class="form-select" style="width:140px;padding:.6rem .9rem;font-size:.78rem">
      <option value="">All plans</option>
      <option value="free"   {{ request('plan') === 'free'   ? 'selected' : '' }}>Free</option>
      <option value="pro"    {{ request('plan') === 'pro'    ? 'selected' : '' }}>Pro</option>
      <option value="agency" {{ request('plan') === 'agency' ? 'selected' : '' }}>Agency</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    @if(request()->hasAny(['search','plan']))
    <a href="{{ route('admin.users') }}" class="btn btn-ghost btn-sm">Clear</a>
    @endif
  </div>
</form>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Plan</th>
        <th>Verified</th>
        <th>Projects</th>
        <th>Reports</th>
        <th>Trial ends</th>
        <th>Joined</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $user)
      <tr>
        <td style="font-family:var(--mono);font-size:.7rem;color:var(--ink3)">{{ $user->id }}</td>
        <td class="td-main">
          <a href="{{ route('admin.users.show', $user) }}"
             style="color:var(--ink);text-decoration:none">{{ $user->name }}</a>
        </td>
        <td style="font-family:var(--mono);font-size:.7rem">{{ $user->email }}</td>
        <td>
          <span class="badge {{ match($user->plan) {
            'pro'    => 'badge-amber',
            'agency' => 'badge-sky',
            default  => 'badge-gray'
          } }}">{{ $user->plan }}</span>
        </td>
        <td>
          @if($user->email_verified_at)
            <span class="badge badge-green">✓ verified</span>
          @else
            <span class="badge badge-coral">✗ pending</span>
          @endif
        </td>
        <td style="font-family:var(--mono);font-size:.72rem;text-align:center">{{ $user->projects_count }}</td>
        <td style="font-family:var(--mono);font-size:.72rem;text-align:center">{{ $user->reports_count }}</td>
        <td style="font-family:var(--mono);font-size:.68rem;color:var(--ink3)">
          {{ $user->trial_ends_at ? $user->trial_ends_at->format('M d') : '—' }}
        </td>
        <td style="font-family:var(--mono);font-size:.68rem;color:var(--ink3)">
          {{ $user->created_at->format('d M Y') }}
        </td>
        <td>
          <div style="display:flex;gap:.3rem">
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost btn-sm">View</a>
            <form action="{{ route('admin.users.impersonate', $user) }}" method="POST" style="display:inline">
              @csrf
              <button type="submit" class="btn btn-ghost btn-sm" title="Login as this user">👤</button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="10" style="text-align:center;padding:2.5rem;color:var(--ink3)">No users found.</td></tr>
      @endforelse
    </tbody>
  </table>
  @if($users->hasPages())
  <div class="pagination">{{ $users->links() }}</div>
  @endif
</div>
@endsection
