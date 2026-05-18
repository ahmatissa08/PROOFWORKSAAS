@extends('layouts.app')
@section('title', 'Welcome to Pro!')

@section('content')
<div style="max-width:520px;margin:4rem auto;text-align:center">
  <div style="font-size:3rem;margin-bottom:1.5rem">🎉</div>
  <h1 class="page-title" style="margin-bottom:.6rem">You're on Pro!</h1>
  <p style="color:var(--ink2);font-size:.95rem;line-height:1.7;margin-bottom:2.5rem">
    Your subscription is now active. All Pro features are unlocked — unlimited projects, all 6 integrations, AI summaries, and auto weekly reports.
  </p>
  <div style="display:flex;gap:.8rem;justify-content:center;flex-wrap:wrap">
    <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to dashboard →</a>
    <a href="{{ route('integrations.index') }}" class="btn btn-ghost">Connect your tools</a>
  </div>
</div>
@endsection
