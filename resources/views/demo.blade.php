@extends('layouts.app')
@section('title', 'Live Demo — ProofWork')
@section('og_title', 'ProofWork Live Demo — See your proof of work in 5 seconds')
@section('og_description', 'Enter a GitHub username or repository and ProofWork generates a real client-ready report instantly.')

@push('styles')
<style>
:root{--bg:#0c0c0e;--surface:#131316;--surface2:#18181c;--border:#242428;--border2:#2e2e34;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--coral:#e85c3a;--sky:#4a9eff;--green:#27c93f;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6;overflow-x:hidden}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-thumb{background:var(--border2);border-radius:2px}

nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:1rem 2.5rem;border-bottom:1px solid var(--border);background:rgba(12,12,14,.92);backdrop-filter:blur(20px)}
.logo{font-family:var(--serif);font-size:1.2rem;font-style:italic;color:var(--ink);text-decoration:none}
.logo-word{font-family:var(--sans);font-style:normal;font-weight:300;font-size:1.1rem;letter-spacing:-.02em}
.nav-right{display:flex;gap:.5rem;align-items:center}
.nav-link{font-size:.8rem;color:var(--ink3);text-decoration:none;padding:.4rem .8rem;border-radius:4px;transition:color .2s,background .2s}
.nav-link:hover{color:var(--ink);background:rgba(255,255,255,.04)}
.nav-cta{background:var(--amber);color:#000;font-weight:600;font-size:.78rem;padding:.45rem 1rem;border-radius:4px;text-decoration:none;transition:opacity .15s}
.nav-cta:hover{opacity:.88}

/* HERO */
.demo-hero{padding:7.5rem 2.5rem 2.5rem;text-align:center;position:relative;overflow:hidden}
.demo-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 40%,rgba(232,163,37,.05) 0%,transparent 70%);pointer-events:none}
.demo-eyebrow{display:inline-flex;align-items:center;gap:.6rem;font-family:var(--mono);font-size:.62rem;color:var(--ink3);letter-spacing:.14em;text-transform:uppercase;margin-bottom:1.4rem;background:rgba(255,255,255,.03);border:1px solid var(--border2);border-radius:30px;padding:.35rem .9rem}
.eyebrow-dot{width:5px;height:5px;background:var(--amber);border-radius:50%;animation:blink 2s ease-in-out infinite;flex-shrink:0}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.25}}
.demo-title{font-family:var(--serif);font-size:clamp(2.2rem,5vw,4rem);font-style:italic;font-weight:400;letter-spacing:-.03em;margin-bottom:.7rem}
.demo-title em{color:var(--amber)}
.demo-subtitle{color:var(--ink2);font-size:.95rem;max-width:52ch;margin:0 auto 2rem;line-height:1.65}

/* MODE TOGGLE */
.mode-toggle{display:inline-flex;background:var(--surface);border:1px solid var(--border2);border-radius:8px;padding:3px;margin:0 auto 1.5rem;gap:2px}
.mode-btn{font-family:var(--mono);font-size:.65rem;letter-spacing:.06em;padding:.4rem 1rem;border-radius:5px;cursor:pointer;border:none;background:transparent;color:var(--ink3);transition:all .2s}
.mode-btn.active{background:var(--surface2);color:var(--ink);box-shadow:0 1px 4px rgba(0,0,0,.3)}

/* SEARCH */
.search-wrap{max-width:560px;margin:0 auto 1rem}
.search-inner{display:flex;gap:.4rem;background:var(--surface);border:1px solid var(--border2);border-radius:8px;padding:.45rem .45rem .45rem 1rem;transition:border-color .2s,box-shadow .2s}
.search-inner:focus-within{border-color:var(--amber);box-shadow:0 0 0 3px rgba(232,163,37,.1)}
.search-icon{color:var(--ink3);display:flex;align-items:center;flex-shrink:0}
.search-input{flex:1;background:transparent;border:none;color:var(--ink);font-family:var(--mono);font-size:.88rem;outline:none;padding:.3rem .5rem}
.search-input::placeholder{color:var(--ink3)}
.search-btn{background:var(--amber);color:#000;border:none;padding:.6rem 1.4rem;border-radius:6px;font-family:var(--sans);font-size:.82rem;font-weight:600;cursor:pointer;transition:opacity .15s;white-space:nowrap}
.search-btn:hover{opacity:.88}
.search-btn:disabled{opacity:.45;cursor:not-allowed}
.search-hint{font-family:var(--mono);font-size:.6rem;color:var(--ink3);text-align:center;margin-bottom:.6rem}
.search-hint code{background:var(--surface2);border:1px solid var(--border2);padding:.1rem .35rem;border-radius:3px;color:var(--amber)}

/* EXAMPLES */
.examples{display:flex;gap:.4rem;justify-content:center;flex-wrap:wrap;margin-bottom:.5rem}
.example-chip{background:var(--surface2);border:1px solid var(--border2);color:var(--ink3);font-family:var(--mono);font-size:.6rem;padding:.22rem .65rem;border-radius:20px;cursor:pointer;transition:all .2s}
.example-chip:hover{border-color:var(--amber);color:var(--amber)}

/* LOADING */
.loading-state{display:none;text-align:center;padding:3.5rem 2rem}
.loading-state.visible{display:block}
.spinner{width:34px;height:34px;border:2px solid var(--border2);border-top-color:var(--amber);border-radius:50%;animation:spin .7s linear infinite;margin:0 auto 1.2rem}
@keyframes spin{to{transform:rotate(360deg)}}
.loading-steps{display:flex;flex-direction:column;gap:.35rem;font-family:var(--mono);font-size:.65rem;color:var(--ink3)}
.lstep{opacity:.3;transition:opacity .25s}.lstep.active{opacity:1;color:var(--amber)}.lstep.done{opacity:.6;color:var(--green)}
.lstep.done::before{content:'✓ '}

/* ERROR */
.error-state{display:none;text-align:center;padding:3rem 2rem;max-width:460px;margin:0 auto}
.error-state.visible{display:block}
.error-icon{font-size:1.8rem;margin-bottom:.8rem}
.error-msg{font-family:var(--mono);font-size:.78rem;color:var(--coral);margin-bottom:1.2rem;line-height:1.55}
.btn-ghost{background:transparent;color:var(--ink2);border:1px solid var(--border2);padding:.65rem 1.4rem;font-family:var(--sans);font-size:.8rem;border-radius:5px;cursor:pointer;transition:all .2s;display:inline-block;text-decoration:none}
.btn-ghost:hover{color:var(--ink);border-color:var(--ink3)}

/* REPORT */
.report-state{display:none}
.report-state.visible{display:block}
.report-wrap{max-width:820px;margin:0 auto;padding:0 2.5rem 6rem}

/* Report header */
.rpt-card{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:1.2rem}
.rpt-top{height:3px;background:linear-gradient(90deg,var(--amber),var(--sky))}
.rpt-header{display:flex;align-items:center;justify-content:space-between;padding:1.2rem 1.5rem;border-bottom:1px solid var(--border);flex-wrap:wrap;gap:.8rem}
.rpt-title-group{}
.rpt-title{font-size:.92rem;font-weight:600}
.rpt-sub{font-family:var(--mono);font-size:.6rem;color:var(--ink3);margin-top:.15rem}
.rpt-meta{text-align:right}
.rpt-period{font-family:var(--mono);font-size:.62rem;color:var(--ink3)}
.rpt-gen{font-family:var(--mono);font-size:.58rem;color:var(--ink3);opacity:.5;margin-top:.15rem}
.verified-pill{display:inline-flex;align-items:center;gap:.35rem;background:rgba(39,201,63,.08);border:1px solid rgba(39,201,63,.18);color:var(--green);font-family:var(--mono);font-size:.55rem;padding:.2rem .55rem;border-radius:20px;margin-top:.3rem}
.verified-pill::before{content:'';width:5px;height:5px;background:var(--green);border-radius:50%}

/* Repo info bar */
.repo-info-bar{display:flex;align-items:center;gap:1.5rem;padding:.9rem 1.5rem;background:var(--surface2);border-bottom:1px solid var(--border);flex-wrap:wrap}
.repo-info-item{display:flex;align-items:center;gap:.4rem;font-family:var(--mono);font-size:.62rem;color:var(--ink3)}
.repo-info-item .val{color:var(--ink2)}
.repo-lang-dot::before{content:'● ';color:var(--amber)}

/* Stats */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--border);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:1.2rem}
.stat-box{background:var(--surface);padding:1.1rem 1.3rem}
.stat-lbl{font-family:var(--mono);font-size:.56rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.3rem}
.stat-val{font-family:var(--serif);font-size:1.8rem;font-style:italic;color:var(--ink);line-height:1}
.stat-val.amber{color:var(--amber)}.stat-val.sky{color:var(--sky)}.stat-val.green{color:var(--green)}
.stat-detail{font-family:var(--mono);font-size:.55rem;color:var(--ink3);margin-top:.2rem}

/* Section */
.rpt-section{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:1.2rem}
.rpt-section-header{display:flex;align-items:center;gap:.65rem;padding:.85rem 1.3rem;border-bottom:1px solid var(--border)}
.s-icon{width:24px;height:24px;border:1px solid var(--border2);border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:.72rem;flex-shrink:0}
.s-title{font-size:.8rem;font-weight:600}
.s-badge{margin-left:auto;font-family:var(--mono);font-size:.56rem;color:var(--ink3);background:var(--surface2);border:1px solid var(--border2);padding:.12rem .45rem;border-radius:3px}

/* Commits */
.commit-list{padding:.7rem 1.3rem}
.commit-item{display:flex;gap:.8rem;padding:.6rem 0;border-bottom:1px solid rgba(255,255,255,.03);align-items:flex-start}
.commit-item:last-child{border-bottom:none}
.cdot{width:7px;height:7px;border-radius:50%;background:var(--amber);flex-shrink:0;margin-top:.4rem;opacity:.7}
.cmsg{font-family:var(--mono);font-size:.7rem;color:var(--ink2);line-height:1.4;flex:1}
.cmsg .repo-ref{color:var(--sky);font-size:.6rem;display:block;margin-top:.1rem;opacity:.7}
.cempty{padding:1rem 1.3rem;font-family:var(--mono);font-size:.7rem;color:var(--ink3)}

/* PR list */
.pr-list{padding:.7rem 1.3rem}
.pr-item{display:flex;gap:.8rem;padding:.6rem 0;border-bottom:1px solid rgba(255,255,255,.03);align-items:center}
.pr-item:last-child{border-bottom:none}
.pr-merged-dot{width:7px;height:7px;border-radius:50%;background:var(--green);flex-shrink:0;opacity:.8}
.pr-title{font-family:var(--mono);font-size:.7rem;color:var(--ink2);flex:1;line-height:1.4}
.pr-meta{font-family:var(--mono);font-size:.58rem;color:var(--ink3);white-space:nowrap}

/* Contributors */
.contributors{display:flex;gap:.5rem;flex-wrap:wrap;padding:.9rem 1.3rem}
.contributor{display:inline-flex;align-items:center;gap:.4rem;font-family:var(--mono);font-size:.62rem;color:var(--ink2);background:var(--surface2);border:1px solid var(--border2);padding:.25rem .6rem;border-radius:20px}
.contributor::before{content:'👤';font-size:.6rem}

/* Repo cards */
.repo-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:var(--border)}
.repo-card{background:var(--surface);padding:1.1rem 1.3rem;transition:background .15s}
.repo-card:hover{background:var(--surface2)}
.repo-name{font-size:.8rem;font-weight:500;color:var(--sky);margin-bottom:.25rem}
.repo-desc{font-size:.7rem;color:var(--ink3);line-height:1.4;margin-bottom:.5rem;min-height:2em}
.repo-meta{display:flex;gap:.7rem;align-items:center}
.repo-lang{font-family:var(--mono);font-size:.58rem;color:var(--ink3)}
.repo-lang::before{content:'● ';color:var(--amber)}
.repo-stars{font-family:var(--mono);font-size:.58rem;color:var(--ink3)}
.repo-updated{font-family:var(--mono);font-size:.58rem;color:var(--ink3);margin-left:auto;opacity:.55}

/* Summary */
.summary-box{padding:1.2rem 1.3rem;background:rgba(232,163,37,.03)}
.summary-label{font-family:var(--mono);font-size:.56rem;color:var(--amber);letter-spacing:.12em;text-transform:uppercase;opacity:.7;margin-bottom:.4rem}
.summary-text{font-size:.88rem;color:var(--ink2);line-height:1.7;font-style:italic}

/* Hash bar */
.hash-bar{display:flex;align-items:center;justify-content:space-between;padding:.65rem 1.3rem;background:var(--surface2);border:1px solid var(--border);border-radius:8px;margin-bottom:1.8rem}
.hash-url{font-family:var(--mono);font-size:.6rem;color:var(--ink3)}
.hash-val{font-family:var(--mono);font-size:.6rem;color:var(--ink3);opacity:.35}

/* CTA */
.report-cta{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:2rem;text-align:center}
.report-cta h3{font-family:var(--serif);font-size:1.6rem;font-style:italic;font-weight:400;margin-bottom:.5rem}
.report-cta p{color:var(--ink2);font-size:.86rem;margin-bottom:1.4rem;line-height:1.65}
.cta-btns{display:flex;gap:.7rem;justify-content:center;flex-wrap:wrap}
.btn-amber{background:var(--amber);color:#000;border:none;padding:.8rem 1.8rem;font-family:var(--sans);font-size:.85rem;font-weight:700;border-radius:5px;cursor:pointer;text-decoration:none;display:inline-block;transition:opacity .15s}
.btn-amber:hover{opacity:.88}

/* ANIM */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
.fade-in{animation:fadeUp .45s ease both}

@media(max-width:768px){
  nav{padding:1rem 1.2rem}.nav-link{display:none}
  .demo-hero{padding:6.5rem 1.2rem 2rem}
  .report-wrap{padding:0 1.2rem 4rem}
  .stats-row{grid-template-columns:1fr 1fr}
  .repo-grid{grid-template-columns:1fr}
}
</style>
@endpush

@section('content')
<nav>
  <a href="{{ route('home') }}" class="logo">Proof<span class="logo-word">Work</span></a>
  <div class="nav-right">
    <a href="{{ route('home') }}" class="nav-link">← Home</a>
    <a href="{{ route('roadmap') }}" class="nav-link">Roadmap</a>
    <a href="{{ route('home') }}#waitlist" class="nav-cta">Join waitlist</a>
  </div>
</nav>

<div class="demo-hero">
  <div class="demo-eyebrow"><span class="eyebrow-dot"></span>Live demo · real GitHub data · no signup</div>
  <h1 class="demo-title">Your proof of work,<br><em>generated in seconds.</em></h1>
  <p class="demo-subtitle">Enter a GitHub username <em>or</em> a specific repository. ProofWork pulls real data and generates an instant client-ready report.</p>

  <!-- Mode toggle -->
  <div id="mode-toggle" class="mode-toggle">
    <button class="mode-btn active" onclick="setMode('user', this)">👤 Username</button>
    <button class="mode-btn" onclick="setMode('repo', this)">📦 Repository</button>
  </div>

  <!-- Search -->
  <div class="search-wrap">
    <div class="search-inner">
      <span class="search-icon">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
      </span>
      <input type="text" id="gh-input" class="search-input" placeholder="torvalds" autocomplete="off" spellcheck="false" />
      <button class="search-btn" id="search-btn" onclick="generateReport()">
        <span id="btn-text">Generate →</span>
      </button>
    </div>
    <div class="search-hint" id="search-hint">
      Username mode: enter <code>torvalds</code> &nbsp;·&nbsp; Repo mode: enter <code>torvalds/linux</code>
    </div>
  </div>

  <!-- Example chips -->
  <div class="examples" id="example-chips">
    <span class="example-chip" onclick="tryUser('torvalds')">torvalds</span>
    <span class="example-chip" onclick="tryUser('ahmatissa08')">ahmatissa08</span>
    <span class="example-chip" onclick="tryRepo('laravel/laravel')">laravel/laravel</span>
    <span class="example-chip" onclick="tryRepo('facebook/react')">facebook/react</span>
    <span class="example-chip" onclick="tryRepo('tailwindlabs/tailwindcss')">tailwindlabs/tailwindcss</span>
  </div>
</div>

<!-- LOADING -->
<div class="loading-state" id="loading-state">
  <div class="spinner"></div>
  <div class="loading-steps">
    <div class="lstep" id="lstep-1">Fetching GitHub data...</div>
    <div class="lstep" id="lstep-2">Scanning commits & PRs...</div>
    <div class="lstep" id="lstep-3">Analysing activity...</div>
    <div class="lstep" id="lstep-4">Building proof of work report...</div>
  </div>
</div>

<!-- ERROR -->
<div class="error-state" id="error-state">
  <div class="error-icon">⚠</div>
  <div class="error-msg" id="error-msg"></div>
  <button class="btn-ghost" onclick="resetDemo()">← Try again</button>
</div>

<!-- REPORT -->
<div class="report-state" id="report-state">
  <div class="report-wrap">

    <!-- Header card -->
    <div class="rpt-card fade-in">
      <div class="rpt-top"></div>
      <div class="rpt-header">
        <div class="rpt-title-group">
          <div class="rpt-title" id="r-title">Weekly Proof Report</div>
          <div class="rpt-sub" id="r-sub">Loading...</div>
          <div class="verified-pill">VERIFIED · GitHub</div>
        </div>
        <div class="rpt-meta">
          <div class="rpt-period" id="r-period"></div>
          <div class="rpt-gen" id="r-gen"></div>
        </div>
      </div>
      <!-- Repo info bar (repo mode only) -->
      <div id="repo-info-bar" style="display:none" class="repo-info-bar">
        <div class="repo-info-item">
          <span>Language:</span>
          <span class="val repo-lang-dot" id="r-lang"></span>
        </div>
        <div class="repo-info-item">
          <span>Stars:</span>
          <span class="val" id="r-stars"></span>
        </div>
        <div class="repo-info-item">
          <span>Forks:</span>
          <span class="val" id="r-forks"></span>
        </div>
        <div class="repo-info-item">
          <span>Open issues:</span>
          <span class="val" id="r-open-issues"></span>
        </div>
        <a id="r-github-link" href="#" target="_blank" style="font-family:var(--mono);font-size:.62rem;color:var(--sky);margin-left:auto;text-decoration:none">View on GitHub →</a>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-row fade-in">
      <div class="stat-box">
        <div class="stat-lbl">Commits</div>
        <div class="stat-val amber" id="r-commits">0</div>
        <div class="stat-detail" id="r-commits-detail">last 30 days</div>
      </div>
      <div class="stat-box">
        <div class="stat-lbl">PRs merged</div>
        <div class="stat-val" id="r-prs">0</div>
        <div class="stat-detail">pull requests</div>
      </div>
      <div class="stat-box">
        <div class="stat-lbl" id="stat3-label">Issues closed</div>
        <div class="stat-val sky" id="r-issues">0</div>
        <div class="stat-detail" id="stat3-detail">resolved</div>
      </div>
      <div class="stat-box">
        <div class="stat-lbl" id="stat4-label">Contributors</div>
        <div class="stat-val green" id="r-stat4">0</div>
        <div class="stat-detail" id="stat4-detail">authors</div>
      </div>
    </div>

    <!-- Commits -->
    <div class="rpt-section fade-in">
      <div class="rpt-section-header">
        <div class="s-icon">⌥</div>
        <div class="s-title">Recent commits</div>
        <div class="s-badge" id="r-commits-badge">0 commits</div>
      </div>
      <div class="commit-list" id="r-commit-list"></div>
    </div>

    <!-- PRs (repo mode) -->
    <div class="rpt-section fade-in" id="pr-section" style="display:none">
      <div class="rpt-section-header">
        <div class="s-icon">◈</div>
        <div class="s-title">Merged pull requests</div>
        <div class="s-badge" id="r-pr-badge">0 merged</div>
      </div>
      <div class="pr-list" id="r-pr-list"></div>
    </div>

    <!-- Contributors (repo mode) -->
    <div class="rpt-section fade-in" id="contributors-section" style="display:none">
      <div class="rpt-section-header">
        <div class="s-icon">👥</div>
        <div class="s-title">Contributors this period</div>
      </div>
      <div class="contributors" id="r-contributors"></div>
    </div>

    <!-- Top repos (user mode) -->
    <div class="rpt-section fade-in" id="repos-section">
      <div class="rpt-section-header">
        <div class="s-icon">◈</div>
        <div class="s-title">Top repositories</div>
        <div class="s-badge" id="r-repos-badge"></div>
      </div>
      <div class="repo-grid" id="r-repo-grid"></div>
    </div>

    <!-- Summary -->
    <div class="rpt-section fade-in">
      <div class="rpt-section-header">
        <div class="s-icon">✦</div>
        <div class="s-title">ProofWork summary</div>
        <div class="s-badge">AI generated</div>
      </div>
      <div class="summary-box">
        <div class="summary-label">This period in review</div>
        <div class="summary-text" id="r-summary"></div>
      </div>
    </div>

    <!-- Hash -->
    <div class="hash-bar fade-in">
      <span class="hash-url" id="r-url">proofwork.app/reports/...</span>
      <span class="hash-val" id="r-hash">hash: ...</span>
    </div>

    <!-- CTA -->
    <div class="report-cta fade-in" id="report-cta">
      <h3>Want this for your own work?</h3>
      <p>Get your proof of work report delivered to your client every Friday — connected to GitHub, Linear, Notion, and your calendar. Automatically.</p>
      <div class="cta-btns">
        <a href="{{ route('home') }}#waitlist" class="btn-amber">Join the waitlist →</a>
        <button class="btn-ghost" onclick="resetDemo()">Try another repo</button>
      </div>
    </div>

  </div>
</div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name=csrf-token]')?.content ?? '';
let currentMode = 'user';

function setMode(mode, btn) {
  currentMode = mode;
  document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  const input = document.getElementById('gh-input');
  const hint  = document.getElementById('search-hint');
  if (mode === 'repo') {
    input.placeholder = 'owner/repository';
    hint.innerHTML = 'Enter <code>owner/repo</code> format, e.g. <code>laravel/laravel</code>';
  } else {
    input.placeholder = 'github username';
    hint.innerHTML = 'Enter a GitHub username, e.g. <code>torvalds</code>';
  }
  input.focus();
}

function tryUser(u) {
  setMode('user', document.querySelectorAll('.mode-btn')[0]);
  document.getElementById('gh-input').value = u;
  generateReport();
}

function tryRepo(r) {
  setMode('repo', document.querySelectorAll('.mode-btn')[1]);
  document.getElementById('gh-input').value = r;
  generateReport();
}

document.getElementById('gh-input').addEventListener('keydown', e => {
  if (e.key === 'Enter') generateReport();
});

function setState(state) {
  ['loading-state','error-state','report-state'].forEach(id => {
    document.getElementById(id).classList.remove('visible');
  });
  if (state) document.getElementById(state).classList.add('visible');
}

function resetDemo() {
  setState(null);
  document.getElementById('gh-input').value = '';
  document.getElementById('gh-input').focus();
  document.getElementById('search-btn').disabled = false;
  document.getElementById('btn-text').textContent = 'Generate →';
}

let stepTimer = null;
function animateSteps() {
  const steps = ['lstep-1','lstep-2','lstep-3','lstep-4'];
  let i = 0;
  steps.forEach(s => { const el = document.getElementById(s); el.classList.remove('active','done'); });
  clearInterval(stepTimer);
  stepTimer = setInterval(() => {
    if (i > 0) { document.getElementById(steps[i-1]).classList.remove('active'); document.getElementById(steps[i-1]).classList.add('done'); }
    if (i < steps.length) { document.getElementById(steps[i]).classList.add('active'); i++; }
    else clearInterval(stepTimer);
  }, 650);
}

async function generateReport() {
  const input = document.getElementById('gh-input').value.trim();
  if (!input) { document.getElementById('gh-input').focus(); return; }

  // Auto-detect mode from input
  if (input.includes('/')) {
    setMode('repo', document.querySelectorAll('.mode-btn')[1]);
  }

  document.getElementById('search-btn').disabled = true;
  document.getElementById('btn-text').textContent = 'Generating...';
  setState('loading-state');
  animateSteps();

  try {
    const res  = await fetch('{{ route("demo.generate") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: JSON.stringify({ input })
    });
    clearInterval(stepTimer);
    const data = await res.json();

    if (!res.ok || data.error) {
      setState('error-state');
      document.getElementById('error-msg').textContent = data.error ?? 'Something went wrong. Try again.';
      document.getElementById('search-btn').disabled = false;
      document.getElementById('btn-text').textContent = 'Generate →';
      return;
    }

    renderReport(data);

  } catch(e) {
    clearInterval(stepTimer);
    setState('error-state');
    document.getElementById('error-msg').textContent = 'Network error. Check your connection and try again.';
    document.getElementById('search-btn').disabled = false;
    document.getElementById('btn-text').textContent = 'Generate →';
  }
}

function renderReport(data) {
  const { mode, report, activity, summary } = data;

  // Header
  if (mode === 'repo') {
    const repo = data.repo;
    document.getElementById('r-title').textContent = `Proof Report — ${repo.full_name}`;
    document.getElementById('r-sub').textContent = `${repo.description ?? 'Repository activity'} · Last 30 days`;
    // Repo info bar
    document.getElementById('repo-info-bar').style.display = 'flex';
    document.getElementById('r-lang').textContent = repo.language;
    document.getElementById('r-stars').textContent = repo.stars.toLocaleString();
    document.getElementById('r-forks').textContent = repo.forks.toLocaleString();
    document.getElementById('r-open-issues').textContent = repo.open_issues.toLocaleString();
    document.getElementById('r-github-link').href = repo.url;
    // Stats
    document.getElementById('stat3-label').textContent = 'Issues closed';
    document.getElementById('stat3-detail').textContent = 'last 30 days';
    document.getElementById('stat4-label').textContent = 'Contributors';
    document.getElementById('stat4-detail').textContent = 'this period';
    document.getElementById('r-issues').textContent = activity.closed_issues;
    document.getElementById('r-stat4').textContent = activity.authors.length;
    document.getElementById('r-commits-detail').textContent = 'last 30 days';
    // PRs section
    if (activity.merged_pr_list && activity.merged_pr_list.length > 0) {
      document.getElementById('pr-section').style.display = 'block';
      document.getElementById('r-pr-badge').textContent = activity.merged_prs + ' merged';
      const prList = document.getElementById('r-pr-list');
      prList.innerHTML = '';
      activity.merged_pr_list.forEach(pr => {
        prList.innerHTML += `<div class="pr-item"><div class="pr-merged-dot"></div><div class="pr-title">${esc(pr.title)}</div><div class="pr-meta">${esc(pr.author)} · ${esc(pr.merged_at)}</div></div>`;
      });
    }
    // Contributors
    if (activity.authors && activity.authors.length > 0) {
      document.getElementById('contributors-section').style.display = 'block';
      const contribs = document.getElementById('r-contributors');
      contribs.innerHTML = '';
      activity.authors.forEach(a => {
        contribs.innerHTML += `<span class="contributor">${esc(a)}</span>`;
      });
    }
    // Hide user repos section
    document.getElementById('repos-section').style.display = 'none';
    // URL
    document.getElementById('r-url').textContent = `proofwork.app/r/${data.repo.owner}-${data.repo.name}-${report.hash}`;
  } else {
    const user = data.user;
    document.getElementById('r-title').textContent = `Weekly Proof Report — ${user.name}`;
    document.getElementById('r-sub').textContent = `@${user.login} · ${user.public_repos} repos · ${user.followers} followers`;
    document.getElementById('repo-info-bar').style.display = 'none';
    // Stats
    document.getElementById('stat3-label').textContent = 'Repos updated';
    document.getElementById('stat3-detail').textContent = 'this week';
    document.getElementById('stat4-label').textContent = 'Public repos';
    document.getElementById('stat4-detail').textContent = 'total';
    document.getElementById('r-issues').textContent = activity.repos_updated;
    document.getElementById('r-stat4').textContent = user.public_repos;
    document.getElementById('r-commits-detail').textContent = 'this week';
    document.getElementById('pr-section').style.display = 'none';
    document.getElementById('contributors-section').style.display = 'none';
    // Top repos
    document.getElementById('repos-section').style.display = 'block';
    document.getElementById('r-repos-badge').textContent = activity.top_repos.length + ' repos';
    const grid = document.getElementById('r-repo-grid');
    grid.innerHTML = '';
    if (activity.top_repos.length > 0) {
      activity.top_repos.forEach(r => {
        grid.innerHTML += `<div class="repo-card"><div class="repo-name">${esc(r.name)}</div><div class="repo-desc">${esc(r.desc ?? 'No description')}</div><div class="repo-meta"><span class="repo-lang">${esc(r.lang)}</span><span class="repo-stars">★ ${r.stars}</span><span class="repo-updated">${esc(r.updated)}</span></div></div>`;
      });
    } else {
      grid.innerHTML = '<div class="cempty" style="padding:1rem">No public repos found.</div>';
    }
    document.getElementById('r-url').textContent = `proofwork.app/r/${user.login}-${report.hash}`;
  }

  // Common
  document.getElementById('r-period').textContent = 'Period: ' + report.period;
  document.getElementById('r-gen').textContent = 'Generated ' + report.generated;
  document.getElementById('r-commits').textContent = activity.commits;
  document.getElementById('r-prs').textContent = activity.merged_prs;
  document.getElementById('r-commits-badge').textContent = activity.commits + ' commits';
  document.getElementById('r-summary').textContent = summary;
  document.getElementById('r-hash').textContent = 'hash: ' + report.hash;

  // Commits
  const commitList = document.getElementById('r-commit-list');
  commitList.innerHTML = '';
  if (activity.commit_msgs && activity.commit_msgs.length > 0) {
    activity.commit_msgs.forEach((msg, i) => {
      const repoRef = mode === 'user' && activity.active_repos
        ? (activity.active_repos[i % activity.active_repos.length] ?? '')
        : '';
      commitList.innerHTML += `<div class="commit-item"><div class="cdot"></div><div class="cmsg">${esc(msg)}${repoRef ? `<span class="repo-ref">${esc(repoRef)}</span>` : ''}</div></div>`;
    });
  } else {
    commitList.innerHTML = '<div class="cempty">No public commit messages found for this period.</div>';
  }

  setState('report-state');
  setTimeout(() => {
    document.getElementById('report-state').scrollIntoView({ behavior: 'smooth', block: 'start' });
  }, 100);
  document.getElementById('search-btn').disabled = false;
  document.getElementById('btn-text').textContent = 'Generate →';
}

function esc(str) {
  if (!str) return '';
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@endpush
