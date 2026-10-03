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
    background: var(--primary-soft, #ffeef2);
    color: var(--primary, #e54646);
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

  /* Stock number style in table */
  .stock-num {
    font-family: 'Courier New', monospace;
    font-size: 11px;
    color: var(--primary, #e54646);
    background: var(--primary-soft, #ffeeee);
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
    background: rgba(241, 99, 127, 0.15);
    color: #fca5a5;
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
        <option value="1">Active</option>
        <option value="0">Inactive</option>
      </select>
    </div>
    <div class="modal-sub" id="modalStatusHint" style="display:none;color:#e07b00;margin:-6px 0 0;"></div>

    <div class="modal-actions">
      <button class="btn-sm" id="cancelModal">Cancel</button>
      <button class="btn-sm primary" id="confirmAddStock">Add Stock</button>
    </div>
  </div>
</div>

<!-- Products Modal (status overview) -->
<div class="modal-backdrop" id="productsModal">
  <div class="modal modal-lg">
    <button class="modal-close" id="closeProductsModal" title="Close">&times;</button>
    <h3>Products</h3>
    <div class="modal-sub">Green means a product has an ongoing active batch. Tap an Out of Stock product to activate one of its available batches.</div>
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
  <a class="nav-item active" href="{{ route('admin.stocks') }}">
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
  <div class="header-title">Stocks</div>
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
      <div class="sc-sub">Within 90 days</div>
    </div>
  </div>

  <!-- Batch Table -->
  <div class="card">
    <div class="card-head">
      <h3>Batch Inventory</h3>
      <div class="filter-row">
        <button class="btn-sm" id="openProductsModal" type="button">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;margin-right:4px;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>Products
        </button>
        <input type="text" id="searchBatch" placeholder="Search medicine or stock no…">
        <select id="filterStatus">
          <option value="Active" selected>Active</option>
          <option value="Inactive">Inactive</option>
          <option value="Out of Stock">Out of Stock</option>
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
  store: "{{ route('admin.stocks.store') }}",
  update: id => `{{ url('/admin/stocks') }}/${id}`,
  destroy: id => `{{ url('/admin/stocks') }}/${id}`,
  activate: id => `{{ url('/admin/stocks') }}/${id}/activate`,
  products: "{{ route('admin.stocks.products') }}",
  availableStocks: productId => `{{ url('/admin/stocks/products') }}/${productId}/available`,
};

// Seeded from the server. Every batch already carries a single computed
// `status`: Active, Inactive, Out of Stock, or Expired.
let batches = @json($stocks);

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

  const rows = batches.filter(b => {
    const matchS = b.medicine.toLowerCase().includes(search) || b.stock_no.toLowerCase().includes(search);
    const matchF = !filter || b.status === filter;
    return matchS && matchF;
  });

  const tbody = document.getElementById("batchTableBody");
  const empty = document.getElementById("emptyState");

  if (!rows.length) {
    tbody.innerHTML = "";
    empty.style.display = "block";
  } else {
    empty.style.display = "none";
    tbody.innerHTML = rows.map(b => {
      const qc = b.quantity <= 50 ? "qty-badge qty-low" : "qty-badge qty-ok";
      return `<tr>
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
            <button class="row-action-btn edit" title="Edit" onclick="openEditStock(${b.id})">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <button class="row-action-btn" title="Delete" onclick="deleteStock(${b.id}, '${b.stock_no}')">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            </button>
          </div>
        </td>
      </tr>`;
    }).join("");
  }

  // Summary
  const totalUnits = batches.reduce((s, b) => s + b.quantity, 0);
  const uniqueMeds = new Set(batches.map(b => b.medicine)).size;
  const expiring = batches.filter(b => {
    if (!b.expiry_date) return false;
    const d = (new Date(b.expiry_date) - new Date()) / 86400000;
    return d >= 0 && d < 90;
  }).length;
  document.getElementById("sumBatches").textContent = batches.length;
  document.getElementById("sumUnits").textContent = totalUnits.toLocaleString();
  document.getElementById("sumMeds").textContent = uniqueMeds;
  document.getElementById("sumExpiring").textContent = expiring;
}

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

// ── Search / Filter ────────────────────────────────────────────────────────
document.getElementById("searchBatch").addEventListener("input", renderTable);
document.getElementById("filterStatus").addEventListener("change", renderTable);

// ── Products modal (status overview) ────────────────────────────────────────
function showProductsModal() {
  document.getElementById("productsModal").classList.add("open");
  loadProducts();
}
function hideProductsModal() {
  document.getElementById("productsModal").classList.remove("open");
}
function loadProducts() {
  const list = document.getElementById("productsList");
  list.innerHTML = `<div class="pick-empty">Loading products…</div>`;
  apiFetch(ROUTES.products, { method: "GET" })
    .then(data => renderProductsList(data.products))
    .catch(err => { list.innerHTML = `<div class="pick-empty">${err.message}</div>`; });
}
function renderProductsList(products) {
  const list = document.getElementById("productsList");
  if (!products.length) {
    list.innerHTML = `<div class="pick-empty">No products yet.</div>`;
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
renderTable();
</script>
</body>
</html>