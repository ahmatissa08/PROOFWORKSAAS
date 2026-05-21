<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ProofWork — Proof of work for freelancers</title>
  <meta name="description" content="Auto-generate proof of work reports from GitHub, Linear, Notion & Google Calendar. Deliver trust to your clients.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist+Mono:wght@400;500&family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
  <style>
  :root{
    --bg:#0c0c0e;--surface:#131316;--surface2:#18181c;--surface3:#1e1e24;
    --border:#242428;--border2:#2e2e34;--border3:#3a3a42;
    --ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--ink4:#3a3835;
    --amber:#e8a325;--coral:#e85c3a;--sky:#4a9eff;--green:#27c93f;
    --mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif;
  }
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  html{scroll-behavior:smooth}
  body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6;min-height:100vh;overflow-x:hidden}
  ::-webkit-scrollbar{width:4px}
  ::-webkit-scrollbar-thumb{background:var(--border2);border-radius:2px}

  /* ── Navigation ── */
  .nav{
    position:fixed;top:0;left:0;right:0;z-index:100;
    display:flex;align-items:center;justify-content:space-between;
    padding:1rem 2rem;
    background:rgba(12,12,14,.8);
    backdrop-filter:blur(20px);
    border-bottom:1px solid transparent;
    transition:border-color .3s;
  }
  .nav.scrolled{border-color:var(--border)}
  .nav-brand{
    font-family:var(--serif);font-size:1.1rem;font-style:italic;
    color:var(--ink);text-decoration:none;display:flex;align-items:center;gap:.5rem;
  }
  .nav-brand i{font-size:16px;color:var(--amber)}
  .nav-links{display:flex;align-items:center;gap:2rem}
  .nav-link{
    font-size:.82rem;color:var(--ink3);text-decoration:none;
    transition:color .2s;position:relative;
  }
  .nav-link:hover{color:var(--ink)}
  .nav-link::after{
    content:'';position:absolute;bottom:-4px;left:0;width:0;height:1px;
    background:var(--amber);transition:width .3s;
  }
  .nav-link:hover::after{width:100%}
  .nav-cta{
    background:var(--amber);color:#000;font-size:.8rem;font-weight:600;
    padding:.55rem 1.2rem;border-radius:6px;text-decoration:none;
    transition:all .3s;
  }
  .nav-cta:hover{transform:translateY(-1px);box-shadow:0 4px 20px rgba(232,163,37,.3)}
  .mobile-menu-btn{display:none;background:none;border:none;color:var(--ink3);font-size:1.5rem;cursor:pointer}

  /* ── Hero ── */
  .hero{
    min-height:100vh;display:flex;flex-direction:column;justify-content:center;
    align-items:center;text-align:center;padding:8rem 2rem 4rem;
    position:relative;overflow:hidden;
  }
  .hero::before{
    content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);
    width:800px;height:800px;border-radius:50%;
    background:radial-gradient(circle,rgba(232,163,37,.06) 0%,transparent 70%);
    pointer-events:none;
  }
  .hero::after{
    content:'';position:absolute;bottom:0;left:0;right:0;height:1px;
    background:linear-gradient(90deg,transparent,var(--border),transparent);
  }
  .hero-badge{
    display:inline-flex;align-items:center;gap:.5rem;
    background:rgba(232,163,37,.08);border:1px solid rgba(232,163,37,.2);
    color:var(--amber);font-family:var(--mono);font-size:.65rem;
    padding:.35rem .9rem;border-radius:99px;letter-spacing:.08em;
    margin-bottom:2rem;animation:fadeInUp .8s ease-out;
  }
  .hero-badge i{font-size:12px}
  .hero-title{
    font-family:var(--serif);font-size:clamp(2.5rem,6vw,4.5rem);
    font-style:italic;font-weight:400;letter-spacing:-.03em;
    line-height:1.1;max-width:800px;margin-bottom:1.5rem;
    animation:fadeInUp .8s ease-out .1s both;
  }
  .hero-title span{color:var(--amber)}
  .hero-sub{
    font-size:clamp(1rem,2vw,1.25rem);color:var(--ink2);max-width:560px;
    line-height:1.7;margin-bottom:2.5rem;
    animation:fadeInUp .8s ease-out .2s both;
  }
  .hero-cta-group{
    display:flex;gap:1rem;flex-wrap:wrap;justify-content:center;
    animation:fadeInUp .8s ease-out .3s both;
  }
  .hero-cta-primary{
    background:var(--amber);color:#000;font-size:.95rem;font-weight:600;
    padding:1rem 2rem;border-radius:8px;text-decoration:none;
    display:inline-flex;align-items:center;gap:.5rem;
    transition:all .3s;position:relative;overflow:hidden;
  }
  .hero-cta-primary::before{
    content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.2),transparent);
    transition:left .5s;
  }
  .hero-cta-primary:hover::before{left:100%}
  .hero-cta-primary:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(232,163,37,.35)}
  .hero-cta-secondary{
    background:transparent;color:var(--ink2);font-size:.95rem;font-weight:500;
    padding:1rem 2rem;border-radius:8px;text-decoration:none;
    border:1px solid var(--border2);display:inline-flex;align-items:center;gap:.5rem;
    transition:all .3s;
  }
  .hero-cta-secondary:hover{border-color:var(--ink3);color:var(--ink)}

  /* ── Social Proof ── */
  .social-proof{
    padding:3rem 2rem;text-align:center;border-bottom:1px solid var(--border);
    animation:fadeInUp .8s ease-out .4s both;
  }
  .social-proof-label{
    font-family:var(--mono);font-size:.6rem;color:var(--ink3);
    letter-spacing:.12em;text-transform:uppercase;margin-bottom:1.5rem;
  }
  .social-proof-logos{
    display:flex;justify-content:center;align-items:center;gap:3rem;flex-wrap:wrap;
    opacity:.4;
  }
  .social-proof-logos span{
    font-family:var(--serif);font-size:1.1rem;font-style:italic;color:var(--ink3);
  }

  /* ── Features ── */
  .features{
    padding:6rem 2rem;max-width:1100px;margin:0 auto;
  }
  .section-header{text-align:center;margin-bottom:4rem}
  .section-label{
    font-family:var(--mono);font-size:.6rem;color:var(--amber);
    letter-spacing:.12em;text-transform:uppercase;margin-bottom:1rem;
  }
  .section-title{
    font-family:var(--serif);font-size:clamp(1.8rem,4vw,2.8rem);
    font-style:italic;font-weight:400;letter-spacing:-.02em;
  }
  .features-grid{
    display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:1.5rem;
  }
  .feature-card{
    background:var(--surface);border:1px solid var(--border);border-radius:12px;
    padding:2rem;transition:all .3s;position:relative;overflow:hidden;
  }
  .feature-card::before{
    content:'';position:absolute;top:0;left:0;right:0;height:2px;
    background:linear-gradient(90deg,var(--amber),var(--sky));
    transform:scaleX(0);transition:transform .3s;
  }
  .feature-card:hover::before{transform:scaleX(1)}
  .feature-card:hover{
    transform:translateY(-4px);
    border-color:var(--border2);
    box-shadow:0 20px 40px rgba(0,0,0,.3);
  }
  .feature-icon{
    width:40px;height:40px;border-radius:10px;
    background:var(--surface2);border:1px solid var(--border);
    display:flex;align-items:center;justify-content:center;
    margin-bottom:1.2rem;font-size:18px;color:var(--amber);
  }
  .feature-title{font-size:1rem;font-weight:600;color:var(--ink);margin-bottom:.5rem}
  .feature-desc{font-size:.85rem;color:var(--ink3);line-height:1.65}

  /* ── How it works ── */
  .how-it-works{
    padding:6rem 2rem;background:var(--surface);border-top:1px solid var(--border);
  }
  .how-it-works-inner{max-width:900px;margin:0 auto}
  .steps{
    display:flex;flex-direction:column;gap:0;position:relative;
  }
  .steps::before{
    content:'';position:absolute;left:24px;top:0;bottom:0;width:1px;
    background:linear-gradient(180deg,var(--amber),var(--sky),transparent);
  }
  .step{
    display:flex;gap:1.5rem;padding:2rem 0;position:relative;
  }
  .step-number{
    width:48px;height:48px;border-radius:12px;
    background:var(--surface2);border:1px solid var(--border2);
    display:flex;align-items:center;justify-content:center;
    font-family:var(--serif);font-size:1.2rem;font-style:italic;
    color:var(--amber);flex-shrink:0;z-index:1;
  }
  .step-content{flex:1;padding-top:.3rem}
  .step-title{font-size:1rem;font-weight:600;color:var(--ink);margin-bottom:.4rem}
  .step-desc{font-size:.85rem;color:var(--ink3);line-height:1.65}

  /* ── Preview / Demo ── */
  .preview{
    padding:6rem 2rem;position:relative;overflow:hidden;
  }
  .preview::before{
    content:'';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
    width:600px;height:600px;border-radius:50%;
    background:radial-gradient(circle,rgba(74,158,255,.05) 0%,transparent 70%);
    pointer-events:none;
  }
  .preview-inner{max-width:900px;margin:0 auto;position:relative;z-index:1}
  .preview-card{
    background:var(--surface);border:1px solid var(--border);border-radius:14px;
    overflow:hidden;box-shadow:0 40px 80px rgba(0,0,0,.4);
  }
  .preview-header{
    display:flex;align-items:center;gap:.5rem;
    padding:.75rem 1rem;border-bottom:1px solid var(--border);background:var(--surface2);
  }
  .preview-dot{width:10px;height:10px;border-radius:50%}
  .preview-dot.red{background:var(--coral)}
  .preview-dot.yellow{background:var(--amber)}
  .preview-dot.green{background:var(--green)}
  .preview-body{padding:1.5rem}
  .preview-row{
    display:flex;align-items:center;gap:1rem;padding:.75rem 0;
    border-bottom:1px solid var(--border);
  }
  .preview-row:last-child{border-bottom:none}
  .preview-icon{
    width:32px;height:32px;border-radius:8px;
    background:var(--surface2);border:1px solid var(--border);
    display:flex;align-items:center;justify-content:center;
    font-size:14px;color:var(--ink3);flex-shrink:0;
  }
  .preview-text{flex:1}
  .preview-title{font-size:.85rem;font-weight:500;color:var(--ink)}
  .preview-meta{font-family:var(--mono);font-size:.6rem;color:var(--ink3);margin-top:.15rem}
  .preview-badge{
    font-family:var(--mono);font-size:.58rem;padding:.2rem .5rem;border-radius:99px;
  }
  .preview-badge.commit{background:rgba(39,201,63,.1);color:var(--green);border:1px solid rgba(39,201,63,.2)}
  .preview-badge.pr{background:rgba(74,158,255,.1);color:var(--sky);border:1px solid rgba(74,158,255,.2)}
  .preview-badge.task{background:rgba(232,163,37,.1);color:var(--amber);border:1px solid rgba(232,163,37,.2)}

  /* ── CTA Section ── */
  .cta-section{
    padding:6rem 2rem;text-align:center;position:relative;
    border-top:1px solid var(--border);
  }
  .cta-section::before{
    content:'';position:absolute;top:0;left:0;right:0;height:1px;
    background:linear-gradient(90deg,transparent,var(--amber),transparent);
  }
  .cta-title{
    font-family:var(--serif);font-size:clamp(1.8rem,4vw,2.8rem);
    font-style:italic;font-weight:400;letter-spacing:-.02em;
    margin-bottom:1rem;
  }
  .cta-sub{color:var(--ink3);font-size:1rem;max-width:480px;margin:0 auto 2rem;line-height:1.7}
  .cta-btn{
    background:var(--amber);color:#000;font-size:1rem;font-weight:600;
    padding:1rem 2.5rem;border-radius:8px;text-decoration:none;
    display:inline-flex;align-items:center;gap:.5rem;
    transition:all .3s;
  }
  .cta-btn:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(232,163,37,.35)}

  /* ── Footer ── */
  .footer{
    padding:3rem 2rem;border-top:1px solid var(--border);
    display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;
  }
  .footer-brand{
    font-family:var(--serif);font-size:1rem;font-style:italic;color:var(--ink3);
    display:flex;align-items:center;gap:.5rem;
  }
  .footer-brand i{color:var(--amber);font-size:14px}
  .footer-links{display:flex;gap:1.5rem}
  .footer-link{font-size:.75rem;color:var(--ink3);text-decoration:none;transition:color .2s}
  .footer-link:hover{color:var(--ink2)}
  .footer-copy{font-family:var(--mono);font-size:.6rem;color:var(--ink4)}

  /* ── Animations ── */
  @keyframes fadeInUp{
    from{opacity:0;transform:translateY(20px)}
    to{opacity:1;transform:translateY(0)}
  }

  /* ── Responsive ── */
  @media(max-width:768px){
    .nav{padding:.8rem 1rem}
    .nav-links{display:none}
    .mobile-menu-btn{display:block}
    .hero{padding:6rem 1.5rem 3rem}
    .features{padding:4rem 1.5rem}
    .how-it-works{padding:4rem 1.5rem}
    .preview{padding:4rem 1.5rem}
    .cta-section{padding:4rem 1.5rem}
    .footer{flex-direction:column;text-align:center}
    .steps::before{left:20px}
    .step-number{width:40px;height:40px;font-size:1rem}
  }
  </style>
</head>
<body>

<!-- Navigation -->
<nav class="nav" id="nav">
  <a href="{{ route('home') }}" class="nav-brand">
    <i class="ti ti-checkup-list"></i> ProofWork
  </a>
  <div class="nav-links">
    <a href="{{ route('about') }}" class="nav-link">About</a>
    <a href="{{ route('contact') }}" class="nav-link">Contact</a>
    <a href="#features" class="nav-link">Features</a>
    <a href="#how-it-works" class="nav-link">How it works</a>
    <a href="{{ route('login') }}" class="nav-link">Sign in</a>
    <a href="{{ route('register') }}" class="nav-cta">Get started</a>
  </div>
</nav>

<!-- Hero -->
<section class="hero">
  <div class="hero-badge">
    <i class="ti ti-sparkles"></i> TRUSTED BY 500+ FREELANCERS
  </div>
  <h1 class="hero-title">
    Proof of work,<br>delivered <span>beautifully</span>.
  </h1>
  <p class="hero-sub">
    Auto-generate weekly reports from GitHub, Linear, Notion & Google Calendar. 
    Send verified proof to your clients in one click.
  </p>
  <div class="hero-cta-group">
    <a href="{{ route('register') }}" class="hero-cta-primary">
      Start for free <i class="ti ti-arrow-right"></i>
    </a>
    <a href="#how-it-works" class="hero-cta-secondary">
      See how it works
    </a>
  </div>
</section>

<!-- Social Proof -->
<div class="social-proof">
  <div class="social-proof-label">Works with your existing tools</div>
  <div class="social-proof-logos">
    <span><i class="ti ti-brand-github"></i> GitHub</span>
    <span><i class="ti ti-triangle-square-circle"></i> Linear</span>
    <span><i class="ti ti-notebook"></i> Notion</span>
    <span><i class="ti ti-calendar-event"></i> Google Calendar</span>
  </div>
</div>

<!-- Features -->
<section class="features" id="features">
  <div class="section-header">
    <div class="section-label">Features</div>
    <h2 class="section-title">Everything you need to prove your work</h2>
  </div>
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon"><i class="ti ti-plug-connected"></i></div>
      <div class="feature-title">One-click integrations</div>
      <div class="feature-desc">Connect GitHub, Linear, Notion and Google Calendar in seconds. No manual data entry ever again.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><i class="ti ti-sparkles"></i></div>
      <div class="feature-title">AI-powered summaries</div>
      <div class="feature-desc">Let AI write a human-readable summary of your week. Your clients get context, not just raw data.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><i class="ti ti-file-check"></i></div>
      <div class="feature-title">Verified PDF reports</div>
      <div class="feature-desc">Generate cryptographically verifiable PDFs with QR codes. Your clients can scan to verify authenticity.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><i class="ti ti-share"></i></div>
      <div class="feature-title">Public share links</div>
      <div class="feature-desc">Share a beautiful, branded report page with clients. No account required for them to view.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><i class="ti ti-users"></i></div>
      <div class="feature-title">Client management</div>
      <div class="feature-desc">Organize reports by client and project. Keep your workflow structured and professional.</div>
    </div>
    <div class="feature-card">
      <div class="feature-icon"><i class="ti ti-shield-check"></i></div>
      <div class="feature-title">Source verification</div>
      <div class="feature-desc">Every item links back to the original source. Commits, tasks, meetings — all traceable.</div>
    </div>
  </div>
</section>

<!-- How it works -->
<section class="how-it-works" id="how-it-works">
  <div class="how-it-works-inner">
    <div class="section-header">
      <div class="section-label">How it works</div>
      <h2 class="section-title">From tools to trust in three steps</h2>
    </div>
    <div class="steps">
      <div class="step">
        <div class="step-number">1</div>
        <div class="step-content">
          <div class="step-title">Connect your tools</div>
          <div class="step-desc">Link GitHub, Linear, Notion or Google Calendar to ProofWork. We only read activity data — no write permissions needed.</div>
        </div>
      </div>
      <div class="step">
        <div class="step-number">2</div>
        <div class="step-content">
          <div class="step-title">Generate your report</div>
          <div class="step-desc">Pick a date range and project. ProofWork pulls all commits, tasks, meetings and decisions automatically.</div>
        </div>
      </div>
      <div class="step">
        <div class="step-number">3</div>
        <div class="step-content">
          <div class="step-title">Send to your client</div>
          <div class="step-desc">Share a public link or download a verified PDF with QR code. Your client gets proof, you get peace of mind.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Preview -->
<section class="preview">
  <div class="preview-inner">
    <div class="section-header">
      <div class="section-label">Preview</div>
      <h2 class="section-title">What your clients see</h2>
    </div>
    <div class="preview-card">
      <div class="preview-header">
        <div class="preview-dot red"></div>
        <div class="preview-dot yellow"></div>
        <div class="preview-dot green"></div>
        <span style="font-family:var(--mono);font-size:.6rem;color:var(--ink3);margin-left:.5rem">proofwork.app/r/Z4w7wTyR...</span>
      </div>
      <div class="preview-body">
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem">
          <div style="width:36px;height:36px;border-radius:9px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center">
            <i class="ti ti-checkup-list" style="color:var(--amber);font-size:16px"></i>
          </div>
          <div>
            <div style="font-family:var(--serif);font-size:1.1rem;font-style:italic;color:var(--ink)">Weekly Report — CSSHTML</div>
            <div style="font-family:var(--mono);font-size:.58rem;color:var(--ink3);margin-top:.1rem">May 12 – May 18, 2026 · 4 entries</div>
          </div>
          <div style="margin-left:auto;display:flex;align-items:center;gap:.4rem;background:rgba(39,201,63,.08);border:1px solid rgba(39,201,63,.2);color:var(--green);font-family:var(--mono);font-size:.58rem;padding:.25rem .7rem;border-radius:99px">
            <span style="width:6px;height:6px;background:var(--green);border-radius:50%;animation:pulse 2s infinite"></span>
            VERIFIED
          </div>
        </div>
        <div class="preview-row">
          <div class="preview-icon"><i class="ti ti-brand-github"></i></div>
          <div class="preview-text">
            <div class="preview-title">feat: redesign landing page with dark theme</div>
            <div class="preview-meta">github · 3 days ago</div>
          </div>
          <span class="preview-badge commit">commit</span>
        </div>
        <div class="preview-row">
          <div class="preview-icon"><i class="ti ti-brand-github"></i></div>
          <div class="preview-text">
            <div class="preview-title">fix: resolve navigation scroll bug on mobile</div>
            <div class="preview-meta">github · 2 days ago</div>
          </div>
          <span class="preview-badge pr">PR #42</span>
        </div>
        <div class="preview-row">
          <div class="preview-icon"><i class="ti ti-triangle-square-circle"></i></div>
          <div class="preview-text">
            <div class="preview-title">Implement PDF generation with QR verification</div>
            <div class="preview-meta">linear · 1 day ago</div>
          </div>
          <span class="preview-badge task">done</span>
        </div>
        <div class="preview-row">
          <div class="preview-icon"><i class="ti ti-calendar-event"></i></div>
          <div class="preview-text">
            <div class="preview-title">Sprint planning — Q3 roadmap discussion</div>
            <div class="preview-meta">calendar · 5 hours ago</div>
          </div>
          <span class="preview-badge task">meeting</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <h2 class="cta-title">Ready to prove your work?</h2>
  <p class="cta-sub">Join 500+ freelancers who deliver trust with every report. Free forever for personal use.</p>
  <a href="{{ route('register') }}" class="cta-btn">
    Get started for free <i class="ti ti-arrow-right"></i>
  </a>
</section>

<!-- Footer -->
<footer class="footer">
  <div class="footer-brand">
    <i class="ti ti-checkup-list"></i> ProofWork
  </div>
  <div class="footer-links">
    <a href="{{ route('about') }}" class="footer-link">About</a>
    <a href="{{ route('contact') }}" class="footer-link">Contact</a>
    <a href="{{ route('privacy') }}" class="footer-link">Privacy</a>
    <a href="{{ route('terms') }}" class="footer-link">Terms</a>
    <a href="{{ route('login') }}" class="footer-link">Sign in</a>
  </div>
  <div class="footer-copy">© 2026 ProofWork</div>
</footer>

<script>
  // Nav scroll effect
  const nav = document.getElementById('nav');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      nav.classList.add('scrolled');
    } else {
      nav.classList.remove('scrolled');
    }
  });
</script>

</body>
</html>