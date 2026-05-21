@extends('layouts.app')

@section('title', 'Welcome to ProofWork')

@section('content')
<div class="onboarding-container">
  {{-- Hero --}}
  <div class="onboarding-hero">
    <div class="hero-avatar">
      <div class="avatar-ring">
        <span>👋</span>
      </div>
      <div class="avatar-pulse"></div>
    </div>
    <h1 class="hero-title">Welcome to ProofWork!</h1>
    <p class="hero-sub">
      You're on a <strong>14-day free trial</strong> of Pro. Let's get you set up in 3 quick steps.
    </p>
    <div class="trial-pill">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      Trial ends {{ auth()->user()->trial_ends_at?->format('M d, Y') ?? 'in 14 days' }}
    </div>
  </div>

  {{-- Progress Bar --}}
  @php
    $steps = [
      ['number' => '01', 'title' => 'Create your first project', 'desc' => 'A project connects a client with your tools and generates reports automatically.', 'action_label' => 'Create project', 'action_url' => route('projects.create'), 'done' => auth()->user()->projects()->exists(), 'icon' => 'folder'],
      ['number' => '02', 'title' => 'Connect your tools', 'desc' => 'Connect GitHub, Linear, or Calendar so ProofWork can collect your activity.', 'action_label' => 'Connect tools', 'action_url' => route('integrations.index'), 'done' => auth()->user()->integrations()->exists(), 'icon' => 'plug'],
      ['number' => '03', 'title' => 'Generate your first report', 'desc' => 'ProofWork pulls your activity and builds a client-ready report in seconds.', 'action_label' => 'Go to projects', 'action_url' => route('projects.index'), 'done' => auth()->user()->reports()->exists(), 'icon' => 'file-text'],
    ];
    $completed = collect($steps)->filter(fn($s) => $s['done'])->count();
    $progress = ($completed / count($steps)) * 100;
  @endphp

  <div class="progress-section">
    <div class="progress-header">
      <span class="progress-label">Getting started</span>
      <span class="progress-count">{{ $completed }}/{{ count($steps) }} completed</span>
    </div>
    <div class="progress-bar">
      <div class="progress-fill" style="width: {{ $progress }}%"></div>
    </div>
  </div>

  {{-- Steps --}}
  <div class="steps-list">
    @foreach($steps as $index => $step)
    <div class="step-card {{ $step['done'] ? 'done' : '' }} {{ !$step['done'] && ($index === 0 || $steps[$index - 1]['done']) ? 'active' : '' }}">
      {{-- Connector line --}}
      @if(!$loop->last)
        <div class="step-connector {{ $step['done'] ? 'done' : '' }}"></div>
      @endif

      <div class="step-body">
        <div class="step-badge">
          @if($step['done'])
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          @else
            <span class="step-number">{{ $step['number'] }}</span>
          @endif
        </div>

        <div class="step-content">
          <div class="step-header">
            <div class="step-icon">
              @if($step['icon'] === 'folder')
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
              @elseif($step['icon'] === 'plug')
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22v-5"/><path d="M9 8V2"/><path d="M15 8V2"/><path d="M18 8v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8z"/></svg>
              @elseif($step['icon'] === 'file-text')
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              @endif
            </div>
            <h3 class="step-title">{{ $step['title'] }}</h3>
            @if($step['done'])
              <span class="step-status">Done</span>
            @elseif(!$step['done'] && ($index === 0 || $steps[$index - 1]['done']))
              <span class="step-status status-next">Next</span>
            @endif
          </div>

          <p class="step-desc">{{ $step['desc'] }}</p>

          @if(!$step['done'])
            <a href="{{ $step['action_url'] }}" class="btn btn-primary btn-sm step-action">
              {{ $step['action_label'] }}
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
          @else
            <div class="step-completed">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              Completed
            </div>
          @endif
        </div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- Skip / Dashboard --}}
  <div class="onboarding-footer">
    @if($completed === count($steps))
      <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Go to dashboard
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    @else
      <a href="{{ route('dashboard') }}" class="skip-link">
        <span>Skip for now</span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    @endif
  </div>

  {{-- Tips --}}
  <div class="tips-section">
    <div class="tips-header">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
      Pro tip
    </div>
    <p class="tips-text">
      You can always come back to this page from your <a href="{{ route('dashboard') }}">dashboard</a>. 
      Your trial gives you full access to all Pro features — no credit card required.
    </p>
  </div>
</div>

@push('styles')
<style>
.onboarding-container {
  max-width: 580px;
  margin: 2.5rem auto;
  padding: 0 1rem;
}

.onboarding-hero {
  text-align: center;
  margin-bottom: 2.5rem;
}

.hero-avatar {
  position: relative;
  display: inline-block;
  margin-bottom: 1.5rem;
}

.avatar-ring {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--amber), #f5c842);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.2rem;
  position: relative;
  z-index: 2;
  box-shadow: 0 4px 20px rgba(232, 163, 37, 0.3);
}

.avatar-pulse {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 72px;
  height: 72px;
  border-radius: 50%;
  background: var(--amber);
  opacity: 0.3;
  animation: pulse 2s ease-out infinite;
  z-index: 1;
}

@keyframes pulse {
  0% { transform: translate(-50%, -50%) scale(1); opacity: 0.3; }
  100% { transform: translate(-50%, -50%) scale(1.6); opacity: 0; }
}

.hero-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 0.5rem;
}

.hero-sub {
  color: var(--ink2);
  font-size: 0.95rem;
  line-height: 1.65;
  max-width: 420px;
  margin: 0 auto;
}

.hero-sub strong {
  color: var(--amber);
  font-weight: 600;
}

.trial-pill {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  margin-top: 1rem;
  padding: 0.4rem 1rem;
  background: rgba(232, 163, 37, 0.08);
  border: 1px solid rgba(232, 163, 37, 0.15);
  border-radius: 20px;
  font-family: var(--mono);
  font-size: 0.72rem;
  color: var(--amber);
}

.progress-section {
  margin-bottom: 1.5rem;
}

.progress-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
}

.progress-label {
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--ink);
}

.progress-count {
  font-family: var(--mono);
  font-size: 0.68rem;
  color: var(--ink3);
}

.progress-bar {
  height: 4px;
  background: var(--border);
  border-radius: 2px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, var(--amber), var(--green));
  border-radius: 2px;
  transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.steps-list {
  display: flex;
  flex-direction: column;
  gap: 0;
  position: relative;
}

.step-card {
  position: relative;
  padding-left: 24px;
}

.step-card.done {
  opacity: 0.55;
}

.step-card.active .step-badge {
  background: var(--amber);
  color: #000;
  box-shadow: 0 0 0 4px rgba(232, 163, 37, 0.15);
}

.step-connector {
  position: absolute;
  left: 17px;
  top: 44px;
  width: 2px;
  height: calc(100% - 32px);
  background: var(--border);
  border-radius: 1px;
  transition: background 0.3s ease;
}

.step-connector.done {
  background: var(--green);
}

.step-body {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  padding: 1rem 0 1.5rem;
}

.step-badge {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: var(--surface2);
  border: 2px solid var(--border2);
  color: var(--ink3);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: all 0.3s ease;
  position: relative;
  z-index: 2;
}

.step-card.done .step-badge {
  background: var(--green);
  border-color: var(--green);
  color: white;
}

.step-number {
  font-family: var(--mono);
  font-size: 0.7rem;
  font-weight: 700;
}

.step-content {
  flex: 1;
  padding-top: 0.2rem;
}

.step-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.35rem;
  flex-wrap: wrap;
}

.step-icon {
  color: var(--ink3);
  opacity: 0.5;
}

.step-title {
  font-size: 0.92rem;
  font-weight: 700;
  color: var(--ink);
  margin: 0;
}

.step-card.done .step-title {
  text-decoration: line-through;
  color: var(--ink3);
}

.step-status {
  font-family: var(--mono);
  font-size: 0.58rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  padding: 0.15rem 0.5rem;
  border-radius: 3px;
  background: var(--surface2);
  color: var(--ink3);
  border: 1px solid var(--border);
}

.step-status.status-next {
  background: rgba(232, 163, 37, 0.1);
  color: var(--amber);
  border-color: rgba(232, 163, 37, 0.2);
}

.step-desc {
  font-size: 0.82rem;
  color: var(--ink3);
  line-height: 1.55;
  margin-bottom: 0.9rem;
}

.step-card.done .step-desc {
  margin-bottom: 0;
}

.step-action {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
}

.step-completed {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.78rem;
  color: var(--green);
  font-weight: 500;
}

.onboarding-footer {
  text-align: center;
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 1px solid var(--border);
}

.skip-link {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-family: var(--mono);
  font-size: 0.72rem;
  color: var(--ink3);
  text-decoration: none;
  transition: color 0.2s;
  padding: 0.5rem 1rem;
  border-radius: 6px;
}

.skip-link:hover {
  color: var(--ink);
  background: rgba(255,255,255,0.03);
}

.skip-link span {
  border-bottom: 1px dotted var(--ink3);
}

.tips-section {
  margin-top: 2rem;
  padding: 1.2rem;
  background: rgba(56, 189, 248, 0.04);
  border: 1px solid rgba(56, 189, 248, 0.1);
  border-radius: 10px;
}

.tips-header {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-family: var(--mono);
  font-size: 0.62rem;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: var(--sky);
  margin-bottom: 0.5rem;
}

.tips-text {
  font-size: 0.82rem;
  color: var(--ink2);
  line-height: 1.6;
  margin: 0;
}

.tips-text a {
  color: var(--sky);
  text-decoration: none;
  font-weight: 500;
}

.tips-text a:hover {
  text-decoration: underline;
}

@media (max-width: 480px) {
  .onboarding-container {
    margin: 1.5rem auto;
  }

  .hero-title {
    font-size: 1.5rem;
  }

  .step-body {
    gap: 0.75rem;
  }

  .step-header {
    gap: 0.35rem;
  }

  .step-connector {
    left: 16px;
  }
}
</style>
@endpush
@endsection