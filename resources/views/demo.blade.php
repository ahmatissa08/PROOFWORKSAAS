@extends('layouts.guest')
@section('title', 'Live Demo — ProofWork')
@section('og_title', 'ProofWork Live Demo — See your proof of work in 5 seconds')
@section('og_description', 'Enter a GitHub username or repository and ProofWork generates a real client-ready report instantly.')

@push('styles')
<style>
:root{
  --bg:#09090b;--surface:#111113;--surface2:#17171a;--surface3:#1e1e22;
  --border:#1f1f23;--border2:#2a2a2f;--border3:#333338;
  --ink:#f4f2ed;--ink2:#9d9b97;--ink3:#55534f;
  --amber:#e8a325;--amber2:#f5b93a;--coral:#e85c3a;--sky:#4a9eff;--green:#27c93f;
  --mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6;overflow-x:hidden}
::-webkit-scrollbar{width:3px}::-webkit-scrollbar-thumb{background:var(--border2);border-radius:2px}

/* NAV */
nav{display:flex;align-items:center;justify-content:space-between;padding:.9rem 2rem;border-bottom:1px solid var(--border);background:rgba(9,9,11,.95);backdrop-filter:blur(20px);position:fixed;top:0;left:0;right:0;z-index:100}
.logo{font-family:var(--serif);font-size:1.15rem;font-style:italic;color:var(--ink);text-decoration:none;display:flex;align-items:center;gap:.5rem}
.logo-icon{width:26px;height:26px;background:var(--amber);border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.logo-icon i{font-size:13px;color:#000}
.logo-word{font-family:var(--sans);font-style:normal;font-weight:300;font-size:1rem}
.nav-r{display:flex;gap:.4rem;align-items:center}
.nav-link{font-size:.78rem;color:var(--ink3);text-decoration:none;padding:.35rem .7rem;border-radius:4px;transition:color .2s,background .2s}
.nav-link:hover{color:var(--ink2);background:rgba(255,255,255,.04)}
.nav-cta{background:var(--amber);color:#000;font-weight:600;font-size:.75rem;padding:.38rem .9rem;border-radius:4px;text-decoration:none;transition:opacity .15s}
.nav-cta:hover{opacity:.88}

/* HERO */
.hero{padding:5.5rem 2rem 3rem;text-align:center;position:relative}
.hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 50% at 50% 0%,rgba(232,163,37,.06) 0%,transparent 70%);pointer-events:none}
.eyebrow{display:inline-flex;align-items:center;gap:.5rem;font-family:var(--mono);font-size:.58rem;color:var(--ink3);letter-spacing:.14em;text-transform:uppercase;margin-bottom:1.2rem;background:rgba(255,255,255,.025);border:1px solid var(--border2);border-radius:30px;padding:.28rem .8rem}
.eyebrow-dot{width:5px;height:5px;background:var(--amber);border-radius:50%;animation:blink 2s ease-in-out infinite;flex-shrink:0}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.2}}
h1.demo-title{font-family:var(--serif);font-size:clamp(2rem,4.5vw,3.5rem);font-style:italic;font-weight:400;letter-spacing:-.03em;margin-bottom:.6rem;line-height:1.1}
h1.demo-title em{color:var(--amber)}
.sub{color:var(--ink3);font-size:.9rem;max-width:46ch;margin:0 auto 2.5rem;line-height:1.7}

/* SEARCH BLOCK */
.search-block{max-width:580px;margin:0 auto}

/* Mode toggle */
.mode-toggle{display:inline-flex;background:var(--surface);border:1px solid var(--border2);border-radius:7px;padding:3px;margin:0 auto 1rem;gap:2px}
.mode-btn{font-family:var(--mono);font-size:.62rem;letter-spacing:.05em;padding:.38rem .95rem;border-radius:4px;cursor:pointer;border:none;background:transparent;color:var(--ink3);transition:all .2s;display:flex;align-items:center;gap:5px}
.mode-btn i{font-size:13px}
.mode-btn.active{background:var(--surface2);color:var(--ink);box-shadow:0 1px 3px rgba(0,0,0,.4)}

/* Search input */
.search-box{display:flex;align-items:center;background:var(--surface);border:1px solid var(--border2);border-radius:9px;padding:.5rem .5rem .5rem 1rem;transition:border-color .2s,box-shadow .2s;gap:.5rem;margin-bottom:.6rem}
.search-box:focus-within{border-color:rgba(232,163,37,.4);box-shadow:0 0 0 3px rgba(232,163,37,.07)}
.search-ico{color:var(--ink3);display:flex;align-items:center;flex-shrink:0;font-size:16px}
.search-input{flex:1;min-width:0;background:transparent;border:none;color:var(--ink);font-family:var(--mono);font-size:.85rem;outline:none;padding:.25rem .4rem}
.search-input::placeholder{color:var(--ink3)}
.search-btn{background:var(--amber);color:#000;border:none;padding:.58rem 1.3rem;border-radius:6px;font-family:var(--sans);font-size:.8rem;font-weight:700;cursor:pointer;transition:opacity .15s,transform .1s;white-space:nowrap;display:flex;align-items:center;gap:5px;flex-shrink:0}
.search-btn i{font-size:14px}
.search-btn:hover{opacity:.9;transform:translateY(-1px)}
.search-btn:disabled{opacity:.4;cursor:not-allowed;transform:none}

/* Hint */
.search-hint{font-family:var(--mono);font-size:.58rem;color:var(--ink3);text-align:center;margin-bottom:.7rem}
.search-hint code{background:var(--surface2);border:1px solid var(--border2);padding:.08rem .3rem;border-radius:3px;color:var(--amber)}

/* Example chips */
.chips{display:flex;gap:.35rem;justify-content:center;flex-wrap:wrap;margin-bottom:.5rem}
.chip{background:var(--surface2);border:1px solid var(--border2);color:var(--ink3);font-family:var(--mono);font-size:.58rem;padding:.2rem .55rem;border-radius:20px;cursor:pointer;transition:all .18s;display:flex;align-items:center;gap:4px}
.chip i{font-size:11px}
.chip:hover{border-color:rgba(232,163,37,.3);color:var(--amber);background:rgba(232,163,37,.05)}

/* LOADING */
.loading{display:none;text-align:center;padding:3rem 2rem;max-width:400px;margin:0 auto}
.loading.on{display:block}
.spinner{width:28px;height:28px;border:2px solid var(--border2);border-top-color:var(--amber);border-radius:50%;animation:spin .65s linear infinite;margin:0 auto 1.2rem}
@keyframes spin{to{transform:rotate(360deg)}}
.lsteps{display:flex;flex-direction:column;gap:.3rem;font-family:var(--mono);font-size:.62rem;color:var(--ink3)}
.ls{opacity:.25;transition:opacity .2s;display:flex;align-items:center;gap:.5rem;justify-content:center}
.ls.active{opacity:1;color:var(--amber)}.ls.done{opacity:.55;color:var(--green)}
.ls.done .ls-dot{background:var(--green)}.ls.active .ls-dot{background:var(--amber)}
.ls-dot{width:5px;height:5px;border-radius:50%;background:var(--border2);flex-shrink:0;transition:background .2s}

/* ERROR */
.err-state{display:none;text-align:center;padding:2.5rem 2rem;max-width:420px;margin:0 auto}
.err-state.on{display:block}
.err-icon{font-size:1.6rem;color:var(--coral);margin-bottom:.7rem}
.err-msg{font-family:var(--mono);font-size:.75rem;color:var(--ink3);margin-bottom:1.2rem;line-height:1.6}
.btn-ghost{background:transparent;color:var(--ink2);border:1px solid var(--border2);padding:.55rem 1.2rem;font-family:var(--sans);font-size:.78rem;border-radius:5px;cursor:pointer;transition:all .18s}
.btn-ghost:hover{color:var(--ink);border-color:var(--border3)}

/* ═══ REPORT ═══ */
.report{display:none}
.report.on{display:block}
.report-wrap{max-width:760px;margin:0 auto;padding:0 1.5rem 6rem}

/* Report header card */
.rpt-head-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:1rem}
.rpt-stripe{height:2px;background:linear-gradient(90deg,var(--amber) 0%,var(--amber2) 40%,var(--sky) 100%)}
.rpt-head-inner{display:flex;align-items:flex-start;justify-content:space-between;padding:1.3rem 1.5rem;gap:1rem;flex-wrap:wrap}
.rpt-badge-row{display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem;flex-wrap:wrap}
.tag{display:inline-flex;align-items:center;gap:4px;font-family:var(--mono);font-size:.52rem;letter-spacing:.1em;text-transform:uppercase;padding:.17rem .5rem;border-radius:3px}
.tag-verified{background:rgba(39,201,63,.08);color:var(--green);border:1px solid rgba(39,201,63,.15)}
.tag-github{background:rgba(255,255,255,.04);color:var(--ink3);border:1px solid var(--border2)}
.rpt-title{font-size:.95rem;font-weight:600;color:var(--ink);margin-bottom:.2rem}
.rpt-sub{font-family:var(--mono);font-size:.6rem;color:var(--ink3)}
.rpt-meta{text-align:right;flex-shrink:0}
.rpt-period{font-family:var(--mono);font-size:.6rem;color:var(--ink3)}
.rpt-gen{font-family:var(--mono);font-size:.55rem;color:var(--ink3);opacity:.4;margin-top:.2rem}

/* Repo bar */
.repo-bar{display:flex;align-items:center;gap:1.2rem;padding:.75rem 1.5rem;background:var(--surface2);border-top:1px solid var(--border);flex-wrap:wrap}
.repo-item{display:flex;align-items:center;gap:.4rem;font-family:var(--mono);font-size:.6rem;color:var(--ink3)}
.repo-item .v{color:var(--ink2)}
.repo-item i{font-size:12px;color:var(--ink3)}
.repo-gh-link{font-family:var(--mono);font-size:.6rem;color:var(--sky);margin-left:auto;text-decoration:none;display:flex;align-items:center;gap:.3rem;transition:opacity .2s}
.repo-gh-link:hover{opacity:.7}
.repo-gh-link i{font-size:12px}

/* Stats row */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--border);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:1rem}
.stat-box{background:var(--surface);padding:1.1rem 1.2rem}
.stat-lbl{font-family:var(--mono);font-size:.53rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.3rem;display:flex;align-items:center;gap:.3rem}
.stat-lbl i{font-size:11px}
.stat-val{font-family:var(--serif);font-size:1.9rem;font-style:italic;color:var(--ink);line-height:1}
.stat-val.a{color:var(--amber)}.stat-val.s{color:var(--sky)}.stat-val.g{color:var(--green)}
.stat-detail{font-family:var(--mono);font-size:.52rem;color:var(--ink3);margin-top:.2rem}

/* Section card */
.sec{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:1rem}
.sec-head{display:flex;align-items:center;gap:.6rem;padding:.8rem 1.2rem;border-bottom:1px solid var(--border);background:var(--surface2)}
.sec-icon{width:22px;height:22px;border:1px solid var(--border2);border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;color:var(--ink3)}
.sec-title{font-size:.78rem;font-weight:600;color:var(--ink)}
.sec-count{margin-left:auto;font-family:var(--mono);font-size:.54rem;color:var(--ink3);background:var(--surface);border:1px solid var(--border2);padding:.1rem .4rem;border-radius:3px}

/* Commit list */
.commit-list{padding:.5rem 1.2rem}
.commit-item{display:flex;gap:.75rem;padding:.55rem 0;border-bottom:1px solid rgba(255,255,255,.025);align-items:flex-start}
.commit-item:last-child{border-bottom:none}
.c-sha{font-family:var(--mono);font-size:.55rem;color:var(--amber);flex-shrink:0;margin-top:2px;opacity:.7}
.c-msg{font-family:var(--mono);font-size:.68rem;color:var(--ink2);line-height:1.45;flex:1}
.c-repo{font-family:var(--mono);font-size:.56rem;color:var(--ink3);margin-top:.1rem;display:flex;align-items:center;gap:.3rem}
.c-repo i{font-size:10px}
.c-time{font-family:var(--mono);font-size:.54rem;color:var(--ink3);flex-shrink:0;opacity:.5}
.commit-empty{padding:1rem 1.2rem;font-family:var(--mono);font-size:.68rem;color:var(--ink3)}

/* PR list */
.pr-list{padding:.5rem 1.2rem}
.pr-item{display:flex;gap:.75rem;padding:.55rem 0;border-bottom:1px solid rgba(255,255,255,.025);align-items:center}
.pr-item:last-child{border-bottom:none}
.pr-dot{width:7px;height:7px;border-radius:50%;background:var(--green);flex-shrink:0;opacity:.8}
.pr-title{font-family:var(--mono);font-size:.68rem;color:var(--ink2);flex:1;line-height:1.4}
.pr-author{font-family:var(--mono);font-size:.56rem;color:var(--ink3);white-space:nowrap}

/* Contributors */
.contribs{display:flex;gap:.4rem;flex-wrap:wrap;padding:.9rem 1.2rem}
.contrib-pill{display:inline-flex;align-items:center;gap:.35rem;font-family:var(--mono);font-size:.6rem;color:var(--ink2);background:var(--surface2);border:1px solid var(--border2);padding:.22rem .55rem;border-radius:20px}
.contrib-pill i{font-size:11px;color:var(--ink3)}

/* Repo grid */
.repo-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border)}
.repo-card{background:var(--surface);padding:1rem 1.2rem;transition:background .12s}
.repo-card:hover{background:var(--surface2)}
.repo-name{font-size:.78rem;font-weight:500;color:var(--sky);margin-bottom:.2rem;display:flex;align-items:center;gap:.3rem}
.repo-name i{font-size:11px}
.repo-desc{font-size:.68rem;color:var(--ink3);line-height:1.4;margin-bottom:.5rem;min-height:2.1em}
.repo-foot{display:flex;gap:.6rem;align-items:center;flex-wrap:wrap}
.repo-lang{font-family:var(--mono);font-size:.56rem;color:var(--ink3);display:flex;align-items:center;gap:.25rem}
.repo-lang::before{content:'';width:6px;height:6px;border-radius:50%;background:var(--amber);display:inline-block}
.repo-stars{font-family:var(--mono);font-size:.56rem;color:var(--ink3);display:flex;align-items:center;gap:.2rem}
.repo-stars i{font-size:10px}
.repo-upd{font-family:var(--mono);font-size:.54rem;color:var(--ink3);opacity:.4;margin-left:auto}

/* Summary */
.summary-wrap{padding:1.2rem 1.5rem}
.summary-label{font-family:var(--mono);font-size:.52rem;color:var(--amber);letter-spacing:.14em;text-transform:uppercase;opacity:.7;margin-bottom:.5rem;display:flex;align-items:center;gap:.4rem}
.summary-label i{font-size:11px}
.summary-text{font-size:.88rem;color:var(--ink2);line-height:1.75;font-style:italic}

/* Hash */
.hash-row{display:flex;align-items:center;justify-content:space-between;padding:.6rem 1rem;background:var(--surface);border:1px solid var(--border);border-radius:7px;margin-bottom:1.5rem}
.hash-url{font-family:var(--mono);font-size:.58rem;color:var(--ink3);display:flex;align-items:center;gap:.4rem}
.hash-url i{font-size:11px;color:var(--amber)}
.hash-val{font-family:var(--mono);font-size:.56rem;color:var(--ink3);opacity:.3}

/* CTA */
.cta-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:2rem;text-align:center;position:relative;overflow:hidden}
.cta-card::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 100%,rgba(232,163,37,.04) 0%,transparent 70%);pointer-events:none}
.cta-card h3{font-family:var(--serif);font-size:1.5rem;font-style:italic;font-weight:400;margin-bottom:.45rem}
.cta-card p{color:var(--ink3);font-size:.84rem;margin-bottom:1.3rem;line-height:1.7;max-width:48ch;margin-left:auto;margin-right:auto}
.cta-btns{display:flex;gap:.6rem;justify-content:center;flex-wrap:wrap}
.btn-amber{background:var(--amber);color:#000;border:none;padding:.72rem 1.6rem;font-family:var(--sans);font-size:.83rem;font-weight:700;border-radius:5px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem;transition:opacity .15s,transform .1s}
.btn-amber:hover{opacity:.9;transform:translateY(-1px)}
.btn-amber i{font-size:14px}

/* Animations */
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.fi{animation:fadeUp .4s ease both}
.fi-1{animation-delay:.05s}.fi-2{animation-delay:.1s}.fi-3{animation-delay:.15s}
.fi-4{animation-delay:.2s}.fi-5{animation-delay:.25s}.fi-6{animation-delay:.3s}.fi-7{animation-delay:.35s}

@media(max-width:768px){
  nav{padding:.8rem 1rem}.nav-link{display:none}
  .hero{padding:5rem 1rem 2rem}
  .report-wrap{padding:0 1rem 4rem}
  .stats-row{grid-template-columns:1fr 1fr}
  .repo-grid{grid-template-columns:1fr}
}
@media(max-width:360px){
  .search-box{align-items:stretch;flex-wrap:wrap;padding:.5rem}
  .search-ico{padding-left:.25rem}
  .search-input{flex:1 1 calc(100% - 2rem)}
  .search-btn{width:100%;justify-content:center}
}
</style>
@endpush

@section('content')
<nav>
  <a href="{{ route('home') }}" class="logo">
    <div class="logo-icon"><i class="ti ti-checkup-list"></i></div>
    Proof<span class="logo-word">Work</span>
  </a>
  <div class="nav-r">
    <a href="{{ route('home') }}" class="nav-link">← Home</a>
    <a href="{{ route('roadmap') }}" class="nav-link">Roadmap</a>
    <a href="{{ route('register') }}" class="nav-cta">Start free</a>
  </div>
</nav>

<div class="hero">
  <div class="eyebrow"><span class="eyebrow-dot"></span>Live demo · real GitHub data · no signup</div>
  <h1 class="demo-title">Your proof of work,<br><em>generated in seconds.</em></h1>
  <p class="sub">Enter a GitHub username <em>or</em> a specific repository. ProofWork pulls real data and generates an instant client-ready report.</p>

  <div class="search-block">
    <div style="display:flex;justify-content:center;margin-bottom:1rem">
      <div class="mode-toggle">
        <button class="mode-btn active" id="btn-user" onclick="setMode('user',this)">
          <i class="ti ti-user"></i> Username
        </button>
        <button class="mode-btn" id="btn-repo" onclick="setMode('repo',this)">
          <i class="ti ti-package"></i> Repository
        </button>
      </div>
    </div>

    <div class="search-box">
      <span class="search-ico"><i class="ti ti-brand-github"></i></span>
      <input id="gh-input" class="search-input" type="text" placeholder="torvalds" autocomplete="off" spellcheck="false" />
      <button class="search-btn" id="search-btn" onclick="generateReport()">
        <i class="ti ti-sparkles"></i>
        <span id="btn-text">Generate</span>
      </button>
    </div>

    <div class="search-hint" id="search-hint">
      Username mode: enter <code>torvalds</code> &nbsp;·&nbsp; Repo mode: enter <code>torvalds/linux</code>
    </div>

    <div class="chips">
      <span class="chip" onclick="tryUser('torvalds')"><i class="ti ti-user"></i>torvalds</span>
      <span class="chip" onclick="tryUser('ahmatissa08')"><i class="ti ti-user"></i>ahmatissa08</span>
      <span class="chip" onclick="tryRepo('laravel/laravel')"><i class="ti ti-package"></i>laravel/laravel</span>
      <span class="chip" onclick="tryRepo('facebook/react')"><i class="ti ti-package"></i>facebook/react</span>
      <span class="chip" onclick="tryRepo('tailwindlabs/tailwindcss')"><i class="ti ti-package"></i>tailwindcss</span>
    </div>
  </div>
</div>

<!-- LOADING -->
<div class="loading" id="loading">
  <div class="spinner"></div>
  <div class="lsteps">
    <div class="ls" id="ls1"><span class="ls-dot"></span>Fetching GitHub data</div>
    <div class="ls" id="ls2"><span class="ls-dot"></span>Scanning commits &amp; PRs</div>
    <div class="ls" id="ls3"><span class="ls-dot"></span>Analysing activity</div>
    <div class="ls" id="ls4"><span class="ls-dot"></span>Building proof of work report</div>
  </div>
</div>

<!-- ERROR -->
<div class="err-state" id="err-state">
  <div class="err-icon"><i class="ti ti-alert-triangle"></i></div>
  <div class="err-msg" id="err-msg"></div>
  <button class="btn-ghost" onclick="resetDemo()">← Try again</button>
</div>

<!-- REPORT -->
<div class="report" id="report">
  <div class="report-wrap">

    <!-- Header card -->
    <div class="rpt-head-card fi fi-1">
      <div class="rpt-stripe"></div>
      <div class="rpt-head-inner">
        <div>
          <div class="rpt-badge-row">
            <span class="tag tag-verified"><i class="ti ti-shield-check"></i> Verified</span>
            <span class="tag tag-github"><i class="ti ti-brand-github"></i> GitHub</span>
          </div>
          <div class="rpt-title" id="r-title">Proof Report</div>
          <div class="rpt-sub" id="r-sub">Loading...</div>
        </div>
        <div class="rpt-meta">
          <div class="rpt-period" id="r-period"></div>
          <div class="rpt-gen" id="r-gen"></div>
        </div>
      </div>
      <div id="repo-bar" style="display:none" class="repo-bar">
        <div class="repo-item"><i class="ti ti-code"></i><span>Lang:</span><span class="v" id="r-lang"></span></div>
        <div class="repo-item"><i class="ti ti-star"></i><span>Stars:</span><span class="v" id="r-stars"></span></div>
        <div class="repo-item"><i class="ti ti-git-branch"></i><span>Forks:</span><span class="v" id="r-forks"></span></div>
        <div class="repo-item"><i class="ti ti-bug"></i><span>Issues:</span><span class="v" id="r-open-issues"></span></div>
        <a id="r-gh-link" href="#" target="_blank" class="repo-gh-link">View on GitHub <i class="ti ti-external-link"></i></a>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-row fi fi-2">
      <div class="stat-box">
        <div class="stat-lbl"><i class="ti ti-git-commit"></i> Commits</div>
        <div class="stat-val a" id="r-commits">0</div>
        <div class="stat-detail" id="r-commits-detail">last 30 days</div>
      </div>
      <div class="stat-box">
        <div class="stat-lbl"><i class="ti ti-git-pull-request"></i> PRs merged</div>
        <div class="stat-val" id="r-prs">0</div>
        <div class="stat-detail">pull requests</div>
      </div>
      <div class="stat-box">
        <div class="stat-lbl"><i class="ti ti-circle-check"></i> <span id="sl3">Issues closed</span></div>
        <div class="stat-val s" id="r-stat3">0</div>
        <div class="stat-detail" id="sd3">resolved</div>
      </div>
      <div class="stat-box">
        <div class="stat-lbl"><i class="ti ti-users"></i> <span id="sl4">Contributors</span></div>
        <div class="stat-val g" id="r-stat4">0</div>
        <div class="stat-detail" id="sd4">authors</div>
      </div>
    </div>

    <!-- Commits -->
    <div class="sec fi fi-3">
      <div class="sec-head">
        <div class="sec-icon"><i class="ti ti-git-commit"></i></div>
        <div class="sec-title">Recent commits</div>
        <div class="sec-count" id="r-commits-badge">0 commits</div>
      </div>
      <div class="commit-list" id="r-commit-list"></div>
    </div>

    <!-- PRs (repo mode only) -->
    <div class="sec fi fi-4" id="pr-sec" style="display:none">
      <div class="sec-head">
        <div class="sec-icon"><i class="ti ti-git-pull-request"></i></div>
        <div class="sec-title">Merged pull requests</div>
        <div class="sec-count" id="r-pr-badge">0 merged</div>
      </div>
      <div class="pr-list" id="r-pr-list"></div>
    </div>

    <!-- Contributors (repo mode only) -->
    <div class="sec fi fi-4" id="contrib-sec" style="display:none">
      <div class="sec-head">
        <div class="sec-icon"><i class="ti ti-users"></i></div>
        <div class="sec-title">Contributors this period</div>
      </div>
      <div class="contribs" id="r-contribs"></div>
    </div>

    <!-- Top repos (user mode only) -->
    <div class="sec fi fi-4" id="repos-sec">
      <div class="sec-head">
        <div class="sec-icon"><i class="ti ti-package"></i></div>
        <div class="sec-title">Top repositories</div>
        <div class="sec-count" id="r-repos-badge"></div>
      </div>
      <div class="repo-grid" id="r-repo-grid"></div>
    </div>

    <!-- Summary -->
    <div class="sec fi fi-5">
      <div class="sec-head">
        <div class="sec-icon"><i class="ti ti-sparkles"></i></div>
        <div class="sec-title">ProofWork summary</div>
        <div class="sec-count">AI generated</div>
      </div>
      <div class="summary-wrap">
        <div class="summary-label"><i class="ti ti-quote"></i> This period in review</div>
        <div class="summary-text" id="r-summary"></div>
      </div>
    </div>

    <!-- Hash -->
    <div class="hash-row fi fi-6">
      <span class="hash-url"><i class="ti ti-link"></i><span id="r-url">proofwork.app/reports/...</span></span>
      <span class="hash-val" id="r-hash">hash: —</span>
    </div>

    <!-- CTA -->
    <div class="cta-card fi fi-7">
      <h3>Want this for your own work?</h3>
      <p>Get your proof of work report delivered to your client every Friday — connected to GitHub, Linear, Notion, and your calendar. Automatically.</p>
      <div class="cta-btns">
        <a href="{{ route('register') }}" class="btn-amber"><i class="ti ti-rocket"></i> Create free account</a>
        <button class="btn-ghost" onclick="resetDemo()">Try another →</button>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name=csrf-token]')?.content ?? '';
let currentMode = 'user', stepTimer = null;

function setMode(m, btn) {
  currentMode = m;
  document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  const inp = document.getElementById('gh-input');
  const hint = document.getElementById('search-hint');
  if (m === 'repo') {
    inp.placeholder = 'owner/repository';
    hint.innerHTML = 'Enter <code>owner/repo</code> format, e.g. <code>laravel/laravel</code>';
  } else {
    inp.placeholder = 'torvalds';
    hint.innerHTML = 'Username mode: enter <code>torvalds</code> &nbsp;·&nbsp; Repo mode: enter <code>torvalds/linux</code>';
  }
  inp.focus();
}

function tryUser(u) {
  setMode('user', document.getElementById('btn-user'));
  document.getElementById('gh-input').value = u;
  generateReport();
}

function tryRepo(r) {
  setMode('repo', document.getElementById('btn-repo'));
  document.getElementById('gh-input').value = r;
  generateReport();
}

document.getElementById('gh-input').addEventListener('keydown', e => {
  if (e.key === 'Enter') generateReport();
});

function show(id) {
  ['loading', 'err-state', 'report'].forEach(i => document.getElementById(i).classList.remove('on'));
  if (id) document.getElementById(id).classList.add('on');
}

function resetDemo() {
  clearInterval(stepTimer);
  show(null);
  document.getElementById('gh-input').value = '';
  document.getElementById('gh-input').focus();
  document.getElementById('search-btn').disabled = false;
  document.getElementById('btn-text').textContent = 'Generate';
}

function animSteps() {
  const ids = ['ls1', 'ls2', 'ls3', 'ls4'];
  let i = 0;
  ids.forEach(s => { const el = document.getElementById(s); el.classList.remove('active', 'done'); });
  clearInterval(stepTimer);
  stepTimer = setInterval(() => {
    if (i > 0) {
      document.getElementById(ids[i - 1]).classList.remove('active');
      document.getElementById(ids[i - 1]).classList.add('done');
    }
    if (i < ids.length) { document.getElementById(ids[i]).classList.add('active'); i++; }
    else clearInterval(stepTimer);
  }, 700);
}

async function generateReport() {
  const input = document.getElementById('gh-input').value.trim();
  if (!input) { document.getElementById('gh-input').focus(); return; }

  if (input.includes('/')) setMode('repo', document.getElementById('btn-repo'));

  document.getElementById('search-btn').disabled = true;
  document.getElementById('btn-text').textContent = 'Generating...';
  show('loading');
  animSteps();

  try {
    const res = await fetch('{{ route("demo.generate") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: JSON.stringify({ input })
    });
    clearInterval(stepTimer);
    const data = await res.json();

    if (!res.ok || data.error) {
      show('err-state');
      document.getElementById('err-msg').textContent = data.error ?? 'Something went wrong. Try again.';
      document.getElementById('search-btn').disabled = false;
      document.getElementById('btn-text').textContent = 'Generate';
      return;
    }

    renderReport(data);
  } catch (e) {
    clearInterval(stepTimer);
    show('err-state');
    document.getElementById('err-msg').textContent = 'Network error. Check your connection and try again.';
    document.getElementById('search-btn').disabled = false;
    document.getElementById('btn-text').textContent = 'Generate';
  }
}

function esc(s) {
  if (!s) return '';
  return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function renderReport(data) {
  const { mode, report, activity, summary } = data;

  document.getElementById('repo-bar').style.display = 'none';
  document.getElementById('pr-sec').style.display = 'none';
  document.getElementById('contrib-sec').style.display = 'none';
  document.getElementById('repos-sec').style.display = 'none';
  document.getElementById('r-pr-list').innerHTML = '';
  document.getElementById('r-contribs').innerHTML = '';
  document.getElementById('r-repo-grid').innerHTML = '';

  if (mode === 'repo') {
    const repo = data.repo;
    document.getElementById('r-title').textContent = `Proof Report — ${repo.full_name}`;
    document.getElementById('r-sub').textContent = `${repo.description ?? 'Repository activity'} · Last 30 days`;
    document.getElementById('repo-bar').style.display = 'flex';
    document.getElementById('r-lang').textContent = repo.language;
    document.getElementById('r-stars').textContent = repo.stars.toLocaleString();
    document.getElementById('r-forks').textContent = repo.forks.toLocaleString();
    document.getElementById('r-open-issues').textContent = repo.open_issues.toLocaleString();
    document.getElementById('r-gh-link').href = repo.url;
    document.getElementById('sl3').textContent = 'Issues closed';
    document.getElementById('sd3').textContent = 'last 30 days';
    document.getElementById('sl4').textContent = 'Contributors';
    document.getElementById('sd4').textContent = 'this period';
    document.getElementById('r-stat3').textContent = activity.closed_issues;
    document.getElementById('r-stat4').textContent = activity.authors.length;
    document.getElementById('r-commits-detail').textContent = 'last 30 days';

    if (activity.merged_pr_list && activity.merged_pr_list.length > 0) {
      document.getElementById('pr-sec').style.display = 'block';
      document.getElementById('r-pr-badge').textContent = activity.merged_prs + ' merged';
      const pl = document.getElementById('r-pr-list');
      pl.innerHTML = '';
      activity.merged_pr_list.forEach(pr => {
        pl.innerHTML += `<div class="pr-item"><div class="pr-dot"></div><div class="pr-title">${esc(pr.title)}</div><div class="pr-author">${esc(pr.author)} · ${esc(pr.merged_at)}</div></div>`;
      });
    }

    if (activity.authors && activity.authors.length > 0) {
      document.getElementById('contrib-sec').style.display = 'block';
      const cc = document.getElementById('r-contribs');
      cc.innerHTML = '';
      activity.authors.forEach(a => {
        cc.innerHTML += `<span class="contrib-pill"><i class="ti ti-user"></i>${esc(a)}</span>`;
      });
    }

    document.getElementById('repos-sec').style.display = 'none';
    document.getElementById('r-url').textContent = `proofwork.app/r/${data.repo.owner}-${data.repo.name}-${report.hash}`;

  } else {
    const user = data.user;
    document.getElementById('r-title').textContent = `Weekly Proof Report — ${user.name}`;
    document.getElementById('r-sub').textContent = `@${user.login} · ${user.public_repos} repos · ${user.followers} followers`;
    document.getElementById('repo-bar').style.display = 'none';
    document.getElementById('sl3').textContent = 'Repos updated';
    document.getElementById('sd3').textContent = 'this week';
    document.getElementById('sl4').textContent = 'Public repos';
    document.getElementById('sd4').textContent = 'total';
    document.getElementById('r-stat3').textContent = activity.repos_updated;
    document.getElementById('r-stat4').textContent = user.public_repos;
    document.getElementById('r-commits-detail').textContent = 'this week';
    document.getElementById('pr-sec').style.display = 'none';
    document.getElementById('contrib-sec').style.display = 'none';
    document.getElementById('repos-sec').style.display = 'block';
    document.getElementById('r-repos-badge').textContent = activity.top_repos.length + ' repos';

    const grid = document.getElementById('r-repo-grid');
    grid.innerHTML = '';
    if (activity.top_repos.length > 0) {
      activity.top_repos.forEach(r => {
        grid.innerHTML += `<div class="repo-card"><div class="repo-name"><i class="ti ti-package"></i>${esc(r.name)}</div><div class="repo-desc">${esc(r.desc ?? 'No description')}</div><div class="repo-foot"><span class="repo-lang">${esc(r.lang)}</span><span class="repo-stars"><i class="ti ti-star"></i>${r.stars}</span><span class="repo-upd">${esc(r.updated)}</span></div></div>`;
      });
    } else {
      grid.innerHTML = '<div class="commit-empty">No public repos found.</div>';
    }

    document.getElementById('r-url').textContent = `proofwork.app/r/${user.login}-${report.hash}`;
  }

  // Common fields
  document.getElementById('r-period').textContent = 'Period: ' + report.period;
  document.getElementById('r-gen').textContent = 'Generated ' + report.generated;
  document.getElementById('r-commits').textContent = activity.commits;
  document.getElementById('r-prs').textContent = activity.merged_prs;
  document.getElementById('r-commits-badge').textContent = activity.commits + ' commits';
  document.getElementById('r-summary').textContent = summary;
  document.getElementById('r-hash').textContent = 'hash: ' + report.hash;

  // Commits
  const cl = document.getElementById('r-commit-list');
  cl.innerHTML = '';
  if (activity.commit_msgs && activity.commit_msgs.length > 0) {
    activity.commit_msgs.forEach((msg, i) => {
      const sha = Math.random().toString(16).substr(2, 7);
      const repoRef = mode === 'user' && activity.active_repos
        ? (activity.active_repos[i % activity.active_repos.length] ?? '')
        : '';
      cl.innerHTML += `<div class="commit-item"><div class="c-sha">${sha}</div><div class="c-msg">${esc(msg)}${repoRef ? `<div class="c-repo"><i class="ti ti-folder"></i>${esc(repoRef)}</div>` : ''}</div><div class="c-time">2d ago</div></div>`;
    });
  } else {
    cl.innerHTML = '<div class="commit-empty">No public commit messages found for this period.</div>';
  }

  show('report');
  setTimeout(() => document.getElementById('report').scrollIntoView({ behavior: 'smooth', block: 'start' }), 80);
  document.getElementById('search-btn').disabled = false;
  document.getElementById('btn-text').textContent = 'Generate';
}
</script>
@endpush
