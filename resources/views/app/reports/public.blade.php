<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $report->title }} - ProofWork</title>
  <meta name="description" content="Proof of work report for {{ $report->period_start->format('M d') }} - {{ $report->period_end->format('M d, Y') }}">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist+Mono:wght@400;500&family=Geist:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
  :root{--bg:#0c0c0e;--surface:#131316;--surface2:#18181c;--border:#242428;--border2:#2e2e34;--ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;--amber:#e8a325;--coral:#e85c3a;--sky:#4a9eff;--green:#27c93f;--mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif}
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  html{scroll-behavior:smooth}
  body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6;min-height:100vh}
  ::-webkit-scrollbar{width:4px}
  ::-webkit-scrollbar-thumb{background:var(--border2);border-radius:2px}
  .topbar{height:3px;background:linear-gradient(90deg,var(--amber),var(--sky))}
  .report-header{max-width:820px;margin:0 auto;padding:2.5rem 2rem 2rem}
  .report-nav{display:flex;align-items:center;justify-content:space-between;margin-bottom:2.5rem}
  .report-brand{font-family:var(--serif);font-size:1rem;font-style:italic;color:var(--ink2);text-decoration:none}
  .verified-badge{display:inline-flex;align-items:center;gap:.4rem;background:rgba(39,201,63,.08);border:1px solid rgba(39,201,63,.18);color:var(--green);font-family:var(--mono);font-size:.58rem;padding:.25rem .65rem;border-radius:20px;letter-spacing:.08em}
  .verified-badge::before{content:'';width:6px;height:6px;background:var(--green);border-radius:50%;animation:pulse 2s infinite}
  @keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
  .report-title{font-family:var(--serif);font-size:clamp(1.8rem,4vw,2.8rem);font-style:italic;font-weight:400;letter-spacing:-.03em;margin-bottom:.5rem}
  .report-meta{display:flex;gap:1.5rem;flex-wrap:wrap;margin-top:.8rem}
  .meta-item{font-family:var(--mono);font-size:.65rem;color:var(--ink3);display:flex;align-items:center;gap:.35rem}
  .meta-item::before{content:'';width:4px;height:4px;background:var(--amber);border-radius:50%;opacity:.6}
  .report-body{max-width:820px;margin:0 auto;padding:0 2rem 4rem}
  .summary-card{background:var(--surface);border:1px solid var(--border);border-left:3px solid var(--amber);border-radius:0 8px 8px 0;padding:1.4rem 1.8rem;margin-bottom:2rem}
  .summary-label{font-family:var(--mono);font-size:.58rem;color:var(--amber);letter-spacing:.12em;text-transform:uppercase;opacity:.8;margin-bottom:.5rem}
  .summary-text{font-size:.95rem;color:var(--ink2);line-height:1.75;font-style:italic}
  .stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--border);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:2rem}
  .stat{background:var(--surface);padding:1.1rem 1.3rem}
  .stat-label{font-family:var(--mono);font-size:.56rem;color:var(--ink3);letter-spacing:.1em;text-transform:uppercase;margin-bottom:.3rem}
  .stat-val{font-family:var(--serif);font-size:1.8rem;font-style:italic;color:var(--ink);line-height:1}
  .stat-val.amber{color:var(--amber)}.stat-val.sky{color:var(--sky)}.stat-val.green{color:var(--green)}
  .section{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:1.5rem}
  .section-header{display:flex;align-items:center;gap:.7rem;padding:.9rem 1.3rem;border-bottom:1px solid var(--border)}
  .section-icon{width:28px;height:28px;border:1px solid var(--border2);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0}
  .section-title{font-size:.85rem;font-weight:600}
  .section-count{margin-left:auto;font-family:var(--mono);font-size:.6rem;color:var(--ink3);background:var(--surface2);border:1px solid var(--border2);padding:.12rem .45rem;border-radius:3px}
  .entry{display:grid;grid-template-columns:1fr auto;gap:1rem;align-items:start;padding:1rem 1.3rem;border-bottom:1px solid rgba(255,255,255,.03);transition:background .12s}
  .entry:last-child{border-bottom:none}
  .entry:hover{background:rgba(255,255,255,.015)}
  .entry-title{font-size:.85rem;font-weight:500;color:var(--ink);margin-bottom:.2rem}
  .entry-desc{font-size:.78rem;color:var(--ink3);line-height:1.5}
  .entry-link{font-family:var(--mono);font-size:.6rem;color:var(--sky);text-decoration:none;margin-top:.3rem;display:inline-block}
  .entry-link:hover{text-decoration:underline}
  .entry-date{font-family:var(--mono);font-size:.6rem;color:var(--ink3);opacity:.6;white-space:nowrap;text-align:right}
  .entry-type{font-family:var(--mono);font-size:.56rem;color:var(--ink3);background:var(--surface2);border:1px solid var(--border2);padding:.1rem .4rem;border-radius:3px;margin-bottom:.3rem;display:inline-block}
  .hash-bar{display:flex;align-items:center;justify-content:space-between;padding:.7rem 1.3rem;background:var(--surface2);border:1px solid var(--border);border-radius:8px;margin-bottom:2rem}
  .hash-url{font-family:var(--mono);font-size:.62rem;color:var(--ink3)}
  .hash-val{font-family:var(--mono);font-size:.6rem;color:var(--ink3);opacity:.35}
  .report-footer{text-align:center;padding:2rem;border-top:1px solid var(--border)}
  .report-footer p{font-family:var(--mono);font-size:.62rem;color:var(--ink3)}
  .report-footer a{color:var(--amber);text-decoration:none}
  @media(max-width:600px){
    .report-header,.report-body{padding-left:1.2rem;padding-right:1.2rem}
    .stats-row{grid-template-columns:1fr 1fr}
    .entry{grid-template-columns:1fr}
  }
  </style>
</head>
<body>
<div class="topbar"></div>

<div class="report-header">
  <div class="report-nav">
    <a href="/" class="report-brand">ProofWork</a>
    <div class="verified-badge">VERIFIED REPORT</div>
  </div>

  <h1 class="report-title">{{ $report->title }}</h1>

  <div class="report-meta">
    <span class="meta-item">{{ $report->periodLabel() }}</span>
    @if($report->project)
    <span class="meta-item">{{ $report->project->name }}</span>
    @endif
    <span class="meta-item">Generated {{ $report->created_at->format('M d, Y') }}</span>
    <span class="meta-item">{{ $report->view_count }} {{ Str::plural('view', $report->view_count) }}</span>
  </div>
</div>

<div class="report-body">
  @if($report->ai_summary)
  <div class="summary-card">
    <div class="summary-label">Summary</div>
    <div class="summary-text">{{ $report->ai_summary }}</div>
  </div>
  @endif

  @php
    $bySource = $report->entries->groupBy('source');
    $githubCount = ($bySource['github'] ?? collect())->count();
    $linearCount = ($bySource['linear'] ?? collect())->count();
    $calendarCount = ($bySource['google_calendar'] ?? collect())->count();
    $totalEntries = $report->entries->count();
  @endphp
  <div class="stats-row">
    <div class="stat">
      <div class="stat-label">Total items</div>
      <div class="stat-val amber">{{ $totalEntries }}</div>
    </div>
    <div class="stat">
      <div class="stat-label">Code changes</div>
      <div class="stat-val">{{ $githubCount }}</div>
    </div>
    <div class="stat">
      <div class="stat-label">Tasks done</div>
      <div class="stat-val sky">{{ $linearCount }}</div>
    </div>
    <div class="stat">
      <div class="stat-label">Meetings</div>
      <div class="stat-val green">{{ $calendarCount }}</div>
    </div>
  </div>

  @foreach($bySource as $source => $entries)
  @php
    $sourceConfig = match($source) {
      'github' => ['icon' => 'G', 'label' => 'GitHub'],
      'linear' => ['icon' => 'L', 'label' => 'Linear'],
      'notion' => ['icon' => 'N', 'label' => 'Notion'],
      'google_calendar' => ['icon' => 'C', 'label' => 'Google Calendar'],
      'manual' => ['icon' => 'M', 'label' => 'Manual entries'],
      default => ['icon' => '*', 'label' => ucfirst($source)],
    };
  @endphp
  <div class="section">
    <div class="section-header">
      <div class="section-icon">{{ $sourceConfig['icon'] }}</div>
      <div class="section-title">{{ $sourceConfig['label'] }}</div>
      <div class="section-count">{{ $entries->count() }} {{ Str::plural('item', $entries->count()) }}</div>
    </div>
    @foreach($entries as $entry)
    <div class="entry">
      <div>
        <div class="entry-type">{{ $entry->type }}</div>
        <div class="entry-title">{{ $entry->title }}</div>
        @if($entry->description)
        <div class="entry-desc">{{ $entry->description }}</div>
        @endif
        @if($entry->source_url)
        <a href="{{ $entry->source_url }}" target="_blank" class="entry-link">View source</a>
        @endif
      </div>
      <div class="entry-date">
        @if($entry->occurred_at)
        {{ $entry->occurred_at->format('M d, Y') }}
        @endif
      </div>
    </div>
    @endforeach
  </div>
  @endforeach

  <div class="hash-bar">
    <div class="hash-url">{{ $report->shareUrl() }}</div>
    <div class="hash-val">{{ $report->share_token }}</div>
  </div>
</div>

<div class="report-footer">
  <p>Generated with <a href="/">ProofWork</a></p>
</div>
</body>
</html>
