<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $siteName }} — Sales Records</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --red:        #2D7A2D;
  --red-h:      #256625;
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
.table-wrap { overflow-x: hidden; }
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
  /* ── Modal Backdrop ── */
  .modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .modal-backdrop.open { display: flex; }
  /* Prescription gallery/zoom open on top of the details or receipt modal */
  #prescriptionModal { z-index: 1100; }
  #prescriptionZoomModal { z-index: 1200; }

  /* ── Modal Box ── */
  .modal {
    background: var(--card, #fff);
    border-radius: 16px;
    width: 100%;
    max-width: 480px;
    max-height: 90vh;
    box-shadow: 0 24px 60px rgba(0,0,0,0.18);
    overflow: hidden;
    animation: modalIn .22s ease;
    display: flex;
    flex-direction: column;
  }
  @keyframes modalIn {
    from { opacity:0; transform: translateY(18px) scale(.97); }
    to   { opacity:1; transform: none; }
  }

  .modal-header {
    padding: 20px 24px 16px;
    border-bottom: 1px solid var(--border, #e8eaf0);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
  }
  .modal-header h3 { font-size: 16px; font-weight: 700; margin: 0; color: var(--text); }
  .modal-close {
    background: none; border: none; cursor: pointer;
    color: var(--muted); padding: 4px; line-height: 1;
    border-radius: 6px; transition: background .15s;
  }
  .modal-close:hover { background: var(--surface, #f4f5f8); }

  .modal-body { padding: 20px 24px; overflow-y: auto; flex: 1 1 auto; }

  /* ── Receipt inside modal ── */
  .receipt-header {
    text-align: center;
    padding-bottom: 16px;
    border-bottom: 1px dashed var(--border, #e8eaf0);
    margin-bottom: 16px;
  }
  .receipt-header .rx-logo {
    font-size: 18px; font-weight: 800; color: var(--text);
  }
  .receipt-header .rx-sub {
    font-size: 11px; color: var(--muted); margin-top: 2px;
  }

  .receipt-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px 12px;
    font-size: 12px;
    margin-bottom: 16px;
  }
  .receipt-meta .rm-label { color: var(--muted); }
  .receipt-meta .rm-value { color: var(--text); font-weight: 600; text-align: right; }

  .receipt-items {
    width: 100%;
    font-size: 12px;
    border-collapse: collapse;
    margin-bottom: 12px;
  }
  .receipt-items thead th {
    font-size: 10px; text-transform: uppercase;
    letter-spacing: .05em; color: var(--muted);
    padding: 4px 0; border-bottom: 1px solid var(--border, #e8eaf0);
    text-align: left;
  }
  .receipt-items thead th:last-child { text-align: right; }
  .receipt-items tbody td {
    padding: 7px 0;
    border-bottom: 1px solid var(--border, #e8eaf0);
    color: var(--text);
  }
  .receipt-items tbody td:last-child { text-align: right; font-weight: 600; }
  .receipt-items tfoot td {
    padding: 8px 0 2px;
    font-size: 13px; font-weight: 700; color: var(--text);
  }
  .receipt-items tfoot td:last-child { text-align: right; }

  /* ── Payment waiting banner ── */
  .payment-input-group {
    margin-top: 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .payment-input-group label {
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .05em;
  }
  .payment-input-group input {
    width: 100%;
    border: 1.5px solid var(--border, #e8eaf0);
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 14px;
    background: var(--card, #fff);
    color: var(--text);
  }
  .payment-input-group input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, .15);
  }

  .receipt-summary {
    margin-top: 14px;
    border-top: 1px dashed var(--border, #e8eaf0);
    padding-top: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .receipt-summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    color: var(--muted);
  }
  .receipt-summary-row.total {
    font-size: 15px;
    font-weight: 800;
    color: var(--text);
    margin-top: 4px;
  }
  .receipt-status {
    margin-top: 12px;
    padding: 10px 12px;
    border-radius: 10px;
    background: #fef2f2;
    color: #991b1b;
    font-size: 13px;
    font-weight: 600;
  }

  .modal-footer {
    padding: 14px 24px 20px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    border-top: 1px solid var(--border, #e8eaf0);
    flex-shrink: 0;
  }

  /* ── Confirm modal (approve step) ── */
  .confirm-icon {
    width: 52px; height: 52px;
    border-radius: 50%;
    background: #fef2f2;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
  }
  .confirm-icon svg { color: #ef4444; }
  .confirm-text { text-align: center; }
  .confirm-text h4 { font-size: 15px; font-weight: 700; color: var(--text); margin: 0 0 6px; }
  .confirm-text p  { font-size: 13px; color: var(--muted); margin: 0; line-height: 1.5; }

  /* ── Order row status badges ── */
  .pill.warn { background: #fffbeb; color: #92400e; }
  .pill.paid { background: #fef2f2; color: #b91c1c; }

  /* ── Approve / View / Detail Buttons ── */
  .btn-approve {
    font-size: 12px; font-weight: 600;
    background: #ef4444; color: #fff;
    border: none; border-radius: 7px;
    padding: 5px 13px; cursor: pointer;
    transition: background .15s, transform .1s;
  }
  .btn-approve:hover { background: #dc2626; }
  .btn-approve:active { transform: scale(.96); }
  .btn-approve:disabled, .btn-sm:disabled {
    opacity: .45; cursor: not-allowed; pointer-events: auto;
  }
  .btn-approve:disabled:hover { background: #ef4444; }
  .btn-approve:disabled:active { transform: none; }

  .btn-view {
    font-size: 12px; font-weight: 600;
    background: var(--surface, #f4f5f8); color: var(--text);
    border: 1.5px solid var(--border, #e8eaf0); border-radius: 7px;
    padding: 5px 13px; cursor: pointer;
    transition: background .15s;
  }
  .btn-view:hover { background: var(--border, #e8eaf0); }

  /* ── Detail button ── */
  .btn-detail {
    font-size: 12px; font-weight: 600;
    background: #effcf0; color: #16a34a;
    border: 1.5px solid #bbf7d0; border-radius: 7px;
    padding: 5px 13px; cursor: pointer;
    transition: background .15s, border-color .15s;
  }
  .btn-detail:hover { background: #dcfce7; border-color: #86efac; }

  /* ── Refund button ── */
  .btn-refund {
    font-size: 12px; font-weight: 600;
    background: #f97316; color: #fff;
    border: none; border-radius: 7px;
    padding: 5px 13px; cursor: pointer;
    transition: background .15s, transform .1s;
  }
  .btn-refund:hover { background: #ea6a0c; }
  .btn-refund:active { transform: scale(.96); }

  /* ── Action cell ── */
  .action-cell { display: flex; gap: 6px; align-items: center; flex-wrap: nowrap; white-space: nowrap; }
  .action-cell > * { flex-shrink: 0; white-space: nowrap; }

  /* ── Detail modal ── */
  .detail-modal { max-width: 520px; }

  .detail-order-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 8px;
  }
  .detail-order-id {
    font-size: 18px; font-weight: 800; color: var(--text);
  }
  .detail-order-meta {
    font-size: 12px; color: var(--muted); margin-top: 3px;
  }

  /* ── Cart items list ── */
  .cart-item-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; }
  .cart-item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--surface, #f4f5f8);
    border-radius: 10px;
    padding: 12px 14px;
    transition: background .15s;
  }
  .cart-item:hover { background: var(--border, #e8eaf0); }
  .cart-item-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    background: #dbeafe;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 16px;
  }
  .cart-item-info { flex: 1; min-width: 0; }
  .cart-item-name {
    font-size: 13px; font-weight: 700; color: var(--text);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .cart-item-qty {
    font-size: 11px; color: var(--muted); margin-top: 2px;
  }
  .cart-item-price {
    font-size: 13px; font-weight: 700; color: var(--text);
    text-align: right;
    flex-shrink: 0;
  }
  .cart-item-unit-price {
    font-size: 10px; color: var(--muted); text-align: right; margin-top: 2px;
  }

  /* ── Detail total summary ── */
  .detail-summary {
    border-top: 1.5px dashed var(--border, #e8eaf0);
    padding-top: 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .detail-summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    color: var(--muted);
  }
  .detail-summary-row.total {
    font-size: 15px;
    font-weight: 800;
    color: var(--text);
    margin-top: 4px;
  }

  /* ── Proof of payment (detail modal) ── */
  .detail-proof {
    margin-top: 16px;
  }
  .detail-proof-label {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .06em; color: var(--muted); margin-bottom: 8px;
  }
  .detail-proof-img-wrap {
    width: 100%;
    max-width: 220px;
    margin: 0 auto;
    border: 1.5px solid var(--border, #e8eaf0);
    border-radius: 12px;
    overflow: hidden;
    background: var(--surface, #f4f5f8);
  }
  .detail-proof-img-wrap img {
    display: block;
    width: 100%;
    height: auto;
    aspect-ratio: 9 / 16;
    object-fit: cover;
  }
  .detail-proof-note {
    text-align: center;
    font-size: 11px;
    color: var(--muted);
    margin-top: 6px;
  }

  /* ── Prescription requirement (detail modal) ── */
  .rx-view-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--blue-light);
    color: var(--blue);
    border: 1.5px solid var(--blue);
    border-radius: 8px;
    padding: 9px 14px;
    font-size: 12.5px;
    font-weight: 700;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    transition: background .15s, color .15s;
  }
  .rx-view-btn:hover { background: var(--blue); color: #fff; }
  .rx-view-btn svg { flex-shrink: 0; }

  .prescription-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 12px;
  }
  .prescription-card {
    border: 1.5px solid var(--border, #e8eaf0);
    border-radius: 10px;
    overflow: hidden;
    background: var(--surface, #f4f5f8);
    cursor: pointer;
    transition: border-color .15s, transform .1s;
  }
  .prescription-card:hover { border-color: var(--blue); transform: translateY(-2px); }
  .prescription-card img {
    display: block;
    width: 100%;
    height: 130px;
    object-fit: cover;
    background: var(--surface2, #eef0f3);
  }
  .prescription-card-label {
    padding: 7px 9px;
    font-size: 11px;
    font-weight: 700;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* ── Detail customer chip ── */
  .detail-customer-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--surface, #f4f5f8);
    border-radius: 30px;
    padding: 6px 12px 6px 6px;
  }
  .detail-customer-avatar {
    width: 26px; height: 26px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
  }
  .detail-customer-name {
    font-size: 12px; font-weight: 700; color: var(--text);
  }


  /* ── Orders table: fixed layout, long text is cut with "…" (no scrolling) ── */
  #ordersTable { table-layout: fixed; width: 100%; }
  #ordersTable thead th,
  #ordersTable tbody td {
    padding-left: 8px; padding-right: 8px;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
  }
  #ordersTable thead th:first-child, #ordersTable tbody td:first-child { padding-left: 14px; }
  #ordersTable .c-id      { width: 13%; }
  #ordersTable .c-items   { width: 7%; }
  #ordersTable .c-total   { width: 9%; }
  #ordersTable .c-date    { width: 10%; }
  #ordersTable .c-pay     { width: 7%; }
  #ordersTable .c-status  { width: 11%; }
  #ordersTable .c-action  { width: 22%; }
  /* Customer / Due Date / Cancelation Reason share whatever space is left */
  #ordersTable tbody td.col-action { overflow: visible; }
  #ordersTable .btn-approve, #ordersTable .btn-view,
  #ordersTable .btn-detail,  #ordersTable .btn-refund { padding: 5px 10px; white-space: nowrap; }

  /* Very small screens: buttons can't sit side by side in a squeezed table,
     so keep a minimum width there (scrolls sideways on phones only). */
  @media (max-width: 900px) {
    .table-wrap { overflow-x: auto; }
    #ordersTable { min-width: 860px; }
  }

  /* dark-mode patches */
  [data-theme="dark"] .confirm-icon { background: #450a0a; }
  [data-theme="dark"] .modal { background: var(--card, #1e2130); }
  [data-theme="dark"] .receipt-header .rx-logo,
  [data-theme="dark"] .confirm-text h4 { color: #f1f5f9; }
  [data-theme="dark"] .btn-detail { background: #14532d; border-color: #22c55e; }
  [data-theme="dark"] .btn-detail:hover { background: #16a34a; }
  [data-theme="dark"] .cart-item { background: rgba(255,255,255,.05); }
  [data-theme="dark"] .cart-item:hover { background: rgba(255,255,255,.09); }
  [data-theme="dark"] .cart-item-icon { background: #1e3a5f; }
  [data-theme="dark"] .detail-customer-chip { background: rgba(255,255,255,.07); }
  [data-theme="dark"] .payment-input-group input { background: rgba(255,255,255,.05); color: #f1f5f9; }
  [data-theme="dark"] .receipt-status { background: #450a0a; color: #fee2e2; }
</style>
</head>
<body>

<!-- ═══════════════ TOAST ═══════════════ -->
<div class="toast" id="toast">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="toastMsg">Done.</span>
</div>

<div class="overlay" id="overlay"></div>

<!-- ═══════════════ SIDEBAR ═══════════════ -->
<aside class="sidebar" id="sidebar">
  @php $staff = $staff ?? auth('staff')->user(); @endphp
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
  <a class="nav-item active" href="{{ route('staff.orders') }}">
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

  <a href = "#" style = "text-decoration: none;">
  <div class="sidebar-bottom">
    <form method="POST" action="{{ route('staff.logout') }}" style="width:100%;">
      @csrf
      <button type="submit" class="user-chip" style="width:100%;border:none;cursor:pointer;font:inherit;text-align:left;background:transparent;">
        <div class="user-avatar">
          @if($staff->profile_picture)
            <img src="{{ asset($staff->profile_picture) }}" alt="{{ $staff->first_name ?? 'Staff' }} {{ $staff->last_name ?? 'User' }}" />
          @else
            {{ strtoupper(substr($staff->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($staff->last_name ?? 'T', 0, 1)) }}
          @endif
        </div>
        <div class="user-info">
          <div class="user-name">{{ $staff->first_name ?? 'Staff' }} {{ $staff->last_name ?? 'User' }}</div>
          <div class="user-role">Pharmacy Staff &middot; Logout</div>
        </div>
      </button>
    </form>
  </div>
</a>

</aside>

<!-- ═══════════════ HEADER ═══════════════ -->
<header class="header">
  <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="header-title">Sales Records</div>
  <div class="header-search">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" placeholder="Search orders, patients…" id="searchInput">
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

<!-- ═══════════════ MAIN ═══════════════ -->
<main class="main">

  <div style="margin-bottom:20px;">
    <div style="font-size:20px;font-weight:800;color:var(--text);">Sales Records</div>
    <div style="font-size:13px;color:var(--muted);margin-top:3px;">Review and approve incoming customer orders.</div>
  </div>

  <!-- Stats -->
  <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px;">
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon" style="background:#fff7ed;color:#f97316;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      </div></div>
      <div class="stat-value" id="pendingCount">5</div>
      <div class="stat-label">Pending Approval</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon blue">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><polyline points="20 6 9 17 4 12"/></svg>
      </div></div>
      <div class="stat-value" id="approvedCount">0</div>
      <div class="stat-label">Completed</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon green">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div></div>
      <div class="stat-value" id="awaitingPayCount">0</div>
      <div class="stat-label">Awaiting Payment</div>
    </div>
  </div>

  <!-- Orders Table -->
  <div class="card">
    <div class="card-head">
      <h3>Incoming Orders</h3>
      <div style="display:flex;gap:8px;align-items:center;">
        <span style="font-size:12px;color:var(--muted);" id="tabPending" class="tab-link active-tab" onclick="filterTable('pending')" style="cursor:pointer;">Pending</span>
        <span style="font-size:12px;color:var(--muted);cursor:pointer;" id="tabConfirmed" class="tab-link" onclick="filterTable('confirmed')">Confirmed</span>
        <span style="font-size:12px;color:var(--muted);cursor:pointer;" id="tabApproved" class="tab-link" onclick="filterTable('approved')">For Pick Up</span>
        <span style="font-size:12px;color:var(--muted);cursor:pointer;" id="tabCompleted" class="tab-link" onclick="filterTable('completed')">Completed</span>
        <span style="font-size:12px;color:var(--muted);cursor:pointer;" id="tabCancelled" class="tab-link" onclick="filterTable('cancelled')">Cancelled</span>
      </div>
    </div>
    <style>
      .tab-link { cursor:pointer; padding:4px 10px; border-radius:6px; font-weight:600; transition:background .15s,color .15s; }
      .tab-link:hover { background:var(--surface,#f4f5f8); color:var(--text); }
      .tab-link.active-tab { background:var(--primary,#2D7A2D); color:#fff !important; }
    </style>
    <div class="table-wrap">
      <table id="ordersTable">
        <thead>
          <tr>
            <th class="c-id">#</th>
            <th class="c-customer">Customer</th>
            <th class="c-items">Items</th>
            <th class="c-total">Total</th>
            <th class="c-date">Date</th>
            <th id="dueDateHeader" style="display:none;">Due Date</th>
            <th id="cancelReasonHeader" style="display:none;">Cancelation Reason</th>
            <th class="c-pay">Payment</th>
            <th class="c-status">Status</th>
            <th class="c-action">Action</th>
          </tr>
        </thead>
        <tbody id="ordersBody">
          <!-- injected by JS -->
        </tbody>
      </table>
    </div>
  </div>

</main>

<!-- ═══════════════ ORDER DETAIL MODAL ═══════════════ -->
<div class="modal-backdrop" id="detailModal">
  <div class="modal detail-modal">
    <div class="modal-header">
      <h3>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;vertical-align:-2px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        Order Details
      </h3>
      <button class="modal-close" onclick="closeModal('detailModal')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body" style="max-height:70vh;overflow-y:auto;">

      <!-- Order top info -->
      <div class="detail-order-info">
        <div>
          <div class="detail-order-id" id="dOrderId">—</div>
          <div class="detail-order-meta" id="dOrderMeta">—</div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
          <span id="dStatusBadge" class="pill warn">Pending</span>
          <span id="dPaymentBadge" class="pill ok">Cash</span>
        </div>
      </div>

      <!-- Customer chip -->
      <div style="margin-bottom:18px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:6px;">Customer</div>
        <div class="detail-customer-chip">
          <div class="detail-customer-avatar" id="dCustomerInitials">—</div>
          <div class="detail-customer-name" id="dCustomerName">—</div>
        </div>
      </div>

      <!-- Items in cart -->
      <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:8px;">
        Items in Order (<span id="dItemCount">0</span>)
      </div>
      <div class="cart-item-list" id="dCartItems">
        <!-- injected -->
      </div>

      <!-- Prescriptions (only shown when the order contains prescription-required items) -->
      <div class="detail-proof" id="dPrescriptionSection" style="display:none;margin-bottom:18px;">
        <div class="detail-proof-label">Prescription Requirement</div>
        <button class="rx-view-btn" onclick="openPrescriptions()">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/><line x1="9" y1="11" x2="12" y2="11"/></svg>
          View Prescriptions (<span id="dPrescriptionCount">0</span>)
        </button>
      </div>

      <!-- Summary -->
      <div class="detail-summary">
        <div class="detail-summary-row">
          <span>Subtotal</span>
          <span id="dSubtotal">—</span>
        </div>
        <div class="detail-summary-row">
          <span>VAT (12%)</span>
          <span id="dVat">—</span>
        </div>
        <div class="detail-summary-row total">
          <span>Total Due</span>
          <span id="dTotal">—</span>
        </div>
      </div>

      <!-- Proof of payment (shown for online app orders — image if uploaded, notice if not) -->
      <div class="detail-proof" id="dProofSection" style="display:none;">
        <div class="detail-proof-label">Proof of Payment</div>
        <div class="detail-proof-img-wrap" id="dProofImgWrap">
          <img id="dProofImage" src="" alt="Proof of payment">
        </div>
        <div class="detail-proof-note" id="dProofNote">Customer already paid via online app — awaiting approval.</div>
        <!-- Stand-in for the customer-side upload (demo) -->
        <div id="dProofUploadWrap" style="display:none;text-align:center;margin-top:10px;">
          <input type="file" id="dProofUploadInput" accept="image/*" onchange="handleProofUpload(event)" style="font-size:12px;max-width:100%;">
        </div>
      </div>

      <!-- Proof of refund (only shown once a cancelled online-paid order has been refunded) -->
      <div class="detail-proof" id="dRefundProofSection" style="display:none;">
        <div class="detail-proof-label">Proof of Refund</div>
        <div class="detail-proof-img-wrap">
          <img id="dRefundProofImage" src="" alt="Proof of refund">
        </div>
        <div class="detail-proof-note">Refund has been processed for this order.</div>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn-sm" onclick="closeModal('detailModal')" style="background:var(--surface,#f4f5f8);color:var(--text);border:1.5px solid var(--border,#e8eaf0);">Close</button>
      <button class="btn-sm" id="detailApproveBtn" onclick="approveFromDetail()" style="background:#ef4444;color:#fff;border:none;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;vertical-align:-2px;"><polyline points="20 6 9 17 4 12"/></svg>
        <span id="detailApproveBtnLabel">Approve Order</span>
      </button>
    </div>
  </div>
</div>

<!-- ═══════════════ CONFIRM APPROVE MODAL ═══════════════ -->
<div class="modal-backdrop" id="confirmModal">
  <div class="modal">
    <div class="modal-header">
      <h3 id="confirmModalHeader">Confirm Approval</h3>
      <button class="modal-close" onclick="closeModal('confirmModal')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="confirm-icon">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <div class="confirm-text">
        <h4 id="confirmTitle">Approve Order #1042?</h4>
        <p id="confirmSub">This will generate a receipt and send a payment link to the customer.</p>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-sm" onclick="closeModal('confirmModal')" style="background:var(--surface,#f4f5f8);color:var(--text);border:1.5px solid var(--border,#e8eaf0);">Cancel</button>
      <button class="btn-sm primary" id="confirmApproveBtn" onclick="doApprove()">Approve & Generate Receipt</button>
    </div>
  </div>
</div>

<!-- ═══════════════ CANCEL ORDER MODAL ═══════════════ -->
<div class="modal-backdrop" id="cancelModal">
  <div class="modal">
    <div class="modal-header">
      <h3>Cancel Order</h3>
      <button class="modal-close" onclick="closeModal('cancelModal')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <p style="font-size:13px;color:var(--muted);margin-bottom:14px;" id="cancelModalSub">Cancel Order #1042?</p>
      <div class="field">
        <label for="cancelReasonSelect">Cancelation Reason</label>
        <select id="cancelReasonSelect" onchange="toggleOtherCancelReason()">
          <option value="Cancelled">Cancelled</option>
          <option value="Session Expired">Session Expired</option>
          <option value="Customer Request">Customer Request</option>
          <option value="Out of Stock">Out of Stock</option>
          <option value="Payment Failed">Payment Failed</option>
          <option value="Other">Other</option>
        </select>
      </div>
      <div class="field" id="cancelReasonOtherField" style="display:none;">
        <label for="cancelReasonOther">Specify Reason</label>
        <input type="text" id="cancelReasonOther" placeholder="Enter reason">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-sm" onclick="closeModal('cancelModal')" style="background:var(--surface,#f4f5f8);color:var(--text);border:1.5px solid var(--border,#e8eaf0);">Back</button>
      <button class="btn-sm" onclick="confirmCancelOrder()" style="background:var(--red);border-color:var(--red);color:#fff;">Cancel Order</button>
    </div>
  </div>
</div>

<!-- ═══════════════ REFUND MODAL ═══════════════ -->
<div class="modal-backdrop" id="refundModal">
  <div class="modal">
    <div class="modal-header">
      <h3>Process Refund</h3>
      <button class="modal-close" onclick="closeModal('refundModal')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <p style="font-size:13px;color:var(--muted);margin-bottom:14px;" id="refundModalSub">Refund Order #1037?</p>
      <div class="field">
        <label for="refundProofInput">Refund Proof</label>
        <input type="file" id="refundProofInput" accept="image/*" onchange="handleRefundProofSelect(event)">
      </div>
      <div id="refundProofPreviewWrap" style="display:none;margin-top:10px;">
        <img id="refundProofPreview" src="" alt="Refund proof preview" style="max-width:100%;border-radius:8px;border:1px solid var(--border);">
      </div>
      <p id="refundProofError" style="display:none;font-size:12px;color:var(--red);margin-top:8px;">Please upload a proof of refund before continuing.</p>
    </div>
    <div class="modal-footer">
      <button class="btn-sm" onclick="closeModal('refundModal')" style="background:var(--surface,#f4f5f8);color:var(--text);border:1.5px solid var(--border,#e8eaf0);">Cancel</button>
      <button class="btn-sm" onclick="confirmRefund()" style="background:#f97316;border-color:#f97316;color:#fff;">Confirm Refund</button>
    </div>
  </div>
</div>

<!-- ═══════════════ PRESCRIPTIONS MODAL ═══════════════ -->
<div class="modal-backdrop" id="prescriptionModal">
  <div class="modal detail-modal">
    <div class="modal-header">
      <h3>
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;vertical-align:-2px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/><line x1="9" y1="11" x2="12" y2="11"/></svg>
        Prescription Files
      </h3>
      <button class="modal-close" onclick="closeModal('prescriptionModal')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body" style="max-height:70vh;overflow-y:auto;">
      <p style="font-size:12px;color:var(--muted);margin-bottom:14px;" id="presModalSub">—</p>
      <div class="prescription-grid" id="prescriptionGrid">
        <!-- injected -->
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-sm" onclick="closeModal('prescriptionModal')" style="background:var(--surface,#f4f5f8);color:var(--text);border:1.5px solid var(--border,#e8eaf0);">Close</button>
    </div>
  </div>
</div>

<!-- ═══════════════ PRESCRIPTION ZOOM MODAL ═══════════════ -->
<div class="modal-backdrop" id="prescriptionZoomModal">
  <div class="modal" style="max-width:480px;">
    <div class="modal-header">
      <h3 id="presZoomLabel">Prescription</h3>
      <button class="modal-close" onclick="closeModal('prescriptionZoomModal')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body" style="text-align:center;">
      <img id="presZoomImage" src="" alt="Prescription" style="max-width:100%;border-radius:10px;border:1px solid var(--border);">
    </div>
    <div class="modal-footer">
      <button class="btn-sm" onclick="closeModal('prescriptionZoomModal')" style="background:var(--surface,#f4f5f8);color:var(--text);border:1.5px solid var(--border,#e8eaf0);">Close</button>
    </div>
  </div>
</div>

<!-- ═══════════════ RECEIPT MODAL ═══════════════ -->
<div class="modal-backdrop" id="receiptModal">
  <div class="modal">
    <div class="modal-header">
      <h3>Order Receipt — <span id="receiptOrderId"></span></h3>
      <button class="modal-close" onclick="closeModal('receiptModal')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-body">

      <!-- Receipt header -->
      <div class="receipt-header">
        <div class="rx-logo">💊 {{ $siteName }}</div>
        <div class="rx-sub">Official Sales Receipt</div>
      </div>

      <!-- Meta -->
      <div class="receipt-meta">
        <span class="rm-label">Customer</span><span class="rm-value" id="rCustomer">—</span>
        <span class="rm-label">Order #</span><span class="rm-value" id="rOrderId">—</span>
        <span class="rm-label">Date</span><span class="rm-value" id="rDate">—</span>
        <span class="rm-label">Payment</span><span class="rm-value" id="rMethod">—</span>
      </div>

      <!-- Items -->
      <table class="receipt-items">
        <thead>
          <tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
        </thead>
        <tbody id="rItemsBody"></tbody>
        <tfoot>
          <tr>
            <td colspan="3">Total</td>
            <td id="rTotal">—</td>
          </tr>
        </tfoot>
      </table>

      <!-- Prescriptions (only shown when the order contains prescription-required items) -->
      <div class="detail-proof" id="rPrescriptionSection" style="display:none;margin-bottom:18px;">
        <div class="detail-proof-label">Prescription Requirement</div>
        <button class="rx-view-btn" onclick="openPrescriptions(currentReceiptId)">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/><line x1="9" y1="11" x2="12" y2="11"/></svg>
          View Prescriptions (<span id="rPrescriptionCount">0</span>)
        </button>
      </div>

      <!-- Proof of payment (online app orders — image if uploaded, notice if not; stays visible after completion) -->
      <div class="detail-proof" id="rProofSection" style="display:none;">
        <div class="detail-proof-label">Proof of Payment</div>
        <div class="detail-proof-img-wrap" id="rProofImgWrap">
          <img id="rProofImage" src="" alt="Proof of payment">
        </div>
        <div class="detail-proof-note" id="rProofNote">Customer already paid via online app.</div>
      </div>

      <div class="receipt-summary">
        <div class="receipt-summary-row">
          <span>Payment</span>
          <span id="rPayment">—</span>
        </div>
        <div class="receipt-summary-row" id="rChangeRow" style="display:none;">
          <span>Change</span>
          <span id="rChange">—</span>
        </div>
      </div>

      <div class="payment-input-group" id="paymentInputGroup">
        <label for="paymentAmountInput">Payment Amount</label>
        <input type="number" id="paymentAmountInput" min="0" step="0.01" placeholder="Enter amount">
      </div>

      <div class="receipt-status" id="receiptStatus" style="display:none;">Transaction completed.</div>

    </div>
    <div class="modal-footer" id="receiptFooterActions"></div>
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
/* ─── Order Data (loaded from StaffOrdersController@data) ─── */
let ORDERS = [];

const CSRF   = document.querySelector('meta[name="csrf-token"]').content;
const ROUTES = {
  data: @json(route('staff.orders.data')),
  base: @json(url('/staff/orders')),
};

// Orders sit in "For Pick Up" for 5 hours; the server auto-cancels them after
// that with the reason "Order expired". Re-poll so the table catches it live.
const ORDERS_POLL_MS = 60 * 1000;

async function orderApi(method, path, body = null) {
  // FormData (file uploads) must NOT get a manual Content-Type — the browser
  // adds the multipart boundary itself.
  const isForm = body instanceof FormData;
  const headers = { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF };
  if (!isForm) headers['Content-Type'] = 'application/json';
  const res = await fetch(ROUTES.base + path, {
    method,
    headers,
    body: body ? (isForm ? body : JSON.stringify(body)) : null,
  });
  const data = await res.json().catch(() => ({}));
  if (!res.ok) throw new Error(data.message || 'Something went wrong.');
  return data;
}

async function loadOrders(silent = false) {
  try {
    const res = await fetch(ROUTES.data, { headers: { 'Accept': 'application/json' } });
    if (!res.ok) throw new Error('Failed to load orders.');
    const data = await res.json();
    ORDERS = data.orders;
    renderTable(currentFilter);
    if (data.expired_count > 0) {
      showToast(data.expired_count + ' order' + (data.expired_count > 1 ? 's' : '') + ' expired and cancelled.');
    }
  } catch (e) {
    if (!silent) showToast(e.message);
  }
}

/* ─── Item emoji map (fallback: 💊) ─── */
const ITEM_ICONS = {
  default: '💊',
  inhaler: '🫁',
  solution: '🧴',
  vitamin: '💛',
  syrup: '🍶',
};
function itemIcon(name) {
  const n = name.toLowerCase();
  if (n.includes('inhaler'))      return '🫁';
  if (n.includes('solution') || n.includes('betadine')) return '🧴';
  if (n.includes('vitamin'))      return '🌟';
  if (n.includes('syrup'))        return '🍶';
  return '💊';
}

/* helpers */
function orderTotal(order) {
  return order.items.reduce((s, i) => s + i.qty * i.price, 0);
}
function esc(v) { return String(v ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
function fmt(n) { return '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
function paymentLabel(order) { return order.payment === 'online' ? 'App' : 'Cash'; }
function paymentPill(order)  { return `<span class="pill ${order.payment === 'online' ? 'blue' : 'ok'}">${paymentLabel(order)}</span>`; }
// Online-app orders can't be reserved until the customer has uploaded proof of payment
function canReserve(order) { return order.payment !== 'online' || !!order.proofImage; }
function genRef() { return Math.random().toString(36).slice(2,10).toUpperCase(); }
function initials(name) {
  return name.split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase();
}

/* ─── Current filter & pending action ─── */
let currentFilter = 'pending';
let pendingApproveId = null;
let detailOrderId = null;
let currentReceiptId = null;

/* ─── Render table ─── */
function renderTable(filter) {
  currentFilter = filter;
  const body = document.getElementById('ordersBody');
  let filtered = [];
  if (filter === 'pending') {
    filtered = ORDERS.filter(o => o.status === 'pending');
  } else if (filter === 'confirmed') {
    filtered = ORDERS.filter(o => o.status === 'confirmed');
  } else if (filter === 'approved') {
    filtered = ORDERS.filter(o => o.status === 'approved');
  } else if (filter === 'completed') {
    filtered = ORDERS.filter(o => o.status === 'completed');
  } else if (filter === 'cancelled') {
    filtered = ORDERS.filter(o => o.status === 'cancelled');
  } else {
    filtered = ORDERS;
  }
  body.innerHTML = '';
  filtered.forEach(order => {
    const total = orderTotal(order);
    const isPending = order.status === 'pending';
    const isConfirmed = order.status === 'confirmed';
    const isApproved = order.status === 'approved'
    const isCompleted = order.status === 'completed';
    const isCancelled = order.status === 'cancelled';
    const tr = document.createElement('tr');

    const dueDate = order.dueDate || '—';

    tr.id = 'row-' + order.id.replace('#','');
    tr.innerHTML = `
      <td style="color:var(--muted);font-size:12px;" title="${esc(order.id)}">${esc(order.id)}</td>
      <td title="${esc(order.customer)}">${esc(order.customer)}</td>
      <td>${order.items.length} item${order.items.length > 1 ? 's' : ''}</td>
      <td style="font-weight:700;">${fmt(total)}</td>
      <td style="font-size:12px;color:var(--muted);" title="${esc(order.date)}">${esc(order.date)}</td>
      ${isApproved ? `<td style="font-size:12px;color:var(--muted);" title="${esc(dueDate)}">${esc(dueDate)}</td>` : ''}
      ${isCancelled ? `<td style="font-size:12px;color:var(--muted);" title="${esc(order.cancelReason || '—')}">${esc(order.cancelReason || '—')}</td>` : ''}
      <td>${paymentPill(order)}</td>
      <td>
        ${isCompleted
          ? '<span class="pill paid">Completed</span>'
          : isCancelled
            ? '<span class="pill danger">Cancelled</span>'
            : isPending
            ? `<span class="pill ${order.paidOnline ? 'blue' : 'warn'}">Pending</span>`
            : isConfirmed
              ? `<span class="pill blue">Confirmed</span>`
              : isApproved
                ? `<span class="pill ${order.paidOnline ? 'paid' : 'blue'}">For Pick Up</span>`
                : '<span class="pill ok">Completed</span>'}
      </td>
      <td class="col-action">
        <div class="action-cell">
          ${isCompleted
            ? `<button class="btn-detail" onclick="deleteCurrentReceiptOrder('${order.id}')">Delete</button> 
               <button class="btn-view" onclick="openReceipt('${order.id}')">Receipt</button>`
            : isCancelled
              ? `<div style="display:flex;align-items:center;gap:6px;flex-wrap:nowrap;"><button class="btn-view" onclick="openDetail('${order.id}')">Details</button>${order.paidOnline
                    ? (order.refunded
                        ? '<span class="pill ok">Refunded</span>'
                        : `<button class="btn-refund" onclick="openRefundModal('${order.id}')">Refund</button>`)
                    : ''}</div>`
              : isPending
                ? `<button class="btn-detail" onclick="rejectOrder('${order.id}')">Reject</button>
                   <button class="btn-view" onclick="openDetail('${order.id}')">Details</button>
                   <button class="btn-approve" onclick="openConfirm('${order.id}', 'confirm')">Approve</button>`
                : isConfirmed
                  ? `<button class="btn-view" onclick="openDetail('${order.id}')">Details</button>
                     <button class="btn-detail" onclick="openCancelModal('${order.id}')">Cancel</button>
                     <button class="btn-approve" onclick="openConfirm('${order.id}', 'reserve')" ${canReserve(order) ? '' : 'disabled title="Waiting for the customer to upload proof of payment"'}>Reserve</button>`
                  : isApproved
                    ? `<button class="btn-detail" onclick="openCancelModal('${order.id}')">Cancel</button>
                       <button class="btn-view" onclick="openReceipt('${order.id}')">Receipt</button>`
                    : ''
          }
        </div>
      </td>
    `;
    body.appendChild(tr);
  });

  const dueDateHeader = document.getElementById('dueDateHeader');
  if (dueDateHeader) dueDateHeader.style.display = filter === 'approved' ? '' : 'none';

  const cancelReasonHeader = document.getElementById('cancelReasonHeader');
  if (cancelReasonHeader) cancelReasonHeader.style.display = filter === 'cancelled' ? '' : 'none';

  updateStats();
  updateTabs();
  applySearch();
}

function filterTable(f) { renderTable(f); }

function updateStats() {
  document.getElementById('pendingCount').textContent  = ORDERS.filter(o => o.status === 'pending').length;
  document.getElementById('approvedCount').textContent = ORDERS.filter(o => o.status === 'completed').length;
  document.getElementById('awaitingPayCount').textContent = ORDERS.filter(o => o.status === 'approved').length;
}

function updateTabs() {
  ['pending','confirmed','approved','completed','cancelled'].forEach(f => {
    const el = document.getElementById('tab' + f.charAt(0).toUpperCase() + f.slice(1));
    el.classList.toggle('active-tab', f === currentFilter);
  });
}

/* ─── Detail modal ─── */
function openDetail(id) {
  const order = ORDERS.find(o => o.id === id);
  if (!order) return;
  detailOrderId = id;

  document.getElementById('dOrderId').textContent   = order.id;
  document.getElementById('dOrderMeta').textContent = order.date;
  document.getElementById('dCustomerInitials').textContent = initials(order.customer);
  document.getElementById('dCustomerName').textContent     = order.customer;
  document.getElementById('dItemCount').textContent  = order.items.length;

  // status & payment method badges
  const sBadge = document.getElementById('dStatusBadge');
  sBadge.textContent = order.status === 'pending' ? 'Pending' : order.status === 'confirmed' ? 'Confirmed' : order.status === 'approved' ? 'For Pick Up' : order.status === 'cancelled' ? 'Cancelled' : 'Completed';
  sBadge.className   = 'pill ' + (order.status === 'pending' ? (order.paidOnline ? 'blue' : 'warn') : order.status === 'confirmed' ? 'blue' : order.status === 'approved' ? (order.paidOnline ? 'paid' : 'blue') : order.status === 'cancelled' ? 'danger' : 'ok');

  const pBadge = document.getElementById('dPaymentBadge');
  pBadge.textContent = paymentLabel(order);
  pBadge.className   = 'pill ' + (order.payment === 'online' ? 'blue' : 'ok');

  // cart items
  const cartEl = document.getElementById('dCartItems');
  cartEl.innerHTML = '';
  order.items.forEach(item => {
    const subtotal = item.qty * item.price;
    const div = document.createElement('div');
    div.className = 'cart-item';
    div.innerHTML = `
      <div class="cart-item-icon">${itemIcon(item.name)}</div>
      <div class="cart-item-info">
        <div class="cart-item-name">${item.name}${item.requiresPrescription ? ' <span class="pill blue" style="font-size:9px;padding:1px 6px;vertical-align:1px;">Rx</span>' : ''}</div>
        <div class="cart-item-qty">Qty: ${item.qty} &nbsp;·&nbsp; ${fmt(item.price)} each</div>
      </div>
      <div>
        <div class="cart-item-price">${fmt(subtotal)}</div>
        <div class="cart-item-unit-price">subtotal</div>
      </div>
    `;
    cartEl.appendChild(div);
  });

  // summary
  const subtotal = orderTotal(order);
  const vat      = subtotal * 0.12;
  const total    = subtotal + vat;
  document.getElementById('dSubtotal').textContent = fmt(subtotal);
  document.getElementById('dVat').textContent      = fmt(vat);
  document.getElementById('dTotal').textContent    = fmt(total);

  // proof of payment (online app orders only — image if uploaded, notice if not)
  const proofSection = document.getElementById('dProofSection');
  const proofImgWrap = document.getElementById('dProofImgWrap');
  const proofNote    = document.getElementById('dProofNote');
  const uploadWrap   = document.getElementById('dProofUploadWrap');
  uploadWrap.style.display = 'none';
  document.getElementById('dProofUploadInput').value = '';
  if (order.proofImage) {
    document.getElementById('dProofImage').src = order.proofImage;
    proofImgWrap.style.display = '';
    proofSection.style.display = '';
    proofNote.textContent = order.status === 'cancelled'
      ? (order.refunded ? 'Customer already paid via online app — refund has been processed.' : 'Customer already paid via online app — refund pending.')
      : order.status === 'confirmed'
        ? 'Customer already paid via online app — ready to reserve.'
        : 'Customer already paid via online app — awaiting approval.';
  } else if (order.payment === 'online') {
    proofImgWrap.style.display = 'none';
    proofSection.style.display = '';
    proofNote.textContent = 'No proof of payment uploaded yet.' + (order.status === 'confirmed' ? ' The order can be reserved once the customer uploads it.' : '');
    if (order.status === 'pending' || order.status === 'confirmed') uploadWrap.style.display = '';
  } else {
    proofSection.style.display = 'none';
  }

  const refundProofSection = document.getElementById('dRefundProofSection');
  if (order.refunded && order.refundProof) {
    document.getElementById('dRefundProofImage').src = order.refundProof;
    refundProofSection.style.display = '';
  } else {
    refundProofSection.style.display = 'none';
  }

  // prescriptions — only shown when at least one item in the order requires one
  const rxItems = order.items.filter(i => i.requiresPrescription);
  const prescriptionSection = document.getElementById('dPrescriptionSection');
  if (rxItems.length > 0) {
    document.getElementById('dPrescriptionCount').textContent = rxItems.length;
    prescriptionSection.style.display = '';
  } else {
    prescriptionSection.style.display = 'none';
  }

  // show/hide approve/reserve button in footer
  const approveBtn = document.getElementById('detailApproveBtn');
  const approveBtnLabel = document.getElementById('detailApproveBtnLabel');
  if (order.status === 'pending') {
    approveBtn.style.display = '';
    approveBtnLabel.textContent = 'Approve Order';
  } else if (order.status === 'confirmed') {
    approveBtn.style.display = '';
    approveBtnLabel.textContent = 'Reserve Order';
  } else {
    approveBtn.style.display = 'none';
  }
  const blocked = order.status === 'confirmed' && !canReserve(order);
  approveBtn.disabled = blocked;
  approveBtn.title    = blocked ? 'Waiting for the customer to upload proof of payment' : '';
  approveBtn.style.opacity = blocked ? '.45' : '';
  approveBtn.style.cursor  = blocked ? 'not-allowed' : '';

  openModal('detailModal');
}

/* ─── Prescriptions gallery (opened from the details modal) ─── */
function openPrescriptions(orderId = detailOrderId) {
  const order = ORDERS.find(o => o.id === orderId);
  if (!order) return;

  const rxItems = order.items.filter(i => i.requiresPrescription);
  document.getElementById('presModalSub').textContent =
    `${rxItems.length} prescription file${rxItems.length > 1 ? 's' : ''} attached to Order ${order.id}`;

  const grid = document.getElementById('prescriptionGrid');
  grid.innerHTML = '';
  rxItems.forEach(item => {
    const card = document.createElement('div');
    card.className = 'prescription-card';
    card.onclick = () => openPrescriptionZoom(item.prescriptionImage, item.name);
    card.innerHTML = `
      <img src="${item.prescriptionImage}" alt="Prescription for ${item.name}">
      <div class="prescription-card-label">${item.name}</div>
    `;
    grid.appendChild(card);
  });

  openModal('prescriptionModal');
}

function openPrescriptionZoom(src, name) {
  document.getElementById('presZoomImage').src = src;
  document.getElementById('presZoomLabel').textContent = name;
  openModal('prescriptionZoomModal');
}

function handleProofUpload(event) {
  const file = event.target.files && event.target.files[0];
  const order = ORDERS.find(o => o.id === detailOrderId);
  if (!file || !order) return;
  const reader = new FileReader();
  reader.onload = e => {
    order.proofImage = e.target.result;
    order.paidOnline = true;
    renderTable(currentFilter);
    openDetail(order.id);
    showToast('Proof of payment uploaded for order ' + order.id + '.');
  };
  reader.readAsDataURL(file);
}

function approveFromDetail() {
  const order = ORDERS.find(o => o.id === detailOrderId);
  if (!order) return;
  if (order.status === 'confirmed' && !canReserve(order)) return;
  const type = order.status === 'confirmed' ? 'reserve' : 'confirm';
  closeModal('detailModal');
  setTimeout(() => openConfirm(detailOrderId, type), 200);
}

async function rejectOrder(id) {
  const order = ORDERS.find(o => o.id === id);
  if (!order) return;
  try {
    await orderApi('PUT', `/${order.dbId}/cancel`, { reason: 'Rejected by pharmacy' });
    await loadOrders(true);
    showToast('Order ' + id + ' has been rejected.');
  } catch (e) { showToast(e.message); }
}

let pendingCancelId = null;

function openCancelModal(id) {
  const order = ORDERS.find(o => o.id === id);
  if (!order) return;
  pendingCancelId = id;
  document.getElementById('cancelModalSub').textContent = `Cancel Order ${id}?`;
  document.getElementById('cancelReasonSelect').value = 'Cancelled';
  document.getElementById('cancelReasonOtherField').style.display = 'none';
  document.getElementById('cancelReasonOther').value = '';
  openModal('cancelModal');
}

function toggleOtherCancelReason() {
  const sel = document.getElementById('cancelReasonSelect').value;
  document.getElementById('cancelReasonOtherField').style.display = sel === 'Other' ? '' : 'none';
}

async function confirmCancelOrder() {
  const order = ORDERS.find(o => o.id === pendingCancelId);
  if (!order) return;
  const selected = document.getElementById('cancelReasonSelect').value;
  const reason = selected === 'Other'
    ? (document.getElementById('cancelReasonOther').value.trim() || 'Other')
    : selected;

  closeModal('cancelModal');
  try {
    await orderApi('PUT', `/${order.dbId}/cancel`, { reason });
    await loadOrders(true);
    showToast('Order ' + order.id + ' cancelled — ' + reason + '.');
  } catch (e) { showToast(e.message); }
}

let pendingRefundId = null;      // order_number shown in the UI (e.g. ORD-20260926-00001)
let pendingRefundFile = null;    // the File chosen in the modal
let refundSubmitting = false;

const REFUND_MAX_BYTES = 5 * 1024 * 1024; // keep in sync with StaffOrdersController@refund

function resetRefundModal() {
  pendingRefundFile = null;
  refundSubmitting = false;
  document.getElementById('refundProofInput').value = '';
  document.getElementById('refundProofPreviewWrap').style.display = 'none';
  document.getElementById('refundProofPreview').src = '';
  const err = document.getElementById('refundProofError');
  err.style.display = 'none';
  err.textContent = 'Please upload a proof of refund before continuing.';
  setRefundBusy(false);
}

function setRefundBusy(busy) {
  const btn = document.querySelector('#refundModal .btn-sm[onclick="confirmRefund()"]');
  if (!btn) return;
  btn.disabled = busy;
  btn.textContent = busy ? 'Processing…' : 'Confirm Refund';
}

function showRefundError(msg) {
  const err = document.getElementById('refundProofError');
  err.textContent = msg;
  err.style.display = '';
}

function openRefundModal(id) {
  const order = ORDERS.find(o => o.id === id);
  if (!order) return;
  if (order.refunded) { showToast('This order has already been refunded.'); return; }
  pendingRefundId = id;
  resetRefundModal();
  document.getElementById('refundModalSub').textContent =
    `Refund Order ${id} (${fmt(orderTotal(order))})?`;
  openModal('refundModal');
}

function handleRefundProofSelect(event) {
  const file = event.target.files && event.target.files[0];
  if (!file) {
    pendingRefundFile = null;
    document.getElementById('refundProofPreviewWrap').style.display = 'none';
    return;
  }
  if (!file.type.startsWith('image/')) {
    pendingRefundFile = null;
    event.target.value = '';
    document.getElementById('refundProofPreviewWrap').style.display = 'none';
    showRefundError('Please choose an image file.');
    return;
  }
  if (file.size > REFUND_MAX_BYTES) {
    pendingRefundFile = null;
    event.target.value = '';
    document.getElementById('refundProofPreviewWrap').style.display = 'none';
    showRefundError('Image is too large (max 5 MB).');
    return;
  }
  pendingRefundFile = file;
  const reader = new FileReader();
  reader.onload = e => {
    document.getElementById('refundProofPreview').src = e.target.result;
    document.getElementById('refundProofPreviewWrap').style.display = '';
    document.getElementById('refundProofError').style.display = 'none';
  };
  reader.readAsDataURL(file);
}

async function confirmRefund() {
  if (refundSubmitting) return;
  const order = ORDERS.find(o => o.id === pendingRefundId);
  if (!order) return;

  if (!pendingRefundFile) {
    showRefundError('Please upload a proof of refund before continuing.');
    return;
  }

  refundSubmitting = true;
  setRefundBusy(true);

  try {
    const form = new FormData();
    form.append('proof_of_refund', pendingRefundFile);
    // POST (not PUT): PHP doesn't parse multipart bodies on PUT requests.
    await orderApi('POST', `/${order.dbId}/refund`, form);

    const refundedId = pendingRefundId;
    closeModal('refundModal');
    resetRefundModal();
    await loadOrders(true);   // pulls refunded + refundProof from the server
    showToast('Refund processed for order ' + refundedId + '.');
  } catch (e) {
    refundSubmitting = false;
    setRefundBusy(false);
    showRefundError(e.message);
  }
}

/* ─── Confirm modal ─── */
let pendingActionType = null; // 'confirm' (pending → confirmed) or 'reserve' (confirmed → for pick up)

function openConfirm(id, type) {
  const guard = ORDERS.find(o => o.id === id);
  if (type === 'reserve' && guard && !canReserve(guard)) return;
  pendingApproveId = id;
  pendingActionType = type;
  const order = ORDERS.find(o => o.id === id);
  const headerEl = document.getElementById('confirmModalHeader');
  const titleEl  = document.getElementById('confirmTitle');
  const subEl    = document.getElementById('confirmSub');
  const btnEl    = document.getElementById('confirmApproveBtn');

  if (type === 'reserve') {
    headerEl.textContent = 'Confirm Reservation';
    titleEl.textContent  = `Reserve Order ${id}?`;
    subEl.textContent    = `This will generate a receipt for ${order.customer} and send a payment link.`;
    btnEl.textContent    = 'Reserve & Generate Receipt';
  } else {
    headerEl.textContent = 'Confirm Approval';
    titleEl.textContent  = `Approve Order ${id}?`;
    subEl.textContent    = `This will confirm the order for ${order.customer} and prepare it for reservation.`;
    btnEl.textContent    = 'Approve';
  }
  openModal('confirmModal');
}

async function doApprove() {
  const order = ORDERS.find(o => o.id === pendingApproveId);
  if (!order) return;
  const isReserve = pendingActionType === 'reserve';
  closeModal('confirmModal');

  try {
    await orderApi('PUT', `/${order.dbId}/${isReserve ? 'ready' : 'confirm'}`);
    await loadOrders(true);
    if (isReserve) {
      showToast('Order ' + order.id + ' reserved — now for pick up!');
      setTimeout(() => openReceipt(order.id), 400);
    } else {
      showToast('Order ' + order.id + ' confirmed!');
    }
  } catch (e) { showToast(e.message); }
}

/* ─── Receipt modal ─── */
function updateReceiptFooter(order) {
  const footer = document.getElementById('receiptFooterActions');
  if (!footer) return;

  if (order.status === 'completed') {
    footer.innerHTML = `
      <button class="btn-sm" onclick="deleteCurrentReceiptOrder()" style="background:#f0fdf4;color:#16a34a;border:1.5px solid #bbf7d0;">Delete</button>
      <button class="btn-sm" onclick="printReceipt()" style="background:var(--surface,#f4f5f8);color:var(--text);border:1.5px solid var(--border,#e8eaf0);">Print Receipt</button>
      `;
  } else {
    footer.innerHTML = `
      <button class="btn-sm" onclick="closeModal('receiptModal')" style="background:var(--surface,#f4f5f8);color:var(--text);border:1.5px solid var(--border,#e8eaf0);">Close</button>
      <button class="btn-sm primary" onclick="submitReceipt()">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;vertical-align:-2px;"><polyline points="20 6 9 17 4 12"/></svg>
        Submit
      </button>
    `;
  }
}

function openReceipt(id) {
  const order = ORDERS.find(o => o.id === id);
  if (!order) return;
  currentReceiptId = id;
  document.getElementById('receiptOrderId').textContent = order.id;
  document.getElementById('rCustomer').textContent = order.customer;
  document.getElementById('rOrderId').textContent   = order.id;
  document.getElementById('rDate').textContent      = order.date;
  document.getElementById('rMethod').textContent    = paymentLabel(order);

  const tbody = document.getElementById('rItemsBody');
  tbody.innerHTML = '';
  order.items.forEach(item => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${item.name}</td>
      <td>${item.qty}</td>
      <td>${fmt(item.price)}</td>
      <td>${fmt(item.qty * item.price)}</td>
    `;
    tbody.appendChild(tr);
  });

  document.getElementById('rTotal').textContent = fmt(orderTotal(order));

  // prescriptions — only shown when at least one item in the order requires one
  const rxItems = order.items.filter(i => i.requiresPrescription);
  const rPrescriptionSection = document.getElementById('rPrescriptionSection');
  if (rxItems.length > 0) {
    document.getElementById('rPrescriptionCount').textContent = rxItems.length;
    rPrescriptionSection.style.display = '';
  } else {
    rPrescriptionSection.style.display = 'none';
  }

  // proof of payment — online app orders only, for every status (incl. completed)
  const rProofSection = document.getElementById('rProofSection');
  const rProofImgWrap = document.getElementById('rProofImgWrap');
  const rProofNote    = document.getElementById('rProofNote');
  if (order.proofImage) {
    document.getElementById('rProofImage').src = order.proofImage;
    rProofImgWrap.style.display = '';
    rProofNote.textContent = 'Customer already paid via online app.';
    rProofSection.style.display = '';
  } else if (order.payment === 'online') {
    rProofImgWrap.style.display = 'none';
    rProofNote.textContent = 'No proof of payment uploaded.';
    rProofSection.style.display = '';
  } else {
    rProofSection.style.display = 'none';
  }

  const paymentInputGroup = document.getElementById('paymentInputGroup');
  const receiptStatus = document.getElementById('receiptStatus');
  const paymentInput = document.getElementById('paymentAmountInput');
  paymentInput.value = '';
  if (order.status === 'completed') {
    paymentInputGroup.style.display = 'none';
    receiptStatus.style.display = '';
    receiptStatus.textContent = 'Transaction completed.';
    document.getElementById('rPayment').textContent = fmt(order.paymentAmount || 0);
    document.getElementById('rChangeRow').style.display = order.change > 0 ? '' : 'none';
    document.getElementById('rChange').textContent = fmt(order.change || 0);
  } else {
    paymentInputGroup.style.display = '';
    receiptStatus.style.display = 'none';
    document.getElementById('rPayment').textContent = '—';
    document.getElementById('rChangeRow').style.display = 'none';
    document.getElementById('rChange').textContent = '—';
  }

  updateReceiptFooter(order);
  openModal('receiptModal');
}

async function submitReceipt() {
  const order = ORDERS.find(o => o.id === currentReceiptId);
  if (!order) return;
  const paymentValue = parseFloat(document.getElementById('paymentAmountInput').value);
  if (Number.isNaN(paymentValue) || paymentValue < 0) {
    showToast('Enter a valid payment amount.');
    return;
  }

  const total = orderTotal(order);
  try {
    await orderApi('PUT', `/${order.dbId}/complete`);
    await loadOrders(true);
  } catch (e) {
    // e.g. the order expired while the receipt was open
    showToast(e.message);
    closeModal('receiptModal');
    await loadOrders(true);
    return;
  }

  const done = ORDERS.find(o => o.id === currentReceiptId);
  if (!done) return;
  done.paymentAmount = paymentValue;
  done.change = Math.max(0, paymentValue - total);

  document.getElementById('paymentInputGroup').style.display = 'none';
  const receiptStatus = document.getElementById('receiptStatus');
  receiptStatus.style.display = '';
  receiptStatus.textContent = 'Transaction completed.';
  document.getElementById('rPayment').textContent = fmt(done.paymentAmount);
  document.getElementById('rChangeRow').style.display = done.change > 0 ? '' : 'none';
  document.getElementById('rChange').textContent = fmt(done.change);

  updateReceiptFooter(done);
  renderTable('completed');
  showToast('Transaction completed.');
}

async function deleteCurrentReceiptOrder(id = currentReceiptId) {
  const order = ORDERS.find(o => o.id === id);
  if (!order) return;
  try {
    await orderApi('DELETE', `/${order.dbId}`);
    closeModal('receiptModal');
    await loadOrders(true);
    showToast(`Order ${order.id} deleted.`);
  } catch (e) { showToast(e.message); }
}

function printReceipt() {
  window.print();
}

/* ─── Modal helpers ─── */
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

/* ─── Toast ─── */
function showToast(msg) {
  const t = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;
  t.classList.add('on');
  setTimeout(() => t.classList.remove('on'), 2800);
}

/* ─── Search ─── */
function applySearch() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('#ordersBody tr').forEach(tr => {
    tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}
document.getElementById('searchInput').addEventListener('input', applySearch);

/* ─── Close modals on backdrop click ─── */
document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
  backdrop.addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
  });
});

/* ─── Init ─── */
renderTable('pending');            // paint the empty shell immediately
loadOrders();                     // fetch (and sweep expired pickups)
setInterval(() => loadOrders(true), ORDERS_POLL_MS);
</script>
</body>
</html>