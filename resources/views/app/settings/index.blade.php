@extends('layouts.app')

@section('title', 'Settings')

@section('breadcrumb')
  <span class="current">Settings</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Settings</h1>
    <p class="page-sub">Manage your account, password, and preferences.</p>
  </div>
</div>

<div class="settings-layout">
  {{-- Sidebar Navigation --}}
  <aside class="settings-nav">
    <div class="nav-group">
      <div class="nav-label">Account</div>
      <a href="#profile" class="nav-item active" data-section="profile">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Profile
      </a>
      <a href="#security" class="nav-item" data-section="security">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Security
      </a>
      <a href="#notifications" class="nav-item" data-section="notifications">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        Notifications
      </a>
    </div>
    <div class="nav-group">
      <div class="nav-label">Billing</div>
      <a href="#billing" class="nav-item" data-section="billing">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        Plan & Billing
      </a>
    </div>
    <div class="nav-group">
      <div class="nav-label">Danger</div>
      <a href="#danger" class="nav-item nav-item-danger" data-section="danger">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Delete Account
      </a>
    </div>
  </aside>

  {{-- Main Content --}}
  <div class="settings-content">
    {{-- Profile Section --}}
    <section id="profile" class="settings-section active">
      <div class="section-header">
        <div class="section-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div>
          <h2 class="section-title">Profile</h2>
          <p class="section-desc">Update your personal information and preferences.</p>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <form action="{{ route('settings.profile') }}" method="POST" class="settings-form">
            @csrf
            @method('PATCH')

            <div class="avatar-section">
              <div class="avatar-preview">
                {{ strtoupper(substr($user->name, 0, 2)) }}
              </div>
              <div class="avatar-info">
                <div class="avatar-name">{{ $user->name }}</div>
                <div class="avatar-email">{{ $user->email }}</div>
                <div class="avatar-hint">Avatar is generated from your initials</div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label" for="name">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  Full name
                </label>
                <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
              </div>
              <div class="form-group">
                <label class="form-label" for="email">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                  Email address
                </label>
                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="timezone">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Timezone
              </label>
              <select id="timezone" name="timezone" class="form-select">
                @foreach(timezone_identifiers_list() as $tz)
                <option value="{{ $tz }}" {{ $user->timezone === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                @endforeach
              </select>
              <div class="form-hint">All reports and schedules will use this timezone</div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save profile
              </button>
            </div>
          </form>
        </div>
      </div>
    </section>

    {{-- Security Section --}}
    <section id="security" class="settings-section">
      <div class="section-header">
        <div class="section-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        </div>
        <div>
          <h2 class="section-title">Security</h2>
          <p class="section-desc">Update your password to keep your account secure.</p>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <form action="{{ route('settings.password') }}" method="POST" class="settings-form">
            @csrf
            @method('PATCH')

            <div class="form-group">
              <label class="form-label" for="current_password">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Current password
              </label>
              <div class="password-input">
                <input type="password" id="current_password" name="current_password" class="form-input" autocomplete="current-password" required>
                <button type="button" class="toggle-password" data-target="current_password">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
              @error('current_password')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label" for="password">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                  New password
                </label>
                <div class="password-input">
                  <input type="password" id="password" name="password" class="form-input" placeholder="10+ chars, mixed case, number, symbol" autocomplete="new-password" required>
                  <button type="button" class="toggle-password" data-target="password">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </div>
                @error('password')<span class="form-error">{{ $message }}</span>@enderror
                <div class="form-hint">Minimum 8 characters with letters and numbers</div>
              </div>
              <div class="form-group">
                <label class="form-label" for="password_confirmation">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                  Confirm password
                </label>
                <div class="password-input">
                  <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" autocomplete="new-password" required>
                  <button type="button" class="toggle-password" data-target="password_confirmation">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </div>
              </div>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Update password
              </button>
            </div>
          </form>
        </div>
      </div>
    </section>

    {{-- Notifications Section --}}
    <section id="notifications" class="settings-section">
      <div class="section-header">
        <div class="section-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <div>
          <h2 class="section-title">Notifications</h2>
          <p class="section-desc">Choose what you want to be notified about.</p>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <form action="{{ route('settings.notifications') }}" method="POST" class="settings-form">
            @csrf
            @method('PATCH')
            @php $prefs = $user->notification_preferences ?? []; @endphp

            <div class="notifications-list">
              @foreach([
                ['report_generated', 'Report generated', 'Get notified when a new report is ready', 'file-text'],
                ['report_viewed', 'Client viewed report', 'Get notified when your client opens a report', 'eye'],
                ['weekly_digest', 'Weekly digest', 'Weekly summary of your activity across all projects', 'bar-chart-2'],
              ] as [$key, $label, $desc, $icon])
              <label class="notification-item">
                <div class="notification-toggle">
                  <input type="checkbox" name="{{ $key }}" value="1" {{ ($prefs[$key] ?? true) ? 'checked' : '' }}>
                  <span class="toggle-slider">
                    <span class="toggle-knob"></span>
                  </span>
                </div>
                <div class="notification-icon">
                  @if($icon === 'file-text')
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                  @elseif($icon === 'eye')
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  @elseif($icon === 'bar-chart-2')
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                  @endif
                </div>
                <div class="notification-content">
                  <div class="notification-label">{{ $label }}</div>
                  <div class="notification-desc">{{ $desc }}</div>
                </div>
              </label>
              @endforeach
            </div>

            <div class="form-actions">
              <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save preferences
              </button>
            </div>
          </form>
        </div>
      </div>
    </section>

    {{-- Billing Section --}}
    <section id="billing" class="settings-section">
      <div class="section-header">
        <div class="section-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div>
          <h2 class="section-title">Plan & Billing</h2>
          <p class="section-desc">Manage your subscription and usage limits.</p>
        </div>
      </div>

      <div class="card billing-card">
        <div class="card-body">
          <div class="billing-overview">
            <div class="plan-badge-large">
              <div class="plan-icon">
                @if($user->plan === 'free')
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                @elseif($user->plan === 'pro')
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                @else
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                @endif
              </div>
              <div class="plan-info">
                <div class="plan-name">
                  {{ ucfirst($user->plan) }}
                  @if($user->onTrial())
                    <span class="badge badge-amber">Trial — ends {{ $user->trial_ends_at->diffForHumans() }}</span>
                  @endif
                </div>
                <div class="plan-desc">
                  {{ $user->isPro() ? 'Unlimited projects & all integrations unlocked' : '1 project, GitHub + 1 integration' }}
                </div>
              </div>
            </div>
            <div class="plan-actions">
              @if($user->isPro())
                <a href="{{ route('billing.portal') }}" class="btn btn-ghost">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                  Manage subscription
                </a>
              @else
                <a href="{{ route('billing.plans') }}" class="btn btn-primary">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  Upgrade to Pro
                </a>
              @endif
            </div>
          </div>
        </div>
      </div>

      @if(!$user->isPro())
      <div class="upgrade-teaser">
        <div class="teaser-content">
          <div class="teaser-title">Unlock more with Pro</div>
          <div class="teaser-features">
            <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Unlimited projects</span>
            <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> All 6 integrations</span>
            <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> AI summaries</span>
            <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Auto weekly reports</span>
          </div>
        </div>
        <a href="{{ route('billing.plans') }}" class="btn btn-ghost btn-sm">
          View plans
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
      </div>
      @endif
    </section>

    {{-- Danger Zone Section --}}
    <section id="danger" class="settings-section">
      <div class="section-header">
        <div class="section-icon section-icon-danger">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div>
          <h2 class="section-title">Danger zone</h2>
          <p class="section-desc">Irreversible actions for your account.</p>
        </div>
      </div>

      <div class="card danger-card">
        <div class="card-body">
          <div class="danger-item">
            <div class="danger-content">
              <div class="danger-title">Delete account</div>
              <div class="danger-desc">
                Account deletion requests are handled by support to prevent accidental data loss. 
                All your projects, reports, and data will be permanently removed.
              </div>
              <div class="danger-warning">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                This action cannot be undone.
              </div>
            </div>
            @php $supportEmail = config('proofwork.admin_email'); @endphp
            @if($supportEmail)
            <a href="mailto:{{ $supportEmail }}?subject={{ rawurlencode('ProofWork account deletion request') }}" class="btn btn-danger">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              Contact support
            </a>
            @else
            <button class="btn btn-danger" type="button" disabled title="Configure PROOFWORK_ADMIN_EMAIL to enable this action.">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              Contact support
            </button>
            @endif
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

@push('styles')
<style>
.settings-layout {
  display: grid;
  grid-template-columns: 220px 1fr;
  gap: 2.5rem;
  max-width: 900px;
  align-items: start;
}

.settings-nav {
  position: sticky;
  top: 2rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.nav-group {
  display: flex;
  flex-direction: column;
  gap: 0.15rem;
}

.nav-label {
  font-family: var(--mono);
  font-size: 0.58rem;
  color: var(--ink3);
  letter-spacing: 0.12em;
  text-transform: uppercase;
  padding: 0 0.75rem;
  margin-bottom: 0.4rem;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.55rem 0.75rem;
  border-radius: 8px;
  font-size: 0.85rem;
  color: var(--ink2);
  text-decoration: none;
  transition: all 0.15s ease;
  cursor: pointer;
}

.nav-item:hover {
  background: rgba(255,255,255,0.03);
  color: var(--ink);
}

.nav-item.active {
  background: rgba(56, 189, 248, 0.08);
  color: var(--sky);
  font-weight: 500;
}

.nav-item.active svg {
  color: var(--sky);
}

.nav-item-danger {
  color: var(--coral);
}

.nav-item-danger:hover {
  background: rgba(232, 92, 58, 0.06);
  color: var(--coral);
}

.settings-content {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.settings-section {
  display: none;
  animation: fadeIn 0.3s ease;
}

.settings-section.active {
  display: block;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(8px); }
  to { opacity: 1; transform: translateY(0); }
}

.section-header {
  display: flex;
  align-items: center;
  gap: 0.9rem;
  margin-bottom: 1.2rem;
}

.section-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: rgba(56, 189, 248, 0.08);
  color: var(--sky);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.section-icon-danger {
  background: rgba(232, 92, 58, 0.08);
  color: var(--coral);
}

.section-title {
  font-size: 1.15rem;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 0.15rem;
}

.section-desc {
  font-size: 0.82rem;
  color: var(--ink3);
}

.settings-form {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

.avatar-section {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding-bottom: 1.2rem;
  margin-bottom: 0.5rem;
  border-bottom: 1px solid var(--border);
}

.avatar-preview {
  width: 56px;
  height: 56px;
  border-radius: 14px;
  background: linear-gradient(135deg, var(--sky), var(--purple));
  color: white;
  font-size: 1.2rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.avatar-info {
  min-width: 0;
}

.avatar-name {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--ink);
}

.avatar-email {
  font-size: 0.78rem;
  color: var(--ink3);
  margin-top: 0.1rem;
}

.avatar-hint {
  font-size: 0.7rem;
  color: var(--ink3);
  margin-top: 0.25rem;
  font-family: var(--mono);
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
}

.form-label {
  display: flex;
  align-items: center;
  gap: 0.35rem;
  font-size: 0.78rem;
  font-weight: 600;
  color: var(--ink2);
}

.form-label svg {
  opacity: 0.4;
}

.form-input,
.form-select {
  padding: 0.65rem 0.9rem;
  font-size: 0.88rem;
  color: var(--ink);
  background: var(--surface);
  border: 1px solid var(--border2);
  border-radius: 8px;
  transition: all 0.2s ease;
  width: 100%;
}

.form-input:focus,
.form-select:focus {
  outline: none;
  border-color: var(--sky);
  box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
}

.form-select {
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  padding-right: 2.2rem;
}

.form-hint {
  font-size: 0.72rem;
  color: var(--ink3);
  margin-top: 0.15rem;
}

.form-error {
  font-size: 0.75rem;
  color: var(--coral);
  margin-top: 0.2rem;
}

.password-input {
  position: relative;
}

.password-input .form-input {
  padding-right: 2.5rem;
}

.toggle-password {
  position: absolute;
  right: 0.5rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: var(--ink3);
  cursor: pointer;
  padding: 0.3rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  transition: color 0.2s;
}

.toggle-password:hover {
  color: var(--ink);
}

.form-actions {
  display: flex;
  gap: 0.75rem;
  padding-top: 0.5rem;
}

.form-actions .btn {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
}

.notifications-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-bottom: 1.2rem;
}

.notification-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border: 1px solid var(--border);
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.notification-item:hover {
  border-color: rgba(255,255,255,0.08);
  background: rgba(255,255,255,0.01);
}

.notification-toggle {
  position: relative;
  flex-shrink: 0;
}

.notification-toggle input {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.toggle-slider {
  display: block;
  width: 40px;
  height: 22px;
  background: var(--border2);
  border-radius: 11px;
  position: relative;
  transition: background 0.3s ease;
  cursor: pointer;
}

.toggle-knob {
  position: absolute;
  top: 2px;
  left: 2px;
  width: 18px;
  height: 18px;
  background: var(--ink);
  border-radius: 50%;
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.notification-toggle input:checked + .toggle-slider {
  background: var(--green);
}

.notification-toggle input:checked + .toggle-slider .toggle-knob {
  transform: translateX(18px);
  background: white;
}

.notification-icon {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  background: var(--surface2);
  color: var(--ink3);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.notification-content {
  flex: 1;
  min-width: 0;
}

.notification-label {
  font-size: 0.88rem;
  font-weight: 600;
  color: var(--ink);
}

.notification-desc {
  font-size: 0.78rem;
  color: var(--ink3);
  margin-top: 0.15rem;
}

.billing-overview {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1.25rem;
}

.plan-badge-large {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.plan-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background: var(--surface2);
  color: var(--amber);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.plan-info {
  min-width: 0;
}

.plan-name {
  font-size: 1rem;
  font-weight: 700;
  color: var(--ink);
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.2rem;
}

.plan-desc {
  font-size: 0.8rem;
  color: var(--ink3);
}

.plan-actions {
  display: flex;
  gap: 0.6rem;
}

.plan-actions .btn {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
}

.upgrade-teaser {
  margin-top: 1rem;
  padding: 1.2rem 1.5rem;
  background: linear-gradient(135deg, rgba(232,163,37,0.06), rgba(232,163,37,0.02));
  border: 1px solid rgba(232,163,37,0.15);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1rem;
}

.teaser-content {
  flex: 1;
}

.teaser-title {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--amber);
  margin-bottom: 0.5rem;
}

.teaser-features {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.teaser-features span {
  font-size: 0.76rem;
  color: var(--ink2);
  display: flex;
  align-items: center;
  gap: 0.35rem;
}

.teaser-features span svg {
  color: var(--green);
  flex-shrink: 0;
}

.danger-card {
  border-color: rgba(232, 92, 58, 0.2);
}

.danger-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1.5rem;
  flex-wrap: wrap;
}

.danger-content {
  flex: 1;
  min-width: 250px;
}

.danger-title {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--coral);
  margin-bottom: 0.3rem;
}

.danger-desc {
  font-size: 0.8rem;
  color: var(--ink3);
  line-height: 1.5;
  margin-bottom: 0.6rem;
}

.danger-warning {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.72rem;
  color: var(--coral);
  background: rgba(232, 92, 58, 0.06);
  padding: 0.35rem 0.7rem;
  border-radius: 5px;
  font-weight: 500;
}

.danger-warning svg {
  flex-shrink: 0;
}

@media (max-width: 768px) {
  .settings-layout {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .settings-nav {
    position: static;
    flex-direction: row;
    overflow-x: auto;
    gap: 0.5rem;
    padding-bottom: 0.5rem;
    scrollbar-width: none;
  }

  .settings-nav::-webkit-scrollbar {
    display: none;
  }

  .nav-group {
    flex-direction: row;
    gap: 0.3rem;
    flex-shrink: 0;
  }

  .nav-label {
    display: none;
  }

  .nav-item {
    white-space: nowrap;
    padding: 0.5rem 0.9rem;
    font-size: 0.82rem;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .billing-overview {
    flex-direction: column;
    align-items: flex-start;
  }

  .danger-item {
    flex-direction: column;
    align-items: flex-start;
  }

  .upgrade-teaser {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
@endpush

@push('scripts')
<script>
(function() {
  const navItems = document.querySelectorAll('.settings-nav .nav-item');
  const sections = document.querySelectorAll('.settings-section');

  // Navigation click handler
  navItems.forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      const targetSection = this.dataset.section;

      // Update nav active state
      navItems.forEach(nav => nav.classList.remove('active'));
      this.classList.add('active');

      // Show target section
      sections.forEach(section => {
        section.classList.remove('active');
        if (section.id === targetSection) {
          section.classList.add('active');
        }
      });

      // Update URL hash
      window.history.replaceState(null, null, '#' + targetSection);
    });
  });

  // Handle initial hash
  const hash = window.location.hash.slice(1);
  if (hash) {
    const targetNav = document.querySelector(`.nav-item[data-section="${hash}"]`);
    if (targetNav) {
      targetNav.click();
    }
  }

  // Password visibility toggle
  document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function() {
      const targetId = this.dataset.target;
      const input = document.getElementById(targetId);
      const isVisible = input.type === 'text';

      input.type = isVisible ? 'password' : 'text';

      // Update icon
      this.innerHTML = isVisible 
        ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>'
        : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
    });
  });
})();
</script>
@endpush
@endsection
