<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'ProofWork') - ProofWork</title>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist+Mono:wght@400;500&family=Geist:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
  :root{
    --bg:#0c0c0e;--surface:#131316;--surface2:#18181c;--surface3:#1e1e22;
    --border:#242428;--border2:#2e2e34;
    --ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;
    --amber:#e8a325;--coral:#e85c3a;--sky:#4a9eff;--green:#27c93f;--purple:#a855f7;
    --sidebar-w:220px;
    --mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif;
  }
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  html{scroll-behavior:smooth}
  body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6;min-height:100vh;display:flex}
  ::-webkit-scrollbar{width:4px}
  ::-webkit-scrollbar-thumb{background:var(--border2);border-radius:2px}
  .sidebar{width:var(--sidebar-w);flex-shrink:0;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:50;overflow-y:auto}
  .sidebar-logo{padding:1.4rem 1.2rem 1rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:.6rem;text-decoration:none;flex-shrink:0}
  .sidebar-logo-text{font-family:var(--serif);font-size:1.15rem;font-style:italic;color:var(--ink)}
  .sidebar-logo-word{font-family:var(--sans);font-style:normal;font-weight:300;font-size:1.05rem}
  .sidebar-logo-badge{font-family:var(--mono);font-size:.38rem;color:var(--amber);letter-spacing:.15em;text-transform:uppercase;vertical-align:super;margin-left:1px}
  .sidebar-plan{margin:.8rem 1rem .5rem;font-family:var(--mono);font-size:.58rem;letter-spacing:.1em;text-transform:uppercase;padding:.25rem .6rem;border-radius:3px;display:inline-block}
  .sidebar-plan.free{background:rgba(255,255,255,.04);color:var(--ink3);border:1px solid var(--border2)}
  .sidebar-plan.pro{background:rgba(232,163,37,.1);color:var(--amber);border:1px solid rgba(232,163,37,.2)}
  .sidebar-plan.agency{background:rgba(74,158,255,.1);color:var(--sky);border:1px solid rgba(74,158,255,.2)}
  .trial-bar{margin:.4rem 1rem .8rem;background:rgba(232,163,37,.06);border:1px solid rgba(232,163,37,.15);border-radius:5px;padding:.5rem .7rem}
  .trial-bar-text{font-family:var(--mono);font-size:.6rem;color:var(--amber);line-height:1.4}
  .trial-bar-link{color:var(--amber);text-decoration:underline;text-underline-offset:2px}
  .sidebar-nav{flex:1;padding:.5rem 0}
  .sidebar-section-label{font-family:var(--mono);font-size:.55rem;color:var(--ink3);letter-spacing:.14em;text-transform:uppercase;padding:.8rem 1.2rem .3rem}
  .sidebar-link{display:flex;align-items:center;gap:.7rem;padding:.55rem 1.2rem;color:var(--ink3);text-decoration:none;font-size:.82rem;font-weight:400;border-radius:0;transition:color .15s,background .15s;position:relative}
  .sidebar-link:hover{color:var(--ink);background:rgba(255,255,255,.03)}
  .sidebar-link.active{color:var(--ink);background:rgba(255,255,255,.04)}
  .sidebar-link.active::before{content:'';position:absolute;left:0;top:20%;bottom:20%;width:2px;background:var(--amber);border-radius:0 2px 2px 0}
  .sidebar-link-icon{font-size:.88rem;width:18px;text-align:center;flex-shrink:0;opacity:.7}
  .sidebar-link.active .sidebar-link-icon{opacity:1}
  .sidebar-bottom{border-top:1px solid var(--border);padding:.8rem}
  .user-card{display:flex;align-items:center;gap:.7rem;padding:.5rem .5rem;border-radius:6px;cursor:pointer;transition:background .15s;text-decoration:none}
  .user-card:hover{background:rgba(255,255,255,.03)}
  .user-avatar{width:30px;height:30px;border-radius:50%;background:var(--amber);color:#000;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;font-family:var(--sans)}
  .user-name{font-size:.78rem;font-weight:500;color:var(--ink);line-height:1.2}
  .user-email{font-family:var(--mono);font-size:.58rem;color:var(--ink3);line-height:1.2}
  .main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}
  .topbar{display:flex;align-items:center;justify-content:space-between;padding:.85rem 2rem;border-bottom:1px solid var(--border);background:rgba(12,12,14,.85);backdrop-filter:blur(12px);position:sticky;top:0;z-index:40;gap:1rem}
  .topbar-left{display:flex;align-items:center;gap:.5rem}
  .topbar-breadcrumb{font-family:var(--mono);font-size:.72rem;color:var(--ink3);display:flex;align-items:center;gap:.4rem}
  .topbar-breadcrumb a{color:var(--ink3);text-decoration:none;transition:color .15s}
  .topbar-breadcrumb a:hover{color:var(--ink2)}
  .topbar-breadcrumb .sep{opacity:.3}
  .topbar-breadcrumb .current{color:var(--ink2)}
  .topbar-right{display:flex;align-items:center;gap:.6rem}
  .topbar-upgrade{background:var(--amber);color:#000;font-family:var(--sans);font-size:.72rem;font-weight:600;padding:.38rem .85rem;border-radius:4px;text-decoration:none;transition:opacity .15s;white-space:nowrap}
  .topbar-upgrade:hover{opacity:.88}
  .topbar-btn{background:var(--surface2);border:1px solid var(--border2);color:var(--ink2);font-family:var(--sans);font-size:.75rem;padding:.38rem .85rem;border-radius:4px;cursor:pointer;text-decoration:none;transition:all .15s;display:flex;align-items:center;gap:.4rem}
  .topbar-btn:hover{color:var(--ink);border-color:var(--border2)}
  .page-content{padding:2rem;flex:1}
  .page-header{margin-bottom:2rem;display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem}
  .page-title{font-family:var(--serif);font-size:1.8rem;font-style:italic;font-weight:400;letter-spacing:-.02em;color:var(--ink)}
  .page-sub{font-size:.85rem;color:var(--ink3);margin-top:.2rem}
  .btn{display:inline-flex;align-items:center;gap:.45rem;padding:.6rem 1.2rem;border-radius:5px;font-family:var(--sans);font-size:.82rem;font-weight:500;cursor:pointer;transition:all .15s;text-decoration:none;border:none;letter-spacing:.01em}
  .btn-primary{background:var(--amber);color:#000;font-weight:600}
  .btn-primary:hover{opacity:.88;transform:translateY(-1px)}
  .btn-ghost{background:transparent;border:1px solid var(--border2);color:var(--ink2)}
  .btn-ghost:hover{color:var(--ink);border-color:var(--ink3)}
  .btn-danger{background:transparent;border:1px solid rgba(232,92,58,.3);color:var(--coral)}
  .btn-danger:hover{background:rgba(232,92,58,.08)}
  .btn-sm{padding:.4rem .85rem;font-size:.75rem}
  .card{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden}
  .card-header{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.4rem;border-bottom:1px solid var(--border)}
  .card-title{font-size:.85rem;font-weight:600;letter-spacing:-.01em}
  .card-body{padding:1.4rem}
  .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1px;background:var(--border);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:2rem}
  .stat-card{background:var(--surface);padding:1.2rem 1.5rem}
  .stat-label{font-family:var(--mono);font-size:.58rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.3rem}
  .stat-value{font-family:var(--serif);font-size:2rem;font-style:italic;color:var(--ink);line-height:1}
  .stat-value.amber{color:var(--amber)}.stat-value.sky{color:var(--sky)}.stat-value.green{color:var(--green)}
  .stat-sub{font-family:var(--mono);font-size:.58rem;color:var(--ink3);margin-top:.2rem}
  .table-wrap{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden}
  table{width:100%;border-collapse:collapse}
  thead th{font-family:var(--mono);font-size:.58rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;padding:.75rem 1.4rem;text-align:left;border-bottom:1px solid var(--border);background:var(--surface2)}
  tbody tr{border-bottom:1px solid rgba(255,255,255,.03);transition:background .12s}
  tbody tr:last-child{border-bottom:none}
  tbody tr:hover{background:rgba(255,255,255,.015)}
  tbody td{padding:.85rem 1.4rem;font-size:.82rem;color:var(--ink2)}
  tbody td.td-main{color:var(--ink);font-weight:500}
  .badge{display:inline-block;padding:.12rem .5rem;border-radius:3px;font-family:var(--mono);font-size:.58rem;letter-spacing:.06em;text-transform:uppercase;font-weight:500}
  .badge-green{background:rgba(39,201,63,.08);color:var(--green);border:1px solid rgba(39,201,63,.15)}
  .badge-amber{background:rgba(232,163,37,.1);color:var(--amber);border:1px solid rgba(232,163,37,.18)}
  .badge-gray{background:rgba(255,255,255,.04);color:var(--ink3);border:1px solid var(--border2)}
  .badge-sky{background:rgba(74,158,255,.08);color:var(--sky);border:1px solid rgba(74,158,255,.15)}
  .badge-coral{background:rgba(232,92,58,.08);color:var(--coral);border:1px solid rgba(232,92,58,.15)}
  .form-group{margin-bottom:1.4rem}
  .form-label{display:block;font-family:var(--mono);font-size:.6rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.5rem}
  .form-input,.form-select,.form-textarea{width:100%;background:var(--bg);border:1px solid var(--border2);color:var(--ink);padding:.72rem 1rem;font-family:var(--sans);font-size:.85rem;border-radius:5px;outline:none;transition:border-color .2s,box-shadow .2s}
  .form-input:focus,.form-select:focus,.form-textarea:focus{border-color:var(--amber);box-shadow:0 0 0 3px rgba(232,163,37,.08)}
  .form-input::placeholder,.form-textarea::placeholder{color:var(--ink3)}
  .form-textarea{resize:vertical;min-height:100px;line-height:1.6}
  .form-select{cursor:pointer;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%235a5855' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center}
  .form-hint{font-family:var(--mono);font-size:.62rem;color:var(--ink3);margin-top:.4rem}
  .form-error{font-family:var(--mono);font-size:.62rem;color:var(--coral);margin-top:.35rem}
  .alert{padding:.85rem 1.1rem;border-radius:6px;font-size:.84rem;margin-bottom:1.2rem;display:flex;align-items:center;gap:.6rem}
  .alert-success{background:rgba(39,201,63,.08);border:1px solid rgba(39,201,63,.2);color:var(--green)}
  .alert-error{background:rgba(232,92,58,.08);border:1px solid rgba(232,92,58,.2);color:var(--coral)}
  .alert-info{background:rgba(74,158,255,.06);border:1px solid rgba(74,158,255,.15);color:var(--sky)}
  .alert-amber{background:rgba(232,163,37,.06);border:1px solid rgba(232,163,37,.15);color:var(--amber)}
  .modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.68);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;padding:1.5rem;z-index:120}
  .modal-backdrop.open{display:flex}
  .modal-card{width:min(480px,100%);background:var(--surface);border:1px solid var(--border2);border-radius:12px;box-shadow:0 20px 80px rgba(0,0,0,.45);overflow:hidden}
  .modal-header{padding:1.1rem 1.3rem;border-bottom:1px solid var(--border)}
  .modal-title{font-size:.95rem;font-weight:600;color:var(--ink)}
  .modal-body{padding:1.2rem 1.3rem;font-size:.84rem;color:var(--ink2);line-height:1.7}
  .modal-actions{display:flex;justify-content:flex-end;gap:.6rem;padding:1rem 1.3rem;border-top:1px solid var(--border);background:var(--surface2)}
  .empty-state{text-align:center;padding:4rem 2rem}
  .empty-icon{font-size:2.5rem;margin-bottom:1rem;opacity:.4}
  .empty-title{font-family:var(--serif);font-size:1.3rem;font-style:italic;font-weight:400;margin-bottom:.5rem;color:var(--ink2)}
  .empty-sub{font-size:.85rem;color:var(--ink3);margin-bottom:1.5rem;max-width:36ch;margin-left:auto;margin-right:auto}
  @media(max-width:900px){.sidebar{transform:translateX(-100%);transition:transform .25s ease}.sidebar.open{transform:translateX(0)}.main{margin-left:0}.page-content{padding:1.2rem}.stats-grid{grid-template-columns:1fr 1fr}}
  </style>
  @stack('styles')
</head>
<body>
<aside class="sidebar" id="sidebar">
  <a href="{{ route('dashboard') }}" class="sidebar-logo">
    <span class="sidebar-logo-text">Proof<span class="sidebar-logo-word">Work</span><sup class="sidebar-logo-badge">BETA</sup></span>
  </a>
  @php $user = auth()->user(); @endphp
  <span class="sidebar-plan {{ $user->plan }}">{{ ucfirst($user->plan) }} plan</span>
  @if($user->onTrial())
  <div class="trial-bar">
    <div class="trial-bar-text">
      Trial ends {{ $user->trial_ends_at->diffForHumans() }}<br>
      <a href="{{ route('billing.plans') }}" class="trial-bar-link">Upgrade to keep access</a>
    </div>
  </div>
  @endif
  <nav class="sidebar-nav">
    <div class="sidebar-section-label">Workspace</div>
    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="sidebar-link-icon">D</span> Dashboard</a>
    <a href="{{ route('projects.index') }}" class="sidebar-link {{ request()->routeIs('projects.*') ? 'active' : '' }}"><span class="sidebar-link-icon">P</span> Projects</a>
    <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"><span class="sidebar-link-icon">R</span> Reports</a>
    <a href="{{ route('clients.index') }}" class="sidebar-link {{ request()->routeIs('clients.*') ? 'active' : '' }}"><span class="sidebar-link-icon">C</span> Clients</a>
    <div class="sidebar-section-label">Setup</div>
    <a href="{{ route('integrations.index') }}" class="sidebar-link {{ request()->routeIs('integrations.*') ? 'active' : '' }}"><span class="sidebar-link-icon">I</span> Integrations</a>
    <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"><span class="sidebar-link-icon">S</span> Settings</a>
    <div class="sidebar-section-label">Billing</div>
    <a href="{{ route('billing.manage') }}" class="sidebar-link {{ request()->routeIs('billing.*') ? 'active' : '' }}"><span class="sidebar-link-icon">B</span> Billing</a>
    @if(!$user->isPro())
    <a href="{{ route('billing.plans') }}" class="sidebar-link" style="color:var(--amber)"><span class="sidebar-link-icon">+</span> Upgrade to Pro</a>
    @endif
  </nav>
  <div class="sidebar-bottom">
    <a href="{{ route('settings.index') }}" class="user-card">
      <div class="user-avatar">{{ $user->initials() }}</div>
      <div>
        <div class="user-name">{{ $user->name }}</div>
        <div class="user-email">{{ $user->email }}</div>
      </div>
    </a>
  </div>
</aside>
<div class="main">
  <header class="topbar">
    <div class="topbar-left">
      <button onclick="document.getElementById('sidebar').classList.toggle('open')" style="display:none;background:transparent;border:none;color:var(--ink2);cursor:pointer;font-size:1.1rem;padding:.2rem" id="sidebar-toggle">Menu</button>
      <div class="topbar-breadcrumb">
        @yield('breadcrumb', '<span class="current">Dashboard</span>')
      </div>
    </div>
    <div class="topbar-right">
      @if(!$user->isPro())
      <a href="{{ route('billing.plans') }}" class="topbar-upgrade">Upgrade to Pro</a>
      @endif
      <form action="{{ route('logout') }}" method="POST" style="display:inline">
        @csrf
        <button type="submit" class="topbar-btn">Logout</button>
      </form>
    </div>
  </header>
  <div style="padding:0 2rem">
    @if(session('success'))
    <div class="alert alert-success" style="margin-top:1rem">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
    <div class="alert alert-amber" style="margin-top:1rem">{{ session('warning') }}</div>
    @endif
    @if(session('upgrade_reason'))
    <div class="alert alert-amber" style="margin-top:1rem">{{ session('upgrade_reason') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-error" style="margin-top:1rem">{{ session('error') }}</div>
    @endif
    @if($errors->any())
    <div class="alert alert-error" style="margin-top:1rem">{{ $errors->first() }}</div>
    @endif
  </div>
  <main class="page-content">
    @yield('content')
  </main>
</div>
<div class="modal-backdrop" id="confirm-modal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="confirm-modal-title">
    <div class="modal-header">
      <div class="modal-title" id="confirm-modal-title">Confirm action</div>
    </div>
    <div class="modal-body" id="confirm-modal-message">
      Please confirm this action.
    </div>
    <div class="modal-actions">
      <button type="button" class="btn btn-ghost" id="confirm-modal-cancel">Cancel</button>
      <button type="button" class="btn btn-primary" id="confirm-modal-submit">Continue</button>
    </div>
  </div>
</div>
<script>
const toggle = document.getElementById('sidebar-toggle');
if (window.innerWidth <= 900) toggle.style.display = 'block';
window.addEventListener('resize', () => {
  toggle.style.display = window.innerWidth <= 900 ? 'block' : 'none';
});
document.addEventListener('click', e => {
  const sidebar = document.getElementById('sidebar');
  if (window.innerWidth <= 900 && sidebar.classList.contains('open') && !sidebar.contains(e.target) && e.target !== toggle) {
    sidebar.classList.remove('open');
  }
});
const confirmModal = document.getElementById('confirm-modal');
const confirmModalTitle = document.getElementById('confirm-modal-title');
const confirmModalMessage = document.getElementById('confirm-modal-message');
const confirmModalCancel = document.getElementById('confirm-modal-cancel');
const confirmModalSubmit = document.getElementById('confirm-modal-submit');
let confirmTargetForm = null;

function closeConfirmModal() {
  confirmModal.classList.remove('open');
  confirmModal.setAttribute('aria-hidden', 'true');
  confirmTargetForm = null;
}

document.addEventListener('click', event => {
  const button = event.target.closest('[data-confirm-form]');
  if (!button) return;

  event.preventDefault();

  confirmTargetForm = button.closest('form');
  if (!confirmTargetForm) return;

  confirmModalTitle.textContent = button.dataset.confirmTitle || 'Confirm action';
  confirmModalMessage.textContent = button.dataset.confirmMessage || 'Please confirm this action.';
  confirmModalSubmit.textContent = button.dataset.confirmSubmitLabel || 'Continue';
  confirmModal.classList.add('open');
  confirmModal.setAttribute('aria-hidden', 'false');
});

confirmModalCancel.addEventListener('click', closeConfirmModal);
confirmModal.addEventListener('click', event => {
  if (event.target === confirmModal) {
    closeConfirmModal();
  }
});
document.addEventListener('keydown', event => {
  if (event.key === 'Escape' && confirmModal.classList.contains('open')) {
    closeConfirmModal();
  }
});
confirmModalSubmit.addEventListener('click', () => {
  if (!confirmTargetForm) return;
  const form = confirmTargetForm;
  closeConfirmModal();
  form.submit();
});
</script>
@stack('scripts')
</body>
</html>
