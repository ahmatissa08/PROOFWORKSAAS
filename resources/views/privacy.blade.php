@extends('layouts.guest')
@section('title', 'Privacy Policy — ProofWork')

@push('styles')
<style>
:root{--bg:#0c0c0e;--surface:#131316;--surface2:#18181c;--border:#242428;--border2:#2e2e34;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.7}
::-webkit-scrollbar{width:4px}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:2px}

nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:1.1rem 2.5rem;border-bottom:1px solid var(--border);background:rgba(12,12,14,.9);backdrop-filter:blur(20px)}
.logo{font-family:var(--serif);font-size:1.25rem;font-style:italic;color:var(--ink);text-decoration:none}
.logo-word{font-family:var(--sans);font-style:normal;font-weight:300;font-size:1.2rem;letter-spacing:-.02em}
.back-btn{font-family:var(--mono);font-size:.7rem;color:var(--ink3);text-decoration:none;padding:.4rem .9rem;border:1px solid var(--border2);border-radius:4px;transition:color .2s,border-color .2s}
.back-btn:hover{color:var(--ink);border-color:var(--ink3)}

.page-wrap{max-width:740px;margin:0 auto;padding:8rem 2.5rem 6rem}

.page-eyebrow{font-family:var(--mono);font-size:.62rem;color:var(--ink3);letter-spacing:.14em;text-transform:uppercase;margin-bottom:1rem}
.page-title{font-family:var(--serif);font-size:clamp(2.2rem,5vw,3.5rem);font-style:italic;font-weight:400;letter-spacing:-.03em;margin-bottom:.5rem}
.page-date{font-family:var(--mono);font-size:.68rem;color:var(--ink3);margin-bottom:3rem;padding-bottom:2rem;border-bottom:1px solid var(--border)}

.doc-section{margin-bottom:2.5rem}
.doc-section h2{font-family:var(--serif);font-size:1.3rem;font-style:italic;font-weight:400;color:var(--amber);margin-bottom:.8rem;letter-spacing:-.01em}
.doc-section p{font-size:.9rem;color:var(--ink2);line-height:1.75;margin-bottom:.9rem}
.doc-section ul{list-style:none;display:flex;flex-direction:column;gap:.5rem;margin-bottom:.9rem;padding-left:.5rem}
.doc-section ul li{font-size:.88rem;color:var(--ink2);display:flex;gap:.7rem;align-items:flex-start;line-height:1.65}
.doc-section ul li::before{content:'→';color:var(--amber);font-family:var(--mono);font-size:.7rem;flex-shrink:0;margin-top:.2rem;opacity:.7}
.doc-section a{color:var(--amber);text-decoration:underline;text-underline-offset:3px}
.doc-section strong{color:var(--ink);font-weight:500}

.highlight-box{background:var(--surface);border:1px solid var(--border);border-left:3px solid var(--amber);border-radius:0 6px 6px 0;padding:1.2rem 1.5rem;margin:1.5rem 0}
.highlight-box p{font-size:.85rem;color:var(--ink2);margin:0;line-height:1.65}

footer{border-top:1px solid var(--border);padding:2rem 2.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
footer p{font-family:var(--mono);font-size:.6rem;color:var(--ink3)}
footer a{font-family:var(--mono);font-size:.6rem;color:var(--ink3);text-decoration:none;transition:color .2s}
footer a:hover{color:var(--amber)}

@media(max-width:600px){.page-wrap{padding:7rem 1.2rem 4rem}nav{padding:1rem 1.2rem}}
</style>
@endpush

@section('content')
<nav>
  <a href="{{ route('home') }}" class="logo">Proof<span class="logo-word">Work</span></a>
  <a href="{{ route('home') }}" class="back-btn">← Back to home</a>
</nav>

<div class="page-wrap">
  <div class="page-eyebrow">Legal</div>
  <h1 class="page-title">Privacy Policy</h1>
  <p class="page-date">Last updated: {{ date('F d, Y') }} &nbsp;·&nbsp; Effective: {{ date('F d, Y') }}</p>

  <div class="highlight-box">
    <p><strong>Short version:</strong> We collect your email to notify you when ProofWork launches. We don't sell your data, we don't share it with advertisers, and you can delete it anytime by emailing us.</p>
  </div>

  <div class="doc-section">
    <h2>1. Who we are</h2>
    <p>ProofWork is an independent software project built by a solo founder. When we say "ProofWork", "we", "us" or "our" in this policy, we mean the person operating this service.</p>
    <p>Contact: <a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a></p>
  </div>

  <div class="doc-section">
    <h2>2. What data we collect</h2>
    <p>When you join our waitlist, we collect:</p>
    <ul>
      <li><strong>Email address</strong> — to notify you when we launch and send product updates</li>
      <li><strong>Name</strong> (optional) — to personalize our communications</li>
      <li><strong>Plan interest</strong> — to understand which tier you're interested in</li>
      <li><strong>IP address</strong> — for security and fraud prevention only</li>
      <li><strong>Browser / user agent</strong> — for technical debugging only</li>
      <li><strong>Referral source</strong> — to understand how you found us (e.g. Reddit, Twitter)</li>
    </ul>
    <p>We do <strong>not</strong> collect payment information, browsing history, or any data beyond what's listed above at this stage.</p>
  </div>

  <div class="doc-section">
    <h2>3. How we use your data</h2>
    <p>We use your data exclusively for:</p>
    <ul>
      <li>Sending you a confirmation email when you join the waitlist</li>
      <li>Notifying you when ProofWork launches or enters early access</li>
      <li>Sending product updates, launch announcements, and early-user offers</li>
      <li>Understanding aggregate demand (plan interest, referral sources)</li>
    </ul>
    <p>We will <strong>never</strong> sell your data, share it with advertisers, or use it for purposes other than those listed above.</p>
  </div>

  <div class="doc-section">
    <h2>4. Data storage & security</h2>
    <p>Your data is stored in a MySQL database hosted on our server. We apply industry-standard security practices including encrypted connections (HTTPS), access controls, and regular backups.</p>
    <p>We retain your data until you request deletion, or until 12 months after ProofWork's public launch if you never converted to a paid plan.</p>
  </div>

  <div class="doc-section">
    <h2>5. Third-party services</h2>
    <p>We use the following third-party services in our operations:</p>
    <ul>
      <li><strong>Gmail / Google SMTP</strong> — to send confirmation and launch emails. Google's privacy policy applies to email transit.</li>
      <li><strong>Telegram</strong> — for our internal admin notifications only. We send a summary of new signups to a private Telegram bot. Your full email is included in this notification but is only visible to the ProofWork founder.</li>
    </ul>
    <p>We do not use analytics trackers, advertising pixels, or any other third-party data collection on this website.</p>
  </div>

  <div class="doc-section">
    <h2>6. Your rights</h2>
    <p>You have the right to:</p>
    <ul>
      <li><strong>Access</strong> — request a copy of the data we hold about you</li>
      <li><strong>Correction</strong> — ask us to correct inaccurate data</li>
      <li><strong>Deletion</strong> — ask us to delete your data entirely ("right to be forgotten")</li>
      <li><strong>Portability</strong> — receive your data in a machine-readable format</li>
      <li><strong>Objection</strong> — opt out of any communications at any time</li>
    </ul>
    <p>To exercise any of these rights, email us at <a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a>. We will respond within 30 days.</p>
  </div>

  <div class="doc-section">
    <h2>7. Cookies</h2>
    <p>This website uses only a single session cookie necessary for security (CSRF protection). We do not use advertising cookies, tracking cookies, or analytics cookies. No cookie consent banner is required because we only use strictly necessary cookies.</p>
  </div>

  <div class="doc-section">
    <h2>8. Children's privacy</h2>
    <p>ProofWork is not directed at children under 16. We do not knowingly collect data from anyone under 16 years of age. If you believe we have inadvertently collected such data, contact us immediately.</p>
  </div>

  <div class="doc-section">
    <h2>9. Changes to this policy</h2>
    <p>We may update this policy as the product evolves. We will notify waitlist subscribers of material changes by email before they take effect. The "Last updated" date at the top of this page always reflects the most recent version.</p>
  </div>

  <div class="doc-section">
    <h2>10. Contact</h2>
    <p>Questions, concerns, or data requests:</p>
    <p>
      <strong>Email:</strong> <a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a><br>
      <strong>Response time:</strong> Within 30 days (usually much faster)
    </p>
  </div>
</div>

<footer>
  <p>© {{ date('Y') }} ProofWork</p>
  <div style="display:flex;gap:1.5rem">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('terms') }}">Terms of Service</a>
    <a href="mailto:addimiahmat@gmail.com">Contact</a>
  </div>
</footer>
@endsection
