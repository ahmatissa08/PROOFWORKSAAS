@extends('layouts.auth')
@section('title', 'Verify your email')

@section('content')
<div style="text-align:center;margin-bottom:1.8rem">
  <div style="font-size:2.5rem;margin-bottom:1rem">Inbox</div>
  <h1 class="auth-title">Check your inbox.</h1>
  <p class="auth-sub">
    We sent a verification link to
    <strong style="color:var(--ink)">{{ auth()->user()->email }}</strong>.<br>
    Click the link to activate your account.
  </p>
</div>

@if(session('status') === 'verification-link-sent')
<div class="alert-success" style="margin-bottom:1.2rem">
  A new verification link has been sent to your email.
</div>
@endif

@if(session('warning'))
<div class="alert-error" style="margin-bottom:1.2rem">
  {{ session('warning') }}
</div>
@endif

<form action="{{ route('verification.send') }}" method="POST" style="margin-bottom:1rem">
  @csrf
  <button type="submit" class="btn-primary">Resend verification email</button>
</form>

<div style="text-align:center;margin-top:1rem">
  <form action="{{ route('logout') }}" method="POST" style="display:inline">
    @csrf
    <button type="submit" style="background:transparent;border:none;color:var(--ink3);font-size:.82rem;cursor:pointer;font-family:var(--sans);text-decoration:underline">
      Log out and use a different account
    </button>
  </form>
</div>

<div style="margin-top:2rem;padding:1rem;background:var(--bg);border:1px solid var(--border);border-radius:6px">
  <p style="font-family:var(--mono);font-size:.62rem;color:var(--ink3);text-align:center;line-height:1.6">
    Didn't receive the email? Check your spam folder.<br>
    Still nothing? Contact support from the sender address configured in your environment.
  </p>
</div>
@endsection
