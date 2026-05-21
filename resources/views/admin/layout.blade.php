<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') — ProofWork</title>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@1&family=Geist+Mono:wght@400;500&family=Geist:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
  :root{--bg:#0c0c0e;--surface:#131316;--surface2:#18181c;--border:#242428;--border2:#2e2e34;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--coral:#e85c3a;--sky:#4a9eff;--green:#27c93f;--sidebar-w:210px;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{background:var(--bg);color:var(--ink);font-family:var(--sans);min-height:100vh;display:flex}
  ::-webkit-scrollbar{width:3px}::-webkit-scrollbar-thumb{background:var(--border2);border-radius:2px}

  .sidebar{width:var(--sidebar-w);flex-shrink:0;background:var(--surface);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:50}
  .sidebar-logo{padding:1.2rem 1rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:.5rem}
  .sidebar-logo-text{font-family:var(--serif);font-size:1.1rem;font-style:italic;color:var(--ink)}
  .sidebar-logo-badge{font-family:var(--mono);font-size:.55rem;color:var(--coral);background:rgba(232,92,58,.1);border:1px solid rgba(232,92,58,.2);padding:.15rem .45rem;border-radius:3px;letter-spacing:.08em;text-transform:uppercase}
  .sidebar-nav{flex:1;padding:.5rem 0;overflow-y:auto}
  .sidebar-section{font-family:var(--mono);font-size:.55rem;color:var(--ink3);letter-spacing:.14em;text-transform:uppercase;padding:.8rem 1rem .3rem}
  .nav-link{display:flex;align-items:center;gap:.65rem;padding:.52rem 1rem;color:var(--ink3);text-decoration:none;font-size:.8rem;transition:color .15s,background .15s;position:relative}
  .nav-link:hover{color:var(--ink);background:rgba(255,255,255,.03)}
  .nav-link.active{color:var(--ink);background:rgba(255,255,255,.04)}
  .nav-link.active::before{content:'';position:absolute;left:0;top:20%;bottom:20%;width:2px;background:var(--coral);border-radius:0 2px 2px 0}
  .nav-icon{font-size:.85rem;width:16px;text-align:center;flex-shrink:0;opacity:.7}
  .nav-link.active .nav-icon{opacity:1}
  .sidebar-bottom{border-top:1px solid var(--border);padding:.8rem}
  .logout-btn{width:100%;background:transparent;border:1px solid var(--border2);color:var(--ink3);font-family:var(--sans);font-size:.75rem;padding:.45rem;border-radius:4px;cursor:pointer;transition:all .15s}
  .logout-btn:hover{border-color:var(--coral);color:var(--coral)}

  .main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}
  .topbar{display:flex;align-items:center;justify-content:space-between;padding:.8rem 1.8rem;border-bottom:1px solid var(--border);background:rgba(12,12,14,.9);position:sticky;top:0;z-index:40;backdrop-filter:blur(12px)}
  .topbar-breadcrumb{font-family:var(--mono);font-size:.7rem;color:var(--ink3);display:flex;align-items:center;gap:.4rem}
  .topbar-breadcrumb a{color:var(--ink3);text-decoration:none}.topbar-breadcrumb a:hover{color:var(--ink2)}
  .topbar-breadcrumb .sep{opacity:.3}.topbar-breadcrumb .current{color:var(--ink2)}
  .topbar-right{display:flex;align-items:center;gap:.5rem}
  .back-site-btn{font-family:var(--mono);font-size:.65rem;color:var(--ink3);text-decoration:none;border:1px solid var(--border2);padding:.3rem .7rem;border-radius:4px;transition:all .15s}
  .back-site-btn:hover{color:var(--ink);border-color:var(--ink3)}

  .page-content{padding:1.8rem;flex:1}
  .page-header{margin-bottom:1.8rem;display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem}
  .page-title{font-family:var(--serif);font-size:1.7rem;font-style:italic;font-weight:400;letter-spacing:-.02em}
  .page-sub{font-size:.82rem;color:var(--ink3);margin-top:.2rem}

  /* Stats */
  .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:1px;background:var(--border);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:1.5rem}
  .stat{background:var(--surface);padding:1.1rem 1.3rem}
  .stat-label{font-family:var(--mono);font-size:.56rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.3rem}
  .stat-val{font-family:var(--serif);font-size:1.9rem;font-style:italic;color:var(--ink);line-height:1}
  .stat-val.amber{color:var(--amber)}.stat-val.green{color:var(--green)}.stat-val.sky{color:var(--sky)}.stat-val.coral{color:var(--coral)}
  .stat-sub{font-family:var(--mono);font-size:.58rem;color:var(--ink3);margin-top:.2rem}

  /* Cards */
  .card{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden}
  .card-header{display:flex;align-items:center;justify-content:space-between;padding:.9rem 1.3rem;border-bottom:1px solid var(--border)}
  .card-title{font-size:.82rem;font-weight:600}
  .card-body{padding:1.3rem}

  /* Buttons */
  .btn{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1rem;border-radius:4px;font-family:var(--sans);font-size:.78rem;font-weight:500;cursor:pointer;transition:all .15s;text-decoration:none;border:none}
  .btn-primary{background:var(--amber);color:#000;font-weight:600}.btn-primary:hover{opacity:.88}
  .btn-ghost{background:transparent;border:1px solid var(--border2);color:var(--ink2)}.btn-ghost:hover{color:var(--ink);border-color:var(--ink3)}
  .btn-danger{background:transparent;border:1px solid rgba(232,92,58,.3);color:var(--coral)}.btn-danger:hover{background:rgba(232,92,58,.08)}
  .btn-sm{padding:.35rem .75rem;font-size:.72rem}

  /* Table */
  .table-wrap{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden}
  table{width:100%;border-collapse:collapse}
  thead th{font-family:var(--mono);font-size:.56rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;padding:.7rem 1.2rem;text-align:left;border-bottom:1px solid var(--border);background:var(--surface2)}
  tbody tr{border-bottom:1px solid rgba(255,255,255,.03);transition:background .12s}
  tbody tr:last-child{border-bottom:none}
  tbody tr:hover{background:rgba(255,255,255,.015)}
  tbody td{padding:.75rem 1.2rem;font-size:.78rem;color:var(--ink2)}
  tbody td.td-main{color:var(--ink);font-weight:500}

  /* Badges */
  .badge{display:inline-block;padding:.1rem .45rem;border-radius:3px;font-family:var(--mono);font-size:.56rem;letter-spacing:.06em;text-transform:uppercase;font-weight:500}
  .badge-green{background:rgba(39,201,63,.08);color:var(--green);border:1px solid rgba(39,201,63,.15)}
  .badge-amber{background:rgba(232,163,37,.1);color:var(--amber);border:1px solid rgba(232,163,37,.18)}
  .badge-gray{background:rgba(255,255,255,.04);color:var(--ink3);border:1px solid var(--border2)}
  .badge-sky{background:rgba(74,158,255,.08);color:var(--sky);border:1px solid rgba(74,158,255,.15)}
  .badge-coral{background:rgba(232,92,58,.08);color:var(--coral);border:1px solid rgba(232,92,58,.15)}

  /* Forms */
  .form-group{margin-bottom:1.2rem}
  .form-label{display:block;font-family:var(--mono);font-size:.58rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.45rem}
  .form-input,.form-select,.form-textarea{width:100%;background:var(--bg);border:1px solid var(--border2);color:var(--ink);padding:.7rem .9rem;font-family:var(--sans);font-size:.84rem;border-radius:4px;outline:none;transition:border-color .2s}
  .form-input:focus,.form-select:focus,.form-textarea:focus{border-color:var(--amber)}
  .form-input::placeholder,.form-textarea::placeholder{color:var(--ink3)}
  .form-textarea{resize:vertical;min-height:100px;line-height:1.6}
  .form-select{cursor:pointer;-webkit-appearance:none}

  /* Alert */
  .alert{padding:.8rem 1rem;border-radius:5px;font-size:.82rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
  .alert-success{background:rgba(39,201,63,.08);border:1px solid rgba(39,201,63,.2);color:var(--green)}
  .alert-error{background:rgba(232,92,58,.08);border:1px solid rgba(232,92,58,.2);color:var(--coral)}

  /* Search */
  .search-bar{display:flex;gap:.5rem;margin-bottom:1.2rem;flex-wrap:wrap}
  .search-input{background:var(--surface);border:1px solid var(--border2);color:var(--ink);padding:.6rem .9rem;font-family:var(--mono);font-size:.75rem;border-radius:5px;outline:none;transition:border-color .2s;min-width:220px}
  .search-input:focus{border-color:var(--amber)}
  .search-input::placeholder{color:var(--ink3)}

  /* Grid */
  .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;margin-bottom:1.2rem}

  /* Pagination */
  .pagination{padding:.8rem 1.2rem;border-top:1px solid var(--border);display:flex;gap:.4rem;flex-wrap:wrap}
  .pagination a,.pagination span{font-family:var(--mono);font-size:.62rem;padding:.25rem .5rem;border-radius:3px;border:1px solid var(--border);color:var(--ink3);text-decoration:none}
  .pagination a:hover{color:var(--ink);border-color:var(--ink3)}
  .pagination .active span{background:var(--amber);color:#000;border-color:transparent}

  /* Impersonation bar */
  .impersonate-bar{background:rgba(232,163,37,.1);border-bottom:1px solid rgba(232,163,37,.2);padding:.5rem 1.8rem;display:flex;align-items:center;gap:1rem;font-family:var(--mono);font-size:.68rem;color:var(--amber)}
  </style>
  @stack('styles')
</head>
<body>

<!-- Impersonation bar -->
@if(session('admin_impersonating'))
<div style="position:fixed;top:0;left:0;right:0;z-index:200" class="impersonate-bar">
  ⚠ Impersonating {{ auth()->user()->name }}
  <a href="{{ route('admin.stop-impersonating') }}" style="color:var(--amber);margin-left:auto;text-decoration:underline">Stop impersonating →</a>
</div>
@endif

<!-- SIDEBAR -->
<aside class="sidebar" style="{{ session('admin_impersonating') ? 'top:36px' : '' }}">
  <div class="sidebar-logo">
    <span class="sidebar-logo-text">ProofWork</span>
    <span class="sidebar-logo-badge">Admin</span>
  </div>

  <nav class="sidebar-nav">
    <div class="sidebar-section">Overview</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <span class="nav-icon">⊞</span> Dashboard
    </a>

    <div class="sidebar-section">Users</div>
    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
      <span class="nav-icon">👤</span> All users
    </a>

    <div class="sidebar-section">Content</div>
    <a href="{{ route('admin.projects') }}" class="nav-link {{ request()->routeIs('admin.projects') ? 'active' : '' }}">
      <span class="nav-icon">◈</span> Projects
    </a>
    <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
      <span class="nav-icon">📄</span> Reports
    </a>

    <div class="sidebar-section">Communication</div>
    <a href="{{ route('admin.broadcast') }}" class="nav-link {{ request()->routeIs('admin.broadcast*') ? 'active' : '' }}">
      <span class="nav-icon">📢</span> Broadcast
    </a>

    <div class="sidebar-section">System</div>
    <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
      <span class="nav-icon">⚙</span> Settings
    </a>
  </nav>

  <div class="sidebar-bottom">
    <form action="{{ route('admin.logout') }}" method="POST">
      @csrf
      <button type="submit" class="logout-btn">Logout admin</button>
    </form>
    <div style="margin-top:.6rem;text-align:center">
      <a href="{{ route('dashboard') }}" class="back-site-btn">← Back to app</a>
    </div>
  </div>
</aside>

<!-- MAIN -->
<div class="main" style="{{ session('admin_impersonating') ? 'margin-top:36px' : '' }}">
  <header class="topbar">
    <div class="topbar-breadcrumb">
      <a href="{{ route('admin.dashboard') }}">Admin</a>
      <span class="sep">›</span>
      @yield('breadcrumb', '<span class="current">Dashboard</span>')
    </div>
    <div class="topbar-right">
      <span style="font-family:var(--mono);font-size:.62rem;color:var(--ink3)">{{ now()->format('d M Y · H:i') }}</span>
    </div>
  </header>

  @if(session('success'))
  <div style="padding:0 1.8rem"><div class="alert alert-success" style="margin-top:1rem">✓ {{ session('success') }}</div></div>
  @endif
  @if(session('error'))
  <div style="padding:0 1.8rem"><div class="alert alert-error" style="margin-top:1rem">⚠ {{ session('error') }}</div></div>
  @endif

  <main class="page-content">
    @yield('content')
  </main>
</div>

@stack('scripts')
</body>
</html>
