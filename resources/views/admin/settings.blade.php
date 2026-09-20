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
  .lang-option {
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    color: var(--text);
    transition: background 0.15s;
  }
  .lang-option:hover {
    background: rgba(128,128,128,0.05);
  }
  .lang-option.selected {
    font-weight: 600;
    color: #cd0000;
  }
  .check-icon {
    display: none;
    color: #cd0000;
  }
  .lang-option.selected .check-icon {
    display: block;
  }

  /* Activity Log Specific Styles */
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
    background-color: #cd0000;
  }
  .toggle-switch input:focus + .slider {
    box-shadow: 0 0 1px #cd0000;
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
  <a class="nav-item active" href="{{ route('admin.settings') }}">
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
  <div class="header-title">Settings</div>
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
  <div style="font-size:20px;font-weight:800;color:var(--text);">Settings</div>
  <div style="font-size:13px;color:var(--muted);margin-top:3px;">Configure system preferences.</div>
</div>
<div class="bottom-grid">
  
  <div class="card" style="grid-column: 1 / -1;">
    <div class="card-head"><h3>Account & Preferences</h3></div>
    <div style="display:flex;flex-direction:column;">
      
      <div id="languageTrigger" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:var(--text);">Language</span>
        <span style="color:var(--muted);font-size:14px;"><span id="currentLanguageLabel">English</span> ›</span>
      </div>
      
      <div id="activityLogTrigger" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:var(--text);">Activity Log</span>
        <span style="color:var(--muted);font-size:14px;">›</span>
      </div>
      
      <div id="notificationsTrigger" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:var(--text);">Notifications</span>
        <span style="color:var(--muted);font-size:14px;">›</span>
      </div>
      
      <div id="securityTrigger" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:var(--text);">Security</span>
        <span style="color:var(--muted);font-size:14px;">›</span>
      </div>

      <div id="helpTrigger" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:var(--text);">Help</span>
        <span style="color:var(--muted);font-size:14px;">›</span>
      </div>
      
      <div id="feedbackTrigger" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:var(--text);">Send Feedback</span>
        <span style="color:var(--muted);font-size:14px;">›</span>
      </div>

      <div id="tosTrigger" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:var(--text);">Terms of Service</span>
        <span style="color:var(--muted);font-size:14px;">›</span>
      </div>

      <div id="logoutTrigger" style="padding:16px 20px;display:flex;justify-content:space-between;align-items:center;cursor:pointer;">
        <span style="font-weight:500;color:#cd0000;">Logout</span>
        <span style="color:var(--muted);font-size:14px;">›</span>
      </div>
    </div>
  </div>

</div>

</main>

<div class="modal-backdrop" id="languageModal">
  <div class="modal-window">
    <div class="modal-header">
      <h3 class="modal-title">Select Language</h3>
      <button class="modal-close" id="closeLanguageModal">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="lang-option selected" data-value="English">
        <span>English</span>
        <svg class="check-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div class="lang-option" data-value="Spanish">
        <span>Español</span>
        <svg class="check-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div class="lang-option" data-value="French">
        <span>Français</span>
        <svg class="check-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div class="lang-option" data-value="Tagalog">
        <span>Tagalog</span>
        <svg class="check-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
    </div>
  </div>
</div>

<div class="modal-backdrop" id="activityLogModal">
  <div class="modal-window" style="max-width: 500px;">
    <div class="modal-header">
      <h3 class="modal-title">Activity Log</h3>
      <button class="modal-close" id="closeActivityLogModal">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body log-list" style="padding: 0;">
      <div class="log-entry">
        <div class="log-time">Today, 10:42 AM</div>
        <div class="log-action"><strong>Admin User</strong> updated stock for Paracetamol 500mg (+50 units).</div>
      </div>
      <div class="log-entry">
        <div class="log-time">Today, 09:15 AM</div>
        <div class="log-action"><strong>Admin User</strong> generated Monthly Sales Report.</div>
      </div>
      <div class="log-entry">
        <div class="log-time">Yesterday, 04:30 PM</div>
        <div class="log-action"><strong>John Doe</strong> processed Prescription #10294.</div>
      </div>
      <div class="log-entry">
        <div class="log-time">Yesterday, 02:10 PM</div>
        <div class="log-action">System flagged Amoxicillin 250mg for approaching expiry.</div>
      </div>
      <div class="log-entry">
        <div class="log-time">Apr 21, 11:05 AM</div>
        <div class="log-action"><strong>Admin User</strong> changed system language to English.</div>
      </div>
    </div>
  </div>
</div>

<div class="modal-backdrop" id="notificationsModal">
  <div class="modal-window">
    <div class="modal-header">
      <h3 class="modal-title">Notifications</h3>
      <button class="modal-close" id="closeNotificationsModal">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body" style="padding: 0;">
      <div class="list-option">
        <div class="list-text">
          <strong>Low Stock Alerts</strong>
          <span>Receive alerts when item quantities drop below threshold</span>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" checked>
          <span class="slider"></span>
        </label>
      </div>
      <div class="list-option">
        <div class="list-text">
          <strong>Expiry Warnings</strong>
          <span>Weekly digest for batches expiring within 30 days</span>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" checked>
          <span class="slider"></span>
        </label>
      </div>
      <div class="list-option">
        <div class="list-text">
          <strong>Daily Sales Summary</strong>
          <span>Receive an end-of-day email reporting total transactions</span>
        </div>
        <label class="toggle-switch">
          <input type="checkbox">
          <span class="slider"></span>
        </label>
      </div>
      <div class="list-option">
        <div class="list-text">
          <strong>System Updates</strong>
          <span>Notices regarding scheduled maintenance or feature rollouts</span>
        </div>
        <label class="toggle-switch">
          <input type="checkbox" checked>
          <span class="slider"></span>
        </label>
      </div>
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
          <strong style="color: #cd0000;">Log out of all devices</strong>
          <span>End all active sessions immediately</span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal-backdrop" id="helpModal">
  <div class="modal-window">
    <div class="modal-header">
      <h3 class="modal-title">Help & Support</h3>
      <button class="modal-close" id="closeHelpModal">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body" style="padding: 0;">
      <div class="list-option clickable" style="cursor: pointer;">
        <div class="list-text">
          <strong>Documentation & Tutorials</strong>
          <span>Read guides on how to use {{ $siteName }}</span>
        </div>
        <span style="color:var(--muted);font-size:16px;">›</span>
      </div>
      <div class="list-option clickable" style="cursor: pointer;">
        <div class="list-text">
          <strong>System Status</strong>
          <span>Check if all servers are operational</span>
        </div>
        <span style="color:var(--muted);font-size:16px;">›</span>
      </div>
      <div class="list-option clickable" style="cursor: pointer;">
        <div class="list-text">
          <strong>Contact Support</strong>
          <span>Reach out to technical helpdesk</span>
        </div>
        <span style="color:var(--muted);font-size:16px;">›</span>
      </div>
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
      <button id="submitFeedbackBtn" style="width: 100%; padding: 12px; background: #cd0000; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Submit Feedback</button>
    </div>
  </div>
</div>

<div class="modal-backdrop" id="tosModal">
  <div class="modal-window" style="max-width: 500px;">
    <div class="modal-header">
      <h3 class="modal-title">Terms of Service</h3>
      <button class="modal-close" id="closeTosModal">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body" style="padding: 20px; max-height: 350px; overflow-y: auto; font-size: 14px; color: var(--text); line-height: 1.6;">
      <h4 style="margin-top: 0; margin-bottom: 8px;">1. Acceptance of Terms</h4>
      <p style="margin-top: 0; margin-bottom: 16px; color: var(--muted);">By accessing and using the {{ $siteName }} Admin Portal, you accept and agree to be bound by the terms and provision of this agreement.</p>
      
      <h4 style="margin-top: 0; margin-bottom: 8px;">2. Use License</h4>
      <p style="margin-top: 0; margin-bottom: 16px; color: var(--muted);">Permission is granted to temporarily use this software for administrative and management purposes only. This is the grant of a license, not a transfer of title.</p>
      
      <h4 style="margin-top: 0; margin-bottom: 8px;">3. Data Privacy</h4>
      <p style="margin-top: 0; margin-bottom: 16px; color: var(--muted);">User data, patient information, and prescription records must be handled in compliance with applicable medical and data protection regulations.</p>

      <h4 style="margin-top: 0; margin-bottom: 8px;">4. Disclaimer</h4>
      <p style="margin-top: 0; margin-bottom: 0; color: var(--muted);">The materials on this portal are provided on an 'as is' basis. We make no warranties, expressed or implied, and hereby disclaim and negate all other warranties including, without limitation, implied warranties or conditions of merchantability.</p>
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
  // Modal interaction logic
  document.addEventListener('DOMContentLoaded', () => {
    // --- Elements ---
    const langTrigger = document.getElementById('languageTrigger');
    const langModal = document.getElementById('languageModal');
    const closeLangBtn = document.getElementById('closeLanguageModal');
    const langOptions = document.querySelectorAll('.lang-option');
    const currentLangLabel = document.getElementById('currentLanguageLabel');
    
    const activityTrigger = document.getElementById('activityLogTrigger');
    const activityModal = document.getElementById('activityLogModal');
    const closeActivityBtn = document.getElementById('closeActivityLogModal');

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

    // --- Language Modal Logic ---
    langTrigger.addEventListener('click', () => langModal.classList.add('active'));
    closeLangBtn.addEventListener('click', () => langModal.classList.remove('active'));
    langModal.addEventListener('click', (e) => {
      if (e.target === langModal) langModal.classList.remove('active');
    });

    langOptions.forEach(option => {
      option.addEventListener('click', function() {
        langOptions.forEach(opt => opt.classList.remove('selected'));
        this.classList.add('selected');
        
        const newLang = this.getAttribute('data-value');
        currentLangLabel.textContent = newLang;
        langModal.classList.remove('active');

        if (toast && toastMsg) {
          toastMsg.textContent = `Language changed to ${newLang}`;
          toast.classList.add('show');
          setTimeout(() => toast.classList.remove('show'), 3000); 
        }
      });
    });

    // --- Activity Log Modal Logic ---
    activityTrigger.addEventListener('click', () => activityModal.classList.add('active'));
    closeActivityBtn.addEventListener('click', () => activityModal.classList.remove('active'));
    activityModal.addEventListener('click', (e) => {
      if (e.target === activityModal) activityModal.classList.remove('active');
    });

    // --- Notifications Modal Logic ---
    notifTrigger.addEventListener('click', () => notifModal.classList.add('active'));
    closeNotifBtn.addEventListener('click', () => notifModal.classList.remove('active'));
    notifModal.addEventListener('click', (e) => {
      if (e.target === notifModal) notifModal.classList.remove('active');
    });

    const toggles = document.querySelectorAll('#notificationsModal input[type="checkbox"]');
    toggles.forEach(toggle => {
      toggle.addEventListener('change', (e) => {
        const title = e.target.closest('.list-option').querySelector('strong').textContent;
        const status = e.target.checked ? 'enabled' : 'disabled';
        if (toast && toastMsg) {
          toastMsg.textContent = `${title} ${status}.`;
          toast.classList.add('show');
          setTimeout(() => toast.classList.remove('show'), 3000); 
        }
      });
    });

    // --- Security Modal Logic ---
    securityTrigger.addEventListener('click', () => securityModal.classList.add('active'));
    closeSecurityBtn.addEventListener('click', () => securityModal.classList.remove('active'));
    securityModal.addEventListener('click', (e) => {
      if (e.target === securityModal) securityModal.classList.remove('active');
    });

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
    if (logoutTrigger) {
      logoutTrigger.addEventListener('click', () => {
        window.location.href = 'admin_login.html';
      });
    }
  });
</script>
</body>
</html>