<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GG Pharmacy — Dashboard</title>
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
  padding: 18px 16px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
  text-decoration: none;
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

/* ── MAIN CONTENT ── */
.main {
  margin-left: var(--sidebar-w);
  margin-top: var(--header-h);
  min-height: calc(100vh - var(--header-h));
  padding: 24px;
  transition: margin-left .3s;
}

/* ── STAT CARDS ── */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 24px;
}

.stat-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 18px 20px;
  box-shadow: var(--shadow-sm);
  transition: background .3s, box-shadow .2s;
  cursor: default;
}
.stat-card:hover { box-shadow: var(--shadow-md); }

.stat-top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 14px;
}
.stat-icon {
  width: 42px; height: 42px;
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
}
.stat-icon svg { width: 20px; height: 20px; }
.stat-icon.red   { background: var(--red-light);    color: var(--red); }
.stat-icon.green { background: var(--green-light);  color: var(--green); }
.stat-icon.orange{ background: var(--orange-light); color: var(--orange); }
.stat-icon.blue  { background: var(--blue-light);   color: var(--blue); }

.stat-trend {
  font-size: 11px; font-weight: 600;
  padding: 3px 8px;
  border-radius: 20px;
  display: flex; align-items: center; gap: 3px;
}
.stat-trend.up   { background: var(--green-light);  color: var(--green); }
.stat-trend.down { background: var(--red-light);    color: var(--red); }

.stat-value {
  font-size: 26px;
  font-weight: 800;
  color: var(--text);
  line-height: 1;
  margin-bottom: 4px;
}
.stat-label {
  font-size: 13px;
  color: var(--muted);
}

/* ── CONTENT GRID ── */
.content-grid {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 20px;
  margin-bottom: 24px;
}

/* ── CARD ── */
.card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 10px;
  box-shadow: var(--shadow-sm);
  transition: background .3s;
  overflow: hidden;
}

.card-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px;
  border-bottom: 1px solid var(--border);
}
.card-head h3 {
  font-size: 15px;
  font-weight: 700;
  color: var(--text);
}
.card-head-right { display: flex; align-items: center; gap: 8px; }

.btn-sm {
  padding: 5px 12px;
  border-radius: 6px;
  font-size: 12px; font-weight: 600;
  font-family: 'Inter', sans-serif;
  cursor: pointer;
  border: 1.5px solid var(--border);
  background: none;
  color: var(--muted);
  transition: border-color .2s, color .2s, background .2s;
}
.btn-sm:hover { border-color: var(--red); color: var(--red); }
.btn-sm.primary {
  background: var(--red); border-color: var(--red); color: #fff;
}
.btn-sm.primary:hover { background: var(--red-h); border-color: var(--red-h); }

/* ── TABLE ── */
.table-wrap { overflow-x: auto; }
table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
}
thead th {
  padding: 10px 16px;
  text-align: left;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--subtle);
  background: var(--surface2);
  border-bottom: 1px solid var(--border);
  white-space: nowrap;
}
tbody tr { transition: background .1s; }
tbody tr:hover { background: var(--bg); }
tbody td {
  padding: 12px 16px;
  border-bottom: 1px solid var(--border);
  color: var(--text);
  vertical-align: middle;
}
tbody tr:last-child td { border-bottom: none; }

.med-name { font-weight: 600; }
.med-category {
  font-size: 11px;
  color: var(--muted);
  margin-top: 1px;
}

.stock-bar-wrap { display: flex; align-items: center; gap: 8px; min-width: 100px; }
.stock-bar-bg {
  flex: 1; height: 5px;
  background: var(--border);
  border-radius: 3px;
  overflow: hidden;
}
.stock-bar-fill { height: 100%; border-radius: 3px; }
.stock-count { font-size: 12px; font-weight: 600; color: var(--text); width: 28px; text-align: right; }

.pill {
  display: inline-flex; align-items: center; gap: 4px;
  font-size: 11px; font-weight: 600;
  padding: 3px 8px;
  border-radius: 20px;
}
.pill.ok      { background: var(--green-light); color: var(--green); }
.pill.warning { background: var(--orange-light); color: var(--orange); }
.pill.danger  { background: var(--red-light);   color: var(--red); }
.pill.blue    { background: var(--blue-light);  color: var(--blue); }
.pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

/* ── ALERT LIST ── */
.alert-list { padding: 8px 0; }
.alert-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 20px;
  border-bottom: 1px solid var(--border);
  transition: background .1s;
  cursor: default;
}
.alert-item:last-child { border-bottom: none; }
.alert-item:hover { background: var(--bg); }
.alert-dot {
  width: 8px; height: 8px;
  border-radius: 50%;
  flex-shrink: 0;
}
.alert-dot.red    { background: var(--red); }
.alert-dot.orange { background: var(--orange); }
.alert-info { flex: 1; min-width: 0; }
.alert-name { font-size: 13px; font-weight: 600; color: var(--text); }
.alert-detail { font-size: 11px; color: var(--muted); margin-top: 1px; }
.alert-qty {
  font-size: 13px; font-weight: 700;
  color: var(--red);
  flex-shrink: 0;
}

/* ── SALES CHART (CSS bars) ── */
.chart-wrap { padding: 20px; }
.chart-bars {
  display: flex;
  align-items: flex-end;
  gap: 6px;
  height: 120px;
  padding-bottom: 24px;
  position: relative;
  border-bottom: 2px solid var(--border);
}
.chart-bar-col {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
  height: 100%;
  justify-content: flex-end;
  position: relative;
}
.chart-bar {
  width: 100%;
  max-width: 36px;
  border-radius: 4px 4px 0 0;
  cursor: pointer;
  transition: opacity .2s;
  background: var(--red);
  opacity: 0.7;
  position: relative;
}
.chart-bar.today { opacity: 1; }
.chart-bar:hover { opacity: 1; }
.chart-bar-label {
  position: absolute;
  bottom: -22px;
  font-size: 10px;
  color: var(--subtle);
  font-weight: 500;
  white-space: nowrap;
}
.chart-bar-col.today .chart-bar-label { color: var(--red); font-weight: 700; }
.chart-legend {
  display: flex;
  gap: 16px;
  padding: 12px 20px;
  border-top: 1px solid var(--border);
}
.legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--muted); }
.legend-dot { width: 8px; height: 8px; border-radius: 50%; }

/* ── RECENT TRANSACTIONS ── */
.tx-list { padding: 4px 0; }
.tx-item {
  display: flex; align-items: center; gap: 12px;
  padding: 11px 20px;
  border-bottom: 1px solid var(--border);
  transition: background .1s;
}
.tx-item:last-child { border-bottom: none; }
.tx-item:hover { background: var(--bg); }
.tx-icon {
  width: 36px; height: 36px;
  border-radius: 8px;
  background: var(--green-light);
  color: var(--green);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.tx-icon svg { width: 16px; height: 16px; }
.tx-icon.out { background: var(--red-light); color: var(--red); }
.tx-info { flex: 1; min-width: 0; }
.tx-name { font-size: 13px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.tx-time { font-size: 11px; color: var(--muted); margin-top: 1px; }
.tx-amount { font-size: 14px; font-weight: 700; color: var(--text); flex-shrink: 0; }
.tx-amount.plus  { color: var(--green); }
.tx-amount.minus { color: var(--red); }

/* ── QUICK ACTIONS ── */
.quick-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-bottom: 24px;
}
.quick-btn {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 14px 12px;
  display: flex; flex-direction: column; align-items: center; gap: 8px;
  cursor: pointer;
  font-size: 12px; font-weight: 600;
  color: var(--muted);
  text-align: center;
  transition: border-color .2s, color .2s, box-shadow .2s, background .2s;
  box-shadow: var(--shadow-sm);
}
.quick-btn:hover { border-color: var(--red); color: var(--red); box-shadow: var(--shadow-md); }
.quick-icon {
  width: 38px; height: 38px;
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
}
.quick-icon svg { width: 18px; height: 18px; }

/* ── BOTTOM GRID ── */
.bottom-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 24px;
}

/* ── EXPIRY TABLE ── */
.expiry-row td:first-child { font-weight: 600; }

/* ── TOAST ── */
.toast {
  position: fixed;
  bottom: 24px; right: 24px;
  background: var(--green);
  color: #fff;
  padding: 12px 18px;
  border-radius: 8px;
  font-size: 13px; font-weight: 600;
  box-shadow: 0 4px 16px rgba(0,0,0,.2);
  transform: translateY(20px);
  opacity: 0;
  transition: opacity .3s, transform .3s;
  pointer-events: none;
  z-index: 200;
  display: flex; align-items: center; gap: 8px;
}
.toast.on { opacity: 1; transform: translateY(0); }
.toast svg { width: 16px; height: 16px; }

/* ── MODAL ── */
.modal-bg {
  display: none;
  position: fixed; inset: 0;
  background: rgba(0,0,0,.5);
  z-index: 150;
  align-items: center;
  justify-content: center;
  padding: 24px;
}
.modal-bg.on { display: flex; }
.modal {
  background: var(--surface);
  border-radius: 12px;
  box-shadow: 0 8px 40px rgba(0,0,0,.2);
  width: 100%;
  max-width: 440px;
  animation: fadeUp .25s ease both;
}
.modal-head {
  padding: 18px 20px 16px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
}
.modal-head h3 { font-size: 16px; font-weight: 700; color: var(--text); }
.modal-close {
  background: none; border: none; cursor: pointer;
  color: var(--muted); padding: 4px; border-radius: 5px;
  transition: color .15s, background .15s;
}
.modal-close:hover { color: var(--text); background: var(--bg); }
.modal-body { padding: 20px; }
.field { margin-bottom: 14px; }
.field label {
  display: block;
  font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: .5px;
  color: var(--muted);
  margin-bottom: 6px;
}
.field input, .field select, .field textarea {
  width: 100%;
  padding: 0 12px;
  height: 42px;
  background: var(--input-bg);
  border: 1.5px solid var(--border);
  border-radius: 7px;
  font-family: 'Inter', sans-serif;
  font-size: 14px; color: var(--text);
  outline: none;
  transition: border-color .2s, background .3s;
}
.field input:focus, .field select:focus, .field textarea:focus { border-color: var(--red); }
.field textarea { height: 70px; padding: 10px 12px; resize: none; }
.row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.modal-foot {
  padding: 14px 20px;
  border-top: 1px solid var(--border);
  display: flex; justify-content: flex-end; gap: 8px;
}

.btn {
  padding: 9px 18px;
  border-radius: 7px;
  font-size: 14px; font-weight: 600;
  font-family: 'Inter', sans-serif;
  cursor: pointer;
  border: 1.5px solid var(--border);
  background: none; color: var(--muted);
  transition: all .2s;
}
.btn:hover { border-color: var(--red); color: var(--red); }
.btn.primary {
  background: var(--red); border-color: var(--red); color: #fff;
}
.btn.primary:hover { background: var(--red-h); border-color: var(--red-h); }

@keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }

/* ── RESPONSIVE ── */
@media (max-width: 1100px) {
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
  .content-grid { grid-template-columns: 1fr; }
  .bottom-grid { grid-template-columns: 1fr; }
  .quick-grid { grid-template-columns: repeat(4, 1fr); }
}

@media (max-width: 768px) {
  .sidebar { transform: translateX(calc(-1 * var(--sidebar-w))); }
  .sidebar.open { transform: translateX(0); }
  .header { left: 0; }
  .main { margin-left: 0; padding: 16px; }
  .hamburger { display: flex; }
  .header-search { display: none; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
  .quick-grid { grid-template-columns: repeat(2, 1fr); }
  .content-grid { grid-template-columns: 1fr; }
  .bottom-grid { grid-template-columns: 1fr; }
  .stat-value { font-size: 22px; }
}

@media (max-width: 480px) {
  .stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
  .stat-card { padding: 14px; }
  .quick-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

</head>
<body>
<div class="toast" id="toast">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="toastMsg">Done.</span>
</div>
<div class="overlay" id="overlay"></div>
<aside class="sidebar" id="sidebar">
  <a class="sidebar-logo" href="{{ route('admin.dashboard') }}">
    <div class="sidebar-logo-icon">
      <img src="{{ asset('logo2.png') }}" alt="GG Pharmacy">
    </div>
    <div class="sidebar-logo-text">
      <strong>GG Pharmacy</strong>
      <span>Admin Portal</span>
    </div>
  </a>

  <div class="sidebar-section-label">Main</div>
  <a class="nav-item active" href="{{ route('admin.dashboard') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
    Inventory<span class="nav-badge">3</span>
  </a>
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    Orders
  </a>
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
    Staffs
  </a>

  <div class="sidebar-section-label">Manage</div>
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    Stocks
  </a>
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
    Reports
  </a>
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    Expiry Tracking<span class="nav-badge">2</span>
  </a>
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    Pharmacy
  </a>
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
    Financial Records
  </a>
  <a class="nav-item" href="#">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    Settings
  </a>

  <a href = "#" style = "text-decoration: none;">
  <div class="sidebar-bottom">
    <form method="POST" action="{{ route('admin.logout') }}" style="width:100%;">
      @csrf
      <button type="submit" class="user-chip" style="width:100%;border:none;cursor:pointer;font:inherit;text-align:left;background:transparent;">
        <div class="user-avatar">
          @if(auth('admin')->user()->profile_picture)
            <img src="{{ asset(auth('admin')->user()->profile_picture) }}" alt="{{ auth('admin')->user()->first_name ?? 'Admin' }} {{ auth('admin')->user()->last_name ?? 'User' }}" />
          @else
            {{ strtoupper(substr(auth('admin')->user()->first_name ?? 'A', 0, 1)) }}{{ strtoupper(substr(auth('admin')->user()->last_name ?? 'D', 0, 1)) }}
          @endif
        </div>
        <div class="user-info">
          <div class="user-name">{{ auth('admin')->user()->first_name ?? 'Admin' }} {{ auth('admin')->user()->last_name ?? 'User' }}</div>
          <div class="user-role">System Administrator &middot; Logout</div>
        </div>
      </button>
    </form>
  </div>
</a>

</aside>

<header class="header">
  <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="header-title">Dashboard</div>
  <div class="header-search">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" placeholder="Search products, staffs…">
  </div>
  <div class="header-actions">
    <div style="position:relative;">
      <button class="icon-btn" id="notifBtn" title="Notifications">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <div class="dot"></div>
      </button>

      <div id="notifDropdown" style="
        display:none;
        position:absolute;
        top:calc(100% + 10px);
        right:0;
        width:340px;
        max-width:88vw;
        background:var(--card,#fff);
        border:1px solid var(--border,#e5e7eb);
        border-radius:12px;
        box-shadow:0 12px 32px rgba(0,0,0,.14);
        z-index:50;
        overflow:hidden;
      ">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid var(--border,#e5e7eb);background:var(--card,#fff);">
          <span style="font-size:13px;font-weight:700;color:var(--text,#111);">Notifications</span>
          <span style="font-size:11px;font-weight:600;color:#CC1F1F;background:rgba(204,31,31,.1);padding:2px 8px;border-radius:20px;">4 New</span>
        </div>

        <div style="max-height:360px;overflow-y:auto;background:var(--card,#fff);">

          <div style="display:flex;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border,#e5e7eb);cursor:pointer;" onmouseover="this.style.background='var(--hover,rgba(0,0,0,.03))'" onmouseout="this.style.background='transparent'">
            <div style="width:36px;height:36px;flex-shrink:0;border-radius:10px;background:rgba(45,122,45,.12);display:flex;align-items:center;justify-content:center;color:#2D7A2D;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div style="flex:1;min-width:0;">
              <div style="font-size:12.5px;font-weight:600;color:var(--text,#111);">New order received</div>
              <div style="font-size:11.5px;color:var(--muted,#666);margin-top:2px;">Order #1043 from Juan dela Cruz · 5 items</div>
              <div style="font-size:10.5px;color:var(--subtle,#999);margin-top:4px;">2 minutes ago</div>
            </div>
          </div>

          <div style="display:flex;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border,#e5e7eb);cursor:pointer;" onmouseover="this.style.background='var(--hover,rgba(0,0,0,.03))'" onmouseout="this.style.background='transparent'">
            <div style="width:36px;height:36px;flex-shrink:0;border-radius:10px;background:rgba(224,123,0,.12);display:flex;align-items:center;justify-content:center;color:#e07b00;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div style="flex:1;min-width:0;">
              <div style="font-size:12.5px;font-weight:600;color:var(--text,#111);">Stock expiry alert</div>
              <div style="font-size:11.5px;color:var(--muted,#666);margin-top:2px;">Amoxicillin 500mg expires in 5 days</div>
              <div style="font-size:10.5px;color:var(--subtle,#999);margin-top:4px;">1 hour ago</div>
            </div>
          </div>

          <div style="display:flex;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border,#e5e7eb);cursor:pointer;" onmouseover="this.style.background='var(--hover,rgba(0,0,0,.03))'" onmouseout="this.style.background='transparent'">
            <div style="width:36px;height:36px;flex-shrink:0;border-radius:10px;background:rgba(37,99,235,.12);display:flex;align-items:center;justify-content:center;color:#2563eb;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div style="flex:1;min-width:0;">
              <div style="font-size:12.5px;font-weight:600;color:var(--text,#111);">Payment received</div>
              <div style="font-size:11.5px;color:var(--muted,#666);margin-top:2px;">Maria Santos paid ₱620 for Sale #1041</div>
              <div style="font-size:10.5px;color:var(--subtle,#999);margin-top:4px;">3 hours ago</div>
            </div>
          </div>

          <div style="display:flex;gap:12px;padding:12px 16px;cursor:pointer;" onmouseover="this.style.background='var(--hover,rgba(0,0,0,.03))'" onmouseout="this.style.background='transparent'">
            <div style="width:36px;height:36px;flex-shrink:0;border-radius:10px;background:rgba(204,31,31,.12);display:flex;align-items:center;justify-content:center;color:#CC1F1F;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
            <div style="flex:1;min-width:0;">
              <div style="font-size:12.5px;font-weight:600;color:var(--text,#111);">Stock running out</div>
              <div style="font-size:11.5px;color:var(--muted,#666);margin-top:2px;">Paracetamol has only 8 units left</div>
              <div style="font-size:10.5px;color:var(--subtle,#999);margin-top:4px;">5 hours ago</div>
            </div>
          </div>

        </div>

        <div style="padding:10px 16px;text-align:center;border-top:1px solid var(--border,#e5e7eb);background:var(--card,#fff);">
          <a href="#" style="font-size:12px;font-weight:600;color:#CC1F1F;text-decoration:none;">View all notifications</a>
        </div>
      </div>
    </div>

    <button class="icon-btn" id="themeBtn" title="Toggle dark mode">
      <span id="themeIco"></span>
    </button>
  </div>
</header>
<main class="main">

<div style="margin-bottom:20px;">
  <div style="font-size:20px;font-weight:800;color:var(--text);">Welcome Back, {{ auth()->user()->first_name ?? 'Admin' }}</div>
  <div style="font-size:13px;color:var(--muted);margin-top:3px;">Here's what's happening at GG Pharmacy today.</div>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></div>
      <div class="stat-trend up">↑ 4%</div>
    </div>
    <div class="stat-value">1,248</div>
    <div class="stat-label">Total Medicines</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
      <div class="stat-trend up">↑ 12%</div>
    </div>
    <div class="stat-value">₱18,450</div>
    <div class="stat-label">Today's Sales</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
      <div class="stat-trend down">↑ 2</div>
    </div>
    <div class="stat-value">12</div>
    <div class="stat-label">Low Stock Items</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
      <div class="stat-trend up">↑ 7%</div>
    </div>
    <div class="stat-value">342</div>
    <div class="stat-label">Total Customers</div>
  </div>
</div>

<div class="content-grid">
  <div class="card">
    <div class="card-head">
      <h3>Weekly Sales Overview</h3>
      <div class="card-head-right">
        <button class="btn-sm">This Week</button>
        <button class="btn-sm">Month</button>
      </div>
    </div>
    <div class="chart-wrap">
      <div style="font-size:11px;color:var(--subtle);margin-bottom:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Sales (₱)</div>
      <!-- Line Chart SVG -->
      <div style="position:relative;">
        <svg id="lineChart" viewBox="0 0 560 160" preserveAspectRatio="none" style="width:100%;height:160px;display:block;overflow:visible;">
          <defs>
            <linearGradient id="lineGrad" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0%" stop-color="#e74c3c" stop-opacity="0.18"/>
              <stop offset="100%" stop-color="#e74c3c" stop-opacity="0"/>
            </linearGradient>
          </defs>
          <!-- Grid lines -->
          <line x1="0" y1="20"  x2="560" y2="20"  stroke="currentColor" stroke-opacity="0.07" stroke-width="1"/>
          <line x1="0" y1="60"  x2="560" y2="60"  stroke="currentColor" stroke-opacity="0.07" stroke-width="1"/>
          <line x1="0" y1="100" x2="560" y2="100" stroke="currentColor" stroke-opacity="0.07" stroke-width="1"/>
          <line x1="0" y1="140" x2="560" y2="140" stroke="currentColor" stroke-opacity="0.07" stroke-width="1"/>
          <!-- Area fill -->
          <path id="areaPath" fill="url(#lineGrad)"/>
          <!-- Line -->
          <path id="linePath" fill="none" stroke="#e74c3c" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/>
          <!-- Data points -->
          <g id="dotGroup"></g>
        </svg>
        <!-- Tooltip -->
        <div id="chartTooltip" style="
          display:none;
          position:absolute;
          background:var(--card,#fff);
          border:1px solid var(--border,#e5e7eb);
          border-radius:8px;
          padding:6px 12px;
          font-size:12px;
          font-weight:600;
          color:var(--text,#111);
          pointer-events:none;
          box-shadow:0 4px 16px rgba(0,0,0,.10);
          white-space:nowrap;
          z-index:10;
        "></div>
        <!-- Day labels -->
        <div id="dayLabels" style="display:flex;justify-content:space-between;padding:6px 0 0;"></div>
      </div>
    </div>
    <div class="chart-legend">
      <div class="legend-item"><div class="legend-dot" style="background:#e74c3c;"></div> Daily Sales</div>
      <div class="legend-item" style="margin-left:auto;font-weight:700;color:var(--text);">Total: ₱92,300</div>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <h3>Order Status</h3>
      <span style="font-size:11px;color:var(--subtle);font-weight:600;">This Month</span>
    </div>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:18px 20px 14px;">
      <!-- Donut Chart -->
      <div style="position:relative;width:170px;height:170px;flex-shrink:0;">
        <svg id="orderDonut" viewBox="0 0 170 170" style="width:170px;height:170px;display:block;transform:rotate(-90deg);">
          <circle id="donutBg"       cx="85" cy="85" r="64" fill="none" stroke="var(--border)" stroke-width="22"/>
          <circle id="donutCompleted" cx="85" cy="85" r="64" fill="none" stroke="#2D7A2D" stroke-width="22" stroke-linecap="butt"/>
          <circle id="donutPending"   cx="85" cy="85" r="64" fill="none" stroke="#e07b00" stroke-width="22" stroke-linecap="butt"/>
          <circle id="donutCancelled" cx="85" cy="85" r="64" fill="none" stroke="#CC1F1F" stroke-width="22" stroke-linecap="butt"/>
        </svg>
        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
          <div style="font-size:26px;font-weight:800;color:var(--text);line-height:1;" id="donutTotal">248</div>
          <div style="font-size:10px;color:var(--subtle);font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-top:2px;">Total</div>
        </div>
      </div>
      <!-- Legend -->
      <div style="display:flex;flex-direction:column;gap:8px;width:100%;margin-top:18px;">
        <div style="display:flex;align-items:center;justify-content:space-between;">
          <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:10px;height:10px;border-radius:50%;background:#2D7A2D;flex-shrink:0;"></div>
            <span style="font-size:12px;color:var(--muted);font-weight:500;">Completed</span>
          </div>
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="font-size:12px;font-weight:700;color:var(--text);">168</span>
            <span style="font-size:10px;color:var(--subtle);width:32px;text-align:right;">68%</span>
          </div>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;">
          <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:10px;height:10px;border-radius:50%;background:#e07b00;flex-shrink:0;"></div>
            <span style="font-size:12px;color:var(--muted);font-weight:500;">Pending</span>
          </div>
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="font-size:12px;font-weight:700;color:var(--text);">55</span>
            <span style="font-size:10px;color:var(--subtle);width:32px;text-align:right;">22%</span>
          </div>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;">
          <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:10px;height:10px;border-radius:50%;background:#CC1F1F;flex-shrink:0;"></div>
            <span style="font-size:12px;color:var(--muted);font-weight:500;">Cancelled</span>
          </div>
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="font-size:12px;font-weight:700;color:var(--text);">25</span>
            <span style="font-size:10px;color:var(--subtle);width:32px;text-align:right;">10%</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="bottom-grid">
  <div class="card">
    <div class="card-head"><h3>Revenue by Product</h3><span style="font-size:11px;color:var(--subtle);font-weight:600;">Top 5 · This Month</span></div>
    <div style="padding:20px 20px 10px;">
      <!-- Bar Chart -->
      <div style="position:relative;">
        <svg id="revenueBarChart" viewBox="0 0 560 200" preserveAspectRatio="none" style="width:100%;height:200px;display:block;overflow:visible;">
          <!-- Grid lines -->
          <line x1="0" y1="0"   x2="560" y2="0"   stroke="currentColor" stroke-opacity="0.06" stroke-width="1"/>
          <line x1="0" y1="50"  x2="560" y2="50"  stroke="currentColor" stroke-opacity="0.06" stroke-width="1"/>
          <line x1="0" y1="100" x2="560" y2="100" stroke="currentColor" stroke-opacity="0.06" stroke-width="1"/>
          <line x1="0" y1="150" x2="560" y2="150" stroke="currentColor" stroke-opacity="0.06" stroke-width="1"/>
          <line x1="0" y1="200" x2="560" y2="200" stroke="currentColor" stroke-opacity="0.08" stroke-width="1"/>
          <!-- Bars rendered by JS -->
          <g id="revBarGroup"></g>
        </svg>
        <!-- X labels -->
        <div id="revBarLabels" style="display:flex;justify-content:space-around;padding:6px 0 0;"></div>
        <!-- Tooltip -->
        <div id="revBarTooltip" style="
          display:none;position:absolute;
          background:var(--card,#fff);
          border:1px solid var(--border,#e5e7eb);
          border-radius:8px;padding:6px 12px;
          font-size:11px;font-weight:600;color:var(--text,#111);
          pointer-events:none;box-shadow:0 4px 16px rgba(0,0,0,.10);
          white-space:nowrap;z-index:10;
        "></div>
      </div>
      <!-- Y-axis labels -->
      <div style="display:flex;justify-content:flex-end;gap:16px;padding:4px 0 0;border-top:1px solid var(--border);">
        <div style="font-size:11px;color:var(--muted);font-weight:500;">Total Revenue</div>
        <div style="font-size:11px;font-weight:700;color:var(--text);">₱48,620</div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-head"><h3>Recent Transactions</h3><a href="sales.html" class="btn-sm">See All</a></div>
    <div class="tx-list">
      <div class="tx-item"><div class="tx-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div><div class="tx-info"><div class="tx-name">Sale #1042 — Maria Santos</div><div class="tx-time">Today, 9:14 AM · 3 items</div></div><div class="tx-amount plus">+₱245</div></div>
      <div class="tx-item"><div class="tx-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div><div class="tx-info"><div class="tx-name">Sale #1041 — Juan dela Cruz</div><div class="tx-time">Today, 8:55 AM · 5 items</div></div><div class="tx-amount plus">+₱620</div></div>
      <div class="tx-item"><div class="tx-icon out"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg></div><div class="tx-info"><div class="tx-name">Restock — Amoxicillin 500mg</div><div class="tx-time">Yesterday, 4:30 PM · 200 pcs</div></div><div class="tx-amount minus">-₱2,400</div></div>
      <div class="tx-item"><div class="tx-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div><div class="tx-info"><div class="tx-name">Sale #1040 — Ana Reyes</div><div class="tx-time">Yesterday, 2:12 PM · 2 items</div></div><div class="tx-amount plus">+₱88</div></div>
      <div class="tx-item"><div class="tx-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div><div class="tx-info"><div class="tx-name">Sale #1039 — Pedro Lim</div><div class="tx-time">Yesterday, 11:05 AM · 7 items</div></div><div class="tx-amount plus">+₱1,150</div></div>
    </div>
  </div>
</div>

<div style="text-align:center;font-size:12px;color:var(--subtle);padding:8px 0 4px;">
  GG Pharmacy Inventory System &nbsp;·&nbsp; Zone 2, Sogod, Southern Leyte &nbsp;·&nbsp; Admin Access Only
</div>

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
// ── Notifications Dropdown ──────────────────────────────────────────────
(function() {
  const notifBtn = document.getElementById('notifBtn');
  const notifDropdown = document.getElementById('notifDropdown');
  if (!notifBtn || !notifDropdown) return;

  notifBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    notifDropdown.style.display = notifDropdown.style.display === 'block' ? 'none' : 'block';
  });

  document.addEventListener('click', (e) => {
    if (notifDropdown.style.display === 'block' && !notifDropdown.contains(e.target) && e.target !== notifBtn) {
      notifDropdown.style.display = 'none';
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') notifDropdown.style.display = 'none';
  });
})();
</script>
<script>
// ── Line Chart ────────────────────────────────────────────────────────────────
const salesData = [
  { day: 'Mon', val: 11200 },
  { day: 'Tue', val: 14800 },
  { day: 'Wed', val: 9500  },
  { day: 'Thu', val: 17200 },
  { day: 'Fri', val: 13600 },
  { day: 'Sat', val: 7850, today: true },
  { day: 'Sun', val: 18100 },
];

const W = 560, H = 160;
const PAD_L = 0, PAD_R = 0, PAD_T = 15, PAD_B = 5;
const chartW = W - PAD_L - PAD_R;
const chartH = H - PAD_T - PAD_B;

const minVal = 0;
const maxVal = Math.max(...salesData.map(d => d.val)) * 1.1;

function xPos(i) {
  return PAD_L + (i / (salesData.length - 1)) * chartW;
}
function yPos(v) {
  return PAD_T + chartH - ((v - minVal) / (maxVal - minVal)) * chartH;
}

// Smooth curve via cubic bezier control points
function smoothPath(pts) {
  if (pts.length < 2) return '';
  let d = `M ${pts[0][0]} ${pts[0][1]}`;
  for (let i = 0; i < pts.length - 1; i++) {
    const x0 = pts[i][0], y0 = pts[i][1];
    const x1 = pts[i+1][0], y1 = pts[i+1][1];
    const cpx = (x0 + x1) / 2;
    d += ` C ${cpx} ${y0}, ${cpx} ${y1}, ${x1} ${y1}`;
  }
  return d;
}

const pts = salesData.map((d, i) => [xPos(i), yPos(d.val)]);

// Line path
const linePath = smoothPath(pts);
document.getElementById('linePath').setAttribute('d', linePath);

// Area path = line + close to bottom
const first = pts[0], last = pts[pts.length - 1];
const areaClose = ` L ${last[0]} ${H} L ${first[0]} ${H} Z`;
document.getElementById('areaPath').setAttribute('d', linePath + areaClose);

// Dots + hover zones
const dotGroup = document.getElementById('dotGroup');
const tooltip  = document.getElementById('chartTooltip');
const svg      = document.getElementById('lineChart');

salesData.forEach((d, i) => {
  const cx = xPos(i), cy = yPos(d.val);

  // Outer ring (today highlight)
  if (d.today) {
    const ring = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
    ring.setAttribute('cx', cx); ring.setAttribute('cy', cy); ring.setAttribute('r', 7);
    ring.setAttribute('fill', 'none'); ring.setAttribute('stroke', '#e74c3c');
    ring.setAttribute('stroke-width', '2'); ring.setAttribute('opacity', '0.3');
    dotGroup.appendChild(ring);
  }

  // Main dot
  const dot = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
  dot.setAttribute('cx', cx); dot.setAttribute('cy', cy);
  dot.setAttribute('r', d.today ? 5 : 4);
  dot.setAttribute('fill', d.today ? '#e74c3c' : '#fff');
  dot.setAttribute('stroke', '#e74c3c');
  dot.setAttribute('stroke-width', '2.5');
  dotGroup.appendChild(dot);

  // Invisible large hit area
  const hit = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
  hit.setAttribute('cx', cx); hit.setAttribute('cy', cy); hit.setAttribute('r', 18);
  hit.setAttribute('fill', 'transparent');
  hit.style.cursor = 'pointer';
  hit.addEventListener('mouseenter', (e) => {
    tooltip.style.display = 'block';
    tooltip.innerHTML = `<span style="color:var(--muted,#888);font-weight:500;">${d.day}</span>&nbsp; ₱${d.val.toLocaleString()}`;
    positionTooltip(cx, cy);
  });
  hit.addEventListener('mouseleave', () => { tooltip.style.display = 'none'; });
  dotGroup.appendChild(hit);
});

function positionTooltip(cx, cy) {
  const rect = svg.getBoundingClientRect();
  const svgW = rect.width, svgH = rect.height;
  const scaleX = svgW / W, scaleY = svgH / H;
  const px = cx * scaleX, py = cy * scaleY;
  const tw = tooltip.offsetWidth || 110, th = tooltip.offsetHeight || 36;
  let left = px - tw / 2;
  let top  = py - th - 10;
  if (left < 0) left = 4;
  if (left + tw > svgW) left = svgW - tw - 4;
  if (top < 0) top = py + 14;
  tooltip.style.left = left + 'px';
  tooltip.style.top  = top  + 'px';
}

// Day labels
const labelsEl = document.getElementById('dayLabels');
salesData.forEach(d => {
  const span = document.createElement('span');
  span.textContent = d.day;
  span.style.cssText = `font-size:11px;color:var(--subtle,#aaa);font-weight:${d.today ? '700' : '500'};${d.today ? 'color:#e74c3c;' : ''}`;
  labelsEl.appendChild(span);
});

// ── Order Status Donut Chart ─────────────────────────────
(function() {
  const r = 64;
  const circ = 2 * Math.PI * r; // ~402.12

  // Data: completed 68%, pending 22%, cancelled 10%
  const segments = [
    { id: 'donutCompleted', pct: 0.68, color: '#2D7A2D' },
    { id: 'donutPending',   pct: 0.22, color: '#e07b00' },
    { id: 'donutCancelled', pct: 0.10, color: '#CC1F1F' },
  ];

  // Small gap between segments
  const GAP_DEG = 2.5;
  const GAP_FRAC = GAP_DEG / 360;
  const totalGap = GAP_FRAC * segments.length;
  let offset = 0; // starts at 0 (top after -90deg rotate)

  segments.forEach(seg => {
    const el = document.getElementById(seg.id);
    const arc = (seg.pct - GAP_FRAC) * circ;
    const gap = GAP_FRAC * circ;
    el.style.strokeDasharray = `${arc} ${circ - arc}`;
    el.style.strokeDashoffset = -(offset * circ);
    offset += seg.pct;
  });
})();

// ── Revenue by Product Bar Chart ─────────────────────────
(function() {
  const products = [
    { name: 'Paracetamol',  short: 'Paracetamol', revenue: 18700 },
    { name: 'Amoxicillin',  short: 'Amoxicillin', revenue: 12400 },
    { name: 'Vitamin C',    short: 'Vitamin C',   revenue: 8320  },
    { name: 'Omeprazole',   short: 'Omeprazole',  revenue: 5800  },
    { name: 'Metformin',    short: 'Metformin',   revenue: 3400  },
  ];

  const SVG_W = 560, SVG_H = 200;
  const PAD_L = 8, PAD_R = 8, PAD_T = 8, PAD_B = 4;
  const chartW = SVG_W - PAD_L - PAD_R;
  const chartH = SVG_H - PAD_T - PAD_B;

  const maxRev = Math.max(...products.map(p => p.revenue)) * 1.08;
  const n = products.length;
  const colW = chartW / n;
  const barW = colW * 0.48;
  const barRadius = 4;
  const accentColor = '#CC1F1F';

  const group = document.getElementById('revBarGroup');
  const labelsDiv = document.getElementById('revBarLabels');
  const tooltip = document.getElementById('revBarTooltip');
  const svgEl = document.getElementById('revenueBarChart');

  products.forEach((p, i) => {
    const barH = ((p.revenue / maxRev) * chartH);
    const x = PAD_L + i * colW + (colW - barW) / 2;
    const y = PAD_T + chartH - barH;

    // Bar with rounded top
    const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
    rect.setAttribute('x', x);
    rect.setAttribute('y', y);
    rect.setAttribute('width', barW);
    rect.setAttribute('height', barH);
    rect.setAttribute('rx', barRadius);
    rect.setAttribute('ry', barRadius);
    rect.setAttribute('fill', accentColor);
    rect.setAttribute('opacity', '0.75');
    rect.style.cursor = 'pointer';
    rect.style.transition = 'opacity .15s';

    // Value label above bar
    const valLabel = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    valLabel.setAttribute('x', x + barW / 2);
    valLabel.setAttribute('y', y - 5);
    valLabel.setAttribute('text-anchor', 'middle');
    valLabel.setAttribute('font-size', '9');
    valLabel.setAttribute('font-weight', '700');
    valLabel.setAttribute('fill', 'currentColor');
    valLabel.setAttribute('opacity', '0.5');
    valLabel.textContent = '₱' + (p.revenue >= 1000 ? (p.revenue/1000).toFixed(1)+'k' : p.revenue);

    rect.addEventListener('mouseenter', (e) => {
      rect.setAttribute('opacity', '1');
      tooltip.style.display = 'block';
      tooltip.innerHTML = `<span style="color:var(--muted);font-weight:500;">${p.name}</span>&nbsp; ₱${p.revenue.toLocaleString()}`;
      const svgRect = svgEl.getBoundingClientRect();
      const scaleX = svgRect.width / SVG_W;
      const scaleY = svgRect.height / SVG_H;
      const px = (x + barW / 2) * scaleX;
      const py = y * scaleY;
      const tw = tooltip.offsetWidth || 130;
      const th = tooltip.offsetHeight || 34;
      let left = px - tw / 2;
      let top = py - th - 8;
      if (left < 0) left = 4;
      if (left + tw > svgRect.width) left = svgRect.width - tw - 4;
      if (top < 0) top = py + 14;
      tooltip.style.left = left + 'px';
      tooltip.style.top = top + 'px';
    });
    rect.addEventListener('mouseleave', () => {
      rect.setAttribute('opacity', '0.75');
      tooltip.style.display = 'none';
    });

    group.appendChild(rect);
    group.appendChild(valLabel);

    // X label
    const span = document.createElement('span');
    span.textContent = p.short;
    span.style.cssText = 'font-size:10px;color:var(--subtle);font-weight:500;text-align:center;flex:1;';
    labelsDiv.appendChild(span);
  });
})();
</script>

</body>
</html>
