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
    <p class="page-sub" style="margin-top:.35rem;color:var(--amber)">Project context: {{ $currentProject->name }}</p>
    @endif
  </div>
</div>

@if(!auth()->user()->isPro())
<div class="alert alert-amber" style="margin-bottom:1.5rem">
  Free plan includes GitHub + 1 integration.
  <a href="{{ route('billing.plans') }}" style="color:var(--amber);font-weight:600">Upgrade to Pro for all 6</a>
</div>
@endif

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.2rem">
  @foreach($providers as $key => $provider)
  @php $connected = $integrations->get($key); @endphp
  <div class="card" style="transition:border-color .2s" onmouseover="this.style.borderColor='var(--border2)'" onmouseout="this.style.borderColor='var(--border)'">
    <div style="padding:1.4rem">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.2rem">
        <div style="display:flex;align-items:center;gap:.8rem">
          <div style="width:40px;height:40px;border:1px solid var(--border2);border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">
            {{ $provider['icon'] }}
          </div>
          <div>
            <div style="font-size:.9rem;font-weight:600;color:var(--ink)">{{ $provider['label'] }}</div>
            @if($connected)
            <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3)">{{ $connected->provider_account_name ?? 'Connected' }}</div>
            @endif
          </div>
        </div>
        @if($connected)
        <span class="badge badge-green">Connected</span>
        @else
        <span class="badge badge-gray">Not connected</span>
        @endif
      </div>

      <p style="font-size:.8rem;color:var(--ink3);line-height:1.55;margin-bottom:1.2rem">{{ $provider['desc'] }}</p>

      @if($key === 'github' && $githubConnections->isNotEmpty())
      <div style="margin-bottom:.9rem;padding:.75rem;border:1px solid var(--border2);border-radius:6px;background:var(--surface2)">
        <div style="font-family:var(--mono);font-size:.58rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.45rem">Linked repositories</div>
        <div style="display:flex;flex-direction:column;gap:.4rem">
          @foreach($githubConnections as $githubConnection)
          <div style="display:flex;justify-content:space-between;gap:.75rem;font-size:.75rem;color:var(--ink2)">
            <span>{{ $githubConnection->resource_name }}</span>
            <span style="color:var(--ink3)">{{ $githubConnection->project?->name ?? 'No project' }}</span>
          </div>
          @endforeach
        </div>
      </div>
      @endif

      @if($connected)
        @if($connected->resource_name)
        <div style="font-family:var(--mono);font-size:.62rem;color:var(--ink3);margin-bottom:.8rem;background:var(--surface2);border:1px solid var(--border2);padding:.4rem .7rem;border-radius:4px">
          {{ $connected->resource_name }}
        </div>
        @elseif($key === 'github')
        <div style="font-size:.76rem;color:var(--amber);margin-bottom:.8rem">
          GitHub account connected. Choose a repository to finish setup.
        </div>
        @endif

        @if($key === 'github')
          @if($githubRepositoryError)
          <div style="font-size:.76rem;color:var(--coral);margin-bottom:.8rem">
            {{ $githubRepositoryError }}
          </div>
          @elseif($githubRepositories->isNotEmpty())
          <form action="{{ route('integrations.resource.update', $connected) }}" method="POST" style="margin-bottom:.8rem">
            @csrf
            @method('PATCH')
            <div class="form-group" style="margin-bottom:.6rem">
              <label class="form-label" for="github_resource_{{ $connected->id }}">Repository</label>
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
            <div class="form-group" style="margin-bottom:.6rem">
              <label class="form-label" for="github_project_{{ $connected->id }}">Project</label>
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
            <button type="submit" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center">
              Save project and repository
            </button>
          </form>
          @else
          <div style="font-size:.76rem;color:var(--ink3);margin-bottom:.8rem">
            No repositories were returned by GitHub for this account.
          </div>
          @endif
        @endif

        <div style="display:flex;gap:.5rem">
          <form action="{{ route('integrations.destroy', $connected) }}" method="POST" style="flex:1">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center" data-confirm-form data-confirm-title="Disconnect integration" data-confirm-message="Disconnect {{ $provider['label'] }} from this workspace?" data-confirm-submit-label="Disconnect">
              Disconnect
            </button>
          </form>
        </div>
      @elseif(!$provider['available'])
        <button type="button" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center" disabled>
          Coming soon
        </button>
      @elseif(!$provider['configured'])
        <button type="button" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center" disabled>
          Setup required
        </button>
      @else
        <a href="{{ route('integrations.connect', array_filter(['provider' => $key, 'project_id' => $currentProject?->id])) }}" class="btn btn-primary btn-sm" style="width:100%;justify-content:center">
          Connect {{ $provider['label'] }}
        </a>
      @endif
    </div>
  </div>
  @endforeach
</div>

<div style="margin-top:2rem">
  <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3);letter-spacing:.12em;text-transform:uppercase;margin-bottom:1rem">Coming soon</div>
  <div style="display:flex;gap:.8rem;flex-wrap:wrap">
    @foreach(['Jira', 'Figma', 'Slack', 'Trello', 'Asana', 'GitLab'] as $tool)
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:6px;padding:.5rem 1rem;font-size:.78rem;color:var(--ink3)">{{ $tool }}</div>
    @endforeach
  </div>
</div>
@endsection
