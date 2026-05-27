<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Changelog — ProofWork</title>
  <meta name="description" content="Every update, fix, and improvement to ProofWork documented.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital,wght@0,400;1,400&family=Geist+Mono:wght@300;400;500&family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root{--bg:#0c0c0e;--surface:#111113;--border:#242428;--border2:#2e2e35;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--amber2:#f5b43a;--coral:#e85c3a;--green:#27c93f;--sky:#4a9eff;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
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
    .page{max-width:820px;margin:0 auto;padding:7.5rem 2rem 6rem}
    .eyebrow{font-family:var(--mono);font-size:.6rem;color:var(--ink3);letter-spacing:.15em;text-transform:uppercase;margin-bottom:.9rem}
    .page-title{font-family:var(--serif);font-size:clamp(2.5rem,6vw,3.8rem);font-weight:400;font-style:italic;letter-spacing:-.03em;margin-bottom:.7rem}
    .page-title em{color:var(--amber)}
    .page-sub{font-size:.9rem;color:var(--ink2);max-width:50ch;line-height:1.7;margin-bottom:3rem}

    /* ENTRIES */
    .cl-entry{display:grid;grid-template-columns:140px 1fr;gap:3rem;padding:2.5rem 0;border-bottom:1px solid var(--border)}
    .cl-entry:last-child{border-bottom:none}
    .cl-left{}
    .cl-date{font-family:var(--mono);font-size:.65rem;color:var(--ink3);margin-bottom:.4rem;letter-spacing:.04em}
    .cl-version{font-family:var(--mono);font-size:.7rem;color:var(--amber);letter-spacing:.06em;font-weight:500}
    .cl-latest{font-family:var(--mono);font-size:.55rem;color:var(--green);background:rgba(39,201,63,.08);border:1px solid rgba(39,201,63,.18);padding:.12rem .45rem;border-radius:3px;display:inline-block;margin-top:.3rem;letter-spacing:.06em;text-transform:uppercase}
    .cl-right{}
    .cl-title{font-size:1.05rem;font-weight:700;letter-spacing:-.02em;margin-bottom:.5rem}
    .cl-desc{font-size:.86rem;color:var(--ink2);line-height:1.7;margin-bottom:1.2rem}
    .cl-items{list-style:none;display:flex;flex-direction:column;gap:.4rem}
    .cl-item{font-size:.82rem;color:var(--ink2);display:flex;gap:.6rem;align-items:flex-start;line-height:1.5}
    .cl-tag{font-family:var(--mono);font-size:.54rem;padding:.1rem .42rem;border-radius:3px;flex-shrink:0;margin-top:.15rem;text-transform:uppercase;letter-spacing:.06em;font-weight:500}
    .t-new{background:rgba(39,201,63,.08);color:var(--green);border:1px solid rgba(39,201,63,.15)}
    .t-fix{background:rgba(232,92,58,.07);color:var(--coral);border:1px solid rgba(232,92,58,.14)}
    .t-imp{background:rgba(74,158,255,.07);color:var(--sky);border:1px solid rgba(74,158,255,.14)}
    .t-sec{background:rgba(168,85,247,.08);color:#a855f7;border:1px solid rgba(168,85,247,.18)}

    /* SUBSCRIBE */
    .subscribe-bar{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:1.5rem 1.8rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-top:2rem}
    .sub-text{}
    .sub-title{font-size:.88rem;font-weight:600;margin-bottom:.2rem}
    .sub-desc{font-size:.78rem;color:var(--ink3)}
    .btn-ghost{display:inline-flex;align-items:center;gap:.4rem;background:transparent;border:1px solid var(--border2);color:var(--ink2);padding:.6rem 1.2rem;border-radius:6px;font-family:var(--sans);font-size:.8rem;transition:all .18s}
    .btn-ghost:hover{color:var(--ink);border-color:var(--border)}

    footer{border-top:1px solid var(--border);padding:1.8rem 2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.8rem}
    footer p{font-family:var(--mono);font-size:.58rem;color:var(--ink3)}
    .foot-links{display:flex;gap:1.5rem}
    .foot-links a{font-family:var(--mono);font-size:.58rem;color:var(--ink3);transition:color .18s}
    .foot-links a:hover{color:var(--amber)}
    @media(max-width:600px){.cl-entry{grid-template-columns:1fr;gap:1rem}.page{padding:7rem 1.2rem 4rem}nav{padding:.8rem 1.2rem}}
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
  <div class="eyebrow">Changelog</div>
  <h1 class="page-title">What we've<br><em>shipped.</em></h1>
  <p class="page-sub">Every release, fix, and improvement — documented in full transparency.</p>

  @php
  $releases = [
    [
      'date' => 'May 2026',
      'version' => 'v1.0.0',
      'latest' => true,
      'title' => 'ProofWork is live',
      'desc' => 'The full SaaS is live. Free accounts available now. No waitlist, no application — create an account and start generating proof of work reports today.',
      'items' => [
        ['new','GitHub integration — commits, PRs, code reviews auto-pulled'],
        ['new','Linear integration — completed tasks and closed issues'],
        ['new','Google Calendar — meetings and decisions auto-logged'],
        ['new','AI summaries via Claude API (Pro plan)'],
        ['new','Public shareable report links — no client login needed'],
        ['new','Auto weekly report generation every Friday'],
        ['new','Stripe billing — Free, Pro ($19/mo), Agency ($49/mo) plans'],
        ['new','14-day free trial on all paid plans'],
        ['new','GitHub OAuth + Google OAuth login'],
        ['new','Admin panel with MRR dashboard, user management, broadcast emails'],
        ['new','Report entries — manual items + AI narrative'],
        ['new','Client management — assign clients to projects, send reports directly'],
      ],
    ],
    [
      'date' => 'Apr 2026',
      'version' => 'v0.9.0',
      'latest' => false,
      'title' => 'Billing & integrations',
      'desc' => 'Added Stripe billing, multi-integration support, and the auto-send pipeline.',
      'items' => [
        ['new','Stripe Checkout + Customer Portal'],
        ['new','Cashier subscription management'],
        ['new','Integration OAuth flow (GitHub, Google)'],
        ['new','Weekly cron job for auto-report generation'],
        ['new','ReportGeneratorService pulling real GitHub data'],
        ['imp','Improved session handling and CSRF protection'],
        ['fix','OAuth email verification bypass for social logins'],
        ['fix','Admin middleware password protection'],
      ],
    ],
    [
      'date' => 'Mar 2026',
      'version' => 'v0.5.0',
      'latest' => false,
      'title' => 'Auth & dashboard',
      'desc' => 'Core authentication, dashboard, and project/report CRUD.',
      'items' => [
        ['new','User registration and login'],
        ['new','Email verification flow'],
        ['new','Dashboard with project and report overview'],
        ['new','Project CRUD with client assignment'],
        ['new','Report generation engine (basic)'],
        ['new','Public report share token system'],
        ['new','Client management module'],
        ['imp','Sidebar navigation and app layout'],
      ],
    ],
    [
      'date' => 'Feb 2026',
      'version' => 'v0.1.0',
      'latest' => false,
      'title' => 'Initial prototype',
      'desc' => 'First working version — manual report generation from GitHub data.',
      'items' => [
        ['new','GitHub data fetching via API'],
        ['new','Basic HTML report output'],
        ['new','Laravel 11 project scaffolding'],
        ['new','MySQL schema — users, projects, reports, entries'],
      ],
    ],
  ];
  @endphp

  @foreach($releases as $release)
  <div class="cl-entry">
    <div class="cl-left">
      <div class="cl-date">{{ $release['date'] }}</div>
      <div class="cl-version">{{ $release['version'] }}</div>
      @if($release['latest'])<div class="cl-latest">Latest</div>@endif
    </div>
    <div class="cl-right">
      <div class="cl-title">{{ $release['title'] }}</div>
      <div class="cl-desc">{{ $release['desc'] }}</div>
      <ul class="cl-items">
        @foreach($release['items'] as [$tag,$text])
        <li class="cl-item">
          <span class="cl-tag t-{{ $tag }}">{{ $tag }}</span>
          {{ $text }}
        </li>
        @endforeach
      </ul>
    </div>
  </div>
  @endforeach

  <div class="subscribe-bar">
    <div class="sub-text">
      <div class="sub-title">Get notified of new releases</div>
      <div class="sub-desc">Follow @proofwork on Twitter or email us to be added to release announcements.</div>
    </div>
    <a href="https://twitter.com/proofwork" target="_blank" class="btn-ghost">
      Follow @proofwork
      <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 6h7m-3-3 3 3-3 3"/></svg>
    </a>
  </div>
</div>
<footer>
  <p>© {{ date('Y') }} ProofWork</p>
  <div class="foot-links">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('roadmap') }}">Roadmap</a>
    <a href="{{ route('contact') }}">Contact</a>
  </div>
</footer>
</body>
</html>
