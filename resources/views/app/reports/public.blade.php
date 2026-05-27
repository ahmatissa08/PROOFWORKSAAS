<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $report->title }} - ProofWork</title>
  <meta name="description" content="Proof of work report for {{ $report->period_start->format('M d') }} – {{ $report->period_end->format('M d, Y') }}">
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Geist+Mono:wght@400;500&family=Geist:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
  <style>
  :root{
    --bg:#0c0c0e;--surface:#131316;--surface2:#18181c;
    --border:#242428;--border2:#2e2e34;
    --ink:#f2f0eb;--ink2:#a09e9a;--ink3:#5a5855;
    --amber:#e8a325;--coral:#e85c3a;--sky:#4a9eff;--green:#27c93f;
    --mono:'Geist Mono',monospace;--sans:'Geist',sans-serif;--serif:'Instrument Serif',serif;
  }
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  html{scroll-behavior:smooth}
  body{background:var(--bg);color:var(--ink);font-family:var(--sans);line-height:1.6;min-height:100vh}
  ::-webkit-scrollbar{width:4px}
  ::-webkit-scrollbar-thumb{background:var(--border2);border-radius:2px}

  /* Gradient top bar */
  .top-accent { height:3px;background:linear-gradient(90deg,var(--amber),var(--sky),var(--green)); }

  /* Wrapper */
  .wrap { max-width:820px;margin:0 auto;padding:0 1.5rem; }

  /* Nav */
  .pub-nav {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.3rem 0; margin-bottom: .5rem;
  }
  .pub-brand {
    font-family: var(--serif); font-size: 1rem; font-style: italic;
    color: var(--ink2); text-decoration: none; display: flex; align-items: center; gap: .5rem;
  }
  .pub-brand i { font-size: 15px; color: var(--amber); }
  .verified-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    background: rgba(39,201,63,.08); border: 1px solid rgba(39,201,63,.2);
    color: var(--green); font-family: var(--mono); font-size: .58rem;
    padding: .28rem .7rem; border-radius: 99px; letter-spacing: .08em;
  }
  .verified-dot {
    width: 6px; height: 6px; background: var(--green); border-radius: 50%;
    animation: pulse 2s infinite;
  }
  @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }

  /* Hero */
  .pub-hero {
    background: var(--surface); border: 1px solid var(--border); border-radius: 14px;
    padding: 2rem; margin: 1.5rem 0;
    position: relative; overflow: hidden;
  }
  .pub-hero::before {
    content:''; position:absolute; top:0; left:0; right:0; height:3px;
    background: linear-gradient(90deg, var(--amber), var(--sky));
  }
  .pub-hero-title {
    font-family: var(--serif); font-size: clamp(1.6rem, 4vw, 2.4rem);
    font-style: italic; font-weight: 400; letter-spacing: -.03em;
    color: var(--ink); margin-bottom: .75rem; line-height: 1.2;
  }
  .pub-hero-meta {
    display: flex; gap: .5rem; flex-wrap: wrap; align-items: center;
  }
  .meta-chip {
    display: inline-flex; align-items: center; gap: 5px;
    font-family: var(--mono); font-size: .6rem; color: var(--ink3);
    background: var(--surface2); border: 1px solid var(--border);
    padding: .28rem .65rem; border-radius: 99px;
  }
  .meta-chip i { font-size: 11px; color: var(--ink3); }

  /* AI Summary */
  .ai-block {
    background: rgba(232,163,37,.04); border: 1px solid rgba(232,163,37,.15);
    border-left: 3px solid var(--amber); border-radius: 0 10px 10px 0;
    padding: 1.3rem 1.6rem; margin-bottom: 1.5rem;
    display: flex; gap: .9rem;
  }
  .ai-block-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: rgba(232,163,37,.12);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: .1rem;
  }
  .ai-block-icon i { font-size: 14px; color: var(--amber); }
  .ai-block-label {
    font-family: var(--mono); font-size: .56rem; color: var(--amber);
    letter-spacing: .12em; text-transform: uppercase; opacity: .8; margin-bottom: .35rem;
  }
  .ai-block-text { font-size: .9rem; color: var(--ink2); line-height: 1.8; font-style: italic; }

  /* Stats */
  .pub-stats {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 1px; background: var(--border);
    border: 1px solid var(--border); border-radius: 12px;
    overflow: hidden; margin-bottom: 1.5rem;
  }
  .pub-stat { background: var(--surface); padding: 1.1rem 1.3rem; }
  .pub-stat-label {
    font-family: var(--mono); font-size: .56rem; color: var(--ink3);
    letter-spacing: .1em; text-transform: uppercase; margin-bottom: .35rem;
    display: flex; align-items: center; gap: 4px;
  }
  .pub-stat-label i { font-size: 11px; }
  .pub-stat-val {
    font-family: var(--serif); font-size: 1.9rem; font-style: italic;
    color: var(--ink); line-height: 1;
  }
  .pub-stat-val.amber { color: var(--amber); }
  .pub-stat-val.sky   { color: var(--sky); }
  .pub-stat-val.green { color: var(--green); }

  /* Sections */
  .pub-section {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 12px; overflow: hidden; margin-bottom: 1.25rem;
  }
  .pub-section-header {
    display: flex; align-items: center; gap: .7rem;
    padding: .9rem 1.3rem; border-bottom: 1px solid var(--border);
    background: var(--surface2);
  }
  .pub-section-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: var(--surface); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; color: var(--ink3); flex-shrink: 0;
  }
  .pub-section-title { font-size: .86rem; font-weight: 600; color: var(--ink); }
  .pub-section-count {
    margin-left: auto;
    font-family: var(--mono); font-size: .6rem; color: var(--ink3);
    background: var(--surface); border: 1px solid var(--border2);
    padding: .12rem .5rem; border-radius: 5px;
  }

  /* Entries */
  .pub-entry {
    display: grid; grid-template-columns: 1fr auto;
    gap: 1rem; align-items: start;
    padding: .95rem 1.3rem; border-bottom: 1px solid var(--border);
    transition: background .12s;
  }
  .pub-entry:last-child { border-bottom: none; }
  .pub-entry:hover { background: rgba(255,255,255,.015); }
  .pub-entry-type {
    display: inline-flex; align-items: center; gap: 4px;
    font-family: var(--mono); font-size: .56rem; color: var(--ink3);
    background: var(--surface2); border: 1px solid var(--border2);
    padding: .1rem .45rem; border-radius: 5px; margin-bottom: .3rem;
  }
  .pub-entry-type i { font-size: 10px; }
  .pub-entry-title { font-size: .85rem; font-weight: 500; color: var(--ink); margin-bottom: .2rem; }
  .pub-entry-desc { font-size: .78rem; color: var(--ink3); line-height: 1.55; }
  .pub-entry-link {
    font-family: var(--mono); font-size: .6rem; color: var(--sky);
    text-decoration: none; margin-top: .3rem; display: inline-flex; align-items: center; gap: 3px;
  }
  .pub-entry-link:hover { text-decoration: underline; }
  .pub-entry-link i { font-size: 10px; }
  .pub-entry-date {
    font-family: var(--mono); font-size: .6rem; color: var(--ink3);
    white-space: nowrap; text-align: right; opacity: .6;
  }

  /* Hash / verify bar */
  .verify-bar {
    display: flex; align-items: center; justify-content: space-between;
    gap: .5rem;
    background: var(--surface); border: 1px solid var(--border); border-radius: 10px;
    padding: .75rem 1.1rem; margin-bottom: 2rem; flex-wrap: wrap;
  }
  .verify-bar-left { display: flex; align-items: center; gap: .6rem; min-width: 0; }
  .verify-bar-left i { font-size: 14px; color: var(--ink3); flex-shrink: 0; }
  .verify-bar-url { font-family: var(--mono); font-size: .6rem; color: var(--ink3); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .verify-bar-hash { font-family: var(--mono); font-size: .58rem; color: var(--ink3); opacity: .3; flex-shrink: 0; }

  /* Footer */
  .pub-footer {
    text-align: center; padding: 2rem;
    border-top: 1px solid var(--border);
    font-family: var(--mono); font-size: .62rem; color: var(--ink3);
  }
  .pub-footer a { color: var(--amber); text-decoration: none; }
  .pub-footer a:hover { text-decoration: underline; }

  @media(max-width:600px){
    .pub-stats { grid-template-columns: 1fr 1fr; }
    .pub-hero { padding: 1.3rem; }
    .pub-entry { grid-template-columns: 1fr; }
  }
  </style>
</head>
<body>

<div class="top-accent"></div>

<div class="wrap">

  {{-- Nav --}}
  <div class="pub-nav">
    <a href="/" class="pub-brand">
      <i class="ti ti-checkup-list"></i> ProofWork
    </a>
    <div class="verified-badge">
      <span class="verified-dot"></span> VERIFIED REPORT
    </div>
  </div>

  {{-- Hero --}}
  <div class="pub-hero">
    <div class="pub-hero-title">{{ $report->title }}</div>
    <div class="pub-hero-meta">
      <span class="meta-chip"><i class="ti ti-calendar"></i> {{ $report->periodLabel() }}</span>
      @if($report->project)
      <span class="meta-chip"><i class="ti ti-folder"></i> {{ $report->project->name }}</span>
      @endif
      <span class="meta-chip"><i class="ti ti-clock"></i> Generated {{ $report->created_at->format('M d, Y') }}</span>
      <span class="meta-chip"><i class="ti ti-eye"></i> {{ $report->view_count }} {{ Str::plural('view', $report->view_count) }}</span>
    </div>
  </div>

  {{-- AI summary --}}
  @if($report->ai_summary)
  <div class="ai-block">
    <div class="ai-block-icon"><i class="ti ti-sparkles"></i></div>
    <div>
      <div class="ai-block-label">AI Summary</div>
      <div class="ai-block-text">{{ $report->ai_summary }}</div>
    </div>
  </div>
  @endif

  {{-- Stats --}}
  @php
    $bySource     = $report->entries->groupBy('source');
    $githubCount  = ($bySource['github'] ?? collect())->count();
    $linearCount  = ($bySource['linear'] ?? collect())->count();
    $calCount     = ($bySource['google_calendar'] ?? collect())->count();
    $totalEntries = $report->entries->count();
  @endphp
  <div class="pub-stats">
    <div class="pub-stat">
      <div class="pub-stat-label"><i class="ti ti-list-details"></i> Total items</div>
      <div class="pub-stat-val amber">{{ $totalEntries }}</div>
    </div>
    <div class="pub-stat">
      <div class="pub-stat-label"><i class="ti ti-brand-github"></i> Code changes</div>
      <div class="pub-stat-val">{{ $githubCount }}</div>
    </div>
    <div class="pub-stat">
      <div class="pub-stat-label"><i class="ti ti-circle-check"></i> Tasks done</div>
      <div class="pub-stat-val sky">{{ $linearCount }}</div>
    </div>
    <div class="pub-stat">
      <div class="pub-stat-label"><i class="ti ti-calendar-event"></i> Meetings</div>
      <div class="pub-stat-val green">{{ $calCount }}</div>
    </div>
  </div>

  {{-- Entries by source --}}
  @foreach($bySource as $source => $entries)
  @php
    $srcConfig = match($source) {
      'github'          => ['icon' => 'ti-brand-github',           'label' => 'GitHub'],
      'linear'          => ['icon' => 'ti-triangle-square-circle', 'label' => 'Linear'],
      'notion'          => ['icon' => 'ti-notebook',               'label' => 'Notion'],
      'google_calendar' => ['icon' => 'ti-calendar-event',         'label' => 'Google Calendar'],
      'manual'          => ['icon' => 'ti-pencil',                 'label' => 'Manual entries'],
      default           => ['icon' => 'ti-plug',                   'label' => ucfirst($source)],
    };
  @endphp
  <div class="pub-section">
    <div class="pub-section-header">
      <div class="pub-section-icon"><i class="ti {{ $srcConfig['icon'] }}"></i></div>
      <div class="pub-section-title">{{ $srcConfig['label'] }}</div>
      <div class="pub-section-count">{{ $entries->count() }} {{ Str::plural('item', $entries->count()) }}</div>
    </div>
    @foreach($entries as $entry)
    <div class="pub-entry">
      <div>
        <div class="pub-entry-type"><i class="ti ti-tag"></i> {{ $entry->type }}</div>
        <div class="pub-entry-title">{{ $entry->title }}</div>
        @if($entry->description)
        <div class="pub-entry-desc">{{ $entry->description }}</div>
        @endif
        @if($entry->source_url)
        <a href="{{ $entry->source_url }}" target="_blank" class="pub-entry-link">
          <i class="ti ti-external-link"></i> View source
        </a>
        @endif
      </div>
      <div class="pub-entry-date">
        @if($entry->occurred_at){{ $entry->occurred_at->format('M d, Y') }}@endif
      </div>
    </div>
    @endforeach
  </div>
  @endforeach

  {{-- Verify bar --}}
  <div class="verify-bar">
    <div class="verify-bar-left">
      <i class="ti ti-shield-check"></i>
      <span class="verify-bar-url">{{ $report->shareUrl() }}</span>
    </div>
    <span class="verify-bar-hash">{{ $report->verificationHash() }}</span>
  </div>

</div>

<div class="pub-footer">
  Generated with <a href="/">ProofWork</a> · Proof of work, delivered.
</div>

</body>
</html>