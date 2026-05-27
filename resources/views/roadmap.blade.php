<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Roadmap — ProofWork</title>
  <meta name="description" content="See what ProofWork has shipped and what's coming next.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital,wght@0,400;1,400&family=Geist+Mono:wght@300;400;500&family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root{--bg:#0c0c0e;--surface:#111113;--border:#242428;--border2:#2e2e35;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--amber2:#f5b43a;--green:#27c93f;--sky:#4a9eff;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{-webkit-font-smoothing:antialiased}
    body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6}
    ::-webkit-scrollbar{width:3px}::-webkit-scrollbar-thumb{background:var(--border2)}
    a{text-decoration:none}
    nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:.9rem 2rem;background:rgba(12,12,14,.9);border-bottom:1px solid var(--border);backdrop-filter:blur(20px)}
    .nav-brand{display:flex;align-items:center;gap:.5rem;color:var(--ink);font-weight:600;font-size:.9rem;letter-spacing:-.02em}
    .nav-mark{width:26px;height:26px;border-radius:6px;background:linear-gradient(135deg,var(--amber),var(--amber2));display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-style:italic;font-size:.88rem;color:#0c0c0e}
    .nav-right{display:flex;gap:.5rem;align-items:center}
    .nav-back{font-size:.78rem;color:var(--ink3);padding:.4rem .8rem;border-radius:5px;transition:all .18s}
    .nav-back:hover{color:var(--ink);background:rgba(255,255,255,.05)}
    .nav-cta{background:var(--amber);color:#000;font-size:.75rem;font-weight:700;padding:.45rem 1rem;border-radius:5px;transition:all .18s}
    .nav-cta:hover{background:var(--amber2)}
    .page{max-width:800px;margin:0 auto;padding:7.5rem 2rem 6rem}
    .eyebrow{font-family:var(--mono);font-size:.6rem;color:var(--ink3);letter-spacing:.15em;text-transform:uppercase;margin-bottom:.9rem}
    .page-title{font-family:var(--serif);font-size:clamp(2.5rem,6vw,3.8rem);font-weight:400;font-style:italic;letter-spacing:-.03em;margin-bottom:.7rem}
    .page-title em{color:var(--amber)}
    .page-sub{font-size:.9rem;color:var(--ink2);max-width:50ch;line-height:1.7;margin-bottom:.6rem}
    .build-note{font-family:var(--mono);font-size:.65rem;color:var(--sky);margin-bottom:3rem;display:flex;align-items:center;gap:.4rem}
    .build-dot{width:6px;height:6px;border-radius:50%;background:var(--sky);animation:blink 2s infinite;flex-shrink:0}
    @keyframes blink{0%,100%{opacity:1}50%{opacity:.25}}

    /* STATUS LEGEND */
    .legend{display:flex;gap:1.5rem;flex-wrap:wrap;margin-bottom:2.5rem;padding:1rem 1.2rem;background:var(--surface);border:1px solid var(--border);border-radius:8px}
    .legend-item{display:flex;align-items:center;gap:.4rem;font-family:var(--mono);font-size:.62rem;color:var(--ink3)}
    .leg-dot{width:8px;height:8px;border-radius:50%}

    /* SECTIONS */
    .rm-section{margin-bottom:3rem}
    .rm-section-label{font-family:var(--mono);font-size:.6rem;letter-spacing:.14em;text-transform:uppercase;margin-bottom:1.1rem;display:flex;align-items:center;gap:.6rem}
    .rm-section-label::after{content:'';flex:1;height:1px;background:var(--border)}
    .label-done{color:var(--green)}.label-prog{color:var(--amber)}.label-plan{color:var(--ink3)}

    /* ITEMS */
    .rm-items{display:flex;flex-direction:column;gap:.6rem}
    .rm-item{display:flex;gap:1rem;align-items:flex-start;padding:1rem 1.2rem;background:var(--surface);border:1px solid var(--border);border-radius:8px;transition:border-color .18s}
    .rm-item:hover{border-color:var(--border2)}
    .rm-item.done{opacity:.7}
    .status-dot{width:9px;height:9px;border-radius:50%;flex-shrink:0;margin-top:.35rem}
    .dot-done{background:var(--green)}
    .dot-prog{background:var(--amber);animation:pulse 2s infinite}
    .dot-plan{background:var(--border2)}
    @keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.8)}}
    .rm-item-content{flex:1}
    .rm-item-title{font-size:.88rem;font-weight:600;letter-spacing:-.01em;margin-bottom:.2rem}
    .rm-item-desc{font-size:.78rem;color:var(--ink3);line-height:1.55}
    .rm-item-tag{font-family:var(--mono);font-size:.55rem;padding:.1rem .45rem;border-radius:3px;display:inline-block;margin-top:.3rem}
    .tag-github{background:rgba(242,240,235,.07);color:var(--ink3);border:1px solid var(--border2)}
    .tag-pro{background:rgba(232,163,37,.1);color:var(--amber);border:1px solid rgba(232,163,37,.2)}
    .rm-done-check{font-family:var(--mono);font-size:.62rem;color:var(--green);flex-shrink:0;margin-top:.2rem}

    /* REQUEST CTA */
    .request-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:1.8rem;text-align:center;margin-top:2rem}
    .request-card h3{font-family:var(--serif);font-size:1.3rem;font-style:italic;font-weight:400;margin-bottom:.5rem}
    .request-card p{font-size:.84rem;color:var(--ink2);margin-bottom:1.2rem;line-height:1.6}
    .btn-amber{display:inline-flex;align-items:center;gap:.4rem;background:var(--amber);color:#000;padding:.7rem 1.5rem;border-radius:6px;font-family:var(--sans);font-size:.82rem;font-weight:700;transition:all .18s}
    .btn-amber:hover{background:var(--amber2);transform:translateY(-1px)}

    footer{border-top:1px solid var(--border);padding:1.8rem 2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.8rem}
    footer p{font-family:var(--mono);font-size:.58rem;color:var(--ink3)}
    .foot-links{display:flex;gap:1.5rem}
    .foot-links a{font-family:var(--mono);font-size:.58rem;color:var(--ink3);transition:color .18s}
    .foot-links a:hover{color:var(--amber)}
    @media(max-width:600px){.page{padding:7rem 1.2rem 4rem}nav{padding:.8rem 1.2rem}}
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
  <div class="eyebrow">Product roadmap</div>
  <h1 class="page-title">What we're<br><em>building.</em></h1>
  <p class="page-sub">Built in public. Here's what's live, what's in progress, and what's next.</p>
  <div class="build-note"><span class="build-dot"></span>ProofWork is live and actively developed. New releases ship regularly.</div>

  <div class="legend">
    <div class="legend-item"><div class="leg-dot" style="background:var(--green)"></div>Shipped & live</div>
    <div class="legend-item"><div class="leg-dot" style="background:var(--amber)"></div>In progress</div>
    <div class="legend-item"><div class="leg-dot" style="background:var(--border2)"></div>Planned</div>
  </div>

  <!-- SHIPPED -->
  <div class="rm-section">
    <div class="rm-section-label label-done">✓ Shipped</div>
    <div class="rm-items">
      @foreach([
        ['GitHub integration','Pull commits, PRs, and code reviews automatically. Real data, real timestamps.','github'],
        ['Linear integration','Sync completed tasks and closed issues directly into your report.',''],
        ['Google Calendar integration','Meetings, decisions, and action items auto-logged weekly.',''],
        ['AI-generated summaries','Claude API turns raw activity into a readable narrative your client enjoys.','pro'],
        ['Public share link','One URL for your client. No login required. Works on any device.',''],
        ['Auto weekly reports','Reports generated and sent every Friday at midnight. Zero effort.','pro'],
        ['Stripe billing','Free, Pro ($19), and Agency ($49) plans. Secure checkout. Cancel anytime.',''],
        ['OAuth login','Sign in with GitHub or Google. Instant account creation.',''],
        ['Admin panel','Full SaaS management: users, MRR dashboard, broadcast emails, plan overrides.',''],
        ['Client report view','Clean public report page. Verifiable. Cryptographically hashed.',''],
      ] as [$title,$desc,$tag])
      <div class="rm-item done">
        <div class="status-dot dot-done"></div>
        <div class="rm-item-content">
          <div class="rm-item-title">{{ $title }}</div>
          <div class="rm-item-desc">{{ $desc }}</div>
          @if($tag)<div class="rm-item-tag tag-{{ $tag }}">{{ $tag === 'pro' ? 'Pro plan' : 'Requires GitHub' }}</div>@endif
        </div>
        <div class="rm-done-check">✓</div>
      </div>
      @endforeach
    </div>
  </div>

  <!-- IN PROGRESS -->
  <div class="rm-section">
    <div class="rm-section-label label-prog">⚡ In progress</div>
    <div class="rm-items">
      @foreach([
        ['Notion integration','Pull updated pages and database entries into your weekly report.'],
        ['Jira integration','Sync completed issues and sprints from Jira Cloud.'],
        ['PDF export','Download any report as a polished, branded PDF.'],
        ['Custom branding','Upload your logo, set your brand colors on reports.'],
      ] as [$title,$desc])
      <div class="rm-item">
        <div class="status-dot dot-prog"></div>
        <div class="rm-item-content">
          <div class="rm-item-title">{{ $title }}</div>
          <div class="rm-item-desc">{{ $desc }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <!-- PLANNED -->
  <div class="rm-section">
    <div class="rm-section-label label-plan">◎ Planned</div>
    <div class="rm-items">
      @foreach([
        ['Figma integration','Track design iterations, shipped frames, and file changes.'],
        ['Slack integration','Log key decisions and updates from channel conversations.'],
        ['GitLab support','Same GitHub features for GitLab repositories.'],
        ['Client portal','Let clients view all their reports in one place without needing a link each time.'],
        ['Team workspace','Invite team members and manage multiple contributors in one account.'],
        ['Webhook API','Push activity data from any tool to ProofWork via webhooks.'],
        ['Report scheduling','Choose your own report day and frequency per project.'],
        ['Mobile app','Native iOS and Android app to manage reports on the go.'],
      ] as [$title,$desc])
      <div class="rm-item">
        <div class="status-dot dot-plan"></div>
        <div class="rm-item-content">
          <div class="rm-item-title">{{ $title }}</div>
          <div class="rm-item-desc">{{ $desc }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <div class="request-card">
    <h3>Missing something?</h3>
    <p>Send a feature request — every suggestion is read personally and influences what ships next.</p>
    <a href="{{ route('contact') }}" class="btn-amber">
      Request a feature
      <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 6h7m-3-3 3 3-3 3"/></svg>
    </a>
  </div>
</div>
<footer>
  <p>© {{ date('Y') }} ProofWork · Built by Ahmat Issa</p>
  <div class="foot-links">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('changelog') }}">Changelog</a>
    <a href="{{ route('contact') }}">Contact</a>
  </div>
</footer>
</body>
</html>
