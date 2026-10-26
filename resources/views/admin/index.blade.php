<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $siteName }} — Dashboard</title>
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
  transition: background .3s, box-shadow .2s, transform .2s;
  cursor: default;
}
.stat-card:hover { box-shadow: var(--shadow-md); }
a.stat-card-link {
  display: block;
  text-decoration: none;
  color: inherit;
  cursor: pointer;
}
a.stat-card-link:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
a.stat-card-link:focus-visible { outline: 2px solid var(--red, #e53935); outline-offset: 2px; }

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
.btn-sm.range-btn.active { border-color: var(--red); color: var(--red); }
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
      <img src="{{ $logo2 ? asset($logo2) : '' }}" alt="{{ $siteName }}">
    </div>
    <div class="sidebar-logo-text">
      <strong>{{ $siteName }}</strong>
      <span>Admin Portal</span>
    </div>
  </a>

  <div class="sidebar-section-label">Main</div>
  <a class="nav-item active" href="{{ route('admin.dashboard') }}">
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
  <div class="header-title">Dashboard</div>
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

<div style="margin-bottom:20px;">
  <div style="font-size:20px;font-weight:800;color:var(--text);">Welcome Back, {{ auth()->user()->first_name ?? 'Admin' }}</div>
  <div style="font-size:13px;color:var(--muted);margin-top:3px;">Here's what's happening at {{ $siteName }} today.</div>
</div>

<div class="stats-grid">
  <a class="stat-card stat-card-link" href="{{ route('admin.inventory') }}">
    <div class="stat-top">
      <div class="stat-icon red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></div>
      <div class="stat-trend up">↑ 4%</div>
    </div>
    <div class="stat-value">{{ number_format($totalMedicines) }}</div>
    <div class="stat-label">Total Products</div>
  </a>
  <a class="stat-card stat-card-link" href="{{ route('admin.orders') }}">
    <div class="stat-top">
      <div class="stat-icon green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
      <div class="stat-trend up" id="todaySalesTrend" style="display:none;"></div>
    </div>
    <div class="stat-value" id="todaySalesValue">₱0</div>
    <div class="stat-label">Today's Sales</div>
  </a>
  <a class="stat-card stat-card-link" href="{{ route('admin.stocks', ['status' => 'low-stock']) }}">
    <div class="stat-top">
      <div class="stat-icon orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
      <div class="stat-trend down">↑ 2</div>
    </div>
    <div class="stat-value">{{ number_format($lowStockItems) }}</div>
    <div class="stat-label">Low Stock Items</div>
  </a>
  <a class="stat-card stat-card-link" href="{{ route('admin.expiry') }}">
    <div class="stat-top">
      <div class="stat-icon blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
      <div class="stat-trend down">≤ 10 days</div>
    </div>
    <div class="stat-value">{{ number_format($expiringStocks) }}</div>
    <div class="stat-label">Expiring Stocks</div>
  </a>
</div>

<div class="content-grid">
  <div class="card">
    <div class="card-head">
      <h3>Weekly Sales Overview</h3>
      <div class="card-head-right">
        <button class="btn-sm range-btn active" data-range="week">This Week</button>
        <button class="btn-sm range-btn" data-range="month">Month</button>
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
      <div class="legend-item" style="margin-left:auto;font-weight:700;color:var(--text);">Total: <span id="weeklyTotal">₱0</span></div>
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
          <div style="font-size:26px;font-weight:800;color:var(--text);line-height:1;" id="donutTotal">0</div>
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
            <span id="completedCount" style="font-size:12px;font-weight:700;color:var(--text);">0</span>
            <span id="completedPct" style="font-size:10px;color:var(--subtle);width:32px;text-align:right;">0%</span>
          </div>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;">
          <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:10px;height:10px;border-radius:50%;background:#e07b00;flex-shrink:0;"></div>
            <span style="font-size:12px;color:var(--muted);font-weight:500;">Pending</span>
          </div>
          <div style="display:flex;align-items:center;gap:8px;">
            <span id="pendingCount" style="font-size:12px;font-weight:700;color:var(--text);">0</span>
            <span id="pendingPct" style="font-size:10px;color:var(--subtle);width:32px;text-align:right;">0%</span>
          </div>
        </div>
        <div style="display:flex;align-items:center;justify-content:space-between;">
          <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:10px;height:10px;border-radius:50%;background:#CC1F1F;flex-shrink:0;"></div>
            <span style="font-size:12px;color:var(--muted);font-weight:500;">Cancelled</span>
          </div>
          <div style="display:flex;align-items:center;gap:8px;">
            <span id="cancelledCount" style="font-size:12px;font-weight:700;color:var(--text);">0</span>
            <span id="cancelledPct" style="font-size:10px;color:var(--subtle);width:32px;text-align:right;">0%</span>
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
        <div style="font-size:11px;font-weight:700;color:var(--text);"><span id="revTotal">₱0</span></div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-head"><h3>Recent Transactions</h3><a href="{{ route('admin.stocks') }}" class="btn-sm">See All</a></div>
    <div class="tx-list" id="txList"><div class="tx-item"><div class="tx-info"><div class="tx-time">Loading…</div></div></div></div>
  </div>
  </div>
</div>

<div style="text-align:center;font-size:12px;color:var(--subtle);padding:8px 0 4px;">
  {{ $siteName }} Inventory System &nbsp;·&nbsp; Zone 2, Sogod, Southern Leyte &nbsp;·&nbsp; Admin Access Only
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
// ── Dashboard charts: data comes from AdminChartController (JSON) ─────────────
const CHART_URLS = {
  sales:        @json(route('admin.charts.sales')),
  orderStatus:  @json(route('admin.charts.orderStatus')),
  revenue:      @json(route('admin.charts.revenueByProduct')),
  transactions: @json(route('admin.charts.recentTransactions')),
};
const SVG_NS = 'http://www.w3.org/2000/svg';
const peso = n => '₱' + Number(n).toLocaleString(undefined, { maximumFractionDigits: 2 });
const esc  = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

async function getJson(url, params = {}) {
  const qs = new URLSearchParams(params).toString();
  const res = await fetch(qs ? `${url}?${qs}` : url, {
    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    credentials: 'same-origin',
  });
  if (!res.ok) throw new Error(`${url} → ${res.status}`);
  return res.json();
}
function svgEl(name, attrs) {
  const el = document.createElementNS(SVG_NS, name);
  Object.entries(attrs).forEach(([k, v]) => el.setAttribute(k, v));
  return el;
}

// ── Weekly Sales Overview (line chart) ────────────────────────────────────────
const lineSvg    = document.getElementById('lineChart');
const dotGroup   = document.getElementById('dotGroup');
const tooltip    = document.getElementById('chartTooltip');
const labelsEl   = document.getElementById('dayLabels');
const LW = 560, LH = 160, L_PAD_T = 15, L_PAD_B = 5;
const L_CH = LH - L_PAD_T - L_PAD_B;

function smoothPath(pts) {
  if (pts.length < 2) return '';
  let d = `M ${pts[0][0]} ${pts[0][1]}`;
  for (let i = 0; i < pts.length - 1; i++) {
    const [x0, y0] = pts[i], [x1, y1] = pts[i + 1];
    const cpx = (x0 + x1) / 2;
    d += ` C ${cpx} ${y0}, ${cpx} ${y1}, ${x1} ${y1}`;
  }
  return d;
}

function positionTooltip(tip, svg, W, H, cx, cy) {
  const rect = svg.getBoundingClientRect();
  const px = cx * rect.width / W, py = cy * rect.height / H;
  const tw = tip.offsetWidth || 110, th = tip.offsetHeight || 36;
  let left = px - tw / 2, top = py - th - 10;
  if (left < 0) left = 4;
  if (left + tw > rect.width) left = rect.width - tw - 4;
  if (top < 0) top = py + 14;
  tip.style.left = left + 'px';
  tip.style.top  = top + 'px';
}

function renderSales(data) {
  const days = data.days;
  const isMonth = data.range === 'month';
  const maxVal = (Math.max(0, ...days.map(d => d.val ?? 0)) || 1) * 1.1;
  const xPos = i => days.length > 1 ? (i / (days.length - 1)) * LW : LW / 2;
  const yPos = v => L_PAD_T + L_CH - (v / maxVal) * L_CH;

  // Only plot days that have happened (val !== null).
  const plotted = days.map((d, i) => ({ d, i })).filter(p => p.d.val !== null);
  const pts = plotted.map(p => [xPos(p.i), yPos(p.d.val)]);
  const linePath = smoothPath(pts);
  document.getElementById('linePath').setAttribute('d', linePath);
  document.getElementById('areaPath').setAttribute('d',
    linePath ? `${linePath} L ${pts[pts.length - 1][0]} ${LH} L ${pts[0][0]} ${LH} Z` : '');

  dotGroup.innerHTML = '';
  tooltip.style.display = 'none';
  plotted.forEach(({ d, i }) => {
    const cx = xPos(i), cy = yPos(d.val);
    if (d.today) {
      dotGroup.appendChild(svgEl('circle', { cx, cy, r: 7, fill: 'none', stroke: '#e74c3c', 'stroke-width': 2, opacity: 0.3 }));
    }
    dotGroup.appendChild(svgEl('circle', {
      cx, cy, r: isMonth ? 3 : (d.today ? 5 : 4),
      fill: d.today ? '#e74c3c' : '#fff', stroke: '#e74c3c', 'stroke-width': 2.5,
    }));
    const hit = svgEl('circle', { cx, cy, r: isMonth ? 10 : 18, fill: 'transparent' });
    hit.style.cursor = 'pointer';
    hit.addEventListener('mouseenter', () => {
      const name = isMonth ? new Date(d.date + 'T00:00:00').toLocaleDateString(undefined, { month: 'short', day: 'numeric' }) : d.label;
      tooltip.style.display = 'block';
      tooltip.innerHTML = `<span style="color:var(--muted,#888);font-weight:500;">${esc(name)}</span>&nbsp; ${peso(d.val)}`;
      positionTooltip(tooltip, lineSvg, LW, LH, cx, cy);
    });
    hit.addEventListener('mouseleave', () => { tooltip.style.display = 'none'; });
    dotGroup.appendChild(hit);
  });

  // Day labels (month view: thin them out so they don't overlap)
  labelsEl.innerHTML = '';
  days.forEach((d, i) => {
    const span = document.createElement('span');
    const show = !isMonth || i === 0 || (i + 1) % 5 === 0 || d.today;
    span.textContent = show ? d.label : '';
    span.style.cssText = `font-size:11px;color:var(--subtle,#aaa);font-weight:${d.today ? '700' : '500'};${d.today ? 'color:#e74c3c;' : ''}`;
    labelsEl.appendChild(span);
  });

  document.getElementById('weeklyTotal').textContent = peso(data.total);
}

async function loadSales(range = 'week') {
  try { renderSales(await getJson(CHART_URLS.sales, { range })); }
  catch (e) { console.error(e); }
}
document.querySelectorAll('.range-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.range-btn').forEach(b => b.classList.toggle('active', b === btn));
    loadSales(btn.dataset.range);
  });
});

// ── Order Status (donut) ──────────────────────────────────────────────────────
function renderOrderStatus(data) {
  const circ = 2 * Math.PI * 64;
  const GAP_FRAC = 2.5 / 360;
  let offset = 0;
  [
    ['donutCompleted', 'completed'],
    ['donutPending',   'pending'],
    ['donutCancelled', 'cancelled'],
  ].forEach(([id, key]) => {
    const seg = data[key];
    const el = document.getElementById(id);
    const arc = seg.fraction > 0 ? Math.max(0, seg.fraction - GAP_FRAC) * circ : 0;
    el.style.strokeDasharray  = `${arc} ${circ - arc}`;
    el.style.strokeDashoffset = -(offset * circ);
    offset += seg.fraction;
    document.getElementById(key + 'Count').textContent = seg.count.toLocaleString();
    document.getElementById(key + 'Pct').textContent   = seg.percent + '%';
  });
  document.getElementById('donutTotal').textContent = data.total.toLocaleString();
}

// ── Revenue by Product (bar chart) ────────────────────────────────────────────
function renderRevenue(data) {
  const products = data.products;
  const SVG_W = 560, SVG_H = 200, PAD_L = 8, PAD_R = 8, PAD_T = 8, PAD_B = 4;
  const chartW = SVG_W - PAD_L - PAD_R, chartH = SVG_H - PAD_T - PAD_B;
  const group = document.getElementById('revBarGroup');
  const labelsDiv = document.getElementById('revBarLabels');
  const tip = document.getElementById('revBarTooltip');
  const svg = document.getElementById('revenueBarChart');

  group.innerHTML = ''; labelsDiv.innerHTML = ''; tip.style.display = 'none';
  document.getElementById('revTotal').textContent = peso(data.total_revenue);

  if (!products.length) {
    labelsDiv.innerHTML = '<span style="font-size:12px;color:var(--subtle);flex:1;text-align:center;">No completed sales this month yet.</span>';
    return;
  }

  const maxRev = Math.max(...products.map(p => p.revenue)) * 1.08;
  const colW = chartW / products.length, barW = colW * 0.48;

  products.forEach((p, i) => {
    const barH = (p.revenue / maxRev) * chartH;
    const x = PAD_L + i * colW + (colW - barW) / 2;
    const y = PAD_T + chartH - barH;

    const rect = svgEl('rect', { x, y, width: barW, height: barH, rx: 4, ry: 4, fill: '#CC1F1F', opacity: 0.75 });
    rect.style.cursor = 'pointer';
    rect.style.transition = 'opacity .15s';

    const val = svgEl('text', { x: x + barW / 2, y: y - 5, 'text-anchor': 'middle', 'font-size': 9, 'font-weight': 700, fill: 'currentColor', opacity: 0.5 });
    val.textContent = '₱' + (p.revenue >= 1000 ? (p.revenue / 1000).toFixed(1) + 'k' : p.revenue);

    rect.addEventListener('mouseenter', () => {
      rect.setAttribute('opacity', '1');
      tip.style.display = 'block';
      tip.innerHTML = `<span style="color:var(--muted);font-weight:500;">${esc(p.name)}</span>&nbsp; ${peso(p.revenue)}`;
      positionTooltip(tip, svg, SVG_W, SVG_H, x + barW / 2, y);
    });
    rect.addEventListener('mouseleave', () => { rect.setAttribute('opacity', '0.75'); tip.style.display = 'none'; });

    group.appendChild(rect);
    group.appendChild(val);

    const span = document.createElement('span');
    span.textContent = p.short;
    span.title = p.name;
    span.style.cssText = 'font-size:10px;color:var(--subtle);font-weight:500;text-align:center;flex:1;';
    labelsDiv.appendChild(span);
  });
}

// ── Recent Transactions ───────────────────────────────────────────────────────
const ICON_IN  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
const ICON_OUT = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>';

function renderTransactions(data) {
  const list = document.getElementById('txList');
  if (!data.transactions.length) {
    list.innerHTML = '<div class="tx-item"><div class="tx-info"><div class="tx-time">No transactions yet.</div></div></div>';
    return;
  }
  list.innerHTML = data.transactions.map(t => {
    const out = t.type === 'refund';
    return `<div class="tx-item">
      <div class="tx-icon${out ? ' out' : ''}">${out ? ICON_OUT : ICON_IN}</div>
      <div class="tx-info"><div class="tx-name">${esc(t.title)}</div><div class="tx-time">${esc(t.subtitle)}</div></div>
      <div class="tx-amount ${out ? 'minus' : 'plus'}">${esc(t.amount)}</div>
    </div>`;
  }).join('');
}

// ── Today's Sales (stat card) ─────────────────────────────────────────────────
// Reuses the same sales endpoint as the Weekly Sales chart (range=month), so
// the card can never disagree with the chart's definition of a "sale". The
// response's `days` array flags the current day with `today: true`; the day
// before it is used for the trend badge.
function renderTodaySales(data) {
  const days = data.days || [];
  const idx  = days.findIndex(d => d.today);
  const valueEl = document.getElementById('todaySalesValue');
  const trendEl = document.getElementById('todaySalesTrend');

  const todayVal = idx >= 0 ? Number(days[idx].val ?? 0) : 0;
  valueEl.textContent = peso(todayVal);

  // Yesterday may belong to the previous month (on the 1st) — no trend then.
  const prev = idx > 0 ? days[idx - 1] : null;
  if (!prev || prev.val === null || prev.val === undefined) {
    trendEl.style.display = 'none';
    return;
  }

  const yesterdayVal = Number(prev.val);
  let cls, text;
  if (yesterdayVal === 0) {
    if (todayVal === 0) { trendEl.style.display = 'none'; return; }
    cls = 'up'; text = 'New';                       // nothing to compare against
  } else {
    const pct = Math.round(((todayVal - yesterdayVal) / yesterdayVal) * 100);
    cls  = pct >= 0 ? 'up' : 'down';
    text = (pct >= 0 ? '↑ ' : '↓ ') + Math.abs(pct) + '%';
  }

  trendEl.className = 'stat-trend ' + cls;
  trendEl.textContent = text;
  trendEl.title = 'vs. yesterday (' + peso(yesterdayVal) + ')';
  trendEl.style.display = '';
}

async function loadTodaySales() {
  try { renderTodaySales(await getJson(CHART_URLS.sales, { range: 'month' })); }
  catch (e) { console.error(e); }
}

// ── Boot ──────────────────────────────────────────────────────────────────────
loadSales('week');
loadTodaySales();
setInterval(loadTodaySales, 60000);   // keep the card fresh as orders complete
getJson(CHART_URLS.orderStatus).then(renderOrderStatus).catch(console.error);
getJson(CHART_URLS.revenue).then(renderRevenue).catch(console.error);
getJson(CHART_URLS.transactions).then(renderTransactions).catch(console.error);
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