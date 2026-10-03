<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $siteName }} — Promo Banner</title>
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
  .btn:disabled, .btn[disabled]{ opacity:.5; cursor:not-allowed; pointer-events:none; }
  .wc-select option:disabled{ color:var(--wc-subtle); }
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

  /* Promo banner cards (2021 x 528 images) */
  .wc-promo-grid{
    display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:16px; padding:18px 20px;
  }
  .wc-promo-card{
    border:1px solid var(--wc-border); border-radius:var(--wc-radius); overflow:hidden;
    background:var(--wc-bg); display:flex; flex-direction:column; transition:border-color .15s, box-shadow .15s;
  }
  .wc-promo-card:hover{ border-color:var(--wc-primary); box-shadow:0 2px 10px rgba(0,0,0,.06); }
  .wc-promo-card-media{
    position:relative; width:100%; aspect-ratio:2021/528; background:var(--wc-card); overflow:hidden;
  }
  .wc-promo-card-media img{ width:100%; height:100%; object-fit:cover; display:block; }
  .wc-promo-card-media .wc-thumb-fallback{ width:100%; height:100%; border-radius:0; border:none; }
  .wc-promo-card-order{
    position:absolute; top:8px; left:8px; font-size:11px; font-weight:700; color:#fff;
    background:rgba(0,0,0,.55); padding:2px 8px; border-radius:999px; letter-spacing:.2px;
  }
  .wc-promo-card-type{ position:absolute; top:8px; right:8px; }
  .wc-promo-card-body{ padding:12px 14px; display:flex; flex-direction:column; gap:2px; }
  .wc-promo-card-title{ font-size:13px; font-weight:700; color:var(--wc-text); line-height:1.3; }
  .wc-promo-card-meta{ font-size:11.5px; color:var(--wc-subtle); }
  .wc-promo-card-actions{ display:flex; gap:8px; padding:0 14px 14px; margin-top:auto; }
  .wc-promo-card-actions .btn-icon{ flex:1; border-radius:8px; }
  .wc-promo-empty{
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<style>
  /* Crop dialog */
  .wc-crop-overlay{ position:fixed; inset:0; background:rgba(10,12,15,.75); display:none; align-items:center; justify-content:center; z-index:1100; padding:20px; }
  .wc-crop-overlay.open{ display:flex; }
  .wc-crop-modal{ background:var(--wc-card); border:1px solid var(--wc-border); border-radius:16px; width:100%; max-width:820px; max-height:94vh; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,.4); overflow:hidden; }
  .wc-crop-stage{ background:#111; flex:1; min-height:0; height:min(56vh, 460px); }
  .wc-crop-stage img{ display:block; max-width:100%; }
  .wc-crop-info{ padding:12px 22px 0; font-size:12.5px; color:var(--wc-subtle); line-height:1.5; }
  .wc-crop-info strong{ color:var(--wc-text); }
  .wc-crop-warn{ color:#e07b00; font-weight:600; }
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

<div style="margin-bottom:4px;">
  <div style="font-size:20px;font-weight:800;color:var(--text);">Website Customization</div>
</div>
<p class="wc-intro">Control what shoppers see on the storefront — site details, homepage banners, section headings, and the product catalog's brands, categories and sliders. Changes to Settings, Banners, Sections and the Promo Banner update existing content; Brands, Categories and Sliders can be freely added, edited or removed.</p>

<div class="wc-wrap">
  <nav class="wc-tabs">
    <a class="wc-tab" href="{{ route('admin.pharmacy') }}"><i class="fas fa-store"></i> Settings</a>
    <a class="wc-tab" href="{{ route('admin.pharmacy_full_width_banners') }}"><i class="fas fa-image"></i> FW Banners</a>
    <a class="wc-tab" href="{{ route('admin.pharmacy_sections') }}"><i class="fas fa-layer-group"></i> Sections</a>
    <a class="wc-tab active" href="{{ route('admin.pharmacy_promo_banners') }}"><i class="fas fa-bullhorn"></i> Promo Banner</a>
    <a class="wc-tab" href="{{ route('admin.pharmacy_sliders') }}"><i class="fas fa-images"></i> Sliders</a>
    <a class="wc-tab" href="{{ route('admin.pharmacy_categories') }}"><i class="fas fa-th-large"></i> Categories</a>
    <a class="wc-tab" href="{{ route('admin.pharmacy_brands') }}"><i class="fas fa-tags"></i> Brands</a>
    <a class="wc-tab" href="{{ route('admin.pharmacy_payment_accounts') }}"><i class="fas fa-wallet"></i>Accounts</a>
  </nav>
  <div class="wc-group-label active" data-group="storefront">Storefront content · Edit only</div>
  <section class="wc-card wc-section active" id="sec-promo">
    <div class="wc-card-head">
      <div class="wc-card-title">
        <div class="wc-card-icon"><i class="fas fa-bullhorn"></i></div>
        <div>
          <h3>Promo Banner</h3>
          <p>Small promo tiles shown in the homepage sidebar</p>
        </div>
      </div>
      <div class="wc-card-head-actions" style="display:flex; align-items:center; gap:10px;">
        <span class="wc-badge-count" id="promoCount"></span>
        <button class="btn btn-primary btn-sm" id="promoAddBtn" type="button" onclick="openModal('promo')">
          <i class="fas fa-plus"></i> Add banner
        </button>
      </div>
    </div>
    <div class="wc-promo-grid" id="promoGrid"></div>
  </section>
</div>
</main>
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
<!-- ============ CROP MODAL ============ -->
<div class="wc-crop-overlay" id="wcCropOverlay">
  <div class="wc-crop-modal">
    <div class="wc-modal-head">
      <h3>Crop banner image</h3>
      <button class="wc-modal-close" onclick="cancelCrop()" type="button"><i class="fas fa-times"></i></button>
    </div>
    <div class="wc-crop-stage"><img id="wcCropImg" alt="Crop preview"></div>
    <div class="wc-crop-info" id="wcCropInfo"></div>
    <div class="wc-modal-foot">
      <button class="btn btn-outline" onclick="cancelCrop()" type="button">Cancel</button>
      <button class="btn btn-primary" id="wcCropApplyBtn" onclick="applyCrop()" type="button"><i class="fas fa-crop-alt"></i> Crop &amp; use image</button>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
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
/* ============================================================================
   DATA — loaded from the server (PromoBanner model)
============================================================================ */
let promoBanners = @json($promoBanners ?? []);

window.CSRF_TOKEN = "{{ csrf_token() }}";
window.PROMO_MAX  = {{ \App\Models\PromoBanner::MAX_BANNERS }};

const PROMO_STORE_URL        = "{{ route('admin.pharmacy_promo_banners.store') }}";
const PROMO_UPDATE_URL_TMPL  = "{{ route('admin.pharmacy_promo_banners.update', ['promoBanner' => '__ID__']) }}";
const PROMO_DESTROY_URL_TMPL = "{{ route('admin.pharmacy_promo_banners.destroy', ['promoBanner' => '__ID__']) }}";
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
   RENDER: PROMO BANNER (read/update)
============================================================================ */
function renderPromo(){
  const count = promoBanners.length;
  document.getElementById('promoCount').textContent = `${count} / ${window.PROMO_MAX} banners`;

  const addBtn = document.getElementById('promoAddBtn');
  if(addBtn){
    const atLimit = count >= window.PROMO_MAX;
    addBtn.disabled = atLimit;
    addBtn.title = atLimit ? `Limit of ${window.PROMO_MAX} promo banners reached — delete one to add another.` : '';
  }

  const sorted = [...promoBanners].sort((a,b)=>a.sort_order-b.sort_order);
  document.getElementById('promoGrid').innerHTML = sorted.length ? sorted.map(p => `
    <div class="wc-promo-card">
      <div class="wc-promo-card-media">
        ${p.image
          ? `<img src="${esc(p.image)}" alt="${esc(p.alt || '')}" onerror="this.outerHTML='<div class=&quot;wc-thumb-fallback&quot; title=&quot;${esc(fileBase(p.image))}&quot;><i class=&quot;fas fa-image&quot;></i></div>'">`
          : `<div class="wc-thumb-fallback" title="No image set"><i class="fas fa-image"></i></div>`}
        <span class="wc-promo-card-order">Slot ${p.sort_order}</span>
        <span class="wc-pill wc-promo-card-type ${p.type==='red' ? 'on' : 'off'}">${esc(p.type)}</span>
      </div>
      <div class="wc-promo-card-body">
        <div class="wc-promo-card-title">${esc(p.alt || '—')}</div>
        <div class="wc-promo-card-meta">${esc(fileBase(p.image)) || 'No image'}</div>
      </div>
      <div class="wc-promo-card-actions">
        <button class="btn-icon" title="Edit" onclick="openModal('promo', ${p.id})"><i class="fas fa-pen"></i></button>
        <button class="btn-icon danger" title="Delete" onclick="confirmDeletePromo(${p.id})"><i class="fas fa-trash"></i></button>
      </div>
    </div>
  `).join('') : `<div class="wc-promo-empty">No promo banners yet — add one to get started.</div>`;
}
renderPromo();

/* ============================================================================
   DELETE: PROMO BANNER
============================================================================ */
function confirmDeletePromo(id){
  const banner = promoBanners.find(p => p.id === id);
  if(!banner) return;
  if(!confirm(`Delete the promo banner "${banner.alt || ('Slot ' + banner.sort_order)}"? This cannot be undone.`)) return;
  deletePromo(id);
}

async function deletePromo(id){
  try {
    const fd = new FormData();
    fd.append('_method', 'DELETE');

    const res = await fetch(PROMO_DESTROY_URL_TMPL.replace('__ID__', id), {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json' },
      body: fd,
    });
    const payload = await res.json().catch(() => ({}));

    if(!res.ok){
      toast(payload.message || 'Could not delete that banner.');
      return;
    }

    promoBanners = promoBanners.filter(p => p.id !== id);
    toast(payload.message || 'Promo banner deleted.');
    renderPromo();
  } catch (err) {
    toast('Network error — please try again.');
  }
}
/* ============================================================================
   MODAL ENGINE (add / edit for all entity types)
============================================================================ */
const ENTITY = {
  promo: { store: () => promoBanners, titleField: 'alt', label:'Promo Banner', crud:true }
};

let modalState = { type:null, id:null, draft:null, files:{} };

/** Which sort-order slots (1..PROMO_MAX) are already used by another banner. */
function takenPromoSortOrders(excludeId = null){
  return new Set(
    promoBanners.filter(p => p.id !== excludeId).map(p => p.sort_order)
  );
}

function nextAvailablePromoSortOrder(){
  const taken = takenPromoSortOrders();
  for(let i = 1; i <= window.PROMO_MAX; i++){
    if(!taken.has(i)) return i;
  }
  return 1;
}

function openModal(type, id=null){
  if(type === 'promo' && !id && promoBanners.length >= window.PROMO_MAX){
    toast(`You can only have ${window.PROMO_MAX} promo banners at a time.`);
    return;
  }

  const cfg = ENTITY[type];
  const store = cfg.store();
  const existing = id ? store.find(x => x.id === id) : null;
  modalState = { type, id, draft: existing ? JSON.parse(JSON.stringify(existing)) : defaultDraft(type), files:{} };

  document.getElementById('wcModalTitle').textContent = existing ? `Edit ${cfg.label}` : `Add ${cfg.label}`;
  document.getElementById('wcModalBody').innerHTML = buildForm(type, modalState.draft);
  document.getElementById('wcModalOverlay').classList.add('open');
}

function closeModal(){
  if(document.getElementById('wcCropOverlay').classList.contains('open')) cancelCrop();
  document.getElementById('wcModalOverlay').classList.remove('open');
  modalState = { type:null, id:null, draft:null, files:{} };
}
function defaultDraft(type){
  const bases = {
    promo: { image:'', alt:'', type:'red', sort_order: nextAvailablePromoSortOrder(), is_active:true },
  };
  return bases[type] || {};
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
/* Required output size (px) for each cropped image field */
const CROP_SPECS = {
  image: { w: 2021, h: 528 }
};
let cropState = { cropper:null, input:null, fieldKey:null, file:null, objectUrl:null };

/* Picking a file no longer sets it directly — it opens the crop dialog first. */
function handleDraftUpload(input, fieldKey){
  const file = input.files[0];
  if(!file) return;
  if(!file.type.startsWith('image/')){
    toast('Please choose an image file.');
    input.value = '';
    return;
  }
  const spec = CROP_SPECS[fieldKey];
  if(!spec){ return; }

  teardownCropper();
  const url = URL.createObjectURL(file);
  cropState = { cropper:null, input, fieldKey, file, objectUrl:url };

  const img = document.getElementById('wcCropImg');
  img.onload = () => {
    const tooSmall = img.naturalWidth < spec.w || img.naturalHeight < spec.h;
    document.getElementById('wcCropInfo').innerHTML =
      `Drag and zoom to choose the area. Output is exactly <strong>${spec.w} × ${spec.h}px</strong>. ` +
      `Original: ${img.naturalWidth} × ${img.naturalHeight}px.` +
      (tooSmall ? ` <span class="wc-crop-warn">This image is smaller than the required size and will be upscaled, so it may look blurry.</span>` : '');
    if(cropState.cropper) cropState.cropper.destroy();
    cropState.cropper = new Cropper(img, {
      aspectRatio: spec.w / spec.h,
      viewMode: 1,
      dragMode: 'move',
      autoCropArea: 1,
      background: false,
      responsive: true,
      zoomOnWheel: true,
      cropBoxMovable: true,
      cropBoxResizable: true,
    });
  };
  img.src = url;
  document.getElementById('wcCropOverlay').classList.add('open');
}

function teardownCropper(){
  if(cropState.cropper){ cropState.cropper.destroy(); }
  if(cropState.objectUrl){ URL.revokeObjectURL(cropState.objectUrl); }
  const img = document.getElementById('wcCropImg');
  img.onload = null;
  img.removeAttribute('src');
  cropState = { cropper:null, input:null, fieldKey:null, file:null, objectUrl:null };
}

function cancelCrop(){
  document.getElementById('wcCropOverlay').classList.remove('open');
  if(cropState.input) cropState.input.value = '';   // allow re-choosing the same file
  teardownCropper();
}

function applyCrop(){
  const { cropper, fieldKey, file, input } = cropState;
  if(!cropper || !fieldKey) return;
  const spec = CROP_SPECS[fieldKey];
  const keepPng = file.type === 'image/png';
  const mime = keepPng ? 'image/png' : 'image/jpeg';

  const canvas = cropper.getCroppedCanvas({
    width: spec.w,
    height: spec.h,
    fillColor: keepPng ? undefined : '#ffffff',
    imageSmoothingEnabled: true,
    imageSmoothingQuality: 'high',
  });
  if(!canvas){ toast('Could not crop that image — please try again.'); return; }

  const btn = document.getElementById('wcCropApplyBtn');
  btn.disabled = true;
  canvas.toBlob((blob) => {
    btn.disabled = false;
    if(!blob){ toast('Could not crop that image — please try again.'); return; }

    const baseName = file.name.replace(/\.[^.]+$/, '') || 'banner';
    const ext = keepPng ? 'png' : 'jpg';
    const cropped = new File([blob], `${baseName}-${spec.w}x${spec.h}.${ext}`, { type: mime });

    modalState.files[fieldKey] = cropped;
    const dataUrl = canvas.toDataURL(mime, 0.92);
    modalState.draft[fieldKey] = dataUrl;
    document.getElementById(`upbox-${fieldKey}`).innerHTML = `<img src="${dataUrl}">`;
    document.getElementById(`upname-${fieldKey}`).textContent = cropped.name;

    document.getElementById('wcCropOverlay').classList.remove('open');
    if(input) input.value = '';
    teardownCropper();
  }, mime, 0.92);
}

/* Escape closes only the crop dialog when it is open (not the form underneath) */
window.addEventListener('keydown', (e) => {
  if(e.key === 'Escape' && document.getElementById('wcCropOverlay').classList.contains('open')){
    e.stopPropagation();
    cancelCrop();
  }
}, true);

function buildForm(type, d){
  if(type === 'promo'){
    const taken = takenPromoSortOrders(modalState.id);
    let sortOptions = '';
    for(let i = 1; i <= window.PROMO_MAX; i++){
      const isTaken = taken.has(i);
      sortOptions += `<option value="${i}" ${d.sort_order === i ? 'selected' : ''}>Slot ${i}${isTaken ? ' (Taken)' : ''}</option>`;
    }

    return `
      ${fieldRow('Banner image', imageUploadControl('image', d.image), 'After choosing a file you will crop it to 2021 × 528px.')}
      ${fieldRow('Alt text', `<input class="wc-input" value="${esc(d.alt)}" oninput="modalState.draft.alt=this.value">`)}
      ${fieldRow('Type', `<select class="wc-select" onchange="modalState.draft.type=this.value">
          <option value="red" ${d.type==='red'?'selected':''}>Red</option>
          <option value="beige" ${d.type==='beige'?'selected':''}>Beige</option>
        </select>`)}
      ${fieldRow(
        'Sort order',
        `<select class="wc-select" onchange="modalState.draft.sort_order=parseInt(this.value)||1">${sortOptions}</select>`,
        `Only ${window.PROMO_MAX} slots exist. Picking a slot marked (Taken) will swap it with the banner currently in that slot.`
      )}
    `;
  }
  return '';
}
document.getElementById('wcModalSaveBtn').addEventListener('click', async () => {
  const { type, id, draft, files } = modalState;
  if(!type) return;

  if(type === 'promo'){
    if(!id && promoBanners.length >= window.PROMO_MAX){
      toast(`You can only have ${window.PROMO_MAX} promo banners at a time.`);
      return;
    }
    if(!id && !(files && files.image)){
      toast('Please choose a banner image.');
      return;
    }
    if(!draft.sort_order){
      toast('Please choose a sort order.');
      return;
    }

    const fd = new FormData();
    fd.append('alt', draft.alt || '');
    fd.append('type', draft.type || 'red');
    fd.append('sort_order', draft.sort_order);
    fd.append('is_active', '1');
    if(files && files.image) fd.append('image', files.image);
    if(id) fd.append('_method', 'PUT');

    const url = id ? PROMO_UPDATE_URL_TMPL.replace('__ID__', id) : PROMO_STORE_URL;
    const saveBtn = document.getElementById('wcModalSaveBtn');
    saveBtn.disabled = true;

    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json' },
        body: fd,
      });
      const payload = await res.json().catch(() => ({}));

      if(!res.ok){
        const firstError = payload.errors ? Object.values(payload.errors)[0]?.[0] : null;
        toast(firstError || payload.message || 'Something went wrong — please check the form.');
        return;
      }

      if(Array.isArray(payload.banners)){
        promoBanners = payload.banners;
      } else if(id){
        const idx = promoBanners.findIndex(x => x.id === id);
        if(idx > -1) promoBanners[idx] = payload.banner;
      } else {
        promoBanners.push(payload.banner);
      }

      toast(payload.message || 'Promo banner saved.');
      closeModal();
      renderPromo();
    } catch (err) {
      toast('Network error — please try again.');
    } finally {
      saveBtn.disabled = false;
    }
  }
});
document.getElementById('wcModalOverlay').addEventListener('click', (e) => { if(e.target === e.currentTarget) closeModal(); });
document.addEventListener('keydown', (e) => { if(e.key === 'Escape'){ closeModal(); } });
</script>
</body>
</html>