<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'ProofWork')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist+Mono:wght@400;500&family=Geist:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
  <style>
  :root{
    --bg:#0c0c0e;--surface:#131316;--surface2:#18181c;--border:#242428;--border2:#2e2e34;
    --ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--ink4:#3a3835;
    --amber:#e8a325;--coral:#e85c3a;--sky:#4a9eff;--green:#27c93f;
    --mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif;
  }
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  html{scroll-behavior:smooth}
  body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.7;min-height:100vh}
  ::-webkit-scrollbar{width:4px}
  ::-webkit-scrollbar-thumb{background:var(--border2);border-radius:2px}

  /* ── Navigation ── */
  .guest-nav{
    position:fixed;top:0;left:0;right:0;z-index:100;
    display:flex;align-items:center;justify-content:space-between;
    padding:1.1rem 2.5rem;
    border-bottom:1px solid var(--border);
    background:rgba(12,12,14,.9);backdrop-filter:blur(20px);
  }
  .guest-logo{
    font-family:var(--serif);font-size:1.25rem;font-style:italic;
    color:var(--ink);text-decoration:none;display:flex;align-items:center;gap:.5rem;
  }
  .guest-logo i{font-size:16px;color:var(--amber)}
  .guest-logo-word{font-family:var(--sans);font-style:normal;font-weight:300;font-size:1.2rem;letter-spacing:-.02em}
  .guest-nav-right{display:flex;gap:.6rem;align-items:center}
  .guest-nav-link{
    font-size:.8rem;color:var(--ink3);text-decoration:none;
    padding:.45rem .85rem;border-radius:4px;transition:color .2s,background .2s;
  }
  .guest-nav-link:hover{color:var(--ink);background:rgba(255,255,255,.04)}
  .guest-nav-cta{
    background:var(--amber);color:#000;font-weight:600;font-size:.78rem;
    padding:.5rem 1.1rem;border-radius:4px;text-decoration:none;transition:opacity .15s;
  }
  .guest-nav-cta:hover{opacity:.88}

  /* ── Content ── */
  .guest-content{min-height:calc(100vh - 140px);padding-top:80px}

  /* ── Footer ── */
  .guest-footer{
    border-top:1px solid var(--border);padding:2rem 2.5rem;
    display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;
  }
  .guest-footer-brand{
    font-family:var(--serif);font-size:1rem;font-style:italic;color:var(--ink3);
    display:flex;align-items:center;gap:.5rem;
  }
  .guest-footer-brand i{color:var(--amber);font-size:14px}
  .guest-footer-links{display:flex;gap:1.5rem}
  .guest-footer-link{font-size:.75rem;color:var(--ink3);text-decoration:none;transition:color .2s}
  .guest-footer-link:hover{color:var(--ink2)}
  .guest-footer-copy{font-family:var(--mono);font-size:.6rem;color:var(--ink4)}

  @media(max-width:600px){
    .guest-nav{padding:1rem 1.2rem}
    .guest-footer{flex-direction:column;text-align:center}
    .guest-nav-right .guest-nav-link{display:none}
  }
  </style>
  @stack('styles')
</head>
<body>

<!-- Navigation -->
<nav class="guest-nav">
  <a href="{{ route('home') }}" class="guest-logo">
    <i class="ti ti-checkup-list"></i> Proof<span class="guest-logo-word">Work</span>
  </a>
  <div class="guest-nav-right">
    <a href="{{ route('home') }}" class="guest-nav-link">Home</a>
    <a href="{{ route('about') }}" class="guest-nav-link">About</a>
    <a href="{{ route('contact') }}" class="guest-nav-link">Contact</a>
    <a href="{{ route('login') }}" class="guest-nav-link">Sign in</a>
    <a href="{{ route('register') }}" class="guest-nav-cta">Get started</a>
  </div>
</nav>

<!-- Content -->
<main class="guest-content">
  @yield('content')
</main>

<!-- Footer -->
<footer class="guest-footer">
  <div class="guest-footer-brand">
    <i class="ti ti-checkup-list"></i> ProofWork
  </div>
  <div class="guest-footer-links">
    <a href="{{ route('about') }}" class="guest-footer-link">About</a>
    <a href="{{ route('contact') }}" class="guest-footer-link">Contact</a>
    <a href="{{ route('privacy') }}" class="guest-footer-link">Privacy</a>
    <a href="{{ route('terms') }}" class="guest-footer-link">Terms</a>
  </div>
  <div class="guest-footer-copy">© {{ date('Y') }} ProofWork</div>
</footer>

@stack('scripts')
</body>
</html>