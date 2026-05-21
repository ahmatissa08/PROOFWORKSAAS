@extends('layouts.guest')
@section('title', 'About — ProofWork')

@push('styles')
<style>
:root{--bg:#0c0c0e;--surface:#131316;--surface2:#18181c;--border:#242428;--border2:#2e2e34;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--coral:#e85c3a;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.7}
::-webkit-scrollbar{width:4px}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:2px}
nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:1.1rem 2.5rem;border-bottom:1px solid var(--border);background:rgba(12,12,14,.9);backdrop-filter:blur(20px)}
.logo{font-family:var(--serif);font-size:1.25rem;font-style:italic;color:var(--ink);text-decoration:none}
.logo-word{font-family:var(--sans);font-style:normal;font-weight:300;font-size:1.2rem;letter-spacing:-.02em}
.nav-right{display:flex;gap:.6rem;align-items:center}
.nav-link{font-size:.8rem;color:var(--ink3);text-decoration:none;padding:.45rem .85rem;border-radius:4px;transition:color .2s,background .2s}
.nav-link:hover{color:var(--ink);background:rgba(255,255,255,.04)}
.nav-cta{background:var(--amber);color:#000;font-weight:600;font-size:.78rem;padding:.5rem 1.1rem;border-radius:4px;text-decoration:none;transition:opacity .15s}
.nav-cta:hover{opacity:.88}

.page-wrap{max-width:720px;margin:0 auto;padding:8rem 2.5rem 6rem}

/* HERO */
.about-hero{margin-bottom:4rem}
.about-eyebrow{font-family:var(--mono);font-size:.62rem;color:var(--ink3);letter-spacing:.14em;text-transform:uppercase;margin-bottom:1rem}
.about-title{font-family:var(--serif);font-size:clamp(2.5rem,6vw,4rem);font-style:italic;font-weight:400;letter-spacing:-.03em;margin-bottom:1.2rem;line-height:1.05}
.about-title em{color:var(--amber)}

/* FOUNDER CARD */
.founder-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:3rem}
.founder-card-top{height:3px;background:linear-gradient(90deg,var(--amber),#4a9eff)}
.founder-inner{padding:2rem;display:flex;gap:1.5rem;align-items:flex-start}
.founder-avatar{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--amber),#f1c135);display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-size:1.8rem;font-style:italic;color:#000;flex-shrink:0;font-weight:400}
.founder-info{}
.founder-name{font-size:1rem;font-weight:600;margin-bottom:.2rem}
.founder-role{font-family:var(--mono);font-size:.65rem;color:var(--ink3);letter-spacing:.08em;margin-bottom:.8rem}
.founder-bio{font-size:.88rem;color:var(--ink2);line-height:1.7}
.founder-links{display:flex;gap:.6rem;margin-top:1rem;flex-wrap:wrap}
.founder-link{display:inline-flex;align-items:center;gap:.4rem;font-family:var(--mono);font-size:.62rem;color:var(--ink3);text-decoration:none;padding:.3rem .7rem;border:1px solid var(--border2);border-radius:4px;transition:all .2s}
.founder-link:hover{border-color:var(--amber);color:var(--amber)}

/* STORY */
.story-section{margin-bottom:3rem}
.story-section p{font-size:.92rem;color:var(--ink2);line-height:1.8;margin-bottom:1.2rem}
.story-section p strong{color:var(--ink)}
.story-section h2{font-family:var(--serif);font-size:1.5rem;font-style:italic;font-weight:400;color:var(--amber);margin-bottom:1rem;margin-top:2rem}

/* QUOTE */
.pull-quote{background:var(--surface);border:1px solid var(--border);border-left:3px solid var(--amber);border-radius:0 8px 8px 0;padding:1.4rem 1.8rem;margin:2rem 0}
.pull-quote p{font-family:var(--serif);font-size:1.15rem;font-style:italic;color:var(--ink);line-height:1.65;margin:0}

/* VALUES */
.values-grid{display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin:2rem 0}
.value-item{background:var(--surface);padding:1.4rem}
.value-icon{font-size:1.2rem;margin-bottom:.6rem}
.value-title{font-size:.85rem;font-weight:600;margin-bottom:.3rem}
.value-desc{font-size:.78rem;color:var(--ink3);line-height:1.55}

/* CTA */
.about-cta{background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:2rem;text-align:center;margin-top:3rem}
.about-cta h3{font-family:var(--serif);font-size:1.5rem;font-style:italic;font-weight:400;margin-bottom:.5rem}
.about-cta p{color:var(--ink2);font-size:.88rem;margin-bottom:1.5rem}
.btn-amber{background:var(--amber);color:#000;border:none;padding:.8rem 1.8rem;font-family:var(--sans);font-size:.85rem;font-weight:600;border-radius:4px;cursor:pointer;text-decoration:none;display:inline-block;transition:opacity .15s;margin-right:.6rem}
.btn-amber:hover{opacity:.88}
.btn-ghost{background:transparent;color:var(--ink2);border:1px solid var(--border2);padding:.8rem 1.8rem;font-family:var(--sans);font-size:.85rem;border-radius:4px;text-decoration:none;display:inline-inline-block;transition:all .2s}
.btn-ghost:hover{color:var(--ink)}

footer{border-top:1px solid var(--border);padding:2rem 2.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
footer p{font-family:var(--mono);font-size:.6rem;color:var(--ink3)}
footer a{font-family:var(--mono);font-size:.6rem;color:var(--ink3);text-decoration:none;transition:color .2s}
footer a:hover{color:var(--amber)}

@media(max-width:600px){
  .page-wrap{padding:7rem 1.2rem 4rem}
  nav{padding:1rem 1.2rem}
  .founder-inner{flex-direction:column}
  .values-grid{grid-template-columns:1fr}
}
</style>
@endpush

@section('content')
<nav>
  <a href="{{ route('home') }}" class="logo">Proof<span class="logo-word">Work</span></a>
  <div class="nav-right">
    <a href="{{ route('home') }}" class="nav-link">← Home</a>
    <a href="{{ route('home') }}#waitlist" class="nav-cta">Join waitlist</a>
  </div>
</nav>

<div class="page-wrap">

  <div class="about-hero">
    <div class="about-eyebrow">About ProofWork</div>
    <h1 class="about-title">Built by a freelancer,<br>for <em>freelancers.</em></h1>
  </div>

  <!-- Founder -->
  <div class="founder-card">
    <div class="founder-card-top"></div>
    <div class="founder-inner">
      <div class="founder-avatar">A</div>
      <div class="founder-info">
        <div class="founder-name">Ahmat Issa</div>
        <div class="founder-role">Founder · Student in Data Science & AI · Université Mundiapolis</div>
        <div class="founder-bio">
          I'm a Master's student in Data Science & AI based in Casablanca, Morocco.
          I've been building software on the side for years — trading bots, fintech apps,
          academic tools. ProofWork came from a real frustration I kept running into
          while doing freelance dev work.
        </div>
        <div class="founder-links">
          <a href="https://github.com/ahmatissa08" target="_blank" class="founder-link">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
            ahmatissa08
          </a>
          <a href="https://twitter.com/proofwork" target="_blank" class="founder-link">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.253 5.622 5.91-5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            @proofwork
          </a>
          <a href="mailto:addimiahmat@gmail.com" class="founder-link">
            ✉ addimiahmat@gmail.com
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Story -->
  <div class="story-section">
    <h2>Why I built this</h2>

    <p>
      Last year I finished a freelance project for a client — two months of solid work.
      When I sent the invoice, they pushed back. <strong>"Can you prove you actually worked 80 hours?"</strong>
      I had nothing. GitHub commits, yes. Notion pages, yes. But no way to show it all
      in one place, clearly, in a format a non-technical client could understand.
    </p>

    <div class="pull-quote">
      <p>"I spent a whole Friday afternoon copy-pasting screenshots into a PDF. It was embarrassing. There had to be a better way."</p>
    </div>

    <p>
      I started asking other freelancers. Same story everywhere.
      <strong>Spreadsheets that nobody trusts. Status updates written from memory.
      Invoices disputed because there's no paper trail.</strong>
      Freelancers lose 3–5 hours every week just on reporting — and the reports
      still don't convince skeptical clients.
    </p>

    <h2>The idea</h2>

    <p>
      What if your work tools already had everything needed to prove what you did?
      GitHub has your commits. Linear has your tasks. Calendar has your meetings.
      <strong>ProofWork just connects the dots</strong> — pulls all that activity,
      formats it into a clean verifiable report, and sends it to your client automatically.
      No writing. No screenshots. No "trust me".
    </p>

    <p>
      I'm building this as a solo founder, alongside my Master's in Data Science & AI
      in Casablanca. No VC money, no team — just a real problem I want to solve.
    </p>

    <h2>Where we are</h2>

    <p>
      ProofWork is currently in <strong>private beta</strong>.
      The waitlist is open and growing. Everyone who signs up now gets
      <strong style="color:var(--amber)">3 months free on the Pro plan</strong> when we launch.
      I read every reply to every email — if you have feedback, questions, or just want to
      talk about the product, I'm one email away.
    </p>
  </div>

  <!-- Values -->
  <div class="values-grid">
    <div class="value-item">
      <div class="value-icon">🔍</div>
      <div class="value-title">Transparency first</div>
      <div class="value-desc">Every item in a ProofWork report links to its source. No summaries invented from nothing.</div>
    </div>
    <div class="value-item">
      <div class="value-icon">⚡</div>
      <div class="value-title">Zero effort</div>
      <div class="value-desc">If you have to do more than connect your tools once, we've failed. Reports should just happen.</div>
    </div>
    <div class="value-item">
      <div class="value-icon">🔒</div>
      <div class="value-title">Read-only access</div>
      <div class="value-desc">We only ever request read access to your tools. We can't push code, close tasks, or write anything.</div>
    </div>
    <div class="value-item">
      <div class="value-icon">🤝</div>
      <div class="value-title">Built with users</div>
      <div class="value-desc">Every feature on the roadmap came from a real conversation with a freelancer. Not from a product manager's spreadsheet.</div>
    </div>
  </div>

  <!-- CTA -->
  <div class="about-cta">
    <h3>Want to be part of this?</h3>
    <p>Join the waitlist — early users shape the product and get 3 months free.</p>
    <a href="{{ route('home') }}#waitlist" class="btn-amber">Join the waitlist →</a>
    <a href="mailto:addimiahmat@gmail.com" class="btn-ghost">Send me feedback</a>
  </div>

</div>

<footer>
  <p>© {{ date('Y') }} ProofWork · Built solo by Ahmat Issa</p>
  <div style="display:flex;gap:1.5rem">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('privacy') }}">Privacy</a>
    <a href="{{ route('terms') }}">Terms</a>
    <a href="mailto:addimiahmat@gmail.com">Contact</a>
  </div>
</footer>
@endsection
