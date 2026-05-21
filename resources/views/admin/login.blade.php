<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login — ProofWork</title>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@1&family=Geist+Mono:wght@400&family=Geist:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
  :root{--bg:#0c0c0e;--surface:#131316;--border:#242428;--border2:#2e2e34;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--coral:#e85c3a;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
  *{box-sizing:border-box;margin:0;padding:0}
  body{background:var(--bg);color:var(--ink);font-family:var(--sans);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem}
  .card{background:var(--surface);border:1px solid var(--border);border-radius:12px;width:100%;max-width:360px;overflow:hidden}
  .card-top{height:3px;background:linear-gradient(90deg,var(--coral),var(--amber))}
  .card-body{padding:2.2rem}
  .logo{font-family:var(--serif);font-size:1.3rem;font-style:italic;color:var(--ink);margin-bottom:.3rem}
  .badge{font-family:var(--mono);font-size:.6rem;color:var(--coral);background:rgba(232,92,58,.1);border:1px solid rgba(232,92,58,.2);padding:.15rem .5rem;border-radius:3px;letter-spacing:.08em;text-transform:uppercase;margin-left:.5rem}
  .sub{font-size:.82rem;color:var(--ink3);margin-bottom:1.8rem}
  label{display:block;font-family:var(--mono);font-size:.58rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.45rem}
  input[type=password]{width:100%;background:var(--bg);border:1px solid var(--border2);color:var(--ink);padding:.75rem .9rem;font-family:var(--mono);font-size:.85rem;border-radius:5px;outline:none;transition:border-color .2s;margin-bottom:1rem}
  input[type=password]:focus{border-color:var(--amber)}
  button{width:100%;background:var(--amber);color:#000;border:none;padding:.82rem;font-family:var(--sans);font-size:.85rem;font-weight:700;border-radius:5px;cursor:pointer;transition:opacity .15s}
  button:hover{opacity:.88}
  </style>
</head>
<body>
<div class="card">
  <div class="card-top"></div>
  <div class="card-body">
    <div class="logo">ProofWork <span class="badge">Admin</span></div>
    <p class="sub">Restricted access. Enter the admin password.</p>
    <form method="POST" action="{{ route('admin.authenticate') }}">
      @csrf
      <label for="admin_password">Password</label>
      <input type="password" name="admin_password" id="admin_password" autofocus autocomplete="current-password">
      <button type="submit">Enter →</button>
    </form>
  </div>
</div>
</body>
</html>
