<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $siteName }} — Reports</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --red:#CC1F1F; --red-h:#b51a1a; --red-light:#f5e0e0;
  --green:#2D7A2D; --green-light:#e0f0e0;
  --orange:#e07b00; --orange-light:#fef3e2;
  --blue:#1877f2; --blue-light:#e7f0fd;
  --bg:#f0f2f5; --surface:#ffffff; --surface2:#f7f8fa; --card:#ffffff;
  --border:#dddfe2; --text:#1c1e21; --muted:#65676b; --subtle:#a0a3a7; --input-bg:#ffffff;
  --sidebar-w:240px; --header-h:60px;
  --shadow-sm:0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
  --shadow-md:0 2px 8px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
}
[data-theme="dark"] {
  --bg:#18191a; --surface:#242526; --surface2:#1e1f20; --card:#242526;
  --border:#3a3b3c; --text:#e4e6eb; --muted:#b0b3b8; --subtle:#6a6b6c; --input-bg:#3a3b3c;
  --red-light:rgba(204,31,31,.15); --green-light:rgba(45,122,45,.15);
  --orange-light:rgba(224,123,0,.15); --blue-light:rgba(24,119,242,.15);
  --shadow-sm:0 1px 3px rgba(0,0,0,.3); --shadow-md:0 2px 8px rgba(0,0,0,.4);
}

html, body { height:100%; font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); transition:background .3s,color .3s; overflow-x:hidden; }

.overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:90; }
.overlay.on { display:block; }

/* Sidebar */
.sidebar { position:fixed; top:0; left:0; width:var(--sidebar-w); height:100vh; background:var(--surface); border-right:1px solid var(--border); display:flex; flex-direction:column; z-index:100; transition:transform .3s ease, background .3s; overflow-y:auto; }
.sidebar-logo { display:flex; align-items:center; gap:10px; height:var(--header-h); padding:0 16px; border-bottom:1px solid var(--border); flex-shrink:0; text-decoration:none; }
.sidebar-logo-icon { width:36px; height:36px; background:var(--red); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:800; font-size:18px; flex-shrink:0; }
.sidebar-logo-icon { overflow:hidden; }
.sidebar-logo-icon img { width:36px; height:36px; object-fit:cover; border-radius:8px; }
.sidebar-logo-text { line-height:1.2; }
.sidebar-logo-text strong { display:block; font-size:14px; font-weight:700; color:var(--text); }
.sidebar-logo-text span { font-size:11px; color:var(--muted); }
.sidebar-section-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--subtle); padding:18px 16px 6px; }
.nav-item { display:flex; align-items:center; gap:10px; padding:9px 14px; margin:1px 8px; border-radius:7px; cursor:pointer; font-size:14px; font-weight:500; color:var(--muted); transition:background .15s,color .15s; text-decoration:none; user-select:none; }
.nav-item:hover { background:var(--bg); color:var(--text); }
.nav-item.active { background:var(--red-light); color:var(--red); font-weight:600; }
.nav-item svg { width:17px; height:17px; flex-shrink:0; }
.nav-badge { margin-left:auto; background:var(--red); color:#fff; font-size:10px; font-weight:700; border-radius:10px; padding:1px 6px; min-width:18px; text-align:center; }
.sidebar-bottom { margin-top:auto; padding:12px 8px; border-top:1px solid var(--border); }
.user-chip { display:flex; align-items:center; gap:10px; padding:8px 10px; border-radius:7px; cursor:pointer; transition:background .15s; }
.user-chip:hover { background:var(--bg); }
.user-avatar { object-fit:cover; width:32px; height:32px; border-radius:50%; background:var(--red); color:#fff; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0; }
.user-info { flex:1; min-width:0; }
.user-name { font-size:13px; font-weight:600; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.user-role { font-size:11px; color:var(--muted); }

/* Header */
.header { position:fixed; top:0; left:var(--sidebar-w); right:0; height:var(--header-h); background:var(--surface); border-bottom:1px solid var(--border); display:flex; align-items:center; gap:12px; padding:0 20px; z-index:80; transition:left .3s, background .3s; }
.hamburger { display:none; background:none; border:none; cursor:pointer; padding:6px; border-radius:6px; color:var(--muted); }
.hamburger:hover { background:var(--bg); color:var(--text); }
.header-title { font-size:17px; font-weight:700; color:var(--text); flex:1; }
.header-search { display:flex; align-items:center; gap:8px; background:var(--bg); border:1.5px solid var(--border); border-radius:7px; padding:0 12px; height:36px; transition:border-color .2s; }
.header-search:focus-within { border-color:var(--red); }
.header-search svg { width:14px; height:14px; color:var(--subtle); flex-shrink:0; }
.header-search input { border:none; background:none; outline:none; font-family:inherit; font-size:14px; color:var(--text); width:200px; }
.header-search input::placeholder { color:var(--subtle); }
.header-actions { display:flex; align-items:center; gap:8px; }
.icon-btn { width:36px; height:36px; background:var(--bg); border:1.5px solid var(--border); border-radius:7px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:var(--muted); transition:border-color .2s,color .2s; position:relative; }
.icon-btn:hover { border-color:var(--red); color:var(--text); }
.icon-btn svg { width:16px; height:16px; }

/* Main */
.main { margin-left:var(--sidebar-w); margin-top:var(--header-h); min-height:calc(100vh - var(--header-h)); padding:24px; transition:margin-left .3s; }

.page-head { display:flex; align-items:flex-end; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
.page-head h1 { font-size:20px; font-weight:800; }
.page-head p { font-size:13px; color:var(--muted); margin-top:3px; }

.head-tools { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.head-tools .btn-export { height:40px; padding:0 16px; border-radius:10px; }
.period-switch { display:inline-flex; background:var(--surface); border:1px solid var(--border); border-radius:10px; padding:3px; box-shadow:var(--shadow-sm); }
.period-switch button { border:none; background:transparent; color:var(--muted); font-family:inherit; font-size:13px; font-weight:600; padding:8px 16px; border-radius:8px; cursor:pointer; transition:.15s; }
.period-switch button:hover { color:var(--text); }
.period-switch button.active { background:var(--red); color:#fff; }

/* Stats */
.stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
.stat-card { background:var(--surface); border:1px solid var(--border); border-radius:10px; padding:18px 20px; box-shadow:var(--shadow-sm); }
.stat-icon { width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; margin-bottom:14px; }
.stat-icon svg { width:20px; height:20px; }
.stat-icon.red { background:var(--red-light); color:var(--red); }
.stat-icon.green { background:var(--green-light); color:var(--green); }
.stat-icon.orange { background:var(--orange-light); color:var(--orange); }
.stat-icon.blue { background:var(--blue-light); color:var(--blue); }
.stat-value { font-size:24px; font-weight:800; line-height:1; margin-bottom:4px; }
.stat-label { font-size:13px; color:var(--muted); }

/* Cards / tables */
.card { background:var(--surface); border:1px solid var(--border); border-radius:12px; box-shadow:var(--shadow-sm); margin-bottom:24px; }
.card-head { border-radius:12px 12px 0 0; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; padding:16px 20px; border-bottom:1px solid var(--border); }
.card-head h3 { font-size:15px; font-weight:700; }
.card-head small { display:block; font-size:12px; font-weight:500; color:var(--muted); margin-top:2px; }
.card-tools { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
.card-tools select { border:1px solid var(--border); background:var(--input-bg); color:var(--text); font-size:13px; font-family:inherit; padding:8px 10px; border-radius:8px; cursor:pointer; }
.btn-export { display:inline-flex; align-items:center; gap:7px; background:var(--red); color:#fff; border:none; padding:8px 14px; border-radius:8px; font-size:13px; font-weight:700; font-family:inherit; cursor:pointer; transition:background .15s; white-space:nowrap; }
.btn-export:hover { background:var(--red-h); }
.btn-export svg { width:14px; height:14px; }
.btn-export .caret { width:12px; height:12px; transition:transform .15s; }
.export-menu { position:relative; }
.export-menu.open .caret { transform:rotate(180deg); }
.export-list { display:none; position:absolute; right:0; top:calc(100% + 6px); min-width:190px; background:var(--surface); border:1px solid var(--border); border-radius:10px; box-shadow:var(--shadow-md); padding:5px; z-index:60; }
.export-menu.open .export-list { display:block; }
.export-list button { display:flex; align-items:center; gap:10px; width:100%; background:none; border:none; color:var(--text); font-family:inherit; font-size:13px; font-weight:600; padding:9px 10px; border-radius:7px; cursor:pointer; text-align:left; }
.export-list button:hover { background:var(--bg); }
.export-list button svg { width:16px; height:16px; flex-shrink:0; }
.export-list button .ico-csv { color:var(--green); }
.export-list button .ico-pdf { color:var(--red); }
.export-list button small { display:block; font-size:11px; font-weight:500; color:var(--muted); }

.table-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:13px; }
thead th { padding:11px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:var(--subtle); background:var(--surface2); border-bottom:1px solid var(--border); white-space:nowrap; }
thead th.num, tbody td.num { text-align:right; }
tbody td { padding:12px 16px; border-bottom:1px solid var(--border); vertical-align:middle; }
tbody tr:last-child td { border-bottom:none; }
tbody tr:hover { background:rgba(204,31,31,.03); }
td.strong { font-weight:700; }
.pill { display:inline-flex; align-items:center; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; background:var(--blue-light); color:var(--blue); }
.muted { color:var(--muted); }
.empty { padding:40px 20px; text-align:center; color:var(--subtle); font-size:13px; font-weight:600; }

.pager { border-radius:0 0 12px 12px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; padding:12px 20px; border-top:1px solid var(--border); background:var(--surface2); }
.pager-info { font-size:12px; color:var(--muted); }
.pager-btns { display:flex; gap:4px; flex-wrap:wrap; }
.pager-btns button { min-width:32px; height:32px; padding:0 10px; border:1px solid var(--border); background:var(--surface); color:var(--muted); border-radius:7px; font-family:inherit; font-size:12px; font-weight:600; cursor:pointer; transition:.15s; }
.pager-btns button:hover:not(:disabled) { border-color:var(--red); color:var(--red); }
.pager-btns button.active { background:var(--red); border-color:var(--red); color:#fff; }
.pager-btns button:disabled { opacity:.45; cursor:not-allowed; }

.footer { text-align:center; font-size:12px; color:var(--subtle); padding:12px 0 4px; }

.toast { position:fixed; bottom:24px; right:24px; background:var(--green); color:#fff; padding:12px 18px; border-radius:8px; font-size:13px; font-weight:600; box-shadow:0 4px 16px rgba(0,0,0,.2); transform:translateY(20px); opacity:0; transition:opacity .3s, transform .3s; pointer-events:none; z-index:200; }
.toast.on { opacity:1; transform:translateY(0); }

@media (max-width:1100px) { .stats-grid { grid-template-columns:repeat(2,1fr); } }
@media (max-width:768px) {
  .sidebar { transform:translateX(calc(-1 * var(--sidebar-w))); }
  .sidebar.open { transform:translateX(0); }
  .header { left:0; }
  .main { margin-left:0; padding:16px; }
  .hamburger { display:flex; }
  .header-search { display:none; }
  .stats-grid { gap:12px; }
  .stat-value { font-size:20px; }
  .head-tools { width:100%; }
  .period-switch { flex:1; }
  .period-switch button { flex:1; padding:8px 6px; }
  .pager { justify-content:center; }
}
</style>
</head>
<body>

<div class="toast" id="toast"></div>
<div class="overlay" id="overlay"></div>

<aside class="sidebar" id="sidebar">
  <a class="sidebar-logo" href="{{ route('admin.dashboard') }}">
    <div class="sidebar-logo-icon">
      <img src="{{ $logo2 ? asset($logo2) : '' }}" alt="{{ $siteName }}">
    </div>
    <div class="sidebar-logo-text">
      <strong>{{ $siteName }}</strong>
      <span>Admin Portal</span>
    </div>
  </a>

  <div class="sidebar-section-label">Main</div>
  <a class="nav-item" href="{{ route('admin.dashboard') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>
  <a class="nav-item" href="{{ route('admin.inventory') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
    Inventory<span class="nav-badge">3</span>
  </a>
  <a class="nav-item" href="{{ route('admin.orders') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    Orders
  </a>
  <a class="nav-item" href="{{ route('admin.staffs') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg>
    Staffs
  </a>

  <div class="sidebar-section-label">Manage</div>
  <a class="nav-item" href="{{ route('admin.stocks') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    Stocks
  </a>
  <a class="nav-item active" href="{{ route('admin.reports') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
    Reports
  </a>
  <a class="nav-item" href="{{ route('admin.expiry') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    Expiry Tracking<span class="nav-badge">2</span>
  </a>
  <a class="nav-item" href="{{ route('admin.pharmacy') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    Pharmacy
  </a>
  <a class="nav-item" href="{{ route('admin.financials') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    Financial Records
  </a>
  <a class="nav-item" href="{{ route('admin.settings') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    Settings
  </a>

  <a href="{{ route('admin.profile') }}" style="text-decoration:none;">
    <div class="sidebar-bottom">
      <div class="user-chip">
        @if(auth('admin')->user()->profile_picture)
          <img src="{{ asset(auth('admin')->user()->profile_picture) }}" class="user-avatar" alt="">
        @else
          <div class="user-avatar">{{ strtoupper(substr(auth('admin')->user()->first_name ?? 'A', 0, 1)) }}{{ strtoupper(substr(auth('admin')->user()->last_name ?? 'D', 0, 1)) }}</div>
        @endif
        <div class="user-info">
          <div class="user-name">{{ auth('admin')->user()->first_name ?? 'Admin' }} {{ auth('admin')->user()->last_name ?? 'User' }}</div>
          <div class="user-role">System Administrator</div>
        </div>
      </div>
    </div>
  </a>
</aside>

<header class="header">
  <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="header-title">Reports</div>
  <div class="header-search">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" id="headerSearch" placeholder="Search products…">
  </div>
  <div class="header-actions">
    <button class="icon-btn" id="themeBtn" title="Toggle dark mode"><span id="themeIco"></span></button>
  </div>
</header>

<main class="main">

  <div class="page-head">
    <div>
      <h1>Reports</h1>
      <p id="periodLabel">Product, brand and category performance.</p>
    </div>
<div class="head-tools">
        <div class="period-switch" id="periodSwitch">
      <button data-period="week">This Week</button>
      <button data-period="month">This Month</button>
      <button data-period="all" class="active">All Time</button>
    </div>
    <div class="export-menu">
        <button class="btn-export" type="button" aria-haspopup="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Export To
          <svg class="caret" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="export-list" role="menu">
          <button type="button" role="menuitem" data-export="all" data-format="csv">
            <svg class="ico-csv" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/></svg>
            <span>Export CSV<small>All tables in one file</small></span>
          </button>
          <button type="button" role="menuitem" data-export="all" data-format="pdf">
            <svg class="ico-pdf" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h1.5a1.5 1.5 0 0 0 0-3H9v5"/></svg>
            <span>Export PDF<small>Written report, all tables</small></span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></div>
      <div class="stat-value" id="statProducts">0</div>
      <div class="stat-label">Total Products</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div>
      <div class="stat-value" id="statPurchases">0</div>
      <div class="stat-label">Units Purchased</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/></svg></div>
      <div class="stat-value" id="statUnitsSold">0</div>
      <div class="stat-label">Units Sold</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
      <div class="stat-value" id="statSales">₱0</div>
      <div class="stat-label">Total Sales</div>
    </div>
  </div>

  <!-- PRODUCTS -->
  <div class="card">
    <div class="card-head">
      <div><h3>Products</h3><small id="productsSub"></small></div>
      <div class="card-tools">
        <select id="filterCategory"><option value="">All Categories</option></select>
        <select id="filterBrand"><option value="">All Brands</option></select>
      </div>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Product</th><th>Category</th><th>Brand</th>
            <th class="num">Total Stocks</th><th class="num">Total Stock Quantity</th>
            <th class="num">Total Purchases</th><th class="num">Total Sales</th>
          </tr>
        </thead>
        <tbody id="productsBody"></tbody>
      </table>
    </div>
    <div class="pager" id="productsPager"></div>
  </div>

  <!-- BRANDS -->
  <div class="card">
    <div class="card-head">
      <div><h3>Brands</h3><small id="brandsSub"></small></div>
      <div class="card-tools">
      </div>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Brand</th><th class="num">Total Products</th><th class="num">Total Purchases</th>
            <th>Most Sold Product</th><th class="num">Total Sales</th>
          </tr>
        </thead>
        <tbody id="brandsBody"></tbody>
      </table>
    </div>
    <div class="pager" id="brandsPager"></div>
  </div>

  <!-- CATEGORIES -->
  <div class="card">
    <div class="card-head">
      <div><h3>Categories</h3><small id="categoriesSub"></small></div>
      <div class="card-tools">
      </div>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Category</th><th class="num">Total Products</th><th class="num">Total Brands</th>
            <th class="num">Total Purchases</th><th class="num">Total Sales</th><th>Most Sold Product</th>
          </tr>
        </thead>
        <tbody id="categoriesBody"></tbody>
      </table>
    </div>
    <div class="pager" id="categoriesPager"></div>
  </div>

  <div class="footer">Pharmacy Inventory System &nbsp;·&nbsp; Zone 2, Sogod, Southern Leyte &nbsp;·&nbsp; Admin Access Only</div>
</main>

<script>
/* ── Theme ─────────────────────────────────────────── */
const html = document.documentElement;
const themeIco = document.getElementById('themeIco');
const MOON = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>`;
const SUN  = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;
function safeStore(k, v) { try { if (v === undefined) return localStorage.getItem(k); localStorage.setItem(k, v); } catch (e) { return null; } }
function setTheme(dark) {
  html.setAttribute('data-theme', dark ? 'dark' : 'light');
  themeIco.innerHTML = dark ? SUN : MOON;
  safeStore('theme', dark ? 'dark' : 'light');
}
const savedTheme = safeStore('theme');
setTheme(savedTheme ? savedTheme === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches);
document.getElementById('themeBtn').addEventListener('click', () => setTheme(html.getAttribute('data-theme') !== 'dark'));

/* ── Sidebar (mobile) ──────────────────────────────── */
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
document.getElementById('hamburger').addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('on'); });
overlay.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('on'); });

/* ── Data (loaded from AdminReportsController@data) ── */
const DATA_URL = @json(route('admin.reports.data'));
let period = 'all';
let productRows = [], brandRows = [], categoryRows = [], totals = {products:0,purchases:0,unitsSold:0,sales:0}, periodLabel = 'All time';

const peso = n => '₱' + Number(n).toLocaleString('en-US', { minimumFractionDigits:2, maximumFractionDigits:2 });
const int  = n => Number(n).toLocaleString('en-US');
const esc  = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

async function loadData() {
  try {
    const res = await fetch(`${DATA_URL}?period=${encodeURIComponent(period)}`, {
      headers: { 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' },
      credentials: 'same-origin'
    });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const d = await res.json();
    productRows = d.products; brandRows = d.brands; categoryRows = d.categories;
    totals = d.totals; periodLabel = d.label;
  } catch (err) {
    showToast('Could not load report data.');
    return;
  }
  populateFilters();
  renderAll();
}

/* ── Generic paginated table ───────────────────────── */
const PAGE_SIZE = 10;
function makeTable({ bodyId, pagerId, getRows, renderRow, colSpan, emptyText }) {
  const body = document.getElementById(bodyId);
  const pager = document.getElementById(pagerId);
  const t = { page:1 };
  t.render = function() {
    const rows = getRows();
    const pages = Math.max(1, Math.ceil(rows.length / PAGE_SIZE));
    if (t.page > pages) t.page = pages;
    const from = (t.page - 1) * PAGE_SIZE;
    const slice = rows.slice(from, from + PAGE_SIZE);

    body.innerHTML = slice.length
      ? slice.map(renderRow).join('')
      : `<tr><td colspan="${colSpan}"><div class="empty">${emptyText}</div></td></tr>`;

    const info = rows.length
      ? `Showing ${from + 1}–${from + slice.length} of ${rows.length}`
      : 'No results';
    let btns = `<button ${t.page === 1 ? 'disabled' : ''} data-p="${t.page - 1}">‹ Prev</button>`;
    for (let i = 1; i <= pages; i++) btns += `<button class="${i === t.page ? 'active' : ''}" data-p="${i}">${i}</button>`;
    btns += `<button ${t.page === pages ? 'disabled' : ''} data-p="${t.page + 1}">Next ›</button>`;
    pager.innerHTML = `<div class="pager-info">${info}</div>` + (pages > 1 ? `<div class="pager-btns">${btns}</div>` : '');
  };
  pager.addEventListener('click', e => {
    const b = e.target.closest('button[data-p]');
    if (!b || b.disabled) return;
    t.page = Number(b.dataset.p);
    t.render();
  });
  return t;
}

/* ── Filters (products table) ──────────────────────── */
const searchInput = document.getElementById('headerSearch');
const filterCategory = document.getElementById('filterCategory');
const filterBrand = document.getElementById('filterBrand');

function populateFilters() {
  const fill = (el, values, label) => {
    const keep = el.value;
    el.innerHTML = `<option value="">${label}</option>` + [...new Set(values)].sort().map(v => `<option>${esc(v)}</option>`).join('');
    el.value = [...el.options].some(o => o.value === keep) ? keep : '';
  };
  fill(filterCategory, productRows.map(p => p.category), 'All Categories');
  fill(filterBrand, productRows.map(p => p.brand), 'All Brands');
}

function filteredProducts() {
  const q = searchInput.value.trim().toLowerCase();
  return productRows.filter(p =>
    (!filterCategory.value || p.category === filterCategory.value) &&
    (!filterBrand.value || p.brand === filterBrand.value) &&
    (!q || `${p.name} ${p.category} ${p.brand}`.toLowerCase().includes(q))
  );
}

const productsTable = makeTable({
  bodyId:'productsBody', pagerId:'productsPager', colSpan:7, emptyText:'No products match your search.',
  getRows: filteredProducts,
  renderRow: p => `<tr>
    <td class="strong">${esc(p.name)}</td>
    <td><span class="pill">${esc(p.category)}</span></td>
    <td>${esc(p.brand)}</td>
    <td class="num">${int(p.stocks)}</td>
    <td class="num">${int(p.qty)}</td>
    <td class="num">${int(p.purchases)}</td>
    <td class="num strong">${peso(p.sales)}</td>
  </tr>`
});

const brandsTable = makeTable({
  bodyId:'brandsBody', pagerId:'brandsPager', colSpan:5, emptyText:'No brands.',
  getRows: () => brandRows,
  renderRow: b => `<tr>
    <td class="strong">${esc(b.brand)}</td>
    <td class="num">${int(b.totalProducts)}</td>
    <td class="num">${int(b.purchases)}</td>
    <td>${esc(b.mostSold)}</td>
    <td class="num strong">${peso(b.sales)}</td>
  </tr>`
});

const categoriesTable = makeTable({
  bodyId:'categoriesBody', pagerId:'categoriesPager', colSpan:6, emptyText:'No categories.',
  getRows: () => categoryRows,
  renderRow: c => `<tr>
    <td class="strong">${esc(c.category)}</td>
    <td class="num">${int(c.totalProducts)}</td>
    <td class="num">${int(c.totalBrands)}</td>
    <td class="num">${int(c.purchases)}</td>
    <td class="num strong">${peso(c.sales)}</td>
    <td>${esc(c.mostSold)}</td>
  </tr>`
});

/* ── Render everything ─────────────────────────────── */
function renderAll() {
  document.getElementById('periodLabel').textContent = `Product, brand and category performance — ${periodLabel}.`;
  ['productsSub','brandsSub','categoriesSub'].forEach(id => document.getElementById(id).textContent = periodLabel);

  document.getElementById('statProducts').textContent  = int(totals.products);
  document.getElementById('statPurchases').textContent = int(totals.purchases);
  document.getElementById('statUnitsSold').textContent = int(totals.unitsSold);
  document.getElementById('statSales').textContent     = peso(totals.sales);

  productsTable.render(); brandsTable.render(); categoriesTable.render();
}

document.getElementById('periodSwitch').addEventListener('click', e => {
  const b = e.target.closest('button[data-period]');
  if (!b) return;
  period = b.dataset.period;
  document.querySelectorAll('#periodSwitch button').forEach(x => x.classList.toggle('active', x === b));
  productsTable.page = brandsTable.page = categoriesTable.page = 1;
  loadData();
});

[searchInput, filterCategory, filterBrand].forEach(el => el.addEventListener(el.tagName === 'INPUT' ? 'input' : 'change', () => { productsTable.page = 1; productsTable.render(); }));

/* ── CSV export (exports all rows, not just the current page) ── */
function csvCell(v) {
  const s = String(v ?? '');
  return /[",\n\r]/.test(s) ? '"' + s.replace(/"/g, '""') + '"' : s;
}
function downloadCsv(filename, header, rows) {
  const lines = [header, ...rows].map(r => r.map(csvCell).join(','));
  const blob = new Blob(['\uFEFF' + lines.join('\r\n')], { type:'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url; a.download = filename;
  document.body.appendChild(a); a.click(); a.remove();
  setTimeout(() => URL.revokeObjectURL(url), 500);
}
function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg; t.classList.add('on');
  clearTimeout(showToast._t);
  showToast._t = setTimeout(() => t.classList.remove('on'), 2400);
}

const EXPORTS = {
  products: () => ({
    header: ['Product','Category','Brand','Total Stocks','Total Stock Quantity','Total Purchases','Total Sales'],
    rows: filteredProducts().map(p => [p.name, p.category, p.brand, p.stocks, p.qty, p.purchases, p.sales.toFixed(2)])
  }),
  brands: () => ({
    header: ['Brand','Total Products','Total Purchases','Most Sold Product','Total Sales'],
    rows: brandRows.map(b => [b.brand, b.totalProducts, b.purchases, b.mostSold, b.sales.toFixed(2)])
  }),
  categories: () => ({
    header: ['Category','Total Products','Total Brands','Total Purchases','Total Sales','Most Sold Product'],
    rows: categoryRows.map(c => [c.category, c.totalProducts, c.totalBrands, c.purchases, c.sales.toFixed(2), c.mostSold])
  })
};

/* ── "Export To" dropdown ──────────────────────────── */
function closeExportMenus(except) {
  document.querySelectorAll('.export-menu.open').forEach(m => { if (m !== except) m.classList.remove('open'); });
}
document.addEventListener('click', e => {
  const toggle = e.target.closest('.export-menu > .btn-export');
  if (toggle) {
    const menu = toggle.parentElement;
    closeExportMenus(menu);
    menu.classList.toggle('open');
    return;
  }
  const item = e.target.closest('.export-list button[data-export]');
  if (item) {
    closeExportMenus();
    runExport(item.dataset.export, item.dataset.format);
    return;
  }
  closeExportMenus();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeExportMenus(); });

function runExport(key, format) {
  return format === 'pdf' ? exportPdf() : exportCsv();
}

/* ── One CSV with all three tables (all rows, not just the current page) ── */
function exportCsv() {
  const stamp = new Date().toISOString().slice(0, 10);
  const sections = [['Products','products'], ['Brands','brands'], ['Categories','categories']];
  const lines = [
    [SITE_NAME + ' - Reports'],
    ['Period', periodLabel],
    ['Generated', new Date().toLocaleString('en-US')]
  ];
  let total = 0;
  sections.forEach(([label, key]) => {
    const { header, rows } = EXPORTS[key]();
    total += rows.length;
    lines.push([], [label.toUpperCase()], header, ...rows);
  });
  const csv = lines.map(r => r.map(csvCell).join(',')).join('\r\n');
  const blob = new Blob(['\uFEFF' + csv], { type:'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url; a.download = `pharmacy-report-${period}-${stamp}.csv`;
  document.body.appendChild(a); a.click(); a.remove();
  setTimeout(() => URL.revokeObjectURL(url), 500);
  showToast(`Exported ${total} rows (3 tables) to CSV.`);
}

/* PDF-BEGIN ── PDF export (written summary + data table) ─────────────── */
const SITE_NAME   = @json($siteName);
const LOGO_URL    = @json($logo2 ? asset($logo2) : null);
const PREPARED_BY = @json(trim((auth('admin')->user()->first_name ?? 'Admin') . ' ' . (auth('admin')->user()->last_name ?? '')));

const PDF_RED  = [204, 31, 31];
const PDF_INK  = [28, 30, 33];
const PDF_GREY = [101, 103, 107];
const PDF_LINE = [221, 223, 226];

const pdfMoney = n => Number(n).toLocaleString('en-US', { minimumFractionDigits:2, maximumFractionDigits:2 });
const pdfPeso  = n => 'PHP ' + pdfMoney(n);
const count    = (n, one, many) => `${int(n)} ${n === 1 ? one : (many || one + 's')}`;
const sumBy    = (rows, k) => rows.reduce((a, r) => a + Number(r[k] || 0), 0);
const maxBy    = (rows, k) => rows.reduce((best, r) => (!best || Number(r[k]) > Number(best[k]) ? r : best), null);

/* Each builder returns the sentences + table for one report. */
const PDF_REPORTS = {
  products() {
    const rows = filteredProducts();
    const sold = sumBy(rows, 'unitsSold'), bought = sumBy(rows, 'purchases');
    const sales = sumBy(rows, 'sales'), onHand = sumBy(rows, 'qty'), batches = sumBy(rows, 'stocks');
    const outOfStock = rows.filter(r => Number(r.qty) === 0).length;
    const topRev = maxBy(rows, 'sales'), topUnits = maxBy(rows, 'unitsSold');

    const filters = [];
    if (filterCategory.value) filters.push(`category "${filterCategory.value}"`);
    if (filterBrand.value)    filters.push(`brand "${filterBrand.value}"`);
    if (searchInput.value.trim()) filters.push(`search "${searchInput.value.trim()}"`);

    const p = [];
    p.push(`This Product Performance Report summarizes ${count(rows.length, 'product')} from the ${SITE_NAME} inventory for the period: ${periodLabel}.` +
      (filters.length ? ` The figures are limited to the current filters (${filters.join(', ')}).` : ' All products in the catalogue are included.'));
    if (!rows.length) {
      p.push('No products matched the selected filters, so there is no data to report.');
    } else {
      p.push(`Within this period, customers ordered ${count(bought, 'unit')} (excluding cancelled orders), and ${count(sold, 'unit')} were completed and picked up, generating total sales of ${pdfPeso(sales)}.`);
      p.push(topRev && topRev.sales > 0
        ? `The highest-earning product was ${topRev.name} with ${pdfPeso(topRev.sales)} in sales` +
          (topUnits && topUnits.id !== topRev.id && topUnits.unitsSold > 0 ? `, while ${topUnits.name} sold the most units (${int(topUnits.unitsSold)}).` : '.')
        : 'No completed sales were recorded for these products in this period.');
      p.push(`Current stock on hand stands at ${count(onHand, 'unit')} across ${count(batches, 'stock batch', 'stock batches')}. ` +
        (outOfStock ? `${count(outOfStock, 'product')} ${outOfStock === 1 ? 'has' : 'have'} no stock on hand and may need to be replenished. ` : 'Every listed product currently has stock on hand. ') +
        'Stock figures are a snapshot as of the date of this report and are not affected by the selected period.');
    }
    return {
      title: 'Product Performance Report',
      paragraphs: p,
      kpis: [['Products', int(rows.length)], ['Units Purchased', int(bought)], ['Units Sold', int(sold)], ['Total Sales', pdfPeso(sales)]],
      head: ['Product', 'Category', 'Brand', 'Stocks', 'Stock Qty', 'Purchases', 'Total Sales (PHP)'],
      body: rows.map(r => [r.name, r.category, r.brand, int(r.stocks), int(r.qty), int(r.purchases), pdfMoney(r.sales)]),
      foot: ['Total', '', '', int(batches), int(onHand), int(bought), pdfMoney(sales)],
      numCols: [3, 4, 5, 6],
      widths: { 0: 44 }
    };
  },

  brands() {
    const rows = brandRows;
    const sales = sumBy(rows, 'sales'), bought = sumBy(rows, 'purchases'), prods = sumBy(rows, 'totalProducts');
    const topSales = maxBy(rows, 'sales'), topBuy = maxBy(rows, 'purchases');
    const idle = rows.filter(r => Number(r.sales) === 0).length;

    const p = [];
    p.push(`This Brand Performance Report compares ${count(rows.length, 'brand')} carried by ${SITE_NAME} for the period: ${periodLabel}. Together they cover ${count(prods, 'product')}, with ${count(bought, 'unit')} ordered by customers and total sales of ${pdfPeso(sales)}.`);
    if (!rows.length) {
      p.push('No brands are on record yet.');
    } else {
      if (topSales && topSales.sales > 0) {
        const share = sales > 0 ? (topSales.sales / sales * 100).toFixed(1) : '0.0';
        p.push(`${topSales.brand} led all brands with ${pdfPeso(topSales.sales)} in sales, accounting for ${share}% of the total` +
          (topSales.mostSold && topSales.mostSold !== '—' ? `; its most sold product was ${topSales.mostSold}.` : '.'));
      } else {
        p.push('No completed sales were recorded for any brand in this period.');
      }
      if (topBuy && topBuy.purchases > 0 && (!topSales || topBuy.brand !== topSales.brand)) {
        p.push(`${topBuy.brand} received the most customer orders with ${count(topBuy.purchases, 'unit')} ordered.`);
      }
      if (idle) p.push(`${count(idle, 'brand')} recorded no sales in this period, which may be worth reviewing for promotion or stocking decisions.`);
    }
    return {
      title: 'Brand Performance Report',
      paragraphs: p,
      kpis: [['Brands', int(rows.length)], ['Products', int(prods)], ['Units Purchased', int(bought)], ['Total Sales', pdfPeso(sales)]],
      head: ['Brand', 'Products', 'Purchases', 'Most Sold Product', 'Total Sales (PHP)'],
      body: rows.map(r => [r.brand, int(r.totalProducts), int(r.purchases), r.mostSold, pdfMoney(r.sales)]),
      foot: ['Total', int(prods), int(bought), '', pdfMoney(sales)],
      numCols: [1, 2, 4],
      widths: {}
    };
  },

  categories() {
    const rows = categoryRows;
    const sales = sumBy(rows, 'sales'), bought = sumBy(rows, 'purchases'), prods = sumBy(rows, 'totalProducts');
    const topSales = maxBy(rows, 'sales'), topBuy = maxBy(rows, 'purchases');
    const idle = rows.filter(r => Number(r.sales) === 0).length;

    const p = [];
    p.push(`This Category Performance Report reviews ${count(rows.length, 'category', 'categories')} in the ${SITE_NAME} inventory for the period: ${periodLabel}. Together they contain ${count(prods, 'product')}, with ${count(bought, 'unit')} ordered by customers and total sales of ${pdfPeso(sales)}.`);
    if (!rows.length) {
      p.push('No categories are on record yet.');
    } else {
      if (topSales && topSales.sales > 0) {
        const share = sales > 0 ? (topSales.sales / sales * 100).toFixed(1) : '0.0';
        p.push(`${topSales.category} was the strongest category with ${pdfPeso(topSales.sales)} in sales (${share}% of the total) across ${count(topSales.totalProducts, 'product')} and ${count(topSales.totalBrands, 'brand')}` +
          (topSales.mostSold && topSales.mostSold !== '—' ? `; its most sold product was ${topSales.mostSold}.` : '.'));
      } else {
        p.push('No completed sales were recorded for any category in this period.');
      }
      if (topBuy && topBuy.purchases > 0 && (!topSales || topBuy.category !== topSales.category)) {
        p.push(`${topBuy.category} attracted the most customer orders with ${count(topBuy.purchases, 'unit')} ordered.`);
      }
      if (idle) p.push(`${count(idle, 'category', 'categories')} recorded no sales in this period.`);
    }
    return {
      title: 'Category Performance Report',
      paragraphs: p,
      kpis: [['Categories', int(rows.length)], ['Products', int(prods)], ['Units Purchased', int(bought)], ['Total Sales', pdfPeso(sales)]],
      head: ['Category', 'Products', 'Brands', 'Purchases', 'Total Sales (PHP)', 'Most Sold Product'],
      body: rows.map(r => [r.category, int(r.totalProducts), int(r.totalBrands), int(r.purchases), pdfMoney(r.sales), r.mostSold]),
      foot: ['Total', int(prods), '', int(bought), pdfMoney(sales), ''],
      numCols: [1, 2, 3, 4],
      widths: {}
    };
  }
};

function loadLogoDataUrl() {
  return new Promise(resolve => {
    if (!LOGO_URL) return resolve(null);
    const img = new Image();
    img.crossOrigin = 'anonymous';
    img.onload = () => {
      try {
        const c = document.createElement('canvas');
        c.width = img.naturalWidth; c.height = img.naturalHeight;
        c.getContext('2d').drawImage(img, 0, 0);
        resolve(c.toDataURL('image/png'));
      } catch (e) { resolve(null); }
    };
    img.onerror = () => resolve(null);
    img.src = LOGO_URL;
  });
}

async function exportPdf() {
  if (!window.jspdf || !window.jspdf.jsPDF) { showToast('PDF library failed to load. Check your connection.'); return; }
  showToast('Preparing PDF…');
  const logo = await loadLogoDataUrl();
  const sections = [PDF_REPORTS.products(), PDF_REPORTS.brands(), PDF_REPORTS.categories()];

  const { jsPDF } = window.jspdf;
  const doc = new jsPDF({ unit:'mm', format:'a4' });
  const W = doc.internal.pageSize.getWidth(), H = doc.internal.pageSize.getHeight(), M = 14;
  const now = new Date();
  const generated = now.toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' }) + ' at ' +
                    now.toLocaleTimeString('en-US', { hour:'numeric', minute:'2-digit' });
  const REPORT_TITLE = 'Pharmacy Performance Report';

  const headed = new Set([1]);
  const runningHeader = () => {
    const n = doc.internal.getCurrentPageInfo().pageNumber;
    if (headed.has(n)) return;
    headed.add(n);
    doc.setFont('helvetica', 'normal'); doc.setFontSize(8); doc.setTextColor(...PDF_GREY);
    doc.text(`${SITE_NAME}  |  ${REPORT_TITLE}`, M, 12);
    doc.setDrawColor(...PDF_LINE); doc.setLineWidth(0.3); doc.line(M, 15, W - M, 15);
  };
  let y = 0;
  const ensure = h => { if (y + h > H - 20) { doc.addPage(); runningHeader(); y = 24; } };

  /* Header band */
  doc.setFillColor(...PDF_RED); doc.rect(0, 0, W, 26, 'F');
  let textX = M;
  if (logo) {
    doc.setFillColor(255, 255, 255); doc.roundedRect(M, 5, 16, 16, 2, 2, 'F');
    try { doc.addImage(logo, 'PNG', M + 1, 6, 14, 14); } catch (e) {}
    textX = M + 21;
  }
  doc.setTextColor(255, 255, 255);
  doc.setFont('helvetica', 'bold'); doc.setFontSize(15); doc.text(String(SITE_NAME), textX, 13.5);
  doc.setFont('helvetica', 'normal'); doc.setFontSize(9); doc.text('Pharmacy Inventory System  |  Admin Portal', textX, 19.5);
  doc.setFont('helvetica', 'bold'); doc.setFontSize(9); doc.text('OFFICIAL REPORT', W - M, 13.5, { align:'right' });
  doc.setFont('helvetica', 'normal'); doc.text(now.toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' }), W - M, 19.5, { align:'right' });

  /* Title block */
  y = 38;
  doc.setTextColor(...PDF_INK); doc.setFont('helvetica', 'bold'); doc.setFontSize(18); doc.text(REPORT_TITLE, M, y);
  y += 6.5;
  doc.setFont('helvetica', 'normal'); doc.setFontSize(9.5); doc.setTextColor(...PDF_GREY);
  doc.text(`Reporting period: ${periodLabel}`, M, y); y += 5;
  doc.text(`Generated on ${generated}  |  Prepared by ${PREPARED_BY}`, M, y); y += 4;
  doc.setDrawColor(...PDF_RED); doc.setLineWidth(0.8); doc.line(M, y, M + 22, y);
  doc.setDrawColor(...PDF_LINE); doc.setLineWidth(0.3); doc.line(M + 22, y, W - M, y);

  /* Overview */
  y += 8;
  doc.setTextColor(...PDF_RED); doc.setFont('helvetica', 'bold'); doc.setFontSize(11); doc.text('Overview', M, y);
  y += 6;
  doc.setTextColor(...PDF_INK); doc.setFont('helvetica', 'normal'); doc.setFontSize(10);
  const overview = [
    `This report consolidates the product, brand and category performance of ${SITE_NAME} for the period: ${periodLabel}. ` +
    `Across the inventory, ${count(totals.products, 'product')} were tracked, customers ordered ${count(totals.purchases, 'unit')}, ` +
    `and ${count(totals.unitsSold, 'unit')} were completed and picked up, generating total sales of ${pdfPeso(totals.sales)}.`,
    'The report is organized in three sections: (1) Products, (2) Brands and (3) Categories. Each section opens with a written summary of its key findings, followed by the detailed figures.'
  ];
  overview.forEach(par => {
    const lines = doc.splitTextToSize(par, W - 2 * M);
    doc.text(lines, M, y, { lineHeightFactor: 1.45 });
    y += lines.length * 5.1 + 2.5;
  });

  /* KPI tiles */
  y += 2;
  const kpis = [['Products', int(totals.products)], ['Units Purchased', int(totals.purchases)], ['Units Sold', int(totals.unitsSold)], ['Total Sales', pdfPeso(totals.sales)]];
  const gap = 4, bw = (W - 2 * M - 3 * gap) / 4;
  kpis.forEach(([label, value], i) => {
    const x = M + i * (bw + gap);
    doc.setFillColor(247, 248, 250); doc.setDrawColor(...PDF_LINE); doc.setLineWidth(0.25);
    doc.roundedRect(x, y, bw, 17, 2, 2, 'FD');
    doc.setFillColor(...PDF_RED); doc.rect(x, y + 2.5, 0.9, 12, 'F');
    doc.setFont('helvetica', 'normal'); doc.setFontSize(8); doc.setTextColor(...PDF_GREY);
    doc.text(label.toUpperCase(), x + 4, y + 6.5);
    doc.setFont('helvetica', 'bold'); doc.setFontSize(value.length > 12 ? 10 : 12); doc.setTextColor(...PDF_INK);
    doc.text(String(value), x + 4, y + 13);
  });
  y += 17 + 6;
  doc.setFont('helvetica', 'italic'); doc.setFontSize(8); doc.setTextColor(...PDF_GREY);
  const noteLines = doc.splitTextToSize(
    'Note: Sales are based on completed (picked up) orders. Purchases are units ordered by customers, excluding cancelled orders. ' +
    'Stock figures reflect current inventory and are independent of the selected period. Amounts are in Philippine Peso (PHP).', W - 2 * M);
  doc.text(noteLines, M, y, { lineHeightFactor: 1.4 });
  y += noteLines.length * 3.9 + 8;

  /* Sections */
  sections.forEach((cfg, idx) => {
    doc.setFont('helvetica', 'normal'); doc.setFontSize(10);
    const blocks = cfg.paragraphs.map(par => doc.splitTextToSize(par, W - 2 * M));
    const textH = blocks.reduce((a, l) => a + l.length * 5.1 + 2.5, 0);
    ensure(14 + textH + 30);

    doc.setFillColor(...PDF_RED); doc.rect(M, y - 5, 1.6, 7.5, 'F');
    doc.setTextColor(...PDF_INK); doc.setFont('helvetica', 'bold'); doc.setFontSize(13);
    doc.text(`${idx + 1}. ${cfg.title.replace(' Report', '')}`, M + 4.5, y + 0.5);
    y += 8;
    doc.setFont('helvetica', 'normal'); doc.setFontSize(10); doc.setTextColor(...PDF_INK);
    blocks.forEach(lines => {
      doc.text(lines, M, y, { lineHeightFactor: 1.45 });
      y += lines.length * 5.1 + 2.5;
    });
    y += 1;

    const colStyles = {};
    cfg.numCols.forEach(c => colStyles[c] = { halign:'right' });
    Object.entries(cfg.widths).forEach(([c, w]) => colStyles[c] = { ...(colStyles[c] || {}), cellWidth:w });

    doc.autoTable({
      startY: y,
      head: [cfg.head],
      body: cfg.body.length ? cfg.body : [[{ content:'No data available for this section.', colSpan:cfg.head.length, styles:{ halign:'center', textColor:PDF_GREY } }]],
      foot: cfg.body.length ? [cfg.foot] : undefined,
      showFoot: 'lastPage',
      margin: { left:M, right:M, top:22, bottom:18 },
      theme: 'grid',
      styles: { font:'helvetica', fontSize:8.5, cellPadding:2.4, textColor:PDF_INK, lineColor:PDF_LINE, lineWidth:0.2, overflow:'linebreak', valign:'middle' },
      headStyles: { fillColor:PDF_RED, textColor:255, fontStyle:'bold', fontSize:8, lineColor:PDF_RED },
      footStyles: { fillColor:[240, 241, 243], textColor:PDF_INK, fontStyle:'bold' },
      alternateRowStyles: { fillColor:[250, 250, 251] },
      columnStyles: colStyles,
      didParseCell: d => {
        if ((d.section === 'head' || d.section === 'foot') && cfg.numCols.includes(d.column.index)) d.cell.styles.halign = 'right';
      },
      didDrawPage: runningHeader
    });
    y = doc.lastAutoTable.finalY + 12;
  });

  /* Footer on every page */
  const pages = doc.getNumberOfPages();
  for (let i = 1; i <= pages; i++) {
    doc.setPage(i);
    doc.setDrawColor(...PDF_LINE); doc.setLineWidth(0.3); doc.line(M, H - 13, W - M, H - 13);
    doc.setFont('helvetica', 'normal'); doc.setFontSize(8); doc.setTextColor(...PDF_GREY);
    doc.text(`${SITE_NAME}  |  Confidential - for internal use only`, M, H - 8);
    doc.text(`Page ${i} of ${pages}`, W - M, H - 8, { align:'right' });
  }

  doc.save(`pharmacy-report-${period}-${now.toISOString().slice(0, 10)}.pdf`);
  showToast('Exported all 3 tables to PDF.');
}
/* PDF-END */

loadData();
</script>
</body>
</html>