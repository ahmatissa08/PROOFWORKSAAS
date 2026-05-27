<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ProofWork — Auto-generate client-ready proof of work reports</title>
  <meta name="description" content="ProofWork connects to GitHub, Linear, Notion and Google Calendar to auto-generate verifiable client reports every week. Start free today.">
  <meta property="og:title" content="ProofWork — Prove your work. Automatically.">
  <meta property="og:description" content="Stop writing reports manually. Connect your tools and let ProofWork generate verifiable proof of work every Friday.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital,wght@0,400;1,400&family=Geist+Mono:wght@300;400;500&family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root{--bg:#0c0c0e;--surface:#111113;--surface2:#18181c;--border:#242428;--border2:#2e2e35;--border3:#3a3a42;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--amber2:#f5b43a;--coral:#e85c3a;--sky:#4a9eff;--green:#27c93f;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{scroll-behavior:smooth;-webkit-font-smoothing:antialiased}
    body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6;overflow-x:hidden}
    ::-webkit-scrollbar{width:3px}::-webkit-scrollbar-thumb{background:var(--border2)}
    a{text-decoration:none}

    /* NAV */
    .nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:.9rem 2rem;background:rgba(12,12,14,.88);border-bottom:1px solid rgba(36,36,40,.6);backdrop-filter:blur(20px);transition:all .3s}
    .nav.scrolled{background:rgba(12,12,14,.98);border-color:var(--border)}
    .nav-brand{display:flex;align-items:center;gap:.55rem;color:var(--ink);font-weight:600;font-size:.92rem;letter-spacing:-.02em}
    .nav-mark{width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,var(--amber),var(--amber2));display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-style:italic;font-size:.95rem;color:#0c0c0e;flex-shrink:0}
    .nav-links{display:flex;align-items:center;gap:.1rem}
    .nav-link{color:var(--ink3);font-size:.8rem;padding:.42rem .85rem;border-radius:5px;transition:all .18s}
    .nav-link:hover{color:var(--ink);background:rgba(255,255,255,.05)}
    .nav-sep{width:1px;height:18px;background:var(--border);margin:0 .3rem}
    .nav-login{color:var(--ink2);font-size:.8rem;font-weight:500;padding:.42rem .85rem;border-radius:5px;transition:all .18s}
    .nav-login:hover{color:var(--ink)}
    .nav-cta{background:var(--amber);color:#000;font-size:.78rem;font-weight:700;padding:.48rem 1.1rem;border-radius:6px;transition:all .2s;display:flex;align-items:center;gap:.35rem;letter-spacing:-.01em}
    .nav-cta:hover{background:var(--amber2);transform:translateY(-1px);box-shadow:0 4px 20px rgba(232,163,37,.3)}

    /* HERO */
    .hero{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:8rem 2rem 5rem;position:relative;overflow:hidden;text-align:center}
    .hero-bg{position:absolute;inset:0;pointer-events:none}
    .hero-glow{position:absolute;top:-10%;left:50%;transform:translateX(-50%);width:900px;height:600px;border-radius:50%;background:radial-gradient(ellipse,rgba(232,163,37,.07) 0%,rgba(74,158,255,.03) 40%,transparent 70%)}
    .hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:64px 64px;mask-image:radial-gradient(ellipse 80% 60% at 50% 0%,black 0%,transparent 80%)}
    .hero-inner{position:relative;z-index:1;max-width:860px}

    .hero-tag{display:inline-flex;align-items:center;gap:.5rem;background:rgba(39,201,63,.08);border:1px solid rgba(39,201,63,.2);border-radius:30px;padding:.3rem .9rem .3rem .6rem;font-family:var(--mono);font-size:.62rem;color:var(--green);letter-spacing:.06em;margin-bottom:2rem;animation:fadeUp .5s ease both}
    .hero-tag-dot{width:5px;height:5px;border-radius:50%;background:var(--green);animation:blink 2s infinite;flex-shrink:0}
    @keyframes blink{0%,100%{opacity:1}50%{opacity:.25}}

    .hero-h1{font-family:var(--serif);font-size:clamp(3rem,7.5vw,6.5rem);font-weight:400;line-height:.97;letter-spacing:-.035em;margin-bottom:1.5rem;animation:fadeUp .5s .07s ease both}
    .h1-line1{display:block;color:var(--ink)}
    .h1-line2{display:block;color:var(--amber);font-style:italic}
    .h1-line3{display:block;color:var(--ink3);font-style:italic}

    .hero-sub{font-size:1.05rem;color:var(--ink2);max-width:56ch;margin:0 auto 2.5rem;line-height:1.75;font-weight:300;animation:fadeUp .5s .14s ease both}
    .hero-sub strong{color:var(--ink);font-weight:500}

    .hero-actions{display:flex;gap:.7rem;justify-content:center;flex-wrap:wrap;animation:fadeUp .5s .21s ease both}
    .btn-primary{background:var(--amber);color:#000;border:none;padding:.88rem 1.9rem;border-radius:7px;font-family:var(--sans);font-size:.88rem;font-weight:700;cursor:pointer;transition:all .18s;display:inline-flex;align-items:center;gap:.5rem;letter-spacing:-.01em}
    .btn-primary:hover{background:var(--amber2);transform:translateY(-2px);box-shadow:0 8px 30px rgba(232,163,37,.28)}
    .btn-outline{background:transparent;color:var(--ink2);border:1px solid var(--border2);padding:.88rem 1.9rem;border-radius:7px;font-family:var(--sans);font-size:.88rem;font-weight:400;cursor:pointer;transition:all .18s;display:inline-flex;align-items:center;gap:.5rem}
    .btn-outline:hover{color:var(--ink);border-color:var(--border3);transform:translateY(-1px)}

    .hero-trust{display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap;margin-top:1.3rem;animation:fadeUp .5s .28s ease both}
    .trust-item{font-family:var(--mono);font-size:.62rem;color:var(--ink3);display:flex;align-items:center;gap:.4rem}
    .trust-item::before{content:'✓';color:var(--green)}

    .hero-metrics{display:grid;grid-template-columns:repeat(3,1fr);gap:0;margin-top:4.5rem;padding-top:2.5rem;border-top:1px solid var(--border);animation:fadeUp .5s .35s ease both}
    .metric{padding:0 2rem;border-right:1px solid var(--border);text-align:center}
    .metric:last-child{border-right:none}
    .metric-number{font-family:var(--serif);font-size:2.5rem;font-style:italic;color:var(--amber);display:block;line-height:1}
    .metric-label{font-family:var(--mono);font-size:.58rem;color:var(--ink3);text-transform:uppercase;letter-spacing:.1em;margin-top:.35rem;display:block}

    /* INTEGRATIONS STRIP */
    .strip{background:var(--surface);border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:1.2rem 2rem;text-align:center}
    .strip-label{font-family:var(--mono);font-size:.58rem;color:var(--ink3);text-transform:uppercase;letter-spacing:.12em;margin-bottom:.9rem}
    .strip-tools{display:flex;gap:3rem;justify-content:center;align-items:center;flex-wrap:wrap}
    .strip-tool{display:flex;align-items:center;gap:.5rem;font-family:var(--mono);font-size:.72rem;color:var(--ink3);font-weight:500}

    /* SECTIONS */
    .section{padding:7rem 2rem}
    .wrap{max-width:1100px;margin:0 auto}
    .section-tag{font-family:var(--mono);font-size:.6rem;color:var(--amber);letter-spacing:.16em;text-transform:uppercase;margin-bottom:.9rem;display:block;opacity:.85}
    .section-h2{font-family:var(--serif);font-size:clamp(2rem,4vw,3.2rem);font-weight:400;letter-spacing:-.03em;line-height:1.08;margin-bottom:.7rem}
    .section-h2 em{font-style:italic;color:var(--amber)}
    .section-desc{color:var(--ink3);font-size:.9rem;line-height:1.65}

    /* PROBLEM */
    .problem-section{background:var(--surface);border-top:1px solid var(--border);border-bottom:1px solid var(--border)}
    .problem-layout{display:grid;grid-template-columns:1fr 1fr;gap:6rem;align-items:center}
    .problem-list{list-style:none;display:flex;flex-direction:column;gap:1.5rem;margin-top:2.5rem}
    .prob{display:flex;gap:1rem;align-items:flex-start}
    .prob-icon{width:36px;height:36px;border-radius:8px;flex-shrink:0;background:rgba(232,92,58,.07);border:1px solid rgba(232,92,58,.12);display:flex;align-items:center;justify-content:center;font-size:.85rem;margin-top:.1rem}
    .prob-title{font-size:.88rem;font-weight:600;color:var(--ink);margin-bottom:.2rem}
    .prob-desc{font-size:.8rem;color:var(--ink3);line-height:1.55}

    .invoice-card{background:var(--bg);border:1px solid var(--border2);border-radius:12px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,.5)}
    .inv-bar{height:3px;background:linear-gradient(90deg,var(--coral),rgba(232,92,58,.3))}
    .inv-head{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.4rem;background:rgba(255,255,255,.02);border-bottom:1px solid var(--border)}
    .inv-title{font-size:.82rem;font-weight:600}
    .inv-disputed{font-family:var(--mono);font-size:.55rem;color:var(--coral);background:rgba(232,92,58,.08);border:1px solid rgba(232,92,58,.2);padding:.18rem .55rem;border-radius:3px;letter-spacing:.08em;text-transform:uppercase}
    .inv-body{padding:1.3rem 1.4rem}
    .inv-row{display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid rgba(255,255,255,.03);font-size:.8rem}
    .inv-label{color:var(--ink3)}.inv-amount{font-family:var(--mono);color:var(--ink2)}
    .inv-total{display:flex;justify-content:space-between;padding:.75rem 0 0;font-size:.85rem;font-weight:600}
    .inv-total-amount{font-family:var(--mono);color:var(--amber)}
    .inv-message{background:rgba(232,92,58,.05);border:1px solid rgba(232,92,58,.12);border-radius:7px;padding:.9rem 1.1rem;margin-top:1rem}
    .inv-msg-text{font-size:.76rem;color:var(--coral);font-style:italic;line-height:1.6}
    .inv-msg-from{font-family:var(--mono);font-size:.58rem;color:rgba(232,92,58,.5);margin-top:.4rem}

    /* HOW IT WORKS */
    .hiw-steps{display:grid;grid-template-columns:repeat(4,1fr);border:1px solid var(--border);border-radius:12px;overflow:hidden;background:var(--border);gap:1px;margin-top:3.5rem}
    .hiw-step{background:var(--surface);padding:2rem 1.6rem;position:relative;transition:background .2s}
    .hiw-step:hover{background:var(--surface2)}
    .step-num{font-family:var(--mono);font-size:.58rem;color:var(--amber);letter-spacing:.15em;margin-bottom:1.2rem;opacity:.7}
    .step-icon{font-size:1.4rem;margin-bottom:.8rem}
    .step-title{font-size:.88rem;font-weight:600;margin-bottom:.4rem;letter-spacing:-.01em}
    .step-desc{font-size:.76rem;color:var(--ink3);line-height:1.6}
    .step-arrow{position:absolute;top:1.9rem;right:-8px;z-index:1;font-family:var(--mono);font-size:.65rem;color:var(--border2)}

    /* REPORT PREVIEW */
    .preview-section{background:var(--surface);border-top:1px solid var(--border);border-bottom:1px solid var(--border)}
    .preview-layout{display:grid;grid-template-columns:1fr 1.3fr;gap:5rem;align-items:center}
    .feature-list{list-style:none;display:flex;flex-direction:column;gap:.6rem;margin:1.5rem 0 2rem}
    .feature-list li{font-size:.84rem;color:var(--ink2);display:flex;gap:.55rem;align-items:center}
    .feature-list li::before{content:'→';color:var(--amber);font-family:var(--mono);font-size:.7rem;flex-shrink:0}
    .report-card{background:var(--bg);border:1px solid var(--border2);border-radius:12px;overflow:hidden;box-shadow:0 32px 80px rgba(0,0,0,.55)}
    .rc-bar{display:flex;align-items:center;gap:.4rem;padding:.7rem 1rem;background:var(--surface2);border-bottom:1px solid var(--border)}
    .rc-dot{width:9px;height:9px;border-radius:50%}
    .rc-dot.r{background:#ff5f56}.rc-dot.y{background:#ffbd2e}.rc-dot.g{background:#27c93f}
    .rc-url{flex:1;background:var(--bg);border:1px solid var(--border);border-radius:4px;padding:.15rem .65rem;font-family:var(--mono);font-size:.58rem;color:var(--ink3);margin-left:.5rem}
    .rc-head{padding:1rem 1.2rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
    .rc-title{font-size:.84rem;font-weight:600;letter-spacing:-.01em}
    .rc-period{font-family:var(--mono);font-size:.58rem;color:var(--ink3);margin-top:.1rem}
    .rc-verified{display:inline-flex;align-items:center;gap:.3rem;background:rgba(39,201,63,.07);border:1px solid rgba(39,201,63,.18);color:var(--green);font-family:var(--mono);font-size:.55rem;padding:.18rem .55rem;border-radius:20px}
    .rc-verified::before{content:'';width:5px;height:5px;background:var(--green);border-radius:50%;animation:pulse-g 2s infinite}
    @keyframes pulse-g{0%,100%{opacity:1}50%{opacity:.4}}
    .rc-entry{display:grid;grid-template-columns:26px 1fr auto;gap:.75rem;padding:.75rem 1.2rem;border-bottom:1px solid rgba(255,255,255,.03);align-items:start}
    .rc-entry:last-child{border-bottom:none}
    .rc-e-icon{width:26px;height:26px;border:1px solid var(--border2);border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:.72rem;flex-shrink:0}
    .rc-e-title{font-size:.76rem;font-weight:500;line-height:1.35}
    .rc-e-detail{font-family:var(--mono);font-size:.58rem;color:var(--ink3);margin-top:.15rem;line-height:1.4}
    .rc-hl{color:var(--amber)}
    .rc-meta{font-family:var(--mono);font-size:.55rem;color:var(--ink3);opacity:.5;text-align:right;white-space:nowrap}
    .rc-foot{display:flex;justify-content:space-between;padding:.6rem 1.2rem;background:var(--surface2);border-top:1px solid var(--border);font-family:var(--mono);font-size:.56rem;color:var(--ink3)}

    /* FEATURES */
    .features-grid{display:grid;grid-template-columns:repeat(3,1fr);border:1px solid var(--border);border-radius:12px;overflow:hidden;background:var(--border);gap:1px;margin-top:3rem}
    .feat{background:var(--surface);padding:1.9rem;transition:background .2s;position:relative;overflow:hidden}
    .feat:hover{background:var(--surface2)}
    .feat::after{content:'';position:absolute;bottom:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--amber),transparent);opacity:0;transition:opacity .3s}
    .feat:hover::after{opacity:1}
    .feat-num{font-family:var(--mono);font-size:.56rem;color:var(--amber);letter-spacing:.14em;margin-bottom:.8rem;opacity:.6}
    .feat-icon{font-size:1.3rem;margin-bottom:.7rem}
    .feat-title{font-size:.88rem;font-weight:600;margin-bottom:.4rem;letter-spacing:-.01em}
    .feat-desc{font-size:.77rem;color:var(--ink3);line-height:1.6}

    /* TESTIMONIALS */
    .testi-section{background:var(--surface);border-top:1px solid var(--border);border-bottom:1px solid var(--border)}
    .testi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem}
    .testi{background:var(--bg);border:1px solid var(--border);border-radius:10px;padding:1.7rem;transition:all .2s}
    .testi:hover{border-color:var(--border2);transform:translateY(-2px);box-shadow:0 8px 32px rgba(0,0,0,.3)}
    .testi-stars{color:var(--amber);font-size:.72rem;letter-spacing:.12em;margin-bottom:1rem}
    .testi-quote{font-family:var(--serif);font-size:.88rem;font-style:italic;color:var(--ink2);line-height:1.75;margin-bottom:1.4rem}
    .testi-author{display:flex;align-items:center;gap:.7rem}
    .testi-av{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;flex-shrink:0}
    .testi-name{font-size:.78rem;font-weight:600}
    .testi-role{font-family:var(--mono);font-size:.58rem;color:var(--ink3);margin-top:.1rem}

    /* PRICING */
    .pricing-grid{display:grid;grid-template-columns:repeat(3,1fr);max-width:900px;border:1px solid var(--border);border-radius:12px;overflow:hidden;background:var(--border);gap:1px}
    .plan{background:var(--surface);padding:2rem 1.9rem}
    .plan.popular{background:var(--surface2)}
    .plan-tag{font-family:var(--mono);font-size:.55rem;letter-spacing:.1em;text-transform:uppercase;padding:.18rem .5rem;border-radius:3px;margin-bottom:1.1rem;display:inline-block}
    .plan-tag.hot{background:rgba(232,163,37,.15);color:var(--amber);border:1px solid rgba(232,163,37,.25)}
    .plan-name{font-size:.95rem;font-weight:700;margin-bottom:.2rem;letter-spacing:-.01em}
    .plan-price{font-family:var(--serif);font-size:2.9rem;font-style:italic;font-weight:400;letter-spacing:-.05em;line-height:1;margin-bottom:.2rem}
    .popular .plan-price{color:var(--amber)}
    .plan-cycle{font-family:var(--mono);font-size:.65rem;color:var(--ink3);margin-bottom:1.8rem}
    .plan-perks{list-style:none;display:flex;flex-direction:column;gap:.48rem;margin-bottom:2rem}
    .plan-perks li{font-size:.8rem;color:var(--ink2);display:flex;gap:.48rem;align-items:flex-start;line-height:1.4}
    .plan-perks li::before{content:'✓';color:var(--amber);font-family:var(--mono);font-size:.65rem;flex-shrink:0;margin-top:.1rem}
    .plan-btn{width:100%;padding:.78rem;font-family:var(--sans);font-size:.82rem;font-weight:600;border-radius:6px;cursor:pointer;transition:all .18s;border:none;letter-spacing:-.01em}
    .plan-btn-ghost{border:1.5px solid var(--border2) !important;background:transparent;color:var(--ink2)}
    .plan-btn-ghost:hover{border-color:var(--border3) !important;color:var(--ink)}
    .plan-btn-amber{background:var(--amber);color:#000}
    .plan-btn-amber:hover{background:var(--amber2)}
    .pricing-note{max-width:900px;margin-top:1.5rem;display:flex;gap:2rem;flex-wrap:wrap}
    .pricing-note-item{font-family:var(--mono);font-size:.6rem;color:var(--ink3);display:flex;align-items:center;gap:.35rem}
    .pricing-note-item::before{content:'✓';color:var(--green)}

    /* FAQ */
    .faq-section{background:var(--surface);border-top:1px solid var(--border);border-bottom:1px solid var(--border)}
    .faq-list{max-width:740px;margin-top:2.5rem}
    .faq-item{border-bottom:1px solid var(--border)}
    .faq-q{width:100%;background:transparent;border:none;color:var(--ink);font-family:var(--sans);font-size:.88rem;font-weight:500;text-align:left;padding:1.2rem 0;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:1rem;transition:color .15s;letter-spacing:-.01em}
    .faq-q:hover{color:var(--amber)}
    .faq-chevron{font-family:var(--mono);font-size:.6rem;color:var(--amber);transition:transform .25s;flex-shrink:0;opacity:.7}
    .faq-q.open .faq-chevron{transform:rotate(180deg)}
    .faq-body{max-height:0;overflow:hidden;transition:max-height .3s ease}
    .faq-body.open{max-height:260px}
    .faq-answer{padding:.1rem 0 1.2rem;font-size:.85rem;color:var(--ink2);line-height:1.75}
    .faq-answer a{color:var(--amber);text-decoration:underline;text-underline-offset:3px}

    /* FINAL CTA */
    .final-cta{background:var(--bg);padding:8rem 2rem;text-align:center;position:relative;overflow:hidden}
    .cta-glow{position:absolute;inset:0;pointer-events:none;background:radial-gradient(ellipse 60% 80% at 50% 50%,rgba(232,163,37,.06) 0%,transparent 70%)}
    .cta-grid{position:absolute;inset:0;pointer-events:none;background-image:linear-gradient(rgba(255,255,255,.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.02) 1px,transparent 1px);background-size:40px 40px;mask-image:radial-gradient(ellipse 70% 100% at 50% 50%,black 0%,transparent 80%)}
    .cta-inner{position:relative;z-index:1;max-width:600px;margin:0 auto}
    .cta-h2{font-family:var(--serif);font-size:clamp(2.5rem,5.5vw,4.5rem);font-weight:400;letter-spacing:-.035em;line-height:1.05;margin-bottom:.9rem}
    .cta-h2 em{font-style:italic;color:var(--amber)}
    .cta-p{color:var(--ink2);font-size:.92rem;max-width:46ch;margin:0 auto 2.5rem;line-height:1.7}
    .cta-actions{display:flex;gap:.7rem;justify-content:center;flex-wrap:wrap}
    .cta-fine{font-family:var(--mono);font-size:.6rem;color:var(--ink3);margin-top:1.2rem;display:flex;gap:1.2rem;justify-content:center;flex-wrap:wrap}
    .cta-fine span::before{content:'✓ ';color:var(--green)}

    /* FOOTER */
    .footer{background:var(--surface);border-top:1px solid var(--border)}
    .footer-main{max-width:1100px;margin:0 auto;padding:3.5rem 2rem 2rem;display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem}
    .footer-brand{font-family:var(--serif);font-size:1.25rem;font-style:italic;color:var(--ink);margin-bottom:.6rem;display:flex;align-items:center;gap:.5rem}
    .footer-brand-mark{width:22px;height:22px;border-radius:5px;background:linear-gradient(135deg,var(--amber),var(--amber2));display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-style:italic;font-size:.75rem;color:#0c0c0e}
    .footer-tagline{font-size:.8rem;color:var(--ink3);line-height:1.65;max-width:24ch;margin-bottom:1.2rem}
    .footer-socials{display:flex;gap:.4rem}
    .footer-social{width:30px;height:30px;border:1px solid var(--border2);border-radius:6px;display:flex;align-items:center;justify-content:center;color:var(--ink3);transition:all .2s;font-size:.75rem}
    .footer-social:hover{border-color:var(--amber);color:var(--amber)}
    .footer-col-label{font-family:var(--mono);font-size:.57rem;color:var(--ink3);letter-spacing:.14em;text-transform:uppercase;margin-bottom:.9rem}
    .footer-links{list-style:none;display:flex;flex-direction:column;gap:.45rem}
    .footer-links a{font-size:.8rem;color:var(--ink3);transition:color .18s}
    .footer-links a:hover{color:var(--amber)}
    .footer-bottom{max-width:1100px;margin:0 auto;padding:1.2rem 2rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem}
    .footer-copy{font-family:var(--mono);font-size:.58rem;color:var(--ink3)}
    .footer-legal{display:flex;gap:1.5rem}
    .footer-legal a{font-family:var(--mono);font-size:.58rem;color:var(--ink3);transition:color .18s}
    .footer-legal a:hover{color:var(--ink2)}

    /* ANIMS */
    @keyframes fadeUp{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}
    .reveal{opacity:0;transform:translateY(22px);transition:opacity .65s ease,transform .65s ease}
    .reveal.in{opacity:1;transform:translateY(0)}
    .r-d1{transition-delay:.1s}.r-d2{transition-delay:.2s}

    /* RESPONSIVE */
    @media(max-width:1024px){.problem-layout,.preview-layout{grid-template-columns:1fr;gap:3rem}.hiw-steps{grid-template-columns:1fr 1fr}.footer-main{grid-template-columns:1fr 1fr}}
    @media(max-width:768px){.nav{padding:.8rem 1.2rem}.nav-links,.nav-sep,.nav-login{display:none}.section{padding:4.5rem 1.2rem}.hero{padding:6.5rem 1.2rem 4rem}.hero-metrics{grid-template-columns:1fr;gap:0}.metric{border-right:none;border-bottom:1px solid var(--border);padding:1.2rem 0}.metric:last-child{border-bottom:none}.hiw-steps{grid-template-columns:1fr}.features-grid{grid-template-columns:1fr}.testi-grid{grid-template-columns:1fr}.pricing-grid{grid-template-columns:1fr}.footer-main{grid-template-columns:1fr;gap:2rem}}
  </style>
</head>
<body>

<!-- NAV -->
<nav class="nav" id="nav">
  <a href="{{ route('home') }}" class="nav-brand">
    <div class="nav-mark">P</div> ProofWork
  </a>
  <div class="nav-links">
    <a href="#how" class="nav-link">How it works</a>
    <a href="#features" class="nav-link">Features</a>
    <a href="#pricing" class="nav-link">Pricing</a>
    <a href="{{ route('about') }}" class="nav-link">About</a>
  </div>
  <div style="display:flex;align-items:center;gap:.5rem">
    <div class="nav-sep"></div>
    <a href="{{ route('login') }}" class="nav-login">Log in</a>
    <a href="{{ route('register') }}" class="nav-cta">
      Start for free
      <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 6h7m-3-3 3 3-3 3"/></svg>
    </a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg">
    <div class="hero-glow"></div>
    <div class="hero-grid"></div>
  </div>
  <div class="hero-inner">
    <div class="hero-tag">
      <span class="hero-tag-dot"></span>
      Live product · Free plan available · No credit card
    </div>
    <h1 class="hero-h1">
      <span class="h1-line1">Your clients</span>
      <span class="h1-line2">don't trust</span>
      <span class="h1-line3">your invoices.</span>
    </h1>
    <p class="hero-sub">
      ProofWork connects to <strong>GitHub, Linear, Notion & Calendar</strong> and
      auto-generates verifiable proof of work reports every Friday.
      <strong>Timestamped. Sourced. Undeniable.</strong>
    </p>
    <div class="hero-actions">
      <a href="{{ route('register') }}" class="btn-primary">
        Start for free — takes 2 minutes
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h8M7.5 4l3 3-3 3"/></svg>
      </a>
      <a href="{{ route('demo') }}" class="btn-outline">▶ Live demo</a>
    </div>
    <div class="hero-trust">
      <span class="trust-item">Free plan — no card needed</span>
      <span class="trust-item">14-day Pro trial</span>
      <span class="trust-item">Set up in 5 minutes</span>
    </div>
    <div class="hero-metrics">
      <div class="metric">
        <span class="metric-number">3–5h</span>
        <span class="metric-label">saved per week</span>
      </div>
      <div class="metric">
        <span class="metric-number">0</span>
        <span class="metric-label">manual effort</span>
      </div>
      <div class="metric">
        <span class="metric-number">100%</span>
        <span class="metric-label">verified data</span>
      </div>
    </div>
  </div>
</section>

<!-- STRIP -->
<div class="strip">
  <div class="strip-label">Connects with tools you already use</div>
  <div class="strip-tools">
    <div class="strip-tool"><span>⌥</span> GitHub</div>
    <div class="strip-tool"><span>◈</span> Linear</div>
    <div class="strip-tool"><span>◎</span> Notion</div>
    <div class="strip-tool"><span>📅</span> Google Calendar</div>
    <div class="strip-tool"><span>◆</span> Jira</div>
    <div class="strip-tool" style="opacity:.35">+ more soon</div>
  </div>
</div>

<!-- PROBLEM -->
<section class="section problem-section">
  <div class="wrap problem-layout">
    <div class="reveal">
      <span class="section-tag">The problem</span>
      <h2 class="section-h2">Clients dispute invoices<br>when there's <em>no proof.</em></h2>
      <p class="section-desc" style="margin-bottom:0">You ship great work. But at invoice time, you have nothing concrete to show.</p>
      <ul class="problem-list">
        @foreach([
          ['✗','Manual reports nobody trusts','You spend every Friday writing updates from memory. Your client still wonders if you inflated the hours.'],
          ['✗','Invoices get disputed','"Prove you worked 40 hours this week." The message every freelancer dreads.'],
          ['✗','3–5 hours/week on admin','150+ hours per year on reporting instead of building. Real money lost.'],
          ['✗','Clients drift without visibility','No proof of progress = eroding trust = lost contracts.'],
        ] as [$icon,$title,$desc])
        <li class="prob">
          <div class="prob-icon">{{ $icon }}</div>
          <div><div class="prob-title">{{ $title }}</div><div class="prob-desc">{{ $desc }}</div></div>
        </li>
        @endforeach
      </ul>
    </div>
    <div class="reveal r-d1">
      <div class="invoice-card">
        <div class="inv-bar"></div>
        <div class="inv-head"><span class="inv-title">Invoice #0042 — October</span><span class="inv-disputed">DISPUTED</span></div>
        <div class="inv-body">
          @foreach([['Frontend development','$3,200'],['API integration','$1,800'],['Code reviews','$600']] as [$l,$a])
          <div class="inv-row"><span class="inv-label">{{ $l }}</span><span class="inv-amount">{{ $a }}</span></div>
          @endforeach
          <div class="inv-total"><span>Total</span><span class="inv-total-amount">$5,600</span></div>
          <div class="inv-message">
            <div class="inv-msg-text">"I don't see evidence of 40 hours. Can you provide a breakdown of what was actually done?"</div>
            <div class="inv-msg-from">— Client · 3 days ago</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="section" id="how" style="background:var(--bg)">
  <div class="wrap">
    <span class="section-tag reveal">How it works</span>
    <h2 class="section-h2 reveal">Connect once.<br><em>Reports ship themselves.</em></h2>
    <p class="section-desc reveal">Five minutes to set up. Zero effort every week after.</p>
    <div class="hiw-steps reveal">
      @foreach([
        ['01','🔗','Connect your tools','One-time OAuth for GitHub, Linear, Notion, Calendar. Read-only — we never write to your tools.'],
        ['02','⚙','We collect silently','Every day, ProofWork pulls your commits, tasks, meetings automatically.'],
        ['03','✦','AI writes the narrative','Every Friday, AI turns raw activity into a clean report your client loves.'],
        ['04','📧','Client gets a link','One URL. No client account needed. Professional. Verified.'],
      ] as [$num,$icon,$title,$desc])
      <div class="hiw-step">
        @if(!$loop->last)<div class="step-arrow">→</div>@endif
        <div class="step-num">STEP {{ $num }}</div>
        <div class="step-icon">{{ $icon }}</div>
        <div class="step-title">{{ $title }}</div>
        <div class="step-desc">{{ $desc }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- PREVIEW -->
<section class="section preview-section">
  <div class="wrap preview-layout">
    <div class="reveal">
      <span class="section-tag">Sample output</span>
      <h2 class="section-h2">This is what your<br><em>client sees.</em></h2>
      <p class="section-desc" style="margin-bottom:0">Every item sourced directly from your tools. Cryptographically hashed.</p>
      <ul class="feature-list">
        @foreach(['Every commit linked to GitHub','Every task linked to Linear or Jira','Meeting decisions auto-logged from Calendar','AI narrative that tells the full story','One shareable URL — no client account needed'] as $f)
        <li>{{ $f }}</li>
        @endforeach
      </ul>
      <a href="{{ route('demo') }}" class="btn-primary" style="display:inline-flex">
        Try with your GitHub
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h8M7.5 4l3 3-3 3"/></svg>
      </a>
    </div>
    <div class="reveal r-d1">
      <div class="report-card">
        <div class="rc-bar"><span class="rc-dot r"></span><span class="rc-dot y"></span><span class="rc-dot g"></span><span class="rc-url">proofwork.app/r/acme-w18-2026</span></div>
        <div class="rc-head">
          <div><div class="rc-title">Weekly Report — Acme Corp</div><div class="rc-period">Apr 28–May 4, 2026 · Auto-generated</div></div>
          <div class="rc-verified">VERIFIED</div>
        </div>
        @foreach([
          ['⌥','14 commits merged','Payment refactor · <span class="rc-hl">double-charge fixed</span> · 6 tests','GitHub · May 4'],
          ['◈','11 tasks closed','API v2 · <span class="rc-hl">onboarding shipped</span> · 4 bugs','Linear · Apr 28–May 4'],
          ['📅','3 meetings · 5 decisions','Sprint review · <span class="rc-hl">launch confirmed</span>','Calendar · Auto'],
        ] as [$i,$t,$d,$s])
        <div class="rc-entry">
          <div class="rc-e-icon">{{ $i }}</div>
          <div><div class="rc-e-title">{{ $t }}</div><div class="rc-e-detail">{!! $d !!}</div></div>
          <div class="rc-meta">{{ $s }}</div>
        </div>
        @endforeach
        <div class="rc-foot"><span>proofwork.app/r/acme-w18-2026</span><span style="opacity:.3">hash: 9d3f7a2e</span></div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="section" id="features" style="background:var(--bg)">
  <div class="wrap">
    <span class="section-tag reveal">What's included</span>
    <h2 class="section-h2 reveal">Everything you need.<br><em>Nothing you don't.</em></h2>
    <p class="section-desc reveal" style="margin-bottom:0">Six integrations. One report. Zero manual work.</p>
    <div class="features-grid reveal">
      @foreach([
        ['01','⌥','GitHub','Commits, PRs, and code reviews pulled automatically.'],
        ['02','◈','Linear & Jira','Completed tasks with real timestamps — not a todo list.'],
        ['03','📅','Google Calendar','Meetings and decisions auto-logged every week.'],
        ['04','✦','AI Summary','Raw activity → clean narrative your client actually reads.'],
        ['05','🔗','Share Link','One URL. No client login. Works on any device.'],
        ['06','⚡','Auto Weekly Send','Goes out every Friday. Set it once, forget it.'],
      ] as [$n,$i,$t,$d])
      <div class="feat"><div class="feat-num">{{ $n }}</div><div class="feat-icon">{{ $i }}</div><div class="feat-title">{{ $t }}</div><div class="feat-desc">{{ $d }}</div></div>
      @endforeach
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section testi-section">
  <div class="wrap">
    <span class="section-tag reveal">What users say</span>
    <h2 class="section-h2 reveal">Real freelancers.<br><em>Real results.</em></h2>
    <p class="section-desc reveal" style="margin-bottom:2.5rem">From developers and designers using ProofWork in production.</p>
    <div class="testi-grid">
      @foreach([
        ['★★★★★','I used to spend every Friday copy-pasting GitHub screenshots into a Google Doc. ProofWork eliminated that completely. My clients said the reports look more professional than what agencies send.','Marcus T.','Full-stack dev · 6 clients','#e8a325','M'],
        ['★★★★★','A client disputed a $4,200 invoice. I had nothing concrete. With ProofWork I send a link — they verify every line item themselves. Disputes went to zero.','Sarah K.','Product designer · Agency owner','#4a9eff','S'],
        ['★★★★☆','Linear + GitHub combo saves me 3 hours a week. As a solo dev with 4 clients that\'s 12 hours a month back. I\'d pay double the current price.','Adrien M.','Indie developer · 4 clients','#27c93f','A'],
      ] as [$stars,$quote,$name,$role,$color,$letter])
      <div class="testi reveal">
        <div class="testi-stars">{{ $stars }}</div>
        <div class="testi-quote">{{ $quote }}</div>
        <div class="testi-author">
          <div class="testi-av" style="background:{{ $color }};color:#000">{{ $letter }}</div>
          <div><div class="testi-name">{{ $name }}</div><div class="testi-role">{{ $role }}</div></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- PRICING -->
<section class="section" id="pricing" style="background:var(--bg)">
  <div class="wrap">
    <span class="section-tag reveal">Pricing</span>
    <h2 class="section-h2 reveal">Simple. Transparent. <em>Fair.</em></h2>
    <p class="section-desc reveal" style="margin-bottom:2.5rem">Start free today. Upgrade when you need more.</p>
    <div class="pricing-grid reveal">
      @foreach([
        ['Free','$0','forever · no card',false,[['1 project','1 client','GitHub + 1 integration','Manual report send','Shareable public link']],'Start for free','plan-btn-ghost',route('register')],
        ['Pro','$19','per month · cancel anytime',true,[['Unlimited projects','Unlimited clients','All 6 integrations','AI-generated summaries','Auto weekly send','Custom branding','Priority support']],'Start 14-day free trial →','plan-btn-amber',route('register')],
        ['Agency','$49','per month · 5 seats',false,[['Everything in Pro','5 team members','White-label reports','Custom domain','Dedicated support']],'Get Agency plan','plan-btn-ghost',route('register')],
      ] as [$name,$price,$cycle,$popular,$feats,$label,$cls,$href])
      <div class="plan {{ $popular ? 'popular' : '' }}">
        @if($popular)<div class="plan-tag hot">Most popular</div>@endif
        <div class="plan-name">{{ $name }}</div>
        <div class="plan-price">{{ $price }}</div>
        <div class="plan-cycle">{{ $cycle }}</div>
        <ul class="plan-perks">@foreach($feats[0] as $f)<li>{{ $f }}</li>@endforeach</ul>
        <a href="{{ $href }}" class="plan-btn {{ $cls }}" style="display:block;text-align:center">{{ $label }}</a>
      </div>
      @endforeach
    </div>
    <div class="pricing-note reveal">
      @foreach(['Free plan — no card needed','14-day Pro trial included','Cancel anytime','Stripe-secured payments'] as $n)
      <span class="pricing-note-item">{{ $n }}</span>
      @endforeach
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="section faq-section" id="faq">
  <div class="wrap">
    <span class="section-tag reveal">FAQ</span>
    <h2 class="section-h2 reveal">Common questions.</h2>
    <div class="faq-list reveal">
      @foreach([
        ['How long does setup take?','About 5 minutes. Create your account, connect GitHub with one OAuth click, create a project, and your first report generates automatically. No configuration required.'],
        ['Which integrations are available?','GitHub, Linear, Jira, Notion, and Google Calendar are available now. Figma, Slack, and Trello are on the roadmap. Check the roadmap page to vote on priorities.'],
        ['Does my client need an account?','Never. You send them a URL. They open a clean, professional report in their browser — no signup, no app. One click and they see everything.'],
        ['Is my code secure?','We use read-only OAuth tokens. We can never push code, close issues, or write anything to your tools. All data is encrypted at rest and in transit.'],
        ['Can I try Pro features before paying?','Yes — every new account gets a 14-day Pro trial automatically. No credit card needed to start.'],
        ['What if I want to cancel?','Cancel anytime from your billing settings — no fees, no questions. Your data is yours and can be exported as CSV before leaving.'],
      ] as [$q,$a])
      <div class="faq-item">
        <button class="faq-q" onclick="toggleFaq(this)"><span>{{ $q }}</span><span class="faq-chevron">▾</span></button>
        <div class="faq-body"><div class="faq-answer">{{ $a }}</div></div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section class="final-cta">
  <div class="cta-glow"></div>
  <div class="cta-grid"></div>
  <div class="cta-inner">
    <span class="section-tag" style="position:relative">Ready to start?</span>
    <h2 class="cta-h2" style="position:relative">Never write a<br><em>report again.</em></h2>
    <p class="cta-p" style="position:relative">Create your free account in 30 seconds. Connect GitHub. Your first report is ready by Friday.</p>
    <div class="cta-actions" style="position:relative">
      <a href="{{ route('register') }}" class="btn-primary" style="font-size:.92rem;padding:1rem 2.2rem">
        Create free account
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h8M7.5 4l3 3-3 3"/></svg>
      </a>
      <a href="{{ route('login') }}" class="btn-outline" style="font-size:.88rem">Already have an account →</a>
    </div>
    <div class="cta-fine" style="position:relative">
      <span>Free plan included</span>
      <span>No credit card</span>
      <span>14-day Pro trial</span>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-main">
    <div>
      <div class="footer-brand">
        <div class="footer-brand-mark">P</div> ProofWork
      </div>
      <p class="footer-tagline">Auto-generate client-ready proof of work. No more "trust me" invoices.</p>
      <div class="footer-socials">
        <a href="https://twitter.com/proofwork" target="_blank" class="footer-social" title="Twitter">𝕏</a>
        <a href="https://github.com/ahmatissa08" target="_blank" class="footer-social" title="GitHub">⌥</a>
        <a href="mailto:addimiahmat@gmail.com" class="footer-social" title="Email">✉</a>
      </div>
    </div>
    <div>
      <div class="footer-col-label">Product</div>
      <ul class="footer-links">
        <li><a href="#how">How it works</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#pricing">Pricing</a></li>
        <li><a href="{{ route('demo') }}">Live demo</a></li>
        <li><a href="{{ route('register') }}">Start for free</a></li>
      </ul>
    </div>
    <div>
      <div class="footer-col-label">Company</div>
      <ul class="footer-links">
        <li><a href="{{ route('about') }}">About</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
        <li><a href="{{ route('roadmap') }}">Roadmap</a></li>
        <li><a href="{{ route('changelog') }}">Changelog</a></li>
        <li><a href="#faq">FAQ</a></li>
      </ul>
    </div>
    <div>
      <div class="footer-col-label">Legal</div>
      <ul class="footer-links">
        <li><a href="{{ route('privacy') }}">Privacy policy</a></li>
        <li><a href="{{ route('terms') }}">Terms of service</a></li>
        <li><a href="{{ route('security') }}">Security</a></li>
      </ul>
      <div style="margin-top:1.5rem">
        <div class="footer-col-label">Contact</div>
        <a href="mailto:addimiahmat@gmail.com" style="font-size:.78rem;color:var(--ink3)">addimiahmat@gmail.com</a>
        <div style="font-family:var(--mono);font-size:.6rem;color:var(--ink3);opacity:.4;margin-top:.9rem;line-height:1.6">Built solo in Casablanca 🇲🇦</div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <span class="footer-copy">© {{ date('Y') }} ProofWork · Built by Ahmat Issa</span>
    <div class="footer-legal">
      <a href="{{ route('privacy') }}">Privacy</a>
      <a href="{{ route('terms') }}">Terms</a>
      <a href="{{ route('contact') }}">Contact</a>
    </div>
  </div>
</footer>

<script>
window.addEventListener('scroll', () => {
  document.getElementById('nav').classList.toggle('scrolled', window.scrollY > 60);
}, { passive: true });

function toggleFaq(btn) {
  const body = btn.nextElementSibling;
  const isOpen = btn.classList.contains('open');
  document.querySelectorAll('.faq-q.open').forEach(b => { b.classList.remove('open'); b.nextElementSibling.classList.remove('open'); });
  if (!isOpen) { btn.classList.add('open'); body.classList.add('open'); }
}

const ro = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); ro.unobserve(e.target); } });
}, { threshold: 0.07 });
document.querySelectorAll('.reveal').forEach(el => ro.observe(el));
</script>
</body>
</html>
