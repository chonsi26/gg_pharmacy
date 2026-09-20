<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $siteName }} — Staffs</title>
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
  /* ── Staff Card Grid ──────────────────────────────── */
  .staff-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    gap: 16px;
    padding: 4px 2px;
  }
  .staff-card {
    background: var(--card, #fff);
    border: 1px solid var(--border, #e8eaf0);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow .2s, transform .2s;
    position: relative;
  }
  .staff-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,.10); transform: translateY(-2px); }

  /* Image area — intentionally NOT circular, a plain rounded-rect panel */
  .staff-img-wrap {
    width: 100%; height: 152px;
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
  }
  .staff-img-wrap img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
  }
  .staff-img-wrap .bg-icon-p {
    position: absolute; width: 130px; height: 130px;
    opacity: .16; right: -20px; bottom: -22px; color: #fff;
  }
  .staff-initials {
    font-size: 40px; font-weight: 800; color: #fff;
    letter-spacing: .5px; position: relative; z-index: 1;
    text-shadow: 0 2px 12px rgba(0,0,0,.18);
  }
  .staff-card .pill-float { position: absolute; top: 10px; right: 10px; z-index: 2; }

  .staff-body { padding: 14px 16px 2px; display: flex; flex-direction: column; gap: 10px; }
  .staff-name-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
  .staff-name { font-size: 14.5px; font-weight: 700; color: var(--text, #111); line-height: 1.3; }
  .staff-id-chip {
    font-size: 10px; font-weight: 800; color: var(--primary, #5c6bc0);
    background: #eef0fb; padding: 3px 8px; border-radius: 6px;
    white-space: nowrap; flex-shrink: 0; letter-spacing: .2px;
  }
  [data-theme="dark"] .staff-id-chip { background: #1a1f38; color: #818cf8; }

  .staff-info-col { display: flex; flex-direction: column; gap: 7px; }
  .staff-info-row { display: flex; align-items: center; gap: 7px; font-size: 12px; color: var(--muted, #888); min-width: 0; }
  .staff-info-row svg { width: 13px; height: 13px; flex-shrink: 0; color: var(--subtle, #aaa); }
  .staff-info-row span { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

  .staff-foot { padding: 14px 16px 16px; display: flex; flex-direction: column; gap: 7px; margin-top: auto; }
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

  [data-theme="dark"] .staff-card { background: var(--card,#18191a); border-color: var(--border,#2e3348); }
  [data-theme="dark"] .btn-edit-full { color: var(--text,#e0e4f0); border-color: var(--border,#2e3348); }
  [data-theme="dark"] .btn-edit-full:hover { background: var(--hover,#2a452f); }
  [data-theme="dark"] .btn-delete-full { border-color: #5f1e1e; }
  [data-theme="dark"] .btn-delete-full:hover { background: #2a1414; border-color: var(--red,#e53e3e); }

  /* Avatar background variety */
  .bg-staff-1 { background: linear-gradient(135deg,#3b82f6,#1d4ed8); }
  .bg-staff-2 { background: linear-gradient(135deg,#10b981,#047857); }
  .bg-staff-3 { background: linear-gradient(135deg,#f59e0b,#b45309); }
  .bg-staff-4 { background: linear-gradient(135deg,#ec4899,#be185d); }
  .bg-staff-5 { background: linear-gradient(135deg,#8b5cf6,#6d28d9); }
  .bg-staff-6 { background: linear-gradient(135deg,#06b6d4,#0e7490); }

  /* pill danger fallback (in case not defined in shared.css) */
  .pill.danger { color: var(--red, #e53e3e); background: #fff0f0; }
  [data-theme="dark"] .pill.danger { background: #2a1414; }

  .card-head { display:flex; align-items:center; justify-content:space-between; padding:16px 20px 12px; flex-wrap:wrap; gap:10px; }
  .card-head h3 { font-size:15px; font-weight:700; color:var(--text); margin:0; }
  .card-head-right { display:flex; align-items:center; gap:8px; }

  /* ═══════════════════════════════════════════════════════
     MODAL — Landscape on PC (≥700px) / Portrait on Mobile
     ═══════════════════════════════════════════════════════ */
  #staffModalBg .modal, #staffEditModalBg .modal {
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
  .modal-landscape { display: flex; flex: 1; overflow: hidden; }

  @media (min-width: 700px) {
    #staffModalBg .modal, #staffEditModalBg .modal { max-width: 780px; width: 90vw; }
    .modal-img-panel {
      width: 210px; min-width: 210px;
      flex-direction: column;
      justify-content: center;
      border-right: 1px solid var(--border, #e8eaf0);
      padding: 28px 20px;
      gap: 22px;
    }
    .modal-fields-panel { padding: 20px 24px 0; max-height: 62vh; }
    .modal-foot-bar { padding: 14px 24px; }
  }

  @media (max-width: 699px) {
    #staffModalBg .modal, #staffEditModalBg .modal { max-width: 98vw; width: 98vw; }
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

  .modal-img-panel { display: flex; background: var(--hover, #f8f9fc); }
  .modal-fields-panel { flex: 1; overflow-y: auto; }
  .modal-foot-bar {
    border-top: 1px solid var(--border, #e8eaf0);
    display: flex; justify-content: flex-end; gap: 10px;
    background: var(--card, #fff);
    flex-shrink: 0;
  }

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
  .img-upload-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
  .upload-ico { width: 30px; height: 30px; color: var(--subtle, #aaa); }
  .upload-hint { font-size: 10px; color: var(--muted, #888); text-align: center; line-height: 1.5; padding: 0 6px; }
  #sImgPreview, #eImgPreview {
    position: absolute; inset: 0; width: 100%; height: 100%;
    object-fit: cover; border-radius: 12px; display: none;
  }
  .panel-label { font-size: 10px; font-weight: 700; color: var(--subtle, #aaa); letter-spacing: .6px; text-transform: uppercase; }
  .staff-id-badge {
    font-size: 12px; font-weight: 800;
    color: var(--primary, #5c6bc0);
    background: #eef0fb; border-radius: 8px;
    padding: 5px 12px; letter-spacing: .4px;
  }
  .id-block { display: flex; flex-direction: column; align-items: center; gap: 5px; text-align: center; }
  @media (max-width: 699px) { .id-block { align-items: flex-start; } }

  [data-theme="dark"] .modal-img-panel { background: #242526; border-color: var(--border, #000000); }
  [data-theme="dark"] .img-upload-zone { background: #3a3b3c; border-color: #000000; }
  [data-theme="dark"] .img-upload-zone:hover { background: #203a20; border-color: var(--primary, #2b4e30); }
  [data-theme="dark"] .img-upload-zone .upload-ico { color: var(--subtle); }
  [data-theme="dark"] .img-upload-zone .upload-hint { color: var(--subtle); }
  [data-theme="dark"] #sImgPreview, [data-theme="dark"] #eImgPreview { border: 1.5px solid var(--border, #2e3348); border-radius: 12px; }
  [data-theme="dark"] .staff-id-badge { background: #1a1f38; color: #818cf8; }
  [data-theme="dark"] .modal-head-bar { border-color: var(--border, #2e3348); }
  [data-theme="dark"] .modal-foot-bar { background: var(--card, #242526); border-color: var(--border, #2e3348); }

  .edit-modal-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #eef0fb; color: var(--primary, #5c6bc0);
    border-radius: 7px; padding: 3px 10px;
    font-size: 11px; font-weight: 700; letter-spacing: .3px;
    margin-top: 4px;
  }
  .edit-modal-badge svg { width: 11px; height: 11px; }
  [data-theme="dark"] .edit-modal-badge { background: #1a1f38; color: #818cf8; }

  /* Password field with show/hide toggle */
  .pw-wrap { position: relative; }
  .pw-wrap input { padding-right: 38px !important; }
  .pw-toggle {
    position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
    width: 22px; height: 22px; border: none; background: transparent;
    color: var(--subtle, #aaa); cursor: pointer; padding: 0;
    display: flex; align-items: center; justify-content: center;
  }
  .pw-toggle:hover { color: var(--muted, #888); }
  .pw-toggle svg { width: 16px; height: 16px; }
  .field-hint { font-size: 10.5px; color: var(--subtle, #aaa); margin-top: 4px; }

  /* ── Delete Confirm Modal ── */
  #staffDeleteModalBg { z-index: 1100; }
  #staffDeleteModalBg .modal {
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

  /* ── Requests button badge ── */
  .req-badge {
    position: absolute; top: -7px; right: -7px;
    background: var(--red, #e53e3e); color: #fff;
    font-size: 10px; font-weight: 800; line-height: 1;
    min-width: 17px; height: 17px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    padding: 0 4px; border: 2px solid var(--card, #fff);
  }
  [data-theme="dark"] .req-badge { border-color: var(--card, #18191a); }

  /* ── Requests Modal ── */
  #staffRequestsModalBg .modal {
    max-width: 640px; width: 92vw;
    padding: 0; overflow: hidden;
    display: flex; flex-direction: column;
  }
  #requestsList {
    padding: 14px 20px 20px;
    overflow-y: auto; max-height: 62vh;
    display: flex; flex-direction: column; gap: 10px;
  }
  .request-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 14px;
    border: 1px solid var(--border, #e8eaf0); border-radius: 12px;
    background: var(--card, #fff);
    transition: opacity .25s, transform .25s;
  }
  .request-item.removing { opacity: 0; transform: scale(.92); }
  .request-avatar {
    width: 42px; height: 42px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 800; color: #fff;
  }
  .request-info { flex: 1; min-width: 0; }
  .request-name { font-size: 13.5px; font-weight: 700; color: var(--text, #111); }
  .request-meta {
    font-size: 11.5px; color: var(--muted, #888); margin-top: 2px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .request-date { font-size: 10.5px; color: var(--subtle, #aaa); margin-top: 3px; }
  .request-actions { display: flex; gap: 6px; flex-shrink: 0; }
  .btn-approve, .btn-reject {
    padding: 7px 12px; border-radius: 8px; background: transparent;
    font-size: 12px; font-weight: 700; cursor: pointer;
    display: flex; align-items: center; gap: 4px;
    transition: background .15s, border-color .15s;
  }
  .btn-approve { border: 1px solid #bbf7d0; color: #16a34a; }
  .btn-approve:hover { background: #f0fdf4; }
  .btn-reject { border: 1px solid #fecaca; color: var(--red, #e53e3e); }
  .btn-reject:hover { background: #fff5f5; }
  [data-theme="dark"] .request-item { background: var(--card, #18191a); border-color: var(--border, #2e3348); }
  [data-theme="dark"] .btn-approve { border-color: #14532d; }
  [data-theme="dark"] .btn-approve:hover { background: #0f2a1a; }
  [data-theme="dark"] .btn-reject { border-color: #5f1e1e; }
  [data-theme="dark"] .btn-reject:hover { background: #2a1414; }
  .requests-empty {
    text-align: center; padding: 46px 20px;
    color: var(--muted, #888); font-size: 13px;
  }
  .requests-empty svg { display: block; margin: 0 auto 10px; opacity: .45; }

  @media (max-width: 480px) {
    .request-item { flex-wrap: wrap; }
    .request-actions { width: 100%; justify-content: flex-end; margin-top: 4px; }
  }

  /* ── Card remove animation ── */
  .staff-card.removing { animation: cardRemove .35s ease forwards; }
  @keyframes cardRemove {
    0%   { opacity: 1; transform: scale(1); }
    60%  { opacity: 0; transform: scale(.88); }
    100% { opacity: 0; transform: scale(.88); max-height: 0; margin: 0; padding: 0; border: none; }
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
  <a class="nav-item" href="{{ route('admin.inventory') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
    Inventory<span class="nav-badge">3</span>
  </a>
  <a class="nav-item" href="{{ route('admin.orders') }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    Orders
  </a>
  <a class="nav-item active" href="{{ route('admin.staffs') }}">
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

<!-- Header -->
<header class="header">
  <button class="hamburger" id="hamburger" aria-label="Toggle sidebar">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="header-title">Staffs</div>
  <div class="header-search">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" placeholder="Search products, staffs…">
  </div>
  <div class="header-actions">
    <button class="icon-btn" title="Notifications">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      <div class="dot"></div>
    </button>
    <button class="icon-btn" id="themeBtn" title="Toggle dark mode"><span id="themeIco"></span></button>
  </div>
</header>

<!-- Main -->
<main class="main">
  <div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div>
      <div style="font-size:20px;font-weight:800;color:var(--text);">Staff Management</div>
      <div style="font-size:13px;color:var(--muted);margin-top:3px;">Manage all pharmacy staff accounts and access.</div>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
      <button class="btn" style="position:relative;" onclick="openRequestsModal()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:2px;vertical-align:-2px;"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="17" y1="11" x2="23" y2="11"/></svg>
        Requests
        <span class="req-badge" id="reqBadgeCount">0</span>
      </button>
      <button class="btn primary" onclick="openStaffModal()">+ Add Staff</button>
    </div>
  </div>

  <div class="card" style="padding-bottom:20px;">
    <div class="card-head">
      <h3>All Staff</h3>
      <div class="card-head-right">
        <div class="header-search" style="height:32px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;color:var(--subtle);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="staffSearch" placeholder="Search…" style="width:150px;" oninput="filterStaffCards(this.value)">
        </div>
      </div>
    </div>
    <br>
    <div class="staff-grid" id="staffGrid" style="padding:0 16px;">

      @forelse ($staffs as $index => $staff)
        @php
          $avatarClasses = ['bg-staff-1','bg-staff-2','bg-staff-3','bg-staff-4','bg-staff-5','bg-staff-6'];
          $avatarClass   = $avatarClasses[$index % count($avatarClasses)];
          $status        = $staff->is_active ? 'Active' : 'Inactive';
          $pillClass     = $staff->is_active ? 'ok' : 'danger';
          $staffCode     = 'STF-' . str_pad($staff->id, 4, '0', STR_PAD_LEFT);
          $initials      = strtoupper(substr($staff->first_name, 0, 1) . substr($staff->last_name, 0, 1)) ?: '?';
          $image         = $staff->profile_picture ? asset($staff->profile_picture) : '';
          $searchStr     = strtolower($staff->first_name . ' ' . $staff->last_name . ' ' . $staff->email . ' ' . $staffCode);
        @endphp
        <!-- {{ $staff->first_name }} {{ $staff->last_name }} -->
        <div class="staff-card"
             data-id="{{ $staffCode }}"
             data-db-id="{{ $staff->id }}"
             data-first-name="{{ $staff->first_name }}"
             data-last-name="{{ $staff->last_name }}"
             data-email="{{ $staff->email }}"
             data-contact="{{ $staff->contact_number }}"
             data-address="{{ $staff->address }}"
             data-image="{{ $image }}"
             data-password=""
             data-status="{{ $status }}"
             data-avatar-class="{{ $avatarClass }}"
             data-search="{{ $searchStr }}">
          <div class="staff-img-wrap {{ $image ? '' : $avatarClass }}">
            <span class="pill-float pill {{ $pillClass }}">{{ $status }}</span>
            @if ($image)
              <img src="{{ $image }}" alt="{{ $staff->first_name }} {{ $staff->last_name }}">
            @else
              <svg class="bg-icon-p" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/></svg>
              <div class="staff-initials">{{ $initials }}</div>
            @endif
          </div>
          <div class="staff-body">
            <div class="staff-name-row">
              <div class="staff-name">{{ $staff->first_name }} {{ $staff->last_name }}</div>
              <span class="staff-id-chip">{{ $staffCode }}</span>
            </div>
            <div class="staff-info-col">
              <div class="staff-info-row" title="{{ $staff->email }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <span>{{ $staff->email }}</span>
              </div>
              <div class="staff-info-row">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <span>{{ $staff->contact_number ?: '—' }}</span>
              </div>
              <div class="staff-info-row" title="{{ $staff->address }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>{{ $staff->address ?: '—' }}</span>
              </div>
            </div>
          </div>
          <div class="staff-foot">
            <button class="btn-edit-full" onclick="openEditStaffModal(this)">Edit</button>
            <button class="btn-delete-full" onclick="openDeleteStaffModal(this)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
              Delete
            </button>
          </div>
        </div>
      @empty
        <div style="padding:40px 16px;text-align:center;color:var(--muted);grid-column:1/-1;">
          No staff records found.
        </div>
      @endforelse

    </div><!-- /staff-grid -->
  </div><!-- /card -->
</main>

<!-- ════════════════════════════════════════════════════
     ADD STAFF MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="staffModalBg">
  <div class="modal">
    <div class="modal-head-bar">
      <div>
        <div style="font-size:15px;font-weight:700;color:var(--text);">Add New Staff</div>
        <div style="font-size:11px;color:var(--muted);margin-top:2px;">Fill in the staff account details</div>
      </div>
      <button class="modal-close" onclick="closeStaffModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-landscape">
      <div class="modal-img-panel">
        <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
          <div class="panel-label">PROFILE PICTURE</div>
          <div class="img-upload-zone">
            <input type="file" accept="image/*" id="sImage" onchange="previewImg(this,'sImgPreview')">
            <svg class="upload-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="4"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <polyline points="21 15 16 10 5 21"/>
            </svg>
            <span class="upload-hint">Click to upload<br>PNG · JPG · WEBP</span>
            <img id="sImgPreview" alt="Preview">
          </div>
        </div>
        <div class="id-block">
          <div class="panel-label">STAFF ID</div>
          <div class="staff-id-badge" id="autoStaffId">STF-0001</div>
          <div style="font-size:10px;color:var(--subtle);">Auto-generated · PK</div>
        </div>
      </div>
      <div style="display:flex;flex-direction:column;flex:1;min-width:0;overflow:hidden;">
        <div class="modal-fields-panel">
          <div class="row-2">
            <div class="field">
              <label>First Name</label>
              <input type="text" placeholder="e.g. Juan" id="sFirstName">
            </div>
            <div class="field">
              <label>Last Name</label>
              <input type="text" placeholder="e.g. Dela Cruz" id="sLastName">
            </div>
          </div>
          <div class="field">
            <label>Email</label>
            <input type="email" placeholder="e.g. juan.delacruz@ggpharmacy.com" id="sEmail">
          </div>
          <div class="row-2">
            <div class="field">
              <label>Contact Number</label>
              <input type="text" placeholder="e.g. +63 917 234 5678" id="sContact">
            </div>
            <div class="field">
              <label>Status</label>
              <select id="sStatus">
                <option>Active</option>
                <option>Inactive</option>
                <option>On Leave</option>
              </select>
            </div>
          </div>
          <div class="field">
            <label>Address</label>
            <textarea placeholder="House/Unit No., Street, City, Province" id="sAddress" style="min-height:60px;resize:vertical;"></textarea>
          </div>
          <div class="field">
            <label>Password</label>
            <div class="pw-wrap">
              <input type="password" placeholder="Set a login password" id="sPassword">
              <button type="button" class="pw-toggle" onclick="togglePw('sPassword', this)" title="Show/Hide password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <div class="field-hint">Minimum 8 characters. Staff will use this to log in.</div>
          </div>
        </div>
        <div class="modal-foot-bar">
          <button class="btn" onclick="closeStaffModal()">Cancel</button>
          <button class="btn primary" onclick="saveStaff()">Save Staff</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════
     EDIT STAFF MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="staffEditModalBg">
  <div class="modal">
    <div class="modal-head-bar">
      <div>
        <div style="font-size:15px;font-weight:700;color:var(--text);">Edit Staff</div>
        <div class="edit-modal-badge" id="editStaffIdBadge">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:11px;height:11px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          STF-0000
        </div>
      </div>
      <button class="modal-close" onclick="closeEditStaffModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div class="modal-landscape">
      <div class="modal-img-panel">
        <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
          <div class="panel-label">PROFILE PICTURE</div>
          <div class="img-upload-zone">
            <input type="file" accept="image/*" id="eImage" onchange="previewImg(this,'eImgPreview')">
            <svg class="upload-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="4"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <polyline points="21 15 16 10 5 21"/>
            </svg>
            <span class="upload-hint">Click to upload<br>PNG · JPG · WEBP</span>
            <img id="eImgPreview" alt="Preview">
          </div>
        </div>
      </div>
      <div style="display:flex;flex-direction:column;flex:1;min-width:0;overflow:hidden;">
        <div class="modal-fields-panel">
          <div class="row-2">
            <div class="field">
              <label>First Name</label>
              <input type="text" placeholder="e.g. Juan" id="eFirstName">
            </div>
            <div class="field">
              <label>Last Name</label>
              <input type="text" placeholder="e.g. Dela Cruz" id="eLastName">
            </div>
          </div>
          <div class="field">
            <label>Email</label>
            <input type="email" placeholder="e.g. juan.delacruz@ggpharmacy.com" id="eEmail">
          </div>
          <div class="row-2">
            <div class="field">
              <label>Contact Number</label>
              <input type="text" placeholder="e.g. +63 917 234 5678" id="eContact">
            </div>
            <div class="field">
              <label>Status</label>
              <select id="eStatus">
                <option>Active</option>
                <option>Inactive</option>
                <option>On Leave</option>
              </select>
            </div>
          </div>
          <div class="field">
            <label>Address</label>
            <textarea placeholder="House/Unit No., Street, City, Province" id="eAddress" style="min-height:60px;resize:vertical;"></textarea>
          </div>
          <div class="field">
            <label>New Password <span style="font-size:10px;font-weight:400;text-transform:none;color:var(--subtle);">(optional)</span></label>
            <div class="pw-wrap">
              <input type="password" placeholder="Leave blank to keep current password" id="ePassword">
              <button type="button" class="pw-toggle" onclick="togglePw('ePassword', this)" title="Show/Hide password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>
        </div>
        <div class="modal-foot-bar">
          <button class="btn" onclick="closeEditStaffModal()">Cancel</button>
          <button class="btn primary" onclick="saveStaffEdit()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════
     DELETE CONFIRM MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="staffDeleteModalBg">
  <div class="modal">
    <div class="delete-ico-wrap">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="3 6 5 6 21 6"/>
        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
        <path d="M10 11v6M14 11v6"/>
        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
      </svg>
    </div>
    <div class="delete-modal-title">Delete Staff?</div>
    <div class="delete-modal-sub">
      You're about to permanently remove<br>
      <strong id="deleteStaffTargetName">—</strong> from staff records.<br>
      This action cannot be undone.
    </div>
    <div class="delete-modal-actions">
      <button class="btn" onclick="closeDeleteStaffModal()">Cancel</button>
      <button class="btn-danger" onclick="confirmDeleteStaff()">Yes, Delete</button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════
     STAFF REGISTRATION REQUESTS MODAL
     ════════════════════════════════════════════════════ -->
<div class="modal-bg" id="staffRequestsModalBg">
  <div class="modal">
    <div class="modal-head-bar">
      <div>
        <div style="font-size:15px;font-weight:700;color:var(--text);">Staff Registration Requests</div>
        <div style="font-size:12px;color:var(--muted);margin-top:2px;">Review and approve or reject new staff sign-ups.</div>
      </div>
      <button class="modal-close" onclick="closeRequestsModal()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div id="requestsList"></div>
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
/* ── Avatar helpers ──────────────────────────────────── */
const AVATAR_CLASSES = ['bg-staff-1','bg-staff-2','bg-staff-3','bg-staff-4','bg-staff-5','bg-staff-6'];
const STAFF_BASE_URL = "{{ url('/admin/staffs') }}";
function initialsOf(first, last) {
  return ((first || '').charAt(0) + (last || '').charAt(0)).toUpperCase() || '?';
}
function pillClassForStatus(status) {
  if (status === 'Active') return 'ok';
  if (status === 'On Leave') return 'warning';
  return 'danger';
}

/* ── Add Staff modal ──────────────────────────────────── */
function openStaffModal() {
  document.getElementById('autoStaffId').textContent = 'STF-' + (Math.floor(Math.random() * 9000) + 1000);
  document.getElementById('staffModalBg').classList.add('on');
}
function closeStaffModal() { document.getElementById('staffModalBg').classList.remove('on'); }
document.getElementById('staffModalBg').addEventListener('click', e => { if (e.target === e.currentTarget) closeStaffModal(); });

/* ── Image preview (shared) ──────────────────────────── */
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

/* ── Password show/hide ──────────────────────────────── */
function togglePw(inputId, btn) {
  const input = document.getElementById(inputId);
  const showing = input.type === 'text';
  input.type = showing ? 'password' : 'text';
  btn.innerHTML = showing
    ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>'
    : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.7 18.7 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
}

/* ── Toast helper ─────────────────────────────────────── */
function showToast(msg) {
  document.getElementById('toastMsg').textContent = msg;
  const toast = document.getElementById('toast');
  toast.classList.add('on');
  setTimeout(() => toast.classList.remove('on'), 3200);
}

/* ── Save (Add) staff ─────────────────────────────────── */
function saveStaff() {
  const firstEl = document.getElementById('sFirstName');
  const lastEl  = document.getElementById('sLastName');
  const emailEl = document.getElementById('sEmail');
  const first = firstEl.value.trim();
  const last  = lastEl.value.trim();
  const email = emailEl.value.trim();

  [firstEl, lastEl, emailEl].forEach(el => el.style.borderColor = '');
  if (!first) { firstEl.style.borderColor = 'var(--red)'; firstEl.focus(); return; }
  if (!last)  { lastEl.style.borderColor  = 'var(--red)'; lastEl.focus();  return; }
  if (!email) { emailEl.style.borderColor = 'var(--red)'; emailEl.focus(); return; }

  const contactEl  = document.getElementById('sContact');
  const addressEl  = document.getElementById('sAddress');
  const passwordEl = document.getElementById('sPassword');
  const statusEl   = document.getElementById('sStatus');
  const fileEl     = document.getElementById('sImage');

  const password = passwordEl.value;
  if (!password || password.length < 8) {
    passwordEl.style.borderColor = 'var(--red)';
    passwordEl.focus();
    return;
  }
  passwordEl.style.borderColor = '';

  const saveBtn = document.querySelector('#staffModalBg .btn.primary');
  const originalLabel = saveBtn.textContent;
  saveBtn.disabled = true;
  saveBtn.textContent = 'Saving…';

  const formData = new FormData();
  formData.append('first_name', first);
  formData.append('last_name', last);
  formData.append('email', email);
  formData.append('contact_number', contactEl.value.trim());
  formData.append('address', addressEl.value.trim());
  formData.append('password', password);
  formData.append('is_active', statusEl.value === 'Active' ? '1' : '0');
  if (fileEl.files && fileEl.files[0]) {
    formData.append('profile_picture', fileEl.files[0]);
  }

  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  fetch('{{ route('admin.staffs.store') }}', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json',
    },
    body: formData,
  })
    .then(async response => {
      const body = await response.json().catch(() => ({}));
      if (!response.ok) {
        throw { status: response.status, body };
      }
      return body;
    })
    .then(body => {
      closeStaffModal();
      resetAddStaffForm();
      sessionStorage.setItem('staffAddedMessage', body.message || `Staff "${first} ${last}" added.`);
      window.location.reload();
    })
    .catch(err => {
      if (err && err.status === 422 && err.body && err.body.errors) {
        const errors = err.body.errors;
        const fieldMap = { first_name: firstEl, last_name: lastEl, email: emailEl, password: passwordEl };
        let firstInvalid = null;
        Object.keys(errors).forEach(key => {
          const el = fieldMap[key];
          if (el) {
            el.style.borderColor = 'var(--red)';
            if (!firstInvalid) firstInvalid = el;
          }
        });
        const firstMessage = Object.values(errors)[0]?.[0] || 'Please check the form and try again.';
        showToast(firstMessage);
        if (firstInvalid) firstInvalid.focus();
      } else {
        showToast('Something went wrong while adding the staff member. Please try again.');
      }
    })
    .finally(() => {
      saveBtn.disabled = false;
      saveBtn.textContent = originalLabel;
    });
}

function resetAddStaffForm() {
  ['sFirstName','sLastName','sEmail','sContact','sAddress','sPassword'].forEach(id => {
    const el = document.getElementById(id);
    el.value = '';
    el.style.borderColor = '';
  });
  document.getElementById('sStatus').selectedIndex = 0;
  const fileInput = document.getElementById('sImage');
  fileInput.value = '';
  const preview = document.getElementById('sImgPreview');
  preview.style.display = 'none';
  const zone = fileInput.closest('.img-upload-zone');
  zone.querySelector('.upload-ico').style.display  = '';
  zone.querySelector('.upload-hint').style.display = '';
}

function buildStaffCard(d) {
  const wrap = document.createElement('div');
  wrap.className = 'staff-card';
  wrap.dataset.id = d.id;
  wrap.dataset.dbId = d.dbId || '';
  wrap.dataset.firstName = d.first;
  wrap.dataset.lastName = d.last;
  wrap.dataset.email = d.email;
  wrap.dataset.contact = d.contact;
  wrap.dataset.address = d.address;
  wrap.dataset.image = d.image || '';
  wrap.dataset.password = d.password || '';
  wrap.dataset.status = d.status;
  wrap.dataset.avatarClass = d.avatarClass;
  wrap.dataset.search = (d.first + ' ' + d.last + ' ' + d.email + ' ' + d.id).toLowerCase();

  const pillClass = pillClassForStatus(d.status);
  const initials = initialsOf(d.first, d.last);
  const imgInner = d.image
    ? `<img src="${d.image}" alt="${d.first} ${d.last}">`
    : `<svg class="bg-icon-p" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/></svg><div class="staff-initials">${initials}</div>`;

  wrap.innerHTML = `
    <div class="staff-img-wrap ${d.image ? '' : d.avatarClass}">
      <span class="pill-float pill ${pillClass}">${d.status}</span>
      ${imgInner}
    </div>
    <div class="staff-body">
      <div class="staff-name-row">
        <div class="staff-name">${d.first} ${d.last}</div>
        <span class="staff-id-chip">${d.id}</span>
      </div>
      <div class="staff-info-col">
        <div class="staff-info-row" title="${d.email}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <span>${d.email}</span>
        </div>
        <div class="staff-info-row">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <span>${d.contact || '—'}</span>
        </div>
        <div class="staff-info-row" title="${d.address}">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <span>${d.address || '—'}</span>
        </div>
      </div>
    </div>
    <div class="staff-foot">
      <button class="btn-edit-full" onclick="openEditStaffModal(this)">Edit</button>
      <button class="btn-delete-full" onclick="openDeleteStaffModal(this)">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
        Delete
      </button>
    </div>
  `;
  return wrap;
}

/* ══════════════════════════════════════════════════════
   EDIT MODAL
   ══════════════════════════════════════════════════════ */
let _editStaffTarget = null;

function openEditStaffModal(btn) {
  const card = btn.closest('.staff-card');
  _editStaffTarget = card;

  const id = card.dataset.id || 'STF-????';
  document.getElementById('editStaffIdBadge').innerHTML =
    `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:11px;height:11px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> ${id}`;

  document.getElementById('eFirstName').value = card.dataset.firstName || '';
  document.getElementById('eLastName').value  = card.dataset.lastName || '';
  document.getElementById('eEmail').value     = card.dataset.email || '';
  document.getElementById('eContact').value   = card.dataset.contact || '';
  document.getElementById('eAddress').value   = card.dataset.address || '';
  document.getElementById('eStatus').value    = card.dataset.status || 'Active';
  document.getElementById('ePassword').value  = '';

  const ePreview = document.getElementById('eImgPreview');
  const eZone = document.getElementById('eImage').closest('.img-upload-zone');
  if (card.dataset.image) {
    ePreview.src = card.dataset.image;
    ePreview.style.display = 'block';
    eZone.querySelector('.upload-ico').style.display  = 'none';
    eZone.querySelector('.upload-hint').style.display = 'none';
  } else {
    ePreview.style.display = 'none';
    ePreview.src = '';
    eZone.querySelector('.upload-ico').style.display  = '';
    eZone.querySelector('.upload-hint').style.display = '';
  }
  document.getElementById('eImage').value = '';

  ['eFirstName','eLastName','eEmail'].forEach(id => document.getElementById(id).style.borderColor = '');
  document.getElementById('staffEditModalBg').classList.add('on');
}

function closeEditStaffModal() {
  document.getElementById('staffEditModalBg').classList.remove('on');
  _editStaffTarget = null;
}
document.getElementById('staffEditModalBg').addEventListener('click', e => { if (e.target === e.currentTarget) closeEditStaffModal(); });

function saveStaffEdit() {
  const firstEl = document.getElementById('eFirstName');
  const lastEl  = document.getElementById('eLastName');
  const emailEl = document.getElementById('eEmail');
  const first = firstEl.value.trim();
  const last  = lastEl.value.trim();
  const email = emailEl.value.trim();

  [firstEl, lastEl, emailEl].forEach(el => el.style.borderColor = '');
  if (!first) { firstEl.style.borderColor = 'var(--red)'; firstEl.focus(); return; }
  if (!last)  { lastEl.style.borderColor  = 'var(--red)'; lastEl.focus();  return; }
  if (!email) { emailEl.style.borderColor = 'var(--red)'; emailEl.focus(); return; }

  if (!_editStaffTarget) return;
  const card = _editStaffTarget;
  const dbId = card.dataset.dbId;

  if (!dbId) {
    // Card was created client-side only (e.g. from an approved request)
    // and has no database record yet — nothing to persist.
    showToast('This staff record cannot be edited yet.');
    return;
  }

  const contactEl  = document.getElementById('eContact');
  const addressEl  = document.getElementById('eAddress');
  const statusEl   = document.getElementById('eStatus');
  const passwordEl = document.getElementById('ePassword');
  const fileEl     = document.getElementById('eImage');

  const status = statusEl.value;
  const newPw  = passwordEl.value;

  if (newPw && newPw.length < 8) {
    passwordEl.style.borderColor = 'var(--red)';
    passwordEl.focus();
    return;
  }
  passwordEl.style.borderColor = '';

  const saveBtn = document.querySelector('#staffEditModalBg .btn.primary');
  const originalLabel = saveBtn.textContent;
  saveBtn.disabled = true;
  saveBtn.textContent = 'Saving…';

  const formData = new FormData();
  formData.append('_method', 'PUT');
  formData.append('first_name', first);
  formData.append('last_name', last);
  formData.append('email', email);
  formData.append('contact_number', contactEl.value.trim());
  formData.append('address', addressEl.value.trim());
  formData.append('is_active', status === 'Active' ? '1' : '0');
  if (newPw) {
    formData.append('password', newPw);
  }
  if (fileEl.files && fileEl.files[0]) {
    formData.append('profile_picture', fileEl.files[0]);
  }

  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  fetch(`${STAFF_BASE_URL}/${dbId}`, {
    method: 'POST', // spoofed to PUT via _method field (required for multipart uploads)
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json',
    },
    body: formData,
  })
    .then(async response => {
      const body = await response.json().catch(() => ({}));
      if (!response.ok) {
        throw { status: response.status, body };
      }
      return body;
    })
    .then(body => {
      closeEditStaffModal();
      sessionStorage.setItem('staffAddedMessage', body.message || `"${first} ${last}" updated successfully.`);
      window.location.reload();
    })
    .catch(err => {
      if (err && err.status === 422 && err.body && err.body.errors) {
        const errors = err.body.errors;
        const fieldMap = { first_name: firstEl, last_name: lastEl, email: emailEl, password: passwordEl };
        let firstInvalid = null;
        Object.keys(errors).forEach(key => {
          const el = fieldMap[key];
          if (el) {
            el.style.borderColor = 'var(--red)';
            if (!firstInvalid) firstInvalid = el;
          }
        });
        const firstMessage = Object.values(errors)[0]?.[0] || 'Please check the form and try again.';
        showToast(firstMessage);
        if (firstInvalid) firstInvalid.focus();
      } else {
        showToast('Something went wrong while updating the staff member. Please try again.');
      }
    })
    .finally(() => {
      saveBtn.disabled = false;
      saveBtn.textContent = originalLabel;
    });
}

/* ══════════════════════════════════════════════════════
   DELETE MODAL
   ══════════════════════════════════════════════════════ */
let _deleteStaffTarget = null;

function openDeleteStaffModal(btn) {
  const card = btn.closest('.staff-card');
  _deleteStaffTarget = card;
  const name = `${card.dataset.firstName || ''} ${card.dataset.lastName || ''}`.trim() || 'this staff member';
  document.getElementById('deleteStaffTargetName').textContent = name;
  document.getElementById('staffDeleteModalBg').classList.add('on');
}

function closeDeleteStaffModal() {
  document.getElementById('staffDeleteModalBg').classList.remove('on');
  _deleteStaffTarget = null;
}
document.getElementById('staffDeleteModalBg').addEventListener('click', e => { if (e.target === e.currentTarget) closeDeleteStaffModal(); });

function confirmDeleteStaff() {
  if (!_deleteStaffTarget) return;
  const card = _deleteStaffTarget;
  const dbId = card.dataset.dbId;
  const name = `${card.dataset.firstName || ''} ${card.dataset.lastName || ''}`.trim() || 'Staff';
  closeDeleteStaffModal();

  if (!dbId) {
    // No database record to delete (client-side-only card) — just remove it.
    card.classList.add('removing');
    setTimeout(() => {
      card.remove();
      showToast(`"${name}" removed from staff records.`);
    }, 360);
    return;
  }

  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  fetch(`${STAFF_BASE_URL}/${dbId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json',
    },
  })
    .then(async response => {
      const body = await response.json().catch(() => ({}));
      if (!response.ok) {
        throw { status: response.status, body };
      }
      return body;
    })
    .then(body => {
      card.classList.add('removing');
      setTimeout(() => {
        card.remove();
        showToast(body.message || `"${name}" removed from staff records.`);
      }, 360);
    })
    .catch(() => {
      showToast('Something went wrong while deleting the staff member. Please try again.');
    });
}

/* ══════════════════════════════════════════════════════
   STAFF REGISTRATION REQUESTS
   ══════════════════════════════════════════════════════ */
let staffRequests = [
  {
    id: 'REQ-2001',
    first: 'Angelo', last: 'Reyes',
    email: 'angelo.reyes@gmail.com',
    contact: '+63 920 111 2222',
    address: 'Talamban, Cebu City, Cebu',
    submitted: 'Aug 20, 2026',
    avatarClass: 'bg-staff-3'
  },
  {
    id: 'REQ-2002',
    first: 'Liza', last: 'Cortez',
    email: 'liza.cortez@yahoo.com',
    contact: '+63 933 444 5566',
    address: 'Lahug, Cebu City, Cebu',
    submitted: 'Aug 21, 2026',
    avatarClass: 'bg-staff-5'
  },
  {
    id: 'REQ-2003',
    first: 'Miguel', last: 'Torres',
    email: 'miguel.torres@outlook.com',
    contact: '+63 906 777 8899',
    address: 'Mandaue City, Cebu',
    submitted: 'Aug 22, 2026',
    avatarClass: 'bg-staff-6'
  }
];

function updateReqBadge() {
  const badge = document.getElementById('reqBadgeCount');
  if (!badge) return;
  if (staffRequests.length > 0) {
    badge.textContent = staffRequests.length;
    badge.style.display = 'flex';
  } else {
    badge.style.display = 'none';
  }
}

function openRequestsModal() {
  renderRequestsList();
  document.getElementById('staffRequestsModalBg').classList.add('on');
}
function closeRequestsModal() {
  document.getElementById('staffRequestsModalBg').classList.remove('on');
}
document.getElementById('staffRequestsModalBg').addEventListener('click', e => { if (e.target === e.currentTarget) closeRequestsModal(); });

function renderRequestsList() {
  const list = document.getElementById('requestsList');
  if (staffRequests.length === 0) {
    list.innerHTML = `
      <div class="requests-empty">
        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        <div>No pending registration requests.</div>
      </div>`;
    updateReqBadge();
    return;
  }
  list.innerHTML = staffRequests.map(r => `
    <div class="request-item" data-req-id="${r.id}">
      <div class="request-avatar ${r.avatarClass}">${initialsOf(r.first, r.last)}</div>
      <div class="request-info">
        <div class="request-name">${r.first} ${r.last}</div>
        <div class="request-meta" title="${r.email} · ${r.contact}">${r.email} · ${r.contact}</div>
        <div class="request-meta" title="${r.address}">· ${r.address || '—'}</div>
        <div class="request-date">Submitted ${r.submitted}</div>
      </div>
      <div class="request-actions">
        <button class="btn-approve" onclick="approveRequest('${r.id}')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          Approve
        </button>
        <button class="btn-reject" onclick="rejectRequest('${r.id}')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          Reject
        </button>
      </div>
    </div>
  `).join('');
  updateReqBadge();
}

function removeRequestFromList(id, cb) {
  const item = document.querySelector(`.request-item[data-req-id="${id}"]`);
  if (item) {
    item.classList.add('removing');
    setTimeout(cb, 220);
  } else {
    cb();
  }
}

function approveRequest(id) {
  const idx = staffRequests.findIndex(r => r.id === id);
  if (idx === -1) return;
  const r = staffRequests[idx];

  removeRequestFromList(id, () => {
    staffRequests.splice(staffRequests.findIndex(x => x.id === id), 1);

    const newId = 'STF-' + (Math.floor(Math.random() * 9000) + 1000);
    const card = buildStaffCard({
      id: newId, first: r.first, last: r.last, email: r.email,
      contact: r.contact, address: r.address, password: '', status: 'Active',
      image: '', avatarClass: r.avatarClass
    });
    document.getElementById('staffGrid').appendChild(card);

    renderRequestsList();
    showToast(`"${r.first} ${r.last}" approved and added to staff.`);
  });
}

function rejectRequest(id) {
  const idx = staffRequests.findIndex(r => r.id === id);
  if (idx === -1) return;
  const r = staffRequests[idx];

  removeRequestFromList(id, () => {
    staffRequests.splice(staffRequests.findIndex(x => x.id === id), 1);
    renderRequestsList();
    showToast(`Registration request from "${r.first} ${r.last}" rejected.`);
  });
}

updateReqBadge();

/* ── Show toast for actions that reload the page ───────── */
(function () {
  const msg = sessionStorage.getItem('staffAddedMessage');
  if (msg) {
    sessionStorage.removeItem('staffAddedMessage');
    showToast(msg);
  }
})();

/* ── Card search (queries the staff table on the server) ─ */
let _staffSearchTimer = null;
let _staffSearchSeq = 0;

function filterStaffCards(q) {
  const query = q.trim();
  clearTimeout(_staffSearchTimer);
  _staffSearchTimer = setTimeout(() => searchStaffsFromDatabase(query), 300);
}

function searchStaffsFromDatabase(query) {
  const mySeq = ++_staffSearchSeq;

  fetch(`${STAFF_BASE_URL}/search?q=${encodeURIComponent(query)}`, {
    headers: { 'Accept': 'application/json' },
  })
    .then(async response => {
      if (!response.ok) throw new Error('Search request failed');
      return response.json();
    })
    .then(data => {
      // Ignore stale responses if the user kept typing.
      if (mySeq !== _staffSearchSeq) return;
      renderStaffSearchResults(data.staffs || []);
    })
    .catch(() => {
      if (mySeq !== _staffSearchSeq) return;
      showToast('Something went wrong while searching staff. Please try again.');
    });
}

function renderStaffSearchResults(staffList) {
  const grid = document.getElementById('staffGrid');
  grid.innerHTML = '';

  if (staffList.length === 0) {
    grid.innerHTML = `<div style="padding:40px 16px;text-align:center;color:var(--muted);grid-column:1/-1;">No staff records found.</div>`;
    return;
  }

  staffList.forEach(s => {
    const card = buildStaffCard({
      id: s.staff_code,
      dbId: s.db_id,
      first: s.first_name,
      last: s.last_name,
      email: s.email,
      contact: s.contact,
      address: s.address,
      image: s.image || '',
      password: '',
      status: s.status,
      avatarClass: s.avatar_class,
    });
    grid.appendChild(card);
  });
}
</script>
</body>
</html>