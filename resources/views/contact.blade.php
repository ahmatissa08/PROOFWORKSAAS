<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Contact — ProofWork</title>
  <meta name="description" content="Get in touch with ProofWork. We read and respond to every message.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital,wght@0,400;1,400&family=Geist+Mono:wght@300;400;500&family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root{--bg:#0c0c0e;--surface:#111113;--surface2:#18181c;--border:#242428;--border2:#2e2e35;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--amber2:#f5b43a;--coral:#e85c3a;--green:#27c93f;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html{-webkit-font-smoothing:antialiased}
    body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6}
    ::-webkit-scrollbar{width:3px}::-webkit-scrollbar-thumb{background:var(--border2)}
    a{text-decoration:none}
    nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:.9rem 2rem;background:rgba(12,12,14,.9);border-bottom:1px solid var(--border);backdrop-filter:blur(20px)}
    .nav-brand{display:flex;align-items:center;gap:.5rem;color:var(--ink);font-weight:600;font-size:.9rem;letter-spacing:-.02em}
    .nav-mark{width:26px;height:26px;border-radius:6px;background:linear-gradient(135deg,var(--amber),var(--amber2));display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-style:italic;font-size:.88rem;color:#0c0c0e}
    .nav-right{display:flex;gap:.5rem}
    .nav-back{font-size:.78rem;color:var(--ink3);padding:.4rem .8rem;border-radius:5px;transition:all .18s}
    .nav-back:hover{color:var(--ink);background:rgba(255,255,255,.05)}
    .nav-cta{background:var(--amber);color:#000;font-size:.75rem;font-weight:700;padding:.45rem 1rem;border-radius:5px;transition:all .18s}
    .nav-cta:hover{background:var(--amber2)}
    .page{max-width:1000px;margin:0 auto;padding:7.5rem 2rem 6rem}
    .eyebrow{font-family:var(--mono);font-size:.6rem;color:var(--ink3);letter-spacing:.15em;text-transform:uppercase;margin-bottom:.9rem}
    .page-title{font-family:var(--serif);font-size:clamp(2.5rem,6vw,4rem);font-weight:400;font-style:italic;letter-spacing:-.03em;line-height:1.05;margin-bottom:.6rem}
    .page-title em{color:var(--amber)}
    .page-sub{font-size:.9rem;color:var(--ink2);max-width:50ch;line-height:1.65;margin-bottom:3.5rem}
    .grid{display:grid;grid-template-columns:300px 1fr;gap:3.5rem;align-items:start}
    .info-label{font-family:var(--mono);font-size:.58rem;color:var(--ink3);letter-spacing:.13em;text-transform:uppercase;margin-bottom:.9rem}
    .method{display:flex;align-items:center;gap:.85rem;padding:.75rem 0;border-bottom:1px solid var(--border)}
    .method:last-child{border-bottom:none}
    .m-icon{width:34px;height:34px;border:1px solid var(--border2);border-radius:7px;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:.82rem;flex-shrink:0}
    .m-label{font-size:.78rem;font-weight:500;color:var(--ink);margin-bottom:.1rem}
    .m-value{font-family:var(--mono);font-size:.62rem;color:var(--ink3)}
    .m-value a{color:var(--ink3);transition:color .18s}
    .m-value a:hover{color:var(--amber)}
    .response-card{background:var(--surface);border:1px solid var(--border);border-radius:9px;padding:1.1rem 1.3rem;margin-top:1.5rem}
    .response-head{display:flex;align-items:center;gap:.45rem;font-size:.78rem;font-weight:500;margin-bottom:.35rem}
    .r-dot{width:7px;height:7px;border-radius:50%;background:var(--green);animation:pulse 2s infinite;flex-shrink:0}
    @keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}
    .response-sub{font-size:.76rem;color:var(--ink3);line-height:1.55}
    .topic-label{font-family:var(--mono);font-size:.58rem;color:var(--ink3);letter-spacing:.13em;text-transform:uppercase;margin:1.8rem 0 .7rem}
    .topic-pills{display:flex;flex-direction:column;gap:.35rem}
    .topic-pill{display:flex;align-items:center;gap:.6rem;background:var(--surface2);border:1px solid var(--border2);color:var(--ink2);font-size:.78rem;padding:.5rem .85rem;border-radius:6px;cursor:pointer;transition:all .18s;text-align:left;font-family:var(--sans);width:100%}
    .topic-pill:hover{border-color:var(--amber);color:var(--ink);background:rgba(232,163,37,.05)}
    .form-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden}
    .form-bar{height:3px;background:linear-gradient(90deg,var(--amber),#4a9eff,var(--amber2))}
    .form-body{padding:2rem}
    .form-row{margin-bottom:1.2rem}
    .form-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.2rem}
    label{display:block;font-family:var(--mono);font-size:.58rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.45rem}
    input,select,textarea{width:100%;background:var(--bg);border:1px solid var(--border2);color:var(--ink);padding:.75rem 1rem;font-family:var(--sans);font-size:.85rem;border-radius:6px;outline:none;transition:border-color .2s,box-shadow .2s;-webkit-appearance:none}
    input:focus,select:focus,textarea:focus{border-color:var(--amber);box-shadow:0 0 0 3px rgba(232,163,37,.07)}
    input::placeholder,textarea::placeholder{color:var(--ink3)}
    select{cursor:pointer;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='7' viewBox='0 0 10 7'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%235a5855' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center}
    select option{background:var(--surface)}
    textarea{resize:vertical;min-height:140px;line-height:1.65}
    .char-count{font-family:var(--mono);font-size:.58rem;color:var(--ink3);text-align:right;margin-top:.3rem;opacity:.6}
    .submit-btn{width:100%;background:var(--amber);color:#000;border:none;padding:.9rem;font-family:var(--sans);font-size:.86rem;font-weight:700;border-radius:6px;cursor:pointer;transition:all .18s;display:flex;align-items:center;justify-content:center;gap:.5rem}
    .submit-btn:hover{background:var(--amber2);transform:translateY(-1px)}
    .submit-btn:disabled{opacity:.45;cursor:not-allowed;transform:none}
    #form-status{display:none;padding:.75rem 1rem;border-radius:6px;font-family:var(--mono);font-size:.7rem;margin-top:.9rem;text-align:center}
    #form-status.success{background:rgba(39,201,63,.07);color:var(--green);border:1px solid rgba(39,201,63,.18)}
    #form-status.error{background:rgba(232,92,58,.07);color:var(--coral);border:1px solid rgba(232,92,58,.18)}
    footer{border-top:1px solid var(--border);padding:1.8rem 2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.8rem}
    footer p{font-family:var(--mono);font-size:.58rem;color:var(--ink3)}
    .foot-links{display:flex;gap:1.5rem}
    .foot-links a{font-family:var(--mono);font-size:.58rem;color:var(--ink3);transition:color .18s}
    .foot-links a:hover{color:var(--amber)}
    @media(max-width:768px){.grid{grid-template-columns:1fr}.form-2{grid-template-columns:1fr}.page{padding:7rem 1.2rem 4rem}nav{padding:.8rem 1.2rem}.nav-back{display:none}}
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
  <div class="eyebrow">Get in touch</div>
  <h1 class="page-title">We <em>read everything.</em></h1>
  <p class="page-sub">Question about the product, a billing issue, or a feature idea? Send a message — I respond personally within 24 hours.</p>
  <div class="grid">
    <div>
      <div class="info-label">Contact</div>
      <div class="method"><div class="m-icon">✉</div><div><div class="m-label">Email</div><div class="m-value"><a href="mailto:addimiahmat@gmail.com">addimiahmat@gmail.com</a></div></div></div>
      <div class="method"><div class="m-icon">𝕏</div><div><div class="m-label">Twitter / X</div><div class="m-value"><a href="https://twitter.com/proofwork" target="_blank">@proofwork</a></div></div></div>
      <div class="method"><div class="m-icon">⌥</div><div><div class="m-label">GitHub</div><div class="m-value"><a href="https://github.com/ahmatissa08" target="_blank">ahmatissa08</a></div></div></div>
      <div class="response-card">
        <div class="response-head"><span class="r-dot"></span>Usually responds within 24h</div>
        <div class="response-sub">Solo-built product — your message goes directly to the founder. No support tickets, no bots.</div>
      </div>
      <div class="topic-label">Quick topic</div>
      <div class="topic-pills">
        <button class="topic-pill" onclick="setTopic('bug report')">🐛 Report a bug</button>
        <button class="topic-pill" onclick="setTopic('feature request')">💡 Feature request</button>
        <button class="topic-pill" onclick="setTopic('billing')">💳 Billing question</button>
        <button class="topic-pill" onclick="setTopic('partnership')">🤝 Partnership</button>
        <button class="topic-pill" onclick="setTopic('feedback')">💬 General feedback</button>
      </div>
    </div>
    <div>
      <div class="form-card">
        <div class="form-bar"></div>
        <div class="form-body">
          <div class="form-2">
            <div><label for="f-name">Your name</label><input type="text" id="f-name" placeholder="Ahmat Issa" maxlength="120"></div>
            <div><label for="f-email">Email address</label><input type="email" id="f-email" placeholder="you@example.com"></div>
          </div>
          <div class="form-row">
            <label for="f-subject">Topic</label>
            <select id="f-subject">
              <option value="">Select a topic...</option>
              <option value="bug report">🐛 Bug report</option>
              <option value="feature request">💡 Feature request</option>
              <option value="billing">💳 Billing question</option>
              <option value="partnership">🤝 Partnership</option>
              <option value="feedback">💬 General feedback</option>
              <option value="other">❓ Other</option>
            </select>
          </div>
          <div class="form-row">
            <label for="f-message">Message</label>
            <textarea id="f-message" placeholder="Tell us what's on your mind..." maxlength="2000" oninput="document.getElementById('cc').textContent=this.value.length"></textarea>
            <div class="char-count"><span id="cc">0</span> / 2000</div>
          </div>
          <input type="text" id="f-hp" style="display:none;position:absolute;left:-9999px" tabindex="-1" autocomplete="off">
          <button class="submit-btn" id="f-btn" onclick="submitForm()">
            Send message
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h8M7.5 4l3 3-3 3"/></svg>
          </button>
          <div id="form-status"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<footer>
  <p>© {{ date('Y') }} ProofWork · Built by Ahmat Issa</p>
  <div class="foot-links">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('about') }}">About</a>
    <a href="{{ route('privacy') }}">Privacy</a>
    <a href="{{ route('terms') }}">Terms</a>
  </div>
</footer>
<script>
const CSRF = document.querySelector('meta[name=csrf-token]')?.content ?? '';
function setTopic(v){const s=document.getElementById('f-subject');for(let i=0;i<s.options.length;i++){if(s.options[i].value===v){s.selectedIndex=i;break}}document.getElementById('f-message').focus()}
async function submitForm(){
  const name=document.getElementById('f-name').value.trim();
  const email=document.getElementById('f-email').value.trim();
  const subject=document.getElementById('f-subject').value;
  const message=document.getElementById('f-message').value.trim();
  const btn=document.getElementById('f-btn');
  const status=document.getElementById('form-status');
  if(!name||!email||!subject||!message){status.style.display='block';status.className='error';status.textContent='⚠ Please fill in all fields.';return}
  if(!email.includes('@')){status.style.display='block';status.className='error';status.textContent='⚠ Please enter a valid email address.';return}
  btn.disabled=true;btn.textContent='Sending...';
  try{
    const res=await fetch('{{ route("contact.store") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:JSON.stringify({name,email,subject,message,website:document.getElementById('f-hp')?.value??''})});
    const data=await res.json();
    status.style.display='block';
    if(res.ok){status.className='success';status.textContent='✓ '+(data.message??'Message sent! We\'ll respond within 24 hours.');['f-name','f-email','f-message'].forEach(id=>document.getElementById(id).value='');document.getElementById('f-subject').selectedIndex=0;document.getElementById('cc').textContent='0';btn.textContent='✓ Sent!';}
    else{status.className='error';status.textContent='⚠ '+(data.message??'Something went wrong.');btn.disabled=false;btn.textContent='Send message';}
  }catch{status.style.display='block';status.className='error';status.textContent='⚠ Network error.';btn.disabled=false;btn.textContent='Send message';}
}
</script>
</body>
</html>
