<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $siteName }} — Reports</title>
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
<style>
  /* ── Page-specific styles (reports) ───────────────────────────── */
  .toolbar{
    display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:18px;
  }
  .toolbar .search-box{
    flex:1;min-width:220px;display:flex;align-items:center;gap:8px;
    background:var(--card);border:1px solid var(--border);border-radius:10px;
    padding:9px 14px;color:var(--muted);
  }
  .toolbar .search-box svg{width:16px;height:16px;flex-shrink:0;stroke:var(--subtle);}
  .toolbar .search-box input{
    border:none;outline:none;background:transparent;font-size:13px;color:var(--text);width:100%;font-family:inherit;
  }
  .toolbar select{
    border:1px solid var(--border);background:var(--card);color:var(--text);
    font-size:13px;font-weight:500;padding:9px 12px;border-radius:10px;font-family:inherit;cursor:pointer;
  }
  .btn-primary{
    display:inline-flex;align-items:center;gap:8px;background:#CC1F1F;color:#fff;border:none;
    padding:10px 18px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;
    font-family:inherit;white-space:nowrap;transition:background .15s;
  }
  .btn-primary:hover{background:#a91717;}
  .btn-primary svg{width:16px;height:16px;}

  .table-wrap{
    background:var(--card);border:1px solid var(--border);border-radius:14px;overflow:hidden;
  }
  table.reports-table{width:100%;border-collapse:collapse;font-size:13px;}
  table.reports-table thead th{
    text-align:left;padding:13px 16px;font-size:11px;font-weight:700;text-transform:uppercase;
    letter-spacing:.4px;color:var(--subtle);border-bottom:1px solid var(--border);background:var(--bg-soft, rgba(0,0,0,0.015));
    white-space:nowrap;
  }
  table.reports-table tbody td{
    padding:13px 16px;border-bottom:1px solid var(--border);color:var(--text);vertical-align:top;
  }
  table.reports-table tbody tr:last-child td{border-bottom:none;}
  table.reports-table tbody tr:hover{background:rgba(204,31,31,0.03);}
  .rid{font-weight:700;color:var(--subtle);}
  .rtitle{font-weight:700;color:var(--text);}
  .rdesc{color:var(--muted);max-width:280px;display:block;}
  .rremarks{color:var(--muted);}

  .type-pill{
    display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;
    font-size:11px;font-weight:700;white-space:nowrap;
  }
  .type-Sales{background:rgba(45,122,45,0.12);color:#2D7A2D;}
  .type-Inventory{background:rgba(224,123,0,0.12);color:#e07b00;}
  .type-Financial{background:rgba(31,111,204,0.12);color:#1f6fcc;}
  .type-Patient{background:rgba(140,60,204,0.12);color:#8c3ccc;}
  .type-Other{background:rgba(120,120,120,0.14);color:#666;}

  .status-badge{
    display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;
    font-size:11px;font-weight:700;white-space:nowrap;
  }
  .status-badge::before{content:'';width:6px;height:6px;border-radius:50%;}
  .status-Completed{background:rgba(45,122,45,0.12);color:#2D7A2D;}
  .status-Completed::before{background:#2D7A2D;}
  .status-Pending{background:rgba(224,123,0,0.12);color:#e07b00;}
  .status-Pending::before{background:#e07b00;}
  .status-Cancelled{background:rgba(204,31,31,0.12);color:#CC1F1F;}
  .status-Cancelled::before{background:#CC1F1F;}

  .row-actions{display:flex;gap:6px;white-space:nowrap;}
  .icon-action{
    width:30px;height:30px;border-radius:8px;border:1px solid var(--border);background:var(--card);
    display:inline-flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);transition:.15s;
  }
  .icon-action svg{width:14px;height:14px;}
  .icon-action:hover{background:rgba(204,31,31,0.08);color:#CC1F1F;border-color:rgba(204,31,31,0.3);}
  .icon-action.view:hover{background:rgba(31,111,204,0.08);color:#1f6fcc;border-color:rgba(31,111,204,0.3);}

  .empty-state{padding:60px 20px;text-align:center;color:var(--subtle);}
  .empty-state svg{width:44px;height:44px;margin-bottom:12px;opacity:.4;}
  .empty-state div{font-size:13px;font-weight:600;}

  /* Modal */
  .modal-backdrop{
    display:none;position:fixed;inset:0;background:rgba(15,15,15,0.45);z-index:100;
    align-items:center;justify-content:center;padding:20px;
  }
  .modal-backdrop.open{display:flex;}
  .modal-box{
    background:var(--card, #ffffff);border-radius:16px;width:100%;max-width:560px;max-height:90vh;overflow-y:auto;
    box-shadow:0 20px 60px rgba(0,0,0,0.25);
  }
  [data-theme="dark"] .modal-box{ background:var(--card, #1c1c1e); }
  .modal-head{
    display:flex;align-items:center;justify-content:space-between;padding:20px 24px;
    border-bottom:1px solid var(--border);
  }
  .modal-head h3{margin:0;font-size:16px;font-weight:800;color:var(--text);}
  .modal-close{
    width:30px;height:30px;border-radius:8px;border:none;background:transparent;color:var(--subtle);
    cursor:pointer;display:flex;align-items:center;justify-content:center;
  }
  .modal-close:hover{background:var(--border);}
  .modal-body{padding:22px 24px;display:flex;flex-direction:column;gap:16px;}
  .form-row{display:flex;gap:14px;flex-wrap:wrap;}
  .form-group{flex:1;min-width:200px;display:flex;flex-direction:column;gap:6px;}
  .form-group label{font-size:12px;font-weight:700;color:var(--muted);}
  .form-group input,.form-group select,.form-group textarea{
    border:1px solid var(--border);background:var(--input-bg, #f7f7f8);color:var(--text, #111);
    padding:10px 12px;border-radius:9px;font-size:13px;font-family:inherit;outline:none;
  }
  [data-theme="dark"] .form-group input,
  [data-theme="dark"] .form-group select,
  [data-theme="dark"] .form-group textarea{ background:var(--input-bg, #2a2a2d); }
  .form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:#CC1F1F;}
  .form-group textarea{resize:vertical;min-height:70px;}
  .modal-foot{
    display:flex;justify-content:flex-end;gap:10px;padding:16px 24px 22px;
  }
  .btn-ghost{
    padding:10px 18px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;
    background:transparent;border:1px solid var(--border);color:var(--muted);font-family:inherit;
  }
  .btn-ghost:hover{background:var(--border);}

  .results-count{font-size:12px;color:var(--subtle);margin:12px 2px 0;}

  /* ── Mobile responsiveness ────────────────────────────────────── */
  @media (max-width: 760px){
    .card[style*="padding:20px"]{padding:14px !important;}

    .toolbar{gap:10px;}
    .toolbar .search-box{min-width:0;width:100%;flex:1 1 100%;}
    .toolbar select{flex:1 1 auto;}
    .toolbar .btn-primary{flex:1 1 100%;justify-content:center;}

    /* Turn the table into a stacked card list on small screens */
    .table-wrap{border:none;background:transparent;overflow:visible;}
    table.reports-table thead{display:none;}
    table.reports-table, table.reports-table tbody, table.reports-table tr, table.reports-table td{
      display:block;width:100%;
    }
    table.reports-table tbody tr{
      background:var(--card);border:1px solid var(--border);border-radius:12px;
      margin-bottom:12px;padding:6px 4px;
    }
    table.reports-table tbody tr:hover{background:var(--card);}
    table.reports-table tbody td{
      border-bottom:none;padding:8px 12px;display:flex;align-items:flex-start;
      justify-content:space-between;gap:12px;text-align:right;
    }
    table.reports-table tbody td::before{
      content:attr(data-label);font-size:11px;font-weight:700;text-transform:uppercase;
      letter-spacing:.4px;color:var(--subtle);text-align:left;flex-shrink:0;padding-top:1px;
    }
    table.reports-table tbody td .rdesc{text-align:right;max-width:none;}
    table.reports-table tbody td .row-actions{justify-content:flex-end;width:100%;}
    table.reports-table tbody td[data-label="Actions"]{padding-top:10px;border-top:1px solid var(--border);margin-top:2px;}

    .results-count{text-align:center;}

    /* Modal */
    .modal-backdrop{padding:0;align-items:flex-end;}
    .modal-box{max-width:none;width:100%;max-height:92vh;border-radius:16px 16px 0 0;}
    .modal-head{padding:16px 18px;}
    .modal-body{padding:16px 18px;}
    .modal-foot{padding:12px 18px 18px;flex-direction:column-reverse;}
    .modal-foot .btn-ghost,.modal-foot .btn-primary{width:100%;justify-content:center;}
    .form-row{gap:14px;}
    .form-group{min-width:100%;}
  }

  @media (max-width: 400px){
    table.reports-table tbody td{flex-direction:column;align-items:flex-start;text-align:left;gap:2px;}
    table.reports-table tbody td::before{padding-top:0;}
    table.reports-table tbody td .rdesc{text-align:left;}
    table.reports-table tbody td .row-actions{justify-content:flex-start;}
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
      <img src = "{{ asset(auth('admin')->user()->profile_picture) }}" class="user-avatar">
      @else
            {{ strtoupper(substr(auth('admin')->user()->first_name ?? 'A', 0, 1)) }}{{ strtoupper(substr(auth('admin')->user()->last_name ?? 'D', 0, 1)) }}
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
    <input type="text" placeholder="Search products, staffs…">
  </div>
  <div class="header-actions">
    <button class="icon-btn" title="Notifications">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      <div class="dot"></div>
    </button>
    <button class="icon-btn" id="themeBtn" title="Toggle dark mode">
      <span id="themeIco"></span>
    </button>
  </div>
</header>
<main class="main">

<div style="margin-bottom:20px;">
  <div style="font-size:20px;font-weight:800;color:var(--text);">Reports</div>
  <div style="font-size:13px;color:var(--muted);margin-top:3px;">Generate, track, and manage all system reports in one place.</div>
</div>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
    </div>
    <div class="stat-value" id="statTotal">0</div>
    <div class="stat-label">Total Reports</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
    </div>
    <div class="stat-value" id="statCompleted">0</div>
    <div class="stat-label">Completed</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
    </div>
    <div class="stat-value" id="statPending">0</div>
    <div class="stat-label">Pending</div>
  </div>
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/></svg></div>
    </div>
    <div class="stat-value" id="statThisMonth">0</div>
    <div class="stat-label">This Month</div>
  </div>
</div>

<div class="card" style="padding:20px;">
  <div class="toolbar">
    <div class="search-box">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="searchInput" placeholder="Search by title, ID, or remarks…">
    </div>
    <select id="filterType">
      <option value="">All Types</option>
      <option value="Sales">Sales</option>
      <option value="Inventory">Inventory</option>
      <option value="Financial">Financial</option>
      <option value="Patient">Patient</option>
      <option value="Other">Other</option>
    </select>
    <select id="filterStatus">
      <option value="">All Status</option>
      <option value="completed">Completed</option>
      <option value="pending">Pending</option>
      <option value="cancelled">Cancelled</option>
    </select>
    <button class="btn-primary" id="addReportBtn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Add Report
    </button>
  </div>

  <div class="table-wrap">
    <table class="reports-table">
      <thead>
        <tr>
          <th>Report ID</th>
          <th>Report Title</th>
          <th>Type</th>
          <th>Date</th>
          <th>Description</th>
          <th>Status</th>
          <th>Remarks</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="reportsTbody"></tbody>
    </table>
    <div class="empty-state" id="emptyState" style="display:none;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      <div>No reports match your search.</div>
    </div>
  </div>
  <div class="results-count" id="resultsCount"></div>
</div>

<div style="text-align:center;font-size:12px;color:var(--subtle);padding:20px 0 4px;">
  {{ $siteName }} Inventory System &nbsp;·&nbsp; Zone 2, Sogod, Southern Leyte &nbsp;·&nbsp; Admin Access Only
</div>

</main>

<!-- Add / Edit Report Modal -->
<div class="modal-backdrop" id="reportModal">
  <div class="modal-box">
    <div class="modal-head">
      <h3 id="modalTitle">Add Report</h3>
      <button class="modal-close" id="modalCloseBtn">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form id="reportForm">
      <div class="modal-body">
        <input type="hidden" id="reportId">
        <div class="form-row">
          <div class="form-group">
            <label for="reportTitle">Report Title</label>
            <input type="text" id="reportTitle" placeholder="e.g., Daily Sales Summary" required>
          </div>
          <div class="form-group">
            <label for="reportType">Report Type</label>
            <select id="reportType" required>
              <option value="Sales">Sales</option>
              <option value="Inventory">Inventory</option>
              <option value="Financial">Financial</option>
              <option value="Patient">Patient</option>
              <option value="Other">Other</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="reportDate">Report Date</label>
            <input type="date" id="reportDate" required>
          </div>
          <div class="form-group">
            <label for="reportStatus">Status</label>
            <select id="reportStatus" required>
              <option value="pending">Pending</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label for="reportDescription">Description</label>
          <textarea id="reportDescription" placeholder="Brief description of the report contents…" required></textarea>
        </div>
        <div class="form-group">
          <label for="reportRemarks">Remarks</label>
          <input type="text" id="reportRemarks" placeholder="e.g., Reviewed, Awaiting restock, None">
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn-ghost" id="cancelBtn">Cancel</button>
        <button type="submit" class="btn-primary">Save Report</button>
      </div>
    </form>
  </div>
</div>

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
// ── Reports Data Store ─────────────────────────────────────────
// Reports come straight from the `reports` database table (see
// AdminController@reports); no more hardcoded / localStorage data.
let reports = @json($reports);

const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const reportsEndpoint = "{{ route('admin.reports.store') }}";
const reportUpdateEndpointTemplate = "{{ route('admin.reports.update', ['report' => '__ID__']) }}";
const reportDestroyEndpointTemplate = "{{ route('admin.reports.destroy', ['report' => '__ID__']) }}";

function reportUpdateUrl(id) { return reportUpdateEndpointTemplate.replace('__ID__', id); }
function reportDestroyUrl(id) { return reportDestroyEndpointTemplate.replace('__ID__', id); }

async function apiRequest(url, method, body) {
  const response = await fetch(url, {
    method,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
    },
    body: body ? JSON.stringify(body) : undefined,
  });

  const data = await response.json().catch(() => ({}));

  if (!response.ok) {
    throw new Error(data.message || 'Something went wrong. Please try again.');
  }

  return data;
}

// ── Rendering ──────────────────────────────────────────────────
const tbody = document.getElementById('reportsTbody');
const emptyState = document.getElementById('emptyState');
const resultsCount = document.getElementById('resultsCount');
const searchInput = document.getElementById('searchInput');
const filterType = document.getElementById('filterType');
const filterStatus = document.getElementById('filterStatus');

function escapeHtml(str) {
  return String(str ?? '').replace(/[&<>"']/g, s => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
  }[s]));
}

function formatDate(dateStr) {
  if (!dateStr) return '—';
  const d = new Date(dateStr + 'T00:00:00');
  if (isNaN(d)) return dateStr;
  return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function statusLabel(status) {
  const s = String(status ?? '');
  return s.charAt(0).toUpperCase() + s.slice(1).toLowerCase();
}

function getFiltered() {
  const q = searchInput.value.trim().toLowerCase();
  const type = filterType.value;
  const status = filterStatus.value;
  return reports.filter(r => {
    if (type && r.type !== type) return false;
    if (status && r.status !== status) return false;
    if (q) {
      const hay = `${r.id} ${r.title} ${r.remarks}`.toLowerCase();
      if (!hay.includes(q)) return false;
    }
    return true;
  }).sort((a, b) => (b.date || '').localeCompare(a.date || '') || b.id - a.id);
}

function render() {
  const list = getFiltered();
  tbody.innerHTML = '';

  if (list.length === 0) {
    emptyState.style.display = 'block';
  } else {
    emptyState.style.display = 'none';
    list.forEach(r => {
      const typeClass = ['Sales','Inventory','Financial','Patient'].includes(r.type) ? r.type : 'Other';
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="rid" data-label="Report ID">#${escapeHtml(r.id)}</td>
        <td class="rtitle" data-label="Report Title">${escapeHtml(r.title)}</td>
        <td data-label="Type"><span class="type-pill type-${typeClass}">${escapeHtml(r.type)}</span></td>
        <td data-label="Date">${formatDate(r.date)}</td>
        <td data-label="Description"><span class="rdesc">${escapeHtml(r.description)}</span></td>
        <td data-label="Status"><span class="status-badge status-${escapeHtml(statusLabel(r.status))}">${escapeHtml(statusLabel(r.status))}</span></td>
        <td class="rremarks" data-label="Remarks">${escapeHtml(r.remarks) || '—'}</td>
        <td data-label="Actions">
          <div class="row-actions">
            <button class="icon-action edit" title="Edit" data-id="${r.id}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <button class="icon-action delete" title="Delete" data-id="${r.id}">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            </button>
          </div>
        </td>`;
      tbody.appendChild(tr);
    });
  }

  resultsCount.textContent = `Showing ${list.length} of ${reports.length} report${reports.length === 1 ? '' : 's'}`;
  updateStats();
}

function updateStats() {
  const total = reports.length;
  const completed = reports.filter(r => r.status === 'completed').length;
  const pending = reports.filter(r => r.status === 'pending').length;
  const now = new Date();
  const thisMonth = reports.filter(r => {
    const d = new Date((r.date || '') + 'T00:00:00');
    return !isNaN(d) && d.getMonth() === now.getMonth() && d.getFullYear() === now.getFullYear();
  }).length;

  document.getElementById('statTotal').textContent = total;
  document.getElementById('statCompleted').textContent = completed;
  document.getElementById('statPending').textContent = pending;
  document.getElementById('statThisMonth').textContent = thisMonth;
}

// ── Toast helper ───────────────────────────────────────────────
function showToast(msg) {
  const toast = document.getElementById('toast');
  const toastMsg = document.getElementById('toastMsg');
  if (!toast) return;
  toastMsg.textContent = msg;
  toast.classList.add('show');
  clearTimeout(showToast._t);
  showToast._t = setTimeout(() => toast.classList.remove('show'), 2600);
}

// ── Modal handling ─────────────────────────────────────────────
const modal = document.getElementById('reportModal');
const modalTitle = document.getElementById('modalTitle');
const reportForm = document.getElementById('reportForm');
const reportIdField = document.getElementById('reportId');
const reportTitleField = document.getElementById('reportTitle');
const reportTypeField = document.getElementById('reportType');
const reportDateField = document.getElementById('reportDate');
const reportStatusField = document.getElementById('reportStatus');
const reportDescriptionField = document.getElementById('reportDescription');
const reportRemarksField = document.getElementById('reportRemarks');

function openModal(editReport) {
  reportForm.reset();
  if (editReport) {
    modalTitle.textContent = `Edit Report #${editReport.id}`;
    reportIdField.value = editReport.id;
    reportTitleField.value = editReport.title;
    reportTypeField.value = editReport.type;
    reportDateField.value = editReport.date;
    reportStatusField.value = editReport.status;
    reportDescriptionField.value = editReport.description;
    reportRemarksField.value = editReport.remarks;
  } else {
    modalTitle.textContent = 'Add Report';
    reportIdField.value = '';
    reportDateField.value = new Date().toISOString().slice(0, 10);
  }
  modal.classList.add('open');
}

function closeModal() {
  modal.classList.remove('open');
}

document.getElementById('addReportBtn').addEventListener('click', () => openModal(null));
document.getElementById('modalCloseBtn').addEventListener('click', closeModal);
document.getElementById('cancelBtn').addEventListener('click', closeModal);
modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

reportForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  const id = reportIdField.value;
  const payload = {
    report_title: reportTitleField.value.trim(),
    report_type: reportTypeField.value,
    report_date: reportDateField.value,
    status: reportStatusField.value,
    description: reportDescriptionField.value.trim(),
    remarks: reportRemarksField.value.trim() || 'None',
  };

  const submitBtn = reportForm.querySelector('button[type="submit"]');
  submitBtn.disabled = true;

  try {
    let result;
    if (id) {
      result = await apiRequest(reportUpdateUrl(id), 'PUT', payload);
      const idx = reports.findIndex(r => String(r.id) === String(id));
      if (idx !== -1) reports[idx] = result.report;
    } else {
      result = await apiRequest(reportsEndpoint, 'POST', payload);
      reports.push(result.report);
    }

    showToast(result.message);
    render();
    closeModal();
  } catch (err) {
    showToast(err.message || 'Could not save the report.');
  } finally {
    submitBtn.disabled = false;
  }
});

// ── Row actions (edit / delete) ─────────────────────────────────
tbody.addEventListener('click', (e) => {
  const editBtn = e.target.closest('.edit');
  const delBtn = e.target.closest('.delete');

  if (editBtn) {
    const id = editBtn.dataset.id;
    const report = reports.find(r => String(r.id) === String(id));
    if (report) openModal(report);
  }

  if (delBtn) {
    const id = delBtn.dataset.id;
    const report = reports.find(r => String(r.id) === String(id));
    if (report && confirm(`Delete "${report.title}" (#${report.id})? This cannot be undone.`)) {
      apiRequest(reportDestroyUrl(id), 'DELETE')
        .then((result) => {
          reports = reports.filter(r => String(r.id) !== String(id));
          render();
          showToast(result.message || 'Report deleted.');
        })
        .catch((err) => showToast(err.message || 'Could not delete the report.'));
    }
  }
});

// ── Search / filter listeners ────────────────────────────────────
searchInput.addEventListener('input', render);
filterType.addEventListener('change', render);
filterStatus.addEventListener('change', render);

// ── Init ──────────────────────────────────────────────────────
render();
</script>
</body>
</html>