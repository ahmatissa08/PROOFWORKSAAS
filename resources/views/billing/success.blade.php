@extends('layouts.app')

@section('title', 'Welcome to Pro!')

@section('content')
<div class="success-container">
  <div class="success-illustration">
    <div class="success-icon">🎉</div>
    <div class="success-confetti">
      <span></span><span></span><span></span><span></span><span></span>
      <span></span><span></span><span></span><span></span><span></span>
    </div>
  </div>

  <h1 class="success-title">You're on Pro!</h1>

  <p class="success-message">
    Your subscription is now active. All Pro features are unlocked — unlimited projects, 
    all 6 integrations, AI summaries, and auto weekly reports.
  </p>

  <div class="success-features">
    <div class="feature-item">
      <div class="feature-check">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <span>Unlimited projects</span>
    </div>
    <div class="feature-item">
      <div class="feature-check">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <span>All integrations</span>
    </div>
    <div class="feature-item">
      <div class="feature-check">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <span>AI summaries</span>
    </div>
    <div class="feature-item">
      <div class="feature-check">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <span>Auto weekly reports</span>
    </div>
  </div>

  <div class="success-actions">
    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
      Go to dashboard
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </a>
    <a href="{{ route('integrations.index') }}" class="btn btn-ghost btn-lg">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
      Connect your tools
    </a>
  </div>

  <div class="success-footer">
    Need help? <a href="mailto:support@proofwork.io">Contact support</a> — we're here for you.
  </div>
</div>

@push('styles')
<style>
.success-container {
  max-width: 520px;
  margin: 3rem auto;
  text-align: center;
  padding: 0 1rem;
}

.success-illustration {
  position: relative;
  display: inline-block;
  margin-bottom: 1.5rem;
}

.success-icon {
  font-size: 4rem;
  line-height: 1;
  position: relative;
  z-index: 2;
  animation: iconPop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

@keyframes iconPop {
  0% { transform: scale(0) rotate(-20deg); opacity: 0; }
  100% { transform: scale(1) rotate(0deg); opacity: 1; }
}

.success-confetti {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  z-index: 1;
}

.success-confetti span {
  position: absolute;
  width: 6px;
  height: 6px;
  border-radius: 50%;
  opacity: 0;
  animation: confetti 1s ease-out forwards;
}

.success-confetti span:nth-child(1) { background: var(--amber); animation-delay: 0.1s; transform: rotate(0deg) translateY(-40px); }
.success-confetti span:nth-child(2) { background: var(--green); animation-delay: 0.15s; transform: rotate(36deg) translateY(-50px); }
.success-confetti span:nth-child(3) { background: var(--sky); animation-delay: 0.2s; transform: rotate(72deg) translateY(-35px); }
.success-confetti span:nth-child(4) { background: var(--purple); animation-delay: 0.25s; transform: rotate(108deg) translateY(-45px); }
.success-confetti span:nth-child(5) { background: var(--amber); animation-delay: 0.3s; transform: rotate(144deg) translateY(-40px); }
.success-confetti span:nth-child(6) { background: var(--green); animation-delay: 0.35s; transform: rotate(180deg) translateY(-50px); }
.success-confetti span:nth-child(7) { background: var(--sky); animation-delay: 0.4s; transform: rotate(216deg) translateY(-35px); }
.success-confetti span:nth-child(8) { background: var(--purple); animation-delay: 0.45s; transform: rotate(252deg) translateY(-45px); }
.success-confetti span:nth-child(9) { background: var(--amber); animation-delay: 0.5s; transform: rotate(288deg) translateY(-40px); }
.success-confetti span:nth-child(10) { background: var(--green); animation-delay: 0.55s; transform: rotate(324deg) translateY(-50px); }

@keyframes confetti {
  0% { opacity: 1; transform: scale(1); }
  100% { opacity: 0; transform: scale(0) translateY(20px); }
}

.success-title {
  font-size: 2rem;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 0.8rem;
  animation: fadeUp 0.5s ease-out 0.2s both;
}

.success-message {
  color: var(--ink2);
  font-size: 1rem;
  line-height: 1.7;
  margin-bottom: 2rem;
  animation: fadeUp 0.5s ease-out 0.35s both;
}

.success-features {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0.75rem;
  margin-bottom: 2.5rem;
  text-align: left;
  max-width: 380px;
  margin-left: auto;
  margin-right: auto;
  animation: fadeUp 0.5s ease-out 0.5s both;
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  font-size: 0.88rem;
  color: var(--ink2);
}

.feature-check {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  background: rgba(39, 201, 63, 0.15);
  color: var(--green);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.success-actions {
  display: flex;
  gap: 0.8rem;
  justify-content: center;
  flex-wrap: wrap;
  margin-bottom: 2rem;
  animation: fadeUp 0.5s ease-out 0.65s both;
}

.success-actions .btn {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
}

.success-actions .btn-lg {
  padding: 0.75rem 1.5rem;
  font-size: 0.95rem;
}

.success-footer {
  font-size: 0.82rem;
  color: var(--ink3);
  animation: fadeUp 0.5s ease-out 0.8s both;
}

.success-footer a {
  color: var(--sky);
  text-decoration: none;
  font-weight: 500;
  transition: opacity 0.2s;
}

.success-footer a:hover {
  opacity: 0.8;
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(12px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 480px) {
  .success-features {
    grid-template-columns: 1fr;
    max-width: 220px;
  }

  .success-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .success-actions .btn {
    justify-content: center;
  }

  .success-title {
    font-size: 1.6rem;
  }
}
</style>
@endpush
@endsection