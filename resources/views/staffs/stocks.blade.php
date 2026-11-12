<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $siteName }} — Stocks</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --red:        #2D7A2D;
  --red-h:      #236023;
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
.notif-more { display: block; width: 100%; padding: 11px 16px; background: none; border: none; border-top: 1px solid var(--border, #e5e7eb); cursor: pointer; font: inherit; font-size: 12px; font-weight: 600; color: var(--red); }
.notif-more:hover { background: var(--hover, rgba(0,0,0,.03)); }
.notif-msg { font-size: 11.5px; color: var(--muted, #666); margin-top: 2px; line-height: 1.4; }
.notif-time { font-size: 10.5px; color: var(--subtle, #999); margin-top: 4px; }
.notif-empty { padding: 28px 16px; text-align: center; font-size: 12.5px; color: var(--muted, #666); }
.notif-foot { padding: 10px 16px; text-align: center; border-top: 1px solid var(--border, #e5e7eb); }

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
.btn-sm.danger { color: var(--red); border-color: var(--red); }
.btn-sm.danger:hover { background: var(--red); color: #fff; }
.btn-sm:disabled { opacity: .6; cursor: not-allowed; }
.filter-actions { display: flex; align-items: center; gap: 8px; }

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

  /* Header: keep every tool visible on mobile */
  .header { gap: 8px; padding: 0 12px; }
  .header-title { font-size: 15px; min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .header-actions { flex-shrink: 0; gap: 6px; }

  /* Compact search, sits right beside the notifications button (same as dashboard) */
  .header > .header-title { flex: 0 1 auto; min-width: 0; }
  .header > .header-search {
    display: flex;
    margin-left: auto;
    flex: 0 1 150px;
    min-width: 0;
    width: 150px;
    max-width: 150px;
    padding: 0 10px;
    gap: 6px;
  }
  .header > .header-search input { width: 100%; min-width: 0; font-size: 13px; }

  /* Suggestions span the screen width instead of the narrow search box */
  .header .search-panel {
    position: fixed;
    top: calc(var(--header-h) + 6px);
    left: 8px; right: 8px;
    width: auto; max-width: none;
  }
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

  /* Modal styles */
  .modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 1000;
    align-items: center;
    justify-content: center;
  }
  .modal-backdrop.open {
    display: flex;
  }
  .modal {
    background: var(--card, #fff);
    border-radius: 14px;
    padding: 28px 28px 24px;
    width: 100%;
    max-width: 460px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.18);
    position: relative;
    animation: modalIn .18s cubic-bezier(.4,0,.2,1);
  }
  @keyframes modalIn {
    from { transform: translateY(18px) scale(.97); opacity: 0; }
    to   { transform: none; opacity: 1; }
  }
  .modal-close {
    position: absolute;
    top: 14px; right: 16px;
    background: none; border: none;
    font-size: 20px; cursor: pointer;
    color: var(--muted);
    line-height: 1;
    padding: 4px 6px;
    border-radius: 6px;
    transition: background .15s;
  }
  .modal-close:hover { background: var(--border, #eee); }
  .modal h3 {
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 4px;
  }
  .modal .modal-sub {
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 20px;
  }
  .modal .stock-num-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--primary-soft, #e6f7ea);
    color: var(--primary, #38a169);
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 18px;
    letter-spacing: .04em;
  }
  .form-group {
    margin-bottom: 14px;
  }
  .form-group label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 6px;
  }
  .form-group select,
  .form-group input {
    width: 100%;
    padding: 9px 12px;
    border: 1.5px solid var(--border, #e2e8f0);
    border-radius: 8px;
    font-size: 13px;
    color: var(--text);
    background: var(--bg, #fff);
    outline: none;
    transition: border-color .15s;
    box-sizing: border-box;
  }
  .form-group select:focus,
  .form-group input:focus {
    border-color: var(--primary, #4f46e5);
  }
  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }
  .modal-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    justify-content: flex-end;
  }

  /* Summary cards */
  .summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 14px;
    margin-bottom: 20px;
  }
  .summary-card {
    padding: 16px 18px;
  }
  .summary-card .sc-label {
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: 6px;
  }
  .summary-card .sc-value {
    font-size: 26px;
    font-weight: 800;
    color: var(--text);
    line-height: 1;
  }
  .summary-card .sc-sub {
    font-size: 11px;
    color: var(--muted);
    margin-top: 4px;
  }

  /* Filter row */
  .filter-row {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
  }
  .filter-row input {
    flex: 1;
    min-width: 180px;
    padding: 7px 12px;
    border: 1.5px solid var(--border, #e2e8f0);
    border-radius: 8px;
    font-size: 13px;
    color: var(--text);
    background: var(--bg, #fff);
    outline: none;
  }
  .filter-row input:focus { border-color: var(--primary, #4f46e5); }
  .filter-row select {
    padding: 7px 12px;
    border: 1.5px solid var(--border, #e2e8f0);
    border-radius: 8px;
    font-size: 13px;
    color: var(--text);
    background: var(--bg, #fff);
    outline: none;
    cursor: pointer;
  }

  /* Batch Inventory header – mobile layout */
  @media (max-width: 768px) {
    .card-head {
      flex-direction: column;
      align-items: stretch;
      gap: 12px;
      padding: 14px 16px;
    }
    .card-head h3 { font-size: 16px; }
    .card-head .filter-row {
      display: grid;
      grid-template-columns: auto minmax(0, 1fr);
      gap: 10px;
      width: 100%;
    }
    /* Search bar on its own full-width row */
    .card-head .filter-row input {
      grid-column: 1 / -1;
      grid-row: 1;
      min-width: 0;
      width: 100%;
      box-sizing: border-box;
      padding: 10px 12px;
      font-size: 14px;
    }
    /* Reset + Products buttons share the first cell of the second row */
    .card-head .filter-row .filter-actions {
      grid-row: 2;
      grid-column: 1;
    }
    .card-head .filter-row .filter-actions .btn-sm {
      grid-row: auto;
      grid-column: auto;
    }
    /* Products button + status filter share the second row */
    .card-head .filter-row .btn-sm {
      grid-row: 2;
      grid-column: 1;
      white-space: nowrap;
      padding: 10px 14px;
      font-size: 13px;
    }
    .card-head .filter-row select {
      grid-row: 2;
      grid-column: 2;
      min-width: 0;
      width: 100%;
      box-sizing: border-box;
      padding: 10px 12px;
      font-size: 14px;
    }
  }

  /* Stock number style in table */
  .stock-num {
    font-family: 'Courier New', monospace;
    font-size: 11px;
    color: var(--primary, #38a169);
    background: var(--primary-soft, #e6f7ea);
    padding: 2px 7px;
    border-radius: 5px;
    font-weight: 700;
    letter-spacing: .03em;
    white-space: nowrap;
  }
  .qty-badge { font-size: 13px; font-weight: 700; color: var(--text); }
  .qty-low   { color: #e53e3e; }
  .qty-ok    { color: #38a169; }

  .pill { font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 20px; }
  .pill.ok      { background: #dcfce7; color: #166534; }
  .pill.warning { background: #fef3c7; color: #92400e; }
  .pill.danger  { background: #fee2e2; color: #991b1b; }

  .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--muted);
    font-size: 13px;
  }
  .empty-state svg { margin-bottom: 10px; opacity: .35; display:block; margin-left:auto; margin-right:auto; }

  /* ── Dark mode overrides for modal ── */
  [data-theme="dark"] .modal-backdrop {
    background: rgba(0,0,0,0.65);
  }
  [data-theme="dark"] .modal {
  background: #242526;
  box-shadow: 0 20px 60px rgba(0,0,0,0.5);
}
  [data-theme="dark"] .modal h3 {
    color: var(--text);
  }
  [data-theme="dark"] .modal .modal-sub {
    color: var(--muted);
  }
  [data-theme="dark"] .modal-close {
    color: var(--muted);
  }
  [data-theme="dark"] .modal-close:hover {
    background: var(--border);
  }
  [data-theme="dark"] .modal .stock-num-badge {
    background: rgba(72, 187, 120, 0.15);
    color: #86efac;
  }
  [data-theme="dark"] .form-group label {
    color: var(--text);
  }
  [data-theme="dark"] .form-group select,
  [data-theme="dark"] .form-group input {
    background: var(--bg);
    color: var(--text);
    border-color: var(--border);
  }
  [data-theme="dark"] .form-group select option {
    background: var(--card);
    color: var(--text);
  }
  [data-theme="dark"] .form-group select:focus,
  [data-theme="dark"] .form-group input:focus {
    border-color: #a5b4fc;
  }

  /* ── Wider modal variant (Products modal) ── */
  .modal.modal-lg { max-width: 560px; }

  /* ── Back link inside a stacked modal ── */
  .modal-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    cursor: pointer;
    color: var(--muted);
    font-size: 12px;
    font-weight: 600;
    padding: 0;
    margin-bottom: 10px;
  }
  .modal-back:hover { color: var(--text); }
  .modal-back svg { width: 14px; height: 14px; }

  /* ── Products modal search bar ── */
  .pick-search { position: relative; margin: 0 0 12px; }
  .pick-search svg {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    width: 15px; height: 15px; color: var(--subtle); pointer-events: none;
  }
  .pick-search input {
    width: 100%;
    padding: 9px 34px 9px 34px;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    background: var(--surface);
    color: var(--text);
    font-size: 13px;
    font-family: inherit;
    outline: none;
    transition: border-color .15s;
  }
  .pick-search input:focus { border-color: var(--red); }
  .pick-search-clear {
    position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
    width: 22px; height: 22px;
    border: none; background: none; cursor: pointer;
    color: var(--muted); font-size: 16px; line-height: 1;
    border-radius: 50%;
    display: none;
  }
  .pick-search-clear:hover { color: var(--text); background: var(--bg); }
  .pick-search.has-value .pick-search-clear { display: block; }
  [data-theme="dark"] .pick-search input { background: var(--bg); }

  /* ── Products modal list ── */
  .pick-list { max-height: 380px; overflow-y: auto; margin: 0 -4px; }
  .pick-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 8px;
    border-radius: 9px;
    transition: background .12s;
  }
  .pick-item + .pick-item { margin-top: 2px; }
  .pick-item.clickable { cursor: pointer; }
  .pick-item.clickable:hover { background: var(--bg); }
  .pick-thumb {
    width: 36px; height: 36px;
    border-radius: 8px;
    background: var(--surface2);
    border: 1px solid var(--border);
    object-fit: cover;
    flex-shrink: 0;
  }
  .pick-thumb.placeholder {
    display: flex; align-items: center; justify-content: center;
    color: var(--subtle);
    font-size: 14px; font-weight: 700;
  }
  .pick-info { flex: 1; min-width: 0; }
  .pick-name { font-size: 13px; font-weight: 600; color: var(--text); }
  .pick-sub { font-size: 11px; color: var(--muted); margin-top: 1px; }
  .pick-chevron { color: var(--subtle); flex-shrink: 0; }
  .pick-chevron svg { width: 16px; height: 16px; }
  .pick-empty { text-align: center; padding: 30px 10px; color: var(--muted); font-size: 13px; }

  /* ── Row action buttons in the batch table ── */
  .row-actions { display: flex; gap: 6px; white-space: nowrap; }
  .row-action-btn {
    width: 28px; height: 28px;
    border-radius: 6px;
    border: 1.5px solid var(--border);
    background: none;
    color: var(--muted);
    cursor: pointer;
    display: inline-flex; align-items: center; justify-content: center;
    transition: border-color .15s, color .15s;
  }
  .row-action-btn svg { width: 14px; height: 14px; }
  .row-action-btn:hover { border-color: var(--red); color: var(--red); }
  .row-action-btn.edit:hover { border-color: var(--blue); color: var(--blue); }

  /* ── Stock details modal (landscape, plain) ── */
  .modal.modal-landscape {
    max-width: 800px;
    padding: 0;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0,0,0,.18);
  }
  .sd-header {
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    padding: 16px 52px 14px 24px;
    border-bottom: 1px solid var(--border);
  }
  .sd-header h3 { margin: 0; font-size: 16px; font-weight: 600; }
  .sd-header-meta { display: flex; align-items: center; gap: 10px; font-size: 13px; color: var(--muted); }
  .modal-landscape .modal-close { top: 12px; right: 14px; }
  .sd-layout { display: grid; grid-template-columns: 240px 1fr; }
  .sd-media {
    padding: 24px;
    border-right: 1px solid var(--border);
    display: flex; flex-direction: column; gap: 14px;
  }
  .sd-image {
    width: 100%; aspect-ratio: 1 / 1;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
  }
  .sd-image img { width: 100%; height: 100%; object-fit: contain; }
  .sd-image .sd-initial { font-size: 48px; font-weight: 600; color: var(--subtle); }
  .sd-media-name { font-size: 14px; font-weight: 600; color: var(--text); line-height: 1.3; }
  a.sd-media-name { display: inline-block; text-decoration: none; cursor: pointer; }
  a.sd-media-name:hover { text-decoration: underline; color: var(--red, #c0392b); }
  .sd-media-sub { font-size: 12px; color: var(--muted); margin-top: 2px; }
  .sd-code { font-size: 12px; color: var(--muted); word-break: break-all; }
  .sd-code::before { content: "Code: "; }
  .sd-info { padding: 8px 24px 0; min-width: 0; display: flex; flex-direction: column; }
  .sd-grid { display: grid; grid-template-columns: 1fr 1fr; column-gap: 28px; }
  .sd-item { padding: 12px 0; border-bottom: 1px solid var(--border); }
  .sd-item .sd-label { font-size: 12px; color: var(--muted); margin-bottom: 3px; }
  .sd-item .sd-value { font-size: 14px; font-weight: 500; color: var(--text); word-break: break-word; }
  .sd-item .sd-value a.sd-link { color: inherit; text-decoration: none; cursor: pointer; }
  .sd-item .sd-value a.sd-link:hover { text-decoration: underline; color: var(--red, #c0392b); }
  .sd-item .sd-hint { font-size: 12px; color: var(--muted); margin-top: 2px; }
  .sd-item .sd-hint.warn { color: var(--orange); }
  .sd-item .sd-hint.bad  { color: var(--red); }
  .sd-footer {
    display: flex; justify-content: flex-end; gap: 8px;
    padding: 14px 24px;
    border-top: 1px solid var(--border);
    background: var(--surface2);
  }
  .stock-table-row { cursor: pointer; }
  .sd-image.zoomable { cursor: zoom-in; }
  .sd-image-hint { font-size: 11px; color: var(--subtle); text-align: center; margin-top: 6px; }

  /* Full image viewer */
  .img-viewer {
    display: none; position: fixed; inset: 0; z-index: 1100;
    background: rgba(0,0,0,.85);
    align-items: center; justify-content: center;
    padding: 32px; cursor: zoom-out;
  }
  .img-viewer.open { display: flex; }
  .img-viewer img {
    max-width: 100%; max-height: 100%;
    object-fit: contain; background: #fff;
    border-radius: 4px; box-shadow: 0 10px 40px rgba(0,0,0,.5);
    cursor: default;
  }
  .img-viewer-close {
    position: absolute; top: 14px; right: 18px;
    background: none; border: none; color: #fff;
    font-size: 30px; line-height: 1; cursor: pointer; padding: 4px 10px;
    border-radius: 6px;
  }
  .img-viewer-close:hover { background: rgba(255,255,255,.15); }

  @media (max-width: 720px) {
    .sd-layout { grid-template-columns: 1fr; }
    .sd-media { border-right: none; border-bottom: 1px solid var(--border); flex-direction: row; align-items: center; padding: 16px 24px; }
    .sd-image { width: 90px; flex-shrink: 0; }
    .sd-grid { grid-template-columns: 1fr; }
  }

  /* ── Pagination (100 per page) ── */
  .pager {
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
    padding: 12px 20px;
    border-top: 1px solid var(--border);
    background: var(--surface2);
  }
  .pager[hidden] { display: none; }
  .pager-info { font-size: 12px; color: var(--muted); }
  .pager-info strong { color: var(--text); font-weight: 600; }
  .pager-controls { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
  .pager-btn {
    min-width: 32px; height: 32px; padding: 0 10px;
    border: 1.5px solid var(--border); border-radius: 6px;
    background: var(--surface); color: var(--muted);
    font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: border-color .15s, color .15s, background .15s;
  }
  .pager-btn:hover:not(:disabled):not(.active) { border-color: var(--red); color: var(--red); }
  .pager-btn.active { background: var(--red); border-color: var(--red); color: #fff; cursor: default; }
  .pager-btn:disabled { opacity: .45; cursor: not-allowed; }
  .pager-gap { padding: 0 4px; color: var(--subtle); font-size: 12px; }
</style>
</head>
<body>
<div class="toast" id="toast">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="toastMsg">Done.</span>
</div>
<div class="overlay" id="overlay"></div>

<!-- Add / Edit Stock Modal -->
<div class="modal-backdrop" id="addStockModal">
  <div class="modal">
    <button class="modal-close" id="closeModal" title="Close">&times;</button>
    <h3 id="stockModalTitle">Add Medicine Stock</h3>
    <div class="modal-sub" id="stockModalSub">Fill in the details to log a new batch entry.</div>

    <div class="stock-num-badge" id="stockNoBadge" style="display:none;">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
      Stock No: <span id="generatedStockNo">—</span>
    </div>

    <input type="hidden" id="modalStockId" value="">

    <div class="form-group">
      <label>Medicine *</label>
      <select id="modalMedicine">
        <option value="">— Select medicine —</option>
        @foreach($products as $product)
          <option value="{{ $product->id }}">{{ $product->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label>Supplier</label>
      <input type="text" name="supplier" placeholder="Enter Supplier Name (Optional)" id="modalSupplier">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Quantity (units) *</label>
        <input type="number" id="modalQty" placeholder="e.g. 200" min="0">
      </div>
      <div class="form-group">
        <label>Unit Cost (₱)</label>
        <input type="number" id="modalCost" placeholder="e.g. 5.50" min="0" step="0.01">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Manufacturing Date</label>
        <input type="date" id="modalMfgDate">
      </div>
      <div class="form-group">
        <label>Expiry Date</label>
        <input type="date" id="modalExpiry">
      </div>
    </div>
    <div class="form-group">
      <label>Status</label>
      <select id="modalStatus">
        <option value="1">Available</option>
        <option value="0">Unavailable</option>
      </select>
    </div>
    <div class="modal-sub" id="modalStatusHint" style="display:none;color:#e07b00;margin:-6px 0 0;"></div>

    <div class="modal-actions">
      <button class="btn-sm" id="cancelModal">Cancel</button>
      <button class="btn-sm primary" id="confirmAddStock">Add Stock</button>
    </div>
  </div>
</div>

<!-- Stock Details Modal (landscape) -->
<div class="modal-backdrop" id="stockDetailsModal">
  <div class="modal modal-landscape">
    <button class="modal-close" id="closeStockDetails" title="Close">&times;</button>

    <div class="sd-header">
      <h3>Stock Details</h3>
      <div class="sd-header-meta">
        <span id="sdStockNo">—</span>
        <span id="sdStatus"></span>
      </div>
    </div>

    <div class="sd-layout">
      <div class="sd-media">
        <div class="sd-image" id="sdImage"></div>
        <div class="sd-image-hint" id="sdImageHint" style="display:none;">Click image to enlarge</div>
        <div>
          <a class="sd-media-name" id="sdProductName" href="#" title="View product details in Inventory">—</a>
          <div class="sd-media-sub" id="sdProductSub"></div>
          <div class="sd-code" id="sdCode" style="display:none;margin-top:6px;"></div>
        </div>
      </div>

      <div class="sd-info">
        <div class="sd-grid">
          <div class="sd-item"><div class="sd-label">Supplier</div><div class="sd-value" id="sdSupplier">—</div></div>
          <div class="sd-item"><div class="sd-label">Availability</div><div class="sd-value" id="sdAvail">—</div></div>
          <div class="sd-item"><div class="sd-label">Quantity</div><div class="sd-value" id="sdQty">—</div></div>
          <div class="sd-item"><div class="sd-label">Unit Cost</div><div class="sd-value" id="sdCost">—</div></div>
          <div class="sd-item"><div class="sd-label">Total Value</div><div class="sd-value" id="sdTotal">—</div></div>
          <div class="sd-item"><div class="sd-label">Date Added</div><div class="sd-value" id="sdAdded">—</div></div>
          <div class="sd-item"><div class="sd-label">Manufacturing Date</div><div class="sd-value" id="sdMfg">—</div></div>
          <div class="sd-item"><div class="sd-label">Expiry Date</div><div class="sd-value" id="sdExpiry">—</div><div class="sd-hint" id="sdExpiryHint"></div></div>
        </div>
      </div>
    </div>

    <div class="sd-footer">
      <button class="btn-sm" id="sdCloseBtn" type="button">Close</button>
      <button class="btn-sm primary" id="sdEditBtn" type="button">Edit Stock</button>
    </div>
  </div>
</div>

<!-- Full image viewer -->
<div class="img-viewer" id="imgViewer">
  <button class="img-viewer-close" id="imgViewerClose" title="Close" type="button">&times;</button>
  <img id="imgViewerImg" src="" alt="">
</div>

<!-- Products Modal (status overview) -->
<div class="modal-backdrop" id="productsModal">
  <div class="modal modal-lg">
    <button class="modal-close" id="closeProductsModal" title="Close">&times;</button>
    <h3>Products</h3>
    <div class="modal-sub">Green means a product has an ongoing active batch. Tap an Out of Stock product to activate one of its available batches.</div>
    <div class="pick-search" id="productsSearchWrap">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="productsSearch" placeholder="Search products…" autocomplete="off">
      <button type="button" class="pick-search-clear" id="productsSearchClear" title="Clear">&times;</button>
    </div>
    <div class="pick-list" id="productsList">
      <div class="pick-empty">Loading products…</div>
    </div>
  </div>
</div>

<!-- Available Stocks Modal (per out-of-stock product) -->
<div class="modal-backdrop" id="availableStocksModal">
  <div class="modal modal-lg">
    <button class="modal-close" id="closeAvailableStocksModal" title="Close">&times;</button>
    <button class="modal-back" id="backToProducts">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Back to products
    </button>
    <h3 id="availableStocksTitle">Available Batches</h3>
    <div class="modal-sub">Sorted by longest time left before expiry. Tap a batch to make it the active stock.</div>
    <div class="pick-list" id="availableStocksList">
      <div class="pick-empty">Loading batches…</div>
    </div>
  </div>
</div>

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
    Inventory
  </a>
  <a class="nav-item" href="{{ route('staff.orders') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    Orders
  </a>

  <div class="sidebar-section-label">Manage</div>
  <a class="nav-item active" href="{{ route('staff.stocks') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    Stocks
  </a>
  <a class="nav-item" href="{{ route('staff.reports') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
    Reports
  </a>
  <a class="nav-item" href="{{ route('staff.expiry') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    Expiry Tracking
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
  <div class="header-title">Stocks</div>
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

    <button class="icon-btn" id="themeBtn" title="Toggle dark mode">
      <span id="themeIco"></span>
    </button>
  </div>
</header>

<main class="main">

  <div style="margin-bottom:20px;display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <div style="font-size:20px;font-weight:800;color:var(--text);">Stock Batches</div>
      <div style="font-size:13px;color:var(--muted);margin-top:3px;">Track medicine stock levels, batch entries, and expiry dates.</div>
    </div>
    <button class="btn-sm primary" id="openAddStock" style="align-self:center;">+ Add Stock</button>
  </div>

  <!-- Summary Cards -->
  <div class="summary-grid">
    <div class="card summary-card">
      <div class="sc-label">Total Batches</div>
      <div class="sc-value" id="sumBatches">—</div>
      <div class="sc-sub">All batch entries</div>
    </div>
    <div class="card summary-card">
      <div class="sc-label">Total Units</div>
      <div class="sc-value" id="sumUnits">—</div>
      <div class="sc-sub">Units in stock</div>
    </div>
    <div class="card summary-card">
      <div class="sc-label">Medicines</div>
      <div class="sc-value" id="sumMeds">—</div>
      <div class="sc-sub">Unique medicines</div>
    </div>
    <div class="card summary-card">
      <div class="sc-label">Expiring Soon</div>
      <div class="sc-value" id="sumExpiring" style="color:#e53e3e;">—</div>
      <div class="sc-sub">Within 10 days</div>
    </div>
  </div>

  <!-- Batch Table -->
  <div class="card">
    <div class="card-head">
      <h3>Batch Inventory</h3>
      <div class="filter-row">
        <div class="filter-actions">
        <button class="btn-sm" id="openProductsModal" type="button">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;margin-right:4px;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>Products
        </button>
        </div>
        <input type="text" id="searchBatch" placeholder="Search medicine or stock no…">
        <select id="filterStatus">
          <option value="Active" selected>Available</option>
          <option value="Inactive">Unavailable</option>
          <option value="Low Stock">Low Stock</option>
          <option value="Out of Stock">Out of Stock</option>
          <option value="Expiring Soon">Expiring Soon</option>
          <option value="Expired">Expired</option>
          <option value="">All Stocks</option>
        </select>
      </div>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Stock No.</th>
            <th>Medicine</th>
            <th>Supplier</th>
            <th>Qty (units)</th>
            <th>Unit Cost</th>
            <th>Mfg. Date</th>
            <th>Expiry Date</th>
            <th>Status</th>
            <th>Date Added</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="batchTableBody"></tbody>
      </table>
    </div>
    <div class="empty-state" id="emptyState" style="display:none;">
      <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      <div>No batches found. Click <strong>+ Add Stock</strong> to get started.</div>
    </div>
    <div class="pager" id="pager" hidden>
      <div class="pager-info" id="pagerInfo"></div>
      <div class="pager-controls" id="pagerControls"></div>
    </div>
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
// ── Server data ──────────────────────────────────────────────────────────
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
const ROUTES = {
  store: "{{ route('staff.stocks.store') }}",
  update: id => `{{ url('/staff/stocks') }}/${id}`,
  destroy: id => `{{ url('/staff/stocks') }}/${id}`,
  activate: id => `{{ url('/staff/stocks') }}/${id}/activate`,
  products: "{{ route('staff.stocks.products') }}",
  availableStocks: productId => `{{ url('/staff/stocks/products') }}/${productId}/available`,
  // Inventory page; "?open={id}" makes it open that product's details modal.
  inventoryProduct: productId => `{{ route('staff.inventory') }}?open=${productId}`,
  // Expiry page; "?open={id}" makes it open that batch's expiry details modal.
  expiryStock: stockId => `{{ route('staff.expiry') }}?open=${stockId}`,
};

// Seeded from the server. Every batch already carries a single computed
// `status`: Active, Inactive, Out of Stock, or Expired.
let batches = @json($stocks);

// Batch table shows this many rows per page.
const PAGE_SIZE = 100;
let currentPage = 1;

// Batches with this many units or fewer count as "Low Stock" in the status filter.
const LOW_STOCK_THRESHOLD = 10;

// Batches expiring within this many days count as "Expiring Soon" in the status
// filter. Keep in sync with StaffNotificationsController's expiring window.
const EXPIRING_DAYS = 10;

function isExpiringSoon(b) {
  if (!b.expiry_date || b.quantity <= 0 || b.status === "Expired") return false;
  const today = new Date(); today.setHours(0, 0, 0, 0);
  const days = Math.round((new Date(b.expiry_date + "T00:00:00") - today) / 86400000);
  return days >= 0 && days <= EXPIRING_DAYS;
}

// ── Helpers ────────────────────────────────────────────────────────────────
function statusPill(s) {
  const cls = { "Active": "ok", "Inactive": "blue", "Out of Stock": "warning", "Expired": "danger" };
  return `<span class="pill ${cls[s] || "blue"}">${s}</span>`;
}

function fmtDate(d) {
  if (!d) return "—";
  return new Date(d + "T00:00:00").toLocaleDateString("en-PH", { year: "numeric", month: "short", day: "numeric" });
}

function fmtMoney(v) {
  return (v || v === 0) ? "₱" + Number(v).toFixed(2) : "—";
}

function showToast(msg) {
  const t = document.getElementById("toast");
  document.getElementById("toastMsg").textContent = msg;
  t.classList.add("on");
  setTimeout(() => t.classList.remove("on"), 3000);
}

async function apiFetch(url, options = {}) {
  const res = await fetch(url, {
    ...options,
    headers: {
      "X-CSRF-TOKEN": CSRF_TOKEN,
      "Accept": "application/json",
      "Content-Type": "application/json",
      ...(options.headers || {}),
    },
  });
  const data = await res.json().catch(() => ({}));
  if (!res.ok) {
    throw new Error(data.message || "Something went wrong.");
  }
  return data;
}

// ── Render batch table ───────────────────────────────────────────────────
function renderTable() {
  const search = document.getElementById("searchBatch").value.toLowerCase();
  const filter = document.getElementById("filterStatus").value;

  const filtered = batches.filter(b => {
    const matchS = b.medicine.toLowerCase().includes(search) || b.stock_no.toLowerCase().includes(search);
    // "Low Stock" is a quantity-based filter (1–10 units left, not expired);
    // every other option matches on the computed `status`.
    const matchF = !filter
      || (filter === "Low Stock"
            ? b.quantity > 0 && b.quantity <= LOW_STOCK_THRESHOLD && b.status !== "Expired"
            : filter === "Expiring Soon"
              ? isExpiringSoon(b)
              : b.status === filter);
    return matchS && matchF;
  });

  // Paging: keep the current page in range, then take this page's slice.
  const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
  if (currentPage > totalPages) currentPage = totalPages;
  if (currentPage < 1) currentPage = 1;
  const startIdx = (currentPage - 1) * PAGE_SIZE;
  const rows = filtered.slice(startIdx, startIdx + PAGE_SIZE);

  const tbody = document.getElementById("batchTableBody");
  const empty = document.getElementById("emptyState");

  if (!rows.length) {
    tbody.innerHTML = "";
    empty.style.display = "block";
  } else {
    empty.style.display = "none";
    tbody.innerHTML = rows.map(b => {
      const qc = b.quantity <= 50 ? "qty-badge qty-low" : "qty-badge qty-ok";
      return `<tr class="stock-table-row" onclick="openStockDetails(${b.id})" title="View stock details">
        <td><span class="stock-num">${b.stock_no}</span></td>
        <td style="font-weight:600;">${b.medicine}</td>
        <td style="font-size:12px;color:var(--muted);">${b.supplier || "—"}</td>
        <td><span class="${qc}">${b.quantity.toLocaleString()}</span></td>
        <td style="font-size:12px;color:var(--muted);">${fmtMoney(b.unit_cost)}</td>
        <td style="font-size:12px;color:var(--muted);">${fmtDate(b.manufacturing_date)}</td>
        <td style="font-size:12px;color:var(--muted);">${fmtDate(b.expiry_date)}</td>
        <td>${statusPill(b.status)}</td>
        <td style="font-size:12px;color:var(--muted);">${fmtDate(b.added)}</td>
        <td>
          <div class="row-actions">
            <button class="row-action-btn edit" title="Edit" onclick="event.stopPropagation(); openEditStock(${b.id})">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <button class="row-action-btn" title="Delete" onclick="event.stopPropagation(); deleteStock(${b.id}, '${b.stock_no}')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            </button>
          </div>
        </td>
      </tr>`;
    }).join("");
  }

  renderPager(filtered.length, totalPages, startIdx, rows.length);

  // Summary
  const totalUnits = batches.reduce((s, b) => s + b.quantity, 0);
  const uniqueMeds = new Set(batches.map(b => b.medicine)).size;
  const expiring = batches.filter(isExpiringSoon).length;
  document.getElementById("sumBatches").textContent = batches.length;
  document.getElementById("sumUnits").textContent = totalUnits.toLocaleString();
  document.getElementById("sumMeds").textContent = uniqueMeds;
  document.getElementById("sumExpiring").textContent = expiring;
}

// ── Pagination ─────────────────────────────────────────────────────────────
function renderPager(total, totalPages, startIdx, shown) {
  const pager = document.getElementById("pager");
  if (!total) { pager.hidden = true; return; }
  pager.hidden = false;

  document.getElementById("pagerInfo").innerHTML =
    `Showing <strong>${(startIdx + 1).toLocaleString()}–${(startIdx + shown).toLocaleString()}</strong> of <strong>${total.toLocaleString()}</strong> batches`;

  const controls = document.getElementById("pagerControls");
  if (totalPages <= 1) { controls.innerHTML = ""; return; }

  // First, last, and a window of pages around the current one, with "…" gaps.
  const pages = [];
  for (let i = 1; i <= totalPages; i++) {
    if (i === 1 || i === totalPages || Math.abs(i - currentPage) <= 2) pages.push(i);
    else if (pages[pages.length - 1] !== "…") pages.push("…");
  }

  controls.innerHTML =
    `<button class="pager-btn" type="button" data-page="${currentPage - 1}" ${currentPage === 1 ? "disabled" : ""}>‹ Prev</button>` +
    pages.map(p => p === "…"
      ? `<span class="pager-gap">…</span>`
      : `<button class="pager-btn ${p === currentPage ? "active" : ""}" type="button" data-page="${p}">${p}</button>`
    ).join("") +
    `<button class="pager-btn" type="button" data-page="${currentPage + 1}" ${currentPage === totalPages ? "disabled" : ""}>Next ›</button>`;
}

document.getElementById("pagerControls").addEventListener("click", e => {
  const btn = e.target.closest(".pager-btn");
  if (!btn || btn.disabled || btn.classList.contains("active")) return;
  currentPage = parseInt(btn.dataset.page, 10);
  renderTable();
  document.getElementById("batchTableBody").closest(".card").scrollIntoView({ behavior: "smooth", block: "start" });
});

// ── Add / Edit Stock Modal ─────────────────────────────────────────────────
let stockModalMode = "add"; // "add" | "edit"

function openStockAddModal() {
  stockModalMode = "add";
  document.getElementById("stockModalTitle").textContent = "Add Medicine Stock";
  document.getElementById("stockModalSub").textContent = "Fill in the details to log a new batch entry.";
  document.getElementById("confirmAddStock").textContent = "Add Stock";
  document.getElementById("stockNoBadge").style.display = "none";
  document.getElementById("modalStockId").value = "";
  ["modalMedicine", "modalSupplier", "modalQty", "modalCost", "modalMfgDate", "modalExpiry"]
    .forEach(id => document.getElementById(id).value = "");
  document.getElementById("modalStatus").value = "1";
  document.getElementById("modalStatusHint").style.display = "none";
  document.getElementById("addStockModal").classList.add("open");
}

function openEditStock(id) {
  const b = batches.find(x => x.id === id);
  if (!b) return;
  stockModalMode = "edit";
  document.getElementById("stockModalTitle").textContent = "Edit Medicine Stock";
  document.getElementById("stockModalSub").textContent = "Update this batch's details.";
  document.getElementById("confirmAddStock").textContent = "Save Changes";
  document.getElementById("stockNoBadge").style.display = "inline-flex";
  document.getElementById("generatedStockNo").textContent = b.stock_no;
  document.getElementById("modalStockId").value = b.id;
  document.getElementById("modalMedicine").value = b.product_id;
  document.getElementById("modalSupplier").value = b.supplier || "";
  document.getElementById("modalQty").value = b.quantity;
  document.getElementById("modalCost").value = b.unit_cost || "";
  document.getElementById("modalMfgDate").value = b.manufacturing_date || "";
  document.getElementById("modalExpiry").value = b.expiry_date || "";
  document.getElementById("modalStatus").value = b.is_active ? "1" : "0";
  document.getElementById("modalStatusHint").style.display = "none";
  document.getElementById("addStockModal").classList.add("open");
}

function closeModal() {
  document.getElementById("addStockModal").classList.remove("open");
}

document.getElementById("openAddStock").addEventListener("click", openStockAddModal);
document.getElementById("closeModal").addEventListener("click", closeModal);
document.getElementById("cancelModal").addEventListener("click", closeModal);
document.getElementById("addStockModal").addEventListener("click", e => { if (e.target === e.currentTarget) closeModal(); });

document.getElementById("confirmAddStock").addEventListener("click", async function () {
  const productId = document.getElementById("modalMedicine").value;
  const qty = parseInt(document.getElementById("modalQty").value, 10);
  const btn = this;

  if (!productId) return alert("Please select a medicine.");
  if (isNaN(qty) || qty < 0) return alert("Please enter a valid quantity.");

  const payload = {
    product_id: productId,
    supplier: document.getElementById("modalSupplier").value || null,
    quantity: qty,
    unit_cost: document.getElementById("modalCost").value || 0,
    manufacturing_date: document.getElementById("modalMfgDate").value || null,
    expiry_date: document.getElementById("modalExpiry").value || null,
    is_active: document.getElementById("modalStatus").value === "1",
  };

  const isEdit = stockModalMode === "edit";
  const id = document.getElementById("modalStockId").value;
  const url = isEdit ? ROUTES.update(id) : ROUTES.store;
  const method = isEdit ? "PUT" : "POST";

  document.getElementById("modalStatusHint").style.display = "none";
  btn.disabled = true;
  try {
    const data = await apiFetch(url, { method, body: JSON.stringify(payload) });
    batches = data.stocks;
    closeModal();
    renderTable();
    showToast(data.message || "Saved successfully.");
  } catch (err) {
    const hint = document.getElementById("modalStatusHint");
    hint.textContent = err.message;
    hint.style.display = "block";
  } finally {
    btn.disabled = false;
  }
});

function deleteStock(id, stockNo) {
  if (!confirm(`Delete batch "${stockNo}"? This cannot be undone.`)) return;
  apiFetch(ROUTES.destroy(id), { method: "DELETE" })
    .then(data => {
      batches = data.stocks;
      renderTable();
      showToast(data.message || "Stock deleted successfully.");
    })
    .catch(err => alert(err.message));
}

// ── Stock details modal (click a row) ──────────────────────────────────────
let detailsStockId = null;

function escHtml(v) {
  return String(v ?? "").replace(/[&<>"']/g, c => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));
}

function openStockDetails(id) {
  const b = batches.find(x => x.id === id);
  if (!b) return;
  detailsStockId = id;

  // Product image (falls back to the product's initial)
  const imgBox = document.getElementById("sdImage");
  const initial = (b.medicine || "?").charAt(0).toUpperCase();
  imgBox.innerHTML = b.product_image
    ? `<img src="${escHtml(b.product_image)}" alt="${escHtml(b.medicine)}">`
    : `<span class="sd-initial">${escHtml(initial)}</span>`;
  const img = imgBox.querySelector("img");
  const hintEl = document.getElementById("sdImageHint");
  imgBox.classList.remove("zoomable");
  imgBox.onclick = null;
  hintEl.style.display = "none";
  if (img) {
    img.onload = () => {
      imgBox.classList.add("zoomable");
      hintEl.style.display = "";
      imgBox.onclick = () => openImageViewer(b.product_image, b.medicine);
    };
    img.onerror = () => {
      imgBox.classList.remove("zoomable");
      imgBox.onclick = null;
      hintEl.style.display = "none";
      imgBox.innerHTML = `<span class="sd-initial">${escHtml(initial)}</span>`;
    };
  }

  const nameEl = document.getElementById("sdProductName");
  nameEl.textContent = b.medicine || "—";
  if (b.product_id) {
    nameEl.href = ROUTES.inventoryProduct(b.product_id);
  } else {
    nameEl.removeAttribute("href");
  }
  document.getElementById("sdProductSub").textContent =
    [b.generic_name, b.form].filter(Boolean).join(" · ");
  const code = document.getElementById("sdCode");
  code.textContent = b.code_name || "";
  code.style.display = b.code_name ? "" : "none";

  document.getElementById("sdStockNo").textContent = b.stock_no;
  document.getElementById("sdStatus").innerHTML = statusPill(b.status);
  document.getElementById("sdSupplier").textContent = b.supplier || "—";
  document.getElementById("sdQty").textContent = Number(b.quantity).toLocaleString() + " units";
  document.getElementById("sdCost").textContent = fmtMoney(b.unit_cost);
  document.getElementById("sdTotal").textContent = fmtMoney((b.unit_cost || 0) * (b.quantity || 0));
  document.getElementById("sdMfg").textContent = fmtDate(b.manufacturing_date);
  const expiryEl = document.getElementById("sdExpiry");
  expiryEl.textContent = "";
  if (b.expiry_date) {
    // Only batches with an expiry date appear on the Expiry page, so only they get a link.
    const link = document.createElement("a");
    link.className = "sd-link";
    link.href = ROUTES.expiryStock(b.id);
    link.title = "View expiry details in Expiry Tracking";
    link.textContent = fmtDate(b.expiry_date);
    expiryEl.appendChild(link);
  } else {
    expiryEl.textContent = fmtDate(b.expiry_date);
  }
  document.getElementById("sdAvail").textContent = b.is_active ? "Available" : "Unavailable";
  document.getElementById("sdAdded").textContent = fmtDate(b.added);

  // Days-to-expiry hint
  const hint = document.getElementById("sdExpiryHint");
  hint.className = "sd-hint";
  hint.textContent = "";
  if (b.expiry_date) {
    const today = new Date(); today.setHours(0, 0, 0, 0);
    const days = Math.round((new Date(b.expiry_date + "T00:00:00") - today) / 86400000);
    if (days < 0) { hint.textContent = `Expired ${Math.abs(days)} day${Math.abs(days) === 1 ? "" : "s"} ago`; hint.classList.add("bad"); }
    else if (days === 0) { hint.textContent = "Expires today"; hint.classList.add("bad"); }
    else { hint.textContent = `${days} day${days === 1 ? "" : "s"} left`; if (days <= EXPIRING_DAYS) hint.classList.add("warn"); }
  }

  document.getElementById("stockDetailsModal").classList.add("open");
}

function closeStockDetails() {
  document.getElementById("stockDetailsModal").classList.remove("open");
  detailsStockId = null;
}

document.getElementById("closeStockDetails").addEventListener("click", closeStockDetails);
document.getElementById("sdCloseBtn").addEventListener("click", closeStockDetails);
document.getElementById("stockDetailsModal").addEventListener("click", e => { if (e.target === e.currentTarget) closeStockDetails(); });
document.getElementById("sdEditBtn").addEventListener("click", () => {
  const id = detailsStockId;
  closeStockDetails();
  if (id !== null) openEditStock(id);
});

// ── Full image viewer ──
function openImageViewer(src, alt) {
  const img = document.getElementById("imgViewerImg");
  img.src = src;
  img.alt = alt || "";
  document.getElementById("imgViewer").classList.add("open");
}
function closeImageViewer() {
  document.getElementById("imgViewer").classList.remove("open");
  document.getElementById("imgViewerImg").src = "";
}
document.getElementById("imgViewer").addEventListener("click", e => {
  if (e.target.id !== "imgViewerImg") closeImageViewer();   // click outside the image closes
});
document.getElementById("imgViewerClose").addEventListener("click", closeImageViewer);

document.addEventListener("keydown", e => {
  if (e.key !== "Escape") return;
  // Escape closes the image viewer first, then the details modal.
  if (document.getElementById("imgViewer").classList.contains("open")) closeImageViewer();
  else closeStockDetails();
});

// ── Search / Filter ────────────────────────────────────────────────────────
document.getElementById("searchBatch").addEventListener("input", () => { currentPage = 1; renderTable(); });
document.getElementById("filterStatus").addEventListener("change", () => { currentPage = 1; renderTable(); });

// ── Products modal (status overview) ────────────────────────────────────────
let allProducts = [];
function showProductsModal() {
  document.getElementById("productsModal").classList.add("open");
  document.getElementById("productsSearch").value = "";
  document.getElementById("productsSearchWrap").classList.remove("has-value");
  loadProducts();
}
function hideProductsModal() {
  document.getElementById("productsModal").classList.remove("open");
}
function loadProducts() {
  const list = document.getElementById("productsList");
  list.innerHTML = `<div class="pick-empty">Loading products…</div>`;
  apiFetch(ROUTES.products, { method: "GET" })
    .then(data => { allProducts = data.products || []; applyProductsFilter(); })
    .catch(err => { list.innerHTML = `<div class="pick-empty">${err.message}</div>`; });
}
function applyProductsFilter() {
  const q = document.getElementById("productsSearch").value.trim().toLowerCase();
  const filtered = q
    ? allProducts.filter(p => (p.name || "").toLowerCase().includes(q) || (p.status || "").toLowerCase().includes(q))
    : allProducts;
  renderProductsList(filtered, q);
}
function renderProductsList(products, query = "") {
  const list = document.getElementById("productsList");
  if (!products.length) {
    list.innerHTML = `<div class="pick-empty">${query ? "No products match your search." : "No products yet."}</div>`;
    return;
  }
  list.innerHTML = products.map(p => {
    const clickable = p.status === "Out of Stock";
    const pillCls = p.status === "In Stock" ? "ok" : "warning";
    const initial = p.name ? p.name.charAt(0).toUpperCase() : "?";
    const safeName = p.name.replace(/'/g, "\\'");
    const thumb = p.image
      ? `<img class="pick-thumb" src="${p.image}" alt="">`
      : `<div class="pick-thumb placeholder">${initial}</div>`;
    return `<div class="pick-item ${clickable ? "clickable" : ""}" ${clickable ? `onclick="openAvailableStocks(${p.id}, '${safeName}')"` : ""}>
      ${thumb}
      <div class="pick-info">
        <div class="pick-name">${p.name}</div>
        <div class="pick-sub">${p.batch_count} batch${p.batch_count === 1 ? "" : "es"} recorded</div>
      </div>
      <span class="pill ${pillCls}">${p.status}</span>
      ${clickable ? `<span class="pick-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></span>` : ""}
    </div>`;
  }).join("");
}

document.getElementById("productsSearch").addEventListener("input", e => {
  document.getElementById("productsSearchWrap").classList.toggle("has-value", !!e.target.value);
  applyProductsFilter();
});
document.getElementById("productsSearchClear").addEventListener("click", () => {
  const input = document.getElementById("productsSearch");
  input.value = "";
  document.getElementById("productsSearchWrap").classList.remove("has-value");
  applyProductsFilter();
  input.focus();
});
document.getElementById("openProductsModal").addEventListener("click", showProductsModal);
document.getElementById("closeProductsModal").addEventListener("click", hideProductsModal);
document.getElementById("productsModal").addEventListener("click", e => { if (e.target === e.currentTarget) hideProductsModal(); });

// ── Available stocks modal (per out-of-stock product) ───────────────────────
function openAvailableStocks(productId, productName) {
  document.getElementById("productsModal").classList.remove("open");
  document.getElementById("availableStocksModal").classList.add("open");
  document.getElementById("availableStocksTitle").textContent = `Available Batches — ${productName}`;
  const list = document.getElementById("availableStocksList");
  list.innerHTML = `<div class="pick-empty">Loading batches…</div>`;

  apiFetch(ROUTES.availableStocks(productId), { method: "GET" })
    .then(data => renderAvailableStocksList(data.stocks))
    .catch(err => { list.innerHTML = `<div class="pick-empty">${err.message}</div>`; });
}
function hideAvailableStocksModal() {
  document.getElementById("availableStocksModal").classList.remove("open");
}
function renderAvailableStocksList(stocks) {
  const list = document.getElementById("availableStocksList");
  if (!stocks.length) {
    list.innerHTML = `<div class="pick-empty">No available batches for this product yet. Try adding a new stock batch instead.</div>`;
    return;
  }
  list.innerHTML = stocks.map(s => `
    <div class="pick-item clickable" onclick="activateStock(${s.id})">
      <div class="pick-thumb placeholder">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/></svg>
      </div>
      <div class="pick-info">
        <div class="pick-name">${s.stock_no} <span style="font-weight:500;color:var(--muted);">· ${s.quantity.toLocaleString()} units</span></div>
        <div class="pick-sub">Expires ${fmtDate(s.expiry_date)} · ${s.supplier || "No supplier listed"}</div>
      </div>
      <span class="pick-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></span>
    </div>
  `).join("");
}

function activateStock(stockId) {
  apiFetch(ROUTES.activate(stockId), { method: "PUT" })
    .then(data => {
      batches = data.stocks;
      renderTable();
      hideAvailableStocksModal();
      hideProductsModal();
      showToast(data.message || "Stock activated.");
    })
    .catch(err => alert(err.message));
}

document.getElementById("closeAvailableStocksModal").addEventListener("click", hideAvailableStocksModal);
document.getElementById("availableStocksModal").addEventListener("click", e => { if (e.target === e.currentTarget) hideAvailableStocksModal(); });
document.getElementById("backToProducts").addEventListener("click", () => {
  hideAvailableStocksModal();
  showProductsModal();
});

// ── Init ───────────────────────────────────────────────────────────────────
// Deep link from the dashboard: /staff/stocks?status=low-stock
(function applyStatusFromUrl() {
  const param = new URLSearchParams(window.location.search).get("status");
  if (!param) return;
  const map = {
    "low-stock": "Low Stock",
    "out-of-stock": "Out of Stock",
    "expired": "Expired",
    "expiring-soon": "Expiring Soon",
    "active": "Active",
    "inactive": "Inactive",
    "all": ""
  };
  if (param in map) document.getElementById("filterStatus").value = map[param];
})();
renderTable();

// Deep link from the Expiry page: /staff/stocks?open={stockId} opens that batch's details modal.
(function openStockFromUrl() {
  const params = new URLSearchParams(window.location.search);
  if (!params.has("open")) return;
  if (params.get("open") === "notifications") return;   // handled by the notification bell
  const id = Number(params.get("open"));

  // Drop the param so a refresh or Back doesn't re-open the modal.
  params.delete("open");
  const qs = params.toString();
  history.replaceState(null, "", window.location.pathname + (qs ? "?" + qs : "") + window.location.hash);

  if (!id || !batches.some(b => b.id === id)) {
    showToast("That stock batch could not be found.");
    return;
  }
  openStockDetails(id);
})();
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

  const STATUS_FILTER = { 'out-of-stock': 'Out of Stock', 'low-stock': 'Low Stock', 'expired': 'Expired', 'expiring-soon': 'Expiring Soon' };
  function openStocks(status) {
    dropdown.style.display = 'none';
    const sel = document.getElementById('filterStatus');
    sel.value = STATUS_FILTER[status];
    document.getElementById('searchBatch').value = '';
    renderTable();
    history.replaceState(null, '', STOCKS_URL + '?status=' + status);
  }

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

  // /staff/notifications/view → dashboard?open=notifications → bell opens on the full list.
  if (new URLSearchParams(window.location.search).get('open') === 'notifications') {
    expanded = true;
    openDropdown();
  }
})();
</script>

</body>
</html>