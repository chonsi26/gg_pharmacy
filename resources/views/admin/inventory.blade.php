<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $siteName }} — Inventory</title>
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
.pill.add-badge { background: var(--red); color: var(--white); }
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
.sort-order-hint {
  margin-top: 6px;
  font-size: 11px;
  font-weight: 600;
  color: var(--orange);
  background: var(--orange-light);
  border-radius: 6px;
  padding: 6px 8px;
  line-height: 1.4;
}
.row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

/* ── View-modal product image + full-image lightbox ── */
.view-img-wrap { position: relative; width: 96px; height: 96px; flex-shrink: 0; border-radius: 10px; overflow: hidden; }
.view-img-zoom-btn {
  position: absolute; inset: 0;
  display: flex; align-items: center; justify-content: center;
  background: rgba(0,0,0,0);
  border: none; padding: 0; cursor: pointer;
  color: #fff;
  opacity: 0;
  transition: background .15s, opacity .15s;
}
.view-img-zoom-btn:hover { background: rgba(0,0,0,.45); opacity: 1; }
.view-img-zoom-btn svg { width: 20px; height: 20px; }

/* ── View Product modal — landscape layout ── */
.modal.modal-view { max-width: 920px; overflow: hidden; }
.view-layout { display: grid; grid-template-columns: 280px 1fr; align-items: stretch; }
.view-aside {
  padding: 24px;
  background: var(--surface2);
  border-right: 1px solid var(--border);
  border-radius: 0 0 0 12px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.view-aside .view-img-wrap { width: 100%; height: 200px; border-radius: 10px; }
.view-aside-name { font-size: 16.5px; font-weight: 700; color: var(--text); line-height: 1.3; }
.view-aside-generic { font-size: 12.5px; color: var(--muted); margin-top: 3px; }
.view-badges { display: flex; flex-wrap: wrap; gap: 6px; }
.view-price-block { padding-top: 4px; border-top: 1px solid var(--border); }
.view-price { font-size: 21px; font-weight: 800; color: var(--text); }
.view-price-old { text-decoration: line-through; color: var(--muted); font-size: 12.5px; margin-left: 6px; }
.view-stock-box {
  margin-top: auto;
  display: flex; align-items: center; justify-content: space-between;
  gap: 8px;
  padding: 10px 12px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 8px;
}
.view-stock-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); }
.view-stock-value { font-size: 14.5px; font-weight: 700; color: var(--text); }
.view-main {
  padding: 24px;
  max-height: 66vh;
  overflow-y: auto;
}
.view-field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 20px; }
.view-field { margin-bottom: 4px; }
.view-field.full { grid-column: 1 / -1; }
.view-field label {
  display: block;
  font-size: 10.5px; font-weight: 700;
  text-transform: uppercase; letter-spacing: .5px;
  color: var(--muted);
  margin-bottom: 5px;
}
.view-field .view-value {
  font-size: 13.5px; color: var(--text); line-height: 1.55;
}
.view-divider { grid-column: 1 / -1; border: none; border-top: 1px solid var(--border); margin: 4px 0; }
.view-empty-note { font-size: 13px; color: var(--subtle); text-align: center; padding: 20px 0; }

@media (max-width: 720px) {
  .modal.modal-view { max-width: 480px; }
  .view-layout { grid-template-columns: 1fr; }
  .view-aside { border-right: none; border-bottom: 1px solid var(--border); border-radius: 0; flex-direction: row; flex-wrap: wrap; align-items: center; }
  .view-aside .view-img-wrap { width: 84px; height: 84px; flex-shrink: 0; }
  .view-aside-text { flex: 1; min-width: 0; }
  .view-price-block { width: 100%; border-top: 1px solid var(--border); padding-top: 12px; }
  .view-stock-box { width: 100%; margin-top: 0; }
  .view-field-grid { grid-template-columns: 1fr; }
  .view-main { max-height: 60vh; }
}
.lightbox-bg {
  display: none;
  position: fixed; inset: 0;
  background: rgba(0,0,0,.8);
  z-index: 200;
  align-items: center;
  justify-content: center;
  padding: 24px;
}
.lightbox-bg.on { display: flex; }
.lightbox-close {
  position: absolute; top: 18px; right: 18px;
  background: rgba(255,255,255,.12); border: none; cursor: pointer;
  color: #fff; padding: 8px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  transition: background .15s;
}
.lightbox-close:hover { background: rgba(255,255,255,.25); }
.lightbox-close svg { width: 20px; height: 20px; }
.lightbox-img { max-width: 90vw; max-height: 85vh; object-fit: contain; border-radius: 8px; animation: fadeUp .25s ease both; }
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
  /* ── Medicine Card Grid ───────────────────────────── */
  .med-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 16px;
    padding: 4px 2px;
  }
  .med-card {
    background: var(--card, #fff);
    border: 1px solid var(--border, #e8eaf0);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow .2s, transform .2s;
    position: relative;
  }
  .med-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,.10); transform: translateY(-2px); }
  .med-img-wrap {
    width: 100%; aspect-ratio: 1 / 1;
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
  }
  .med-img-wrap svg.bg-icon {
    width: 68px; height: 68px; opacity: .18;
    position: absolute; right: 14px; bottom: 8px;
  }
  .med-img-wrap .med-img-main {
    width: 100%; height: 100%;
    object-fit: contain;
    filter: drop-shadow(0 4px 12px rgba(0,0,0,.18));
    position: relative; z-index: 1;
  }
  .med-card .pill-float { position: absolute; top: 10px; right: 10px; z-index: 2; }
  .pill-float::before { content: none !important; display: none !important; }
  .med-body { padding: 14px 16px 16px; display: flex; flex-direction: column; gap: 10px; flex: 1; }
  .med-title { font-size: 14px; font-weight: 700; color: var(--text, #111); line-height: 1.3; }
  .med-cat-row { display: flex; align-items: center; gap: 6px; }
  .med-cat-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
  .med-cat-label { font-size: 12px; color: var(--muted, #888); }
  .stock-section { display: flex; flex-direction: column; gap: 5px; }
  .stock-meta { display: flex; justify-content: space-between; align-items: center; }
  .stock-label { font-size: 11px; color: var(--subtle, #aaa); text-transform: uppercase; letter-spacing: .4px; font-weight: 500; }
  .stock-count-val { font-size: 12px; font-weight: 700; color: var(--text, #111); }
  .stock-bar-bg { height: 5px; border-radius: 99px; background: var(--border, #e8eaf0); overflow: hidden; }
  .stock-bar-fill { height: 100%; border-radius: 99px; transition: width .4s ease; }
  .med-meta-row { display: flex; justify-content: space-between; align-items: center; font-size: 12px; }
  .med-price { font-size: 15px; font-weight: 800; color: var(--text, #111); }
  .med-expiry { color: var(--muted, #888); font-size: 11px; }
  .med-expiry.critical { color: var(--red, #e53e3e); font-weight: 600; }

  /* ── Card footer buttons ── */
  .med-foot {
    padding: 0 16px 14px;
    display: flex;
    flex-direction: column;
    gap: 7px;
  }
  .btn-edit-full {
    width: 100%; padding: 8px;
    border: 1px solid var(--border, #e8f0e8); border-radius: 8px;
    background: transparent; font-size: 12px; font-weight: 600;
    color: var(--text, #111); cursor: pointer;
    transition: background .15s, border-color .15s, color .15s;
  }
  .btn-edit-full:hover {
    background: var(--hover, #f5faf6);
    border-color: var(--primary, #5cc06d);
    color: var(--primary, #5cc06b);
  }
  .btn-delete-full {
    width: 100%; padding: 8px;
    border: 1px solid #fecaca; border-radius: 8px;
    background: transparent; font-size: 12px; font-weight: 600;
    color: var(--red, #e53e3e); cursor: pointer;
    transition: background .15s, border-color .15s;
    display: flex; align-items: center; justify-content: center; gap: 5px;
  }
  .btn-delete-full:hover { background: #fff5f5; border-color: var(--red, #e53e3e); }
  .btn-delete-full svg { width: 13px; height: 13px; flex-shrink: 0; }

  /* Category colours */
  .cat-analgesic { background: #3b82f6; } .cat-antibiotic { background: #ef4444; }
  .cat-antidiabetic { background: #8b5cf6; } .cat-hypertension { background: #f59e0b; }
  .cat-supplement { background: #10b981; } .cat-antacid { background: #06b6d4; }
  .cat-antihistamine { background: #ec4899; } .cat-cardiovascular { background: #f97316; }
  .bg-analgesic { background: linear-gradient(135deg,#dbeafe,#eff6ff); }
  .bg-antibiotic { background: linear-gradient(135deg,#fee2e2,#fff5f5); }
  .bg-antidiabetic { background: linear-gradient(135deg,#ede9fe,#faf5ff); }
  .bg-hypertension { background: linear-gradient(135deg,#fef3c7,#fffbeb); }
  .bg-supplement { background: linear-gradient(135deg,#d1fae5,#f0fdf4); }
  .bg-antacid { background: linear-gradient(135deg,#cffafe,#ecfeff); }
  .bg-antihistamine { background: linear-gradient(135deg,#fce7f3,#fdf4ff); }
  .bg-cardiovascular { background: linear-gradient(135deg,#ffedd5,#fff7ed); }
  [data-theme="dark"] .med-card { background: var(--card,#18191a); border-color: var(--border,#2e3348); }
  [data-theme="dark"] .bg-analgesic      { background: linear-gradient(135deg,#1e3a5f,#1a2740); }
  [data-theme="dark"] .bg-antibiotic     { background: linear-gradient(135deg,#5f1e1e,#401a1a); }
  [data-theme="dark"] .bg-antidiabetic   { background: linear-gradient(135deg,#3b1f6e,#2a1545); }
  [data-theme="dark"] .bg-hypertension   { background: linear-gradient(135deg,#5f3e08,#3d2800); }
  [data-theme="dark"] .bg-supplement     { background: linear-gradient(135deg,#064e2a,#033318); }
  [data-theme="dark"] .bg-antacid        { background: linear-gradient(135deg,#084f5a,#033340); }
  [data-theme="dark"] .bg-antihistamine  { background: linear-gradient(135deg,#5e1540,#3d0e2b); }
  [data-theme="dark"] .bg-cardiovascular { background: linear-gradient(135deg,#5e2a06,#3d1b00); }
  [data-theme="dark"] .btn-edit-full { color: var(--text,#e0e4f0); border-color: var(--border,#2e3348); }
  [data-theme="dark"] .btn-edit-full:hover { background: var(--hover,#2a452f); }
  [data-theme="dark"] .btn-delete-full { border-color: #5f1e1e; }
  [data-theme="dark"] .btn-delete-full:hover { background: #2a1414; border-color: var(--red,#e53e3e); }
  .card-head { display:flex; align-items:center; justify-content:space-between; padding:16px 20px 12px; flex-wrap:wrap; gap:10px; }
  .card-head h3 { font-size:15px; font-weight:700; color:var(--text); margin:0; }
  .card-head-right { display:flex; align-items:center; gap:8px; }

  /* ═══════════════════════════════════════════════════════
     MODAL — Landscape on PC (≥700px) / Portrait on Mobile
     ═══════════════════════════════════════════════════════ */
  #modalBg .modal, #editModalBg .modal {
    padding: 0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }
  .modal-head-bar {
    padding: 16px 22px 14px;
    border-bottom: 1px solid var(--border, #e8eaf0);
    display: flex; align-items: center; justify-content: space-between;
    flex-shrink: 0;
  }
  .modal-landscape {
    display: flex;
    flex: 1;
    overflow: hidden;
  }

  /* ── PC Landscape (side-by-side) ── */
  @media (min-width: 700px) {
    #modalBg .modal, #editModalBg .modal { max-width: 820px; width: 90vw; }
    .modal-img-panel {
      width: 210px; min-width: 210px;
      flex-direction: column;
      justify-content: center;
      border-right: 1px solid var(--border, #e8eaf0);
      padding: 28px 20px;
      gap: 22px;
    }
    .modal-fields-panel { padding: 20px 24px 0; max-height: 60vh; }
    .modal-foot-bar { padding: 14px 24px; }
  }

  /* ── Mobile Portrait (stacked) ── */
  @media (max-width: 699px) {
    #modalBg .modal, #editModalBg .modal { max-width: 98vw; width: 98vw; }
    .modal-landscape { flex-direction: column; overflow-y: auto; max-height: 78vh; }
    .modal-img-panel {
      flex-direction: row;
      flex-shrink: 0;
      border-bottom: 1px solid var(--border, #e8eaf0);
      padding: 18px 18px;
      gap: 18px;
      align-items: center;
    }
    .modal-fields-panel { padding: 16px 18px 0; }
    .modal-foot-bar { padding: 12px 18px; }
  }

  /* Shared panel styles */
  .modal-img-panel {
    display: flex;
    background: var(--hover, #f8f9fc);
  }
  .modal-fields-panel {
    flex: 1;
    overflow-y: auto;
  }
  .modal-foot-bar {
    border-top: 1px solid var(--border, #e8eaf0);
    display: flex; justify-content: flex-end; gap: 10px;
    background: var(--card, #fff);
    flex-shrink: 0;
  }

  /* Image upload zone */
  .img-upload-zone {
    width: 128px; height: 128px;
    border-radius: 14px;
    border: 2px dashed var(--border, #d0d4e8);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 7px; cursor: pointer;
    transition: border-color .2s, background .2s;
    background: var(--card, #fff);
    position: relative; overflow: hidden;
    flex-shrink: 0;
  }
  .img-upload-zone:hover { border-color: var(--primary, #5cc05c); background: #f1fff0; }
  .img-upload-zone input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
  }
  .upload-ico { width: 30px; height: 30px; color: var(--subtle, #aaa); }
  .upload-hint { font-size: 10px; color: var(--muted, #888); text-align: center; line-height: 1.5; padding: 0 6px; }
  #imgPreview, #editImgPreview {
    position: absolute; inset: 0; width: 100%; height: 100%;
    object-fit: cover; border-radius: 12px; display: none;
  }
  .panel-label {
    font-size: 10px; font-weight: 700; color: var(--subtle, #aaa);
    letter-spacing: .6px; text-transform: uppercase;
  }
  .med-id-badge {
    font-size: 12px; font-weight: 800;
    color: var(--primary, #c05c5c);
    background: #fbeeee; border-radius: 8px;
    padding: 5px 12px; letter-spacing: .4px;
  }
  .id-block { display: flex; flex-direction: column; align-items: center; gap: 5px; text-align: center; }

  /* Mobile: id-block goes beside image */
  @media (max-width: 699px) {
    .id-block { align-items: flex-start; }
  }

  /* ── Dark mode: upload zone & modal image panel ── */
  [data-theme="dark"] .modal-img-panel {
    background: #242526;
    border-color: var(--border, #000000);
  }
  [data-theme="dark"] .img-upload-zone {
    background: #3a3b3c;
    border-color: #000000;
  }
  [data-theme="dark"] .img-upload-zone:hover {
    background: #203a20;
    border-color: var(--primary, #2b4e30);
  }
  [data-theme="dark"] .img-upload-zone .upload-ico {
    color: var(--subtle);
  }
  [data-theme="dark"] .img-upload-zone .upload-hint {
    color: var(--subtle);
  }
  [data-theme="dark"] #imgPreview,
  [data-theme="dark"] #editImgPreview {
    border: 1.5px solid var(--border, #2e3348);
    border-radius: 12px;
  }
  [data-theme="dark"] .panel-label {
    color: var(--subtle)0;
  }
  [data-theme="dark"] .med-id-badge {
    background: #381a1a;
    color: #f88181;
  }
  [data-theme="dark"] .modal-head-bar {
    border-color: var(--border, #2e3348);
  }
  [data-theme="dark"] .modal-foot-bar {
    background: var(--card, #242526);
    border-color: var(--border, #2e3348);
  }

  /* ── Delete Confirm Modal ── */
  #deleteModalBg { z-index: 1100; }
  #deleteModalBg .modal {
    max-width: 400px; width: 92vw;
    padding: 28px 28px 22px;
    border-radius: 18px;
    flex-direction: column;
    gap: 0;
    text-align: center;
  }
  .delete-ico-wrap {
    width: 56px; height: 56px; border-radius: 50%;
    background: #fff5f5; border: 1.5px solid #fecaca;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
  }
  .delete-ico-wrap svg { width: 26px; height: 26px; color: var(--red, #e53e3e); }
  .delete-modal-title { font-size: 16px; font-weight: 800; color: var(--text, #111); margin-bottom: 8px; }
  .delete-modal-sub { font-size: 13px; color: var(--muted, #888); line-height: 1.55; margin-bottom: 22px; }
  .delete-modal-sub strong { color: var(--text, #111); font-weight: 700; }
  .delete-modal-actions { display: flex; gap: 10px; }
  .delete-modal-actions .btn { flex: 1; justify-content: center; }
  .btn-danger {
    flex: 1; padding: 9px 16px; border-radius: 9px;
    background: var(--red, #e53e3e); border: none;
    color: #fff; font-size: 13px; font-weight: 700; cursor: pointer;
    transition: opacity .15s;
  }
  .btn-danger:hover { opacity: .88; }
  [data-theme="dark"] .delete-ico-wrap { background: #2a1414; border-color: #5f1e1e; }

  /* ── Card remove animation ── */
  .med-card.removing {
    animation: cardRemove .35s ease forwards;
  }
  @keyframes cardRemove {
    0%   { opacity: 1; transform: scale(1); }
    60%  { opacity: 0; transform: scale(.88); }
    100% { opacity: 0; transform: scale(.88); max-height: 0; margin: 0; padding: 0; border: none; }
  }

  /* ── Add Stock Modal ─────────────────────────────── */
  .modal-backdrop {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.48);
    display: flex; align-items: center; justify-content: center;
    z-index: 1300;
    opacity: 0; pointer-events: none;
    transition: opacity .2s;
  }
  .modal-backdrop.on { opacity: 1; pointer-events: all; }
  #addStockModal .modal {
    max-width: 480px; width: 92vw;
    padding: 28px 28px 24px;
    border-radius: 18px;
    display: flex; flex-direction: column;
    gap: 0;
    max-height: 92vh; overflow-y: auto;
    position: relative;
  }
  #addStockModal .modal h3 {
    font-size: 16px; font-weight: 800;
    color: var(--text, #111); margin: 0 0 3px;
  }
  #addStockModal .modal-close {
    position: absolute; top: 16px; right: 16px;
  }
  .modal-sub {
    font-size: 12px; color: var(--muted, #888);
    margin-bottom: 16px; line-height: 1.5;
  }
  .stock-num-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: #eef0fb; color: #5c6bc0;
    border-radius: 8px; padding: 5px 12px;
    font-size: 12px; font-weight: 700; letter-spacing: .3px;
    margin-bottom: 18px;
  }
  [data-theme="dark"] .stock-num-badge { background: #1a1f38; color: #818cf8; }
  .form-group {
    display: flex; flex-direction: column; gap: 5px;
    margin-bottom: 14px;
  }
  .form-group label {
    font-size: 11px; font-weight: 700;
    color: var(--subtle, #aaa);
    text-transform: uppercase; letter-spacing: .4px;
  }
  .form-group select,
  .form-group input[type="text"],
  .form-group input[type="number"],
  .form-group input[type="date"] {
    width: 100%; padding: 8px 11px;
    border: 1.5px solid var(--border, #e8eaf0);
    border-radius: 9px; font-size: 13px;
    background: var(--card, #fff); color: var(--text, #111);
    transition: border-color .15s;
    box-sizing: border-box;
  }
  .form-group select:focus,
  .form-group input:focus {
    outline: none; border-color: var(--primary, #5cc06d);
  }
  .form-group select:disabled {
    opacity: .75; cursor: not-allowed;
    background: var(--hover, #f5f6fa);
  }
  [data-theme="dark"] .form-group select,
  [data-theme="dark"] .form-group input[type="text"],
  [data-theme="dark"] .form-group input[type="number"],
  [data-theme="dark"] .form-group input[type="date"] {
    background: var(--input-bg, #2a2b2d);
    border-color: var(--border, #3a3b3c);
    color: var(--text, #e0e4f0);
  }
  [data-theme="dark"] .form-group select:disabled { background: #222; }
  .form-row {
    display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
  }
  @media (max-width: 480px) { .form-row { grid-template-columns: 1fr; } }
  .modal-actions {
    display: flex; justify-content: flex-end; gap: 10px;
    margin-top: 6px; padding-top: 16px;
    border-top: 1px solid var(--border, #e8eaf0);
  }
  .btn-sm {
    padding: 8px 20px; border-radius: 9px;
    font-size: 13px; font-weight: 600; cursor: pointer;
    border: 1.5px solid var(--border, #e8eaf0);
    background: transparent; color: var(--text, #111);
    transition: background .15s, border-color .15s;
  }
  .btn-sm:hover { background: var(--hover, #f5f6fa); border-color: var(--muted, #ccc); }
  .btn-sm.primary {
    background: var(--primary, #5cc06d); color: #fff;
    border-color: var(--primary, #5cc06d);
  }
  .btn-sm.primary:hover { opacity: .88; }
  [data-theme="dark"] .modal-actions { border-color: var(--border, #2e3348); }
  [data-theme="dark"] .btn-sm { color: var(--text, #e0e4f0); border-color: var(--border, #3a3b3c); }
  [data-theme="dark"] .btn-sm:hover { background: var(--hover, #2a2b2d); }

  /* ── Edit modal accent header ── */
  .edit-modal-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #eef0fb; color: var(--primary, #5c6bc0);
    border-radius: 7px; padding: 3px 10px;
    font-size: 11px; font-weight: 700; letter-spacing: .3px;
    margin-top: 4px;
  }
  .edit-modal-badge svg { width: 11px; height: 11px; }
  [data-theme="dark"] .edit-modal-badge {
    background: #1a1f38;
    color: #818cf8;
  }

  /* ── Categories / Brand header buttons ─────────────── */
  .btn-chip {
    display: inline-flex; align-items: center; gap: 6px;
    height: 32px; padding: 0 12px;
    border: 1.5px solid var(--border, #dddfe2);
    border-radius: 8px; background: transparent;
    font-size: 12px; font-weight: 600; color: var(--muted, #65676b);
    cursor: pointer; white-space: nowrap;
    transition: border-color .15s, color .15s, background .15s;
  }
  .btn-chip svg { width: 13px; height: 13px; flex-shrink: 0; }
  .btn-chip:hover { border-color: var(--primary, #5cc06d); color: var(--primary, #5cc06d); background: var(--hover, #f5faf6); }
  [data-theme="dark"] .btn-chip { border-color: var(--border, #3a3b3c); color: var(--muted, #b0b3b8); }
  [data-theme="dark"] .btn-chip:hover { background: var(--hover, #2a452f); }

  /* ── Sort by chip + dropdown ────────────────────────── */
  .sort-chip-wrap { position: relative; }
  .btn-chip.active { border-color: var(--primary, #5cc06d); color: var(--primary, #5cc06d); }
  .sort-menu {
    display: none; position: absolute; top: calc(100% + 6px); left: 0;
    min-width: 190px; background: var(--card, #fff);
    border: 1px solid var(--border, #e8eaf0); border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,.12); padding: 6px; z-index: 40;
    flex-direction: column; gap: 2px;
  }
  .sort-menu.on { display: flex; }
  .sort-menu-item {
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
    width: 100%; text-align: left; border: none; background: transparent;
    padding: 8px 9px; border-radius: 7px; font-size: 12.5px; font-weight: 600;
    color: var(--text, #111); cursor: pointer; white-space: nowrap;
  }
  .sort-menu-item:hover { background: var(--hover, #f5faf6); }
  .sort-menu-item.selected { color: var(--primary, #5cc06d); }
  .sort-menu-item.selected::after { content: '✓'; font-weight: 700; }
  [data-theme="dark"] .sort-menu { background: var(--input-bg, #2a2b2d); border-color: var(--border, #3a3b3c); }
  [data-theme="dark"] .sort-menu-item { color: var(--text, #e4e6eb); }
  [data-theme="dark"] .sort-menu-item:hover { background: var(--hover, #2a452f); }

  /* ── Manage (Categories / Brand) list modal ────────── */
  #categoriesModalBg .modal, #brandsModalBg .modal { max-width: 460px; }
  #categoryFormModalBg .modal, #brandFormModalBg .modal { max-width: 440px; }
  .manage-list { display: flex; flex-direction: column; gap: 8px; max-height: 50vh; overflow-y: auto; margin-top: 2px; }
  .manage-list-item {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 10px; border: 1px solid var(--border, #e8eaf0);
    border-radius: 10px; background: var(--card, #fff);
  }
  [data-theme="dark"] .manage-list-item { background: var(--input-bg, #2a2b2d); border-color: var(--border, #3a3b3c); }
  .manage-list-info { flex: 1; min-width: 0; }
  .manage-list-name { font-size: 13px; font-weight: 700; color: var(--text, #111); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .manage-list-sub { font-size: 11px; color: var(--muted, #888); margin-top: 2px; display: flex; gap: 5px; }
  .manage-list-actions { display: flex; gap: 4px; flex-shrink: 0; }
  .manage-icon-btn {
    width: 26px; height: 26px; border-radius: 7px;
    border: 1px solid var(--border, #e8eaf0); background: transparent;
    display: flex; align-items: center; justify-content: center;
    color: var(--muted, #888); cursor: pointer; transition: background .15s, color .15s, border-color .15s;
  }
  .manage-icon-btn svg { width: 12px; height: 12px; }
  .manage-icon-btn:hover { border-color: var(--primary, #5cc06d); color: var(--primary, #5cc06d); }
  .manage-icon-btn.danger:hover { border-color: var(--red, #e53e3e); color: var(--red, #e53e3e); }
  .manage-empty { text-align: center; font-size: 12px; color: var(--muted, #888); padding: 24px 0; }
  .cat-icon-preview {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
  }
  .cat-icon-preview.large { width: 52px; height: 52px; border-radius: 12px; margin: 4px auto 0; }
  .cat-icon-preview.large svg { width: 24px; height: 24px; }
  .brand-thumb {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; overflow: hidden;
  }
  .brand-thumb img { width: 100%; height: 100%; object-fit: cover; }
  .mini-flag {
    font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .3px;
    background: var(--hover, #f5f6fa); color: var(--muted, #888);
    padding: 1px 6px; border-radius: 5px;
  }
  [data-theme="dark"] .mini-flag { background: #333; }

  /* Small image upload zone (brand form) */
  .img-upload-zone.sm { width: 88px; height: 88px; border-radius: 12px; }
  .img-upload-zone.sm .upload-ico { width: 22px; height: 22px; }
  .img-upload-zone.sm .upload-hint { font-size: 9px; }
  .brand-img-row { display: flex; gap: 16px; margin-bottom: 4px; }
  .brand-img-field { display: flex; flex-direction: column; align-items: center; gap: 6px; }

  /* Checkbox field row */
  .field-check {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; font-weight: 600; color: var(--text, #111);
    margin-bottom: 14px; cursor: pointer;
  }
  .field-check input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--primary, #5cc06d); cursor: pointer; }

  /* Color picker input */
  .field input[type="color"] {
    padding: 4px; cursor: pointer;
  }
</style>
</head>
<body>
<div class="toast" id="toast">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="toastMsg">Done.</span>
</div>
<div class="overlay" id="overlay"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
  <a class="sidebar-logo" href="{{ route('admin.dashboard') }}">
    <div class="sidebar-logo-icon"><img src="{{ $logo2 ? asset($logo2) : '' }}" alt="{{ $siteName }}"></div>
    <div class="sidebar-logo-text"><strong>{{ $siteName }}</strong><span>Admin Portal</span></div>
  </a>
  <div class="sidebar-section-label">Main</div>
  <a class="nav-item" href="{{ route('admin.dashboard') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    Dashboard
  </a>
  <a class="nav-item active" href="{{ route('admin.inventory') }}">
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

<!-- Header -->
<header class="header">
  <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="header-title">Inventory</div>
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

    <button class="icon-btn" id="themeBtn" title="Toggle dark mode"><span id="themeIco"></span></button>
  </div>
</header>

<!-- Main -->
<main class="main">
  <div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <div style="font-size:20px;font-weight:800;color:var(--text);">Inventory Management</div>
      <div style="font-size:13px;color:var(--muted);margin-top:3px;">Manage all products and stock levels.</div>
    </div>
    <button class="btn primary" onclick="openModal()">+ Add Product</button>
  </div>

  <div class="card" style="padding-bottom:20px;">
    <div class="card-head">
      <h3>All Medicines</h3>
      <div class="card-head-right">
        <div class="sort-chip-wrap" id="sortChipWrap">
          <button class="btn-chip" onclick="toggleSortMenu()" id="sortChipBtn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M6 12h12M10 18h4"/></svg>
            <span id="sortChipLabel">Sort by</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:10px;height:10px;margin-left:1px;"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="sort-menu" id="sortMenu">
            <button class="sort-menu-item" data-by="alphabetical" onclick="sortProducts('alphabetical')">A-Z</button>
            <button class="sort-menu-item" data-by="date" onclick="sortProducts('date')">Date</button>
            <button class="sort-menu-item" data-by="category" onclick="sortProducts('category')">Category</button>
            <button class="sort-menu-item" data-by="brand" onclick="sortProducts('brand')">Brand</button>
          </div>
        </div>
        <button class="btn-chip" onclick="openCategoriesModal()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3.24H4a1 1 0 0 0-1 1v5.59a2 2 0 0 0 .59 1.41l9.58 9.59a2 2 0 0 0 2.83 0l4.59-4.59a2 2 0 0 0 0-2.83z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>
          Add Categories
        </button>
        <button class="btn-chip" onclick="openBrandsModal()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3.24H4a1 1 0 0 0-1 1v5.59a2 2 0 0 0 .59 1.41l9.58 9.59a2 2 0 0 0 2.83 0l4.59-4.59a2 2 0 0 0 0-2.83z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>
          Add brand
        </button>
        <div class="header-search" style="height:32px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;color:var(--subtle);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="cardSearch" placeholder="Search…" style="width:130px;" oninput="filterCards(this.value)">
        </div>
      </div>
    </div>
    <br>
    <div class="med-grid" id="medGrid" style="padding:0 16px;">

      @forelse($products as $product)
        @php
          $category   = $product->category;
          $brand      = $product->brand;
          $stock      = $product->stocks->first();
          $qty        = $stock->quantity ?? 0;
          $catColor   = $category->icon_color ?? '#6b7280';
          $catBg      = $category->bg_color ?? '#f3f4f6';
          $catName    = $category->name ?? 'Uncategorized';
          $searchKey  = strtolower($product->name.' '.($product->form ?? '').' '.$catName.' '.($brand->name ?? ''));
          $barColor   = $qty <= 10 ? 'var(--red)' : ($qty <= 50 ? 'var(--orange)' : 'var(--green)');
          $barWidth   = min($qty, 100);
          $badgeClass = $product->badge_type === 'sale-badge' ? 'warning' : 'ok';
        @endphp
        <div class="med-card" data-name="{{ $searchKey }}" onclick="openViewModal(this)" style="cursor:pointer;"
             data-id="{{ $product->id }}"
             data-generic-name="{{ $product->generic_name }}"
             data-form="{{ $product->form }}"
             data-brand-name="{{ $brand->name ?? '' }}"
             data-brand-id="{{ $product->brand_id }}"
             data-medicine="{{ $product->name }}"
             data-cat="{{ $product->category_id }}"
             data-section-id="{{ $product->section_id }}"
             data-sort-order="{{ $product->sort_order }}"
             data-old-price="{{ $product->old_price }}"
             data-origin="{{ $product->origin }}"
             data-price="{{ $product->price }}"
             data-desc="{{ $product->description }}"
             data-ingredients="{{ $product->ingredients }}"
             data-warnings="{{ $product->warnings }}"
             data-width="{{ $product->width }}"
             data-height="{{ $product->height }}"
             data-depth="{{ $product->depth }}"
             data-requires-rx="{{ $product->requires_prescription ? 'true' : 'false' }}"
             @if($product->badge) data-badge-text="{{ $product->badge }}" data-badge-type="{{ $product->badge_type }}" @endif>
          <div class="med-img-wrap" style="background: {{ $product->image ? '#fff' : 'linear-gradient(135deg, '.$catBg.', '.$catBg.')' }};">
            @if($product->badge)
              <span class="pill-float pill {{ $badgeClass }}" style="cursor:pointer;" onclick="event.stopPropagation(); openBadgeModal(this)">{{ $product->badge }}</span>
            @else
              <span class="pill-float pill add-badge" style="cursor:pointer;display:inline-flex;align-items:center;gap:4px;" onclick="event.stopPropagation(); openBadgeModal(this)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:10px;height:10px;flex-shrink:0;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Add Badge</span>
            @endif
            @if($product->image)
              <img class="med-img-main" src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:contain;border-radius:8px;">
            @else
              <svg class="med-img-main" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                <circle cx="40" cy="40" r="24" fill="{{ $catColor }}"/>
                <text x="40" y="45" text-anchor="middle" font-size="11" font-family="Inter,sans-serif" font-weight="700" fill="white">{{ strtoupper(substr($product->name, 0, 2)) }}</text>
              </svg>
            @endif
          </div>
          <div class="med-body">
            <div class="med-title">{{ $product->name }}</div>
            <div class="med-cat-row"><span class="med-cat-dot" style="background:{{ $catColor }};"></span><span class="med-cat-label">{{ $catName }}</span></div>
            <div class="stock-section">
              <div class="stock-meta"><span class="stock-label">Stock</span><span class="stock-count-val">{{ $qty }} units</span></div>
              <div class="stock-bar-bg"><div class="stock-bar-fill" style="width:{{ $barWidth }}%;background:{{ $barColor }};"></div></div>
            </div>
            <div class="med-meta-row">
              <span class="med-price">
                &#8369;{{ number_format($product->price, 2) }}
                @if($product->old_price)
                  <span style="text-decoration:line-through;color:var(--muted);font-weight:500;font-size:11px;margin-left:4px;">&#8369;{{ number_format($product->old_price, 2) }}</span>
                @endif
              </span>
            </div>
          </div>
          <div class="med-foot">
            <button class="btn-edit-full" onclick="event.stopPropagation(); openEditModal(this)">Edit</button>
            <button class="btn-delete-full" onclick="event.stopPropagation(); openDeleteModal(this)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
              Delete
            </button>
          </div>
        </div>
      @empty
        <div class="manage-empty">No products yet. Click "+ Add Products" to get started.</div>
      @endforelse

    </div><!-- /med-grid -->
  </div><!-- /card -->
</main>

<!-- Suggestions for the typable Brand / Category fields (kept in sync by refreshComboOptions()) -->
<datalist id="brandOptions">
  @foreach($brands as $brand)
    <option value="{{ $brand->name }}"></option>
  @endforeach
</datalist>
<datalist id="categoryOptions">
  @foreach($categories as $cat)
    <option value="{{ $cat->name }}"></option>
  @endforeach
</datalist>

<!-- ════════════════════════════════════════════════════
     ADD PRODUCT MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="modalBg">
  <div class="modal">
    <div class="modal-head-bar">
      <div>
        <div style="font-size:15px;font-weight:700;color:var(--text);">Add New Medicine</div>
        <div style="font-size:11px;color:var(--muted);margin-top:2px;">Fill in the product details</div>
      </div>
      <button class="modal-close" onclick="closeModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-landscape">
      <div class="modal-img-panel">
        <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
          <div class="panel-label">PRODUCT IMAGE</div>
          <div class="img-upload-zone">
            <input type="file" accept="image/*" id="mImage" onchange="previewImg(this,'imgPreview')">
            <svg class="upload-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="4"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <polyline points="21 15 16 10 5 21"/>
            </svg>
            <span class="upload-hint">Click to upload<br>PNG · JPG · WEBP</span>
            <img id="imgPreview" alt="Preview">
          </div>
        </div>
        <div class="id-block">
          <div class="panel-label">MEDICINE ID</div>
          <div class="med-id-badge" id="autoMedId">MED-0001</div>
          <div style="font-size:10px;color:var(--subtle);">Auto-generated · PK</div>
        </div>
        <div class="field" style="width:100%;margin-top:10px;">
          <label>Section</label>
          <select id="mSection" onchange="handleSectionChange('m')">
            <option value="">— Select —</option>
            @foreach($sections as $sec)
              <option value="{{ $sec->id }}">{{ $sec->label }}</option>
            @endforeach
          </select>
        </div>
        <div class="field" style="width:100%;margin-top:10px;">
          <label>Sort Order</label>
          <input type="number" min="0" step="1" id="mSortOrder" placeholder="Select a section first" oninput="checkSortOrderConflict('m')">
          <div class="sort-order-hint" id="mSortOrderHint" style="display:none;"></div>
        </div>
      </div>
      <div style="display:flex;flex-direction:column;flex:1;min-width:0;overflow:hidden;">
        <div class="modal-fields-panel">
          <div class="field">
            <label>Medicine Name</label>
            <input type="text" placeholder="e.g. Paracetamol 500mg" id="mName">
          </div>
          <div class="row-2">
            <div class="field">
              <label>Generic Name</label>
              <input type="text" placeholder="e.g. Paracetamol" id="mGenericName">
            </div>
            <div class="field">
              <label>Brand</label>
              <input type="text" id="mBrandText" list="brandOptions" placeholder="Type or pick a brand" autocomplete="off"
                     oninput="syncCombo('m','brand')" onchange="handleComboCommit('m','brand')">
              <input type="hidden" id="mBrand">
            </div>
          </div>
          <div class="row-2">
            <div class="field">
              <label>Category <span style="font-size:10px;font-weight:400;text-transform:none;color:var(--subtle);">(FK)</span></label>
              <input type="text" id="mCatText" list="categoryOptions" placeholder="Type or pick a category" autocomplete="off"
                     oninput="syncCombo('m','category')" onchange="handleComboCommit('m','category')">
              <input type="hidden" id="mCat">
            </div>
            <div class="field">
              <label>Form</label>
              <select id="mForm" onchange="applyFormProfile('m')">
                <option value="">— Select —</option>
                <optgroup label="Product Types">
                  <option>Medicine</option>
                  <option>Vitamins</option>
                  <option>Alcohol</option>
                  <option>Shampoo</option>
                  <option>Conditioner</option>
                  <option>Soap</option>
                  <option>Body Wash</option>
                  <option>Lotion</option>
                  <option>Skincare</option>
                  <option>Sunscreen</option>
                  <option>Makeup</option>
                  <option>Perfume</option>
                  <option>Deodorant</option>
                  <option>Toothpaste</option>
                  <option>Toothbrush</option>
                  <option>Mouthwash</option>
                  <option>Hair Color</option>
                  <option>Hair Treatment</option>
                  <option>Feminine Care</option>
                  <option>Baby Care</option>
                  <option>Diapers</option>
                  <option>Supplements</option>
                  <option>Medical Supplies</option>
                  <option>Household Cleaning</option>
                  <option>Food and Beverages</option>
                </optgroup>
                <optgroup label="Medicine Dosage Forms">
                  <option>Tablet</option>
                  <option>Capsule</option>
                  <option>Syrup</option>
                  <option>Suspension</option>
                  <option>Injection</option>
                  <option>Drops</option>
                  <option>Cream / Ointment</option>
                  <option>Patch</option>
                </optgroup>
              </select>
            </div>
          </div>
          <div class="row-2">
            <div class="field">
              <label>Origin / Manufacturer</label>
              <input type="text" placeholder="e.g. Unilab, Philippines" id="mOrigin">
            </div>
            <div class="field">
              <label>Selling Price (₱)</label>
              <input type="number" placeholder="0.00" step="0.01" min="0" id="mPrice">
            </div>
          </div>
          <div class="field">
            <label>Description <span style="font-size:10px;font-weight:400;text-transform:none;color:var(--subtle);">(optional)</span></label>
            <textarea placeholder="Indications, dosage, warnings, or any relevant notes…" id="mDesc" style="min-height:68px;resize:vertical;"></textarea>
          </div>
          <div class="field">
            <label>Ingredients <span style="font-size:10px;font-weight:400;text-transform:none;color:var(--subtle);">(optional)</span></label>
            <textarea placeholder="Active and inactive ingredients…" id="mIngredients" style="min-height:68px;resize:vertical;"></textarea>
          </div>
          <div class="field">
            <label>Warnings <span style="font-size:10px;font-weight:400;text-transform:none;color:var(--subtle);">(optional)</span></label>
            <textarea placeholder="Precautions, contraindications, or safety warnings…" id="mWarnings" style="min-height:68px;resize:vertical;"></textarea>
          </div>
          <div class="row-3" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
            <div class="field">
              <label>Width (cm)</label>
              <input type="number" placeholder="0.00" step="0.01" min="0" id="mWidth">
            </div>
            <div class="field">
              <label>Height (cm)</label>
              <input type="number" placeholder="0.00" step="0.01" min="0" id="mHeight">
            </div>
            <div class="field">
              <label>Depth (cm)</label>
              <input type="number" placeholder="0.00" step="0.01" min="0" id="mDepth">
            </div>
          </div>
          <label class="field-check" id="mRxWrap" style="margin-bottom:6px;">
            <input type="checkbox" id="mRequiresPrescription"> Requires Prescription
          </label>
        </div>
        <div class="modal-foot-bar">
          <button class="btn" onclick="closeModal()">Cancel</button>
          <button class="btn primary" onclick="saveMedicine()">Save Medicine</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════
     EDIT MEDICINE MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="editModalBg">
  <div class="modal">
    <div class="modal-head-bar">
      <div>
        <div style="font-size:15px;font-weight:700;color:var(--text);">Edit Medicine</div>
        <div class="edit-modal-badge" id="editMedIdBadge">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:11px;height:11px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          MED-0000
        </div>
      </div>
      <button class="modal-close" onclick="closeEditModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-landscape">
      <div class="modal-img-panel">
        <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
          <div class="panel-label">PRODUCT IMAGE</div>
          <div class="img-upload-zone">
            <input type="file" accept="image/*" id="eImage" onchange="previewImg(this,'editImgPreview')">
            <svg class="upload-ico" id="editUploadIco" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="4"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <polyline points="21 15 16 10 5 21"/>
            </svg>
            <span class="upload-hint" id="editUploadHint">Click to change<br>PNG · JPG · WEBP</span>
            <img id="editImgPreview" alt="Preview">
          </div>
        </div>
        <div class="id-block">
          <div class="panel-label">MEDICINE ID</div>
          <div class="med-id-badge" id="editMedIdDisplay">MED-0000</div>
          <div style="font-size:10px;color:var(--subtle);">Read-only · PK</div>
        </div>
        <div class="field" style="width:100%;margin-top:10px;">
          <label>Section</label>
          <select id="eSection" onchange="handleSectionChange('e')">
            <option value="">— Select —</option>
            @foreach($sections as $sec)
              <option value="{{ $sec->id }}">{{ $sec->label }}</option>
            @endforeach
          </select>
        </div>
        <div class="field" style="width:100%;margin-top:10px;">
          <label>Sort Order</label>
          <input type="number" min="0" step="1" id="eSortOrder" oninput="checkSortOrderConflict('e')">
          <div class="sort-order-hint" id="eSortOrderHint" style="display:none;"></div>
        </div>
      </div>
      <div style="display:flex;flex-direction:column;flex:1;min-width:0;overflow:hidden;">
        <div class="modal-fields-panel">
          <div class="field">
            <label>Medicine Name</label>
            <input type="text" placeholder="e.g. Paracetamol 500mg" id="eName">
          </div>
          <div class="row-2">
            <div class="field">
              <label>Generic Name</label>
              <input type="text" placeholder="e.g. Paracetamol" id="eGenericName">
            </div>
            <div class="field">
              <label>Brand</label>
              <input type="text" id="eBrandText" list="brandOptions" placeholder="Type or pick a brand" autocomplete="off"
                     oninput="syncCombo('e','brand')" onchange="handleComboCommit('e','brand')">
              <input type="hidden" id="eBrand">
            </div>
          </div>
          <div class="row-2">
            <div class="field">
              <label>Category <span style="font-size:10px;font-weight:400;text-transform:none;color:var(--subtle);">(FK)</span></label>
              <input type="text" id="eCatText" list="categoryOptions" placeholder="Type or pick a category" autocomplete="off"
                     oninput="syncCombo('e','category')" onchange="handleComboCommit('e','category')">
              <input type="hidden" id="eCat">
            </div>
            <div class="field">
              <label>Form</label>
              <select id="eForm" onchange="applyFormProfile('e')">
                <option value="">— Select —</option>
                <optgroup label="Product Types">
                  <option>Medicine</option>
                  <option>Vitamins</option>
                  <option>Alcohol</option>
                  <option>Shampoo</option>
                  <option>Conditioner</option>
                  <option>Soap</option>
                  <option>Body Wash</option>
                  <option>Lotion</option>
                  <option>Skincare</option>
                  <option>Sunscreen</option>
                  <option>Makeup</option>
                  <option>Perfume</option>
                  <option>Deodorant</option>
                  <option>Toothpaste</option>
                  <option>Toothbrush</option>
                  <option>Mouthwash</option>
                  <option>Hair Color</option>
                  <option>Hair Treatment</option>
                  <option>Feminine Care</option>
                  <option>Baby Care</option>
                  <option>Diapers</option>
                  <option>Supplements</option>
                  <option>Medical Supplies</option>
                  <option>Household Cleaning</option>
                  <option>Food and Beverages</option>
                </optgroup>
                <optgroup label="Medicine Dosage Forms">
                  <option>Tablet</option>
                  <option>Capsule</option>
                  <option>Syrup</option>
                  <option>Suspension</option>
                  <option>Injection</option>
                  <option>Drops</option>
                  <option>Cream / Ointment</option>
                  <option>Patch</option>
                </optgroup>
              </select>
            </div>
          </div>
          <div class="row-2">
            <div class="field">
              <label>Origin / Manufacturer</label>
              <input type="text" placeholder="e.g. Unilab, Philippines" id="eOrigin">
            </div>
            <div class="field">
              <label>Selling Price (₱)</label>
              <input type="number" placeholder="0.00" step="0.01" min="0" id="ePrice">
            </div>
          </div>
          <div class="field">
            <label>Description <span style="font-size:10px;font-weight:400;text-transform:none;color:var(--subtle);">(optional)</span></label>
            <textarea placeholder="Indications, dosage, warnings, or any relevant notes…" id="eDesc" style="min-height:68px;resize:vertical;"></textarea>
          </div>
          <div class="field">
            <label>Ingredients <span style="font-size:10px;font-weight:400;text-transform:none;color:var(--subtle);">(optional)</span></label>
            <textarea placeholder="Active and inactive ingredients…" id="eIngredients" style="min-height:68px;resize:vertical;"></textarea>
          </div>
          <div class="field">
            <label>Warnings <span style="font-size:10px;font-weight:400;text-transform:none;color:var(--subtle);">(optional)</span></label>
            <textarea placeholder="Precautions, contraindications, or safety warnings…" id="eWarnings" style="min-height:68px;resize:vertical;"></textarea>
          </div>
          <div class="row-3" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
            <div class="field">
              <label>Width (cm)</label>
              <input type="number" placeholder="0.00" step="0.01" min="0" id="eWidth">
            </div>
            <div class="field">
              <label>Height (cm)</label>
              <input type="number" placeholder="0.00" step="0.01" min="0" id="eHeight">
            </div>
            <div class="field">
              <label>Depth (cm)</label>
              <input type="number" placeholder="0.00" step="0.01" min="0" id="eDepth">
            </div>
          </div>
          <label class="field-check" id="eRxWrap" style="margin-bottom:6px;">
            <input type="checkbox" id="eRequiresPrescription"> Requires Prescription
          </label>
        </div>
        <div class="modal-foot-bar">
          <button class="btn" onclick="closeEditModal()">Cancel</button>
          <button class="btn primary" onclick="saveEdit()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════
     VIEW PRODUCT MODAL (read-only, populated from
     AdminInventoryController@show)
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="viewModalBg">
  <div class="modal modal-view">
    <div class="modal-head">
      <h3 id="viewModalTitle">Product Details</h3>
      <button class="modal-close" onclick="closeViewModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div id="viewModalBody">
      <!-- filled by renderViewModal() -->
    </div>
    <div class="modal-foot">
      <button class="btn" onclick="closeViewModal()">Close</button>
      <button class="btn primary" id="viewModalEditBtn" onclick="editFromViewModal()">Edit Product</button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════
     FULL PRODUCT IMAGE LIGHTBOX
     ════════════════════════════════════════════════════ -->
<div class="lightbox-bg" id="imageLightboxBg">
  <button class="lightbox-close" onclick="closeImageLightbox()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
  </button>
  <img class="lightbox-img" id="imageLightboxImg" src="" alt="">
</div>

<!-- ════════════════════════════════════════════════════
     DELETE CONFIRM MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="deleteModalBg">
  <div class="modal">
    <div class="delete-ico-wrap">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="3 6 5 6 21 6"/>
        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
        <path d="M10 11v6M14 11v6"/>
        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
      </svg>
    </div>
    <div class="delete-modal-title">Delete Medicine?</div>
    <div class="delete-modal-sub">
      You're about to permanently remove<br>
      <strong id="deleteTargetName">—</strong> from inventory.<br>
      This action cannot be undone.
    </div>
    <div class="delete-modal-actions">
      <button class="btn" onclick="closeDeleteModal()">Cancel</button>
      <button class="btn-danger" onclick="confirmDelete()">Yes, Delete</button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════
     ADD STOCK MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-backdrop" id="addStockModal">
  <div class="modal">
    <button class="modal-close" id="closeStockModal" title="Close">&times;</button>
    <h3>Add Product Stock</h3>
    <div class="modal-sub">Fill in the details to log a new batch entry.</div>

    <div class="stock-num-badge">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
      Stock No: <span id="generatedStockNo">—</span>
    </div>

    <div class="form-group">
      <label>Medicine *</label>
      <select id="modalMedicine">
        <option value="">— Select medicine —</option>
      </select>
    </div>
    <div class="form-group">
      <label>Supplier</label>
      <input type="text" name="supplier" placeholder="Enter Supplier Name (Optional)" id="modalSupplier">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Quantity (units) *</label>
        <input type="number" id="modalQty" placeholder="e.g. 200" min="1">
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
        <label>Expiry Date *</label>
        <input type="date" id="modalExpiry">
      </div>
    </div>

    <div class="modal-actions">
      <button class="btn-sm" id="cancelStockModal">Cancel</button>
      <button class="btn-sm primary" id="confirmAddStock">Add Stock</button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════
     CATEGORIES LIST MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="categoriesModalBg">
  <div class="modal">
    <div class="modal-head">
      <h3>Categories</h3>
      <button class="modal-close" onclick="closeCategoriesModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <button class="btn primary" style="width:100%;margin-bottom:14px;justify-content:center;display:flex;align-items:center;gap:6px;" onclick="openCategoryForm()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Category
      </button>
      <div class="manage-list" id="categoriesList"></div>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════
     CATEGORY ADD/EDIT MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="categoryFormModalBg">
  <div class="modal">
    <div class="modal-head">
      <h3 id="categoryFormTitle">Add Category</h3>
      <button class="modal-close" onclick="closeCategoryForm()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="field" style="text-align:center;margin-bottom:2px;">
        <label>Preview</label>
        <div class="cat-icon-preview large" id="catPreviewBox">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l3 2"/></svg>
        </div>
      </div>
      <div class="field">
        <label>Name</label>
        <input type="text" id="catName" placeholder="e.g. Analgesic">
      </div>
      <div class="field">
        <label>Icon Class</label>
        <input type="text" id="catIconClass" placeholder="e.g. icon-pill">
      </div>
      <div class="row-2">
        <div class="field">
          <label>Icon Color</label>
          <input type="color" id="catIconColor" value="#3b82f6">
        </div>
        <div class="field">
          <label>Background Color</label>
          <input type="color" id="catBgColor" value="#eff6ff">
        </div>
      </div>
      <div class="field">
        <label>Status</label>
        <select id="catStatus">
          <option>Active</option>
          <option>Inactive</option>
        </select>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn" onclick="closeCategoryForm()">Cancel</button>
      <button class="btn primary" onclick="saveCategory()">Save Category</button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════
     BRANDS LIST MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="brandsModalBg">
  <div class="modal">
    <div class="modal-head">
      <h3>Brand</h3>
      <button class="modal-close" onclick="closeBrandsModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <button class="btn primary" style="width:100%;margin-bottom:14px;justify-content:center;display:flex;align-items:center;gap:6px;" onclick="openBrandForm()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Brand
      </button>
      <div class="manage-list" id="brandsList"></div>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════
     BRAND ADD/EDIT MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="brandFormModalBg">
  <div class="modal">
    <div class="modal-head">
      <h3 id="brandFormTitle">Add Brand</h3>
      <button class="modal-close" onclick="closeBrandForm()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="field">
        <label>Name</label>
        <input type="text" id="brandName" placeholder="e.g. Biogesic">
      </div>
      <div class="brand-img-row">
        <div class="brand-img-field">
          <label class="panel-label">Ticker Image</label>
          <div class="img-upload-zone sm">
            <input type="file" accept="image/*" id="brandTickerImage" onchange="previewImg(this,'brandTickerPreview')">
            <svg class="upload-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="4"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <span class="upload-hint">Upload</span>
            <img id="brandTickerPreview" alt="Preview">
          </div>
        </div>
        <div class="brand-img-field">
          <label class="panel-label">Featured Image</label>
          <div class="img-upload-zone sm">
            <input type="file" accept="image/*" id="brandFeaturedImage" onchange="previewImg(this,'brandFeaturedPreview')">
            <svg class="upload-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="4"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <span class="upload-hint">Upload</span>
            <img id="brandFeaturedPreview" alt="Preview">
          </div>
        </div>
      </div>
      <div class="field">
        <label>Featured Color</label>
        <input type="color" id="brandFeaturedColor" value="#5cc06d">
      </div>
      <label class="field-check">
        <input type="checkbox" id="brandShowTicker"> Show in Ticker
      </label>
      <label class="field-check">
        <input type="checkbox" id="brandShowFeatured"> Show in Featured
      </label>
      <div class="field">
        <label>Status</label>
        <select id="brandStatus">
          <option>Active</option>
          <option>Inactive</option>
        </select>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn" onclick="closeBrandForm()">Cancel</button>
      <button class="btn primary" onclick="saveBrand()">Save Brand</button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════
     ADD BADGE MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="badgeModalBg">
  <div class="modal">
    <div class="modal-head">
      <h3 id="badgeFormTitle">Add Badge</h3>
      <button class="modal-close" onclick="closeBadgeModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="field">
        <label>Badge</label>
        <input type="text" id="badgeText" placeholder="e.g. Best Seller">
      </div>
      <div class="field">
        <label>Badge Type</label>
        <select id="badgeType">
          <option value="most-sold">Most Sold</option>
          <option value="sale-badge">Sale Badge</option>
        </select>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn" onclick="closeBadgeModal()">Cancel</button>
      <button class="btn primary" onclick="saveBadge()">Save Badge</button>
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

  // "View all notifications" → full list page.
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
/* ── Add modal ─────────────────────────────────────── */
function openModal()  { applyFormProfile('m'); document.getElementById('modalBg').classList.add('on'); }
function closeModal() { document.getElementById('modalBg').classList.remove('on'); }
document.getElementById('modalBg').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeModal();
});

/* ── Image preview (shared) ─────────────────────────── */
function previewImg(input, previewId) {
  if (!input.files || !input.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => {
    const preview = document.getElementById(previewId);
    preview.src = e.target.result;
    preview.style.display = 'block';
    const zone = input.closest('.img-upload-zone');
    const ico  = zone.querySelector('.upload-ico');
    const hint = zone.querySelector('.upload-hint');
    if (ico)  ico.style.display  = 'none';
    if (hint) hint.style.display = 'none';
  };
  reader.readAsDataURL(input.files[0]);
}

/* ── Auto-generate Medicine ID ───────────────────────── */
(function generateId() {
  const n = String(Math.floor(Math.random() * 9000) + 1000);
  document.getElementById('autoMedId').textContent = 'MED-' + n;
})();

/* ══════════════════════════════════════════════════════
   BACKEND HELPERS  (AdminInventoryController)
   ══════════════════════════════════════════════════════ */
const INVENTORY_URL        = "{{ route('admin.inventory') }}";
const INVENTORY_SEARCH_URL = "{{ route('admin.inventory.search') }}";
const INVENTORY_SORT_URL   = "{{ route('admin.inventory.sort') }}";
const CSRF_TOKEN           = document.querySelector('meta[name="csrf-token"]').content;

/**
 * Map of section_id → sort_order → { id, name } for every product currently
 * in the grid. Sort order is scoped per section, since each section has its
 * own ordering — a "taken" slot in one section doesn't affect another.
 */
const SECTION_SORT_MAP = {!! json_encode(
    $products->groupBy('section_id')->map(
        fn ($group) => $group->mapWithKeys(fn ($p) => [(int) $p->sort_order => ['id' => $p->id, 'name' => $p->name]])
    )
) !!};

/**
 * POST a FormData payload and return the decoded JSON body.
 * `method` may be PUT/DELETE — Laravel picks it up via _method spoofing,
 * which is what lets us keep sending multipart file uploads.
 */
async function sendForm(url, formData, method = 'POST') {
  if (method !== 'POST') formData.append('_method', method);

  const res = await fetch(url, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': CSRF_TOKEN,
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: formData,
  });

  const body = await res.json().catch(() => ({}));

  if (!res.ok) {
    const firstError = body.errors ? Object.values(body.errors)[0][0] : (body.message || 'Something went wrong.');
    throw new Error(firstError);
  }
  return body;
}

/* ══════════════════════════════════════════════════════
   VIEW PRODUCT MODAL
   Clicking a card fetches full details from
   AdminInventoryController@show (GET /admin/inventory/{product})
   rather than re-reading the card's data-* attributes, so fields
   the card doesn't carry (usage/directions, stock qty, etc.) are
   always there too.
   ══════════════════════════════════════════════════════ */
let _viewTargetCard = null;

async function openViewModal(card) {
  _viewTargetCard = card;
  const id = card.dataset.id;

  document.getElementById('viewModalTitle').textContent = card.dataset.medicine || 'Product Details';
  document.getElementById('viewModalBody').innerHTML =
    '<div style="padding:60px 24px;text-align:center;color:var(--muted);font-size:13px;">Loading…</div>';
  document.getElementById('viewModalBg').classList.add('on');

  try {
    const res = await fetch(`${INVENTORY_URL}/${id}`, {
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    });
    const body = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(body.message || 'Could not load this product.');
    renderViewModal(body.product);
  } catch (err) {
    document.getElementById('viewModalBody').innerHTML =
      `<div style="padding:50px 24px;text-align:center;color:var(--red);font-size:13px;">${err.message}</div>`;
  }
}

function renderViewModal(p) {
  document.getElementById('viewModalTitle').textContent = p.name;

  const esc = s => (s ?? '').toString()
    .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

  const priceHtml = `
    <span class="view-price">${p.formatted_price}</span>
    ${p.has_discount ? `
      <span class="view-price-old">${p.formatted_old_price}</span>
      <span class="pill warning" style="margin-left:6px;">-${p.discount_percent}%</span>
    ` : ''}
  `;

  // Short facts sit two-up in the grid; long free-text fields span the full width.
  const field = (label, value, { full = false, multiline = false } = {}) => value
    ? `<div class="view-field${full ? ' full' : ''}">
         <label>${label}</label>
         <div class="view-value"${multiline ? ' style="white-space:pre-wrap;"' : ''}>${esc(value)}</div>
       </div>`
    : '';

  const divider = '<hr class="view-divider">';

  const dims = p.has_dimensions
    ? [p.width, p.height, p.depth].filter(v => v !== null && v !== undefined).join(' × ') + ' cm'
    : null;

  const topFacts = [
    field('Form', p.form),
    field('Origin / Manufacturer', p.origin),
    field('Dimensions (W × H × D)', dims),
  ].filter(Boolean).join('');

  const longSections = [
    field('Description', p.description, { full: true, multiline: true }),
    field('Directions / Usage', p.product_usage, { full: true, multiline: true }),
    field('Ingredients', p.ingredients, { full: true, multiline: true }),
    field('Warnings', p.warnings, { full: true, multiline: true }),
  ].filter(Boolean).join(divider);

  const mainContent = [topFacts, longSections].filter(Boolean).join(topFacts && longSections ? divider : '')
    || '<div class="view-empty-note">No additional details on file for this product.</div>';

  document.getElementById('viewModalBody').innerHTML = `
    <div class="view-layout">
      <div class="view-aside">
        <div class="view-img-wrap" style="background:linear-gradient(135deg, ${p.category_bg}, ${p.category_bg});display:flex;align-items:center;justify-content:center;">
          ${p.image_url
            ? `<img src="${p.image_url}" alt="${esc(p.name)}" style="width:100%;height:100%;object-fit:contain;">
               <button type="button" class="view-img-zoom-btn" title="View full image" data-img="${esc(p.image_url)}" data-alt="${esc(p.name)}" onclick="openImageLightbox(this.dataset.img, this.dataset.alt)">
                 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
               </button>`
            : `<svg viewBox="0 0 80 80" width="60" height="60"><circle cx="40" cy="40" r="24" fill="${p.category_color}"/><text x="40" y="45" text-anchor="middle" font-size="11" font-family="Inter,sans-serif" font-weight="700" fill="white">${esc(p.name.slice(0, 2).toUpperCase())}</text></svg>`}
        </div>

        <div class="view-aside-text">
          <div class="view-aside-name">${esc(p.name)}</div>
          ${p.generic_name ? `<div class="view-aside-generic">${esc(p.generic_name)}</div>` : ''}
          <div class="view-badges" style="margin-top:8px;">
            <span class="pill" style="background:${p.category_bg};color:${p.category_color};">${esc(p.category_name)}</span>
            ${p.form ? `<span class="pill" style="background:var(--bg-soft,#f3f4f6);color:var(--text,#374151);">${esc(p.form)}</span>` : ''}
            ${p.brand_name ? `<span class="pill ok">${esc(p.brand_name)}</span>` : ''}
            ${p.section_label ? `<span class="pill blue">${esc(p.section_label)}</span>` : ''}
            ${p.requires_prescription ? `<span class="pill warning">Rx Only</span>` : ''}
            ${!p.is_active ? `<span class="pill danger">Inactive</span>` : ''}
          </div>
        </div>

        <div class="view-price-block">${priceHtml}</div>

        <div class="view-stock-box">
          <span class="view-stock-label">Stock on hand</span>
          <span class="view-stock-value">${p.stock_quantity} units</span>
        </div>
      </div>

      <div class="view-main">
        <div class="view-field-grid">
          ${mainContent}
        </div>
      </div>
    </div>
  `;
}

function closeViewModal() {
  document.getElementById('viewModalBg').classList.remove('on');
  _viewTargetCard = null;
}
document.getElementById('viewModalBg').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeViewModal();
});

/** Full-size product image lightbox, opened from the View Product modal. */
function openImageLightbox(src, alt) {
  if (!src) return;
  const img = document.getElementById('imageLightboxImg');
  img.src = src;
  img.alt = alt || '';
  document.getElementById('imageLightboxBg').classList.add('on');
}
function closeImageLightbox() {
  document.getElementById('imageLightboxBg').classList.remove('on');
  document.getElementById('imageLightboxImg').src = '';
}
document.getElementById('imageLightboxBg').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeImageLightbox();
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape' && document.getElementById('imageLightboxBg').classList.contains('on')) {
    closeImageLightbox();
  }
});

/** Jump straight from the details view into the edit modal for the same product. */
function editFromViewModal() {
  if (!_viewTargetCard) return;
  const card = _viewTargetCard;
  closeViewModal();
  openEditModal({ closest: () => card });
}

/** Collect the Add / Edit modal fields into a FormData. `p` is 'm' or 'e'. */
function collectProductForm(p) {
  const val = id => (document.getElementById(id)?.value ?? '').trim();
  const fd  = new FormData();

  fd.append('name',          val(p + 'Name'));
  fd.append('generic_name',  val(p + 'GenericName'));
  fd.append('form',          val(p + 'Form'));
  fd.append('brand_id',      val(p + 'Brand'));
  fd.append('category_id',   val(p + 'Cat'));
  fd.append('section_id',    val(p + 'Section'));
  fd.append('sort_order',    val(p + 'SortOrder'));
  fd.append('origin',        val(p + 'Origin'));
  fd.append('price',         val(p + 'Price'));
  fd.append('description',   val(p + 'Desc'));
  fd.append('ingredients',   val(p + 'Ingredients'));
  fd.append('warnings',      val(p + 'Warnings'));
  fd.append('width',         val(p + 'Width'));
  fd.append('height',        val(p + 'Height'));
  fd.append('depth',         val(p + 'Depth'));
  fd.append('requires_prescription', document.getElementById(p + 'RequiresPrescription').checked ? 1 : 0);

  const file = document.getElementById(p === 'm' ? 'mImage' : 'eImage').files[0];
  if (file) fd.append('image', file);

  // Laravel rejects "" for nullable numeric/exists rules — drop empty values.
  for (const [key, value] of [...fd.entries()]) {
    if (value === '') fd.delete(key);
  }
  return fd;
}

/**
 * Warn when the typed Sort Order is already used by another product in the
 * SAME section. `prefix` is 'm' (add modal) or 'e' (edit modal).
 */
function checkSortOrderConflict(prefix) {
  const input = document.getElementById(prefix + 'SortOrder');
  const hint  = document.getElementById(prefix + 'SortOrderHint');
  if (!input || !hint) return;

  const raw = input.value.trim();
  const sectionId = document.getElementById(prefix + 'Section').value;

  if (raw === '' || !sectionId) { hint.style.display = 'none'; hint.textContent = ''; return; }

  const order      = parseInt(raw, 10);
  const sectionMap = SECTION_SORT_MAP[sectionId] || {};
  const taken      = sectionMap[order];
  const excludeId  = (prefix === 'e' && typeof _editTarget !== 'undefined' && _editTarget)
    ? String(_editTarget.dataset.id)
    : null;

  if (taken && String(taken.id) !== excludeId) {
    // A true swap only happens when editing within the SAME section. Adding
    // a new product, or moving an existing one to a different section, just
    // bumps whoever is in the way to the end of that section instead.
    const isSameSectionEdit = prefix === 'e'
      && typeof _editTarget !== 'undefined' && _editTarget
      && String(_editTarget.dataset.sectionId || '') === String(sectionId);

    hint.textContent = isSameSectionEdit
      ? `Taken — "${taken.name}" is currently at position ${order} in this section. Saving will swap the two positions.`
      : `Taken — "${taken.name}" is currently at position ${order} in this section. Saving will move it to the end of the section to make room.`;
    hint.style.display = 'block';
  } else {
    hint.style.display = 'none';
    hint.textContent = '';
  }
}

/**
 * Section changed: for the Add modal, auto-fill the first open slot in that
 * section (each section has its own ordering, and its own gaps). Either
 * way, re-check whether the currently typed Sort Order now conflicts in the
 * newly picked section.
 */
function handleSectionChange(prefix) {
  const sectionId = document.getElementById(prefix + 'Section').value;

  if (prefix === 'm') {
    document.getElementById('mSortOrder').value = sectionId ? lowestFreeSortOrder(sectionId) : '';
  }

  checkSortOrderConflict(prefix);
}

/** Smallest sort order (starting at 1) not already used within a section. */
function lowestFreeSortOrder(sectionId) {
  const sectionMap = SECTION_SORT_MAP[sectionId] || {};
  let candidate = 1;
  while (sectionMap[candidate]) candidate++;
  return candidate;
}

/** Flag a field red and focus it. */
function flagField(el) {
  el.style.borderColor = 'var(--red)';
  el.focus();
}

/* ── Save (Add) ──────────────────────────────────────── */
async function saveMedicine() {
  const nameEl  = document.getElementById('mName');
  const priceEl = document.getElementById('mPrice');

  if (!nameEl.value.trim())  { flagField(nameEl);  return; }
  if (priceEl.value === '')  { flagField(priceEl); return; }
  nameEl.style.borderColor = '';
  priceEl.style.borderColor = '';
  if (!guardCombos('m')) return;

  const btn = document.querySelector('#modalBg .btn.primary');
  btn.disabled = true;

  try {
    const result = await sendForm(INVENTORY_URL, collectProductForm('m'));
    const savedName = result.product.name;

    closeModal();
    showToast(result.message);

    if (result.swapped_product) {
      // A sort-order swap changes another card's position in the grid too —
      // reload so the listing order is correct rather than patching the DOM.
      window.location.reload();
      return;
    }

    resetAddForm();
    /* the grid is re-rendered server-side once the stock step is done */
    _reloadAfterStock = true;
    openAddStockModal(savedName);
  } catch (err) {
    showToast(err.message);
  } finally {
    btn.disabled = false;
  }
}

/* ── Form-specific field labels ───────────────────────────
   Picking a Form re-labels (and re-hints) the shared inputs so
   each product type asks for what actually matters to it. The
   input IDs never change, so saving works exactly as before.
   Each profile: [label, placeholder] per field; `rx` toggles the
   "Requires Prescription" checkbox. Missing keys use BASE.     */
const FORM_BASE = {
  name:    ['Product Name',          'e.g. Product name and size'],
  generic: ['Generic Name',          'e.g. Generic / variant name'],
  origin:  ['Origin / Manufacturer', 'e.g. Unilab, Philippines'],
  desc:    ['Description',           'Product details or any relevant notes…'],
  ingr:    ['Ingredients',           'List the ingredients…'],
  warn:    ['Warnings',              'Precautions or safety warnings…'],
  rx:      false
};

const FORM_PROFILES = {
  'Medicine': {
    name:['Medicine Name','e.g. Paracetamol 500mg'], generic:['Generic Name','e.g. Paracetamol'],
    desc:['Indications / Description','Indications, dosage, or any relevant notes…'],
    ingr:['Active & Inactive Ingredients','Active and inactive ingredients…'],
    warn:['Warnings & Contraindications','Precautions, contraindications, or safety warnings…'], rx:true },
  'Vitamins': {
    name:['Vitamin Name','e.g. Vitamin C 500mg'], generic:['Active Nutrient','e.g. Ascorbic Acid'],
    origin:['Manufacturer','e.g. Pharex, Philippines'],
    desc:['Health Benefits','e.g. Supports immune health…'],
    ingr:['Nutrient Content','Nutrients per serving and other ingredients…'],
    warn:['Dosage Warnings & Allergens','Max daily intake, allergens, interactions…'] },
  'Alcohol': {
    name:['Alcohol Product Name','e.g. Isopropyl Alcohol 70% 500ml'], generic:['Alcohol Type','e.g. Ethyl / Isopropyl'],
    desc:['Intended Use','e.g. Disinfectant, antiseptic, cleaning…'],
    ingr:['Alcohol Content','e.g. 70% ethyl alcohol, denatured, water…'],
    warn:['Flammability & Safety Warnings','Flammable, keep away from fire, external use only…'] },
  'Shampoo': {
    name:['Shampoo Name','e.g. Anti-Dandruff Shampoo 340ml'], generic:['Hair Type / Variant','e.g. Dry, Oily, Anti-Dandruff'],
    desc:['Benefits & Directions','How to use and what it does…'],
    ingr:['Key Ingredients','e.g. Zinc pyrithione, keratin…'],
    warn:['Precautions','Avoid contact with eyes…'] },
  'Conditioner': {
    name:['Conditioner Name','e.g. Smooth & Silky Conditioner 340ml'], generic:['Hair Concern','e.g. Frizz, Damage, Color-treated'],
    desc:['Benefits & Directions','How to use and what it does…'],
    ingr:['Key Ingredients','e.g. Argan oil, silicone…'],
    warn:['Precautions','Avoid contact with eyes…'] },
  'Soap': {
    name:['Soap Name','e.g. Antibacterial Bar Soap 90g'], generic:['Soap Type / Variant','e.g. Antibacterial, Moisturizing, Whitening'],
    desc:['Benefits & Usage','Skin benefits and how to use…'],
    ingr:['Ingredients','e.g. Sodium tallowate, glycerin…'],
    warn:['Skin Precautions','Discontinue if irritation occurs…'] },
  'Body Wash': {
    name:['Body Wash Name','e.g. Moisturizing Body Wash 400ml'], generic:['Skin Type / Variant','e.g. Sensitive, Dry, Refreshing'],
    desc:['Benefits & Usage','Skin benefits and how to use…'],
    ingr:['Ingredients','e.g. Glycerin, aloe vera…'],
    warn:['Skin Precautions','Avoid contact with eyes…'] },
  'Lotion': {
    name:['Lotion Name','e.g. Daily Moisture Lotion 200ml'], generic:['Skin Type','e.g. Dry, Sensitive, Normal'],
    desc:['Benefits & Usage','Hydration benefits and how to apply…'],
    ingr:['Ingredients','e.g. Shea butter, vitamin E…'],
    warn:['Skin Precautions','For external use only…'] },
  'Skincare': {
    name:['Skincare Product Name','e.g. Niacinamide 10% Serum 30ml'], generic:['Product Type','e.g. Serum, Toner, Moisturizer, Cleanser'],
    desc:['Skin Concern & Usage','e.g. Brightening, acne-prone skin, AM/PM use…'],
    ingr:['Active Ingredients','e.g. Niacinamide, hyaluronic acid…'],
    warn:['Patch Test & Precautions','Patch test first, avoid eye area…'] },
  'Sunscreen': {
    name:['Sunscreen Name','e.g. UV Shield Sunscreen 50ml'], generic:['SPF / PA Rating','e.g. SPF 50 PA++++'],
    desc:['Protection & Usage','e.g. Water-resistant, reapply every 2 hours…'],
    ingr:['UV Filters & Ingredients','e.g. Zinc oxide, avobenzone…'],
    warn:['Sun Safety Precautions','For external use only, avoid eyes…'] },
  'Makeup': {
    name:['Makeup Product Name','e.g. Matte Lipstick'], generic:['Shade / Finish','e.g. Ruby Red, Matte'],
    desc:['Product Details','Coverage, finish, wear time…'],
    ingr:['Ingredients','Full ingredient list…'],
    warn:['Allergen & Safety Warnings','Discontinue use if irritation occurs…'] },
  'Perfume': {
    name:['Fragrance Name','e.g. Ocean Breeze Eau de Toilette 50ml'], generic:['Fragrance Family','e.g. Floral, Woody, Citrus'],
    desc:['Scent Description','Overall scent profile and occasion…'],
    ingr:['Fragrance Notes','Top / Middle / Base notes…'],
    warn:['Safety Warnings','Flammable, avoid contact with eyes…'] },
  'Deodorant': {
    name:['Deodorant Name','e.g. Fresh Roll-On 50ml'], generic:['Type / Scent','e.g. Roll-on, Spray, Stick — Cool Fresh'],
    desc:['Protection & Usage','e.g. 48-hour protection, apply to dry underarms…'],
    ingr:['Ingredients','e.g. Aluminum chlorohydrate…'],
    warn:['Skin Precautions','Do not apply on broken skin…'] },
  'Toothpaste': {
    name:['Toothpaste Name','e.g. Whitening Toothpaste 150g'], generic:['Variant / Purpose','e.g. Whitening, Sensitive, Cavity Protection'],
    desc:['Benefits & Usage','Dental benefits and brushing directions…'],
    ingr:['Active Ingredients','e.g. Sodium fluoride 1450 ppm…'],
    warn:['Warnings','Do not swallow, keep out of reach of children…'] },
  'Toothbrush': {
    name:['Toothbrush Name','e.g. Soft Bristle Toothbrush'], generic:['Bristle Type','e.g. Soft, Medium, Ultra-soft'],
    origin:['Manufacturer / Brand Origin','e.g. Colgate, Philippines'],
    desc:['Features','e.g. Tongue cleaner, slim head, pack of 2…'],
    ingr:['Materials','e.g. Nylon bristles, polypropylene handle…'],
    warn:['Care & Replacement','Replace every 3 months…'] },
  'Mouthwash': {
    name:['Mouthwash Name','e.g. Antiseptic Mouthwash 500ml'], generic:['Mouthwash Type','e.g. Antiseptic, Fluoride, Alcohol-free'],
    desc:['Benefits & Usage','Oral benefits and rinsing directions…'],
    ingr:['Active Ingredients','e.g. Cetylpyridinium chloride…'],
    warn:['Warnings','Do not swallow, not for children under 6…'] },
  'Hair Color': {
    name:['Hair Color Name','e.g. Permanent Hair Color 4N'], generic:['Shade / Color Code','e.g. 4N Natural Brown'],
    desc:['Application Instructions','Mixing ratio, processing time…'],
    ingr:['Dye & Developer Ingredients','Color cream and developer contents…'],
    warn:['Patch Test & Allergy Warnings','Perform a patch test 48 hours before use…'] },
  'Hair Treatment': {
    name:['Hair Treatment Name','e.g. Keratin Repair Hair Mask 250ml'], generic:['Treatment Type','e.g. Mask, Serum, Hair Oil, Ampoule'],
    desc:['Benefits & Usage','Hair concern addressed and how to apply…'],
    ingr:['Key Ingredients','e.g. Keratin, argan oil…'],
    warn:['Precautions','Avoid contact with eyes and scalp irritation…'] },
  'Feminine Care': {
    name:['Feminine Care Product Name','e.g. Ultra-Thin Napkins 8s'], generic:['Product Type','e.g. Napkin, Panty Liner, Intimate Wash'],
    desc:['Absorbency & Usage','e.g. Heavy flow, with wings, overnight…'],
    ingr:['Materials / Ingredients','e.g. Cotton-soft top sheet, pH-balanced formula…'],
    warn:['Hygiene Warnings','Change regularly, discontinue if irritation occurs…'] },
  'Baby Care': {
    name:['Baby Product Name','e.g. Gentle Baby Wash 200ml'], generic:['Product Type / Age Range','e.g. Baby wash, 0–12 months'],
    desc:['Benefits & Usage','Gentleness, suitability, directions…'],
    ingr:['Ingredients','e.g. Hypoallergenic, tear-free formula…'],
    warn:['Safety Warnings','Keep out of reach of children, avoid eyes…'] },
  'Diapers': {
    name:['Diaper Name','e.g. Dry Comfort Diapers Medium 40s'], generic:['Size / Weight Range','e.g. Medium (6–11 kg)'],
    desc:['Features & Pack Count','e.g. 40 pcs, wetness indicator, 12-hr protection…'],
    ingr:['Materials','e.g. Fluff pulp, SAP, breathable backsheet…'],
    warn:['Safety Warnings','Dispose properly, keep away from children…'] },
  'Supplements': {
    name:['Supplement Name','e.g. Omega-3 Fish Oil 1000mg'], generic:['Active Compound','e.g. EPA / DHA'],
    origin:['Manufacturer','e.g. Nature\u2019s Bounty, USA'],
    desc:['Benefits & Recommended Dosage','Intended benefit and suggested serving…'],
    ingr:['Supplement Facts','Serving size, amount per serving, other ingredients…'],
    warn:['Side Effects & Interactions','Consult a doctor if pregnant or on medication…'] },
  'Medical Supplies': {
    name:['Item Name','e.g. Disposable Syringe 3ml (Box of 100)'], generic:['Item Type','e.g. Syringe, Bandage, BP Monitor'],
    desc:['Specifications & Intended Use','Size, quantity per pack, intended use…'],
    ingr:['Materials','e.g. Latex-free, medical-grade plastic…'],
    warn:['Sterility & Disposal','Single-use only, dispose as medical waste…'] },
  'Household Cleaning': {
    name:['Cleaning Product Name','e.g. Multi-Surface Disinfectant 1L'], generic:['Cleaner Type','e.g. Disinfectant, Bleach, Dishwashing'],
    desc:['Usage Directions','Dilution, surfaces, and how to apply…'],
    ingr:['Active Ingredients','e.g. Sodium hypochlorite 5%…'],
    warn:['Hazard & Safety Warnings','Irritant, do not mix with other cleaners…'] },
  'Food and Beverages': {
    name:['Food / Beverage Name','e.g. Oat Biscuits 200g'], generic:['Food Type / Variant','e.g. Biscuit, Juice Drink, Chocolate'],
    origin:['Manufacturer / Country of Origin','e.g. Monde Nissin, Philippines'],
    desc:['Description & Serving Info','Flavor, serving size, shelf life…'],
    ingr:['Ingredients & Nutrition','Ingredient list and nutrition highlights…'],
    warn:['Allergen Warnings','Contains milk, soy, gluten…'] }
};

// Legacy dosage forms (kept so existing data still selects) use the Medicine profile.
['Tablet','Capsule','Syrup','Suspension','Injection','Drops','Cream / Ointment','Patch']
  .forEach(f => { FORM_PROFILES[f] = FORM_PROFILES['Medicine']; });

/** Re-label the shared inputs for the selected Form. `p` is 'm' (add) or 'e' (edit). */
function applyFormProfile(p) {
  const sel     = document.getElementById(p + 'Form');
  const profile = Object.assign({}, FORM_BASE, FORM_PROFILES[sel ? sel.value : ''] || {});

  const fields = {
    name:    { id: p + 'Name',        optional: false },
    generic: { id: p + 'GenericName', optional: false },
    origin:  { id: p + 'Origin',      optional: false },
    desc:    { id: p + 'Desc',        optional: true  },
    ingr:    { id: p + 'Ingredients', optional: true  },
    warn:    { id: p + 'Warnings',    optional: true  }
  };

  Object.keys(fields).forEach(key => {
    const input = document.getElementById(fields[key].id);
    if (!input) return;
    const label = input.closest('.field')?.querySelector('label');
    const [text, placeholder] = profile[key];
    if (label) {
      label.textContent = text;
      if (fields[key].optional) {
        const opt = document.createElement('span');
        opt.style.cssText = 'font-size:10px;font-weight:400;text-transform:none;color:var(--subtle);';
        opt.textContent = ' (optional)';
        label.appendChild(opt);
      }
    }
    input.placeholder = placeholder;
  });

  // "Requires Prescription" only applies to Medicine (and its dosage forms).
  const rxWrap = document.getElementById(p + 'RxWrap');
  if (rxWrap) {
    rxWrap.style.display = profile.rx ? '' : 'none';
    if (!profile.rx) document.getElementById(p + 'RequiresPrescription').checked = false;
  }
}

/* ── Reset the add-medicine form ─────────────────────── */
function resetAddForm() {
  ['mName','mGenericName','mOrigin','mDesc','mIngredients','mWarnings','mWidth','mHeight','mDepth','mPrice'].forEach(id => {
    const el = document.getElementById(id);
    el.value = '';
    el.style.borderColor = '';
  });
  document.getElementById('mRequiresPrescription').checked = false;
  ['mForm','mSection'].forEach(id => document.getElementById(id).selectedIndex = 0);
  applyFormProfile('m');
  clearCombo('m', 'brand');
  clearCombo('m', 'category');

  // No section selected yet, so there's no "next free slot" to suggest.
  document.getElementById('mSortOrder').value = '';
  document.getElementById('mSortOrderHint').style.display = 'none';

  const fileInput = document.getElementById('mImage');
  fileInput.value = '';
  document.getElementById('imgPreview').style.display = 'none';
  const zone = fileInput.closest('.img-upload-zone');
  zone.querySelector('.upload-ico').style.display  = '';
  zone.querySelector('.upload-hint').style.display = '';

  document.getElementById('autoMedId').textContent = 'MED-' + (Math.floor(Math.random() * 9000) + 1000);
}

/* ── Toast helper ────────────────────────────────────── */
function showToast(msg) {
  document.getElementById('toastMsg').textContent = msg;
  const toast = document.getElementById('toast');
  toast.classList.add('on');
  setTimeout(() => toast.classList.remove('on'), 3200);
}

/* ══════════════════════════════════════════════════════
   ADD BADGE MODAL
   ══════════════════════════════════════════════════════ */
let _badgeCard = null;

function openBadgeModal(pillEl) {
  _badgeCard = pillEl.closest('.med-card');
  const medName = _badgeCard ? _badgeCard.dataset.medicine : '';
  document.getElementById('badgeFormTitle').textContent = medName ? `Add Badge — ${medName}` : 'Add Badge';

  document.getElementById('badgeText').value = _badgeCard?.dataset.badgeText || '';
  document.getElementById('badgeType').value = _badgeCard?.dataset.badgeType || 'most-sold';
  document.getElementById('badgeText').style.borderColor = '';

  document.getElementById('badgeModalBg').classList.add('on');
}

function closeBadgeModal() {
  document.getElementById('badgeModalBg').classList.remove('on');
  _badgeCard = null;
}

document.getElementById('badgeModalBg').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeBadgeModal();
});

async function saveBadge() {
  const textEl = document.getElementById('badgeText');
  const text = textEl.value.trim();
  if (!text) { flagField(textEl); return; }
  textEl.style.borderColor = '';

  const type = document.getElementById('badgeType').value;
  const card = _badgeCard;
  if (!card) { closeBadgeModal(); return; }

  const fd = new FormData();
  fd.append('badge', text);
  fd.append('badge_type', type);

  try {
    const result = await sendForm(`${INVENTORY_URL}/${card.dataset.id}/badge`, fd, 'PUT');

    card.dataset.badgeText = result.badge;
    card.dataset.badgeType = result.badge_type;

    const pill = card.querySelector('.pill-float');
    if (pill) {
      pill.textContent = result.badge;
      pill.classList.remove('ok', 'warning');
      pill.classList.add(result.badge_type === 'sale-badge' ? 'warning' : 'ok');
    }

    closeBadgeModal();
    showToast(result.message);
  } catch (err) {
    showToast(err.message);
  }
}

/* ══════════════════════════════════════════════════════
   EDIT MODAL
   ══════════════════════════════════════════════════════ */
let _editTarget = null;

function openEditModal(btn) {
  const card = btn.closest('.med-card');
  _editTarget = card;

  const id      = card.dataset.id      || 'MED-????';
  const name      = card.dataset.medicine || '';
  const generic   = card.dataset.genericName || '';
  const brandId   = card.dataset.brandId || '';
  const cat       = card.dataset.cat     || '';
  const form      = card.dataset.form    || '';
  const origin    = card.dataset.origin  || '';
  const price     = card.dataset.price   || '';
  const desc      = card.dataset.desc    || '';
  const ingredients = card.dataset.ingredients || '';
  const warnings    = card.dataset.warnings    || '';
  const width       = card.dataset.width       || '';
  const height      = card.dataset.height      || '';
  const depth       = card.dataset.depth       || '';
  const requiresRx  = card.dataset.requiresRx  === 'true';

  const badge = document.getElementById('editMedIdBadge');
  badge.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:11px;height:11px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> ${id}`;
  document.getElementById('editMedIdDisplay').textContent = id;

  document.getElementById('eName').value   = name;
  document.getElementById('eGenericName').value = generic;
  setCombo('e', 'brand', brandId);
  document.getElementById('eOrigin').value = origin;
  document.getElementById('ePrice').value  = price;
  document.getElementById('eDesc').value   = desc;
  document.getElementById('eIngredients').value = ingredients;
  document.getElementById('eWarnings').value     = warnings;
  document.getElementById('eWidth').value        = width;
  document.getElementById('eHeight').value       = height;
  document.getElementById('eDepth').value        = depth;
  document.getElementById('eRequiresPrescription').checked = requiresRx;

  setCombo('e', 'category', cat);

  document.getElementById('eSection').value = card.dataset.sectionId || '';

  document.getElementById('eSortOrder').value = card.dataset.sortOrder || '';
  checkSortOrderConflict('e');

  const formSel = document.getElementById('eForm');
  const formOpts = formSel.options;
  formSel.selectedIndex = 0;
  for (let i = 0; i < formOpts.length; i++) {
    if (formOpts[i].text === form) { formSel.selectedIndex = i; break; }
  }
  applyFormProfile('e');

  const ePreview = document.getElementById('editImgPreview');
  ePreview.style.display = 'none';
  ePreview.src = '';
  const eZone = document.getElementById('eImage').closest('.img-upload-zone');
  eZone.querySelector('.upload-ico').style.display  = '';
  eZone.querySelector('.upload-hint').style.display = '';
  document.getElementById('eImage').value = '';

  document.getElementById('editModalBg').classList.add('on');
}

function closeEditModal() {
  document.getElementById('editModalBg').classList.remove('on');
  _editTarget = null;
}

document.getElementById('editModalBg').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeEditModal();
});

async function saveEdit() {
  const nameEl  = document.getElementById('eName');
  const priceEl = document.getElementById('ePrice');

  if (!nameEl.value.trim()) { flagField(nameEl);  return; }
  if (priceEl.value === '') { flagField(priceEl); return; }
  nameEl.style.borderColor = '';
  priceEl.style.borderColor = '';

  if (!_editTarget) { closeEditModal(); return; }
  if (!guardCombos('e')) return;

  const card = _editTarget;
  const btn  = document.querySelector('#editModalBg .btn.primary');
  btn.disabled = true;

  try {
    const result = await sendForm(`${INVENTORY_URL}/${card.dataset.id}`, collectProductForm('e'), 'PUT');
    closeEditModal();
    showToast(result.message);

    if (result.swapped_product) {
      // The product that used to hold this slot moved too — reload so the
      // grid's sort order reflects both changes correctly.
      window.location.reload();
      return;
    }

    applyProductToCard(card, result.product);
  } catch (err) {
    showToast(err.message);
  } finally {
    btn.disabled = false;
  }
}

/** Write a product payload from the server back onto its grid card. */
function applyProductToCard(card, p) {
  // Clear this product's old slot out of the section map before recording
  // its new one — it may have moved to a different section or position.
  const prevSectionId = card.dataset.sectionId;
  const prevSortOrder = card.dataset.sortOrder;
  if (prevSectionId && prevSortOrder !== '' && prevSortOrder !== undefined) {
    const prevMap = SECTION_SORT_MAP[prevSectionId];
    if (prevMap && prevMap[prevSortOrder] && String(prevMap[prevSortOrder].id) === String(p.id)) {
      delete prevMap[prevSortOrder];
    }
  }

  card.dataset.id          = p.id;
  card.dataset.medicine    = p.name;
  card.dataset.genericName = p.generic_name || '';
  card.dataset.form        = p.form || '';
  card.dataset.brandId     = p.brand_id || '';
  card.dataset.brandName   = p.brand_name || '';
  card.dataset.cat         = p.category_id || '';
  card.dataset.sectionId   = p.section_id || '';
  card.dataset.sortOrder   = p.sort_order ?? '';
  if (p.sort_order !== undefined && p.sort_order !== null && p.section_id) {
    const key = String(p.section_id);
    if (!SECTION_SORT_MAP[key]) SECTION_SORT_MAP[key] = {};
    SECTION_SORT_MAP[key][p.sort_order] = { id: p.id, name: p.name };
  }
  card.dataset.origin      = p.origin || '';
  card.dataset.price       = p.price;
  card.dataset.oldPrice    = p.old_price ?? '';
  card.dataset.desc        = p.description || '';
  card.dataset.ingredients = p.ingredients || '';
  card.dataset.warnings    = p.warnings || '';
  card.dataset.width       = p.width || '';
  card.dataset.height      = p.height || '';
  card.dataset.depth       = p.depth || '';
  card.dataset.requiresRx  = p.requires_prescription ? 'true' : 'false';
  card.dataset.name        = `${p.name} ${p.form || ''} ${p.category_name} ${p.brand_name}`.toLowerCase();

  const titleEl = card.querySelector('.med-title');
  if (titleEl) titleEl.textContent = p.name;

  const catLabel = card.querySelector('.med-cat-label');
  if (catLabel) catLabel.textContent = p.category_name;

  const catDot = card.querySelector('.med-cat-dot');
  if (catDot) catDot.style.background = p.icon_color;

  const imgWrap = card.querySelector('.med-img-wrap');
  if (imgWrap) {
    imgWrap.style.background = p.image_url
      ? '#fff'
      : `linear-gradient(135deg, ${p.bg_color}, ${p.bg_color})`;
  }

  const priceEl = card.querySelector('.med-price');
  if (priceEl) {
    priceEl.innerHTML = '&#8369;' + Number(p.price).toFixed(2) +
      (p.old_price
        ? ` <span style="text-decoration:line-through;color:var(--muted);font-weight:500;font-size:11px;margin-left:4px;">&#8369;${Number(p.old_price).toFixed(2)}</span>`
        : '');
  }

  if (p.image_url) {
    const existing = card.querySelector('.med-img-main');
    if (existing && existing.tagName === 'IMG') {
      existing.src = p.image_url + '?v=' + Date.now();
    } else if (existing) {
      const img = document.createElement('img');
      img.className = 'med-img-main';
      img.src = p.image_url + '?v=' + Date.now();
      img.alt = p.name;
      img.style.cssText = 'width:72px;height:72px;object-fit:contain;border-radius:8px;';
      existing.replaceWith(img);
    }
  }
}

/* ══════════════════════════════════════════════════════
   DELETE MODAL
   ══════════════════════════════════════════════════════ */
let _deleteTarget = null;

function openDeleteModal(btn) {
  const card = btn.closest('.med-card');
  _deleteTarget = card;
  const name = card.dataset.medicine || card.querySelector('.med-title')?.textContent || 'this medicine';
  document.getElementById('deleteTargetName').textContent = name;
  document.getElementById('deleteModalBg').classList.add('on');
}

function closeDeleteModal() {
  document.getElementById('deleteModalBg').classList.remove('on');
  _deleteTarget = null;
}

document.getElementById('deleteModalBg').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeDeleteModal();
});

async function confirmDelete() {
  if (!_deleteTarget) return;

  const card = _deleteTarget;
  const name = card.dataset.medicine || 'Medicine';
  const btn  = document.querySelector('#deleteModalBg .btn-danger');
  btn.disabled = true;

  try {
    const result = await sendForm(`${INVENTORY_URL}/${card.dataset.id}`, new FormData(), 'DELETE');

    closeDeleteModal();
    card.classList.add('removing');
    setTimeout(() => {
      card.remove();
      showToast(result.message);
      if (!document.querySelector('#medGrid .med-card')) {
        document.getElementById('medGrid').innerHTML =
          '<div class="manage-empty">No products yet. Click "+ Add Product" to get started.</div>';
      }
    }, 360);
  } catch (err) {
    closeDeleteModal();
    showToast(err.message);
  } finally {
    btn.disabled = false;
  }
}

/* ── Card search (backed by AdminInventoryController@search) ──────────
   Debounced so we don't fire a request on every keystroke; the matching
   is done server-side (name / generic name / category / brand), and we
   just show/hide the already-rendered cards by id. */
let _cardSearchTimer = null;

function filterCards(q) {
  clearTimeout(_cardSearchTimer);
  _cardSearchTimer = setTimeout(() => runCardSearch(q.trim()), 250);
}

async function runCardSearch(q) {
  const cards = document.querySelectorAll('#medGrid .med-card');

  try {
    const res  = await fetch(`${INVENTORY_SEARCH_URL}?q=${encodeURIComponent(q)}`, {
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    });
    if (!res.ok) throw new Error('Search request failed');
    const body = await res.json();
    const ids  = new Set((body.ids || []).map(String));

    cards.forEach(card => {
      card.style.display = ids.has(card.dataset.id) ? '' : 'none';
    });
  } catch (err) {
    // If the search request fails, fall back to the old client-side
    // substring match on name/category/brand rather than hiding everything.
    const val = q.toLowerCase();
    cards.forEach(card => {
      card.style.display = card.dataset.name.includes(val) ? '' : 'none';
    });
  }
}

/* ── Sort by (backed by AdminInventoryController@sort) ─────────────────
   Same "server decides the order, JS just re-appends the already-rendered
   cards by id" approach as card search above — nothing is re-fetched or
   re-rendered, the grid is just reordered in place. */
const SORT_LABELS = {
  alphabetical: 'A-Z',
  date:         'Date',
  category:     'Category',
  brand:        'Brand',
};
let _currentSort = null;

function toggleSortMenu() {
  document.getElementById('sortMenu').classList.toggle('on');
}

document.addEventListener('click', (e) => {
  const wrap = document.getElementById('sortChipWrap');
  if (wrap && !wrap.contains(e.target)) {
    document.getElementById('sortMenu').classList.remove('on');
  }
});

async function sortProducts(by) {
  const grid = document.getElementById('medGrid');
  const menu = document.getElementById('sortMenu');
  menu.classList.remove('on');

  try {
    const res = await fetch(`${INVENTORY_SORT_URL}?by=${encodeURIComponent(by)}`, {
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    });
    if (!res.ok) throw new Error('Sort request failed');
    const body = await res.json();
    const ids  = (body.ids || []).map(String);

    const cards = new Map();
    document.querySelectorAll('#medGrid .med-card').forEach(card => cards.set(card.dataset.id, card));

    // Re-append in the server-decided order; any card not in the list
    // (shouldn't happen, but just in case) stays put at the end.
    ids.forEach(id => { const card = cards.get(id); if (card) grid.appendChild(card); });

    _currentSort = by;
    document.getElementById('sortChipLabel').textContent = SORT_LABELS[by] || 'Sort by';
    document.getElementById('sortChipBtn').classList.add('active');
    document.querySelectorAll('.sort-menu-item').forEach(item => {
      item.classList.toggle('selected', item.dataset.by === by);
    });
  } catch (err) {
    showToast('Could not sort products. Please try again.');
  }
}

/* ══════════════════════════════════════════════════════
   ADD STOCK MODAL
   ══════════════════════════════════════════════════════ */
let _stockLockedMed = null;
/* set by saveMedicine(): reload once the follow-up stock step is dismissed,
   so the freshly created product appears as a server-rendered card. */
let _reloadAfterStock = false;

function openAddStockModal(lockedMedicineName) {
  _stockLockedMed = lockedMedicineName || null;

  /* generate stock number */
  const stockNo = 'STK-' + (Math.floor(Math.random() * 90000) + 10000);
  document.getElementById('generatedStockNo').textContent = stockNo;

  /* populate medicine dropdown from cards in grid */
  const sel = document.getElementById('modalMedicine');
  sel.innerHTML = '<option value="">— Select medicine —</option>';
  document.querySelectorAll('#medGrid .med-card').forEach(card => {
    const medName = card.dataset.medicine || card.querySelector('.med-title')?.textContent || '';
    if (!medName) return;
    const opt = document.createElement('option');
    opt.value = medName;
    opt.textContent = medName;
    sel.appendChild(opt);
  });

  /* add the newly-saved medicine if not already in grid */
  if (_stockLockedMed) {
    let found = false;
    for (const opt of sel.options) { if (opt.value === _stockLockedMed) { found = true; break; } }
    if (!found) {
      const opt = document.createElement('option');
      opt.value = _stockLockedMed;
      opt.textContent = _stockLockedMed;
      sel.appendChild(opt);
    }
    sel.value = _stockLockedMed;
    sel.disabled = true;
    sel.title = 'Locked to newly added medicine';
  } else {
    sel.disabled = false;
    sel.title = '';
  }

  /* reset other fields */
  ['modalSupplier','modalQty','modalCost','modalMfgDate','modalExpiry'].forEach(id => {
    document.getElementById(id).value = '';
    document.getElementById(id).style.borderColor = '';
  });

  document.getElementById('addStockModal').classList.add('on');
}

function closeAddStockModal() {
  document.getElementById('addStockModal').classList.remove('on');
  _stockLockedMed = null;

  if (_reloadAfterStock) {
    _reloadAfterStock = false;
    setTimeout(() => window.location.reload(), 600);
  }
}

document.getElementById('closeStockModal').addEventListener('click', closeAddStockModal);
document.getElementById('cancelStockModal').addEventListener('click', closeAddStockModal);
document.getElementById('addStockModal').addEventListener('click', e => {
  if (e.target === e.currentTarget) closeAddStockModal();
});

document.getElementById('confirmAddStock').addEventListener('click', function () {
  const medSel   = document.getElementById('modalMedicine');
  const qtyEl    = document.getElementById('modalQty');
  const expiryEl = document.getElementById('modalExpiry');

  let valid = true;

  if (!medSel.value) {
    medSel.style.borderColor = 'var(--red)';
    valid = false;
  } else { medSel.style.borderColor = ''; }

  if (!qtyEl.value || parseInt(qtyEl.value) < 1) {
    qtyEl.style.borderColor = 'var(--red)';
    valid = false;
  } else { qtyEl.style.borderColor = ''; }

  if (!expiryEl.value) {
    expiryEl.style.borderColor = 'var(--red)';
    valid = false;
  } else { expiryEl.style.borderColor = ''; }

  if (!valid) return;

  const medName = medSel.value;
  const qty     = qtyEl.value;
  const stockNo = document.getElementById('generatedStockNo').textContent;

  closeAddStockModal();
  showToast(`"${medName}" added · ${qty} units logged (${stockNo}).`);
});

/* ══════════════════════════════════════════════════════
   CATEGORIES
   ══════════════════════════════════════════════════════ */
@php
  $categoriesJson = $categories->map(fn($c) => [
  'id'         => $c->id,
  'name'       => $c->name,
  'icon_class' => $c->icon_class,
  'icon_color' => $c->icon_color,
  'bg_color'   => $c->bg_color,
  'status'     => $c->is_active ? 'Active' : 'Inactive',
  ])->values()->toJson();
@endphp
let categories = {!! $categoriesJson !!};
let _catEditId  = null;
let _catComboTarget = null; // 'm' | 'e' when the form was opened from the typable Category field
const CATEGORIES_URL = "{{ route('admin.inventory.categories.store') }}";

function renderCategories() {
  refreshComboOptions();
  const list = document.getElementById('categoriesList');
  if (!categories.length) { list.innerHTML = '<div class="manage-empty">No categories yet.</div>'; return; }
  list.innerHTML = categories.map(c => `
    <div class="manage-list-item">
      <span class="cat-icon-preview" style="background:${c.bg_color};color:${c.icon_color};">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l3 2"/></svg>
      </span>
      <div class="manage-list-info">
        <div class="manage-list-name">${c.name}</div>
        <div class="manage-list-sub">${c.icon_class || '—'}</div>
      </div>
      <span class="pill ${c.status === 'Active' ? 'ok' : 'warning'}">${c.status}</span>
      <div class="manage-list-actions">
        <button class="manage-icon-btn" onclick="openCategoryForm(${c.id})" title="Edit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </button>
        <button class="manage-icon-btn danger" onclick="deleteCategory(${c.id})" title="Delete">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
        </button>
      </div>
    </div>
  `).join('');
}

function openCategoriesModal() { renderCategories(); document.getElementById('categoriesModalBg').classList.add('on'); }
function closeCategoriesModal() { document.getElementById('categoriesModalBg').classList.remove('on'); }
document.getElementById('categoriesModalBg').addEventListener('click', e => { if (e.target === e.currentTarget) closeCategoriesModal(); });

function openCategoryForm(id, opts) {
  _catEditId = id || null;
  _catComboTarget = (opts && opts.target) ? opts.target : null;
  const titleEl = document.getElementById('categoryFormTitle');
  if (id) {
    const c = categories.find(x => x.id === id);
    titleEl.textContent = 'Edit Category';
    document.getElementById('catName').value = c.name;
    document.getElementById('catIconClass').value = c.icon_class;
    document.getElementById('catIconColor').value = c.icon_color;
    document.getElementById('catBgColor').value = c.bg_color;
    document.getElementById('catStatus').value = c.status;
  } else {
    titleEl.textContent = 'Add Category';
    document.getElementById('catName').value = '';
    document.getElementById('catIconClass').value = '';
    document.getElementById('catIconColor').value = '#3b82f6';
    document.getElementById('catBgColor').value = '#eff6ff';
    document.getElementById('catStatus').value = 'Active';
  }
  if (opts && opts.name) document.getElementById('catName').value = opts.name;
  document.getElementById('catName').style.borderColor = '';
  updateCatPreview();
  document.getElementById('categoryFormModalBg').classList.add('on');
  if (opts && opts.name) setTimeout(() => document.getElementById('catName').focus(), 50);
}
function closeCategoryForm() {
  document.getElementById('categoryFormModalBg').classList.remove('on');
  // Cancelled while creating from the typable Category field: drop the unmatched text.
  if (_catComboTarget) {
    const prefix = _catComboTarget;
    _catComboTarget = null;
    clearCombo(prefix, 'category');
    document.getElementById(prefix + 'CatText')?.focus();
  }
}
document.getElementById('categoryFormModalBg').addEventListener('click', e => { if (e.target === e.currentTarget) closeCategoryForm(); });

function updateCatPreview() {
  const box = document.getElementById('catPreviewBox');
  box.style.background = document.getElementById('catBgColor').value;
  box.style.color = document.getElementById('catIconColor').value;
}
document.getElementById('catIconColor').addEventListener('input', updateCatPreview);
document.getElementById('catBgColor').addEventListener('input', updateCatPreview);

async function saveCategory() {
  const nameEl = document.getElementById('catName');
  const name = nameEl.value.trim();
  if (!name) { nameEl.style.borderColor = 'var(--red)'; nameEl.focus(); return; }
  nameEl.style.borderColor = '';

  const fd = new FormData();
  fd.append('name', name);
  fd.append('icon_class', document.getElementById('catIconClass').value.trim());
  fd.append('icon_color', document.getElementById('catIconColor').value);
  fd.append('bg_color', document.getElementById('catBgColor').value);
  fd.append('is_active', document.getElementById('catStatus').value === 'Active' ? 1 : 0);

  const btn = document.querySelector('#categoryFormModalBg .btn.primary');
  btn.disabled = true;

  try {
    const url    = _catEditId ? `${CATEGORIES_URL}/${_catEditId}` : CATEGORIES_URL;
    const method = _catEditId ? 'PUT' : 'POST';
    const result = await sendForm(url, fd, method);

    if (_catEditId) {
      Object.assign(categories.find(x => x.id === _catEditId), result.category);
    } else {
      categories.push(result.category);
    }

    const comboPrefix = _catComboTarget;
    _catComboTarget = null;          // saved, so closing must not clear the field
    closeCategoryForm();
    renderCategories();
    if (comboPrefix) setCombo(comboPrefix, 'category', result.category.id);
    showToast(result.message);
  } catch (err) {
    showToast(err.message);
  } finally {
    btn.disabled = false;
  }
}

async function deleteCategory(id) {
  const c = categories.find(x => x.id === id);
  if (!c) return;
  if (!confirm(`Delete category "${c.name}"? Medicines using this category will keep their existing tag.`)) return;

  try {
    const result = await sendForm(`${CATEGORIES_URL}/${id}`, new FormData(), 'DELETE');
    categories = categories.filter(x => x.id !== id);
    renderCategories();
    showToast(result.message);
  } catch (err) {
    showToast(err.message);
  }
}

/* ══════════════════════════════════════════════════════
   BRANDS
   ══════════════════════════════════════════════════════ */
@php
  $brandsJson = $brands->map(fn($b) => [
  'id'               => $b->id,
  'name'             => $b->name,
  'ticker_image'     => $b->ticker_image ? asset($b->ticker_image) : null,
  'featured_image'   => $b->featured_image ? asset($b->featured_image) : null,
  'featured_color'   => $b->featured_color,
  'show_in_ticker'   => (bool) $b->show_in_ticker,
  'show_in_featured' => (bool) $b->show_in_featured,
  'status'           => $b->is_active ? 'Active' : 'Inactive',
  ])->values()->toJson();
@endphp
let brands = {!! $brandsJson !!};
let _brandEditId = null;
let _brandComboTarget = null; // 'm' | 'e' when the form was opened from the typable Brand field
const BRANDS_URL = "{{ route('admin.inventory.brands.store') }}";

function renderBrands() {
  refreshComboOptions();
  const list = document.getElementById('brandsList');
  if (!brands.length) { list.innerHTML = '<div class="manage-empty">No brands yet.</div>'; return; }
  list.innerHTML = brands.map(b => `
    <div class="manage-list-item">
      <span class="brand-thumb" style="background:${b.featured_color}22;">
        ${b.ticker_image
          ? `<img src="${b.ticker_image}" alt="${b.name}">`
          : `<span style="color:${b.featured_color};font-weight:800;font-size:12px;">${b.name.slice(0, 2).toUpperCase()}</span>`}
      </span>
      <div class="manage-list-info">
        <div class="manage-list-name">${b.name}</div>
        <div class="manage-list-sub">
          ${b.show_in_ticker ? '<span class="mini-flag">Ticker</span>' : ''}
          ${b.show_in_featured ? '<span class="mini-flag">Featured</span>' : ''}
        </div>
      </div>
      <span class="pill ${b.status === 'Active' ? 'ok' : 'warning'}">${b.status}</span>
      <div class="manage-list-actions">
        <button class="manage-icon-btn" onclick="openBrandForm(${b.id})" title="Edit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </button>
        <button class="manage-icon-btn danger" onclick="deleteBrand(${b.id})" title="Delete">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
        </button>
      </div>
    </div>
  `).join('');
}

function openBrandsModal() { renderBrands(); document.getElementById('brandsModalBg').classList.add('on'); }
function closeBrandsModal() { document.getElementById('brandsModalBg').classList.remove('on'); }
document.getElementById('brandsModalBg').addEventListener('click', e => { if (e.target === e.currentTarget) closeBrandsModal(); });

function resetBrandImageZone(inputId, previewId) {
  document.getElementById(inputId).value = '';
  const preview = document.getElementById(previewId);
  preview.style.display = 'none';
  preview.src = '';
  const zone = preview.closest('.img-upload-zone');
  zone.querySelector('.upload-ico').style.display = '';
  zone.querySelector('.upload-hint').style.display = '';
}

function showBrandPreview(previewId, src) {
  const preview = document.getElementById(previewId);
  preview.src = src;
  preview.style.display = 'block';
  const zone = preview.closest('.img-upload-zone');
  zone.querySelector('.upload-ico').style.display = 'none';
  zone.querySelector('.upload-hint').style.display = 'none';
}

function openBrandForm(id, opts) {
  _brandEditId = id || null;
  _brandComboTarget = (opts && opts.target) ? opts.target : null;
  const titleEl = document.getElementById('brandFormTitle');
  resetBrandImageZone('brandTickerImage', 'brandTickerPreview');
  resetBrandImageZone('brandFeaturedImage', 'brandFeaturedPreview');

  if (id) {
    const b = brands.find(x => x.id === id);
    titleEl.textContent = 'Edit Brand';
    document.getElementById('brandName').value = b.name;
    document.getElementById('brandFeaturedColor').value = b.featured_color;
    document.getElementById('brandShowTicker').checked = b.show_in_ticker;
    document.getElementById('brandShowFeatured').checked = b.show_in_featured;
    document.getElementById('brandStatus').value = b.status;
    if (b.ticker_image) showBrandPreview('brandTickerPreview', b.ticker_image);
    if (b.featured_image) showBrandPreview('brandFeaturedPreview', b.featured_image);
  } else {
    titleEl.textContent = 'Add Brand';
    document.getElementById('brandName').value = '';
    document.getElementById('brandFeaturedColor').value = '#5cc06d';
    document.getElementById('brandShowTicker').checked = false;
    document.getElementById('brandShowFeatured').checked = false;
    document.getElementById('brandStatus').value = 'Active';
  }
  if (opts && opts.name) document.getElementById('brandName').value = opts.name;
  document.getElementById('brandName').style.borderColor = '';
  document.getElementById('brandFormModalBg').classList.add('on');
  if (opts && opts.name) setTimeout(() => document.getElementById('brandName').focus(), 50);
}
function closeBrandForm() {
  document.getElementById('brandFormModalBg').classList.remove('on');
  // Cancelled while creating from the typable Brand field: drop the unmatched text.
  if (_brandComboTarget) {
    const prefix = _brandComboTarget;
    _brandComboTarget = null;
    clearCombo(prefix, 'brand');
    document.getElementById(prefix + 'BrandText')?.focus();
  }
}
document.getElementById('brandFormModalBg').addEventListener('click', e => { if (e.target === e.currentTarget) closeBrandForm(); });

async function saveBrand() {
  const nameEl = document.getElementById('brandName');
  const name = nameEl.value.trim();
  if (!name) { flagField(nameEl); return; }
  nameEl.style.borderColor = '';

  const fd = new FormData();
  fd.append('name', name);
  fd.append('featured_color', document.getElementById('brandFeaturedColor').value);
  fd.append('show_in_ticker', document.getElementById('brandShowTicker').checked ? 1 : 0);
  fd.append('show_in_featured', document.getElementById('brandShowFeatured').checked ? 1 : 0);
  fd.append('is_active', document.getElementById('brandStatus').value === 'Active' ? 1 : 0);

  // Only attach a file when the admin actually picked a new one — leaving
  // the input empty on edit tells the backend to keep the existing image.
  const tickerFile   = document.getElementById('brandTickerImage').files[0];
  const featuredFile = document.getElementById('brandFeaturedImage').files[0];
  if (tickerFile)   fd.append('ticker_image', tickerFile);
  if (featuredFile) fd.append('featured_image', featuredFile);

  const btn = document.querySelector('#brandFormModalBg .btn.primary');
  btn.disabled = true;

  try {
    const url    = _brandEditId ? `${BRANDS_URL}/${_brandEditId}` : BRANDS_URL;
    const method = _brandEditId ? 'PUT' : 'POST';
    const result = await sendForm(url, fd, method);

    if (_brandEditId) {
      Object.assign(brands.find(x => x.id === _brandEditId), result.brand);
    } else {
      brands.push(result.brand);
    }

    const comboPrefix = _brandComboTarget;
    _brandComboTarget = null;        // saved, so closing must not clear the field
    closeBrandForm();
    renderBrands();
    if (comboPrefix) setCombo(comboPrefix, 'brand', result.brand.id);
    showToast(result.message);
  } catch (err) {
    showToast(err.message);
  } finally {
    btn.disabled = false;
  }
}

async function deleteBrand(id) {
  const b = brands.find(x => x.id === id);
  if (!b) return;
  if (!confirm(`Delete brand "${b.name}"?`)) return;

  try {
    const result = await sendForm(`${BRANDS_URL}/${id}`, new FormData(), 'DELETE');
    brands = brands.filter(x => x.id !== id);
    renderBrands();
    showToast(result.message);
  } catch (err) {
    showToast(err.message);
  }
}

/* ══════════════════════════════════════════════════════
   TYPABLE BRAND / CATEGORY FIELDS (Add + Edit modals)
   Type a name or pick from the suggestions. If the committed text
   (blur / Enter) isn't an existing record, the matching Add Brand /
   Add Category modal opens with the typed name already filled in.
   `prefix` is 'm' (Add modal) or 'e' (Edit modal).
   ══════════════════════════════════════════════════════ */
const COMBOS = {
  brand: {
    items:  () => brands,
    textId: p => p + 'BrandText',
    idId:   p => p + 'Brand',
    listId: 'brandOptions',
    open:   (p, name) => openBrandForm(null, { name, target: p }),
  },
  category: {
    items:  () => categories,
    textId: p => p + 'CatText',
    idId:   p => p + 'Cat',
    listId: 'categoryOptions',
    open:   (p, name) => openCategoryForm(null, { name, target: p }),
  },
};

function findComboItem(kind, text) {
  const t = (text || '').trim().toLowerCase();
  if (!t) return null;
  return COMBOS[kind].items().find(x => x.name.trim().toLowerCase() === t) || null;
}

/** Keep the hidden id field (what actually gets submitted) in step with the typed text. */
function syncCombo(prefix, kind) {
  const c = COMBOS[kind];
  const text = document.getElementById(c.textId(prefix));
  const item = findComboItem(kind, text.value);
  document.getElementById(c.idId(prefix)).value = item ? item.id : '';
  text.style.borderColor = '';
}

/** Select an existing record (by id) in the field; empty/unknown id clears it. */
function setCombo(prefix, kind, id) {
  const c = COMBOS[kind];
  const item = id ? c.items().find(x => String(x.id) === String(id)) : null;
  document.getElementById(c.textId(prefix)).value = item ? item.name : '';
  document.getElementById(c.idId(prefix)).value   = item ? item.id : '';
  document.getElementById(c.textId(prefix)).style.borderColor = '';
}

function clearCombo(prefix, kind) { setCombo(prefix, kind, null); }

/** Fired on change (blur / Enter / datalist pick). Unknown name → open the Add modal. */
function handleComboCommit(prefix, kind) {
  const c = COMBOS[kind];
  const text = document.getElementById(c.textId(prefix));
  const name = text.value.trim();

  syncCombo(prefix, kind);
  if (!name) return;

  const existing = findComboItem(kind, name);
  if (existing) { text.value = existing.name; return; }   // normalise casing

  c.open(prefix, name);
}

/** Before saving a product: an unmatched typed name must not be silently dropped. */
function guardCombos(prefix) {
  for (const kind of ['brand', 'category']) {
    const c = COMBOS[kind];
    const text = document.getElementById(c.textId(prefix));
    const name = text.value.trim();
    if (name && !findComboItem(kind, name)) {
      text.style.borderColor = 'var(--red)';
      c.open(prefix, name);
      return false;
    }
    syncCombo(prefix, kind);
  }
  return true;
}

/** Rebuild the suggestion lists after a brand/category is added, edited or deleted. */
function refreshComboOptions() {
  for (const kind of Object.keys(COMBOS)) {
    const dl = document.getElementById(COMBOS[kind].listId);
    if (!dl) continue;
    dl.innerHTML = '';
    COMBOS[kind].items().forEach(x => {
      const o = document.createElement('option');
      o.value = x.name;
      dl.appendChild(o);
    });
  }
}
</script>
<script>
/* ── Deep link from the header search: /admin/inventory?open={id} opens that product's details modal ── */
(function () {
  const params = new URLSearchParams(window.location.search);
  const id = params.get('open');
  if (!id) return;

  // Drop the param so a refresh or Back doesn't re-open the modal.
  params.delete('open');
  const qs = params.toString();
  history.replaceState(null, '', window.location.pathname + (qs ? '?' + qs : '') + window.location.hash);

  const card = Array.from(document.querySelectorAll('.med-card')).find(c => c.dataset.id === id);
  if (!card) {
    if (typeof showToast === 'function') showToast('That product could not be found.');
    return;
  }
  card.scrollIntoView({ block: 'center' });
  openViewModal(card);
})();
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
      links[active].click();
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
/* On the Inventory page a product suggestion opens its details modal right here,
   without reloading. Staff suggestions still navigate to the Staffs page. */
(function () {
  const panel = document.getElementById('adminSearchPanel');
  if (!panel) return;
  const PRODUCT_PREFIX = "{{ route('admin.inventory') }}?open=";

  panel.addEventListener('click', e => {
    const link = e.target.closest('a.sp-item');
    if (!link || !link.href.startsWith(PRODUCT_PREFIX)) return;

    const id   = new URL(link.href).searchParams.get('open');
    const card = Array.from(document.querySelectorAll('.med-card')).find(c => c.dataset.id === id);
    if (!card) return;                       // not on the page → fall back to normal navigation

    e.preventDefault();
    panel.hidden = true;
    card.scrollIntoView({ block: 'center' });
    openViewModal(card);
  });
})();
</script>
</body>
</html>