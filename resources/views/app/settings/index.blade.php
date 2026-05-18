@extends('layouts.app')
@section('title', 'Settings')
@section('breadcrumb')
  <span class="current">Settings</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Settings</h1>
    <p class="page-sub">Manage your account, password, and notifications.</p>
  </div>
</div>

<div style="max-width:640px;display:flex;flex-direction:column;gap:1.5rem">
  <div class="card">
    <div class="card-header"><div class="card-title">Profile</div></div>
    <div class="card-body">
      <form action="{{ route('settings.profile') }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="form-group">
          <label class="form-label">Full name</label>
          <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Email address</label>
          <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Timezone</label>
          <select name="timezone" class="form-select">
            @foreach(timezone_identifiers_list() as $tz)
            <option value="{{ $tz }}" {{ $user->timezone === $tz ? 'selected' : '' }}>{{ $tz }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Save profile</button>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><div class="card-title">Change password</div></div>
    <div class="card-body">
      <form action="{{ route('settings.password') }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="form-group">
          <label class="form-label">Current password</label>
          <input type="password" name="current_password" class="form-input" required>
          @error('current_password')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">New password</label>
          <input type="password" name="password" class="form-input" required>
          @error('password')<span class="form-error">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Confirm new password</label>
          <input type="password" name="password_confirmation" class="form-input" required>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Update password</button>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><div class="card-title">Notifications</div></div>
    <div class="card-body">
      <form action="{{ route('settings.notifications') }}" method="POST">
        @csrf
        @method('PATCH')
        @php $prefs = $user->notification_preferences ?? []; @endphp
        @foreach([
          ['report_generated', 'Report generated', 'Get notified when a new report is ready'],
          ['report_viewed', 'Client viewed report', 'Get notified when your client opens a report'],
          ['weekly_digest', 'Weekly digest', 'Weekly summary of your activity across all projects'],
        ] as [$key, $label, $desc])
        <label style="display:flex;align-items:flex-start;gap:.8rem;cursor:pointer;padding:.6rem 0;border-bottom:1px solid var(--border)">
          <input type="checkbox" name="{{ $key }}" value="1" style="accent-color:var(--amber);margin-top:.2rem" {{ ($prefs[$key] ?? true) ? 'checked' : '' }}>
          <div>
            <div style="font-size:.85rem;font-weight:500;color:var(--ink)">{{ $label }}</div>
            <div style="font-size:.78rem;color:var(--ink3);margin-top:.1rem">{{ $desc }}</div>
          </div>
        </label>
        @endforeach
        <div style="margin-top:1rem">
          <button type="submit" class="btn btn-primary btn-sm">Save preferences</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-header"><div class="card-title">Plan & Billing</div></div>
    <div class="card-body">
      <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem">
        <div>
          <div style="font-size:.88rem;font-weight:500;margin-bottom:.2rem">
            {{ ucfirst($user->plan) }} plan
            @if($user->onTrial())<span class="badge badge-amber" style="margin-left:.4rem">Trial - ends {{ $user->trial_ends_at->diffForHumans() }}</span>@endif
          </div>
          <div style="font-size:.78rem;color:var(--ink3)">
            {{ $user->isPro() ? 'Unlimited projects & all integrations' : '1 project, 2 integrations' }}
          </div>
        </div>
        <div style="display:flex;gap:.6rem">
          @if($user->isPro())
          <a href="{{ route('billing.portal') }}" class="btn btn-ghost btn-sm">Manage subscription</a>
          @else
          <a href="{{ route('billing.plans') }}" class="btn btn-primary btn-sm">Upgrade to Pro</a>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="card" style="border-color:rgba(232,92,58,.2)">
    <div class="card-header" style="border-bottom-color:rgba(232,92,58,.15)">
      <div class="card-title" style="color:var(--coral)">Danger zone</div>
    </div>
    <div class="card-body">
      <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap">
        <div>
          <div style="font-size:.85rem;font-weight:500;margin-bottom:.2rem">Delete account</div>
          <div style="font-size:.78rem;color:var(--ink3)">Permanently delete your account and all data. Cannot be undone.</div>
        </div>
        <button class="btn btn-danger btn-sm" onclick="alert('Please contact addimiahmat@gmail.com to delete your account.')">
          Delete account
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
