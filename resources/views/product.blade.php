<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $product->name }} – {{ $settings['site_name'] ?? 'GG Pharmacy' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --red: #C01A1A;
    --red2: #CC1A1A;
    --dark-red: #8B1010;
    --green: #2E7D32;
    --dark-green: #1B5E20;
    --gray-light: #f5f5f5;
    --gray: #666;
    --border: #e0e0e0;
    --text: #222;
  }
  body { font-family: 'Open Sans', sans-serif; font-size: 14px; color: var(--text); background: #fff; }
  a { text-decoration: none; color: inherit; }

  /* ── TOP BAR ─────────────────────────────────────────────────────────────── */
  .top-bar { background: #fff; border-bottom: 1px solid var(--border); padding: 6px 40px; display: flex; justify-content: flex-end; align-items: center; gap: 20px; font-size: 12px; }
  .top-bar a { color: #333; font-weight: 600; display: flex; align-items: center; gap: 5px; }
  .top-bar a:hover { color: var(--red); }
  .top-bar .sep { color: #ccc; }
  .top-bar .social { display: flex; gap: 10px; margin-left: 10px; }
  .top-bar .social a { width: 24px; height: 24px; border-radius: 50%; background: #1877f2; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; }
  .top-bar .social a.insta { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fd5949 45%, #d6249f 60%, #285aeb 90%); }

  /* ── HEADER ─────────────────────────────────────────────────────────────── */
  .header { background: #fff; padding: 12px 40px; display: flex; align-items: center; gap: 20px; border-bottom: 1px solid var(--border); }
  .logo img { height: 60px; object-fit: contain; }
  .search-bar { flex: 1; display: flex; border: 2px solid var(--border); border-radius: 4px; overflow: hidden; max-width: 600px; margin: 0 20px; }
  .search-bar input { flex: 1; padding: 10px 16px; border: none; outline: none; font-size: 13px; color: #999; }
  .search-bar button { background: var(--red); border: none; padding: 0 20px; cursor: pointer; color: #fff; font-size: 16px; }
  .header-right { display: flex; align-items: center; gap: 18px; margin-left: auto; }
  .phone-box { display: flex; align-items: center; gap: 10px; }
  .phone-box .ph-icon { color: var(--red); font-size: 22px; }
  .phone-box span { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 13px; color: var(--red); }
  .phone-box small { display: block; font-size: 11px; font-weight: 700; }
  .icon-btn { font-size: 20px; color: #555; cursor: pointer; }
  .cart-btn { display: flex; align-items: center; gap: 6px; font-family: 'Montserrat', sans-serif; font-weight: 700; }
  .cart-badge { background: var(--red); color: #fff; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; display: flex; align-items: center; justify-content: center; }

  /* ── NOTIFICATION DROPDOWN ───────────────────────────────────────────────── */
  .notif-wrapper { position: relative; display: flex; align-items: center; }
  .notif-badge { position: absolute; top: -6px; right: -6px; background: var(--red); color: #fff; border-radius: 50%; width: 16px; height: 16px; font-size: 9px; font-weight: 800; display: flex; align-items: center; justify-content: center; font-family: 'Montserrat', sans-serif; pointer-events: none; }
  .notif-dropdown { display: none; position: absolute; top: calc(100% + 14px); right: -12px; width: 320px; background: #fff; border-radius: 10px; box-shadow: 0 8px 32px rgba(0,0,0,0.15); border: 1px solid var(--border); z-index: 500; overflow: hidden; }
  .notif-dropdown.open { display: block; }
  .notif-dropdown::before { content: ''; position: absolute; top: -6px; right: 18px; width: 12px; height: 12px; background: #fff; border-left: 1px solid var(--border); border-top: 1px solid var(--border); transform: rotate(45deg); }
  .notif-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 16px 12px; border-bottom: 1px solid var(--border); }
  .notif-header span { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 13px; color: var(--text); }
  .notif-mark-all { font-size: 11px; color: var(--red); font-weight: 700; cursor: pointer; background: none; border: none; font-family: 'Open Sans', sans-serif; }
  .notif-mark-all:hover { text-decoration: underline; }
  .notif-list { max-height: 320px; overflow-y: auto; }
  .notif-list::-webkit-scrollbar { width: 4px; }
  .notif-list::-webkit-scrollbar-thumb { background: var(--red); border-radius: 4px; }
  .notif-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 16px; border-bottom: 1px solid #f5f5f5; cursor: pointer; transition: background 0.15s; position: relative; }
  .notif-item:last-child { border-bottom: none; }
  .notif-item:hover { background: #fafafa; }
  .notif-item.unread { background: #fff8f8; }
  .notif-item.unread:hover { background: #fff3f3; }
  .notif-icon-wrap { flex-shrink: 0; width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 15px; }
  .notif-icon-wrap.order { background: #fff0e0; color: #e65100; }
  .notif-icon-wrap.promo { background: #fff0f0; color: var(--red); }
  .notif-icon-wrap.system { background: #e8f5e9; color: var(--green); }
  .notif-icon-wrap.wishlist { background: #fce4ec; color: #e91e63; }
  .notif-body { flex: 1; min-width: 0; }
  .notif-body p { font-size: 12px; color: var(--text); line-height: 1.45; margin-bottom: 3px; }
  .notif-body p strong { font-weight: 700; }
  .notif-time { font-size: 10px; color: #aaa; font-weight: 600; }
  .notif-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--red); flex-shrink: 0; margin-top: 5px; }
  .notif-empty { padding: 36px 16px; text-align: center; color: #bbb; font-size: 13px; }
  .notif-empty i { font-size: 32px; display: block; margin-bottom: 10px; color: #ddd; }
  .notif-footer { padding: 11px 16px; border-top: 1px solid var(--border); text-align: center; }
  .notif-footer a { font-size: 12px; font-weight: 700; color: var(--red); font-family: 'Montserrat', sans-serif; }
  .notif-footer a:hover { text-decoration: underline; }

  /* ── NAV ─────────────────────────────────────────────────────────────────── */
  nav { background: var(--red); border-bottom: 2px solid var(--dark-red); }
  nav ul { display: flex; align-items: center; list-style: none; padding: 0 40px; }
  nav ul li { position: relative; }
  nav ul li > a { display: block; padding: 14px 16px; font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700; color: #fff; white-space: nowrap; }
  nav ul li > a:hover, nav ul li.active > a { color: #ffd600; }
  nav ul li > a .fa-chevron-down { font-size: 9px; margin-left: 4px; }
  .hamburger { display: none; background: none; border: none; cursor: pointer; padding: 10px 16px; color: #fff; font-size: 22px; margin-left: auto; }
  .green-strip { background: var(--green); padding: 5px 40px; font-size: 11px; color: #fff; font-weight: 700; text-align: center; }

  /* ── FOOTER ─────────────────────────────────────────────────────────────── */
  footer { background: #222; color: #aaa; padding: 50px 40px 30px; }
  .footer-top-bar { background: var(--green); padding: 14px 40px; display: flex; align-items: center; gap: 16px; }
  .footer-top-bar img { height: 40px; }
  .footer-top-bar span { color: #fff; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 14px; }
  .footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1.2fr; gap: 40px; margin-bottom: 40px; }
  .footer-section h4 { font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 800; color: #fff; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 16px; }
  .footer-section p, .footer-section a { font-size: 12px; color: #aaa; line-height: 2; display: block; }
  .footer-section a:hover { color: #fff; }
  .footer-section .label { font-weight: 700; color: #fff; font-size: 12px; margin-top: 10px; }
  .footer-social { display: flex; gap: 10px; margin-top: 16px; }
  .footer-social a { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; }
  .footer-social a.fb { background: #1877f2; color: #fff; }
  .footer-social a.ig { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fd5949 45%, #d6249f 60%, #285aeb 90%); color: #fff; }
  .newsletter input { width: 100%; padding: 12px; border: 1px solid #555; background: #333; color: #fff; border-radius: 6px; margin-bottom: 10px; outline: none; font-size: 13px; }
  .subscribe-btn { background: var(--green); color: #fff; border: none; padding: 12px 24px; font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 13px; border-radius: 6px; cursor: pointer; width: 100%; }
  .footer-note { font-size: 11px; color: #777; margin-top: 10px; line-height: 1.6; }
  .footer-bottom { border-top: 1px solid #444; padding-top: 20px; display: flex; align-items: center; justify-content: space-between; }
  .footer-bottom p { font-size: 11px; color: #666; }
  .payment-icons { display: flex; gap: 8px; }
  .pay-icon { background: #fff; border-radius: 4px; padding: 4px 8px; font-size: 10px; font-weight: 800; color: #222; }
  .shipping-bar { background: var(--green); color: #fff; padding: 10px; text-align: center; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; position: sticky; bottom: 0; z-index: 100; }
  .shipping-bar a { color: #ffd600; font-weight: 800; }

  /* ── LOGIN MODAL ─────────────────────────────────────────────────────────── */
  .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 2000; align-items: center; justify-content: center; }
  .modal-overlay.open { display: flex; }
  .modal-box { background: #fff; border-radius: 12px; width: 100%; max-width: 400px; padding: 40px 36px 32px; position: relative; box-shadow: 0 12px 40px rgba(0,0,0,0.2); margin: 16px; }
  .modal-close { position: absolute; top: 14px; right: 18px; background: none; border: none; font-size: 20px; color: #888; cursor: pointer; line-height: 1; }
  .modal-close:hover { color: var(--red); }
  .modal-logo { text-align: center; margin-bottom: 20px; }
  .modal-logo span { font-family: 'Montserrat', sans-serif; font-weight: 900; font-size: 20px; color: var(--red); letter-spacing: 1px; }
  .modal-title { font-family: 'Montserrat', sans-serif; font-size: 16px; font-weight: 800; color: var(--text); text-align: center; margin-bottom: 6px; }
  .modal-subtitle { font-size: 12px; color: var(--gray); text-align: center; margin-bottom: 24px; }
  .modal-field { margin-bottom: 16px; }
  .modal-field label { display: block; font-size: 12px; font-weight: 700; color: #444; margin-bottom: 6px; font-family: 'Montserrat', sans-serif; }
  .modal-field input { width: 100%; padding: 11px 14px; border: 1.5px solid var(--border); border-radius: 6px; font-size: 13px; outline: none; transition: border-color 0.2s; font-family: 'Open Sans', sans-serif; }
  .modal-field input:focus { border-color: var(--red); }
  .modal-forgot { text-align: right; margin-top: -8px; margin-bottom: 20px; }
  .modal-forgot a { font-size: 11px; color: var(--red); font-weight: 600; }
  .modal-forgot a:hover { text-decoration: underline; }
  .modal-login-btn { width: 100%; background: var(--red); color: #fff; border: none; border-radius: 6px; padding: 13px; font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 14px; cursor: pointer; letter-spacing: 0.5px; transition: background 0.2s; }
  .modal-login-btn:hover { background: var(--dark-red); }
  .modal-divider { display: flex; align-items: center; gap: 10px; margin: 20px 0; }
  .modal-divider hr { flex: 1; border: none; border-top: 1px solid var(--border); }
  .modal-divider span { font-size: 11px; color: #aaa; white-space: nowrap; }
  .modal-register { text-align: center; font-size: 12px; color: var(--gray); }
  .modal-register a { color: var(--red); font-weight: 700; }
  .modal-register a:hover { text-decoration: underline; }
  .modal-box.wide { max-width: 540px; padding: 36px 40px 32px; max-height: 93vh; overflow-y: auto; }
  .modal-box.wide::-webkit-scrollbar { width: 4px; }
  .modal-box.wide::-webkit-scrollbar-thumb { background: var(--red); border-radius: 4px; }
  .reg-avatar-block { display: flex; flex-direction: column; align-items: center; gap: 10px; margin-bottom: 22px; }
  .reg-avatar-ring { width: 88px; height: 88px; border-radius: 50%; background: #f5f5f5; border: 2.5px dashed #d0d0d0; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; cursor: pointer; transition: border-color 0.2s; }
  .reg-avatar-ring:hover { border-color: var(--red); }
  .reg-avatar-ring i { font-size: 32px; color: #ccc; }
  .reg-avatar-ring img { width: 100%; height: 100%; object-fit: cover; display: none; position: absolute; inset: 0; }
  .reg-avatar-ring .reg-cam-badge { position: absolute; bottom: 4px; right: 4px; background: var(--red); color: #fff; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 10px; }
  .reg-avatar-label { font-family: 'Montserrat', sans-serif; font-size: 11px; font-weight: 700; color: #888; letter-spacing: 0.5px; text-transform: uppercase; }
  .reg-avatar-hint { font-size: 10px; color: #bbb; }
  .reg-avatar-block input[type="file"] { display: none; }
  .reg-section-label { font-family: 'Montserrat', sans-serif; font-size: 10px; font-weight: 800; color: #bbb; text-transform: uppercase; letter-spacing: 1px; margin: 4px 0 14px; display: flex; align-items: center; gap: 8px; }
  .reg-section-label::before, .reg-section-label::after { content: ''; flex: 1; height: 1px; background: #ebebeb; }
  .modal-fields-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px; }
  .input-icon-wrap { position: relative; }
  .input-icon-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #bbb; font-size: 13px; pointer-events: none; }
  .input-icon-wrap input { width: 100%; padding: 10px 14px 10px 36px; border: 1.5px solid var(--border); border-radius: 6px; font-size: 13px; outline: none; transition: border-color 0.2s; font-family: 'Open Sans', sans-serif; background: #fafafa; }
  .input-icon-wrap input:focus { border-color: var(--red); background: #fff; }
  .modal-field textarea { width: 100%; padding: 10px 14px 10px 36px; border: 1.5px solid var(--border); border-radius: 6px; font-size: 13px; outline: none; resize: none; font-family: 'Open Sans', sans-serif; background: #fafafa; transition: border-color 0.2s; }
  .modal-field textarea:focus { border-color: var(--red); background: #fff; }
  .textarea-icon-wrap { position: relative; }
  .textarea-icon-wrap i { position: absolute; left: 12px; top: 12px; color: #bbb; font-size: 13px; pointer-events: none; }
  .textarea-icon-wrap textarea { padding-left: 36px; }
  .input-error { border-color: var(--red) !important; background: #fff5f5 !important; }
  .modal-alert-error { background: #fff0f0; border: 1px solid #ffc5c5; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; font-size: 12px; color: var(--red); display: flex; flex-direction: column; gap: 4px; }
  .modal-alert-error i { margin-right: 5px; }

  /* ── PRODUCT CARD (reused in related section) ────────────────────────────── */
  .product-carousel { display: flex; gap: 16px; overflow-x: auto; padding-bottom: 10px; }
  .product-carousel::-webkit-scrollbar { height: 4px; }
  .product-carousel::-webkit-scrollbar-track { background: #f5f5f5; }
  .product-carousel::-webkit-scrollbar-thumb { background: var(--red); border-radius: 4px; }
  .product-card { flex: 0 0 200px; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; background: #fff; transition: box-shadow 0.2s; }
  .product-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
  .product-img { height: 180px; display: flex; align-items: center; justify-content: center; background: #fafafa; padding: 16px; position: relative; }
  .product-img img { max-width: 140px; max-height: 140px; object-fit: contain; }
  .badge { position: absolute; top: 10px; left: 10px; background: var(--red); color: #fff; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 4px; font-family: 'Montserrat', sans-serif; }
  .badge.sale-badge { background: #e65100; }
  .most-sold { background: var(--green); color: #fff; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 4px; font-family: 'Montserrat', sans-serif; position: absolute; top: 10px; left: 10px; }
  .product-info { padding: 12px; }
  .product-cat { font-size: 9px; font-weight: 800; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
  .product-name { font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; color: var(--text); margin-bottom: 8px; line-height: 1.4; min-height: 34px; }
  .product-price { font-family: 'Montserrat', sans-serif; font-size: 16px; font-weight: 900; color: var(--red); margin-bottom: 10px; }
  .product-price .old-price { text-decoration: line-through; color: var(--gray); font-size: 12px; font-weight: 400; margin-right: 6px; }

  /* ── BREADCRUMB ──────────────────────────────────────────────────────────── */
  .breadcrumb-bar {
    background: var(--gray-light);
    border-bottom: 1px solid var(--border);
    padding: 10px 40px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: var(--gray);
    flex-wrap: wrap;
  }
  .breadcrumb-bar a { color: var(--gray); font-weight: 600; transition: color 0.2s; }
  .breadcrumb-bar a:hover { color: var(--red); }
  .breadcrumb-bar .sep { color: #ccc; font-size: 10px; }
  .breadcrumb-bar .current { color: var(--text); font-weight: 700; }

  /* ── PRODUCT DETAIL WRAPPER ──────────────────────────────────────────────── */
  .product-detail-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 36px 40px 0;
    display: grid;
    grid-template-columns: 460px 1fr;
    gap: 48px;
    align-items: start;
  }

  /* ── PRODUCT IMAGE PANEL ─────────────────────────────────────────────────── */
  .product-image-panel {
    position: sticky;
    top: 20px;
  }
  .product-main-img-wrap {
    background: #fafafa;
    border: 1px solid var(--border);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
    min-height: 380px;
    position: relative;
    overflow: hidden;
  }
  .product-main-img-wrap img {
    max-width: 100%;
    max-height: 300px;
    object-fit: contain;
    transition: transform 0.3s ease;
  }
  .product-main-img-wrap:hover img { transform: scale(1.04); }
  .product-badge-overlay {
    position: absolute;
    top: 16px;
    left: 16px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .pd-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 5px;
    font-family: 'Montserrat', sans-serif;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.3px;
  }
  .pd-badge.sale { background: #e65100; color: #fff; }
  .pd-badge.most-sold-badge { background: var(--green); color: #fff; }
  .pd-badge.discount { background: var(--red); color: #fff; }
  .product-share-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 16px;
  }
  .share-label { font-size: 11px; font-weight: 700; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; }
  .share-btn {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    color: #fff;
    transition: opacity 0.2s;
    cursor: pointer;
  }
  .share-btn:hover { opacity: 0.85; }
  .share-btn.fb  { background: #1877f2; }
  .share-btn.ig  { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fd5949 45%, #d6249f 60%, #285aeb 90%); }
  .share-btn.tw  { background: #1da1f2; }
  .share-btn.link { background: #607d8b; }

  /* ── PRODUCT INFO PANEL ──────────────────────────────────────────────────── */
  .product-info-panel { padding-bottom: 40px; }

  .product-meta-tags {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 12px;
  }
  .meta-tag {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 3px 10px;
    border-radius: 20px;
    border: 1.5px solid;
  }
  .meta-tag.category { color: #e65100; border-color: #e65100; background: #fff3e0; }
  .meta-tag.brand    { color: var(--green); border-color: var(--green); background: #e8f5e9; }
  .meta-tag.section  { color: var(--red); border-color: var(--red); background: #fff0f0; }

  .product-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 26px;
    font-weight: 900;
    color: var(--text);
    line-height: 1.25;
    margin-bottom: 16px;
  }

  .product-rating-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
  }
  .stars { color: #f5a623; font-size: 14px; letter-spacing: 2px; }
  .rating-text { font-size: 12px; color: var(--gray); }
  .origin-chip {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 700;
    color: var(--gray);
  }
  .origin-chip i { color: var(--red); }

  /* Pricing */
  .price-block { margin-bottom: 24px; }
  .price-main {
    font-family: 'Montserrat', sans-serif;
    font-size: 36px;
    font-weight: 900;
    color: var(--red);
    line-height: 1;
    margin-bottom: 6px;
  }
  .price-old-row {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .price-old {
    font-size: 16px;
    color: #aaa;
    text-decoration: line-through;
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
  }
  .price-save-badge {
    background: #e65100;
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    font-size: 11px;
    font-weight: 800;
    padding: 3px 10px;
    border-radius: 20px;
  }

  /* Qty + Cart */
  .cart-action-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    flex-wrap: wrap;
  }
  .qty-wrap {
    display: flex;
    align-items: center;
    border: 2px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
  }
  .qty-wrap button {
    width: 40px;
    height: 48px;
    border: none;
    background: #f5f5f5;
    font-size: 18px;
    font-weight: 700;
    color: var(--text);
    cursor: pointer;
    transition: background 0.15s;
  }
  .qty-wrap button:hover { background: #e0e0e0; }
  .qty-wrap input {
    width: 56px;
    height: 48px;
    border: none;
    border-left: 2px solid var(--border);
    border-right: 2px solid var(--border);
    text-align: center;
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
    outline: none;
  }
  .add-cart-main {
    flex: 1;
    min-width: 160px;
    height: 48px;
    background: var(--red);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.2s;
    letter-spacing: 0.5px;
  }
  .add-cart-main:hover { background: var(--dark-red); }
  .wishlist-btn {
    width: 48px;
    height: 48px;
    border: 2px solid var(--border);
    border-radius: 8px;
    background: #fff;
    color: #ccc;
    font-size: 18px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
  }
  .wishlist-btn:hover, .wishlist-btn.active { color: #e91e63; border-color: #e91e63; }

  .buy-now-btn {
    width: 100%;
    height: 48px;
    background: var(--green);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.2s;
    margin-bottom: 24px;
  }
  .buy-now-btn:hover { background: var(--dark-green); }

  /* Delivery info strips */
  .delivery-strips { display: flex; flex-direction: column; gap: 10px; margin-bottom: 24px; }
  .delivery-strip {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: #fafafa;
  }
  .delivery-strip i { font-size: 18px; color: var(--green); flex-shrink: 0; }
  .delivery-strip .ds-label { font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; color: var(--text); }
  .delivery-strip .ds-sub { font-size: 11px; color: var(--gray); }

  /* ── PRODUCT TABS ────────────────────────────────────────────────────────── */
  .product-tabs-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 40px 48px;
  }
  .tabs-header {
    display: flex;
    border-bottom: 2px solid var(--border);
    margin-bottom: 28px;
    gap: 0;
    overflow-x: auto;
  }
  .tab-btn {
    padding: 14px 24px;
    font-family: 'Montserrat', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: var(--gray);
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
    letter-spacing: 0.3px;
  }
  .tab-btn:hover { color: var(--red); }
  .tab-btn.active { color: var(--red); border-bottom-color: var(--red); }
  .tab-pane { display: none; }
  .tab-pane.active { display: block; animation: fadeInTab 0.25s ease; }
  @keyframes fadeInTab { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }
  .tab-content-body {
    font-size: 14px;
    color: #444;
    line-height: 1.85;
  }
  .tab-content-body p { margin-bottom: 12px; }
  .tab-content-body ul { padding-left: 20px; margin-bottom: 12px; }
  .tab-content-body ul li { margin-bottom: 6px; }
  .tab-content-body h4 {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 10px;
    margin-top: 20px;
  }
  .tab-content-body h4:first-child { margin-top: 0; }

  /* Warning box */
  .warning-box {
    background: #fff8e1;
    border: 1px solid #ffe082;
    border-left: 4px solid #f5a623;
    border-radius: 6px;
    padding: 16px 20px;
    margin-bottom: 16px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
  }
  .warning-box i { color: #f5a623; font-size: 18px; flex-shrink: 0; margin-top: 2px; }
  .warning-box p { font-size: 13px; color: #5d4037; line-height: 1.6; margin: 0; }

  /* Dimensions table */
  .spec-table { width: 100%; border-collapse: collapse; }
  .spec-table tr { border-bottom: 1px solid var(--border); }
  .spec-table tr:last-child { border-bottom: none; }
  .spec-table td { padding: 12px 16px; font-size: 13px; }
  .spec-table td:first-child { font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--text); width: 200px; background: #fafafa; }
  .spec-table td:last-child { color: #444; }

  /* Empty tab state */
  .tab-empty {
    text-align: center;
    padding: 40px 20px;
    color: #bbb;
  }
  .tab-empty i { font-size: 40px; display: block; margin-bottom: 12px; }
  .tab-empty p { font-size: 13px; }

  /* ── RELATED PRODUCTS ────────────────────────────────────────────────────── */
  .related-section {
    background: var(--gray-light);
    border-top: 1px solid var(--border);
    padding: 40px;
  }
  .related-section .section-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px;
    font-weight: 900;
    color: var(--text);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .related-section .section-title::after {
    content: '';
    flex: 1;
    height: 2px;
    background: var(--border);
  }

  /* ── RESPONSIVE ──────────────────────────────────────────────────────────── */
  @media (max-width: 1024px) {
    .product-detail-wrap { grid-template-columns: 1fr 1fr; gap: 32px; }
    .footer-grid { grid-template-columns: 1fr 1fr; }
  }
  @media (max-width: 768px) {
    .top-bar { padding: 6px 16px; gap: 10px; flex-wrap: wrap; justify-content: center; }
    .top-bar .sep { display: none; }
    .top-bar a.store-finder, .top-bar a.track-order { display: none; }
    .top-bar .social { margin-left: 0; }
    .header { padding: 10px 16px; flex-wrap: wrap; gap: 10px; }
    .logo img { height: 44px; }
    .search-bar { order: 3; width: 100%; max-width: 100%; margin: 0; flex: none; }
    .header-right { margin-left: auto; gap: 12px; }
    .phone-box { display: none; }
    nav { position: relative; }
    nav ul { display: none; flex-direction: column; padding: 0; background: var(--red); position: absolute; top: 100%; left: 0; right: 0; z-index: 999; border-top: 2px solid var(--dark-red); }
    nav ul.open { display: flex; }
    nav ul li { width: 100%; border-bottom: 1px solid rgba(255,255,255,0.15); }
    nav ul li > a { padding: 14px 20px; font-size: 14px; display: block; width: 100%; }
    .hamburger { display: block; }
    .nav-inner { display: flex; align-items: center; padding: 0 16px; }
    .nav-brand { color: #fff; font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 14px; padding: 12px 0; }
    .green-strip { padding: 6px 12px; font-size: 10px; line-height: 1.6; }
    .breadcrumb-bar { padding: 10px 16px; }
    .product-detail-wrap { grid-template-columns: 1fr; gap: 24px; padding: 20px 16px 0; }
    .product-image-panel { position: static; }
    .product-main-img-wrap { min-height: 280px; padding: 24px; }
    .product-title { font-size: 20px; }
    .price-main { font-size: 28px; }
    .product-tabs-section { padding: 0 16px 32px; }
    .tab-btn { padding: 12px 16px; font-size: 12px; }
    .related-section { padding: 24px 16px; }
    .footer-grid { grid-template-columns: 1fr; gap: 24px; }
    .footer-bottom { flex-direction: column; gap: 12px; text-align: center; }
    footer { padding: 30px 16px 20px; }
    .shipping-bar { font-size: 11px; padding: 8px 12px; gap: 6px; flex-wrap: wrap; }
    .modal-fields-row { grid-template-columns: 1fr; }
  }
  @media (max-height: 680px) {
    .modal-box.wide { padding: 20px 28px 18px; }
    .reg-avatar-ring { width: 70px; height: 70px; }
    .modal-box.wide .modal-logo { margin-bottom: 8px; }
    .modal-box.wide .modal-subtitle { margin-bottom: 16px; }
    .modal-field { margin-bottom: 10px; }
  }
</style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════════════════════════
     HEADER — identical to home.blade.php
     ═══════════════════════════════════════════════════════════════════════════ --}}

<!-- TOP BAR -->
<div class="top-bar">
  <a href="#" class="store-finder"><i class="fas fa-map-marker-alt"></i> STORE FINDER</a>
  <span class="sep">|</span>
  <a href="#" class="track-order"><i class="fas fa-truck"></i> TRACK YOUR ORDER</a>
  <span class="sep">|</span>
  @guest
    <a href="#" id="loginBtn"><i class="fas fa-user"></i> LOG IN</a>
    <span class="sep">|</span>
    <a href="#" id="registerBtn"><i class="fas fa-user-plus"></i> REGISTER</a>
  @else
    <span style="color:#333;font-weight:600;display:flex;align-items:center;gap:5px;">
      <i class="fas fa-user-circle"></i> {{ Auth::user()->full_name }}
    </span>
    <span class="sep">|</span>
    <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="margin:0;">
      @csrf
      <a href="#" onclick="event.preventDefault();document.getElementById('logoutForm').submit();" style="color:#333;font-weight:600;display:flex;align-items:center;gap:5px;">
        <i class="fas fa-sign-out-alt"></i> LOG OUT
      </a>
    </form>
  @endguest
  <div class="social">
    <a href="{{ $settings['facebook_url'] ?? '#' }}"><i class="fab fa-facebook-f"></i></a>
    <a href="{{ $settings['instagram_url'] ?? '#' }}" class="insta"><i class="fab fa-instagram"></i></a>
  </div>
</div>

<!-- HEADER -->
<div class="header">
  <div class="logo">
    <a href="{{ route('home') }}">
      <img src="{{ asset($settings['logo'] ?? 'images/logo.png') }}" alt="{{ $settings['site_name'] ?? 'GG Pharmacy' }}">
    </a>
  </div>
  <div class="search-bar">
    <input type="text" placeholder="Search for Generic and Branded Medicine">
    <button><i class="fas fa-search"></i></button>
  </div>
  <div class="header-right">
    <div class="phone-box">
      <i class="fas fa-phone-alt ph-icon"></i>
      <div>
        <small style="color:#555;font-size:10px;font-weight:600;">{{ $settings['phone_label'] ?? 'Call Us Now' }}</small>
        <span>{{ $settings['phone'] ?? '' }}</span>
      </div>
    </div>
    @auth
    <a href="{{ route('profile') }}" title="My Profile"><i class="fas fa-user icon-btn"></i></a>
    @else
    <a href="#" id="userIconLoginBtn" title="Log In" style="display:flex;align-items:center;"><i class="fas fa-user icon-btn"></i></a>
    @endauth

    @auth
    <div class="notif-wrapper" id="notifWrapper">
      <i class="far fa-heart icon-btn" id="notifToggle" title="Notifications" style="cursor:pointer;"></i>
      <span class="notif-badge" id="notifBadge">3</span>
      <div class="notif-dropdown" id="notifDropdown">
        <div class="notif-header">
          <span><i class="far fa-heart" style="color:var(--red);margin-right:6px;"></i> Notifications</span>
          <button class="notif-mark-all" id="notifMarkAll">Mark all as read</button>
        </div>
        <div class="notif-list" id="notifList">
          <div class="notif-item unread" data-id="1">
            <div class="notif-icon-wrap order"><i class="fas fa-box"></i></div>
            <div class="notif-body">
              <p><strong>Order #10245</strong> has been shipped and is on its way!</p>
              <span class="notif-time"><i class="fas fa-clock" style="margin-right:3px;"></i>2 minutes ago</span>
            </div>
            <div class="notif-dot"></div>
          </div>
          <div class="notif-item unread" data-id="2">
            <div class="notif-icon-wrap promo"><i class="fas fa-tag"></i></div>
            <div class="notif-body">
              <p><strong>Flash Sale!</strong> Up to 30% off on vitamins &amp; supplements today only.</p>
              <span class="notif-time"><i class="fas fa-clock" style="margin-right:3px;"></i>1 hour ago</span>
            </div>
            <div class="notif-dot"></div>
          </div>
          <div class="notif-item unread" data-id="3">
            <div class="notif-icon-wrap wishlist"><i class="fas fa-heart"></i></div>
            <div class="notif-body">
              <p><strong>Biogesic 500mg</strong> from your wishlist is back in stock.</p>
              <span class="notif-time"><i class="fas fa-clock" style="margin-right:3px;"></i>3 hours ago</span>
            </div>
            <div class="notif-dot"></div>
          </div>
          <div class="notif-item" data-id="4">
            <div class="notif-icon-wrap system"><i class="fas fa-check-circle"></i></div>
            <div class="notif-body">
              <p><strong>Order #10201</strong> was delivered successfully. Enjoy your purchase!</p>
              <span class="notif-time"><i class="fas fa-clock" style="margin-right:3px;"></i>Yesterday</span>
            </div>
          </div>
        </div>
        <div class="notif-footer"><a href="#">View all notifications</a></div>
      </div>
    </div>
    @else
    <i class="far fa-heart icon-btn" title="Log in to see notifications"></i>
    @endauth

    <div class="cart-btn">
      <div style="position:relative;">
        <i class="fas fa-shopping-cart icon-btn"></i>
        <div class="cart-badge" style="position:absolute;top:-8px;right:-8px;">0</div>
      </div>
    </div>
  </div>
</div>

<!-- NAV -->
<nav>
  <div class="nav-inner">
    <span class="nav-brand" style="display:none" id="nav-brand-label">{{ $settings['site_name'] ?? 'GG PHARMACY' }}</span>
    <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu">
      <i class="fas fa-bars"></i>
    </button>
  </div>
  <ul id="navMenu">
    @foreach($navItems as $item)
      <li @if($item->is_active) class="active" @endif>
        <a href="{{ $item->href }}">
          {{ $item->label }}
          @if($item->has_dropdown)
            <i class="fas fa-chevron-down"></i>
          @endif
        </a>
      </li>
    @endforeach
  </ul>
</nav>

<script>
  (function() {
    var btn = document.getElementById('hamburgerBtn');
    var menu = document.getElementById('navMenu');
    var label = document.getElementById('nav-brand-label');
    if (btn && menu) {
      btn.addEventListener('click', function() {
        menu.classList.toggle('open');
        var icon = btn.querySelector('i');
        icon.className = menu.classList.contains('open') ? 'fas fa-times' : 'fas fa-bars';
      });
    }
    function checkWidth() {
      if (window.innerWidth <= 768) { if (label) label.style.display = 'block'; }
      else { if (label) label.style.display = 'none'; if (menu) menu.classList.remove('open'); var icon = btn && btn.querySelector('i'); if (icon) icon.className = 'fas fa-bars'; }
    }
    checkWidth(); window.addEventListener('resize', checkWidth);
  })();
</script>

<!-- GREEN ADDRESS STRIP -->
<div class="green-strip">
  {{ $settings['location_strip'] ?? '' }} &nbsp;|&nbsp;
  <i class="fas fa-phone-alt"></i> {{ $settings['phone'] ?? '' }} &nbsp;|&nbsp;
  <i class="fas fa-clock"></i> {{ $settings['working_hours'] ?? '' }}
</div>

{{-- ═══════════════════════════════════════════════════════════════════════════
     PRODUCT PAGE CONTENT
     ═══════════════════════════════════════════════════════════════════════════ --}}

<!-- BREADCRUMB -->
<div class="breadcrumb-bar">
  <a href="{{ route('home') }}"><i class="fas fa-home" style="font-size:11px;"></i> Home</a>
  @if($product->section)
    <span class="sep"><i class="fas fa-chevron-right"></i></span>
    <a href="{{ $product->section->see_all_url ?? '#' }}">{{ $product->section->label }}</a>
  @endif
  @if($product->category)
    <span class="sep"><i class="fas fa-chevron-right"></i></span>
    <a href="#">{{ $product->category->name }}</a>
  @endif
  <span class="sep"><i class="fas fa-chevron-right"></i></span>
  <span class="current">{{ $product->name }}</span>
</div>

<!-- PRODUCT DETAIL -->
<div class="product-detail-wrap">

  {{-- ── LEFT: Image ───────────────────────────────────────────────────────── --}}
  <div class="product-image-panel">
    <div class="product-main-img-wrap">
      <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" id="mainProductImg">
      <div class="product-badge-overlay">
        @if($product->isMostSold())
          <span class="pd-badge most-sold-badge"><i class="fas fa-fire"></i> {{ $product->badge }}</span>
        @elseif($product->isSaleBadge())
          <span class="pd-badge sale"><i class="fas fa-tag"></i> {{ $product->badge }}</span>
        @elseif($product->badge)
          <span class="pd-badge discount">{{ $product->badge }}</span>
        @endif
        @if($product->hasDiscount())
          <span class="pd-badge discount"><i class="fas fa-percent"></i> Save {{ $product->discountPercent() }}%</span>
        @endif
      </div>
    </div>
    <div class="product-share-row">
      <span class="share-label">Share:</span>
      <a href="#" class="share-btn fb" title="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
      <a href="#" class="share-btn ig" title="Share on Instagram"><i class="fab fa-instagram"></i></a>
      <a href="#" class="share-btn tw" title="Share on Twitter"><i class="fab fa-twitter"></i></a>
      <a href="#" class="share-btn link" title="Copy link" onclick="event.preventDefault();navigator.clipboard&&navigator.clipboard.writeText(window.location.href);this.querySelector('i').className='fas fa-check';setTimeout(()=>this.querySelector('i').className='fas fa-link',1500);">
        <i class="fas fa-link"></i>
      </a>
    </div>
  </div>

  {{-- ── RIGHT: Info ────────────────────────────────────────────────────────── --}}
  <div class="product-info-panel">

    {{-- Meta tags --}}
    <div class="product-meta-tags">
      @if($product->category)
        <span class="meta-tag category"><i class="fas fa-tag" style="margin-right:4px;font-size:9px;"></i>{{ $product->category->name }}</span>
      @endif
      @if($product->brand)
        <span class="meta-tag brand"><i class="fas fa-building" style="margin-right:4px;font-size:9px;"></i>{{ $product->brand->name }}</span>
      @endif
      @if($product->section)
        <span class="meta-tag section">{{ $product->section->label }}</span>
      @endif
    </div>

    {{-- Product name --}}
    <h1 class="product-title">{{ $product->name }}</h1>

    {{-- Rating / origin row --}}
    <div class="product-rating-row">
      <span class="stars">★★★★★</span>
      <span class="rating-text">No reviews yet &nbsp;·&nbsp; Be the first to review</span>
      @if($product->origin)
        <span class="origin-chip"><i class="fas fa-globe-asia"></i> {{ $product->origin }}</span>
      @endif
    </div>

    {{-- Pricing --}}
    <div class="price-block">
      <div class="price-main">{{ $product->formattedPrice() }}</div>
      @if($product->hasDiscount())
        <div class="price-old-row">
          <span class="price-old">{{ $product->formattedOldPrice() }}</span>
          <span class="price-save-badge">You save {{ $product->discountPercent() }}%</span>
        </div>
      @endif
    </div>

    {{-- Qty + Add to Cart --}}
    <div class="cart-action-row">
      <div class="qty-wrap">
        <button type="button" id="qtyMinus" aria-label="Decrease quantity">−</button>
        <input type="number" id="qtyInput" value="1" min="1" max="99" aria-label="Quantity">
        <button type="button" id="qtyPlus" aria-label="Increase quantity">+</button>
      </div>
      <button class="add-cart-main" type="button">
        <i class="fas fa-shopping-cart"></i> ADD TO CART
      </button>
      <button class="wishlist-btn" id="wishlistBtn" type="button" title="Add to wishlist" aria-label="Add to wishlist">
        <i class="far fa-heart"></i>
      </button>
    </div>

    <button class="buy-now-btn" type="button">
      <i class="fas fa-bolt"></i> BUY NOW
    </button>

    {{-- Delivery info --}}
    <div class="delivery-strips">
      <div class="delivery-strip">
        <i class="fas fa-shipping-fast"></i>
        <div>
          <div class="ds-label">Fast Delivery Available</div>
          <div class="ds-sub">Order before 2PM for same-day dispatch</div>
        </div>
      </div>
      <div class="delivery-strip">
        <i class="fas fa-undo-alt"></i>
        <div>
          <div class="ds-label">Easy Returns</div>
          <div class="ds-sub">Return within 7 days for a full refund</div>
        </div>
      </div>
      <div class="delivery-strip">
        <i class="fas fa-shield-alt"></i>
        <div>
          <div class="ds-label">Authentic &amp; Quality Assured</div>
          <div class="ds-sub">All products are sourced from accredited suppliers</div>
        </div>
      </div>
    </div>

  </div>{{-- /.product-info-panel --}}

</div>{{-- /.product-detail-wrap --}}

<!-- PRODUCT TABS -->
<div class="product-tabs-section">

  <div class="tabs-header" role="tablist">
    <button class="tab-btn active" role="tab" aria-selected="true"  data-tab="description">
      <i class="fas fa-align-left" style="margin-right:6px;"></i> Description
    </button>
    @if($product->ingredients)
    <button class="tab-btn" role="tab" aria-selected="false" data-tab="ingredients">
      <i class="fas fa-flask" style="margin-right:6px;"></i> Ingredients
    </button>
    @endif
    @if($product->product_usage)
    <button class="tab-btn" role="tab" aria-selected="false" data-tab="usage">
      <i class="fas fa-book-medical" style="margin-right:6px;"></i> Directions
    </button>
    @endif
    @if($product->warnings)
    <button class="tab-btn" role="tab" aria-selected="false" data-tab="warnings">
      <i class="fas fa-exclamation-triangle" style="margin-right:6px;"></i> Warnings
    </button>
    @endif
    @if($product->hasDimensions() || $product->origin)
    <button class="tab-btn" role="tab" aria-selected="false" data-tab="specs">
      <i class="fas fa-ruler-combined" style="margin-right:6px;"></i> Specifications
    </button>
    @endif
  </div>

  {{-- Description --}}
  <div class="tab-pane active" id="tab-description" role="tabpanel">
    <div class="tab-content-body">
      @if($product->description)
        {!! nl2br(e($product->description)) !!}
      @else
        <div class="tab-empty">
          <i class="fas fa-align-left"></i>
          <p>No description available for this product.</p>
        </div>
      @endif
    </div>
  </div>

  {{-- Ingredients --}}
  @if($product->ingredients)
  <div class="tab-pane" id="tab-ingredients" role="tabpanel">
    <div class="tab-content-body">
      {!! nl2br(e($product->ingredients)) !!}
    </div>
  </div>
  @endif

  {{-- Usage / Directions --}}
  @if($product->product_usage)
  <div class="tab-pane" id="tab-usage" role="tabpanel">
    <div class="tab-content-body">
      {!! nl2br(e($product->product_usage)) !!}
    </div>
  </div>
  @endif

  {{-- Warnings --}}
  @if($product->warnings)
  <div class="tab-pane" id="tab-warnings" role="tabpanel">
    <div class="tab-content-body">
      <div class="warning-box">
        <i class="fas fa-exclamation-triangle"></i>
        <p>Please read all warnings carefully before use. Keep out of reach of children. Consult a healthcare professional if symptoms persist.</p>
      </div>
      {!! nl2br(e($product->warnings)) !!}
    </div>
  </div>
  @endif

  {{-- Specifications --}}
  @if($product->hasDimensions() || $product->origin)
  <div class="tab-pane" id="tab-specs" role="tabpanel">
    <div class="tab-content-body">
      <table class="spec-table">
        @if($product->origin)
        <tr>
          <td><i class="fas fa-globe-asia" style="margin-right:8px;color:var(--gray);"></i> Country of Origin</td>
          <td>{{ $product->origin }}</td>
        </tr>
        @endif
        @if($product->brand)
        <tr>
          <td><i class="fas fa-building" style="margin-right:8px;color:var(--gray);"></i> Brand</td>
          <td>{{ $product->brand->name }}</td>
        </tr>
        @endif
        @if($product->category)
        <tr>
          <td><i class="fas fa-tag" style="margin-right:8px;color:var(--gray);"></i> Category</td>
          <td>{{ $product->category->name }}</td>
        </tr>
        @endif
        @if($product->width)
        <tr>
          <td><i class="fas fa-arrows-alt-h" style="margin-right:8px;color:var(--gray);"></i> Width</td>
          <td>{{ $product->width }} cm</td>
        </tr>
        @endif
        @if($product->height)
        <tr>
          <td><i class="fas fa-arrows-alt-v" style="margin-right:8px;color:var(--gray);"></i> Height</td>
          <td>{{ $product->height }} cm</td>
        </tr>
        @endif
        @if($product->depth)
        <tr>
          <td><i class="fas fa-cube" style="margin-right:8px;color:var(--gray);"></i> Depth</td>
          <td>{{ $product->depth }} cm</td>
        </tr>
        @endif
      </table>
    </div>
  </div>
  @endif

</div>{{-- /.product-tabs-section --}}

<!-- RELATED PRODUCTS -->
@if($relatedProducts->isNotEmpty())
<div class="related-section">
  <h2 class="section-title">More from {{ $product->section->label ?? 'This Section' }}</h2>
  <div class="product-carousel">
    @foreach($relatedProducts as $related)
      @include('partials.product-card', ['product' => $related])
    @endforeach
  </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════════════════════
     FOOTER — identical to home.blade.php
     ═══════════════════════════════════════════════════════════════════════════ --}}

<div class="footer-top-bar">
  <img src="{{ asset($settings['logo'] ?? 'images/logo.png') }}" alt="{{ $settings['site_name'] ?? 'GG Pharmacy' }}">
  <span>{{ $settings['footer_top_text'] ?? 'Your Trusted Pharmacy' }}</span>
</div>
<footer>
  <div class="footer-grid">
    <div class="footer-section">
      <h4>CONTACT INFO</h4>
      <div class="label">ADDRESS:</div>
      <p>{{ $settings['address_line1'] ?? '' }}</p>
      <p>{{ $settings['address_line2'] ?? '' }}</p>
      <p>{{ $settings['address_line3'] ?? '' }}</p>
      <div class="label" style="margin-top:10px;">PHONE:</div>
      <p>{{ $settings['phone'] ?? '' }}</p>
      <div class="label" style="margin-top:10px;">EMAIL:</div>
      <p>{{ $settings['email'] ?? '' }}</p>
      <div class="label" style="margin-top:10px;">WORKING DAYS/HOURS:</div>
      <p>{{ $settings['working_hours'] ?? '' }}</p>
      <div class="footer-social">
        <a href="{{ $settings['facebook_url'] ?? '#' }}" class="fb"><i class="fab fa-facebook-f"></i></a>
        <a href="{{ $settings['instagram_url'] ?? '#' }}" class="ig"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
    <div class="footer-section">
      <h4>ABOUT GG PHARMACY</h4>
      <a href="#">About Us</a>
      <a href="#">Careers</a>
      <a href="#">Store Finder</a>
      <a href="#">Contact Us</a>
      <a href="#">Terms And Conditions</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Call &amp; Pick-Up</a>
    </div>
    <div class="footer-section">
      <h4>CUSTOMER SERVICE</h4>
      <a href="#">Help Center</a>
      <a href="#">Payment</a>
      <a href="#">Shipping &amp; Delivery</a>
      <a href="#">Returns &amp; Refunds</a>
      <a href="#">How To Buy</a>
      <a href="#">Question?</a>
      <a href="#">Track Your Order</a>
    </div>
    <div class="footer-section newsletter">
      <h4>SUBSCRIBE NEWSLETTER</h4>
      <p style="margin-bottom:14px;">{{ $settings['newsletter_intro'] ?? '' }}</p>
      <input type="email" placeholder="Email address">
      <button class="subscribe-btn">SUBSCRIBE</button>
      <p class="footer-note">{{ $settings['newsletter_note'] ?? '' }}</p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>{{ $settings['copyright'] ?? '' }}</p>
    <div class="payment-icons">
      @foreach(explode(',', $settings['payment_methods'] ?? 'VISA,MC,GCash,PayMaya') as $method)
        <div class="pay-icon">{{ trim($method) }}</div>
      @endforeach
    </div>
  </div>
</footer>

<!-- LOGIN MODAL -->
<div class="modal-overlay" id="loginModal">
  <div class="modal-box">
    <button class="modal-close" id="modalClose" aria-label="Close" type="button">&times;</button>
    <div class="modal-logo"><span>{{ strtoupper($settings['site_name'] ?? 'GG PHARMACY') }}</span></div>
    <div class="modal-title">Welcome Back!</div>
    <div class="modal-subtitle">Sign in to your account to continue</div>
    @if($errors->hasBag('login'))
      <div class="modal-alert-error">
        @foreach($errors->getBag('login')->all() as $err)
          <div><i class="fas fa-exclamation-circle"></i> {{ $err }}</div>
        @endforeach
      </div>
    @endif
    <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
      @csrf
      <div class="modal-field">
        <label for="loginEmail">Email Address</label>
        <input type="email" id="loginEmail" name="email" placeholder="Enter your email address" value="{{ old('email') }}" class="{{ $errors->getBag('login')->has('email') ? 'input-error' : '' }}">
      </div>
      <div class="modal-field">
        <label for="loginPassword">Password</label>
        <input type="password" id="loginPassword" name="password" placeholder="Enter your password" class="{{ $errors->getBag('login')->has('password') ? 'input-error' : '' }}">
      </div>
      <div class="modal-forgot"><a href="#">Forgot Password?</a></div>
      <button type="submit" class="modal-login-btn">LOG IN</button>
    </form>
    <div class="modal-divider"><hr><span>Don't have an account?</span><hr></div>
    <div class="modal-register">New to {{ $settings['site_name'] ?? 'GG Pharmacy' }}? <a href="#" id="switchToRegister">Register here</a></div>
  </div>
</div>

<!-- REGISTER MODAL -->
<div class="modal-overlay" id="registerModal">
  <div class="modal-box wide">
    <button class="modal-close" id="registerModalClose" aria-label="Close" type="button">&times;</button>
    <div class="modal-logo"><span>{{ strtoupper($settings['site_name'] ?? 'GG PHARMACY') }}</span></div>
    <div class="modal-title">Create an Account</div>
    <div class="modal-subtitle">Join {{ $settings['site_name'] ?? 'GG Pharmacy' }} and start shopping today</div>
    @if($errors->hasBag('register'))
      <div class="modal-alert-error">
        @foreach($errors->getBag('register')->all() as $err)
          <div><i class="fas fa-exclamation-circle"></i> {{ $err }}</div>
        @endforeach
      </div>
    @endif
    <form method="POST" action="{{ route('register') }}" id="registerForm" enctype="multipart/form-data" novalidate>
      @csrf
      <div class="reg-avatar-block">
        <label for="profilePicInput" style="cursor:pointer;">
          <div class="reg-avatar-ring" id="avatarPreview">
            <i class="fas fa-user"></i>
            <img id="avatarImg" src="" alt="Preview">
            <span class="reg-cam-badge"><i class="fas fa-camera"></i></span>
          </div>
        </label>
        <input type="file" id="profilePicInput" name="profile_picture" accept="image/*">
        <div class="reg-avatar-label">Profile Photo</div>
        <div class="reg-avatar-hint">JPG, PNG or GIF · Max 2MB</div>
      </div>
      <div class="reg-section-label">Personal Information</div>
      <div class="modal-fields-row">
        <div class="modal-field">
          <label for="regFirstName">First Name</label>
          <div class="input-icon-wrap">
            <i class="fas fa-user"></i>
            <input type="text" id="regFirstName" name="first_name" placeholder="Juan" value="{{ old('first_name') }}" class="{{ $errors->getBag('register')->has('first_name') ? 'input-error' : '' }}">
          </div>
        </div>
        <div class="modal-field">
          <label for="regLastName">Last Name</label>
          <div class="input-icon-wrap">
            <i class="fas fa-user"></i>
            <input type="text" id="regLastName" name="last_name" placeholder="dela Cruz" value="{{ old('last_name') }}" class="{{ $errors->getBag('register')->has('last_name') ? 'input-error' : '' }}">
          </div>
        </div>
      </div>
      <div class="modal-fields-row">
        <div class="modal-field">
          <label for="regEmail">Email Address</label>
          <div class="input-icon-wrap">
            <i class="fas fa-envelope"></i>
            <input type="email" id="regEmail" name="email" placeholder="you@example.com" value="{{ old('email') }}" class="{{ $errors->getBag('register')->has('email') ? 'input-error' : '' }}">
          </div>
        </div>
        <div class="modal-field">
          <label for="regContact">Contact Number</label>
          <div class="input-icon-wrap">
            <i class="fas fa-phone-alt"></i>
            <input type="tel" id="regContact" name="contact_number" placeholder="09XXXXXXXXX" value="{{ old('contact_number') }}" class="{{ $errors->getBag('register')->has('contact_number') ? 'input-error' : '' }}">
          </div>
        </div>
      </div>
      <div class="modal-field">
        <label for="regAddress">Address</label>
        <div class="textarea-icon-wrap">
          <i class="fas fa-map-marker-alt"></i>
          <textarea id="regAddress" name="address" rows="2" placeholder="House No., Street, Barangay, City/Municipality, Province" class="{{ $errors->getBag('register')->has('address') ? 'input-error' : '' }}">{{ old('address') }}</textarea>
        </div>
      </div>
      <div class="reg-section-label">Security</div>
      <div class="modal-fields-row">
        <div class="modal-field">
          <label for="regPassword">Password</label>
          <div class="input-icon-wrap">
            <i class="fas fa-lock"></i>
            <input type="password" id="regPassword" name="password" placeholder="Create a strong password" class="{{ $errors->getBag('register')->has('password') ? 'input-error' : '' }}">
          </div>
        </div>
        <div class="modal-field">
          <label for="regPasswordConfirm">Confirm Password</label>
          <div class="input-icon-wrap">
            <i class="fas fa-lock"></i>
            <input type="password" id="regPasswordConfirm" name="password_confirmation" placeholder="Repeat your password">
          </div>
        </div>
      </div>
      <button type="submit" class="modal-login-btn" style="margin-top:8px;">CREATE ACCOUNT</button>
    </form>
    <div class="modal-divider"><hr><span>Already have an account?</span><hr></div>
    <div class="modal-register">Already registered? <a href="#" id="switchToLogin">Log in here</a></div>
  </div>
</div>

<!-- SHIPPING BAR -->
<div class="shipping-bar">
  {{ $settings['shipping_message'] ?? '' }} &nbsp;<a href="#">Dismiss</a>
</div>

<script>
  // ── Qty controls ────────────────────────────────────────────────────────────
  const qtyInput = document.getElementById('qtyInput');
  document.getElementById('qtyMinus').addEventListener('click', () => {
    if (parseInt(qtyInput.value) > 1) qtyInput.value = parseInt(qtyInput.value) - 1;
  });
  document.getElementById('qtyPlus').addEventListener('click', () => {
    if (parseInt(qtyInput.value) < 99) qtyInput.value = parseInt(qtyInput.value) + 1;
  });

  // ── Wishlist toggle ─────────────────────────────────────────────────────────
  const wishlistBtn = document.getElementById('wishlistBtn');
  if (wishlistBtn) {
    wishlistBtn.addEventListener('click', function() {
      this.classList.toggle('active');
      const icon = this.querySelector('i');
      icon.className = this.classList.contains('active') ? 'fas fa-heart' : 'far fa-heart';
    });
  }

  // ── Tab switching ───────────────────────────────────────────────────────────
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.tab-btn').forEach(b => { b.classList.remove('active'); b.setAttribute('aria-selected','false'); });
      document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
      this.classList.add('active');
      this.setAttribute('aria-selected', 'true');
      const target = document.getElementById('tab-' + this.dataset.tab);
      if (target) target.classList.add('active');
    });
  });

  // ── Modal helpers ───────────────────────────────────────────────────────────
  const loginModal    = document.getElementById('loginModal');
  const registerModal = document.getElementById('registerModal');
  function openModal(modal)  { modal && modal.classList.add('open'); }
  function closeModal(modal) { modal && modal.classList.remove('open'); }

  const loginBtn         = document.getElementById('loginBtn');
  const registerBtn      = document.getElementById('registerBtn');
  const userIconLoginBtn = document.getElementById('userIconLoginBtn');
  if (loginBtn)         loginBtn.addEventListener('click',         e => { e.preventDefault(); openModal(loginModal); });
  if (registerBtn)      registerBtn.addEventListener('click',      e => { e.preventDefault(); openModal(registerModal); });
  if (userIconLoginBtn) userIconLoginBtn.addEventListener('click', e => { e.preventDefault(); openModal(loginModal); });

  const modalClose         = document.getElementById('modalClose');
  const registerModalClose = document.getElementById('registerModalClose');
  if (modalClose)         modalClose.addEventListener('click',         () => closeModal(loginModal));
  if (registerModalClose) registerModalClose.addEventListener('click', () => closeModal(registerModal));
  if (loginModal)    loginModal.addEventListener('click',    e => { if (e.target === loginModal)    closeModal(loginModal); });
  if (registerModal) registerModal.addEventListener('click', e => { if (e.target === registerModal) closeModal(registerModal); });

  const switchToLogin    = document.getElementById('switchToLogin');
  const switchToRegister = document.getElementById('switchToRegister');
  if (switchToLogin)    switchToLogin.addEventListener('click',    e => { e.preventDefault(); closeModal(registerModal); openModal(loginModal); });
  if (switchToRegister) switchToRegister.addEventListener('click', e => { e.preventDefault(); closeModal(loginModal); openModal(registerModal); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeModal(loginModal); closeModal(registerModal); } });

  @if($errors->hasBag('login') && $errors->getBag('login')->any())
    document.addEventListener('DOMContentLoaded', () => openModal(loginModal));
  @elseif($errors->hasBag('register') && $errors->getBag('register')->any())
    document.addEventListener('DOMContentLoaded', () => openModal(registerModal));
  @endif

  // Profile picture preview
  const profilePicInput = document.getElementById('profilePicInput');
  if (profilePicInput) {
    profilePicInput.addEventListener('change', function() {
      const file = this.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        const img  = document.getElementById('avatarImg');
        const icon = document.querySelector('#avatarPreview i');
        img.src = e.target.result; img.style.display = 'block';
        if (icon) icon.style.display = 'none';
      };
      reader.readAsDataURL(file);
    });
  }

  document.querySelector('.shipping-bar a').addEventListener('click', e => {
    e.preventDefault(); e.target.closest('.shipping-bar').style.display = 'none';
  });

  // ── Notification dropdown ───────────────────────────────────────────────────
  (function () {
    const toggle   = document.getElementById('notifToggle');
    const dropdown = document.getElementById('notifDropdown');
    const badge    = document.getElementById('notifBadge');
    const markAll  = document.getElementById('notifMarkAll');
    if (!toggle || !dropdown) return;
    function countUnread() { return document.querySelectorAll('#notifList .notif-item.unread').length; }
    function refreshBadge() { const n = countUnread(); badge.textContent = n; badge.style.display = n > 0 ? 'flex' : 'none'; }
    toggle.addEventListener('click', e => { e.stopPropagation(); dropdown.classList.toggle('open'); });
    document.querySelectorAll('#notifList .notif-item').forEach(item => {
      item.addEventListener('click', function() {
        this.classList.remove('unread');
        const dot = this.querySelector('.notif-dot'); if (dot) dot.remove();
        refreshBadge();
      });
    });
    if (markAll) {
      markAll.addEventListener('click', () => {
        document.querySelectorAll('#notifList .notif-item.unread').forEach(item => {
          item.classList.remove('unread');
          const dot = item.querySelector('.notif-dot'); if (dot) dot.remove();
        });
        refreshBadge();
      });
    }
    document.addEventListener('click', e => { if (!dropdown.contains(e.target) && e.target !== toggle) dropdown.classList.remove('open'); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') dropdown.classList.remove('open'); });
    refreshBadge();
  })();
</script>

@if(!empty($settings['chatbase_id']))
<script>
(function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="{{ $settings['chatbase_id'] }}";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
</script>
@endif
</body>
</html>