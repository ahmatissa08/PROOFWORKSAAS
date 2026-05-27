<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Terms of Service — ProofWork</title>
  <meta name="description" content="ProofWork terms of service. Simple, clear, fair.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital,wght@0,400;1,400&family=Geist+Mono:wght@300;400;500&family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root{--bg:#0c0c0e;--surface:#111113;--border:#242428;--border2:#2e2e35;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--amber2:#f5b43a;--green:#27c93f;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{-webkit-font-smoothing:antialiased;scroll-behavior:smooth}
    body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.7}
    ::-webkit-scrollbar{width:3px}::-webkit-scrollbar-thumb{background:var(--border2)}
    a{text-decoration:none}
    nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:.9rem 2rem;background:rgba(12,12,14,.9);border-bottom:1px solid var(--border);backdrop-filter:blur(20px)}
    .nav-brand{display:flex;align-items:center;gap:.5rem;color:var(--ink);font-weight:600;font-size:.9rem;letter-spacing:-.02em}
    .nav-mark{width:26px;height:26px;border-radius:6px;background:linear-gradient(135deg,var(--amber),var(--amber2));display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-style:italic;font-size:.88rem;color:#0c0c0e}
    .nav-back{font-size:.78rem;color:var(--ink3);padding:.4rem .8rem;border-radius:5px;transition:all .18s}
    .nav-back:hover{color:var(--ink);background:rgba(255,255,255,.05)}
    .layout{max-width:1040px;margin:0 auto;padding:7.5rem 2rem 6rem;display:grid;grid-template-columns:220px 1fr;gap:4rem;align-items:start}
    .toc{position:sticky;top:5.5rem}
    .toc-label{font-family:var(--mono);font-size:.56rem;color:var(--ink3);letter-spacing:.14em;text-transform:uppercase;margin-bottom:.9rem}
    .toc-list{list-style:none;display:flex;flex-direction:column;gap:.2rem}
    .toc-list a{font-family:var(--mono);font-size:.65rem;color:var(--ink3);padding:.3rem .6rem;border-radius:4px;display:block;transition:all .18s;border-left:2px solid transparent}
    .toc-list a:hover,.toc-list a.active{color:var(--amber);background:rgba(232,163,37,.05);border-left-color:var(--amber)}
    .toc-date{font-family:var(--mono);font-size:.58rem;color:var(--ink3);margin-top:1.2rem;padding-top:1.2rem;border-top:1px solid var(--border);line-height:1.6}
    .eyebrow{font-family:var(--mono);font-size:.6rem;color:var(--ink3);letter-spacing:.15em;text-transform:uppercase;margin-bottom:.9rem}
    .doc-title{font-family:var(--serif);font-size:clamp(2.2rem,5vw,3.5rem);font-weight:400;font-style:italic;letter-spacing:-.03em;line-height:1.05;margin-bottom:.5rem}
    .doc-date{font-family:var(--mono);font-size:.65rem;color:var(--ink3);margin-bottom:2.5rem;padding-bottom:2rem;border-bottom:1px solid var(--border)}
    .tldr{background:rgba(232,163,37,.06);border:1px solid rgba(232,163,37,.18);border-radius:10px;padding:1.3rem 1.6rem;margin-bottom:3rem}
    .tldr-head{font-family:var(--mono);font-size:.6rem;color:var(--amber);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.7rem;opacity:.85}
    .tldr ul{list-style:none;display:flex;flex-direction:column;gap:.45rem}
    .tldr ul li{font-size:.84rem;color:var(--ink2);display:flex;gap:.55rem;align-items:flex-start;line-height:1.55}
    .tldr ul li::before{content:'✓';color:var(--green);font-family:var(--mono);font-size:.7rem;flex-shrink:0;margin-top:.1rem}
    .sec{margin-bottom:3rem;scroll-margin-top:7rem}
    .sec h2{font-family:var(--serif);font-size:1.35rem;font-style:italic;font-weight:400;color:var(--amber);margin-bottom:.9rem}
    .sec p{font-size:.88rem;color:var(--ink2);line-height:1.8;margin-bottom:.9rem}
    .sec p:last-child{margin-bottom:0}
    .sec ul{list-style:none;display:flex;flex-direction:column;gap:.45rem;margin-bottom:.9rem;padding-left:.3rem}
    .sec ul li{font-size:.86rem;color:var(--ink2);display:flex;gap:.6rem;align-items:flex-start;line-height:1.65}
    .sec ul li::before{content:'→';color:var(--amber);font-family:var(--mono);font-size:.68rem;flex-shrink:0;margin-top:.2rem;opacity:.7}
    .sec strong{color:var(--ink);font-weight:500}
    .sec a{color:var(--amber);text-decoration:underline;text-underline-offset:3px}
    .highlight{background:var(--surface);border:1px solid var(--border);border-left:3px solid var(--amber);border-radius:0 7px 7px 0;padding:1.1rem 1.4rem;margin:1.3rem 0}
    .highlight p{margin:0;font-size:.85rem}
    .plan-table{width:100%;border-collapse:collapse;border:1px solid var(--border);border-radius:8px;overflow:hidden;margin:1.2rem 0;font-size:.8rem}
    .plan-table thead th{background:var(--surface);padding:.65rem 1rem;text-align:left;font-family:var(--mono);font-size:.6rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;border-bottom:1px solid var(--border)}
    .plan-table tbody td{padding:.65rem 1rem;border-bottom:1px solid var(--border);color:var(--ink2)}
    .plan-table tbody tr:last-child td{border-bottom:none}
    .pb{font-family:var(--mono);font-size:.58rem;padding:.1rem .45rem;border-radius:3px;display:inline-block}
    .pb-free{background:rgba(90,88,85,.15);color:var(--ink3)}
    .pb-pro{background:rgba(232,163,37,.12);color:var(--amber)}
    .pb-agency{background:rgba(74,158,255,.1);color:#4a9eff}
    footer{border-top:1px solid var(--border);padding:1.8rem 2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.8rem}
    footer p{font-family:var(--mono);font-size:.58rem;color:var(--ink3)}
    .foot-links{display:flex;gap:1.5rem}
    .foot-links a{font-family:var(--mono);font-size:.58rem;color:var(--ink3);transition:color .18s}
    .foot-links a:hover{color:var(--amber)}
    @media(max-width:900px){.layout{grid-template-columns:1fr}.toc{display:none}}
    @media(max-width:600px){.layout{padding:7rem 1.2rem 4rem}nav{padding:.8rem 1.2rem}}
  </style>
</head>
<body>
<nav>
  <a href="{{ route('home') }}" class="nav-brand"><div class="nav-mark">P</div>ProofWork</a>
  <a href="{{ route('home') }}" class="nav-back">← Home</a>
</nav>
<div class="layout">
  <aside class="toc">
    <div class="toc-label">Contents</div>
    <ul class="toc-list">
      <li><a href="#acceptance">1. Acceptance</a></li>
      <li><a href="#service">2. The service</a></li>
      <li><a href="#account">3. Your account</a></li>
      <li><a href="#plans">4. Plans & billing</a></li>
      <li><a href="#usage">5. Acceptable use</a></li>
      <li><a href="#integrations">6. Integrations</a></li>
      <li><a href="#ip">7. Intellectual property</a></li>
      <li><a href="#data">8. Your data</a></li>
      <li><a href="#warranties">9. Warranties</a></li>
      <li><a href="#liability">10. Liability</a></li>
      <li><a href="#termination">11. Termination</a></li>
      <li><a href="#changes">12. Changes</a></li>
      <li><a href="#contact">13. Contact</a></li>
    </ul>
    <div class="toc-date">Last updated<br>{{ date('F d, Y') }}</div>
  </aside>
  <main>
    <div class="eyebrow">Legal</div>
    <h1 class="doc-title">Terms of Service</h1>
    <div class="doc-date">Effective: {{ date('F d, Y') }} · Version 1.0</div>
    <div class="tldr">
      <div class="tldr-head">⚡ TL;DR</div>
      <ul>
        <li>Use ProofWork fairly and don't abuse the service</li>
        <li>Free plan available — paid plans include a 14-day free trial</li>
        <li>Your data is yours — we never sell it or claim rights to it</li>
        <li>Cancel anytime — no lock-in, no cancellation fees</li>
        <li>We do our best but can't guarantee 100% uptime</li>
      </ul>
    </div>
    <div class="sec" id="acceptance"><h2>1. Acceptance of terms</h2><p>By creating an account or using ProofWork, you agree to these Terms. If you don't agree, don't use the service. These terms form a binding agreement between you and ProofWork.</p></div>
    <div class="sec" id="service"><h2>2. The service</h2><p>ProofWork is a SaaS platform that connects to your development tools (GitHub, Linear, Notion, Google Calendar) via read-only OAuth and automatically generates client-ready proof of work reports on a schedule.</p><p>We may modify, improve, or deprecate features with reasonable notice. We'll notify you by email of significant changes.</p></div>
    <div class="sec" id="account"><h2>3. Your account</h2><ul><li>You must be at least 16 to create an account</li><li>You are responsible for maintaining the security of your credentials</li><li>One person, one account — teams should use the Agency plan</li><li>You are responsible for all activity under your account</li><li>Notify us immediately of any unauthorized access</li></ul></div>
    <div class="sec" id="plans">
      <h2>4. Plans & billing</h2>
      <table class="plan-table">
        <thead><tr><th>Plan</th><th>Price</th><th>Projects</th><th>Integrations</th><th>AI summaries</th></tr></thead>
        <tbody>
          <tr><td><span class="pb pb-free">Free</span></td><td>$0 forever</td><td>1</td><td>2</td><td>—</td></tr>
          <tr><td><span class="pb pb-pro">Pro</span></td><td>$19/month</td><td>Unlimited</td><td>All 6</td><td>✓</td></tr>
          <tr><td><span class="pb pb-agency">Agency</span></td><td>$49/month</td><td>Unlimited</td><td>All 6</td><td>✓</td></tr>
        </tbody>
      </table>
      <ul>
        <li>Free plan is free forever — no credit card required</li>
        <li>Pro and Agency include a <strong>14-day free trial</strong> — no card needed to start</li>
        <li>Subscriptions renew monthly unless cancelled</li>
        <li>Cancel anytime from billing settings — no fees</li>
        <li>Prices may change with 30 days' notice to active subscribers</li>
      </ul>
      <div class="highlight"><p>Payments are processed by <strong>Stripe</strong>. We never store or see your card details.</p></div>
    </div>
    <div class="sec" id="usage"><h2>5. Acceptable use</h2><p>You agree not to:</p><ul><li>Use ProofWork to generate false or fabricated reports</li><li>Attempt to reverse-engineer, scrape, or attack our systems</li><li>Share credentials with others (use Agency plan for teams)</li><li>Use automated scripts to abuse the service</li><li>Violate any applicable laws or regulations</li></ul></div>
    <div class="sec" id="integrations"><h2>6. Integrations</h2><p>ProofWork uses <strong>read-only OAuth tokens</strong>. We request only the minimum scopes necessary and can never write, modify, or delete anything in your tools. Integration availability depends on third-party APIs — we're not responsible for their downtime.</p></div>
    <div class="sec" id="ip"><h2>7. Intellectual property</h2><p><strong>Your data is yours.</strong> We claim no ownership over your commits, tasks, meetings, or any content from your tools. The generated reports belong to you.</p><p>The ProofWork brand, software, and design are our intellectual property. You may not reproduce them without written permission.</p></div>
    <div class="sec" id="data"><h2>8. Your data</h2><p>We handle your data as described in our <a href="{{ route('privacy') }}">Privacy Policy</a>. We don't sell it, we don't share it with advertisers, and you can delete it at any time. Export everything as CSV from account settings. Deleted accounts are fully purged within 30 days.</p></div>
    <div class="sec" id="warranties"><h2>9. Warranties</h2><p>ProofWork is provided "as is". We make no warranties of merchantability, fitness, or uninterrupted availability. We aim for high uptime but cannot guarantee it.</p></div>
    <div class="sec" id="liability"><h2>10. Limitation of liability</h2><p>ProofWork's total liability for any claims shall not exceed the amount you paid in the 3 months preceding the claim. We're not liable for indirect, incidental, or consequential damages.</p></div>
    <div class="sec" id="termination"><h2>11. Termination</h2><p>You may cancel anytime from billing settings. We may terminate accounts that violate these terms. Upon termination, paid access ends immediately. Data is available for export for 30 days before permanent deletion.</p></div>
    <div class="sec" id="changes"><h2>12. Changes to terms</h2><p>We'll notify you by email at least 14 days before material changes take effect. Continued use after that date constitutes acceptance.</p></div>
    <div class="sec" id="contact"><h2>13. Contact</h2><p><strong>Email:</strong> <a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a><br><strong>Twitter:</strong> <a href="https://twitter.com/proofwork" target="_blank">@proofwork</a></p></div>
  </main>
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
