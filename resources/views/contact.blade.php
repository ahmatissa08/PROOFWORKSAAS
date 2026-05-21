@extends('layouts.guest')
@section('title', 'Contact — ProofWork')
@section('og_title', 'Contact ProofWork')
@section('og_description', 'Got a question, feedback, or feature request? We read and respond to every message.')

@push('styles')
<style>
:root{--bg:#0c0c0e;--surface:#131316;--surface2:#18181c;--border:#242428;--border2:#2e2e34;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--green:#27c93f;--coral:#e85c3a;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6}
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

.page-wrap{max-width:900px;margin:0 auto;padding:8rem 2.5rem 6rem}

/* Header */
.page-eyebrow{font-family:var(--mono);font-size:.62rem;color:var(--ink3);letter-spacing:.14em;text-transform:uppercase;margin-bottom:1rem}
.page-title{font-family:var(--serif);font-size:clamp(2.5rem,6vw,4rem);font-style:italic;font-weight:400;letter-spacing:-.03em;margin-bottom:.75rem}
.page-title em{color:var(--amber)}
.page-sub{color:var(--ink2);font-size:.9rem;max-width:50ch;line-height:1.65;margin-bottom:3rem}

/* Grid */
.contact-grid{display:grid;grid-template-columns:1fr 1.6fr;gap:3rem;align-items:start}

/* Info column */
.contact-info{}
.info-section{margin-bottom:2rem}
.info-label{font-family:var(--mono);font-size:.6rem;color:var(--ink3);letter-spacing:.12em;text-transform:uppercase;margin-bottom:.8rem}

.contact-method{display:flex;align-items:center;gap:.75rem;padding:.7rem 0;border-bottom:1px solid var(--border)}
.contact-method:last-child{border-bottom:none}
.contact-icon{width:32px;height:32px;border:1px solid var(--border2);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0;background:var(--surface2)}
.contact-text{}
.contact-label{font-size:.78rem;font-weight:500;color:var(--ink)}
.contact-value{font-family:var(--mono);font-size:.65rem;color:var(--ink3);margin-top:.1rem}
.contact-value a{color:var(--ink3);text-decoration:none;transition:color .2s}
.contact-value a:hover{color:var(--amber)}

.response-note{background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:1.2rem 1.4rem;margin-top:1.5rem}
.response-note-title{font-size:.78rem;font-weight:500;margin-bottom:.4rem}
.response-note-text{font-size:.78rem;color:var(--ink2);line-height:1.6}
.response-dot{display:inline-block;width:7px;height:7px;background:var(--green);border-radius:50%;margin-right:.4rem;animation:pulse 2s infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}

/* Form column */
.contact-form-wrap{background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden}
.form-top{height:3px;background:linear-gradient(90deg,var(--amber),var(--sky))}
.form-body{padding:2rem}

.form-row{margin-bottom:1.2rem}
.form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.2rem}
label{display:block;font-family:var(--mono);font-size:.6rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.5rem}
input[type=text],input[type=email],select,textarea{width:100%;background:var(--bg);border:1px solid var(--border2);color:var(--ink);padding:.75rem 1rem;font-family:var(--sans);font-size:.85rem;border-radius:5px;outline:none;transition:border-color .2s,box-shadow .2s;-webkit-appearance:none}
input[type=text]:focus,input[type=email]:focus,select:focus,textarea:focus{border-color:var(--amber);box-shadow:0 0 0 3px rgba(232,163,37,.08)}
input::placeholder,textarea::placeholder{color:var(--ink3)}
select{cursor:pointer;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235a5855' d='M6 8L1 3h10z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center}
select option{background:var(--surface)}
textarea{resize:vertical;min-height:130px;line-height:1.6}
.submit-btn{width:100%;background:var(--amber);color:#000;border:none;padding:.9rem;font-family:var(--sans);font-size:.88rem;font-weight:600;border-radius:5px;cursor:pointer;transition:opacity .15s,transform .15s;letter-spacing:.02em}
.submit-btn:hover{opacity:.88;transform:translateY(-1px)}
.submit-btn:disabled{opacity:.5;cursor:not-allowed;transform:none}

#form-status{display:none;padding:.75rem 1rem;border-radius:5px;font-family:var(--mono);font-size:.72rem;margin-top:.8rem;letter-spacing:.04em}
#form-status.success{background:rgba(39,201,63,.08);color:var(--green);border:1px solid rgba(39,201,63,.2)}
#form-status.error{background:rgba(232,92,58,.08);color:var(--coral);border:1px solid rgba(232,92,58,.2)}

footer{border-top:1px solid var(--border);padding:2rem 2.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}
footer p{font-family:var(--mono);font-size:.6rem;color:var(--ink3)}
footer a{font-family:var(--mono);font-size:.6rem;color:var(--ink3);text-decoration:none;transition:color .2s}
footer a:hover{color:var(--amber)}

@media(max-width:768px){
  .contact-grid{grid-template-columns:1fr}
  .form-row-2{grid-template-columns:1fr}
  .page-wrap{padding:7rem 1.2rem 4rem}
  nav{padding:1rem 1.2rem}
  .nav-link{display:none}
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
  <div class="page-eyebrow">Get in touch</div>
  <h1 class="page-title">We <em>read everything.</em></h1>
  <p class="page-sub">Got a question, feedback, or a feature idea? Send a message — I respond personally within 24 hours.</p>

  <div class="contact-grid">

    <!-- Info -->
    <div class="contact-info">
      <div class="info-section">
        <div class="info-label">Contact methods</div>

        <div class="contact-method">
          <div class="contact-icon">✉</div>
          <div class="contact-text">
            <div class="contact-label">Email</div>
            <div class="contact-value"><a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a></div>
          </div>
        </div>

        <div class="contact-method">
          <div class="contact-icon">𝕏</div>
          <div class="contact-text">
            <div class="contact-label">Twitter / X</div>
            <div class="contact-value"><a href="https://twitter.com/proofwork" target="_blank">@proofwork</a></div>
          </div>
        </div>

        <div class="contact-method">
          <div class="contact-icon">⌥</div>
          <div class="contact-text">
            <div class="contact-label">GitHub</div>
            <div class="contact-value"><a href="https://github.com/ahmatissa08" target="_blank">ahmatissa08</a></div>
          </div>
        </div>

      </div>

      <div class="response-note">
        <div class="response-note-title">
          <span class="response-dot"></span>Usually responds within 24h
        </div>
        <div class="response-note-text">
          Built solo — no support team, just the founder. Your message goes directly to Ahmat's inbox and Telegram.
        </div>
      </div>

      <!-- FAQ shortcuts -->
      <div class="info-section" style="margin-top:1.8rem">
        <div class="info-label">Common topics</div>
        <div style="display:flex;flex-direction:column;gap:.4rem;margin-top:.5rem">
          @foreach([
            ['💡', 'Feature request', 'feature request'],
            ['🐛', 'Report a bug', 'bug report'],
            ['🤝', 'Partnership', 'partnership'],
            ['📰', 'Press inquiry', 'press'],
            ['💬', 'General feedback', 'feedback'],
          ] as [$icon, $label, $subject])
          <button onclick="setSubject('{{ $subject }}')"
                  style="background:var(--surface2);border:1px solid var(--border2);color:var(--ink2);padding:.5rem .9rem;border-radius:5px;cursor:pointer;font-family:var(--sans);font-size:.78rem;text-align:left;display:flex;align-items:center;gap:.6rem;transition:all .2s"
                  onmouseover="this.style.borderColor='var(--amber)';this.style.color='var(--ink)'"
                  onmouseout="this.style.borderColor='var(--border2)';this.style.color='var(--ink2)'">
            <span>{{ $icon }}</span> {{ $label }}
          </button>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Form -->
    <div>
      <div class="contact-form-wrap">
        <div class="form-top"></div>
        <div class="form-body">
          <div class="form-row-2">
            <div>
              <label for="name">Your name</label>
              <input type="text" id="name" placeholder="Ahmat Issa" maxlength="120" />
            </div>
            <div>
              <label for="email">Email address</label>
              <input type="email" id="email" placeholder="you@example.com" />
            </div>
          </div>

          <div class="form-row">
            <label for="subject">Subject</label>
            <select id="subject">
              <option value="">Select a topic...</option>
              <option value="feature request">💡 Feature request</option>
              <option value="bug report">🐛 Bug report</option>
              <option value="partnership">🤝 Partnership</option>
              <option value="press">📰 Press inquiry</option>
              <option value="feedback">💬 General feedback</option>
              <option value="other">❓ Other</option>
            </select>
          </div>

          <div class="form-row">
            <label for="message">Message</label>
            <textarea id="message" placeholder="Tell us what's on your mind..."></textarea>
          </div>

          <!-- Honeypot -->
          <input type="text" name="website" id="website" style="display:none;position:absolute;left:-9999px" tabindex="-1" autocomplete="off" />

          <button class="submit-btn" id="submit-btn" onclick="submitContact()">
            Send message →
          </button>
          <div id="form-status"></div>
        </div>
      </div>
    </div>

  </div>
</div>

<footer>
  <p>© {{ date('Y') }} ProofWork · Built by Ahmat Issa</p>
  <div style="display:flex;gap:1.5rem">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('about') }}">About</a>
    <a href="{{ route('privacy') }}">Privacy</a>
    <a href="{{ route('terms') }}">Terms</a>
  </div>
</footer>
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name=csrf-token]')?.content ?? '';

function setSubject(val) {
  const sel = document.getElementById('subject');
  for (let i = 0; i < sel.options.length; i++) {
    if (sel.options[i].value === val) { sel.selectedIndex = i; break; }
  }
  document.getElementById('message').focus();
}

async function submitContact() {
  const name    = document.getElementById('name').value.trim();
  const email   = document.getElementById('email').value.trim();
  const subject = document.getElementById('subject').value;
  const message = document.getElementById('message').value.trim();
  const btn     = document.getElementById('submit-btn');
  const status  = document.getElementById('form-status');

  // Validate
  if (!name || !email || !subject || !message) {
    status.style.display = 'block';
    status.className = 'error';
    status.textContent = 'Please fill in all fields.';
    return;
  }

  if (!email.includes('@')) {
    status.style.display = 'block';
    status.className = 'error';
    status.textContent = 'Please enter a valid email address.';
    return;
  }

  btn.disabled = true;
  btn.textContent = 'Sending...';

  try {
    const res  = await fetch('{{ route("contact.store") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        name, email, subject, message,
        website: document.getElementById('website')?.value ?? '',
      })
    });

    const data = await res.json();
    status.style.display = 'block';

    if (res.ok) {
      status.className = 'success';
      status.textContent = '✓ ' + data.message;
      document.getElementById('name').value    = '';
      document.getElementById('email').value   = '';
      document.getElementById('subject').selectedIndex = 0;
      document.getElementById('message').value = '';
      btn.textContent = 'Sent! ✓';
    } else {
      status.className = 'error';
      status.textContent = data.message ?? 'Something went wrong. Please try again.';
      btn.disabled = false;
      btn.textContent = 'Send message →';
    }
  } catch(e) {
    status.style.display = 'block';
    status.className = 'error';
    status.textContent = 'Network error. Please try again.';
    btn.disabled = false;
    btn.textContent = 'Send message →';
  }
}
</script>
@endpush
