<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $siteName }} — Settings</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --red:        #2D7A2D;
  --red-h:      #246124;
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
}
.user-avatar { overflow: hidden; }
.user-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
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
.notif-pill { font-size: 11px; font-weight: 600; color: #2D7A2D; background: rgba(45,122,45,.1); padding: 2px 8px; border-radius: 20px; }
.notif-link-btn { background: none; border: none; cursor: pointer; font: inherit; font-size: 11.5px; font-weight: 600; color: var(--muted, #666); padding: 0; }
.notif-link-btn:hover { color: #2D7A2D; }
.notif-list { max-height: 400px; overflow-y: auto; }
.notif-section { padding: 7px 16px; font-size: 10.5px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--muted, #666); background: var(--surface2, #f7f8fa); border-bottom: 1px solid var(--border, #e5e7eb); }
.notif-section.important { color: #2D7A2D; }
.notif-item { display: flex; gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--border, #e5e7eb); cursor: pointer; border-left: 3px solid transparent; position: relative; }
.notif-item:hover { background: var(--hover, rgba(0,0,0,.03)); }
.notif-item.important { border-left-color: var(--tone); background: color-mix(in srgb, var(--tone) 5%, transparent); }
.notif-item.read:not(.important) { opacity: .6; }
.notif-ico { width: 36px; height: 36px; flex-shrink: 0; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--tone); background: color-mix(in srgb, var(--tone) 13%, transparent); }
.notif-body { flex: 1; min-width: 0; }
.notif-title { font-size: 12.5px; font-weight: 600; color: var(--text, #111); display: flex; align-items: center; gap: 6px; }
.notif-unread { width: 7px; height: 7px; border-radius: 50%; background: #2D7A2D; flex-shrink: 0; }
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
  /* Modal Styles */
  .modal-backdrop {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s ease;
  }
  .modal-backdrop.active {
    opacity: 1;
    pointer-events: auto;
  }
  .modal-window {
    background: #fff;
    width: 90%;
    max-width: 400px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    transform: translateY(20px);
    transition: transform 0.2s ease;
    overflow: hidden;
  }
  .modal-backdrop.active .modal-window {
    transform: translateY(0);
  }
  html[data-theme="dark"] .modal-window {
    background: #1e1e1e;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
  }
  .modal-header {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .modal-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text);
    margin: 0;
  }
  .modal-close {
    background: none;
    border: none;
    color: var(--muted);
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .modal-close:hover {
    background: rgba(128,128,128,0.1);
    color: var(--text);
  }
  .modal-body {
    padding: 12px 0;
  }
  .theme-option {
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    color: var(--text);
    transition: background 0.15s;
  }
  .theme-option:hover {
    background: rgba(128,128,128,0.05);
  }
  .theme-option.selected {
    font-weight: 600;
    color: #2D7A2D;
  }
  .check-icon {
    display: none;
    color: #2D7A2D;
  }
  .theme-option.selected .check-icon {
    display: block;
  }

  /* Log / toolbar styles (kept for parity with the admin settings page) */
  .log-list {
    max-height: 350px;
    overflow-y: auto;
  }
  .log-entry {
    padding: 12px 20px;
    border-bottom: 1px solid var(--border);
  }
  .log-entry:last-child {
    border-bottom: none;
  }
  .log-time {
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 4px;
  }
  .log-action {
    font-size: 14px;
    color: var(--text);
    line-height: 1.4;
  }
  .log-meta { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; margin-top: 6px; font-size: 11.5px; color: var(--muted); }
  .log-time { cursor: default; }
  .al-total { font-size: 12px; font-weight: 600; color: var(--muted); margin-left: 6px; }
  .al-bar { display: flex; flex-wrap: wrap; gap: 8px; padding: 12px 20px; border-bottom: 1px solid var(--border); }
  .al-input, .al-select {
    height: 34px; padding: 0 10px; font-family: inherit; font-size: 13px; color: var(--text);
    background: var(--input-bg); border: 1.5px solid var(--border); border-radius: 7px; outline: none;
    transition: border-color .2s;
  }
  .al-input:focus, .al-select:focus { border-color: #2D7A2D; }
  .al-input[type="search"] { flex: 1 1 100%; }
  .al-select { flex: 1 1 120px; min-width: 0; }
  .al-date { flex: 1 1 120px; min-width: 0; }
  .al-empty { padding: 32px 20px; text-align: center; font-size: 13px; color: var(--muted); }
  .al-more { padding: 12px 20px; text-align: center; }
  .al-more button { background: none; border: none; cursor: pointer; font: inherit; font-size: 12.5px; font-weight: 600; color: #2D7A2D; }
  .al-more button:disabled { opacity: .5; cursor: default; }

  /* Shared List Option Styles (used for Notifications & Security) */
  .list-option {
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border);
  }
  .list-option.clickable:hover {
    background: rgba(128,128,128,0.05);
  }
  .list-option:last-child {
    border-bottom: none;
  }
  .list-text strong {
    display: block;
    font-size: 14px;
    color: var(--text);
    font-weight: 500;
  }
  .list-text span {
    display: block;
    font-size: 12px;
    color: var(--muted);
    margin-top: 4px;
  }

  /* Toggle Switch */
  .toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
    flex-shrink: 0;
  }
  .toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #ccc;
    transition: .3s;
    border-radius: 24px;
  }
  .slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
  }
  .toggle-switch input:checked + .slider {
    background-color: #2D7A2D;
  }
  .toggle-switch input:focus + .slider {
    box-shadow: 0 0 1px #2D7A2D;
  }
  .toggle-switch input:checked + .slider:before {
    transform: translateX(20px);
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
  <a class="nav-item" href="{{ route('staff.reports') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
    Reports
  </a>
  <a class="nav-item" href="{{ route('staff.expiry') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    Expiry Tracking<span class="nav-badge">2</span>
  </a>

  <a class="nav-item active" href="{{ route('staff.settings') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    Settings
  </a>

  <a href = "{{ route('staff.profile') }}" style = "text-decoration: none;">
  <div class="sidebar-bottom">
      <div class="user-chip">
        <div class="user-avatar">
          @if($staff->profile_picture)
            <img src="{{ asset($staff->profile_picture) }}" alt="{{ $staff->first_name ?? 'Staff' }} {{ $staff->last_name ?? 'User' }}" />
          @else
            {{ strtoupper(substr($staff->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($staff->last_name ?? 'T', 0, 1)) }}
          @endif
        </div>
        <div class="user-info">
          <div class="user-name">{{ $staff->first_name ?? 'Staff' }} {{ $staff->last_name ?? 'User' }}</div>
          <div class="user-role">Pharmacy Staff</div>
        </div>
      </div>
  </div>
</a>

</aside>
<header class="header">
  <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="header-title">Settings</div>
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

<div style="margin-bottom:20px;">
  <div style="font-size:20px;font-weight:800;color:var(--text);">Settings</div>
  <div style="font-size:13px;color:var(--muted);margin-top:3px;">Configure system preferences.</div>
</div>
<div class="bottom-grid">
  
  <div class="card" style="grid-column: 1 / -1;">
    <div class="card-head"><h3>Account & Preferences</h3></div>
    <div style="display:flex;flex-direction:column;">
      
      <div id="themeTrigger" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:var(--text);">Appearance</span>
        <span style="color:var(--muted);font-size:14px;"><span id="currentThemeLabel">Light Mode</span> ›</span>
      </div>
      
      <div id="activityLogTrigger" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:var(--text);">Activity Log</span>
        <span style="color:var(--muted);font-size:14px;">›</span>
      </div>
      
      <div id="notificationsTrigger" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:var(--text);">Notifications</span>
        <span style="color:var(--muted);font-size:14px;">›</span>
      </div>
      

      <div id="helpTrigger" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:var(--text);">Help</span>
        <span style="color:var(--muted);font-size:14px;">›</span>
      </div>
      

      <div id="tosTrigger" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:var(--text);">Terms of Service</span>
        <span style="color:var(--muted);font-size:14px;">›</span>
      </div>

      <div id="logoutTrigger" style="padding:16px 20px;display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:#2D7A2D;">Logout</span>
        <span style="color:var(--muted);font-size:14px;">›</span>
      </div>

      <form id="logoutForm" method="POST" action="{{ route('staff.logout') }}" style="display:none;">
        @csrf
      </form>
    </div>
  </div>

</div>

</main>

<div class="modal-backdrop" id="themeModal">
  <div class="modal-window">
    <div class="modal-header">
      <h3 class="modal-title">Appearance</h3>
      <button class="modal-close" id="closeThemeModal">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="theme-option" data-value="light">
        <span style="display:flex;align-items:center;gap:10px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg> Light Mode</span>
        <svg class="check-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div class="theme-option" data-value="dark">
        <span style="display:flex;align-items:center;gap:10px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg> Dark Mode</span>
        <svg class="check-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
    </div>
  </div>
</div>

<div class="modal-backdrop" id="activityLogModal">
  <div class="modal-window" style="max-width: 640px;">
    <div class="modal-header">
      <h3 class="modal-title">Activity Log <span class="al-total" id="alTotal"></span></h3>
      <button class="modal-close" id="closeActivityLogModal">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="al-bar">
      <input type="search" id="alSearch" class="al-input" placeholder="Search activity…" maxlength="100" autocomplete="off" aria-label="Search activity log">
      <select id="alModule" class="al-select" aria-label="Filter by module"><option value="all">All modules</option></select>
      <select id="alAction" class="al-select" aria-label="Filter by action"><option value="all">All actions</option></select>
      <input type="date" id="alFrom" class="al-input al-date" aria-label="From date" title="From date">
      <input type="date" id="alTo" class="al-input al-date" aria-label="To date" title="To date">
    </div>
    <div class="modal-body log-list" id="alList" style="padding: 0;">
      <div class="al-empty">Loading…</div>
    </div>
  </div>
</div>

<div class="modal-backdrop" id="notificationsModal">
  <div class="modal-window" style="max-width: 600px;">
    <div class="modal-header">
      <h3 class="modal-title">Notifications</h3>
      <button class="modal-close" id="closeNotificationsModal">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="nf-bar">
      <div class="nf-tabs">
        <button type="button" class="nf-tab active" data-filter="all">All</button>
        <button type="button" class="nf-tab" data-filter="unread">Unread <span id="nfUnreadCount"></span></button>
        <button type="button" class="nf-tab" data-filter="important">Important</button>
      </div>
      <button type="button" class="nf-link" id="nfReadAll" disabled>Mark all read</button>
    </div>
    <div class="modal-body nf-list" id="nfList" style="padding: 0;">
      <div class="nf-empty">Loading…</div>
    </div>
  </div>
</div>

<div class="modal-backdrop" id="securityModal">
  <div class="modal-window">
    <div class="modal-header">
      <h3 class="modal-title">Security Settings</h3>
      <button class="modal-close" id="closeSecurityModal">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body" style="padding: 0;">
      <div class="list-option">
        <div class="list-text">
          <strong>Two-Factor Authentication (2FA)</strong>
          <span>Require a verification code when logging in</span>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" id="tfaToggle">
          <span class="slider"></span>
        </label>
      </div>
      <div class="list-option clickable" style="cursor: pointer;" id="changePwdBtn">
        <div class="list-text">
          <strong>Change Password</strong>
          <span>Update your current login password</span>
        </div>
        <span style="color:var(--muted);font-size:16px;">›</span>
      </div>
      <div class="list-option clickable" style="cursor: pointer;" id="logoutDevicesBtn">
        <div class="list-text">
          <strong style="color: #2D7A2D;">Log out of all devices</strong>
          <span>End all active sessions immediately</span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal-backdrop" id="helpModal">
  <div class="modal-window modal-doc">
    <div class="modal-header">
      <h3 class="modal-title">Help &amp; Support</h3>
      <button class="modal-close" id="closeHelpModal" aria-label="Close">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body doc-body" style="padding: 0;">
      <p class="doc-intro">Quick guides for each section of the {{ $siteName }} Staff Portal. Tap a topic to expand it.</p>
      <details class="doc-item">
        <summary>
          <span class="doc-ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg></span>
          <span class="doc-text"><strong>Dashboard</strong><span>Overview of sales, stock and activity</span></span>
          <svg class="doc-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </summary>
        <ul class="doc-steps"><li>Use the dashboard for a quick snapshot of the pharmacy’s performance.</li><li>Sidebar badges beside <b>Inventory</b> and <b>Expiry Tracking</b> flag items that need attention.</li></ul>
      </details>
      <details class="doc-item">
        <summary>
          <span class="doc-ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></span>
          <span class="doc-text"><strong>Inventory</strong><span>Medicines, brands, categories and badges</span></span>
          <svg class="doc-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </summary>
        <ul class="doc-steps"><li>Click <b>+ Add Product</b> to create a medicine, then <b>Save Medicine</b>.</li><li>Use <b>Edit</b> to change details, or <b>Yes, Delete</b> to remove a product after confirming.</li><li>Create <b>Brands</b>, <b>Categories</b> and <b>Badges</b> here so they can be assigned to medicines.</li><li>Mark a product as prescription-required so customers must upload a prescription when ordering.</li></ul>
      </details>
      <details class="doc-item">
        <summary>
          <span class="doc-ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg></span>
          <span class="doc-text"><strong>Sales Records</strong><span>Incoming orders and receipts</span></span>
          <svg class="doc-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </summary>
        <ul class="doc-steps"><li>Orders are grouped by tab: <b>Pending</b>, <b>For Pick Up</b>, <b>Confirmed</b>, <b>Completed</b> and <b>Cancelled</b>.</li><li>Open an order’s <b>Details</b> to review items and any uploaded <b>Prescription</b> files before approving.</li><li>Choose <b>Approve</b> to confirm an order and generate a receipt, or <b>Reject</b> / <b>Cancel Order</b> if it can’t be fulfilled.</li><li>Use <b>Process Refund</b> for returns, and <b>Print Receipt</b> for a customer copy.</li></ul>
      </details>
      <details class="doc-item">
        <summary>
          <span class="doc-ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg></span>
          <span class="doc-text"><strong>Stocks &amp; Expiry</strong><span>Batches, quantities and expiring medicines</span></span>
          <svg class="doc-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </summary>
        <ul class="doc-steps"><li>In <b>Stocks</b>, use <b>+ Add Stock</b> to record a new batch with quantity and expiry date.</li><li>Check <b>Batch Inventory</b> to see the available batches for each product.</li><li>Open <b>Expiry Tracking</b> regularly to review <b>Expiring Soon</b> and expired batches so they can be removed from sale.</li></ul>
      </details>
      <details class="doc-item">
        <summary>
          <span class="doc-ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span>
          <span class="doc-text"><strong>Reports</strong><span>Sales and stock reports</span></span>
          <svg class="doc-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </summary>
        <ul class="doc-steps"><li>Open <b>Reports</b> to review sales and stock figures for a chosen period.</li><li>Use the available filters to narrow the data, then export it if you need a copy.</li></ul>
      </details>
      <details class="doc-item">
        <summary>
          <span class="doc-ico"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></span>
          <span class="doc-text"><strong>Settings &amp; Account</strong><span>Appearance, notifications and profile</span></span>
          <svg class="doc-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
        </summary>
        <ul class="doc-steps"><li>Switch between Light and Dark mode under <b>Appearance</b> (or with the sun/moon button in the header).</li><li>Review alerts under <b>Notifications</b>; unhandled items are pinned under <b>Needs attention</b>.</li><li>Update your photo, details and password from your <b>Profile</b> page.</li></ul>
      </details>

    </div>
  </div>
</div>

<div class="modal-backdrop" id="feedbackModal">
  <div class="modal-window">
    <div class="modal-header">
      <h3 class="modal-title">Send Feedback</h3>
      <button class="modal-close" id="closeFeedbackModal">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body" style="padding: 20px;">
      <p style="font-size: 14px; color: var(--muted); margin-top: 0; margin-bottom: 12px;">We'd love to hear your thoughts on how we can improve the system.</p>
      <textarea placeholder="Tell us what you think..." style="width: 100%; height: 120px; padding: 12px; border: 1px solid var(--border); border-radius: 8px; resize: none; margin-bottom: 16px; font-family: inherit; box-sizing: border-box; background: transparent; color: var(--text);"></textarea>
      <button id="submitFeedbackBtn" style="width: 100%; padding: 12px; background: #2D7A2D; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Submit Feedback</button>
    </div>
  </div>
</div>

<div class="modal-backdrop" id="tosModal">
  <div class="modal-window modal-doc">
    <div class="modal-header">
      <h3 class="modal-title">Terms of Service</h3>
      <button class="modal-close" id="closeTosModal" aria-label="Close">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body doc-body doc-terms">
      <div class="doc-updated">{{ $siteName }} Staff Portal</div>
      <h4 class="doc-h">1. Acceptance of Terms</h4>
      <p class="doc-p">By signing in to the {{ $siteName }} Staff Portal, you agree to these terms. Access is limited to authorized staff of {{ $siteName }}. If you do not agree, do not use the portal.</p>
      <h4 class="doc-h">2. Authorized Use</h4>
      <p class="doc-p">The portal is provided for managing the pharmacy’s inventory, stocks, orders, expiry tracking and reports. You may not use it for any unlawful purpose or to access data you have not been authorized to view.</p>
      <h4 class="doc-h">3. Account &amp; Security</h4>
      <p class="doc-p">You are responsible for all activity under your account. Keep your credentials confidential, do not share your login, enable two-factor authentication where available, and sign out on shared devices. Report any suspected unauthorized access immediately.</p>
      <h4 class="doc-h">4. Medicines, Prescriptions &amp; Orders</h4>
      <p class="doc-p">You must keep product details, prices, prescription requirements, stock quantities and expiry dates accurate. Prescription-required medicines may only be approved after the uploaded prescription has been reviewed. Expired or expiring batches must be removed from sale, and orders must be approved, refunded or cancelled in line with applicable pharmacy regulations.</p>
      <h4 class="doc-h">5. Data Privacy</h4>
      <p class="doc-p">Customer details, prescription files and order history are confidential. Handle them only as needed for your duties, in compliance with applicable medical and data protection laws, and do not copy, export or disclose them without authorization.</p>
      <h4 class="doc-h">6. Reports &amp; Exports</h4>
      <p class="doc-p">Report data and any exported files must be stored securely and shared only with authorized persons.</p>
      <h4 class="doc-h">7. Activity Monitoring</h4>
      <p class="doc-p">Actions taken in the portal may be recorded for security, auditing and accountability.</p>
      <h4 class="doc-h">8. Suspension or Termination</h4>
      <p class="doc-p">Access may be suspended or removed at any time for violations of these terms or when your role ends.</p>
    </div>
  </div>
</div>

<style>
  /* Help & Terms modals (shared document-style layout) */
  .modal-window.modal-doc { max-width: 560px; display: flex; flex-direction: column; max-height: 85vh; }
  .modal-window.modal-doc .modal-header { flex: none; }
  .doc-body { overflow-y: auto; flex: 1; }
  .doc-intro { padding: 14px 20px; font-size: 13px; color: var(--muted); border-bottom: 1px solid var(--border); line-height: 1.5; }
  .doc-item { border-bottom: 1px solid var(--border); }
  .doc-item summary {
    list-style: none; cursor: pointer;
    display: flex; align-items: center; gap: 12px;
    padding: 14px 20px; transition: background .15s;
  }
  .doc-item summary::-webkit-details-marker { display: none; }
  .doc-item summary:hover { background: rgba(128,128,128,0.05); }
  .doc-ico {
    width: 32px; height: 32px; flex: none; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    background: var(--red-light, rgba(45,122,45,.1)); color: #2D7A2D;
  }
  html[data-theme="dark"] .doc-ico { background: rgba(45,122,45,.18); }
  .doc-text { flex: 1; min-width: 0; }
  .doc-text strong { display: block; font-size: 14px; font-weight: 600; color: var(--text); }
  .doc-text span { display: block; font-size: 12px; color: var(--muted); margin-top: 2px; }
  .doc-chev { flex: none; color: var(--muted); transition: transform .2s; }
  .doc-item[open] .doc-chev { transform: rotate(180deg); }
  .doc-item[open] summary { background: rgba(128,128,128,0.05); }
  .doc-steps { margin: 0; padding: 4px 20px 16px 64px; font-size: 13px; line-height: 1.6; color: var(--muted); }
  .doc-steps li { margin-bottom: 6px; }
  .doc-steps b { color: var(--text); font-weight: 600; }
  .doc-note { padding: 16px 20px; background: rgba(128,128,128,0.05); }
  .doc-note strong { display: block; font-size: 14px; color: var(--text); font-weight: 600; }
  .doc-note span { display: block; font-size: 12px; color: var(--muted); margin-top: 4px; line-height: 1.5; }
  .doc-note b { color: var(--text); }
  .doc-terms { padding: 20px; font-size: 14px; line-height: 1.6; }
  .doc-updated { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); margin-bottom: 14px; }
  .doc-h { margin: 0 0 6px; font-size: 14px; font-weight: 600; color: var(--text); }
  .doc-p { margin: 0 0 16px; color: var(--muted); }
  .doc-p:last-child { margin-bottom: 0; }

  /* All-notifications list (Settings → Notifications) */
  .nf-bar { display: flex; align-items: center; justify-content: space-between; gap: 8px; padding: 10px 20px; border-bottom: 1px solid var(--border); flex-wrap: wrap; }
  .nf-tabs { display: flex; gap: 6px; }
  .nf-tab { background: none; border: 1px solid var(--border); color: var(--muted); font: inherit; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; cursor: pointer; }
  .nf-tab:hover { color: var(--text); }
  .nf-tab.active { background: #2D7A2D; border-color: #2D7A2D; color: #fff; }
  .nf-link { background: none; border: none; cursor: pointer; font: inherit; font-size: 12px; font-weight: 600; color: #2D7A2D; padding: 0; }
  .nf-link:disabled { opacity: .4; cursor: default; }
  .nf-list { max-height: 60vh; overflow-y: auto; }
  .nf-section { padding: 7px 20px; font-size: 10.5px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); background: var(--surface2); border-bottom: 1px solid var(--border); }
  .nf-section.important { color: #2D7A2D; }
  .nf-item { display: flex; gap: 12px; padding: 12px 20px; border-bottom: 1px solid var(--border); cursor: pointer; border-left: 3px solid transparent; }
  .nf-item:hover { background: var(--hover); }
  .nf-item.important { border-left-color: var(--tone); background: color-mix(in srgb, var(--tone) 5%, transparent); }
  .nf-item.read:not(.important) { opacity: .6; }
  .nf-ico { width: 36px; height: 36px; flex-shrink: 0; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--tone); background: color-mix(in srgb, var(--tone) 13%, transparent); }
  .nf-body { flex: 1; min-width: 0; }
  .nf-title { font-size: 13px; font-weight: 600; color: var(--text); display: flex; align-items: center; gap: 6px; }
  .nf-dot { width: 7px; height: 7px; border-radius: 50%; background: #2D7A2D; flex-shrink: 0; }
  .nf-msg { font-size: 12px; color: var(--muted); margin-top: 2px; line-height: 1.4; }
  .nf-time { font-size: 11px; color: var(--muted); opacity: .8; margin-top: 4px; }
  .nf-empty { padding: 32px 20px; text-align: center; font-size: 13px; color: var(--muted); }
  .nf-more { padding: 12px 20px; text-align: center; }
</style>
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
    readAll: @json(route('staff.notifications.readAll')),
    read:    @json(route('staff.notifications.read', ['notification' => '__ID__'])),
  };
  const CSRF = @json(csrf_token());
  const POLL_MS = 60000;
  const LIMIT = 15;

  let items = [];

  const TONES = { danger: '#2D7A2D', warning: '#e07b00', info: '#2563eb', success: '#CC1F1F', neutral: '#65676b' };
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

  // "View all notifications" → we're already on Settings, so open the full-list modal.
  moreEl.addEventListener('click', () => {
    dropdown.style.display = 'none';
    const trigger = document.getElementById('notificationsTrigger');
    if (trigger) trigger.click();
    else window.location.href = @json(route('staff.notifications.view'));
  });

  // Let the full-list modal refresh the bell after read / mark-all-read.
  window.refreshHeaderNotifications = load;

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
  // Modal interaction logic
  document.addEventListener('DOMContentLoaded', () => {
    // --- Elements ---
    const themeTrigger = document.getElementById('themeTrigger');
    const themeModal = document.getElementById('themeModal');
    const closeThemeBtn = document.getElementById('closeThemeModal');
    const themeOptions = document.querySelectorAll('.theme-option');
    const currentThemeLabel = document.getElementById('currentThemeLabel');
    

    const notifTrigger = document.getElementById('notificationsTrigger');
    const notifModal = document.getElementById('notificationsModal');
    const closeNotifBtn = document.getElementById('closeNotificationsModal');

    const securityTrigger = document.getElementById('securityTrigger');
    const securityModal = document.getElementById('securityModal');
    const closeSecurityBtn = document.getElementById('closeSecurityModal');

    const helpTrigger = document.getElementById('helpTrigger');
    const helpModal = document.getElementById('helpModal');
    const closeHelpBtn = document.getElementById('closeHelpModal');

    // NEW: Elements for Feedback and TOS
    const feedbackTrigger = document.getElementById('feedbackTrigger');
    const feedbackModal = document.getElementById('feedbackModal');
    const closeFeedbackBtn = document.getElementById('closeFeedbackModal');
    const submitFeedbackBtn = document.getElementById('submitFeedbackBtn');

    const tosTrigger = document.getElementById('tosTrigger');
    const tosModal = document.getElementById('tosModal');
    const closeTosBtn = document.getElementById('closeTosModal');
    const logoutTrigger = document.getElementById('logoutTrigger');

    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toastMsg');

    // --- Appearance (Light / Dark) Modal Logic ---
    const syncThemeUI = () => {
      const mode = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
      currentThemeLabel.textContent = mode === 'dark' ? 'Dark Mode' : 'Light Mode';
      themeOptions.forEach(opt => opt.classList.toggle('selected', opt.dataset.value === mode));
    };
    syncThemeUI();
    // Keep in sync when the header sun/moon button is used too
    new MutationObserver(syncThemeUI).observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

    themeTrigger.addEventListener('click', () => themeModal.classList.add('active'));
    closeThemeBtn.addEventListener('click', () => themeModal.classList.remove('active'));
    themeModal.addEventListener('click', (e) => {
      if (e.target === themeModal) themeModal.classList.remove('active');
    });

    themeOptions.forEach(option => {
      option.addEventListener('click', function() {
        const dark = this.dataset.value === 'dark';
        setTheme(dark); // defined in the Theme script above; also saves to localStorage
        themeModal.classList.remove('active');

        if (toast && toastMsg) {
          toastMsg.textContent = `Switched to ${dark ? 'Dark' : 'Light'} Mode`;
          toast.classList.add('on');
          setTimeout(() => toast.classList.remove('on'), 3000);
        }
      });
    });

    // --- Notifications: full list (data comes from StaffNotificationsController@all) ---
    const nfList     = document.getElementById('nfList');
    const nfReadAll  = document.getElementById('nfReadAll');
    const nfUnreadEl = document.getElementById('nfUnreadCount');
    const nfTabs     = document.querySelectorAll('.nf-tab');

    const NF_URLS = {
      all:     @json(route('staff.notifications.all')),
      readAll: @json(route('staff.notifications.readAll')),
      read:    @json(route('staff.notifications.read', ['notification' => '__ID__'])),
    };
    const NF_CSRF = @json(csrf_token());
    const NF_TONES = { danger: '#2D7A2D', warning: '#e07b00', info: '#2563eb', success: '#CC1F1F', neutral: '#65676b' };
    const nfSvg = p => `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${p}</svg>`;
    const NF_ICONS = {
      box:   nfSvg('<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>'),
      alert: nfSvg('<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'),
      clock: nfSvg('<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'),
      order: nfSvg('<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>'),
      check: nfSvg('<polyline points="20 6 9 17 4 12"/>'),
      x:     nfSvg('<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>'),
      money: nfSvg('<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>'),
      file:  nfSvg('<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>'),
      user:  nfSvg('<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'),
      bell:  nfSvg('<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>'),
    };
    const NF_TYPE_ICON = {
      out_of_stock: 'box', low_stock: 'alert', expired_stock: 'x', expiring_stock: 'clock',
      order_pending: 'order', order_confirmed: 'order', order_ready: 'check',
      refund_pending: 'money', payment_proof: 'money', prescription_uploaded: 'file',
      order_completed: 'check', order_cancelled: 'x', new_customer: 'user',
    };
    const nfEsc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    let nfFilter = 'all';
    let nfItems = [];
    let nfNextPage = null;
    let nfLoading = false;

    async function nfApi(url, method = 'GET') {
      const res = await fetch(url, {
        method,
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': NF_CSRF },
        credentials: 'same-origin',
      });
      if (!res.ok) throw new Error(`${url} → ${res.status}`);
      return res.json();
    }

    function nfItemHtml(n) {
      const tone = NF_TONES[n.tone] || NF_TONES.neutral;
      return `
        <div class="nf-item ${n.important ? 'important' : ''} ${n.is_read ? 'read' : ''}" data-id="${n.id}" style="--tone:${tone}">
          <div class="nf-ico">${NF_ICONS[NF_TYPE_ICON[n.type]] || NF_ICONS.bell}</div>
          <div class="nf-body">
            <div class="nf-title">${nfEsc(n.title)}${n.is_read ? '' : '<span class="nf-dot"></span>'}</div>
            <div class="nf-msg">${nfEsc(n.message)}</div>
            <div class="nf-time">${nfEsc(n.time)}</div>
          </div>
        </div>`;
    }

    function nfRender(more) {
      if (!nfItems.length) {
        nfList.innerHTML = `<div class="nf-empty">${nfFilter === 'all' ? 'You\'re all caught up 🎉' : 'Nothing to show here.'}</div>`;
        return;
      }
      const pinned = nfItems.filter(n => n.important);
      const rest   = nfItems.filter(n => !n.important);
      let html = '';
      if (pinned.length) html += `<div class="nf-section important">Needs attention · ${pinned.length}</div>` + pinned.map(nfItemHtml).join('');
      if (rest.length) {
        if (pinned.length) html += '<div class="nf-section">Recent activity</div>';
        html += rest.map(nfItemHtml).join('');
      }
      if (nfNextPage) html += '<div class="nf-more"><button type="button" class="nf-link" id="nfLoadMore">Load more</button></div>';
      nfList.innerHTML = html;
    }

    async function nfLoad(page = 1) {
      if (nfLoading) return;
      nfLoading = true;
      try {
        const data = await nfApi(`${NF_URLS.all}?filter=${nfFilter}&page=${page}`);
        nfItems = page === 1 ? data.items : nfItems.concat(data.items);
        nfNextPage = data.next_page;
        nfReadAll.disabled = !(data.unread > 0);
        nfUnreadEl.textContent = data.unread > 0 ? `(${data.unread})` : '';
        nfRender();
        if (window.refreshHeaderNotifications) window.refreshHeaderNotifications();
      } catch (err) {
        console.error(err);
        if (page === 1) nfList.innerHTML = '<div class="nf-empty">Couldn\'t load notifications.</div>';
      } finally {
        nfLoading = false;
      }
    }

    function openNotifications() {
      notifModal.classList.add('active');
      nfLoad(1);
    }

    notifTrigger.addEventListener('click', openNotifications);
    closeNotifBtn.addEventListener('click', () => notifModal.classList.remove('active'));
    notifModal.addEventListener('click', (e) => {
      if (e.target === notifModal) notifModal.classList.remove('active');
    });

    nfTabs.forEach(tab => tab.addEventListener('click', () => {
      nfFilter = tab.dataset.filter;
      nfTabs.forEach(t => t.classList.toggle('active', t === tab));
      nfLoad(1);
    }));

    nfReadAll.addEventListener('click', async () => {
      try { await nfApi(NF_URLS.readAll, 'PUT'); } catch (err) { console.error(err); }
      nfLoad(1);
    });

    nfList.addEventListener('click', async (e) => {
      if (e.target.closest('#nfLoadMore')) { nfLoad(nfNextPage); return; }
      const row = e.target.closest('.nf-item');
      if (!row) return;
      const n = nfItems.find(x => String(x.id) === row.dataset.id);
      if (!n) return;
      try { if (!n.is_read) await nfApi(NF_URLS.read.replace('__ID__', n.id), 'PUT'); } catch (err) { console.error(err); }
      if (n.link) window.location.href = n.link; else nfLoad(1);
    });

    // Arrived from the bell's "View all notifications" → open the list right away.
    if (new URLSearchParams(window.location.search).get('open') === 'notifications') {
      openNotifications();
      history.replaceState(null, '', window.location.pathname);
    }

    // --- Security Modal Logic ---
    if (securityTrigger && securityModal && closeSecurityBtn) {
      securityTrigger.addEventListener('click', () => securityModal.classList.add('active'));
      closeSecurityBtn.addEventListener('click', () => securityModal.classList.remove('active'));
      securityModal.addEventListener('click', (e) => {
        if (e.target === securityModal) securityModal.classList.remove('active');
      });
    }

    const tfaToggle = document.getElementById('tfaToggle');
    if (tfaToggle) {
      tfaToggle.addEventListener('change', (e) => {
        const status = e.target.checked ? 'enabled' : 'disabled';
        if (toast && toastMsg) {
          toastMsg.textContent = `Two-Factor Authentication ${status}.`;
          toast.classList.add('show');
          setTimeout(() => toast.classList.remove('show'), 3000); 
        }
      });
    }

    const logoutDevicesBtn = document.getElementById('logoutDevicesBtn');
    if (logoutDevicesBtn) {
      logoutDevicesBtn.addEventListener('click', () => {
        securityModal.classList.remove('active');
        if (toast && toastMsg) {
          toastMsg.textContent = `Successfully logged out of all other devices.`;
          toast.classList.add('show');
          setTimeout(() => toast.classList.remove('show'), 3000); 
        }
      });
    }

    // --- Help Modal Logic ---
    if (helpTrigger && helpModal && closeHelpBtn) {
      helpTrigger.addEventListener('click', () => helpModal.classList.add('active'));
      closeHelpBtn.addEventListener('click', () => helpModal.classList.remove('active'));
      helpModal.addEventListener('click', (e) => {
        if (e.target === helpModal) helpModal.classList.remove('active');
      });
    }

    // --- NEW: Feedback Modal Logic ---
    if (feedbackTrigger && feedbackModal && closeFeedbackBtn) {
      feedbackTrigger.addEventListener('click', () => feedbackModal.classList.add('active'));
      closeFeedbackBtn.addEventListener('click', () => feedbackModal.classList.remove('active'));
      feedbackModal.addEventListener('click', (e) => {
        if (e.target === feedbackModal) feedbackModal.classList.remove('active');
      });

      // Handle Submit button feedback
      if (submitFeedbackBtn) {
        submitFeedbackBtn.addEventListener('click', () => {
          feedbackModal.classList.remove('active');
          if (toast && toastMsg) {
            toastMsg.textContent = 'Feedback successfully sent. Thank you!';
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000); 
          }
        });
      }
    }

    // --- NEW: Terms of Service Modal Logic ---
    if (tosTrigger && tosModal && closeTosBtn) {
      tosTrigger.addEventListener('click', () => tosModal.classList.add('active'));
      closeTosBtn.addEventListener('click', () => tosModal.classList.remove('active'));
      tosModal.addEventListener('click', (e) => {
        if (e.target === tosModal) tosModal.classList.remove('active');
      });
    }

    // --- Logout Logic ---
    const logoutForm = document.getElementById('logoutForm');
    if (logoutTrigger && logoutForm) {
      logoutTrigger.addEventListener('click', () => {
        // POSTs to staff.logout (StaffController@logout) with the CSRF token
        logoutForm.submit();
      });
    }
  });
</script>

<script>
/* ══════════════════════════════════════════════════════
   ACTIVITY LOG — data comes from StaffSettingsController@logs
   Every create / update / delete / approve / login / logout made in the
   staff portal is recorded by the LogStaffActivity middleware.
   ══════════════════════════════════════════════════════ */
(function () {
  const modal   = document.getElementById('activityLogModal');
  const trigger = document.getElementById('activityLogTrigger');
  if (!modal || !trigger) return;

  const closeBtn = document.getElementById('closeActivityLogModal');
  const listEl   = document.getElementById('alList');
  const totalEl  = document.getElementById('alTotal');
  const searchEl = document.getElementById('alSearch');
  const moduleEl = document.getElementById('alModule');
  const actionEl = document.getElementById('alAction');
  const fromEl   = document.getElementById('alFrom');
  const toEl     = document.getElementById('alTo');
  const LOGS_URL = @json(route('staff.settings.logs'));

  let nextPage = null, loading = false, timer = null, seq = 0, rows = [];

  const esc = s => String(s ?? '').replace(/[&<>"']/g,
    c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const cap = s => s ? s.charAt(0).toUpperCase() + s.slice(1) : s;

  const entryHtml = l => `
    <div class="log-entry">
      <div class="log-time" title="${esc(l.full_time)}">${esc(l.time)}</div>
      <div class="log-action"><strong>${esc(l.staff)}</strong> ${esc(l.description)}.</div>
      <div class="log-meta">
        <span class="pill ${esc(l.tone)}">${esc(cap(l.action))}</span>
        <span>${esc(l.module)}</span>${l.ip ? `<span>· ${esc(l.ip)}</span>` : ''}
      </div>
    </div>`;

  function fillOptions(select, values, allLabel, labeler) {
    const current = select.value;
    select.innerHTML = `<option value="all">${allLabel}</option>` +
      (values || []).map(v => `<option value="${esc(v)}">${esc(labeler ? labeler(v) : v)}</option>`).join('');
    select.value = [...select.options].some(o => o.value === current) ? current : 'all';
  }

  function render() {
    if (!rows.length) {
      listEl.innerHTML = '<div class="al-empty">No activity found.</div>';
      return;
    }
    listEl.innerHTML = rows.map(entryHtml).join('') +
      (nextPage ? '<div class="al-more"><button type="button" id="alMore">Load more</button></div>' : '');
  }

  async function load(page = 1) {
    if (loading && page > 1) return;
    loading = true;
    const mine = ++seq;                       // ignore responses from superseded requests
    const params = new URLSearchParams({ page });
    if (searchEl.value.trim())       params.set('search', searchEl.value.trim());
    if (moduleEl.value !== 'all')    params.set('module', moduleEl.value);
    if (actionEl.value !== 'all')    params.set('action', actionEl.value);
    if (fromEl.value)                params.set('date_from', fromEl.value);
    if (toEl.value)                  params.set('date_to', toEl.value);

    try {
      const res = await fetch(`${LOGS_URL}?${params}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
      });
      if (!res.ok) throw new Error(`Activity log → ${res.status}`);
      const data = await res.json();
      if (mine !== seq) return;

      rows = page === 1 ? data.logs : rows.concat(data.logs);
      nextPage = data.next_page;
      totalEl.textContent = `(${data.total})`;

      if (data.modules) fillOptions(moduleEl, data.modules, 'All modules');
      if (data.actions) fillOptions(actionEl, data.actions, 'All actions', cap);
      render();
    } catch (err) {
      console.error(err);
      if (mine === seq && page === 1) listEl.innerHTML = '<div class="al-empty">Couldn\'t load the activity log.</div>';
    } finally {
      if (mine === seq) loading = false;
    }
  }

  // Open / close — opening always shows fresh data.
  trigger.addEventListener('click', () => { modal.classList.add('active'); load(1); });
  closeBtn.addEventListener('click', () => modal.classList.remove('active'));
  modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('active'); });

  searchEl.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(() => load(1), 300); });
  [moduleEl, actionEl, fromEl, toEl].forEach(el => el.addEventListener('change', () => load(1)));

  listEl.addEventListener('click', e => {
    const more = e.target.closest('#alMore');
    if (more && nextPage) { more.disabled = true; load(nextPage); }
  });
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

</body>
</html>