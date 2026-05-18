@extends('layouts.auth')
@section('title', 'Set new password')

@section('content')
<h1 class="auth-title">New password.</h1>
<p class="auth-sub">Choose a strong password for your account.</p>

@if($errors->any())
<div class="alert-error">{{ $errors->first() }}</div>
@endif

<form action="{{ route('password.update') }}" method="POST">
  @csrf
  <input type="hidden" name="token" value="{{ $token }}">
  <div class="form-group">
    <label class="form-label" for="email">Email address</label>
    <input id="email" name="email" type="email" class="form-input"
      value="{{ old('email', $email) }}" required autofocus>
  </div>
  <div class="form-group">
    <label class="form-label" for="password">New password</label>
    <input id="password" name="password" type="password" class="form-input"
      placeholder="8+ characters" required>
    @error('password')<span class="form-error">{{ $message }}</span>@enderror
  </div>
  <div class="form-group">
    <label class="form-label" for="password_confirmation">Confirm password</label>
    <input id="password_confirmation" name="password_confirmation" type="password"
      class="form-input" placeholder="Repeat password" required>
  </div>
  <button type="submit" class="btn-primary">Reset password →</button>
</form>
@endsection
