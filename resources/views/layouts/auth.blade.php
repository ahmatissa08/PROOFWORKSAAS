<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title') - ProofWork</title>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist+Mono:wght@400&family=Geist:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
  :root{--bg:#0c0c0e;--surface:#131316;--border:#242428;--border2:#2e2e34;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--coral:#e85c3a;--green:#27c93f;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{background:var(--bg);color:var(--ink);font-family:var(--sans);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem;position:relative;overflow:hidden}
  body::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 50% at 30% 40%,rgba(232,163,37,.05) 0%,transparent 70%);pointer-events:none}
  .auth-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;width:100%;max-width:420px;overflow:hidden;position:relative}
  .auth-card-top{height:3px;background:linear-gradient(90deg,#e8a325,#4a9eff)}
  .auth-card-body{padding:2.5rem}
  .auth-logo{font-family:var(--serif);font-size:1.3rem;font-style:italic;color:var(--ink);text-decoration:none;display:block;margin-bottom:2rem}
  .auth-logo span{font-family:var(--sans);font-style:normal;font-weight:300;font-size:1.2rem}
  .auth-title{font-family:var(--serif);font-size:1.6rem;font-style:italic;font-weight:400;margin-bottom:.3rem}
  .auth-sub{color:var(--ink3);font-size:.84rem;margin-bottom:2rem}
  .form-group{margin-bottom:1.2rem}
  .form-label{display:block;font-family:var(--mono);font-size:.6rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.5rem}
  .form-input{width:100%;background:rgba(12,12,14,.8);border:1px solid var(--border2);color:var(--ink);padding:.75rem 1rem;font-family:var(--sans);font-size:.88rem;border-radius:5px;outline:none;transition:border-color .2s,box-shadow .2s}
  .form-input:focus{border-color:var(--amber);box-shadow:0 0 0 3px rgba(232,163,37,.08)}
  .form-input::placeholder{color:var(--ink3)}
  .form-error{font-family:var(--mono);font-size:.62rem;color:var(--coral);margin-top:.35rem;display:block}
  .btn-primary{width:100%;background:var(--amber);color:#000;border:none;padding:.85rem;font-family:var(--sans);font-size:.88rem;font-weight:700;border-radius:5px;cursor:pointer;transition:opacity .15s;letter-spacing:.02em;margin-top:.5rem}
  .btn-primary:hover{opacity:.88}
  .divider{display:flex;align-items:center;gap:.8rem;margin:1.5rem 0;font-family:var(--mono);font-size:.62rem;color:var(--ink3)}
  .divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border)}
  .social-btn{width:100%;background:var(--bg);border:1px solid var(--border2);color:var(--ink2);padding:.72rem;font-family:var(--sans);font-size:.84rem;font-weight:500;border-radius:5px;cursor:pointer;transition:all .15s;display:flex;align-items:center;justify-content:center;gap:.6rem;text-decoration:none;margin-bottom:.6rem}
  .social-btn:hover{border-color:var(--ink3);color:var(--ink)}
  .auth-footer{text-align:center;margin-top:1.5rem;font-size:.82rem;color:var(--ink3)}
  .auth-footer a{color:var(--amber);text-decoration:none}
  .auth-footer a:hover{text-decoration:underline}
  .alert-success{background:rgba(39,201,63,.08);border:1px solid rgba(39,201,63,.2);color:var(--green);padding:.75rem 1rem;border-radius:5px;font-size:.82rem;margin-bottom:1.2rem}
  .alert-error{background:rgba(232,92,58,.08);border:1px solid rgba(232,92,58,.2);color:var(--coral);padding:.75rem 1rem;border-radius:5px;font-size:.82rem;margin-bottom:1.2rem}
  </style>
</head>
<body>
<div class="auth-card">
  <div class="auth-card-top"></div>
  <div class="auth-card-body">
    <a href="/" class="auth-logo">Proof<span>Work</span></a>
    @yield('content')
  </div>
</div>
</body>
</html>
