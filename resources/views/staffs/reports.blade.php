<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $siteName }} — Reports</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  /* NOTE: colors swapped (same as the rest of the staff portal) — --red holds green, --green holds red */
  --red:        #2D7A2D;
  --red-h:      #236123;
  --red-light:  #e0f0e0;
  --green:      #CC1F1F;
  --green-light:#f5e0e0;
  --orange:     #e07b00;
  --orange-light:#fef3e2;
  --blue:       #1877f2;
  --blue-light: #e7f0fd;
  --white:      #ffffff;
  --bg:         #f0f2f5;
  --surface:    #ffffff;
  --surface2:   #f7f8fa;
  --card:       #ffffff;
  --hover:      rgba(0,0,0,.03);
  --border:     #dddfe2;
  --text:       #1c1e21;
  --muted:      #65676b;
  --subtle:     #a0a3a7;
  --input-bg:   #ffffff;
  --focus:      rgba(45,122,45,0.12);
  --sidebar-w:  240px;
  --header-h:   60px;
  --shadow-sm:  0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
  --shadow-md:  0 2px 8px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
}

[data-theme="dark"] {
  --bg:         #18191a;
  --surface:    #242526;
  --surface2:   #1e1f20;
  --card:       #242526;
  --hover:      rgba(255,255,255,.04);
  --border:     #3a3b3c;
  --text:       #e4e6eb;
  --muted:      #b0b3b8;
  --subtle:     #6a6b6c;
  --input-bg:   #3a3b3c;
  --focus:      rgba(45,122,45,0.18);
  --red-light:  rgba(45,122,45,0.15);
  --green-light:rgba(204,31,31,0.15);
  --orange-light:rgba(224,123,0,0.15);
  --blue-light: rgba(24,119,242,0.15);
  --shadow-sm:  0 1px 3px rgba(0,0,0,.3);
  --shadow-md:  0 2px 8px rgba(0,0,0,.4);
}

html, body { height: 100%; font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); transition: background .3s, color .3s; overflow-x: hidden; }

/* ── Overlay for mobile ── */
.overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 90; }
.overlay.on { display: block; }

/* ── SIDEBAR ── */
.sidebar {
  position: fixed; top: 0; left: 0;
  width: var(--sidebar-w); height: 100vh;
  background: var(--surface);
  border-right: 1px solid var(--border);
  display: flex; flex-direction: column;
  z-index: 100;
  transition: transform .3s ease, background .3s;
  overflow-y: auto;
}
.sidebar-logo {
  display: flex; align-items: center; gap: 10px;
  height: var(--header-h);
  padding: 0 16px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
  text-decoration: none;
  box-sizing: border-box;
}
.sidebar-logo-icon {
  width: 36px; height: 36px;
  background: var(--red);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; overflow: hidden;
}
.sidebar-logo-icon img { width: 36px; height: 36px; object-fit: cover; border-radius: 8px; }
.sidebar-logo-text { line-height: 1.2; }
.sidebar-logo-text strong { display: block; font-size: 14px; font-weight: 700; color: var(--text); }
.sidebar-logo-text span { font-size: 11px; color: var(--muted); }

.sidebar-section-label {
  font-size: 10px; font-weight: 700;
  text-transform: uppercase; letter-spacing: 1px;
  color: var(--subtle);
  padding: 18px 16px 6px;
}
.nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 14px; margin: 1px 8px;
  border-radius: 7px; cursor: pointer;
  font-size: 14px; font-weight: 500;
  color: var(--muted);
  transition: background .15s, color .15s;
  text-decoration: none; user-select: none;
}
.nav-item:hover { background: var(--bg); color: var(--text); }
.nav-item.active { background: var(--red-light); color: var(--red); font-weight: 600; }
.nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
.nav-badge {
  margin-left: auto;
  background: var(--red); color: #fff;
  font-size: 10px; font-weight: 700;
  border-radius: 10px; padding: 1px 6px;
  min-width: 18px; text-align: center;
}
.sidebar-bottom { margin-top: auto; padding: 12px 8px; border-top: 1px solid var(--border); }
.user-chip {
  display: flex; align-items: center; gap: 10px;
  padding: 8px 10px; border-radius: 7px;
  cursor: pointer; transition: background .15s;
}
.user-chip:hover { background: var(--bg); }
.user-avatar {
  width: 32px; height: 32px; border-radius: 50%;
  background: var(--red); color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 700;
  flex-shrink: 0; overflow: hidden;
}
.user-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
.user-info { flex: 1; min-width: 0; }
.user-name { font-size: 13px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.user-role { font-size: 11px; color: var(--muted); }

/* ── HEADER ── */
.header {
  position: fixed; top: 0; left: var(--sidebar-w); right: 0;
  height: var(--header-h);
  background: var(--surface);
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; gap: 12px;
  padding: 0 20px;
  z-index: 80;
  transition: left .3s, background .3s;
}
.hamburger {
  display: none; background: none; border: none; cursor: pointer;
  padding: 6px; border-radius: 6px; color: var(--muted);
  transition: background .15s, color .15s;
}
.hamburger:hover { background: var(--bg); color: var(--text); }
.header-title { font-size: 17px; font-weight: 700; color: var(--text); flex: 1; }

.header-search {
  display: flex; align-items: center; gap: 8px;
  background: var(--bg);
  border: 1.5px solid var(--border);
  border-radius: 7px; padding: 0 12px; height: 36px;
  transition: border-color .2s;
  position: relative;
}
.header-search:focus-within { border-color: var(--red); }
.header-search svg { width: 14px; height: 14px; color: var(--subtle); flex-shrink: 0; }
.header-search input {
  border: none; background: none; outline: none;
  font-family: 'Inter', sans-serif; font-size: 14px; color: var(--text);
  width: 180px;
}
.header-search input::placeholder { color: var(--subtle); }

/* ── HEADER SEARCH SUGGESTIONS ── */
.search-panel {
  position: absolute; top: calc(100% + 8px); right: 0;
  width: 380px; max-width: 90vw; max-height: 70vh; overflow-y: auto;
  background: var(--card); border: 1px solid var(--border);
  border-radius: 12px; box-shadow: 0 12px 32px rgba(0,0,0,.14);
  z-index: 60;
}
.search-panel[hidden] { display: none; }
.sp-label { padding: 10px 14px 4px; font-size: 11.5px; font-weight: 700; color: var(--subtle); }
.sp-item {
  display: flex; align-items: center; gap: 10px;
  padding: 8px 14px; color: var(--text); text-decoration: none; cursor: pointer;
}
.sp-item:hover, .sp-item.active { background: var(--red-light); }
.sp-thumb, .sp-avatar {
  width: 34px; height: 34px; flex-shrink: 0; overflow: hidden;
  display: flex; align-items: center; justify-content: center;
}
.sp-thumb { border-radius: 8px; background: #fff; border: 1px solid var(--border); font-size: 11px; font-weight: 700; color: var(--muted); }
.sp-thumb img { width: 100%; height: 100%; object-fit: contain; }
.sp-avatar { border-radius: 50%; color: #fff; font-size: 12px; font-weight: 700; }
.sp-avatar img { width: 100%; height: 100%; object-fit: cover; }
.sp-tone-1 { background: linear-gradient(135deg,#3b82f6,#1d4ed8); }
.sp-tone-2 { background: linear-gradient(135deg,#10b981,#047857); }
.sp-tone-3 { background: linear-gradient(135deg,#f59e0b,#b45309); }
.sp-tone-4 { background: linear-gradient(135deg,#ec4899,#be185d); }
.sp-tone-5 { background: linear-gradient(135deg,#8b5cf6,#6d28d9); }
.sp-tone-6 { background: linear-gradient(135deg,#06b6d4,#0e7490); }
.sp-text { min-width: 0; flex: 1; }
.sp-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sp-meta { font-size: 11.5px; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.search-panel mark { background: transparent; color: var(--red); font-weight: 700; }
.sp-foot {
  display: block; padding: 11px 14px; margin-top: 6px;
  border-top: 1px solid var(--border);
  font-size: 12.5px; font-weight: 600; color: var(--red); text-decoration: none;
}
.sp-foot:hover { background: var(--red-light); }
.sp-state { padding: 18px 14px; font-size: 13px; color: var(--muted); text-align: center; }

.header-actions { display: flex; align-items: center; gap: 8px; }
.icon-btn {
  width: 36px; height: 36px;
  background: var(--bg);
  border: 1.5px solid var(--border);
  border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; color: var(--muted);
  transition: border-color .2s, color .2s, background .2s;
  position: relative;
}
.icon-btn:hover { border-color: var(--red); color: var(--text); }
.icon-btn svg { width: 16px; height: 16px; }
.icon-btn .dot {
  position: absolute; top: 6px; right: 6px;
  width: 7px; height: 7px;
  background: var(--red);
  border-radius: 50%;
  border: 1.5px solid var(--surface);
}

/* ── NOTIFICATIONS ── */
.notif-dropdown {
  display: none; position: absolute; top: calc(100% + 10px); right: 0;
  width: 360px; max-width: 88vw;
  background: var(--card, #fff); border: 1px solid var(--border, #e5e7eb);
  border-radius: 12px; box-shadow: 0 12px 32px rgba(0,0,0,.14);
  z-index: 50; overflow: hidden;
}
.notif-head { display: flex; align-items: center; justify-content: space-between; gap: 8px; padding: 14px 16px; border-bottom: 1px solid var(--border, #e5e7eb); }
.notif-head-title { font-size: 13px; font-weight: 700; color: var(--text, #111); }
.notif-pill { font-size: 11px; font-weight: 600; color: var(--red); background: var(--red-light); padding: 2px 8px; border-radius: 20px; }
.notif-link-btn { background: none; border: none; cursor: pointer; font: inherit; font-size: 11.5px; font-weight: 600; color: var(--muted, #666); padding: 0; }
.notif-link-btn:hover { color: var(--red); }
.notif-list { max-height: 400px; overflow-y: auto; }
.notif-section { padding: 7px 16px; font-size: 10.5px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--muted, #666); background: var(--surface2, #f7f8fa); border-bottom: 1px solid var(--border, #e5e7eb); }
.notif-section.important { color: #CC1F1F; }
.notif-item { display: flex; gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--border, #e5e7eb); cursor: pointer; border-left: 3px solid transparent; position: relative; }
.notif-item:hover { background: var(--hover, rgba(0,0,0,.03)); }
.notif-item.important { border-left-color: var(--tone); background: color-mix(in srgb, var(--tone) 5%, transparent); }
.notif-item.read:not(.important) { opacity: .6; }
.notif-ico { width: 36px; height: 36px; flex-shrink: 0; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--tone); background: color-mix(in srgb, var(--tone) 13%, transparent); }
.notif-body { flex: 1; min-width: 0; }
.notif-title { font-size: 12.5px; font-weight: 600; color: var(--text, #111); display: flex; align-items: center; gap: 6px; }
.notif-unread { width: 7px; height: 7px; border-radius: 50%; background: var(--red); flex-shrink: 0; }
.notif-msg { font-size: 11.5px; color: var(--muted, #666); margin-top: 2px; line-height: 1.4; }
.notif-time { font-size: 10.5px; color: var(--subtle, #999); margin-top: 4px; }
.notif-empty { padding: 28px 16px; text-align: center; font-size: 12.5px; color: var(--muted, #666); }
.notif-foot { padding: 10px 16px; text-align: center; border-top: 1px solid var(--border, #e5e7eb); }
.notif-more { display: block; width: 100%; padding: 11px 16px; background: none; border: none; border-top: 1px solid var(--border, #e5e7eb); cursor: pointer; font: inherit; font-size: 12px; font-weight: 600; color: var(--red); }
.notif-more:hover { background: var(--hover, rgba(0,0,0,.03)); }

/* ── MAIN CONTENT ── */
.main {
  margin-left: var(--sidebar-w);
  margin-top: var(--header-h);
  min-height: calc(100vh - var(--header-h));
  padding: 24px;
  transition: margin-left .3s;
}

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
.export-list button .ico-csv { color:var(--red); }
.export-list button .ico-pdf { color:var(--green); }
.export-list button small { display:block; font-size:11px; font-weight:500; color:var(--muted); }

.table-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:13px; }
thead th { padding:11px 16px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:var(--subtle); background:var(--surface2); border-bottom:1px solid var(--border); white-space:nowrap; }
thead th.num, tbody td.num { text-align:right; }
tbody td { padding:12px 16px; border-bottom:1px solid var(--border); vertical-align:middle; }
tbody tr:last-child td { border-bottom:none; }
tbody tr:hover { background:rgba(45,122,45,.04); }
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

.card-tools input.tbl-search { border:1.5px solid var(--border); background:var(--bg); color:var(--text); border-radius:7px; height:34px; padding:0 10px; font-family:inherit; font-size:13px; outline:none; }
.card-tools input.tbl-search:focus { border-color:var(--red); }

.footer { text-align:center; font-size:12px; color:var(--subtle); padding:12px 0 4px; }

/* ── TOAST ── */
.toast {
  position: fixed; bottom: 24px; right: 24px;
  background: var(--red); color: #fff;
  padding: 12px 18px; border-radius: 8px;
  font-size: 13px; font-weight: 600;
  box-shadow: 0 4px 16px rgba(0,0,0,.2);
  transform: translateY(20px); opacity: 0;
  transition: opacity .3s, transform .3s;
  pointer-events: none; z-index: 200;
  display: flex; align-items: center; gap: 8px;
}
.toast.on { opacity: 1; transform: translateY(0); }
.toast svg { width: 16px; height: 16px; }

/* ── RESPONSIVE ── */
@media (max-width:1100px) { .stats-grid { grid-template-columns:repeat(2,1fr); } }
@media (max-width:768px) {
  .sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); }
  .sidebar.open { transform: translateX(0); }
  .header { left: 0; }
  .main { margin-left: 0; padding: 16px; }
  .hamburger { display: flex; }
  .header-search { display: none; }
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

@php $staffUser = auth('staff')->user(); @endphp

<div class="toast" id="toast">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="toastMsg">Done.</span>
</div>
<div class="overlay" id="overlay"></div>

<aside class="sidebar" id="sidebar">
  <a class="sidebar-logo" href="{{ route('staff.dashboard') }}">
    <div class="sidebar-logo-icon">
      <img src="{{ $logo2 ? asset($logo2) : '' }}" alt="{{ $siteName }}">
    </div>
    <div class="sidebar-logo-text">
      <strong>{{ $siteName }}</strong>
      <span>Staff Portal</span>
    </div>
  </a>

  <div class="sidebar-section-label">Main</div>
  <a class="nav-item" href="{{ route('staff.dashboard') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>
  <a class="nav-item" href="{{ route('staff.inventory') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
    Inventory<span class="nav-badge">3</span>
  </a>
  <a class="nav-item" href="{{ route('staff.orders') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    Orders
  </a>

  <div class="sidebar-section-label">Manage</div>
  <a class="nav-item" href="{{ route('staff.stocks') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    Stocks
  </a>
  <a class="nav-item active" href="{{ route('staff.reports') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
    Reports
  </a>
  <a class="nav-item" href="{{ route('staff.expiry') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    Expiry Tracking<span class="nav-badge">2</span>
  </a>

        <a class="nav-item" href="{{ route('staff.settings') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    Settings
  </a>

  <a href = "{{ route('staff.profile') }}" style = "text-decoration: none;">
  <div class="sidebar-bottom">
      @csrf
      <button type="submit" class="user-chip" style="width:100%;border:none;cursor:pointer;font:inherit;text-align:left;background:transparent;">
        <div class="user-avatar">
          @if(auth('staff')->user()->profile_picture)
            <img src="{{ asset(auth('staff')->user()->profile_picture) }}" alt="{{ auth('staff')->user()->first_name ?? 'Staff' }} {{ auth('staff')->user()->last_name ?? 'User' }}" />
          @else
            {{ strtoupper(substr(auth('staff')->user()->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr(auth('staff')->user()->last_name ?? 'T', 0, 1)) }}
          @endif
        </div>
        <div class="user-info">
          <div class="user-name">{{ auth('staff')->user()->first_name ?? 'Staff' }} {{ auth('staff')->user()->last_name ?? 'User' }}</div>
          <div class="user-role">Pharmacy Staff</div>
        </div>
      </button>
  </div>
</a>
</aside>

<header class="header">
  <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="header-title">Reports</div>
  <form class="header-search" id="staffSearch" action="{{ route('staff.search') }}" method="GET" role="search" autocomplete="off">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="q" id="staffSearchInput" placeholder="Search products…" value="{{ $q ?? '' }}" maxlength="100" aria-label="Search products" aria-controls="staffSearchPanel" aria-expanded="false">
    <div class="search-panel" id="staffSearchPanel" role="listbox" hidden></div>
  </form>
  <div class="header-actions">
    <div style="position:relative;">
      <button class="icon-btn" id="notifBtn" title="Notifications">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <div class="dot" id="notifDot" style="display:none;"></div>
      </button>

      <div id="notifDropdown" class="notif-dropdown">
        <div class="notif-head">
          <span class="notif-head-title">Notifications</span>
          <span style="display:flex;align-items:center;gap:10px;">
            <button type="button" class="notif-link-btn" id="notifReadAll" style="display:none;">Mark all read</button>
            <span class="notif-pill" id="notifCount" style="display:none;"></span>
          </span>
        </div>

        <div class="notif-list" id="notifList">
          <div class="notif-empty">Loading…</div>
        </div>

        <div class="notif-foot">
          <button type="button" class="notif-link-btn" id="notifMore" style="color:var(--red);font-size:12px;">View all notifications</button>
        </div>
      </div>
    </div>

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
        <input type="text" class="tbl-search" id="tableSearch" placeholder="Filter products…" aria-label="Filter products table">
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
      <div class="card-tools"></div>
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
      <div class="card-tools"></div>
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

  <div class="footer">{{ $siteName }} Inventory System &nbsp;·&nbsp; Zone 2, Sogod, Southern Leyte &nbsp;·&nbsp; Staff Access Only</div>
</main>

<script>
// ── Notifications (data comes from StaffNotificationsController) ─────────────
(function () {
  const btn      = document.getElementById('notifBtn');
  const dropdown = document.getElementById('notifDropdown');
  if (!btn || !dropdown) return;

  const listEl    = document.getElementById('notifList');
  const dotEl     = document.getElementById('notifDot');
  const countEl   = document.getElementById('notifCount');
  const readAllEl = document.getElementById('notifReadAll');
  const moreEl    = document.getElementById('notifMore');

  const URLS = {
    list:    @json(route('staff.notifications')),
    all:     @json(route('staff.notifications.all')),
    readAll: @json(route('staff.notifications.readAll')),
    read:    @json(route('staff.notifications.read', ['notification' => '__ID__'])),
  };
  const CSRF = @json(csrf_token());
  const POLL_MS = 60000;
  const LIMIT = 15;
  const PER_PAGE = 20;

  let items = [];
  let expanded = false;     // false = latest 15 · true = full paginated list ("View all")
  let nextPage = null;

  // Stock-related notification types → ?status= value understood by the stocks page.
  const STOCK_STATUS = { out_of_stock: 'out-of-stock', low_stock: 'low-stock', expired_stock: 'expired', expiring_stock: 'expiring-soon' };
  const STOCKS_URL = @json(route('staff.stocks'));

  function openStocks(status) { window.location.href = STOCKS_URL + '?status=' + status; }

  const TONES = { danger: '#CC1F1F', warning: '#e07b00', info: '#2563eb', success: '#2D7A2D', neutral: '#65676b' };
  const svg = p => `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${p}</svg>`;
  const ICONS = {
    box:    svg('<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>'),
    alert:  svg('<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'),
    clock:  svg('<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'),
    order:  svg('<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>'),
    check:  svg('<polyline points="20 6 9 17 4 12"/>'),
    x:      svg('<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>'),
    money:  svg('<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>'),
    file:   svg('<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>'),
    user:   svg('<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'),
    bell:   svg('<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>'),
  };
  const TYPE_ICON = {
    out_of_stock: 'box', low_stock: 'alert', expired_stock: 'x', expiring_stock: 'clock',
    order_pending: 'order', order_confirmed: 'order', order_ready: 'check',
    refund_pending: 'money', payment_proof: 'money', prescription_uploaded: 'file',
    order_completed: 'check', order_cancelled: 'x', new_customer: 'user',
  };

  const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

  async function api(url, method = 'GET') {
    const res = await fetch(url, {
      method,
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF },
      credentials: 'same-origin',
      keepalive: true,
    });
    if (!res.ok) throw new Error(`${url} → ${res.status}`);
    return res.json();
  }

  function itemHtml(n) {
    const tone = TONES[n.tone] || TONES.neutral;
    return `
      <div class="notif-item ${n.important ? 'important' : ''} ${n.is_read ? 'read' : ''}" data-id="${n.id}" style="--tone:${tone}">
        <div class="notif-ico">${ICONS[TYPE_ICON[n.type]] || ICONS.bell}</div>
        <div class="notif-body">
          <div class="notif-title">${esc(n.title)}${n.is_read ? '' : '<span class="notif-unread"></span>'}</div>
          <div class="notif-msg">${esc(n.message)}</div>
          <div class="notif-time">${esc(n.time)}</div>
        </div>
      </div>`;
  }

  function render(data) {
    items = data.items || [];

    // Badge / dot / header
    const unread = data.unread || 0, important = data.important || 0;
    dotEl.style.display = (unread > 0 || important > 0) ? '' : 'none';
    countEl.style.display = unread > 0 ? '' : 'none';
    countEl.textContent = `${unread} New`;
    readAllEl.style.display = unread > 0 ? '' : 'none';
    moreEl.textContent = expanded ? 'Show recent only' : 'View all notifications';

    if (!items.length) {
      listEl.innerHTML = '<div class="notif-empty">You\'re all caught up 🎉</div>';
      return;
    }

    // Important (unhandled) ones are pinned in their own section at the top.
    const pinned = items.filter(n => n.important);
    const rest   = items.filter(n => !n.important);
    let html = '';
    if (pinned.length) {
      html += `<div class="notif-section important">Needs attention · ${pinned.length}</div>` + pinned.map(itemHtml).join('');
    }
    if (rest.length) {
      if (pinned.length) html += '<div class="notif-section">Recent activity</div>';
      html += rest.map(itemHtml).join('');
    }
    if (expanded && nextPage) html += '<button type="button" class="notif-more" id="notifLoadMore">Load more</button>';
    listEl.innerHTML = html;
  }

  async function load(append = false) {
    try {
      if (!expanded) {
        render(await api(`${URLS.list}?limit=${LIMIT}`));
        return;
      }
      const page = append ? nextPage : 1;
      const data = await api(`${URLS.all}?page=${page}&per_page=${PER_PAGE}`);
      nextPage = data.has_more ? data.next_page : null;
      if (append) data.items = items.concat(data.items || []);
      render(data);
    } catch (err) {
      console.error(err);
      if (dropdown.style.display === 'block') listEl.innerHTML = '<div class="notif-empty">Couldn\'t load notifications.</div>';
    }
  }

  // Click a notification → mark it read, then go to the related page.
  listEl.addEventListener('click', async (e) => {
    if (e.target.closest('#notifLoadMore')) { load(true); return; }
    const row = e.target.closest('.notif-item');
    if (!row) return;
    const n = items.find(x => String(x.id) === row.dataset.id);
    if (!n) return;
    try { if (!n.is_read) await api(URLS.read.replace('__ID__', n.id), 'PUT'); } catch (err) { console.error(err); }
    // Stock notifications open the stocks table pre-filtered to that status.
    const stockStatus = STOCK_STATUS[n.type];
    if (stockStatus) { openStocks(stockStatus); return; }
    if (n.link) window.location.href = n.link; else load();
  });

  readAllEl.addEventListener('click', async () => {
    try { await api(URLS.readAll, 'PUT'); } catch (err) { console.error(err); }
    load();
  });

  // "View all notifications" ⇄ "Show recent only": staff have no Settings page,
  // so the full paginated list is shown right inside the bell.
  moreEl.addEventListener('click', () => {
    expanded = !expanded;
    nextPage = null;
    listEl.scrollTop = 0;
    load();
  });

  // Open / close
  function openDropdown() { dropdown.style.display = 'block'; load(); }
  btn.addEventListener('click', (e) => {
    e.stopPropagation();
    if (dropdown.style.display === 'block') dropdown.style.display = 'none';
    else openDropdown();
  });
  document.addEventListener('click', (e) => {
    if (dropdown.style.display === 'block' && !dropdown.contains(e.target) && !btn.contains(e.target)) {
      dropdown.style.display = 'none';
    }
  });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') dropdown.style.display = 'none'; });

  // Initial load + keep the badge fresh (the expanded list is not auto-refreshed).
  load();
  setInterval(() => { if (!document.hidden && !expanded) load(); }, POLL_MS);
})();
</script>
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

/* ── Data (loaded from StaffReportsController@data) ── */
const DATA_URL = @json(route('staff.reports.data'));
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
const searchInput = document.getElementById('tableSearch');
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
function showToast(msg) {
  const t = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;
  t.classList.add('on');
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
  const blob = new Blob(['﻿' + csv], { type:'text/csv;charset=utf-8;' });
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
const PREPARED_BY = @json(trim(($staffUser->first_name ?? 'Staff') . ' ' . ($staffUser->last_name ?? '')));

const PDF_RED  = [45, 122, 45];
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
  doc.setFont('helvetica', 'normal'); doc.setFontSize(9); doc.text('Pharmacy Inventory System  |  Staff Portal', textX, 19.5);
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

<script>
/* ══════════════════════════════════════════════════════
   HEADER SEARCH — products
   • Typing shows live suggestions (StaffSearchController@suggest).
   • Clicking a suggestion opens that product's information modal
     on the inventory page (?open={id}).
   • Pressing Enter, or "See all results", goes to the results page.
   ══════════════════════════════════════════════════════ */
(function () {
  const form  = document.getElementById('staffSearch');
  if (!form) return;
  const input = document.getElementById('staffSearchInput');
  const panel = document.getElementById('staffSearchPanel');
  const SUGGEST_URL = "{{ route('staff.search.suggest') }}";

  let timer = null, controller = null, links = [], active = -1;

  const esc = s => String(s ?? '').replace(/[&<>"']/g,
    c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

  // Split the raw text on the match and escape every piece, so user text can never
  // inject markup and a search like "amp" can't cut through an &amp; entity.
  const hl = (text, q) => {
    text = String(text ?? '');
    if (!q) return esc(text);
    const re = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
    return text.split(re).map((part, i) => i % 2 ? '<mark>' + esc(part) + '</mark>' : esc(part)).join('');
  };

  const open  = () => { panel.hidden = false; input.setAttribute('aria-expanded', 'true'); };
  const close = () => { panel.hidden = true;  input.setAttribute('aria-expanded', 'false'); setActive(-1); };

  function setActive(i) {
    links.forEach(a => a.classList.remove('active'));
    active = i;
    if (i >= 0 && links[i]) {
      links[i].classList.add('active');
      links[i].scrollIntoView({ block: 'nearest' });
    }
  }

  const productRow = (p, q) => `
    <a class="sp-item" role="option" href="${esc(p.url)}">
      <span class="sp-thumb">${p.image
        ? `<img src="${esc(p.image)}" alt="">`
        : esc(p.name.slice(0, 2).toUpperCase())}</span>
      <div class="sp-text">
        <div class="sp-name">${hl(p.name, q)}</div>
        <div class="sp-meta">${hl(p.meta, q)}</div>
      </div>
    </a>`;

  function render(data) {
    const q = data.query;
    if (!data.products.length) {
      panel.innerHTML = `<div class="sp-state">No products match “${esc(q)}”.</div>`;
    } else {
      panel.innerHTML =
        `<div class="sp-label">Products</div>${data.products.map(p => productRow(p, q)).join('')}` +
        `<a class="sp-foot" href="${esc(data.results_url)}">See all results for “${esc(q)}”</a>`;
    }
    links = Array.from(panel.querySelectorAll('.sp-item'));
    setActive(-1);
    open();
  }

  async function fetchSuggestions() {
    const q = input.value.trim();
    if (!q) { close(); return; }

    if (controller) controller.abort();
    controller = new AbortController();

    try {
      const res = await fetch(`${SUGGEST_URL}?q=${encodeURIComponent(q)}`, {
        signal: controller.signal,
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      });
      if (!res.ok) throw new Error('Suggestions unavailable');
      const data = await res.json();
      if (input.value.trim() !== q) return;   // a newer keystroke already superseded this
      render(data);
    } catch (err) {
      if (err.name !== 'AbortError') close();  // Enter still works without suggestions
    }
  }

  input.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(fetchSuggestions, 180);
  });

  input.addEventListener('focus', () => {
    if (input.value.trim() && panel.innerHTML) open();
  });

  input.addEventListener('keydown', e => {
    if (e.key === 'Escape') { close(); return; }
    if (panel.hidden || !links.length) return;

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      setActive(active + 1 >= links.length ? 0 : active + 1);
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      setActive(active <= 0 ? links.length - 1 : active - 1);
    } else if (e.key === 'Enter' && active >= 0) {
      e.preventDefault();                       // a highlighted suggestion wins over the results page
      window.location.href = links[active].href;
    }
  });

  form.addEventListener('submit', e => {
    if (!input.value.trim()) e.preventDefault();
  });

  document.addEventListener('click', e => {
    if (!form.contains(e.target)) close();
  });
})();
</script>

</body>
</html>