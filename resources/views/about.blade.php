<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About — ProofWork</title>
  <meta name="description" content="ProofWork is a live SaaS product built by a solo founder in Casablanca.">
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
    .nav-right{display:flex;align-items:center;gap:.5rem}
    .nav-back{font-size:.78rem;color:var(--ink3);padding:.4rem .8rem;border-radius:5px;transition:all .18s}
    .nav-back:hover{color:var(--ink);background:rgba(255,255,255,.05)}
    .nav-cta{background:var(--amber);color:#000;font-size:.75rem;font-weight:700;padding:.45rem 1rem;border-radius:5px;transition:all .18s}
    .nav-cta:hover{background:var(--amber2)}
    .page{max-width:760px;margin:0 auto;padding:7.5rem 2rem 6rem}
    .eyebrow{font-family:var(--mono);font-size:.6rem;color:var(--ink3);letter-spacing:.15em;text-transform:uppercase;margin-bottom:.9rem}
    .page-title{font-family:var(--serif);font-size:clamp(2.5rem,6vw,4rem);font-weight:400;font-style:italic;letter-spacing:-.03em;line-height:1.05;margin-bottom:2.5rem}
    .page-title em{color:var(--amber)}
    .founder{background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:3.5rem}
    .founder-bar{height:3px;background:linear-gradient(90deg,var(--amber),#4a9eff,var(--amber2))}
    .founder-inner{padding:2rem;display:flex;gap:1.8rem;align-items:flex-start}
    .founder-av{width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,var(--amber),var(--amber2));display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-size:1.9rem;font-style:italic;color:#0c0c0e;flex-shrink:0}
    .founder-name{font-size:1rem;font-weight:700;letter-spacing:-.02em;margin-bottom:.15rem}
    .founder-role{font-family:var(--mono);font-size:.62rem;color:var(--ink3);letter-spacing:.06em;margin-bottom:.9rem}
    .founder-bio{font-size:.88rem;color:var(--ink2);line-height:1.75}
    .founder-links{display:flex;gap:.5rem;flex-wrap:wrap;margin-top:1.1rem}
    .founder-link{display:inline-flex;align-items:center;gap:.35rem;font-family:var(--mono);font-size:.6rem;color:var(--ink3);padding:.28rem .65rem;border:1px solid var(--border2);border-radius:4px;transition:all .18s}
    .founder-link:hover{border-color:var(--amber);color:var(--amber)}
    .section-title{font-family:var(--serif);font-size:1.4rem;font-style:italic;font-weight:400;color:var(--amber);margin-bottom:.9rem;letter-spacing:-.01em;margin-top:2.5rem}
    p{font-size:.9rem;color:var(--ink2);line-height:1.8;margin-bottom:1.1rem}
    p strong{color:var(--ink);font-weight:500}
    .quote{background:var(--surface);border:1px solid var(--border);border-left:3px solid var(--amber);border-radius:0 8px 8px 0;padding:1.3rem 1.7rem;margin:1.8rem 0}
    .quote p{font-family:var(--serif);font-size:1.1rem;font-style:italic;color:var(--ink);line-height:1.6;margin:0}
    .stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin:2.5rem 0}
    .stat{background:var(--surface);padding:1.3rem;text-align:center}
    .stat-num{font-family:var(--serif);font-size:2.2rem;font-style:italic;color:var(--amber);line-height:1;display:block}
    .stat-lbl{font-family:var(--mono);font-size:.58rem;color:var(--ink3);text-transform:uppercase;letter-spacing:.1em;margin-top:.3rem;display:block}
    .values{display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin:1.5rem 0}
    .val{background:var(--surface);padding:1.5rem}
    .val-icon{font-size:1.2rem;margin-bottom:.5rem}
    .val-title{font-size:.84rem;font-weight:600;margin-bottom:.3rem;letter-spacing:-.01em}
    .val-desc{font-size:.77rem;color:var(--ink3);line-height:1.55}
    .cta-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:2.2rem;text-align:center;margin-top:3.5rem}
    .cta-card h3{font-family:var(--serif);font-size:1.6rem;font-style:italic;font-weight:400;margin-bottom:.5rem}
    .cta-card p{font-size:.86rem;color:var(--ink2);margin-bottom:1.5rem;max-width:44ch;margin-left:auto;margin-right:auto}
    .btn-amber{display:inline-block;background:var(--amber);color:#000;padding:.8rem 1.8rem;border-radius:6px;font-family:var(--sans);font-size:.84rem;font-weight:700;transition:all .18s;margin-right:.5rem}
    .btn-amber:hover{background:var(--amber2);transform:translateY(-1px)}
    .btn-ghost{display:inline-block;background:transparent;color:var(--ink2);border:1px solid var(--border2);padding:.8rem 1.8rem;border-radius:6px;font-family:var(--sans);font-size:.84rem;transition:all .18s}
    .btn-ghost:hover{color:var(--ink);border-color:var(--border)}
    footer{border-top:1px solid var(--border);padding:1.8rem 2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.8rem}
    footer p{font-family:var(--mono);font-size:.58rem;color:var(--ink3)}
    .foot-links{display:flex;gap:1.5rem}
    .foot-links a{font-family:var(--mono);font-size:.58rem;color:var(--ink3);transition:color .18s}
    .foot-links a:hover{color:var(--amber)}
    @media(max-width:600px){.page{padding:7rem 1.2rem 4rem}nav{padding:.8rem 1.2rem}.founder-inner{flex-direction:column}.values,.stats-row{grid-template-columns:1fr}}
  </style>
</head>
<body>
<nav>
  <a href="{{ route('home') }}" class="nav-brand"><div class="nav-mark">P</div>ProofWork</a>
  <div class="nav-right">
    <a href="{{ route('home') }}" class="nav-back">← Home</a>
    <a href="{{ route('register') }}" class="nav-cta">Start for free</a>
  </div>
</nav>
<div class="page">
  <div class="eyebrow">About ProofWork</div>
  <h1 class="page-title">Built by a freelancer,<br>for <em>freelancers.</em></h1>

  <div class="founder">
    <div class="founder-bar"></div>
    <div class="founder-inner">
      <div class="founder-av">A</div>
      <div>
        <div class="founder-name">Ahmat Issa</div>
        <div class="founder-role">Founder · M1 Data Science & AI · Université Mundiapolis, Casablanca 🇲🇦</div>
        <div class="founder-bio">I'm a Master's student in Data Science & AI building software on the side. ProofWork was born from a real problem I hit during freelance work — a client disputed my invoice because I couldn't prove my hours. That cost me $2,400 and a relationship. I built this tool so it never happens to anyone again.</div>
        <div class="founder-links">
          <a href="https://github.com/ahmatissa08" target="_blank" class="founder-link">⌥ ahmatissa08</a>
          <a href="https://twitter.com/proofwork" target="_blank" class="founder-link">𝕏 @proofwork</a>
          <a href="mailto:addimiahmat@gmail.com" class="founder-link">✉ addimiahmat@gmail.com</a>
        </div>
      </div>
    </div>
  </div>

  <h2 class="section-title">Why I built this</h2>
  <p>Last year I finished a two-month freelance project. When I sent the invoice, the client pushed back. <strong>"Can you actually prove you worked 80 hours?"</strong> I had GitHub commits, Notion pages, calendar events — but no way to show it clearly in a format a non-technical client could understand.</p>
  <div class="quote"><p>"I spent a whole Friday copy-pasting screenshots into a PDF. There had to be a better way."</p></div>
  <p>I asked other freelancers. Same story everywhere — reports written from memory, invoices disputed, 3-5 hours a week wasted on admin that still didn't protect them. So I built ProofWork.</p>

  <h2 class="section-title">Where we are today</h2>
  <p>ProofWork is <strong>live, available, and ready to use right now</strong>. No waitlist. No application process. Create a free account today, connect GitHub in one OAuth click, and your first automated report will be ready by Friday.</p>
  <p>The product connects to your existing tools — GitHub, Linear, Notion, Google Calendar — and silently collects your activity every day. Every Friday it generates a clean, verifiable report and delivers it to your client. You do nothing after the initial setup.</p>

  <div class="stats-row">
    <div class="stat"><span class="stat-num">5min</span><span class="stat-lbl">to set up</span></div>
    <div class="stat"><span class="stat-num">0</span><span class="stat-lbl">weekly effort</span></div>
    <div class="stat"><span class="stat-num">100%</span><span class="stat-lbl">verified data</span></div>
  </div>

  <h2 class="section-title">What we believe</h2>
  <div class="values">
    <div class="val"><div class="val-icon">🔍</div><div class="val-title">Radical transparency</div><div class="val-desc">Every item in a ProofWork report links to its original source. Nothing invented or summarized from nothing.</div></div>
    <div class="val"><div class="val-icon">⚡</div><div class="val-title">Zero effort by default</div><div class="val-desc">If using ProofWork requires ongoing manual work, we've failed. Reports should just happen.</div></div>
    <div class="val"><div class="val-icon">🔒</div><div class="val-title">Read-only forever</div><div class="val-desc">We only request read access. We can never push code, close issues, or write anything to your tools.</div></div>
    <div class="val"><div class="val-icon">🤝</div><div class="val-title">Built with users</div><div class="val-desc">Every feature on the roadmap came from a real conversation with a freelancer — not a product manager's spreadsheet.</div></div>
  </div>

  <div class="cta-card">
    <h3>Start using ProofWork today.</h3>
    <p>Free plan available. No credit card. Your first report ready by Friday.</p>
    <a href="{{ route('register') }}" class="btn-amber">Create free account →</a>
    <a href="mailto:addimiahmat@gmail.com" class="btn-ghost">Get in touch</a>
  </div>
</div>
<footer>
  <p>© {{ date('Y') }} ProofWork · Built solo by Ahmat Issa · Casablanca 🇲🇦</p>
  <div class="foot-links">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('contact') }}">Contact</a>
    <a href="{{ route('privacy') }}">Privacy</a>
    <a href="{{ route('terms') }}">Terms</a>
  </div>
</footer>
</body>
</html>
