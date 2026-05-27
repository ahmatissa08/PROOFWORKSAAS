<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Security — ProofWork</title>
  <meta name="description" content="How ProofWork protects your data and your tools.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital,wght@0,400;1,400&family=Geist+Mono:wght@300;400;500&family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root{--bg:#0c0c0e;--surface:#111113;--border:#242428;--border2:#2e2e35;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--amber2:#f5b43a;--green:#27c93f;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{-webkit-font-smoothing:antialiased}
    body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.7}
    ::-webkit-scrollbar{width:3px}::-webkit-scrollbar-thumb{background:var(--border2)}
    a{text-decoration:none}
    nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:.9rem 2rem;background:rgba(12,12,14,.9);border-bottom:1px solid var(--border);backdrop-filter:blur(20px)}
    .nav-brand{display:flex;align-items:center;gap:.5rem;color:var(--ink);font-weight:600;font-size:.9rem;letter-spacing:-.02em}
    .nav-mark{width:26px;height:26px;border-radius:6px;background:linear-gradient(135deg,var(--amber),var(--amber2));display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-style:italic;font-size:.88rem;color:#0c0c0e}
    .nav-back{font-size:.78rem;color:var(--ink3);padding:.4rem .8rem;border-radius:5px;transition:all .18s}
    .nav-back:hover{color:var(--ink);background:rgba(255,255,255,.05)}
    .page{max-width:760px;margin:0 auto;padding:7.5rem 2rem 6rem}
    .eyebrow{font-family:var(--mono);font-size:.6rem;color:var(--ink3);letter-spacing:.15em;text-transform:uppercase;margin-bottom:.9rem}
    .page-title{font-family:var(--serif);font-size:clamp(2.5rem,6vw,3.8rem);font-weight:400;font-style:italic;letter-spacing:-.03em;margin-bottom:.7rem}
    .page-title em{color:var(--amber)}
    .page-sub{font-size:.9rem;color:var(--ink2);max-width:50ch;line-height:1.7;margin-bottom:3rem}
    .sec-grid{display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:3rem}
    .sec-item{background:var(--surface);padding:1.5rem;transition:background .18s}
    .sec-item:hover{background:var(--border2)}
    .sec-icon{font-size:1.3rem;margin-bottom:.6rem}
    .sec-title{font-size:.88rem;font-weight:600;margin-bottom:.35rem;letter-spacing:-.01em}
    .sec-desc{font-size:.78rem;color:var(--ink3);line-height:1.55}
    h2{font-family:var(--serif);font-size:1.3rem;font-style:italic;font-weight:400;color:var(--amber);margin-bottom:.8rem;margin-top:2.5rem}
    p{font-size:.88rem;color:var(--ink2);line-height:1.8;margin-bottom:.9rem}
    p a{color:var(--amber);text-decoration:underline;text-underline-offset:3px}
    p strong{color:var(--ink);font-weight:500}
    .scopes-list{list-style:none;display:flex;flex-direction:column;gap:.5rem;margin-bottom:1rem}
    .scopes-list li{font-size:.84rem;color:var(--ink2);display:flex;gap:.6rem;align-items:flex-start}
    .scopes-list li::before{content:'→';color:var(--amber);font-family:var(--mono);font-size:.7rem;flex-shrink:0;margin-top:.18rem;opacity:.7}
    .report-card{background:rgba(39,201,63,.05);border:1px solid rgba(39,201,63,.18);border-radius:9px;padding:1.3rem 1.6rem;margin-top:2.5rem}
    .report-head{font-family:var(--mono);font-size:.62rem;color:var(--green);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.6rem;opacity:.85}
    .report-text{font-size:.86rem;color:var(--ink2);line-height:1.65}
    .report-text a{color:var(--amber)}
    footer{border-top:1px solid var(--border);padding:1.8rem 2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.8rem}
    footer p{font-family:var(--mono);font-size:.58rem;color:var(--ink3)}
    .foot-links{display:flex;gap:1.5rem}
    .foot-links a{font-family:var(--mono);font-size:.58rem;color:var(--ink3);transition:color .18s}
    .foot-links a:hover{color:var(--amber)}
    @media(max-width:600px){.sec-grid{grid-template-columns:1fr}.page{padding:7rem 1.2rem 4rem}nav{padding:.8rem 1.2rem}}
  </style>
</head>
<body>
<nav>
  <a href="{{ route('home') }}" class="nav-brand"><div class="nav-mark">P</div>ProofWork</a>
  <a href="{{ route('home') }}" class="nav-back">← Home</a>
</nav>
<div class="page">
  <div class="eyebrow">Security</div>
  <h1 class="page-title">Your data is<br><em>safe with us.</em></h1>
  <p class="page-sub">Security is a core part of how ProofWork is built — not an afterthought. Here's exactly what we do.</p>

  <div class="sec-grid">
    @foreach([
      ['🔒','Read-only access','We request only read-only OAuth scopes. We cannot push code, close issues, or write anything to your tools. Ever.'],
      ['🔐','Encrypted tokens','All OAuth access tokens are encrypted at rest using AES-256. Never stored in plaintext.'],
      ['🚦','HTTPS everywhere','All connections use TLS 1.2+. No unencrypted connections between your browser, our servers, and third-party APIs.'],
      ['🗑️','Data minimization','We collect only what\'s necessary to generate reports. No email access, no file browsing, no extras.'],
      ['👤','No data selling','We never sell, rent, or share your data with advertisers or data brokers. Period.'],
      ['⚡','Secure auth','Passwords are hashed with bcrypt. Sessions are encrypted and rotated on login. OAuth removes password risk entirely.'],
    ] as [$icon,$title,$desc])
    <div class="sec-item">
      <div class="sec-icon">{{ $icon }}</div>
      <div class="sec-title">{{ $title }}</div>
      <div class="sec-desc">{{ $desc }}</div>
    </div>
    @endforeach
  </div>

  <h2>OAuth scopes we request</h2>
  <p>We request the absolute minimum permissions needed for each integration:</p>
  <ul class="scopes-list">
    <li><strong>GitHub:</strong> <code style="font-family:var(--mono);font-size:.78rem;color:var(--ink3)">repo:read, read:user</code> — read commits, PRs, and your profile. Cannot push or create anything.</li>
    <li><strong>Google Calendar:</strong> <code style="font-family:var(--mono);font-size:.78rem;color:var(--ink3)">calendar.readonly</code> — read calendar events. Cannot create or modify events.</li>
    <li><strong>Linear:</strong> <code style="font-family:var(--mono);font-size:.78rem;color:var(--ink3)">read</code> — read issues and teams. Cannot create or close anything.</li>
    <li><strong>Notion:</strong> <code style="font-family:var(--mono);font-size:.78rem;color:var(--ink3)">read_content</code> — read pages. Cannot create, edit, or delete pages.</li>
  </ul>

  <h2>Infrastructure</h2>
  <p>ProofWork runs on Railway cloud infrastructure. Databases use encrypted connections and are not publicly accessible. Daily backups are performed automatically. All application code runs behind HTTPS with proper security headers.</p>

  <h2>Responsible disclosure</h2>
  <p>If you discover a security vulnerability, please report it responsibly by emailing <a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a> before any public disclosure. We'll respond within 48 hours and work to resolve confirmed issues promptly.</p>

  <div class="report-card">
    <div class="report-head">🛡 Report a vulnerability</div>
    <div class="report-text">
      Found something? Email <a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a> with details.
      We take all reports seriously and respond within 48 hours.
    </div>
  </div>
</div>
<footer>
  <p>© {{ date('Y') }} ProofWork</p>
  <div class="foot-links">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('privacy') }}">Privacy</a>
    <a href="{{ route('contact') }}">Contact</a>
  </div>
</footer>
</body>
</html>
