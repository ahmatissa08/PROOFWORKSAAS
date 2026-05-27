<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Privacy Policy — ProofWork</title>
  <meta name="description" content="ProofWork privacy policy. We don't sell your data. Ever.">
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
    .sec h2{font-family:var(--serif);font-size:1.35rem;font-style:italic;font-weight:400;color:var(--amber);margin-bottom:.9rem;letter-spacing:-.01em}
    .sec p{font-size:.88rem;color:var(--ink2);line-height:1.8;margin-bottom:.9rem}
    .sec p:last-child{margin-bottom:0}
    .sec ul{list-style:none;display:flex;flex-direction:column;gap:.45rem;margin-bottom:.9rem;padding-left:.3rem}
    .sec ul li{font-size:.86rem;color:var(--ink2);display:flex;gap:.6rem;align-items:flex-start;line-height:1.65}
    .sec ul li::before{content:'→';color:var(--amber);font-family:var(--mono);font-size:.68rem;flex-shrink:0;margin-top:.2rem;opacity:.7}
    .sec strong{color:var(--ink);font-weight:500}
    .sec a{color:var(--amber);text-decoration:underline;text-underline-offset:3px}
    .highlight{background:var(--surface);border:1px solid var(--border);border-left:3px solid var(--amber);border-radius:0 7px 7px 0;padding:1.1rem 1.4rem;margin:1.3rem 0}
    .highlight p{margin:0;font-size:.85rem}
    .rights-grid{display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin:1.2rem 0}
    .right{background:var(--surface);padding:1.1rem 1.2rem}
    .right-title{font-size:.8rem;font-weight:600;margin-bottom:.25rem;letter-spacing:-.01em}
    .right-desc{font-size:.75rem;color:var(--ink3);line-height:1.5}
    footer{border-top:1px solid var(--border);padding:1.8rem 2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.8rem}
    footer p{font-family:var(--mono);font-size:.58rem;color:var(--ink3)}
    .foot-links{display:flex;gap:1.5rem}
    .foot-links a{font-family:var(--mono);font-size:.58rem;color:var(--ink3);transition:color .18s}
    .foot-links a:hover{color:var(--amber)}
    @media(max-width:900px){.layout{grid-template-columns:1fr}.toc{display:none}}
    @media(max-width:600px){.layout{padding:7rem 1.2rem 4rem}.rights-grid{grid-template-columns:1fr}nav{padding:.8rem 1.2rem}}
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
    <ul class="toc-list" id="toc">
      <li><a href="#who">1. Who we are</a></li>
      <li><a href="#collect">2. Data we collect</a></li>
      <li><a href="#use">3. How we use it</a></li>
      <li><a href="#storage">4. Storage & security</a></li>
      <li><a href="#third">5. Third parties</a></li>
      <li><a href="#rights">6. Your rights</a></li>
      <li><a href="#cookies">7. Cookies</a></li>
      <li><a href="#children">8. Children</a></li>
      <li><a href="#changes">9. Changes</a></li>
      <li><a href="#contact">10. Contact</a></li>
    </ul>
    <div class="toc-date">Last updated<br>{{ date('F d, Y') }}</div>
  </aside>
  <main>
    <div class="eyebrow">Legal</div>
    <h1 class="doc-title">Privacy Policy</h1>
    <div class="doc-date">Effective: {{ date('F d, Y') }} · Version 1.0</div>
    <div class="tldr">
      <div class="tldr-head">⚡ TL;DR</div>
      <ul>
        <li>We collect only what's needed to run the service</li>
        <li>We never sell your data to anyone, ever</li>
        <li>Integration access is read-only — we can never write to your tools</li>
        <li>You can delete your account and all data at any time</li>
        <li>No advertising trackers or third-party analytics pixels</li>
      </ul>
    </div>
    <div class="sec" id="who"><h2>1. Who we are</h2><p>ProofWork is a SaaS product built by a solo founder. "ProofWork", "we", "us", "our" refers to the person operating this service. Contact: <a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a></p></div>
    <div class="sec" id="collect">
      <h2>2. What data we collect</h2>
      <ul>
        <li><strong>Account data</strong> — name, email address, password (bcrypt-hashed, never plaintext)</li>
        <li><strong>Integration tokens</strong> — OAuth access tokens for GitHub, Google, Linear, Notion. Encrypted at rest. Read-only</li>
        <li><strong>Activity data</strong> — commits, tasks, meetings pulled from your tools to generate reports</li>
        <li><strong>Usage data</strong> — pages visited and features used for product improvement only</li>
        <li><strong>Payment data</strong> — handled entirely by Stripe. We never see or store card numbers</li>
      </ul>
      <div class="highlight"><p><strong>Read-only access.</strong> We request the minimum OAuth scopes and can never push commits, close issues, create events, or modify anything in your tools.</p></div>
    </div>
    <div class="sec" id="use">
      <h2>3. How we use your data</h2>
      <ul>
        <li>Generate proof of work reports from your connected tools</li>
        <li>Deliver reports on schedule and send them to your clients</li>
        <li>Send product notifications (report ready, client viewed, etc.)</li>
        <li>Process payments through Stripe</li>
        <li>Improve the product using aggregate usage patterns</li>
        <li>Respond to support requests</li>
      </ul>
      <p>We will <strong>never</strong> sell your data, share it with advertisers, or use it for any purpose not listed above.</p>
    </div>
    <div class="sec" id="storage"><h2>4. Storage & security</h2><p>Data is stored in a MySQL database. OAuth tokens are encrypted at rest with AES-256. All connections use TLS/HTTPS. We apply access controls and perform regular backups. Data is retained while your account is active. Deleted accounts are purged within 30 days.</p></div>
    <div class="sec" id="third">
      <h2>5. Third-party services</h2>
      <ul>
        <li><strong>Stripe</strong> — payment processing</li>
        <li><strong>Gmail / SMTP</strong> — transactional email delivery</li>
        <li><strong>GitHub, Google, Linear, Notion</strong> — OAuth providers. Read-only access only</li>
        <li><strong>Railway</strong> — server infrastructure</li>
      </ul>
      <p>We use no advertising trackers, analytics pixels, or any other third-party data collection beyond what's listed.</p>
    </div>
    <div class="sec" id="rights">
      <h2>6. Your rights</h2>
      <div class="rights-grid">
        <div class="right"><div class="right-title">Access</div><div class="right-desc">Request a copy of all data we hold about you</div></div>
        <div class="right"><div class="right-title">Correction</div><div class="right-desc">Ask us to fix inaccurate or incomplete data</div></div>
        <div class="right"><div class="right-title">Deletion</div><div class="right-desc">Delete your account and all associated data permanently</div></div>
        <div class="right"><div class="right-title">Portability</div><div class="right-desc">Export your data in CSV format at any time</div></div>
        <div class="right"><div class="right-title">Objection</div><div class="right-desc">Opt out of any non-essential communications</div></div>
        <div class="right"><div class="right-title">Revocation</div><div class="right-desc">Disconnect any integration and revoke access tokens</div></div>
      </div>
      <p>Email <a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a> to exercise any right. We respond within 30 days.</p>
    </div>
    <div class="sec" id="cookies"><h2>7. Cookies</h2><p>We use only technically necessary cookies — a session cookie for authentication and an optional remember-me token. No advertising cookies, no tracking pixels, no third-party cookies. No cookie banner needed.</p></div>
    <div class="sec" id="children"><h2>8. Children's privacy</h2><p>ProofWork is not directed at anyone under 16. We do not knowingly collect data from minors. Contact us immediately if you believe we have done so.</p></div>
    <div class="sec" id="changes"><h2>9. Changes to this policy</h2><p>We'll notify users by email at least 14 days before any material changes take effect. The "Last updated" date at the top always reflects the current version.</p></div>
    <div class="sec" id="contact"><h2>10. Contact</h2><p><strong>Email:</strong> <a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a><br><strong>Response time:</strong> Within 30 days (usually within 24 hours)</p></div>
  </main>
</div>
<footer>
  <p>© {{ date('Y') }} ProofWork</p>
  <div class="foot-links">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('terms') }}">Terms</a>
    <a href="{{ route('contact') }}">Contact</a>
  </div>
</footer>
<script>
const sections=document.querySelectorAll('.sec');
const tocLinks=document.querySelectorAll('.toc-list a');
new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting)tocLinks.forEach(a=>a.classList.toggle('active',a.getAttribute('href')==='#'+e.target.id))})},{threshold:.4,rootMargin:'-80px 0px -60% 0px'}).observe && sections.forEach(s=>new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting)tocLinks.forEach(a=>a.classList.toggle('active',a.getAttribute('href')==='#'+e.target.id))})},{threshold:.4,rootMargin:'-80px 0px -60% 0px'}).observe(s));
</script>
</body>
</html>
