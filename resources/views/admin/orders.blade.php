<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $siteName }} — Sales Records</title>
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
    background: #f0fdf4;
    color: #166534;
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
    background: #f0fdf4;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
  }
  .confirm-icon svg { color: #22c55e; }
  .confirm-text { text-align: center; }
  .confirm-text h4 { font-size: 15px; font-weight: 700; color: var(--text); margin: 0 0 6px; }
  .confirm-text p  { font-size: 13px; color: var(--muted); margin: 0; line-height: 1.5; }

  /* ── Order row status badges ── */
  .pill.warn { background: #fffbeb; color: #92400e; }
  .pill.paid { background: #f0fdf4; color: #15803d; }

  /* ── Approve / View / Detail Buttons ── */
  .btn-approve {
    font-size: 12px; font-weight: 600;
    background: #22c55e; color: #fff;
    border: none; border-radius: 7px;
    padding: 5px 13px; cursor: pointer;
    transition: background .15s, transform .1s;
  }
  .btn-approve:hover { background: #16a34a; }
  .btn-approve:active { transform: scale(.96); }

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
    background: #ffefef; color: #eb2525;
    border: 1.5px solid #febfbf; border-radius: 7px;
    padding: 5px 13px; cursor: pointer;
    transition: background .15s, border-color .15s;
  }
  .btn-detail:hover { background: #fedbdb; border-color: #fd9393; }

  /* ── Action cell ── */
  .action-cell { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }

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

  /* dark-mode patches */
  [data-theme="dark"] .confirm-icon { background: #052e16; }
  [data-theme="dark"] .modal { background: var(--card, #1e2130); }
  [data-theme="dark"] .receipt-header .rx-logo,
  [data-theme="dark"] .confirm-text h4 { color: #f1f5f9; }
  [data-theme="dark"] .btn-detail { background: #5f1e1e; border-color: #eb2525; }
  [data-theme="dark"] .btn-detail:hover { background: #d81d1d; }
  [data-theme="dark"] .cart-item { background: rgba(255,255,255,.05); }
  [data-theme="dark"] .cart-item:hover { background: rgba(255,255,255,.09); }
  [data-theme="dark"] .cart-item-icon { background: #1e3a5f; }
  [data-theme="dark"] .detail-customer-chip { background: rgba(255,255,255,.07); }
  [data-theme="dark"] .payment-input-group input { background: rgba(255,255,255,.05); color: #f1f5f9; }
  [data-theme="dark"] .receipt-status { background: #052e16; color: #dcfce7; }
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
  <a class="nav-item active" href="{{ route('admin.orders') }}">
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
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
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
        <span style="font-size:12px;color:var(--muted);cursor:pointer;" id="tabApproved" class="tab-link" onclick="filterTable('approved')">Approved</span>
        <span style="font-size:12px;color:var(--muted);cursor:pointer;" id="tabCompleted" class="tab-link" onclick="filterTable('completed')">Completed</span>
      </div>
    </div>
    <style>
      .tab-link { cursor:pointer; padding:4px 10px; border-radius:6px; font-weight:600; transition:background .15s,color .15s; }
      .tab-link:hover { background:var(--surface,#f4f5f8); color:var(--text); }
      .tab-link.active-tab { background:var(--primary,#f63b3b); color:#fff !important; }
    </style>
    <div class="table-wrap">
      <table id="ordersTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Items</th>
            <th>Total</th>
            <th>Date</th>
            <th id="dueDateHeader" style="display:none;">Due Date</th>
            <th>Type</th>
            <th>Status</th>
            <th>Action</th>
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
          <span id="dTypeBadge" class="pill ok">OTC</span>
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

      <!-- Proof of payment (only shown for orders already paid via online app) -->
      <div class="detail-proof" id="dProofSection" style="display:none;">
        <div class="detail-proof-label">Proof of Payment</div>
        <div class="detail-proof-img-wrap">
          <img id="dProofImage" src="" alt="Proof of payment">
        </div>
        <div class="detail-proof-note">Customer already paid via online app — awaiting approval.</div>
      </div>

    </div>
    <div class="modal-footer">
      <button class="btn-sm" onclick="closeModal('detailModal')" style="background:var(--surface,#f4f5f8);color:var(--text);border:1.5px solid var(--border,#e8eaf0);">Close</button>
      <button class="btn-sm" id="detailApproveBtn" onclick="approveFromDetail()" style="background:#22c55e;color:#fff;border:none;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;vertical-align:-2px;"><polyline points="20 6 9 17 4 12"/></svg>
        Approve Order
      </button>
    </div>
  </div>
</div>

<!-- ═══════════════ CONFIRM APPROVE MODAL ═══════════════ -->
<div class="modal-backdrop" id="confirmModal">
  <div class="modal">
    <div class="modal-header">
      <h3>Confirm Approval</h3>
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
        <span class="rm-label">Type</span><span class="rm-value" id="rType">—</span>
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

      <!-- Proof of payment (only shown while awaiting approval/payment, hidden once completed) -->
      <div class="detail-proof" id="rProofSection" style="display:none;">
        <div class="detail-proof-label">Proof of Payment</div>
        <div class="detail-proof-img-wrap">
          <img id="rProofImage" src="" alt="Proof of payment">
        </div>
        <div class="detail-proof-note">Customer already paid via online app.</div>
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
/* ─── Order Data ─── */
const ORDERS = [
  {
    id: '#1042', customer: 'Maria Santos', date: 'Mar 30 · 9:14 AM',
    type: 'OTC', status: 'pending',
    items: [
      { name: 'Biogesic 500mg', qty: 2, price: 12 },
      { name: 'Kremil-S Tablet', qty: 1, price: 18 },
      { name: 'Vitamin C 500mg', qty: 1, price: 8 },
    ]
  },
  {
    id: '#1041', customer: 'Juan dela Cruz', date: 'Mar 30 · 8:55 AM',
    type: 'Rx', status: 'pending',
    items: [
      { name: 'Losartan 50mg', qty: 30, price: 9.5 },
      { name: 'Metformin 500mg', qty: 30, price: 8.2 },
      { name: 'Atorvastatin 20mg', qty: 14, price: 12 },
    ]
  },
  {
    id: '#1040', customer: 'Ana Reyes', date: 'Mar 29 · 2:12 PM',
    type: 'OTC', status: 'approved',
    items: [
      { name: 'Neozep Forte', qty: 1, price: 55 },
      { name: 'Alaxan FR', qty: 1, price: 33 },
    ]
  },
  {
    id: '#1039', customer: 'Pedro Lim', date: 'Mar 29 · 11:05 AM',
    type: 'Rx', status: 'pending',
    items: [
      { name: 'Amlodipine 5mg', qty: 30, price: 7 },
      { name: 'Omeprazole 20mg', qty: 14, price: 12 },
      { name: 'Salbutamol Inhaler', qty: 1, price: 285 },
      { name: 'Cetirizine 10mg', qty: 10, price: 6.5 },
    ]
  },
  {
    id: '#1038', customer: 'Rosa Garcia', date: 'Mar 29 · 9:30 AM',
    type: 'OTC', status: 'pending',
    items: [
      { name: 'Betadine Solution 60ml', qty: 1, price: 55 },
    ]
  },
  {
    id: '#1043', customer: 'Carlos Tan', date: 'Mar 30 · 9:20 AM',
    type: 'Rx', status: 'approved', paidOnline: true,
    proofImage: 'payment-proof-1043.jfif',
    items: [
      { name: 'Losartan 50mg', qty: 30, price: 9.5 },
      { name: 'Metformin 500mg', qty: 60, price: 8.2 },
      { name: 'Rosuvastatin 10mg', qty: 30, price: 15 },
    ]
  },
];

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
function fmt(n) { return '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
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
  } else if (filter === 'approved') {
    filtered = ORDERS.filter(o => o.status === 'approved');
  } else if (filter === 'completed') {
    filtered = ORDERS.filter(o => o.status === 'completed');
  } else {
    filtered = ORDERS;
  }
  body.innerHTML = '';
  filtered.forEach(order => {
    const total = orderTotal(order);
    const isPending = order.status === 'pending';
    const isApproved = order.status === 'approved'
    const isCompleted = order.status === 'completed';
    const tr = document.createElement('tr');

    let dueDate = '';
    if (isApproved) {
      const orderDate = new Date(order.date.replace('·', ''));
      orderDate.setDate(orderDate.getDate() + 3);
      dueDate = orderDate.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric'
      }).replace(',', ' ·');
    }

    tr.id = 'row-' + order.id.replace('#','');
    tr.innerHTML = `
      <td style="color:var(--muted);font-size:12px;">${order.id}</td>
      <td>${order.customer}</td>
      <td>${order.items.length} item${order.items.length > 1 ? 's' : ''}</td>
      <td style="font-weight:700;">${fmt(total)}</td>
      <td style="font-size:12px;color:var(--muted);">${order.date}</td>
      ${isApproved ? `<td style="font-size:12px;color:var(--muted);">${dueDate}</td>` : ''}
      <td><span class="pill ${order.type === 'OTC' ? 'ok' : 'blue'}">${order.type}</span></td>
      <td>
        ${isCompleted
          ? '<span class="pill paid">Completed</span>'
          : isPending
          ? `<span class="pill ${order.paidOnline ? 'blue' : 'warn'}">Pending</span>`
          : isApproved
            ? `<span class="pill ${order.paidOnline ? 'paid' : 'blue'}">Approved</span>`
            : '<span class="pill ok">Completed</span>'}
      </td>
      <td>
        <div class="action-cell">
          ${isCompleted
            ? `<button class="btn-detail" onclick="deleteCurrentReceiptOrder('${order.id}')">Delete</button> 
               <button class="btn-view" onclick="openReceipt('${order.id}')">Receipt</button>`
            : isPending
              ? `<button class="btn-detail" onclick="rejectOrder('${order.id}')">Reject</button>
                 <button class="btn-view" onclick="openDetail('${order.id}')">Details</button>
                 <button class="btn-approve" onclick="openConfirm('${order.id}')">Approve</button>`
              : isApproved
                ? `<button class="btn-detail" onclick="cancelOrder('${order.id}')">Cancel</button>
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

  updateStats();
  updateTabs();
}

function filterTable(f) { renderTable(f); }

function updateStats() {
  document.getElementById('pendingCount').textContent  = ORDERS.filter(o => o.status === 'pending').length;
  document.getElementById('approvedCount').textContent = ORDERS.filter(o => o.status === 'completed').length;
  document.getElementById('awaitingPayCount').textContent = ORDERS.filter(o => o.status === 'approved').length;
}

function updateTabs() {
  ['pending','approved','completed'].forEach(f => {
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

  // status & type badges
  const sBadge = document.getElementById('dStatusBadge');
  sBadge.textContent = order.status === 'pending' ? 'Pending' : order.status === 'approved' ? 'Approved' : 'Completed';
  sBadge.className   = 'pill ' + (order.status === 'pending' ? (order.paidOnline ? 'blue' : 'warn') : order.status === 'approved' ? (order.paidOnline ? 'paid' : 'blue') : 'ok');

  const tBadge = document.getElementById('dTypeBadge');
  tBadge.textContent = order.type;
  tBadge.className   = 'pill ' + (order.type === 'OTC' ? 'ok' : 'blue');

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
        <div class="cart-item-name">${item.name}</div>
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

  // proof of payment (only for orders already paid via an online app)
  const proofSection = document.getElementById('dProofSection');
  if (order.proofImage) {
    document.getElementById('dProofImage').src = order.proofImage;
    proofSection.style.display = '';
  } else {
    proofSection.style.display = 'none';
  }

  // show/hide approve button in footer
  const approveBtn = document.getElementById('detailApproveBtn');
  approveBtn.style.display = order.status === 'pending' ? '' : 'none';

  openModal('detailModal');
}

function approveFromDetail() {
  closeModal('detailModal');
  setTimeout(() => openConfirm(detailOrderId), 200);
}

function rejectOrder(id) {
  const order = ORDERS.find(o => o.id === id);
  if (!order) return;
  order.status = 'rejected'; // Or 'cancelled'
  renderTable(currentFilter);
  showToast('Order ' + id + ' has been rejected.');
}

function cancelOrder(id) {
  const order = ORDERS.find(o => o.id === id);
  if (!order) return;
  order.status = 'cancelled';
  renderTable(currentFilter);
  showToast('Order ' + id + ' cancelled.');
}

/* ─── Confirm modal ─── */
function openConfirm(id) {
  pendingApproveId = id;
  document.getElementById('confirmTitle').textContent = `Approve Order ${id}?`;
  const order = ORDERS.find(o => o.id === id);
  document.getElementById('confirmSub').textContent =
    `This will generate a receipt for ${order.customer} and send a payment link.`;
  openModal('confirmModal');
}

function doApprove() {
  const order = ORDERS.find(o => o.id === pendingApproveId);
  if (!order) return;
  order.status = 'approved';
  closeModal('confirmModal');
  renderTable(currentFilter);
  showToast('Order ' + pendingApproveId + ' approved!');
  setTimeout(() => openReceipt(pendingApproveId), 400);
}

/* ─── Receipt modal ─── */
function updateReceiptFooter(order) {
  const footer = document.getElementById('receiptFooterActions');
  if (!footer) return;

  if (order.status === 'completed') {
    footer.innerHTML = `
      <button class="btn-sm" onclick="deleteCurrentReceiptOrder()" style="background:#fff1f2;color:#e11d48;border:1.5px solid #fecdd3;">Delete</button>
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
  document.getElementById('rType').textContent      = order.type;

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

  // proof of payment — only while awaiting approval/payment, never once completed
  const rProofSection = document.getElementById('rProofSection');
  if (order.proofImage && order.status !== 'completed') {
    document.getElementById('rProofImage').src = order.proofImage;
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

function submitReceipt() {
  const order = ORDERS.find(o => o.id === currentReceiptId);
  if (!order) return;
  const paymentValue = parseFloat(document.getElementById('paymentAmountInput').value);
  if (Number.isNaN(paymentValue) || paymentValue < 0) {
    showToast('Enter a valid payment amount.');
    return;
  }

  const total = orderTotal(order);
  order.status = 'completed';
  order.paymentAmount = paymentValue;
  order.change = Math.max(0, paymentValue - total);

  document.getElementById('paymentInputGroup').style.display = 'none';
  const receiptStatus = document.getElementById('receiptStatus');
  receiptStatus.style.display = '';
  receiptStatus.textContent = 'Transaction completed.';

  // proof of payment — hide now that the order is completed
  document.getElementById('rProofSection').style.display = 'none';

  document.getElementById('rPayment').textContent = fmt(order.paymentAmount);
  document.getElementById('rChangeRow').style.display = order.change > 0 ? '' : 'none';
  document.getElementById('rChange').textContent = fmt(order.change);

  updateReceiptFooter(order);
  renderTable('completed');
  showToast('Transaction completed.');
}

function deleteCurrentReceiptOrder() {
  const index = ORDERS.findIndex(o => o.id === currentReceiptId);
  if (index === -1) return;

  const deleted = ORDERS[index];
  ORDERS.splice(index, 1);
  closeModal('receiptModal');
  renderTable(currentFilter);
  showToast(`Order ${deleted.id} deleted.`);
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
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2800);
}

/* ─── Search ─── */
document.getElementById('searchInput').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('#ordersBody tr').forEach(tr => {
    tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
});

/* ─── Close modals on backdrop click ─── */
document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
  backdrop.addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
  });
});

/* ─── Init ─── */
renderTable('pending');
</script>
</body>
</html>