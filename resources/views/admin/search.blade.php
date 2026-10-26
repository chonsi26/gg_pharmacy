<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $siteName }} — {{ $q !== '' ? 'Search: ' . $q : 'Search' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --red:        #CC1F1F;
  --red-h:      #b51a1a;
  --red-light:  #f5e0e0;
  --green:      #2D7A2D;
  --green-light:#e0f0e0;
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
  --focus:      rgba(204,31,31,0.12);
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
  --focus:      rgba(204,31,31,0.18);
  --red-light:  rgba(204,31,31,0.15);
  --green-light:rgba(45,122,45,0.15);
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
  position: fixed;
  top: 0; left: 0;
  width: var(--sidebar-w);
  height: 100vh;
  background: var(--surface);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  z-index: 100;
  transition: transform .3s ease, background .3s;
  overflow-y: auto;
}

.sidebar-logo {
  display: flex;
  align-items: center;
  gap: 10px;
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
  flex-shrink: 0;
  overflow: hidden;
}
.sidebar-logo-icon img { width: 36px; height: 36px; object-fit: cover; border-radius: 8px; }
.sidebar-logo-text { line-height: 1.2; }
.sidebar-logo-text strong {
  display: block;
  font-size: 14px; font-weight: 700;
  color: var(--text);
}
.sidebar-logo-text span {
  font-size: 11px;
  color: var(--muted);
}

.sidebar-section-label {
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--subtle);
  padding: 18px 16px 6px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 14px;
  margin: 1px 8px;
  border-radius: 7px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  color: var(--muted);
  transition: background .15s, color .15s;
  text-decoration: none;
  user-select: none;
}
.nav-item:hover { background: var(--bg); color: var(--text); }
.nav-item.active { background: var(--red-light); color: var(--red); font-weight: 600; }
.nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
.nav-badge {
  margin-left: auto;
  background: var(--red);
  color: #fff;
  font-size: 10px; font-weight: 700;
  border-radius: 10px;
  padding: 1px 6px;
  min-width: 18px;
  text-align: center;
}

.sidebar-bottom {
  margin-top: auto;
  padding: 12px 8px;
  border-top: 1px solid var(--border);
}
.user-chip {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 10px;
  border-radius: 7px;
  cursor: pointer;
  transition: background .15s;
}
.user-chip:hover { background: var(--bg); }
.user-avatar {
  width: 32px; height: 32px;
  border-radius: 50%;
  background: var(--red);
  color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; font-weight: 700;
  flex-shrink: 0;
  overflow: hidden;
}
.user-avatar img {
  width: 100%; height: 100%;
  object-fit: cover;
  display: block;
}
.user-info { flex: 1; min-width: 0; }
.user-name { font-size: 13px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.user-role { font-size: 11px; color: var(--muted); }

/* ── HEADER ── */
.header {
  position: fixed;
  top: 0;
  left: var(--sidebar-w);
  right: 0;
  height: var(--header-h);
  background: var(--surface);
  border-bottom: 1px solid var(--border);
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 0 20px;
  z-index: 80;
  transition: left .3s, background .3s;
}

.hamburger {
  display: none;
  background: none; border: none;
  cursor: pointer;
  padding: 6px;
  border-radius: 6px;
  color: var(--muted);
  transition: background .15s, color .15s;
}
.hamburger:hover { background: var(--bg); color: var(--text); }

.header-title {
  font-size: 17px;
  font-weight: 700;
  color: var(--text);
  flex: 1;
}

.header-search {
  display: flex;
  align-items: center;
  gap: 8px;
  background: var(--bg);
  border: 1.5px solid var(--border);
  border-radius: 7px;
  padding: 0 12px;
  height: 36px;
  transition: border-color .2s;
}
.header-search:focus-within { border-color: var(--red); }
.header-search svg { width: 14px; height: 14px; color: var(--subtle); flex-shrink: 0; }
.header-search input {
  border: none; background: none; outline: none;
  font-family: 'Inter', sans-serif;
  font-size: 14px; color: var(--text);
  width: 180px;
}
.header-search input::placeholder { color: var(--subtle); }

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
  position: absolute;
  top: 6px; right: 6px;
  width: 7px; height: 7px;
  background: var(--red);
  border-radius: 50%;
  border: 1.5px solid var(--surface);
}

.main {
  margin-left: var(--sidebar-w);
  margin-top: var(--header-h);
  min-height: calc(100vh - var(--header-h));
  padding: 24px;
  transition: margin-left .3s;
}


/* ── HEADER SEARCH SUGGESTIONS ── */
.header-search { position: relative; }
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


/* ── Responsive shell (same breakpoints as the dashboard) ── */
@media (max-width: 768px) {
  .sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); }
  .sidebar.open { transform: translateX(0); }
  .header { left: 0; }
  .main { margin-left: 0; padding: 16px; }
  .hamburger { display: flex; }
  .header-search { display: none; }
}

/* ── SEARCH RESULTS PAGE ── */
.main { max-width: 1280px; }
.pill {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 20px; white-space: nowrap;
}
.pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
.pill.ok     { background: var(--green-light);  color: var(--green); }
.pill.warning{ background: var(--orange-light); color: var(--orange); }
.pill.danger { background: var(--red-light);    color: var(--red); }

.sr-top { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 18px; }
.sr-title { font-size: 20px; font-weight: 800; color: var(--text); }
.sr-sub { font-size: 13px; color: var(--muted); margin-top: 3px; }
.sr-sub strong { color: var(--text); font-weight: 600; }
.sr-form { display: flex; gap: 8px; width: 100%; max-width: 440px; }
.sr-form input {
  flex: 1; min-width: 0; height: 38px; padding: 0 12px;
  background: var(--input-bg); color: var(--text);
  border: 1.5px solid var(--border); border-radius: 7px;
  font: 14px 'Inter', sans-serif; outline: none; transition: border-color .2s, box-shadow .2s;
}
.sr-form input:focus { border-color: var(--red); box-shadow: 0 0 0 3px var(--focus); }
.sr-form button {
  height: 38px; padding: 0 18px; border: none; border-radius: 7px; cursor: pointer;
  background: var(--red); color: #fff; font: 600 13px 'Inter', sans-serif; transition: background .15s;
}
.sr-form button:hover { background: var(--red-h); }

.sr-tabs { display: flex; gap: 4px; border-bottom: 1px solid var(--border); margin-bottom: 22px; overflow-x: auto; }
.sr-tab {
  padding: 10px 14px; font-size: 13.5px; font-weight: 600; color: var(--muted);
  text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -1px; white-space: nowrap;
}
.sr-tab:hover { color: var(--text); }
.sr-tab.on { color: var(--red); border-bottom-color: var(--red); }
.sr-tab .n { margin-left: 6px; font-size: 11.5px; font-weight: 600; color: var(--subtle); }
.sr-tab.on .n { color: var(--red); }

.sr-section + .sr-section { margin-top: 30px; }
.sr-section-head { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; margin-bottom: 12px; }
.sr-section-head h2 { font-size: 15px; font-weight: 700; color: var(--text); }
.sr-more { font-size: 12.5px; font-weight: 600; color: var(--red); text-decoration: none; }
.sr-more:hover { text-decoration: underline; }

mark { background: transparent; color: var(--red); font-weight: 700; }

/* Product cards */
.sr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 16px; }
.p-card {
  display: flex; flex-direction: column; text-decoration: none; color: inherit;
  background: var(--card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden;
  transition: border-color .15s, box-shadow .15s;
}
.p-card:hover, .s-card:hover { border-color: var(--red); box-shadow: var(--shadow-md); }
.p-card:focus-visible, .s-card:focus-visible { outline: 2px solid var(--red); outline-offset: 2px; }
.p-img { position: relative; height: 150px; background: #fff; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: center; }
.p-img img { width: 100%; height: 100%; object-fit: contain; padding: 8px; }
.p-img .fallback { width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 18px; }
.p-img .tag { position: absolute; top: 10px; left: 10px; }
.p-body { padding: 12px 14px 14px; display: flex; flex-direction: column; gap: 6px; flex: 1; }
.p-name { font-size: 14px; font-weight: 700; color: var(--text); line-height: 1.3; }
.p-meta { font-size: 12px; color: var(--muted); }
.p-cat { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--muted); }
.p-cat i { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.p-foot { margin-top: auto; padding-top: 8px; display: flex; align-items: baseline; justify-content: space-between; gap: 8px; }
.p-price { font-size: 15px; font-weight: 800; color: var(--text); }
.p-price s { margin-left: 5px; font-size: 11.5px; font-weight: 500; color: var(--subtle); }
.p-stock { font-size: 12px; font-weight: 600; }
.p-stock.ok { color: var(--green); } .p-stock.low { color: var(--orange); } .p-stock.out { color: var(--red); }

/* Staff cards */
.s-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 16px; }
.s-card {
  display: flex; align-items: center; gap: 14px; padding: 14px 16px; text-decoration: none; color: inherit;
  background: var(--card); border: 1px solid var(--border); border-radius: 12px;
  transition: border-color .15s, box-shadow .15s;
}
.s-avatar { width: 52px; height: 52px; border-radius: 50%; overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 17px; }
.s-avatar img { width: 100%; height: 100%; object-fit: cover; }
.tone-1 { background: linear-gradient(135deg,#3b82f6,#1d4ed8); }
.tone-2 { background: linear-gradient(135deg,#10b981,#047857); }
.tone-3 { background: linear-gradient(135deg,#f59e0b,#b45309); }
.tone-4 { background: linear-gradient(135deg,#ec4899,#be185d); }
.tone-5 { background: linear-gradient(135deg,#8b5cf6,#6d28d9); }
.tone-6 { background: linear-gradient(135deg,#06b6d4,#0e7490); }
.s-text { min-width: 0; flex: 1; }
.s-name-row { display: flex; align-items: center; gap: 8px; }
.s-name { font-size: 14px; font-weight: 700; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.s-code { font-size: 11px; font-weight: 600; color: var(--muted); background: var(--bg); border: 1px solid var(--border); border-radius: 5px; padding: 1px 6px; flex-shrink: 0; }
.s-line { font-size: 12.5px; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }

/* Empty / prompt states */
.sr-empty { text-align: center; padding: 70px 20px; background: var(--card); border: 1px dashed var(--border); border-radius: 12px; }
.sr-empty h3 { font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
.sr-empty p { font-size: 13px; color: var(--muted); max-width: 420px; margin: 0 auto; line-height: 1.55; }

/* Pager */
.sr-pager { display: flex; align-items: center; justify-content: center; gap: 14px; margin-top: 26px; font-size: 13px; color: var(--muted); }
.sr-pager a, .sr-pager span.btn {
  padding: 7px 14px; border: 1.5px solid var(--border); border-radius: 7px; font-weight: 600;
  color: var(--text); text-decoration: none; background: var(--card);
}
.sr-pager a:hover { border-color: var(--red); color: var(--red); }
.sr-pager span.btn { opacity: .4; }
</style>
</head>
<body>
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
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
    Staffs
  </a>

  <div class="sidebar-section-label">Manage</div>
  <a class="nav-item" href="{{ route('admin.stocks') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    Stocks
  </a>
  <a class="nav-item" href="{{ route('admin.reports') }}">
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
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
    Financial Records
  </a>
  <a class="nav-item" href="{{ route('admin.settings') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    Settings
  </a>

<a href = "{{ route('admin.profile') }}" style = "text-decoration: none;">
  <div class="sidebar-bottom">
    <div class="user-chip">
        @if(auth('admin')->user()->profile_picture)
      <img id="sidebarAvatarImg" src = "{{ asset(auth('admin')->user()->profile_picture) }}" class="user-avatar" style="object-fit:cover;">
      @else
          <div id="sidebarAvatarImg" class="user-avatar">{{ strtoupper(substr(auth('admin')->user()->first_name ?? 'A', 0, 1)) }}{{ strtoupper(substr(auth('admin')->user()->last_name ?? 'D', 0, 1)) }}</div>
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
  <div class="header-title">Search</div>
  <form class="header-search" id="adminSearch" action="{{ route('admin.search') }}" method="GET" role="search" autocomplete="off">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="q" id="adminSearchInput" placeholder="Search products, staffs…" value="{{ $q ?? '' }}" maxlength="100" aria-label="Search products and staffs" aria-controls="adminSearchPanel" aria-expanded="false">
    <div class="search-panel" id="adminSearchPanel" role="listbox" hidden></div>
  </form>
  <div class="header-actions">
    <button class="icon-btn" id="themeBtn" title="Toggle dark mode">
      <span id="themeIco"></span>
    </button>
  </div>
</header>

@php
  // Wrap every match in <mark>. Split on the raw text and escape each piece, so
  // searching for "amp" can never cut through an &amp; entity.
  $hl = function ($text) use ($q) {
      $text = (string) $text;
      if ($q === '') return e($text);
      $parts = preg_split('/(' . preg_quote($q, '/') . ')/iu', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
      if ($parts === false) return e($text);
      $out = '';
      foreach ($parts as $i => $part) {
          $out .= $i % 2 ? '<mark>' . e($part) . '</mark>' : e($part);
      }
      return $out;
  };

  // Same image resolution rules as the rest of the admin area.
  $productImage = function (?string $image) {
      if (! $image) return null;
      if (\Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])) return $image;
      if (\Illuminate\Support\Str::startsWith($image, 'storage/')) return asset($image);
      return asset('storage/' . ltrim($image, '/'));
  };

  $total = $productsTotal + $staffsTotal;
  $tabUrl = fn (string $t) => route('admin.search', array_filter(['q' => $q, 'type' => $t === 'all' ? null : $t]));
@endphp

<main class="main">

  <div class="sr-top">
    <div>
      <div class="sr-title">Search results</div>
      <div class="sr-sub">
        @if ($q === '')
          Find products and staff in one place.
        @else
          <strong>{{ number_format($total) }}</strong> {{ \Illuminate\Support\Str::plural('result', $total) }} for <strong>“{{ $q }}”</strong>
        @endif
      </div>
    </div>
    <form class="sr-form" action="{{ route('admin.search') }}" method="GET">
      @if ($type !== 'all') <input type="hidden" name="type" value="{{ $type }}"> @endif
      <input type="text" name="q" value="{{ $q }}" placeholder="Search products, staffs…" maxlength="100" aria-label="Search products and staffs">
      <button type="submit">Search</button>
    </form>
  </div>

  @if ($q === '')

    <div class="sr-empty">
      <h3>What are you looking for?</h3>
      <p>Search products by name, generic name, form, category or brand — and staff by name, email, contact number or staff ID (like STF-0007).</p>
    </div>

  @else

    <nav class="sr-tabs" aria-label="Result type">
      <a class="sr-tab {{ $type === 'all' ? 'on' : '' }}" href="{{ $tabUrl('all') }}">All<span class="n">{{ $total }}</span></a>
      <a class="sr-tab {{ $type === 'products' ? 'on' : '' }}" href="{{ $tabUrl('products') }}">Products<span class="n">{{ $productsTotal }}</span></a>
      <a class="sr-tab {{ $type === 'staffs' ? 'on' : '' }}" href="{{ $tabUrl('staffs') }}">Staffs<span class="n">{{ $staffsTotal }}</span></a>
    </nav>

    @php
      $showProducts = $type !== 'staffs'   && $productsTotal > 0;
      $showStaffs   = $type !== 'products' && $staffsTotal > 0;
    @endphp

    @if (! $showProducts && ! $showStaffs)

      <div class="sr-empty">
        <h3>No {{ $type === 'all' ? 'products or staffs' : $type }} match “{{ $q }}”</h3>
        <p>Check the spelling, try a shorter word, or search by brand, category or email instead.</p>
      </div>

    @endif

    @if ($showProducts)
    <section class="sr-section">
      <div class="sr-section-head">
        <h2>Products</h2>
        @if ($type === 'all' && $productsTotal > $products->count())
          <a class="sr-more" href="{{ $tabUrl('products') }}">See all {{ number_format($productsTotal) }} products</a>
        @endif
      </div>
      <div class="sr-grid">
        @foreach ($products as $product)
          @php
            $img   = $productImage($product->image);
            $qty   = (int) ($product->available_qty ?? 0);
            $cat   = $product->category;
            $color = $cat->icon_color ?? '#6b7280';
            $meta  = collect([$product->generic_name, $product->form])->filter()->implode(' · ');
          @endphp
          <a class="p-card" href="{{ route('admin.inventory', ['open' => $product->id]) }}">
            <div class="p-img">
              @if ($img)
                <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy">
              @else
                <span class="fallback" style="background: {{ $color }};">{{ strtoupper(mb_substr($product->name, 0, 2)) }}</span>
              @endif
              @if (! $product->is_active)
                <span class="tag pill danger">Inactive</span>
              @elseif ($product->requires_prescription)
                <span class="tag pill warning">Rx only</span>
              @endif
            </div>
            <div class="p-body">
              <div class="p-name">{!! $hl($product->name) !!}</div>
              @if ($meta !== '') <div class="p-meta">{!! $hl($meta) !!}</div> @endif
              <div class="p-cat">
                <i style="background: {{ $color }};"></i>
                <span>{!! $hl($cat->name ?? 'Uncategorized') !!}@if ($product->brand) · {!! $hl($product->brand->name) !!}@endif</span>
              </div>
              <div class="p-foot">
                <div class="p-price">{{ $product->formattedPrice() }}@if ($product->hasDiscount())<s>{{ $product->formattedOldPrice() }}</s>@endif</div>
                @if ($qty <= 0)
                  <span class="p-stock out">Out of stock</span>
                @elseif ($qty <= 10)
                  <span class="p-stock low">{{ $qty }} left</span>
                @else
                  <span class="p-stock ok">{{ number_format($qty) }} in stock</span>
                @endif
              </div>
            </div>
          </a>
        @endforeach
      </div>

      @if ($type === 'products' && $products->hasPages())
        <div class="sr-pager">
          @if ($products->onFirstPage()) <span class="btn">Previous</span> @else <a href="{{ $products->previousPageUrl() }}">Previous</a> @endif
          <span>Page {{ $products->currentPage() }} of {{ $products->lastPage() }}</span>
          @if ($products->hasMorePages()) <a href="{{ $products->nextPageUrl() }}">Next</a> @else <span class="btn">Next</span> @endif
        </div>
      @endif
    </section>
    @endif

    @if ($showStaffs)
    <section class="sr-section">
      <div class="sr-section-head">
        <h2>Staffs</h2>
        @if ($type === 'all' && $staffsTotal > $staffs->count())
          <a class="sr-more" href="{{ $tabUrl('staffs') }}">See all {{ number_format($staffsTotal) }} staffs</a>
        @endif
      </div>
      <div class="s-grid">
        @foreach ($staffs as $staff)
          @php
            $fullName = trim($staff->first_name . ' ' . $staff->last_name);
            $code     = 'STF-' . str_pad($staff->id, 4, '0', STR_PAD_LEFT);
            $initials = strtoupper(mb_substr($staff->first_name, 0, 1) . mb_substr($staff->last_name, 0, 1)) ?: '?';
          @endphp
          <a class="s-card" href="{{ route('admin.staffs', ['open' => $staff->id]) }}">
            <span class="s-avatar tone-{{ $staff->id % 6 + 1 }}">
              @if ($staff->profile_picture)
                <img src="{{ asset($staff->profile_picture) }}" alt="{{ $fullName }}" loading="lazy">
              @else
                {{ $initials }}
              @endif
            </span>
            <div class="s-text">
              <div class="s-name-row">
                <span class="s-name">{!! $hl($fullName) !!}</span>
                <span class="s-code">{!! $hl($code) !!}</span>
              </div>
              <div class="s-line">{!! $hl($staff->email) !!}</div>
              @if ($staff->contact_number) <div class="s-line">{!! $hl($staff->contact_number) !!}</div> @endif
            </div>
            <span class="pill {{ $staff->is_active ? 'ok' : 'danger' }}">{{ $staff->is_active ? 'Active' : 'Inactive' }}</span>
          </a>
        @endforeach
      </div>

      @if ($type === 'staffs' && $staffs->hasPages())
        <div class="sr-pager">
          @if ($staffs->onFirstPage()) <span class="btn">Previous</span> @else <a href="{{ $staffs->previousPageUrl() }}">Previous</a> @endif
          <span>Page {{ $staffs->currentPage() }} of {{ $staffs->lastPage() }}</span>
          @if ($staffs->hasMorePages()) <a href="{{ $staffs->nextPageUrl() }}">Next</a> @else <span class="btn">Next</span> @endif
        </div>
      @endif
    </section>
    @endif

  @endif

</main>

<script>
// ── Theme ──────────────────────────────────────────────
const html = document.documentElement;
const themeBtn = document.getElementById('themeBtn');
const themeIco = document.getElementById('themeIco');
const MOON = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>`;
const SUN  = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;

const setTheme = dark => {
  html.setAttribute('data-theme', dark ? 'dark' : 'light');
  themeIco.innerHTML = dark ? SUN : MOON;
  localStorage.setItem('theme', dark ? 'dark' : 'light');
};
const saved = localStorage.getItem('theme');
setTheme(saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches);
themeBtn.addEventListener('click', () => setTheme(html.getAttribute('data-theme') !== 'dark'));

// ── Sidebar (mobile) ────────────────────────────────────
const sidebar   = document.getElementById('sidebar');
const overlay   = document.getElementById('overlay');
const hamburger = document.getElementById('hamburger');

hamburger.addEventListener('click', () => {
  sidebar.classList.toggle('open');
  overlay.classList.toggle('on');
});
overlay.addEventListener('click', () => {
  sidebar.classList.remove('open');
  overlay.classList.remove('on');
});
</script>
<script>
/* ══════════════════════════════════════════════════════
   HEADER SEARCH — products & staffs
   • Typing shows live suggestions (AdminSearchController@suggest).
   • Clicking a suggestion opens that product's / staff member's
     information modal on its own page (?open={id}).
   • Pressing Enter, or "See all results", goes to the results page.
   ══════════════════════════════════════════════════════ */
(function () {
  const form  = document.getElementById('adminSearch');
  if (!form) return;
  const input = document.getElementById('adminSearchInput');
  const panel = document.getElementById('adminSearchPanel');
  const SUGGEST_URL = "{{ route('admin.search.suggest') }}";

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

  const staffRow = (s, q) => `
    <a class="sp-item" role="option" href="${esc(s.url)}">
      <span class="sp-avatar sp-tone-${esc(s.tone)}">${s.image
        ? `<img src="${esc(s.image)}" alt="">`
        : esc(s.initials)}</span>
      <div class="sp-text">
        <div class="sp-name">${hl(s.name, q)}</div>
        <div class="sp-meta">${hl(s.meta, q)}</div>
      </div>
    </a>`;

  function render(data) {
    const q = data.query;
    if (!data.products.length && !data.staffs.length) {
      panel.innerHTML = `<div class="sp-state">No products or staffs match “${esc(q)}”.</div>`;
    } else {
      panel.innerHTML =
        (data.products.length ? `<div class="sp-label">Products</div>${data.products.map(p => productRow(p, q)).join('')}` : '') +
        (data.staffs.length   ? `<div class="sp-label">Staffs</div>${data.staffs.map(s => staffRow(s, q)).join('')}` : '') +
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
