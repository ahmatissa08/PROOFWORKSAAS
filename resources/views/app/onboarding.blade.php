@extends('layouts.app')
@section('title', 'Welcome to ProofWork')

@section('content')
<div style="max-width:600px;margin:2rem auto">
  <div style="text-align:center;margin-bottom:3rem">
    <div style="font-size:2.5rem;margin-bottom:1rem">👋</div>
    <h1 class="page-title">Welcome to ProofWork!</h1>
    <p style="color:var(--ink2);font-size:.95rem;line-height:1.65;margin-top:.5rem">
      You're on a 14-day free trial. Let's get you set up in 3 steps.
    </p>
  </div>

  <div style="display:flex;flex-direction:column;gap:1rem">

    @php $steps = [
      ['number' => '01', 'title' => 'Create your first project', 'desc' => 'A project connects a client with your tools and generates reports automatically.', 'action_label' => 'Create project →', 'action_url' => route('projects.create'), 'done' => auth()->user()->projects()->exists()],
      ['number' => '02', 'title' => 'Connect your tools', 'desc' => 'Connect GitHub, Linear, or Calendar so ProofWork can collect your activity.', 'action_label' => 'Connect tools →', 'action_url' => route('integrations.index'), 'done' => auth()->user()->integrations()->exists()],
      ['number' => '03', 'title' => 'Generate your first report', 'desc' => 'ProofWork pulls your activity and builds a client-ready report in seconds.', 'action_label' => 'Go to projects →', 'action_url' => route('projects.index'), 'done' => auth()->user()->reports()->exists()],
    ]; @endphp

    @foreach($steps as $step)
    <div class="card" style="{{ $step['done'] ? 'opacity:.6' : '' }}">
      <div style="padding:1.4rem;display:flex;align-items:flex-start;gap:1.2rem">
        <div style="width:36px;height:36px;border-radius:50%;background:{{ $step['done'] ? 'var(--green)' : 'var(--amber)' }};color:#000;display:flex;align-items:center;justify-content:center;font-family:var(--mono);font-size:.72rem;font-weight:600;flex-shrink:0">
          {{ $step['done'] ? '✓' : $step['number'] }}
        </div>
        <div style="flex:1">
          <div style="font-size:.9rem;font-weight:600;margin-bottom:.3rem {{ $step['done'] ? ';text-decoration:line-through;color:var(--ink3)' : '' }}">
            {{ $step['title'] }}
          </div>
          <div style="font-size:.82rem;color:var(--ink3);line-height:1.55;margin-bottom:{{ $step['done'] ? '0' : '.9rem' }}">
            {{ $step['desc'] }}
          </div>
          @if(!$step['done'])
          <a href="{{ $step['action_url'] }}" class="btn btn-primary btn-sm">{{ $step['action_label'] }}</a>
          @endif
        </div>
      </div>
    </div>
    @endforeach

  </div>

  <div style="text-align:center;margin-top:2rem">
    <a href="{{ route('dashboard') }}" style="font-family:var(--mono);font-size:.68rem;color:var(--ink3);text-decoration:none">
      Skip for now → Go to dashboard
    </a>
  </div>
</div>
@endsection
