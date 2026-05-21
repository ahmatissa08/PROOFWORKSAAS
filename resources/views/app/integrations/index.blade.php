@extends('layouts.app')

@section('title', 'Integrations')

@section('breadcrumb')
  <span class="current">Integrations</span>
@endsection

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Integrations</h1>
    <p class="page-sub">Connect your tools to auto-populate proof of work reports.</p>
    @if($currentProject)
    <div class="project-context">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
      Project context: <strong>{{ $currentProject->name }}</strong>
    </div>
    @endif
  </div>
</div>

@if(!auth()->user()->isPro())
<div class="alert alert-amber upgrade-banner">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
  <span>Free plan includes GitHub + 1 integration.</span>
  <a href="{{ route('billing.plans') }}">Upgrade to Pro for all 6 →</a>
</div>
@endif

<div class="integrations-grid">
  @foreach($providers as $key => $provider)
  @php $connected = $integrations->get($key); @endphp
  <div class="integration-card {{ $connected ? 'connected' : '' }} {{ !$provider['available'] ? 'coming-soon' : '' }}">
    <div class="integration-body">
      <div class="integration-header">
        <div class="integration-meta">
          <div class="integration-icon" style="background: {{ $provider['bg'] ?? 'var(--surface2)' }}; color: {{ $provider['color'] ?? 'var(--ink)' }}">
            {{ $provider['icon'] }}
          </div>
          <div class="integration-info">
            <div class="integration-name">{{ $provider['label'] }}</div>
            @if($connected)
            <div class="integration-account">{{ $connected->provider_account_name ?? 'Connected' }}</div>
            @endif
          </div>
        </div>
        @if($connected)
        <span class="badge badge-green">
          <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          Connected
        </span>
        @elseif(!$provider['available'])
        <span class="badge badge-gray">Soon</span>
        @else
        <span class="badge badge-gray">Not connected</span>
        @endif
      </div>

      <p class="integration-desc">{{ $provider['desc'] }}</p>

      {{-- GitHub linked repositories --}}
      @if($key === 'github' && $githubConnections->isNotEmpty())
      <div class="linked-repos">
        <div class="linked-repos-header">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
          Linked repositories
        </div>
        <div class="linked-repos-list">
          @foreach($githubConnections as $githubConnection)
          <div class="linked-repo-item">
            <span>{{ $githubConnection->resource_name }}</span>
            <span class="linked-repo-project">{{ $githubConnection->project?->name ?? 'No project' }}</span>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Connected state details --}}
      @if($connected)
        @if($connected->resource_name)
        <div class="resource-badge">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
          {{ $connected->resource_name }}
        </div>
        @elseif($key === 'github')
        <div class="integration-hint hint-amber">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
          GitHub account connected. Choose a repository to finish setup.
        </div>
        @endif

        {{-- GitHub repository selector --}}
        @if($key === 'github')
          @if($githubRepositoryError)
          <div class="integration-hint hint-danger">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ $githubRepositoryError }}
          </div>
          @elseif($githubRepositories->isNotEmpty())
          <form action="{{ route('integrations.resource.update', $connected) }}" method="POST" class="github-form">
            @csrf
            @method('PATCH')
            <div class="form-group">
              <label class="form-label" for="github_resource_{{ $connected->id }}">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
                Repository
              </label>
              <select id="github_resource_{{ $connected->id }}" name="resource_id" class="form-select">
                <option value="">Choose a repository</option>
                @foreach($githubRepositories as $repository)
                <option value="{{ $repository['id'] }}" {{ (string) $connected->resource_id === (string) $repository['id'] ? 'selected' : '' }}>
                  {{ $repository['full_name'] }}{{ $repository['private'] ? ' (private)' : '' }}
                </option>
                @endforeach
              </select>
              @error('resource_id')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
              <label class="form-label" for="github_project_{{ $connected->id }}">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Project
              </label>
              <select id="github_project_{{ $connected->id }}" name="project_id" class="form-select">
                <option value="">No project selected</option>
                @foreach($projects as $project)
                <option value="{{ $project->id }}" {{ (int) old('project_id', $currentProject?->id ?? $connected->project_id) === $project->id ? 'selected' : '' }}>
                  {{ $project->name }}
                </option>
                @endforeach
              </select>
              @error('project_id')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-ghost btn-sm btn-full">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              Save project and repository
            </button>
          </form>
          @else
          <div class="integration-hint hint-muted">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            No repositories were returned by GitHub for this account.
          </div>
          @endif
        @endif

        <div class="integration-actions">
          <form action="{{ route('integrations.destroy', $connected) }}" method="POST" class="action-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm btn-full" data-confirm-form data-confirm-title="Disconnect integration" data-confirm-message="Disconnect {{ $provider['label'] }} from this workspace?" data-confirm-submit-label="Disconnect">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
              Disconnect
            </button>
          </form>
        </div>
      @elseif(!$provider['available'])
        <button type="button" class="btn btn-ghost btn-sm btn-full" disabled>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Coming soon
        </button>
      @elseif(!$provider['configured'])
        <button type="button" class="btn btn-ghost btn-sm btn-full" disabled>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
          Setup required
        </button>
      @else
        <a href="{{ route('integrations.connect', array_filter(['provider' => $key, 'project_id' => $currentProject?->id])) }}" class="btn btn-primary btn-sm btn-full">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
          Connect {{ $provider['label'] }}
        </a>
      @endif
    </div>
  </div>
  @endforeach
</div>

<div class="coming-soon-section">
  <div class="coming-soon-header">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    Coming soon
  </div>
  <div class="coming-soon-chips">
    @foreach([
      ['Jira', '#0052CC'],
      ['Figma', '#F24E1E'],
      ['Slack', '#4A154B'],
      ['Trello', '#0079BF'],
      ['Asana', '#F06A6A'],
      ['GitLab', '#FC6D26'],
    ] as [$tool, $color])
    <div class="coming-soon-chip" style="--chip-color: {{ $color }}">
      <span class="chip-dot" style="background: {{ $color }}"></span>
      {{ $tool }}
    </div>
    @endforeach
  </div>
</div>

@push('styles')
<style>
.project-context {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  margin-top: 0.5rem;
  font-size: 0.82rem;
  color: var(--amber);
  padding: 0.35rem 0.8rem;
  background: rgba(232, 163, 37, 0.08);
  border: 1px solid rgba(232, 163, 37, 0.15);
  border-radius: 6px;
}

.project-context strong {
  font-weight: 600;
}

.upgrade-banner {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  margin-bottom: 1.5rem;
  padding: 1rem 1.2rem;
  border-radius: 10px;
  background: rgba(232, 163, 37, 0.06);
  border: 1px solid rgba(232, 163, 37, 0.12);
  color: var(--amber);
  font-size: 0.88rem;
}

.upgrade-banner a {
  color: var(--amber);
  font-weight: 600;
  margin-left: auto;
  text-decoration: none;
  transition: opacity 0.2s;
}

.upgrade-banner a:hover {
  opacity: 0.8;
}

.integrations-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.2rem;
}

.integration-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 14px;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
}

.integration-card:hover {
  transform: translateY(-3px);
  border-color: rgba(255,255,255,0.08);
  box-shadow: 0 8px 32px rgba(0,0,0,0.12);
}

.integration-card.connected {
  border-color: rgba(39, 201, 63, 0.25);
}

.integration-card.connected:hover {
  border-color: rgba(39, 201, 63, 0.4);
  box-shadow: 0 0 0 1px rgba(39, 201, 63, 0.1), 0 8px 32px rgba(0,0,0,0.12);
}

.integration-card.coming-soon {
  opacity: 0.6;
}

.integration-card.coming-soon:hover {
  transform: none;
  border-color: var(--border);
  box-shadow: none;
}

.integration-body {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.integration-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 1rem;
  gap: 0.75rem;
}

.integration-meta {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  min-width: 0;
}

.integration-icon {
  width: 44px;
  height: 44px;
  border: 1px solid var(--border2);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  flex-shrink: 0;
  transition: transform 0.2s ease;
}

.integration-card:hover .integration-icon {
  transform: scale(1.05);
}

.integration-info {
  min-width: 0;
}

.integration-name {
  font-size: 0.92rem;
  font-weight: 700;
  color: var(--ink);
  line-height: 1.3;
}

.integration-account {
  font-family: var(--mono);
  font-size: 0.62rem;
  color: var(--ink3);
  margin-top: 0.15rem;
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
}

.integration-desc {
  font-size: 0.82rem;
  color: var(--ink3);
  line-height: 1.6;
  margin-bottom: 1.2rem;
}

.linked-repos {
  margin-bottom: 0.9rem;
  padding: 0.85rem;
  border: 1px solid var(--border2);
  border-radius: 8px;
  background: var(--surface2);
}

.linked-repos-header {
  font-family: var(--mono);
  font-size: 0.58rem;
  color: var(--ink3);
  letter-spacing: 0.1em;
  text-transform: uppercase;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.35rem;
}

.linked-repos-list {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
}

.linked-repo-item {
  display: flex;
  justify-content: space-between;
  gap: 0.75rem;
  font-size: 0.76rem;
  color: var(--ink2);
  padding: 0.3rem 0;
  border-bottom: 1px solid rgba(255,255,255,0.03);
}

.linked-repo-item:last-child {
  border-bottom: none;
}

.linked-repo-project {
  color: var(--ink3);
  font-size: 0.7rem;
  flex-shrink: 0;
}

.resource-badge {
  font-family: var(--mono);
  font-size: 0.65rem;
  color: var(--ink3);
  margin-bottom: 0.8rem;
  background: var(--surface2);
  border: 1px solid var(--border2);
  padding: 0.45rem 0.8rem;
  border-radius: 6px;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
}

.integration-hint {
  display: flex;
  align-items: flex-start;
  gap: 0.5rem;
  font-size: 0.78rem;
  margin-bottom: 0.8rem;
  padding: 0.6rem 0.8rem;
  border-radius: 6px;
  line-height: 1.5;
}

.integration-hint svg {
  flex-shrink: 0;
  margin-top: 0.15rem;
}

.hint-amber {
  color: var(--amber);
  background: rgba(232, 163, 37, 0.06);
  border: 1px solid rgba(232, 163, 37, 0.1);
}

.hint-danger {
  color: var(--coral);
  background: rgba(239, 68, 68, 0.06);
  border: 1px solid rgba(239, 68, 68, 0.1);
}

.hint-muted {
  color: var(--ink3);
  background: var(--surface2);
  border: 1px solid var(--border2);
}

.github-form {
  margin-bottom: 0.8rem;
}

.github-form .form-group {
  margin-bottom: 0.7rem;
}

.github-form .form-label {
  display: flex;
  align-items: center;
  gap: 0.35rem;
  font-size: 0.72rem;
  font-weight: 600;
  color: var(--ink2);
  margin-bottom: 0.35rem;
}

.github-form .form-label svg {
  opacity: 0.5;
}

.github-form .form-select {
  width: 100%;
  padding: 0.55rem 0.75rem;
  font-size: 0.82rem;
  color: var(--ink);
  background: var(--surface);
  border: 1px solid var(--border2);
  border-radius: 6px;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 0.6rem center;
  padding-right: 2rem;
}

.github-form .form-select:focus {
  outline: none;
  border-color: var(--sky);
  box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
}

.github-form .form-error {
  display: block;
  font-size: 0.72rem;
  color: var(--coral);
  margin-top: 0.25rem;
}

.integration-actions {
  margin-top: auto;
  display: flex;
  gap: 0.5rem;
}

.action-form {
  flex: 1;
}

.btn-full {
  width: 100%;
  justify-content: center;
}

.coming-soon-section {
  margin-top: 2.5rem;
}

.coming-soon-header {
  font-family: var(--mono);
  font-size: 0.62rem;
  color: var(--ink3);
  letter-spacing: 0.12em;
  text-transform: uppercase;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.coming-soon-header svg {
  opacity: 0.5;
}

.coming-soon-chips {
  display: flex;
  gap: 0.6rem;
  flex-wrap: wrap;
}

.coming-soon-chip {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 0.55rem 1rem;
  font-size: 0.8rem;
  color: var(--ink2);
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s ease;
}

.coming-soon-chip:hover {
  border-color: var(--chip-color);
  color: var(--ink);
  transform: translateY(-1px);
}

.chip-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  flex-shrink: 0;
}

.badge {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  flex-shrink: 0;
}

@media (max-width: 640px) {
  .upgrade-banner {
    flex-direction: column;
    text-align: center;
    gap: 0.5rem;
  }

  .upgrade-banner a {
    margin-left: 0;
  }

  .integrations-grid {
    grid-template-columns: 1fr;
  }

  .integration-header {
    flex-wrap: wrap;
  }

  .linked-repo-item {
    flex-direction: column;
    gap: 0.15rem;
  }
}
</style>
@endpush
@endsection