<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $siteName }} — Full-Width Banners</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
.toast.on, .toast.show { opacity: 1; transform: translateY(0); }
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
.notif-pill { font-size: 11px; font-weight: 600; color: #CC1F1F; background: rgba(204,31,31,.1); padding: 2px 8px; border-radius: 20px; }
.notif-link-btn { background: none; border: none; cursor: pointer; font: inherit; font-size: 11.5px; font-weight: 600; color: var(--muted, #666); padding: 0; }
.notif-link-btn:hover { color: #CC1F1F; }
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
.notif-unread { width: 7px; height: 7px; border-radius: 50%; background: #CC1F1F; flex-shrink: 0; }
.notif-msg { font-size: 11.5px; color: var(--muted, #666); margin-top: 2px; line-height: 1.4; }
.notif-time { font-size: 10.5px; color: var(--subtle, #999); margin-top: 4px; }
.notif-empty { padding: 28px 16px; text-align: center; font-size: 12.5px; color: var(--muted, #666); }
.notif-foot { padding: 10px 16px; text-align: center; border-top: 1px solid var(--border, #e5e7eb); }

</style>
<style>
  /* ---- Fallback tokens (only apply if shared.css doesn't already define them) ---- */
  :root{
    --wc-bg: var(--bg, #f4f6f8);
    --wc-card: var(--card, #ffffff);
    --wc-text: var(--text, #1a2027);
    --wc-subtle: var(--subtle, #6b7684);
    --wc-border: var(--border, #e4e8ec);
    --wc-primary: #d81f26;
    --wc-primary-dark: #ad1319;
    --wc-primary-light: #fbe6e7;
    --wc-success: #1a7a35;
    --wc-success-dark: #145c28;
    --wc-success-light: #e5f4e9;
    --wc-danger: #d81f26;
    --wc-danger-light: #fbe6e7;
    --wc-radius: 12px;
  }
  [data-theme="dark"]{
    --wc-bg: var(--bg, #10151a);
    --wc-card: var(--card, #171d24);
    --wc-text: var(--text, #eef1f4);
    --wc-subtle: var(--subtle, #8b96a3);
    --wc-border: var(--border, #262e37);
    --wc-primary-light: #3a1417;
    --wc-success-light: #123a1e;
  }

  .wc-wrap{ display:flex; flex-direction:column; gap:24px; }

  .wc-intro{ color:var(--wc-subtle); font-size:14px; max-width:720px; line-height:1.6; margin-top:4px; }

  /* Section jump nav */
  .wc-tabs{
    display:flex; gap:8px; flex-wrap:wrap;
    position:sticky; top:0; z-index:5;
    background:var(--wc-bg); padding:10px 0 14px;
  }
  .wc-tab{
    display:flex; align-items:center; gap:7px;
    padding:8px 14px; border-radius:999px; font-size:13px; font-weight:600;
    color:var(--wc-subtle); background:var(--wc-card); border:1px solid var(--wc-border);
    cursor:pointer; text-decoration:none; transition:all .15s ease; white-space:nowrap;
  }
  .wc-tab:hover{ color:var(--wc-primary); border-color:var(--wc-primary); }
  .wc-tab svg{ width:14px; height:14px; }
  .wc-tab.active{ color:#fff; background:var(--wc-primary); border-color:var(--wc-primary); }
  .wc-tab.active:hover{ color:#fff; }

  /* Sections stack in normal document order below the tabs, but only the
     active one is visible at a time */
  .wc-section{ display:none; }
  .wc-section.active{ display:block; }
  .wc-group-label[data-group]{ display:none; }
  .wc-group-label[data-group].active{ display:block; }

  .wc-group-label{
    font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase;
    color:var(--wc-subtle); margin:6px 2px 2px;
  }

  /* Cards */
  .wc-card{
    background:var(--wc-card); border:1px solid var(--wc-border); border-radius:var(--wc-radius);
    overflow:hidden; scroll-margin-top:70px;
  }
  .wc-card-head{
    display:flex; align-items:flex-start; justify-content:space-between; gap:16px;
    padding:18px 20px; border-bottom:1px solid var(--wc-border);
  }
  .wc-card-title{ display:flex; align-items:center; gap:10px; }
  .wc-card-icon{
    width:36px; height:36px; border-radius:10px; background:var(--wc-primary-light);
    color:var(--wc-primary-dark); display:flex; align-items:center; justify-content:center; flex-shrink:0;
  }
  .wc-card-icon svg{ width:18px; height:18px; }
  .wc-card-title h3{ font-size:15px; font-weight:800; color:var(--wc-text); margin:0; }
  .wc-card-title p{ font-size:12.5px; color:var(--wc-subtle); margin:2px 0 0; }
  .wc-badge-count{
    font-size:11px; font-weight:700; color:var(--wc-subtle); background:var(--wc-bg);
    border:1px solid var(--wc-border); padding:3px 9px; border-radius:999px;
  }

  /* Buttons */
  .btn{
    display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:700;
    border-radius:9px; padding:8px 14px; cursor:pointer; border:1px solid transparent;
    transition:all .15s ease; font-family:inherit;
  }
  .btn svg{ width:14px; height:14px; }
  .btn-primary{ background:var(--wc-primary); color:#fff; }
  .btn-primary:hover{ background:var(--wc-primary-dark); }
  .btn-outline{ background:transparent; color:var(--wc-text); border-color:var(--wc-border); }
  .btn-outline:hover{ border-color:var(--wc-primary); color:var(--wc-primary); }
  .btn-sm{ padding:6px 10px; font-size:12px; border-radius:8px; }
  .btn-icon{
    width:32px; height:32px; padding:0; justify-content:center; border-radius:8px;
    background:transparent; border:1px solid var(--wc-border); color:var(--wc-subtle);
  }
  .btn-icon:hover{ color:var(--wc-primary); border-color:var(--wc-primary); }
  .btn-icon.danger:hover{ color:var(--wc-danger); border-color:var(--wc-danger); }

  /* Table */
  .wc-table-scroll{ overflow-x:auto; }
  table.wc-table{ width:100%; border-collapse:collapse; font-size:13px; min-width:600px; }
  table.wc-table th{
    text-align:left; font-size:11px; font-weight:800; letter-spacing:.04em; text-transform:uppercase;
    color:var(--wc-subtle); padding:11px 20px; border-bottom:1px solid var(--wc-border); white-space:nowrap;
  }
  table.wc-table td{
    padding:11px 20px; border-bottom:1px solid var(--wc-border); color:var(--wc-text); vertical-align:middle;
  }
  table.wc-table tbody tr:last-child td{ border-bottom:none; }
  table.wc-table tbody tr:hover{ background:var(--wc-bg); }
  .wc-cell-actions{ display:flex; gap:6px; justify-content:flex-end; }
  .wc-empty{ text-align:center; padding:40px 20px; color:var(--wc-subtle); font-size:13px; }

  /* Thumbs */
  .wc-thumb{
    width:44px; height:44px; border-radius:9px; object-fit:cover; border:1px solid var(--wc-border);
    background:var(--wc-bg); flex-shrink:0;
  }
  .wc-thumb-fallback{
    width:44px; height:44px; border-radius:9px; border:1px dashed var(--wc-border);
    background:var(--wc-bg); display:flex; align-items:center; justify-content:center; color:var(--wc-subtle); flex-shrink:0;
  }
  .wc-thumb-fallback svg{ width:16px; height:16px; }
  .wc-name-cell{ display:flex; align-items:center; gap:10px; font-weight:600; }
  .wc-sub{ color:var(--wc-subtle); font-size:12px; font-weight:500; }

  /* Slider cards */
  .wc-slider-grid{
    display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr)); gap:16px; padding:18px 20px;
  }
  .wc-slide-card{
    border:1px solid var(--wc-border); border-radius:var(--wc-radius); overflow:hidden;
    background:var(--wc-bg); display:flex; flex-direction:column; transition:border-color .15s, box-shadow .15s;
  }
  .wc-slide-card:hover{ border-color:var(--wc-primary); box-shadow:0 2px 10px rgba(0,0,0,.06); }
  .wc-slide-card-media{
    position:relative; width:100%; aspect-ratio:2/1; background:var(--wc-card); overflow:hidden;
  }
  .wc-slide-card-media img{ width:100%; height:100%; object-fit:cover; display:block; }
  .wc-slide-card-media .wc-thumb-fallback{
    width:100%; height:100%; border-radius:0; border:none;
  }
  .wc-slide-card-order{
    position:absolute; top:8px; left:8px; font-size:11px; font-weight:700; color:#fff;
    background:rgba(0,0,0,.55); padding:2px 8px; border-radius:999px; letter-spacing:.2px;
  }
  .wc-slide-card-fullscreen{
    position:absolute; bottom:8px; right:8px; width:26px; height:26px; display:flex;
    align-items:center; justify-content:center; border:none; border-radius:6px; cursor:pointer;
    color:#fff; background:rgba(0,0,0,.55); font-size:12px; transition:background .15s, transform .15s;
  }
  .wc-slide-card-fullscreen:hover{ background:rgba(0,0,0,.8); transform:scale(1.06); }
  .wc-slide-card-body{ padding:12px 14px; display:flex; flex-direction:column; gap:2px; flex:1; }
  .wc-slide-card-title{ font-size:13px; font-weight:700; color:var(--wc-text); line-height:1.3; }
  .wc-slide-card-file{ font-size:11.5px; color:var(--wc-subtle); word-break:break-all; }
  .wc-slide-card-actions{
    display:flex; gap:8px; padding:0 14px 14px; margin-top:auto;
  }
  .wc-slide-card-actions .btn-icon{ flex:1; border-radius:8px; }
  .wc-slide-empty{
    grid-column:1/-1; text-align:center; padding:40px 20px; color:var(--wc-subtle); font-size:13px;
  }

  /* Full-width banner cards (335 x 370 images) */
  .wc-fwb-grid{
    display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:16px; padding:18px 20px;
  }
  .wc-fwb-card{
    border:1px solid var(--wc-border); border-radius:var(--wc-radius); overflow:hidden;
    background:var(--wc-bg); display:flex; flex-direction:column; transition:border-color .15s, box-shadow .15s;
  }
  .wc-fwb-card:hover{ border-color:var(--wc-primary); box-shadow:0 2px 10px rgba(0,0,0,.06); }
  .wc-fwb-card-media{
    position:relative; width:100%; aspect-ratio:335/370; background:var(--wc-card); overflow:hidden;
  }
  .wc-fwb-card-media img{ width:100%; height:100%; object-fit:cover; display:block; }
  .wc-fwb-card-media .wc-thumb-fallback{
    width:100%; height:100%; border-radius:0; border:none;
  }
  .wc-fwb-fullscreen-btn{
    position:absolute; right:8px; bottom:8px; width:26px; height:26px; border-radius:7px;
    background:rgba(0,0,0,.55); color:#fff; border:none; display:flex; align-items:center; justify-content:center;
    font-size:11px; cursor:pointer; transition:background .15s, transform .15s; padding:0;
  }
  .wc-fwb-fullscreen-btn:hover{ background:rgba(0,0,0,.8); transform:scale(1.06); }

  /* Fullscreen image lightbox */
  .wc-lightbox-overlay{
    position:fixed; inset:0; background:rgba(10,12,15,.9); display:none; align-items:center; justify-content:center;
    z-index:1000; padding:40px;
  }
  .wc-lightbox-overlay.open{ display:flex; }
  .wc-lightbox-overlay img{
    max-width:100%; max-height:100%; border-radius:8px; box-shadow:0 10px 40px rgba(0,0,0,.5);
  }
  .wc-lightbox-close{
    position:absolute; top:20px; right:24px; width:38px; height:38px; border-radius:50%;
    background:rgba(255,255,255,.12); color:#fff; border:none; font-size:16px; cursor:pointer;
    display:flex; align-items:center; justify-content:center; transition:background .15s;
  }
  .wc-lightbox-close:hover{ background:rgba(255,255,255,.25); }
  .wc-fwb-card-body{ padding:12px 14px; display:flex; flex-direction:column; gap:2px; }
  .wc-fwb-card-title{ font-size:13px; font-weight:700; color:var(--wc-text); line-height:1.3; }
  .wc-fwb-card-key{ font-size:11.5px; color:var(--wc-subtle); }
  .wc-fwb-card-actions{
    display:flex; gap:8px; padding:0 14px 14px; margin-top:auto;
  }
  .wc-fwb-card-actions .btn-icon{ flex:1; border-radius:8px; }
  .wc-fwb-empty{
    grid-column:1/-1; text-align:center; padding:40px 20px; color:var(--wc-subtle); font-size:13px;
  }

  /* Color swatch */
  .wc-swatch{ display:inline-flex; align-items:center; gap:7px; font-size:12.5px; color:var(--wc-subtle); }
  .wc-swatch i{ width:16px; height:16px; border-radius:5px; border:1px solid var(--wc-border); display:inline-block; }

  /* Toggle pill (read-only display in table) */
  .wc-pill{ font-size:11px; font-weight:700; padding:3px 9px; border-radius:999px; display:inline-block; }
  .wc-pill.on{ background:var(--wc-success-light); color:var(--wc-success-dark); }
  .wc-pill.off{ background:var(--wc-bg); color:var(--wc-subtle); border:1px solid var(--wc-border); }

  /* Settings form */
  .wc-settings-grid{ display:grid; grid-template-columns:repeat(2, 1fr); gap:16px 22px; padding:20px; }
  @media (max-width: 760px){ .wc-settings-grid{ grid-template-columns:1fr; } }
  .wc-field{ display:flex; flex-direction:column; gap:6px; }
  .wc-field.span-2{ grid-column: span 2; }
  @media (max-width: 760px){ .wc-field.span-2{ grid-column: span 1; } }
  .wc-field label{ font-size:12px; font-weight:700; color:var(--wc-text); }
  .wc-field .hint{ font-size:11.5px; color:var(--wc-subtle); font-weight:500; margin-top:-3px; }
  .wc-input, .wc-textarea, .wc-select{
    font-family:inherit; font-size:13px; padding:9px 12px; border-radius:9px;
    border:1px solid var(--wc-border); background:var(--wc-bg); color:var(--wc-text);
    outline:none; transition:border-color .15s ease; width:100%;
  }
  .wc-input:focus, .wc-textarea:focus, .wc-select:focus{ border-color:var(--wc-primary); background:var(--wc-card); }
  .wc-textarea{ resize:vertical; min-height:56px; font-family:inherit; }
  .wc-card-foot{ display:flex; justify-content:flex-end; gap:10px; padding:16px 20px; border-top:1px solid var(--wc-border); background:var(--wc-bg); }

  /* File upload control */
  .wc-upload{ display:flex; align-items:center; gap:10px; }
  .wc-upload-box{
    width:56px; height:56px; border-radius:10px; border:1.5px dashed var(--wc-border);
    display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0; background:var(--wc-bg);
  }
  .wc-upload-box img{ width:100%; height:100%; object-fit:cover; }
  .wc-upload-box svg{ width:18px; height:18px; color:var(--wc-subtle); }
  .wc-upload-actions{ display:flex; flex-direction:column; gap:4px; }
  .wc-upload-filename{ font-size:11.5px; color:var(--wc-subtle); max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
  input[type="file"].wc-file-input{ display:none; }

  /* Color picker control */
  .wc-color-control{ display:flex; align-items:center; gap:10px; }
  .wc-color-control input[type="color"]{
    width:40px; height:36px; padding:2px; border-radius:8px; border:1px solid var(--wc-border);
    background:var(--wc-bg); cursor:pointer;
  }

  /* Switch */
  .wc-switch-row{ display:flex; align-items:center; justify-content:space-between; gap:10px; }
  .wc-switch{ position:relative; width:38px; height:22px; flex-shrink:0; }
  .wc-switch input{ opacity:0; width:0; height:0; }
  .wc-switch .track{
    position:absolute; inset:0; background:var(--wc-border); border-radius:999px; cursor:pointer; transition:.15s;
  }
  .wc-switch .track::before{
    content:""; position:absolute; width:16px; height:16px; left:3px; top:3px; background:#fff; border-radius:50%; transition:.15s;
    box-shadow:0 1px 2px rgba(0,0,0,.25);
  }
  .wc-switch input:checked + .track{ background:var(--wc-success); }
  .wc-switch input:checked + .track::before{ transform:translateX(16px); }

  /* Modal */
  .wc-modal-overlay{
    position:fixed; inset:0; background:rgba(15,20,25,.55); display:none; align-items:center; justify-content:center;
    z-index:100; padding:20px;
  }
  .wc-modal-overlay.open{ display:flex; }
  .wc-modal{
    background:var(--wc-card); border-radius:16px; width:100%; max-width:520px; max-height:88vh; overflow:auto;
    border:1px solid var(--wc-border); box-shadow:0 20px 60px rgba(0,0,0,.3);
  }
  .wc-modal-head{
    display:flex; align-items:center; justify-content:space-between; padding:18px 22px; border-bottom:1px solid var(--wc-border);
    position:sticky; top:0; background:var(--wc-card); z-index:2;
  }
  .wc-modal-head h3{ font-size:15px; font-weight:800; color:var(--wc-text); margin:0; }
  .wc-modal-close{
    width:30px; height:30px; border-radius:8px; border:1px solid var(--wc-border); background:transparent;
    display:flex; align-items:center; justify-content:center; cursor:pointer; color:var(--wc-subtle);
  }
  .wc-modal-close:hover{ color:var(--wc-danger); border-color:var(--wc-danger); }
  .wc-modal-body{ padding:20px 22px; display:flex; flex-direction:column; gap:16px; }
  .wc-modal-foot{
    display:flex; justify-content:flex-end; gap:10px; padding:16px 22px; border-top:1px solid var(--wc-border);
    position:sticky; bottom:0; background:var(--wc-card);
  }

  /* Confirm dialog reuse */
  .wc-confirm-text{ font-size:13.5px; color:var(--wc-subtle); line-height:1.6; }
  .wc-confirm-name{ color:var(--wc-text); font-weight:700; }
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
  <a class="nav-item" href="{{ route('admin.reports') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
    Reports
  </a>
  <a class="nav-item" href="{{ route('admin.expiry') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    Expiry Tracking<span class="nav-badge">2</span>
  </a>

  <a class="nav-item active" href="{{ route('admin.pharmacy') }}">
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
  <div class="header-title">Pharmacy</div>
  <form class="header-search" id="adminSearch" action="{{ route('admin.search') }}" method="GET" role="search" autocomplete="off">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" name="q" id="adminSearchInput" placeholder="Search products, staffs…" value="{{ $q ?? '' }}" maxlength="100" aria-label="Search products and staffs" aria-controls="adminSearchPanel" aria-expanded="false">
    <div class="search-panel" id="adminSearchPanel" role="listbox" hidden></div>
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
          <button type="button" class="notif-link-btn" id="notifMore" style="color:#CC1F1F;font-size:12px;">View all notifications</button>
        </div>
      </div>
    </div>

    <button class="icon-btn" id="themeBtn" title="Toggle dark mode">
      <span id="themeIco"></span>
    </button>
  </div>
</header>
<main class="main">

<div style="margin-bottom:4px;">
  <div style="font-size:20px;font-weight:800;color:var(--text);">Website Customization</div>
</div>
<p class="wc-intro">Control what shoppers see on the storefront — site details, homepage banners, section headings, and the product catalog's brands, categories and sliders. Changes to Settings, Banners, Sections and the Promo Banner update existing content; Brands, Categories and Sliders can be freely added, edited or removed.</p>

<div class="wc-wrap">
  <nav class="wc-tabs">
    <a class="wc-tab" href="{{ route('admin.pharmacy') }}"><i class="fas fa-store"></i> Settings</a>
    <a class="wc-tab active" href="{{ route('admin.pharmacy_full_width_banners') }}"><i class="fas fa-image"></i> FW Banners</a>
    <a class="wc-tab" href="{{ route('admin.pharmacy_sections') }}"><i class="fas fa-layer-group"></i> Sections</a>
    <a class="wc-tab" href="{{ route('admin.pharmacy_promo_banners') }}"><i class="fas fa-bullhorn"></i> Promo Banner</a>
    <a class="wc-tab" href="{{ route('admin.pharmacy_sliders') }}"><i class="fas fa-images"></i> Sliders</a>
    <a class="wc-tab" href="{{ route('admin.pharmacy_categories') }}"><i class="fas fa-th-large"></i> Categories</a>
    <a class="wc-tab" href="{{ route('admin.pharmacy_brands') }}"><i class="fas fa-tags"></i> Brands</a>
    <a class="wc-tab" href="{{ route('admin.pharmacy_payment_accounts') }}"><i class="fas fa-wallet"></i>Accounts</a>
  </nav>
  <div class="wc-group-label active" data-group="storefront">Storefront content · Edit only</div>
  <section class="wc-card wc-section active" id="sec-fwb">
    <div class="wc-card-head">
      <div class="wc-card-title">
        <div class="wc-card-icon"><i class="fas fa-image"></i></div>
        <div>
          <h3>Full-Width Banners</h3>
          <p>Wide promotional banners shown between homepage sections</p>
        </div>
      </div>
      <div style="display:flex; align-items:center; gap:10px;">
        <span class="wc-badge-count" id="fwbCount"></span>
        <button class="btn btn-primary btn-sm" id="fwbAddBtn" type="button" onclick="openModal('fwb')"><i class="fas fa-plus"></i> Add Banner</button>
      </div>
    </div>
    <div class="wc-fwb-grid" id="fwbBody"></div>
  </section>

  <section class="wc-card wc-section active" id="sec-blogs">
    <div class="wc-card-head">
      <div class="wc-card-title">
        <div class="wc-card-icon"><i class="fas fa-newspaper"></i></div>
        <div>
          <h3>Blogs</h3>
          <p>Posts shown in the storefront blog section — add as many as you like</p>
        </div>
      </div>
      <div style="display:flex; align-items:center; gap:10px;">
        <span class="wc-badge-count" id="blogCount"></span>
        <button class="btn btn-primary btn-sm" type="button" onclick="openModal('blog')"><i class="fas fa-plus"></i> Add Blog</button>
      </div>
    </div>
    <div class="wc-table-scroll">
      <table class="wc-table">
        <thead>
          <tr>
            <th>Post</th>
            <th>Category</th>
            <th>Date</th>
            <th>Comments</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="blogBody"></tbody>
      </table>
    </div>
  </section>

  <section class="wc-card wc-section active" id="sec-card-images">
    <div class="wc-card-head">
      <div class="wc-card-title">
        <div class="wc-card-icon"><i class="fas fa-id-card"></i></div>
        <div>
          <h3>Card Images</h3>
          <p>Promotional tiles shown beside the product grids on the homepage — up to 2</p>
        </div>
      </div>
      <div style="display:flex; align-items:center; gap:10px;">
        <span class="wc-badge-count" id="cardCount"></span>
        <button class="btn btn-primary btn-sm" id="cardAddBtn" type="button" onclick="openModal('card')"><i class="fas fa-plus"></i> Add Card Image</button>
      </div>
    </div>
    <div class="wc-table-scroll">
      <table class="wc-table">
        <thead>
          <tr>
            <th>Image</th>
            <th>Slot</th>
            <th>Key</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="cardBody"></tbody>
      </table>
    </div>
  </section>
</div>
</main>
<!-- ============ IMAGE LIGHTBOX ============ -->
<div class="wc-lightbox-overlay" id="wcLightboxOverlay" onclick="if(event.target===this) closeFwbLightbox()">
  <button class="wc-lightbox-close" onclick="closeFwbLightbox()" type="button"><i class="fas fa-times"></i></button>
  <img id="wcLightboxImg" src="" alt="">
</div>
<!-- ============ MODAL ============ -->
<div class="wc-modal-overlay" id="wcModalOverlay">
  <div class="wc-modal">
    <div class="wc-modal-head">
      <h3 id="wcModalTitle">Edit</h3>
      <button class="wc-modal-close" onclick="closeModal()" type="button"><i class="fas fa-times"></i></button>
    </div>
    <div class="wc-modal-body" id="wcModalBody"></div>
    <div class="wc-modal-foot">
      <button class="btn btn-outline" onclick="closeModal()" type="button">Cancel</button>
      <button class="btn btn-primary" id="wcModalSaveBtn" type="button"><i class="fas fa-check"></i> Save</button>
    </div>
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
// ── Notifications (data comes from AdminNotificationsController) ─────────────
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
    list:    @json(route('admin.notifications')),
    readAll: @json(route('admin.notifications.readAll')),
    read:    @json(route('admin.notifications.read', ['notification' => '__ID__'])),
  };
  const CSRF = @json(csrf_token());
  const POLL_MS = 60000;
  const LIMIT = 15;

  let items = [];

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
    listEl.innerHTML = html;
  }

  async function load() {
    try {
      render(await api(`${URLS.list}?limit=${LIMIT}`));
    } catch (err) {
      console.error(err);
      if (dropdown.style.display === 'block') listEl.innerHTML = '<div class="notif-empty">Couldn\'t load notifications.</div>';
    }
  }

  // Click a notification → mark it read, then go to the related page.
  listEl.addEventListener('click', async (e) => {
    const row = e.target.closest('.notif-item');
    if (!row) return;
    const n = items.find(x => String(x.id) === row.dataset.id);
    if (!n) return;
    try { if (!n.is_read) await api(URLS.read.replace('__ID__', n.id), 'PUT'); } catch (err) { console.error(err); }
    if (n.link) window.location.href = n.link; else load();
  });

  readAllEl.addEventListener('click', async () => {
    try { await api(URLS.readAll, 'PUT'); } catch (err) { console.error(err); }
    load();
  });

  // "View all notifications" → Settings page, Notifications row (full list).
  moreEl.addEventListener('click', () => {
    window.location.href = @json(route('admin.notifications.view'));
  });

  // Open / close
  btn.addEventListener('click', (e) => {
    e.stopPropagation();
    const open = dropdown.style.display === 'block';
    dropdown.style.display = open ? 'none' : 'block';
    if (!open) load();
  });
  document.addEventListener('click', (e) => {
    if (dropdown.style.display === 'block' && !dropdown.contains(e.target) && !btn.contains(e.target)) {
      dropdown.style.display = 'none';
    }
  });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') dropdown.style.display = 'none'; });

  // Initial load + keep the badge fresh.
  load();
  setInterval(() => { if (!document.hidden) load(); }, POLL_MS);
})();
</script>
<script>
/* ============================================================================
   DATA — loaded from the `full_width_banners` database table
============================================================================ */
let fullWidthBanners = @json($fullWidthBanners);
/* ============================================================================
   HELPERS
============================================================================ */
function toast(msg){
  const t = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;
  t.classList.add('show');
  clearTimeout(window._wcToastTimer);
  window._wcToastTimer = setTimeout(()=> t.classList.remove('show'), 2600);
}

function esc(str){
  return String(str ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

function fileBase(path){
  if(!path) return '';
  return path.split('/').pop();
}

function thumbHtml(src, label){
  if(!src){
    return `<div class="wc-thumb-fallback" title="No image set"><i class="fas fa-image"></i></div>`;
  }
  return `<img class="wc-thumb" src="${esc(src)}" alt="${esc(label||'')}" onerror="this.outerHTML='<div class=&quot;wc-thumb-fallback&quot; title=&quot;${esc(fileBase(src))}&quot;><i class=&quot;fas fa-image&quot;></i></div>'">`;
}

function openFwbLightbox(src, alt){
  document.getElementById('wcLightboxImg').src = src;
  document.getElementById('wcLightboxImg').alt = alt || '';
  document.getElementById('wcLightboxOverlay').classList.add('open');
}
function closeFwbLightbox(){
  document.getElementById('wcLightboxOverlay').classList.remove('open');
  document.getElementById('wcLightboxImg').src = '';
}
document.addEventListener('keydown', (e) => {
  if(e.key === 'Escape') closeFwbLightbox();
});

function readFileAsDataURL(file){
  return new Promise((resolve, reject)=>{
    const reader = new FileReader();
    reader.onload = () => resolve(reader.result);
    reader.onerror = reject;
    reader.readAsDataURL(file);
  });
}
/* ============================================================================
   RENDER: FULL-WIDTH BANNERS (read/update)
============================================================================ */
const FWB_MAX = {{ \App\Models\FullWidthBanner::MAX_BANNERS }};

function renderFwb(){
  fullWidthBanners.sort((a, b) => a.sort_order - b.sort_order);
  document.getElementById('fwbCount').textContent = fullWidthBanners.length + ' / ' + FWB_MAX + ' banners';

  const addBtn = document.getElementById('fwbAddBtn');
  addBtn.disabled = fullWidthBanners.length >= FWB_MAX;
  addBtn.title = addBtn.disabled ? `Maximum of ${FWB_MAX} full-width banners reached` : '';
  addBtn.style.opacity = addBtn.disabled ? '.5' : '1';
  addBtn.style.cursor = addBtn.disabled ? 'not-allowed' : 'pointer';

  document.getElementById('fwbBody').innerHTML = fullWidthBanners.length ? fullWidthBanners.map(b => `
    <div class="wc-fwb-card">
      <div class="wc-fwb-card-media">
        ${thumbHtml(b.image, b.alt)}
        <span class="wc-slide-card-order">Slot ${esc(b.sort_order)}</span>
        ${b.image ? `<button class="wc-fwb-fullscreen-btn" title="View fullscreen" onclick="event.stopPropagation(); openFwbLightbox('${esc(b.image)}', '${esc(b.alt || '')}')"><i class="fas fa-expand"></i></button>` : ''}
      </div>
      <div class="wc-fwb-card-body">
        <div class="wc-fwb-card-title">${esc(b.alt || '—')}</div>
        <div class="wc-fwb-card-key">${esc(b.section_key)}</div>
      </div>
      <div class="wc-fwb-card-actions">
        <button class="btn-icon" title="Edit" onclick="openModal('fwb', ${b.id})"><i class="fas fa-pen"></i></button>
        <button class="btn-icon danger" title="Delete" onclick="deleteFwb(${b.id})"><i class="fas fa-trash"></i></button>
      </div>
    </div>
  `).join('') : `<div class="wc-fwb-empty">No full-width banners yet.</div>`;
}
renderFwb();

async function deleteFwb(id){
  const banner = fullWidthBanners.find(x => x.id === id);
  if(!banner) return;
  if(!confirm(`Delete the banner in slot ${banner.sort_order}? This cannot be undone.`)) return;

  try{
    const res = await fetch(`{{ url('/admin/pharmacy_full_width_banners') }}/${id}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: (() => { const fd = new FormData(); fd.append('_method', 'DELETE'); return fd; })(),
    });
    const data = await res.json();
    if(!res.ok){ toast(data.message || 'Could not delete banner.'); return; }

    fullWidthBanners = fullWidthBanners.filter(x => x.id !== id);
    renderFwb();
    toast(data.message || 'Full-width banner deleted successfully.');
  }catch(err){
    toast('Something went wrong. Please try again.');
  }
}
/* ============================================================================
   BLOGS — unlimited add / edit / delete (stored in the `blogs` table)
============================================================================ */
let blogPosts = @json($blogs);
const blogCategories = @json($blogCategories);
const BLOG_MONTHS = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];

function isHexColor(v){ return /^#[0-9a-fA-F]{6}$/.test(v || ''); }

function blogThumb(b){
  if(b.image) return thumbHtml(b.image, b.title);
  if(b.icon_class){
    const bg = esc(b.icon_bg || 'var(--wc-bg)');
    const fg = esc(b.icon_color || 'var(--wc-subtle)');
    return `<div class="wc-thumb-fallback" style="background:${bg};color:${fg};border-style:solid;"><i class="${esc(b.icon_class)}"></i></div>`;
  }
  return thumbHtml(null);
}

function renderBlogs(){
  document.getElementById('blogCount').textContent = blogPosts.length + (blogPosts.length === 1 ? ' blog' : ' blogs');

  document.getElementById('blogBody').innerHTML = blogPosts.length ? blogPosts.map(b => `
    <tr>
      <td>
        <div class="wc-name-cell">
          ${blogThumb(b)}
          <div style="min-width:0;">
            <div>${esc(b.title)}</div>
            <div class="wc-sub" style="max-width:340px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${esc(b.excerpt)}</div>
          </div>
        </div>
      </td>
      <td>${b.category_name ? esc(b.category_name) : '<span class="wc-sub">—</span>'}</td>
      <td style="white-space:nowrap;">${esc(b.month)} ${esc(b.day)}</td>
      <td>${esc(b.comment_count)}</td>
      <td><span class="wc-pill ${b.is_active ? 'on' : 'off'}">${b.is_active ? 'Active' : 'Hidden'}</span></td>
      <td>
        <div class="wc-cell-actions">
          <button class="btn-icon" title="Edit" onclick="openModal('blog', ${b.id})"><i class="fas fa-pen"></i></button>
          <button class="btn-icon danger" title="Delete" onclick="deleteBlog(${b.id})"><i class="fas fa-trash"></i></button>
        </div>
      </td>
    </tr>
  `).join('') : `<tr><td colspan="6" class="wc-empty">No blogs yet. Click “Add Blog” to create one.</td></tr>`;
}
renderBlogs();

async function deleteBlog(id){
  const blog = blogPosts.find(x => x.id === id);
  if(!blog) return;
  if(!confirm(`Delete the blog "${blog.title}"? This cannot be undone.`)) return;

  try{
    const fd = new FormData();
    fd.append('_method', 'DELETE');
    const res = await fetch(`{{ url('/admin/pharmacy_blogs') }}/${id}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: fd,
    });
    const data = await res.json();
    if(!res.ok){ toast(data.message || 'Could not delete blog.'); return; }

    blogPosts = blogPosts.filter(x => x.id !== id);
    renderBlogs();
    toast(data.message || 'Blog deleted successfully.');
  }catch(err){
    toast('Something went wrong. Please try again.');
  }
}
/* ============================================================================
   CARD IMAGES — max 2 (stored in the `card_images` database table)
============================================================================ */
let cardImages = @json($cardImages);
const CARD_MAX = {{ \App\Models\CardImage::MAX_IMAGES }};

function renderCards(){
  cardImages.sort((a, b) => a.sort_order - b.sort_order);
  document.getElementById('cardCount').textContent = cardImages.length + ' / ' + CARD_MAX + ' card images';

  const addBtn = document.getElementById('cardAddBtn');
  addBtn.disabled = cardImages.length >= CARD_MAX;
  addBtn.title = addBtn.disabled ? `Maximum of ${CARD_MAX} card images reached` : '';
  addBtn.style.opacity = addBtn.disabled ? '.5' : '1';
  addBtn.style.cursor = addBtn.disabled ? 'not-allowed' : 'pointer';

  document.getElementById('cardBody').innerHTML = cardImages.length ? cardImages.map(c => `
    <tr>
      <td>
        <div class="wc-name-cell">
          ${thumbHtml(c.image, c.alt)}
          <div style="min-width:0;">
            <div>${esc(c.alt || '—')}</div>
          </div>
        </div>
      </td>
      <td style="white-space:nowrap;">Slot ${esc(c.sort_order)}</td>
      <td><span class="wc-sub">${esc(c.section_key)}</span></td>
      <td><span class="wc-pill ${c.is_active ? 'on' : 'off'}">${c.is_active ? 'Active' : 'Hidden'}</span></td>
      <td>
        <div class="wc-cell-actions">
          ${c.image ? `<button class="btn-icon" title="View fullscreen" onclick="openFwbLightbox('${esc(c.image)}', '${esc(c.alt || '')}')"><i class="fas fa-expand"></i></button>` : ''}
          <button class="btn-icon" title="Edit" onclick="openModal('card', ${c.id})"><i class="fas fa-pen"></i></button>
          <button class="btn-icon danger" title="Delete" onclick="deleteCard(${c.id})"><i class="fas fa-trash"></i></button>
        </div>
      </td>
    </tr>
  `).join('') : `<tr><td colspan="5" class="wc-empty">No card images yet. Click “Add Card Image” to create one.</td></tr>`;
}
renderCards();

async function deleteCard(id){
  const card = cardImages.find(x => x.id === id);
  if(!card) return;
  if(!confirm(`Delete the card image in slot ${card.sort_order}? This cannot be undone.`)) return;

  try{
    const fd = new FormData();
    fd.append('_method', 'DELETE');
    const res = await fetch(`{{ url('/admin/pharmacy_card_images') }}/${id}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      },
      body: fd,
    });
    const data = await res.json();
    if(!res.ok){ toast(data.message || 'Could not delete card image.'); return; }

    cardImages = cardImages.filter(x => x.id !== id);
    renderCards();
    toast(data.message || 'Card image deleted successfully.');
  }catch(err){
    toast('Something went wrong. Please try again.');
  }
}
/* ============================================================================
   MODAL ENGINE (add / edit for all entity types)
============================================================================ */
const ENTITY = {
  fwb: { store: () => fullWidthBanners, titleField: 'sort_order', label:'Full-Width Banner', crud:true },
  card: { store: () => cardImages, titleField: 'sort_order', label:'Card Image', crud:true },
  blog: { store: () => blogPosts, titleField: 'title', label:'Blog', crud:true }
};

let modalState = { type:null, id:null, draft:null };

function openModal(type, id=null){
  const cfg = ENTITY[type];
  const store = cfg.store();
  const existing = id ? store.find(x => x.id === id) : null;
  modalState = { type, id, draft: existing ? JSON.parse(JSON.stringify(existing)) : defaultDraft(type), draftFiles: {} };

  document.getElementById('wcModalTitle').textContent = existing ? `Edit ${cfg.label}` : `Add ${cfg.label}`;
  document.getElementById('wcModalBody').innerHTML = buildForm(type, modalState.draft);
  document.getElementById('wcModalOverlay').classList.add('open');
}

function closeModal(){
  document.getElementById('wcModalOverlay').classList.remove('open');
  modalState = { type:null, id:null, draft:null };
}
function defaultDraft(type){
  const bases = {
    fwb: { sort_order: null, image: null, alt: '', is_active: true },
    card: { sort_order: null, image: null, alt: '', is_active: true },
    blog: { title: '', excerpt: '', image: null, icon_class: '', icon_color: '#CC1F1F', icon_bg: '#f5e0e0',
            day: new Date().getDate(), month: BLOG_MONTHS[new Date().getMonth()], comment_count: 0,
            category_id: null, is_active: true, remove_image: false },
  };
  return bases[type] || {};
}

/** Which sort_order slots (1..FWB_MAX) are already used by *other* banners. */
function takenFwbSlots(excludeId){
  return new Set(
    fullWidthBanners.filter(b => b.id !== excludeId).map(b => b.sort_order)
  );
}

/** Which sort_order slots (1..CARD_MAX) are already used by *other* card images. */
function takenCardSlots(excludeId){
  return new Set(
    cardImages.filter(c => c.id !== excludeId).map(c => c.sort_order)
  );
}

function fieldRow(label, controlHtml, hint){
  return `<div class="wc-field"><label>${esc(label)}</label>${controlHtml}${hint ? `<div class="hint">${esc(hint)}</div>` : ''}</div>`;
}
function imageUploadControl(fieldKey, currentVal){
  return `
    <div class="wc-upload">
      <div class="wc-upload-box" id="upbox-${fieldKey}">${currentVal ? `<img src="${esc(currentVal)}" onerror="this.parentElement.innerHTML='<i class=&quot;fas fa-image&quot;></i>'">` : '<i class="fas fa-image"></i>'}</div>
      <div class="wc-upload-actions">
        <label class="btn btn-outline btn-sm" style="cursor:pointer;">
          <i class="fas fa-upload"></i> Choose file
          <input type="file" class="wc-file-input" accept="image/*" onchange="handleDraftUpload(this, '${fieldKey}')">
        </label>
        <span class="wc-upload-filename" id="upname-${fieldKey}">${esc(fileBase(currentVal)) || 'No file selected'}</span>
      </div>
    </div>`;
}
async function handleDraftUpload(input, fieldKey){
  const file = input.files[0];
  if(!file) return;
  const dataUrl = await readFileAsDataURL(file);
  modalState.draft[fieldKey] = dataUrl;
  modalState.draftFiles = modalState.draftFiles || {};
  modalState.draftFiles[fieldKey] = file; // the actual File, sent to the server on save
  document.getElementById(`upbox-${fieldKey}`).innerHTML = `<img src="${dataUrl}">`;
  document.getElementById(`upname-${fieldKey}`).textContent = file.name;
}
function buildForm(type, d){
  if(type === 'fwb'){
    const taken = takenFwbSlots(modalState.id);
    const options = Array.from({length: FWB_MAX}, (_, i) => i + 1).map(n => {
      const isTaken = taken.has(n);
      const isSelected = d.sort_order === n;
      return `<option value="${n}" ${isSelected ? 'selected' : ''}>Slot ${n} (fbanner_${n})${isTaken ? ' — taken' : ''}</option>`;
    }).join('');

    return `
      ${fieldRow('Sort order', `<select class="wc-select" onchange="modalState.draft.sort_order=parseInt(this.value,10)">
          <option value="" ${d.sort_order ? '' : 'selected'} disabled>Choose a slot…</option>
          ${options}
        </select>`, 'Slots marked "taken" are already used by another banner — choosing one will swap that banner into this slot\'s old position. The key (e.g. fbanner_1) is set automatically from this.')}
      ${fieldRow('Banner image', imageUploadControl('image', d.image))}
      ${fieldRow('Alt text', `<input class="wc-input" value="${esc(d.alt)}" oninput="modalState.draft.alt=this.value">`)}
      ${fieldRow('Active', `<div class="wc-switch-row"><span class="hint" style="margin:0;">Show this banner on the storefront</span><label class="wc-switch"><input type="checkbox" ${d.is_active ? 'checked' : ''} onchange="modalState.draft.is_active=this.checked"><span class="track"></span></label></div>`)}
    `;
  }

  if(type === 'card'){
    const taken = takenCardSlots(modalState.id);
    const options = Array.from({length: CARD_MAX}, (_, i) => i + 1).map(n => {
      const isTaken = taken.has(n);
      const isSelected = d.sort_order === n;
      return `<option value="${n}" ${isSelected ? 'selected' : ''}>Slot ${n} (card_image_${n})${isTaken ? ' — taken' : ''}</option>`;
    }).join('');

    return `
      ${fieldRow('Sort order', `<select class="wc-select" onchange="modalState.draft.sort_order=parseInt(this.value,10)">
          <option value="" ${d.sort_order ? '' : 'selected'} disabled>Choose a slot…</option>
          ${options}
        </select>`, 'Slot 1 is shown beside the first product grid, slot 2 beside the second. Slots marked "taken" are already used — choosing one will swap that card into this one\'s old position.')}
      ${fieldRow('Card image', imageUploadControl('image', d.image))}
      ${fieldRow('Alt text', `<input class="wc-input" value="${esc(d.alt)}" oninput="modalState.draft.alt=this.value">`)}
      ${fieldRow('Active', `<div class="wc-switch-row"><span class="hint" style="margin:0;">Show this card image on the storefront</span><label class="wc-switch"><input type="checkbox" ${d.is_active ? 'checked' : ''} onchange="modalState.draft.is_active=this.checked"><span class="track"></span></label></div>`)}
    `;
  }

  if(type === 'blog'){
    const catOptions = `<option value="">— No category —</option>` + blogCategories.map(c =>
      `<option value="${c.id}" ${d.category_id === c.id ? 'selected' : ''}>${esc(c.name)}</option>`).join('');
    const monthOptions = BLOG_MONTHS.map(m =>
      `<option value="${m}" ${d.month === m ? 'selected' : ''}>${m}</option>`).join('');

    return `
      ${fieldRow('Title', `<input class="wc-input" maxlength="255" value="${esc(d.title)}" oninput="modalState.draft.title=this.value">`)}
      ${fieldRow('Excerpt', `<textarea class="wc-textarea" rows="3" maxlength="2000" oninput="modalState.draft.excerpt=this.value">${esc(d.excerpt)}</textarea>`)}
      ${fieldRow('Category', `<select class="wc-select" onchange="modalState.draft.category_id=this.value ? parseInt(this.value,10) : null">${catOptions}</select>`)}
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
        ${fieldRow('Day', `<input class="wc-input" type="number" min="1" max="31" value="${esc(d.day)}" oninput="modalState.draft.day=this.value">`)}
        ${fieldRow('Month', `<select class="wc-select" onchange="modalState.draft.month=this.value">${monthOptions}</select>`)}
        ${fieldRow('Comments', `<input class="wc-input" type="number" min="0" value="${esc(d.comment_count)}" oninput="modalState.draft.comment_count=this.value">`)}
      </div>
      ${fieldRow('Blog image', `${imageUploadControl('image', d.image)}
        ${d.image ? `<label class="hint" style="display:flex;align-items:center;gap:6px;margin-top:8px;cursor:pointer;"><input type="checkbox" onchange="modalState.draft.remove_image=this.checked"> Remove current image</label>` : ''}`,
        'Optional. If no image is set, the icon below is shown instead.')}
      ${fieldRow('Icon class (FontAwesome)', `<input class="wc-input" placeholder="fas fa-heartbeat" value="${esc(d.icon_class)}" oninput="modalState.draft.icon_class=this.value">`, 'Used only when there is no image.')}
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        ${fieldRow('Icon color', `<div class="wc-color-control"><input type="color" value="${isHexColor(d.icon_color) ? d.icon_color : '#CC1F1F'}" oninput="modalState.draft.icon_color=this.value"></div>`)}
        ${fieldRow('Icon background', `<div class="wc-color-control"><input type="color" value="${isHexColor(d.icon_bg) ? d.icon_bg : '#f5e0e0'}" oninput="modalState.draft.icon_bg=this.value"></div>`)}
      </div>
      ${fieldRow('Active', `<div class="wc-switch-row"><span class="hint" style="margin:0;">Show this blog on the storefront</span><label class="wc-switch"><input type="checkbox" ${d.is_active ? 'checked' : ''} onchange="modalState.draft.is_active=this.checked"><span class="track"></span></label></div>`)}
    `;
  }
  return '';
}
document.getElementById('wcModalSaveBtn').addEventListener('click', async () => {
  const { type, id, draft } = modalState;
  if(!type) return;

  if(type === 'fwb'){
    if(!draft.sort_order){
      toast('Please choose a sort order.');
      return;
    }

    const fd = new FormData();
    fd.append('sort_order', draft.sort_order);
    fd.append('alt', draft.alt || '');
    fd.append('is_active', draft.is_active ? '1' : '0');
    if(modalState.draftFiles && modalState.draftFiles.image){
      fd.append('image', modalState.draftFiles.image);
    }
    if(id) fd.append('_method', 'PUT');

    const url = id
      ? `{{ url('/admin/pharmacy_full_width_banners') }}/${id}`
      : `{{ route('admin.pharmacy_full_width_banners.store') }}`;

    const saveBtn = document.getElementById('wcModalSaveBtn');
    saveBtn.disabled = true;

    try{
      const res = await fetch(url, {
        method: 'POST', // Laravel method-spoofs PUT via _method above
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        },
        body: fd,
      });
      const data = await res.json();

      if(!res.ok){
        const firstError = data.errors ? Object.values(data.errors)[0][0] : data.message;
        toast(firstError || 'Could not save banner.');
        return;
      }

      if(Array.isArray(data.banners)){
        // A swap may have moved another banner's slot too, so replace the
        // whole list rather than patching just the one we edited.
        fullWidthBanners = data.banners;
      } else {
        const store = ENTITY.fwb.store();
        const idx = store.findIndex(x => x.id === data.banner.id);
        if(idx > -1){ store[idx] = data.banner; } else { store.push(data.banner); }
      }

      toast(data.message || `${ENTITY.fwb.label} saved.`);
      closeModal();
      renderFwb();
    }catch(err){
      toast('Something went wrong. Please try again.');
    }finally{
      saveBtn.disabled = false;
    }
    return;
  }

  if(type === 'card'){
    if(!draft.sort_order){
      toast('Please choose a sort order.');
      return;
    }

    const fd = new FormData();
    fd.append('sort_order', draft.sort_order);
    fd.append('alt', draft.alt || '');
    fd.append('is_active', draft.is_active ? '1' : '0');
    if(modalState.draftFiles && modalState.draftFiles.image){
      fd.append('image', modalState.draftFiles.image);
    }
    if(id) fd.append('_method', 'PUT');

    const url = id
      ? `{{ url('/admin/pharmacy_card_images') }}/${id}`
      : `{{ route('admin.pharmacy_card_images.store') }}`;

    const saveBtn = document.getElementById('wcModalSaveBtn');
    saveBtn.disabled = true;

    try{
      const res = await fetch(url, {
        method: 'POST', // Laravel method-spoofs PUT via _method above
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        },
        body: fd,
      });
      const data = await res.json();

      if(!res.ok){
        const firstError = data.errors ? Object.values(data.errors)[0][0] : data.message;
        toast(firstError || 'Could not save card image.');
        return;
      }

      // A swap may have moved another card's slot too, so replace the whole list.
      cardImages = Array.isArray(data.cardImages) ? data.cardImages : cardImages;

      toast(data.message || 'Card image saved.');
      closeModal();
      renderCards();
    }catch(err){
      toast('Something went wrong. Please try again.');
    }finally{
      saveBtn.disabled = false;
    }
    return;
  }

  if(type === 'blog'){
    if(!(draft.title || '').trim()){ toast('Please enter a blog title.'); return; }
    if(!(draft.excerpt || '').trim()){ toast('Please enter a short excerpt.'); return; }
    if(!draft.day || draft.day < 1 || draft.day > 31){ toast('Please enter a valid day (1–31).'); return; }

    const fd = new FormData();
    fd.append('title', draft.title.trim());
    fd.append('excerpt', draft.excerpt.trim());
    fd.append('day', draft.day);
    fd.append('month', draft.month);
    fd.append('comment_count', draft.comment_count || 0);
    fd.append('category_id', draft.category_id || '');
    const hasIcon = (draft.icon_class || '').trim() !== '';
    fd.append('icon_class', hasIcon ? draft.icon_class.trim() : '');
    fd.append('icon_color', hasIcon ? (draft.icon_color || '') : '');
    fd.append('icon_bg',    hasIcon ? (draft.icon_bg || '') : '');
    fd.append('is_active', draft.is_active ? '1' : '0');
    if(modalState.draftFiles && modalState.draftFiles.image){
      fd.append('image', modalState.draftFiles.image);
    } else if(draft.remove_image){
      fd.append('remove_image', '1');
    }
    if(id) fd.append('_method', 'PUT');

    const url = id
      ? `{{ url('/admin/pharmacy_blogs') }}/${id}`
      : `{{ route('admin.pharmacy_blogs.store') }}`;

    const saveBtn = document.getElementById('wcModalSaveBtn');
    saveBtn.disabled = true;

    try{
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        },
        body: fd,
      });
      const data = await res.json();

      if(!res.ok){
        const firstError = data.errors ? Object.values(data.errors)[0][0] : data.message;
        toast(firstError || 'Could not save blog.');
        return;
      }

      const idx = blogPosts.findIndex(x => x.id === data.blog.id);
      if(idx > -1){ blogPosts[idx] = data.blog; } else { blogPosts.unshift(data.blog); }

      toast(data.message || 'Blog saved.');
      closeModal();
      renderBlogs();
    }catch(err){
      toast('Something went wrong. Please try again.');
    }finally{
      saveBtn.disabled = false;
    }
    return;
  }
});
document.getElementById('wcModalOverlay').addEventListener('click', (e) => { if(e.target === e.currentTarget) closeModal(); });
document.addEventListener('keydown', (e) => { if(e.key === 'Escape'){ closeModal(); } });
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