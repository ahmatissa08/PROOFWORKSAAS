@extends('layouts.auth')
@section('title', 'Reset password')

@section('content')
<h1 class="auth-title">Forgot password.</h1>
<p class="auth-sub">Enter your email and we'll send you a reset link.</p>

@if(session('status'))
<div class="alert-success">{{ session('status') }}</div>
@endif

@if($errors->any())
<div class="alert-error">{{ $errors->first() }}</div>
@endif

<form action="{{ route('password.email') }}" method="POST">
  @csrf
  <div class="form-group">
    <label class="form-label" for="email">Email address</label>
    <input id="email" name="email" type="email" class="form-input"
      value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
  </div>
  <button type="submit" class="btn-primary">Send reset link →</button>
</form>

<div class="auth-footer">
  <a href="{{ route('login') }}">← Back to login</a>
</div>
@endsection
