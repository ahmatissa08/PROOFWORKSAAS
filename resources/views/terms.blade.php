@extends('layouts.guest')
@section('title', 'Terms of Service — ProofWork')

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
.doc-section h2{font-family:var(--serif);font-size:1.3rem;font-style:italic;font-weight:400;color:var(--amber);margin-bottom:.8rem}
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
  <h1 class="page-title">Terms of Service</h1>
  <p class="page-date">Last updated: {{ date('F d, Y') }} &nbsp;·&nbsp; Effective: {{ date('F d, Y') }}</p>

  <div class="highlight-box">
    <p><strong>Short version:</strong> By joining our waitlist you agree to receive launch communications from us. We're in pre-launch phase — no paid service exists yet. We'll update these terms when the product launches.</p>
  </div>

  <div class="doc-section">
    <h2>1. Acceptance of terms</h2>
    <p>By accessing proofwork.app and joining our waitlist, you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our service.</p>
    <p>These terms apply to the current pre-launch waitlist phase. Separate terms will govern the full product when it launches.</p>
  </div>

  <div class="doc-section">
    <h2>2. Description of service</h2>
    <p>ProofWork is a pre-launch SaaS product. Currently, the only available service is a waitlist registration that entitles you to:</p>
    <ul>
      <li>Early access to ProofWork before public launch</li>
      <li>Discounted or free access as described at the time of signup (currently: 3 months free on Pro for early users)</li>
      <li>Product updates and launch announcements by email</li>
    </ul>
    <p>No paid service is being offered at this time. No payment is required or collected.</p>
  </div>

  <div class="doc-section">
    <h2>3. Waitlist & early access</h2>
    <p>Joining the waitlist does not guarantee:</p>
    <ul>
      <li>That ProofWork will launch on any particular date</li>
      <li>That the product will include any specific feature</li>
      <li>Any specific pricing beyond what was communicated at the time of signup</li>
    </ul>
    <p>We reserve the right to modify early-access offers before the product launches. If we reduce the early-user benefit significantly, we will notify you by email before the change takes effect and give you the option to opt out.</p>
  </div>

  <div class="doc-section">
    <h2>4. Your responsibilities</h2>
    <p>By using this service you agree to:</p>
    <ul>
      <li>Provide accurate information (real email address)</li>
      <li>Not sign up with multiple accounts to game waitlist positioning</li>
      <li>Not attempt to scrape, attack, or reverse-engineer this website</li>
      <li>Not use automated tools to submit the waitlist form</li>
    </ul>
    <p>We reserve the right to remove you from the waitlist if you violate these terms.</p>
  </div>

  <div class="doc-section">
    <h2>5. Communications</h2>
    <p>By joining the waitlist you consent to receive:</p>
    <ul>
      <li>A one-time confirmation email at signup</li>
      <li>Launch announcement email when ProofWork becomes available</li>
      <li>Occasional product update emails (maximum 2–3 per month)</li>
    </ul>
    <p>You can unsubscribe at any time by replying "unsubscribe" to any email or by contacting us at <a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a>. Unsubscribing removes you from marketing emails but does not delete your data (see our <a href="{{ route('privacy') }}">Privacy Policy</a> for deletion requests).</p>
  </div>

  <div class="doc-section">
    <h2>6. Intellectual property</h2>
    <p>All content on this website — including the ProofWork name, logo, design, and copy — is the intellectual property of the ProofWork founder. You may not reproduce, distribute, or create derivative works without explicit written permission.</p>
  </div>

  <div class="doc-section">
    <h2>7. Disclaimer of warranties</h2>
    <p>The ProofWork website and waitlist service are provided "as is" without any warranty of any kind. We do not guarantee that the website will be available at all times, error-free, or secure. The waitlist is a pre-launch mechanism — the actual product does not yet exist in production.</p>
  </div>

  <div class="doc-section">
    <h2>8. Limitation of liability</h2>
    <p>To the maximum extent permitted by law, ProofWork shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of (or inability to use) this website or the waitlist service. Our total liability, if any, shall not exceed zero dollars, as no payment has been collected.</p>
  </div>

  <div class="doc-section">
    <h2>9. Changes to terms</h2>
    <p>We may update these terms as the product evolves from pre-launch to a live service. We will notify waitlist subscribers by email of any material changes at least 14 days before they take effect. Continued use of the service after that date constitutes acceptance of the new terms.</p>
  </div>

  <div class="doc-section">
    <h2>10. Governing law</h2>
    <p>These terms are governed by applicable law. For any dispute, we encourage you to first contact us directly at <a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a> — the vast majority of issues can be resolved quickly and informally.</p>
  </div>

  <div class="doc-section">
    <h2>11. Contact</h2>
    <p>
      <strong>Email:</strong> <a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a><br>
      <strong>Twitter:</strong> <a href="https://twitter.com/proofwork" target="_blank">@proofwork</a><br>
      <strong>GitHub:</strong> <a href="https://github.com/ahmatissa08" target="_blank">ahmatissa08</a>
    </p>
  </div>
</div>

<footer>
  <p>© {{ date('Y') }} ProofWork</p>
  <div style="display:flex;gap:1.5rem">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('privacy') }}">Privacy Policy</a>
    <a href="mailto:addimiahmat@gmail.com">Contact</a>
  </div>
</footer>
@endsection
