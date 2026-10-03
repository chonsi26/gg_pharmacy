<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>My Cart – {{ $settings['site_name'] ?? 'GG Pharmacy' }}</title>
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

  /* ── ADD-TO-CART TOAST + FLY ANIMATION ───────────────────────────────── */
  .cart-toast-container { position: fixed; top: 90px; right: 20px; z-index: 10000; display: flex; flex-direction: column; gap: 10px; pointer-events: none; }
  .cart-toast { background: #fff; border-left: 4px solid var(--red); box-shadow: 0 6px 22px rgba(0,0,0,0.15); border-radius: 6px; padding: 12px 16px; font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 600; color: #222; display: flex; align-items: center; gap: 10px; min-width: 220px; max-width: 320px; opacity: 0; transform: translateX(30px); transition: opacity 0.3s ease, transform 0.3s ease; }
  .cart-toast.show { opacity: 1; transform: translateX(0); }
  .cart-toast i { color: #2e9e4f; font-size: 16px; flex-shrink: 0; }
  @media (max-width: 768px) {
    .cart-toast-container { top: auto; bottom: 20px; right: 12px; left: 12px; align-items: center; }
    .cart-toast { transform: translateY(20px); max-width: 100%; }
    .cart-toast.show { transform: translateY(0); }
  }
  .fly-to-cart-img { position: fixed; z-index: 10001; border-radius: 8px; object-fit: cover; pointer-events: none; transition: transform 0.7s cubic-bezier(.35,0,.25,1), opacity 0.7s ease-in; will-change: transform, opacity; }
  @keyframes cartBump { 0% { transform: scale(1); } 35% { transform: scale(1.4); } 65% { transform: scale(0.92); } 100% { transform: scale(1); } }
  #cartIcon.cart-bump { animation: cartBump 0.4s ease; color: var(--red); }

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
  .footer-top-bar { background: #f3f3f3; padding: 14px 40px; display: flex; align-items: center; gap: 16px; }
  .footer-top-bar img { height: 40px; }
  .footer-top-bar span { color: var(--red); font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 14px; }
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
  .breadcrumb { padding: 14px 40px; background: #f9f9f9; border-bottom: 1px solid var(--border); font-size: 12px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
  .breadcrumb a { color: var(--gray); font-weight: 600; }
  .breadcrumb a:hover { color: var(--red); }
  .breadcrumb .sep { color: #ccc; }
  .breadcrumb .current { color: var(--text); font-weight: 700; }

  /* ══════════════════════════════════
     MEDICINE PAGE LAYOUT
  ══════════════════════════════════ */
  .med-page { max-width: 1200px; margin: 0 auto; padding: 30px 40px 60px; }

  /* ── TOP PRODUCT SECTION ── */
  .med-top { display: grid; grid-template-columns: 460px 1fr; gap: 40px; align-items: start; }

  /* ── IMAGE GALLERY ── */
  .img-gallery { position: sticky; top: 20px; }
  .main-img-wrap { border: 1px solid var(--border); border-radius: 12px; overflow: hidden; background: #fafafa; display: flex; align-items: center; justify-content: center; height: 360px; position: relative; }
  .main-img-wrap img { max-width: 85%; max-height: 85%; object-fit: contain; transition: transform 0.3s; }
  .main-img-wrap:hover img { transform: scale(1.05); }
  .img-badge-stack { position: absolute; top: 14px; left: 14px; display: flex; flex-direction: column; gap: 6px; }
  .img-badge { display: inline-flex; align-items: center; gap: 5px; background: var(--red); color: #fff; font-size: 10px; font-weight: 800; padding: 4px 10px; border-radius: 4px; font-family: 'Montserrat', sans-serif; }
  .img-badge.generic { background: var(--green); }
  .img-badge.discount { background: var(--dark-red); }

  /* ── MEDICINE INFO ── */
  .med-info { display: flex; flex-direction: column; gap: 14px; }
  .med-brand { font-size: 11px; font-weight: 700; color: var(--red); text-transform: uppercase; letter-spacing: 0.8px; }
  .med-name { font-family: 'Montserrat', sans-serif; font-size: 26px; font-weight: 900; color: var(--text); line-height: 1.25; }
  .med-generic { font-size: 13px; color: var(--gray); margin-top: -6px; }
  .med-generic span { font-weight: 700; color: var(--text); }

  /* ── RATING SUMMARY ── */
  .rating-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
  .stars { display: flex; gap: 2px; }
  .stars i { color: var(--star); font-size: 15px; }
  .stars i.far { color: #ddd; }
  .rating-count { font-size: 12px; color: var(--gray); }
  .rating-count a { color: var(--red); font-weight: 600; }

  /* ── AVAILABILITY ── */
  .avail-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
  .avail-badge { display: flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; border-radius: 4px; padding: 3px 10px; }
  .avail-badge.instock { background: #e8f5e9; color: var(--green); border: 1px solid #a5d6a7; }
  .avail-badge.rx { background: #fff3e0; color: #e65100; border: 1px solid #ffcc80; }
  .avail-badge.otc { background: #e8f5e9; color: var(--green); border: 1px solid #a5d6a7; }

  /* ── PRICE ── */
  .price-block { background: var(--gray-light); border-radius: 10px; padding: 16px 20px; }
  .price-block .old-price { text-decoration: line-through; color: var(--gray); font-size: 14px; }
  .price-block .current-price { font-family: 'Montserrat', sans-serif; font-size: 32px; font-weight: 900; color: var(--red); line-height: 1.1; }
  .discount-pill { display: inline-block; background: var(--red); color: #fff; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 12px; font-family: 'Montserrat', sans-serif; margin-left: 8px; vertical-align: middle; }
  .savings-note { font-size: 12px; color: var(--green); font-weight: 600; margin-top: 6px; }

  /* ── QTY & CART ── */
  .qty-cart-row { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
  .qty-control { display: flex; align-items: center; border: 2px solid var(--border); border-radius: 8px; overflow: hidden; }
  .qty-control button { width: 38px; height: 46px; border: none; background: #f5f5f5; font-size: 18px; font-weight: 800; cursor: pointer; color: var(--text); transition: background 0.15s; }
  .qty-control button:hover { background: #eee; }
  .qty-control input { width: 52px; height: 46px; border: none; border-left: 2px solid var(--border); border-right: 2px solid var(--border); text-align: center; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 15px; outline: none; }
  .qty-cart-row .add-cart-main { flex: 1; min-width: 160px; background: var(--red); color: #fff; border: none; border-radius: 8px; height: 46px; font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 13px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s; }
  .qty-cart-row .add-cart-main:hover { background: var(--dark-red); }
  .qty-cart-row .wishlist-btn { width: 46px; height: 46px; border: 2px solid var(--border); border-radius: 8px; background: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #aaa; transition: all 0.2s; flex-shrink: 0; }
  .qty-cart-row .wishlist-btn:hover, .qty-cart-row .wishlist-btn.active { border-color: var(--red); color: var(--red); }
  .buy-now-btn { width: 100%; height: 46px; background: var(--green); color: #fff; border: none; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 13px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s; }
  .buy-now-btn:hover { background: var(--dark-green); }

  /* ── GUARANTEES ── */
  .guarantees { display: flex; gap: 0; border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
  .guarantee-item { flex: 1; display: flex; align-items: center; gap: 10px; padding: 12px 14px; border-right: 1px solid var(--border); }
  .guarantee-item:last-child { border-right: none; }
  .guarantee-item i { font-size: 20px; color: var(--red); flex-shrink: 0; }
  .guarantee-item div { font-size: 11px; line-height: 1.4; }
  .guarantee-item div strong { font-weight: 700; display: block; font-size: 12px; }

  /* ── MEDICINE META TAGS ── */
  .med-tags { display: flex; gap: 8px; flex-wrap: wrap; }
  .med-tag { display: flex; align-items: center; gap: 5px; background: #f0f0f0; border-radius: 20px; padding: 5px 12px; font-size: 11px; font-weight: 600; color: #444; }
  .med-tag i { color: var(--red); font-size: 11px; }

  /* ══════════════════════════════════
     TABS SECTION
  ══════════════════════════════════ */
  .tabs-section { margin-top: 48px; }
  .tabs-nav { display: flex; gap: 0; border-bottom: 2px solid var(--border); overflow-x: auto; }
  .tabs-nav .tab-btn { padding: 13px 24px; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 13px; color: var(--gray); border: none; border-bottom: 3px solid transparent; background: none; cursor: pointer; white-space: nowrap; margin-bottom: -2px; transition: all 0.2s; }
  .tabs-nav .tab-btn:hover { color: var(--red); }
  .tabs-nav .tab-btn.active { color: var(--red); border-bottom-color: var(--red); }
  .tabs-section .tab-pane { display: none; padding: 28px 0; }
  .tabs-section .tab-pane.active { display: block; }

  /* ── DESCRIPTION TAB ── */
  .desc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; }
  .desc-text h3 { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 16px; color: var(--text); margin-bottom: 12px; }
  .desc-text p { color: var(--gray); font-size: 13px; line-height: 1.8; margin-bottom: 14px; }
  .info-table { width: 100%; border-collapse: collapse; }
  .info-table tr { border-bottom: 1px solid var(--border); }
  .info-table tr:last-child { border-bottom: none; }
  .info-table td { padding: 10px 12px; font-size: 13px; vertical-align: top; }
  .info-table td:first-child { font-weight: 700; color: var(--text); width: 40%; background: #f9f9f9; }
  .info-table td:last-child { color: var(--gray); }

  /* ── WARNINGS TAB ── */
  .warning-box { background: #fff8e1; border-left: 4px solid #f59e0b; border-radius: 0 8px 8px 0; padding: 14px 18px; font-size: 13px; color: #555; line-height: 1.7; margin-bottom: 16px; }
  .warning-box strong { color: #e65100; }
  .tab-body-text { font-size: 13px; color: #444; line-height: 1.85; }

  /* ── SPECIFICATIONS TAB ── */
  .spec-table { width: 100%; border-collapse: collapse; }
  .spec-table tr { border-bottom: 1px solid var(--border); }
  .spec-table tr:last-child { border-bottom: none; }
  .spec-table td { padding: 12px 16px; font-size: 13px; }
  .spec-table td:first-child { font-family: 'Montserrat', sans-serif; font-weight: 700; color: var(--text); width: 220px; background: #fafafa; }
  .spec-table td:last-child { color: #444; }

  /* Empty tab state */
  .tab-empty { text-align: center; padding: 40px 20px; color: #bbb; }
  .tab-empty i { font-size: 40px; display: block; margin-bottom: 12px; }
  .tab-empty p { font-size: 13px; }

  /* ══════════════════════════════════
     RATINGS & REVIEWS SECTION
  ══════════════════════════════════ */
  .reviews-section { margin-top: 48px; border-top: 2px solid var(--border); padding-top: 36px; }
  .reviews-section h2 { font-family: 'Montserrat', sans-serif; font-weight: 900; font-size: 20px; color: var(--text); margin-bottom: 24px; }

  .rating-overview-empty { display: flex; align-items: center; gap: 24px; background: var(--gray-light); border-radius: 14px; padding: 28px; margin-bottom: 28px; flex-wrap: wrap; }
  .rating-big { text-align: center; }
  .rating-big .num { font-family: 'Montserrat', sans-serif; font-weight: 900; font-size: 44px; color: var(--text); line-height: 1; }
  .rating-big .stars-lg { display: flex; justify-content: center; gap: 3px; margin: 6px 0; }
  .rating-big .stars-lg i { font-size: 20px; color: #ddd; }
  .rating-empty-text { font-size: 13px; color: var(--gray); line-height: 1.7; }
  .rating-empty-text strong { color: var(--text); }

  /* ── WRITE REVIEW ── */
  .write-review { background: var(--gray-light); border-radius: 14px; padding: 24px; }
  .write-review h3 { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 16px; color: var(--text); margin-bottom: 18px; }
  .star-picker { display: flex; gap: 6px; margin-bottom: 18px; }
  .star-picker i { font-size: 28px; color: #ddd; cursor: pointer; transition: color 0.15s; }
  .star-picker i.lit { color: var(--star); }
  .review-form { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .review-form .full { grid-column: 1 / -1; }
  .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--gray); margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.4px; }
  .form-group input, .form-group textarea { width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 10px 14px; font-size: 13px; font-family: 'Open Sans', sans-serif; outline: none; transition: border-color 0.2s; background: #fff; }
  .form-group input:focus, .form-group textarea:focus { border-color: var(--red); }
  .form-group textarea { resize: vertical; min-height: 90px; }
  .submit-review { background: var(--red); color: #fff; border: none; border-radius: 8px; padding: 12px 28px; font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 13px; cursor: pointer; transition: background 0.2s; }
  .submit-review:hover { background: var(--dark-red); }

  /* ══════════════════════════════════
     RELATED MEDICINES
  ══════════════════════════════════ */
  .related-section { margin-top: 48px; border-top: 2px solid var(--border); padding-top: 36px; }
  .related-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
  .related-header h2 { font-family: 'Montserrat', sans-serif; font-weight: 900; font-size: 20px; color: var(--red); }
  .see-all-link { font-size: 13px; font-weight: 700; color: var(--green); display: flex; align-items: center; gap: 5px; }
  .see-all-link:hover { color: var(--dark-green); }
  .related-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; }
  .rel-card { border: 1px solid var(--border); border-radius: 10px; overflow: hidden; background: #fff; transition: box-shadow 0.2s, transform 0.2s; display: block; }
  .rel-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); transform: translateY(-2px); }
  .rel-img { height: 150px; background: #fafafa; display: flex; align-items: center; justify-content: center; padding: 14px; position: relative; }
  .rel-img img { max-width: 110px; max-height: 110px; object-fit: contain; }
  .rel-badge { position: absolute; top: 8px; left: 8px; background: var(--red); color: #fff; font-size: 9px; font-weight: 800; padding: 2px 7px; border-radius: 3px; font-family: 'Montserrat', sans-serif; }
  .rel-badge.most-sold { background: var(--green); }
  .rel-info { padding: 12px; }
  .rel-cat { font-size: 9px; font-weight: 800; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
  .rel-name { font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; color: var(--text); line-height: 1.4; min-height: 32px; margin-bottom: 8px; }
  .rel-price { font-family: 'Montserrat', sans-serif; font-size: 15px; font-weight: 900; color: var(--red); }
  .rel-price .rel-old-price { text-decoration: line-through; color: var(--gray); font-size: 11px; font-weight: 400; margin-right: 4px; }

  /* ══════════════════════════════════
     RESPONSIVE (content section)
  ══════════════════════════════════ */
  @media (max-width: 1024px) {
    .med-top { grid-template-columns: 380px 1fr; gap: 28px; }
    .related-grid { grid-template-columns: repeat(3, 1fr); }
    .desc-grid { grid-template-columns: 1fr; }
  }
  @media (max-width: 768px) {
    .breadcrumb { padding: 10px 16px; }
    .med-page { padding: 16px 16px 40px; }
    .med-top { grid-template-columns: 1fr; gap: 20px; }
    .img-gallery { position: static; }
    .main-img-wrap { height: 260px; }
    .med-name { font-size: 20px; }
    .price-block .current-price { font-size: 26px; }
    .guarantees { flex-direction: column; }
    .guarantee-item { border-right: none; border-bottom: 1px solid var(--border); }
    .guarantee-item:last-child { border-bottom: none; }
    .tabs-nav .tab-btn { padding: 10px 14px; font-size: 12px; }
    .rating-overview-empty { flex-direction: column; text-align: center; }
    .review-form { grid-template-columns: 1fr; }
    .review-form .full { grid-column: 1; }
    .related-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
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

  /* ═══════════════════════════════════════════════════════════════════════
     CART PAGE — Shopee-style full-width table layout
     ═══════════════════════════════════════════════════════════════════════ */
  .cart-page { max-width: 100%; margin: 0 auto 90px; padding: 30px 40px 40px; }
  .cart-page-title { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 24px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
  .cart-page-title .count-pill { background: #f3f4f6; color: #4b5563; border: 1px solid #d5d8dc; font-size: 11px; border-radius: 3px; padding: 3px 10px; font-weight: 600; letter-spacing: 0.4px; text-transform: uppercase; }

  .cart-checkbox { width: 16px; height: 16px; accent-color: #374151; cursor: pointer; flex-shrink: 0; }

  /* ── Table shell ─────────────────────────────────────────────────────── */
  .cart-table-wrap { background: #fff; border: 1px solid #d5d8dc; border-radius: 4px; overflow: hidden; overflow-x: auto; }
  .cart-table { width: 100%; min-width: 900px; border-collapse: collapse; }

  .cart-table thead th {
    background: #f3f4f6;
    border-bottom: 1px solid #d5d8dc;
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    font-size: 11px;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    color: #4b5563;
    padding: 10px 14px;
    text-align: center;
    white-space: nowrap;
  }
  .cart-table thead th.col-check { width: 46px; padding-right: 0; }
  .cart-table thead th.col-product { text-align: left; padding-left: 8px; width: auto; }
  .cart-table thead th.col-price { width: 140px; text-align: right; }
  .cart-table thead th.col-qty { width: 170px; }
  .cart-table thead th.col-total { width: 140px; text-align: right; }
  .cart-table thead th.col-action { width: 90px; }

  .cart-item td { padding: 14px; border-top: 1px solid #e5e7eb; vertical-align: middle; transition: background 0.15s, opacity 0.25s ease, transform 0.25s ease; }
  .cart-item:first-child td { border-top: none; }
  .cart-item:hover td { background: #fafafa; }
  .cart-item.removing td { opacity: 0; }
  .cart-item.removing { transform: translateX(30px); }
  .cart-item td.col-check { text-align: center; padding-right: 0; }

  .cart-item-product { display: flex; align-items: center; gap: 14px; min-width: 0; padding-left: 0; }

  .cart-item-img { width: 48px; height: 48px; border-radius: 2px; object-fit: cover; border: 1px solid #e5e7eb; flex-shrink: 0; background: var(--gray-light); }

  .cart-item-details { min-width: 0; }
  .cart-item-name { font-weight: 600; font-size: 13px; color: var(--text); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 2px; }
  .cart-item-name:hover { text-decoration: underline; }
  .cart-item-meta { font-size: 12px; color: var(--gray); display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
  .cart-item-rx { color: #4b5563; font-weight: 600; font-size: 12px; }

  .cart-item td.col-price { text-align: right; }
  .cart-item-price .old { display: block; color: #9ca3af; text-decoration: line-through; font-size: 11px; font-weight: 400; }
  .cart-item-price .now { font-weight: 400; color: var(--gray); font-size: 12.5px; white-space: nowrap; }

  .cart-item td.col-qty { text-align: center; }
  .qty-stepper { display: flex; align-items: center; border: 1px solid #d5d8dc; border-radius: 3px; overflow: hidden; width: fit-content; margin: 0 auto; }
  .qty-stepper button { width: 28px; height: 28px; border: none; background: #fff; font-size: 14px; cursor: pointer; color: var(--text); }
  .qty-stepper button:hover { background: #f3f4f6; }
  .qty-stepper button:disabled { color: #ccc; cursor: not-allowed; background: #fff; }
  .qty-stepper input { width: 38px; height: 28px; border: none; border-left: 1px solid #d5d8dc; border-right: 1px solid #d5d8dc; text-align: center; font-size: 13px; font-family: 'Open Sans', sans-serif; -moz-appearance: textfield; }
  .qty-stepper input::-webkit-outer-spin-button, .qty-stepper input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

  .cart-item td.col-total { text-align: right; }
  .cart-item-subtotal { font-weight: 600; color: var(--text); font-size: 13px; white-space: nowrap; }

  .cart-item td.col-action { text-align: center; }
  .cart-item-remove { border: none; background: transparent; color: var(--gray); font-size: 12.5px; font-weight: 600; cursor: pointer; transition: color 0.15s; }
  .cart-item-remove:hover { color: var(--dark-red); text-decoration: underline; }

  /* Sticky bottom summary bar */
  .cart-summary-bar { position: fixed; bottom: 0; left: 0; right: 0; background: #f9fafb; border-top: 1px solid #d5d8dc; z-index: 500; }
  .cart-summary-inner { max-width: 100%; margin: 0 auto; padding: 12px 40px; display: flex; align-items: center; gap: 18px; }
  .cart-summary-select-all { display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 13px; white-space: nowrap; }
  .cart-summary-delete { background: #fff; border: 1px solid #d5d8dc; border-radius: 3px; color: #374151; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 12px; padding: 8px 16px; cursor: pointer; white-space: nowrap; }
  .cart-summary-delete:hover { background: #f3f4f6; border-color: #9ca3af; }
  .cart-summary-spacer { flex: 1; }
  .cart-summary-total { text-align: right; font-family: 'Montserrat', sans-serif; white-space: nowrap; }
  .cart-summary-total .label { font-size: 12px; color: #6b7280; font-weight: 600; }
  .cart-summary-total .amount { font-size: 20px; font-weight: 700; color: var(--text); line-height: 1.2; }
  .cart-checkout-btn { background: var(--green); color: #fff; border: 1px solid var(--dark-green); border-radius: 3px; padding: 0 26px; height: 42px; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 13px; letter-spacing: 0.3px; cursor: pointer; white-space: nowrap; transition: background 0.15s; }
  .cart-checkout-btn:hover { background: var(--dark-green); }
  .cart-checkout-btn:disabled { background: #e5e7eb; border-color: #d5d8dc; color: #9ca3af; cursor: not-allowed; }

  .cart-empty-state { background: #fff; border: 1px solid #d5d8dc; border-radius: 4px; padding: 60px 20px; text-align: center; }
  .cart-empty-state i { font-size: 40px; color: #d1d5db; margin-bottom: 14px; }
  .cart-empty-state h2 { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 8px; }
  .cart-empty-state p { color: var(--gray); font-size: 13px; margin-bottom: 20px; }
  .cart-empty-state .shop-now-btn { display: inline-block; background: var(--green); color: #fff; border: 1px solid var(--dark-green); font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 13px; padding: 11px 26px; border-radius: 3px; }
  .cart-empty-state .shop-now-btn:hover { background: var(--dark-green); }

  @media (max-width: 768px) {
    .cart-page { padding: 20px 12px 30px; margin-bottom: 150px; }
    .cart-page-title { font-size: 20px; }

    /* Table stays a real table but scrolls sideways on small screens. */
    .cart-table-wrap { -webkit-overflow-scrolling: touch; }

    .cart-summary-inner { padding: 10px 12px; gap: 10px; flex-wrap: wrap; }
    .cart-summary-select-all { font-size: 12px; }
    .cart-summary-spacer { display: none; }
    .cart-summary-total { flex: 1; text-align: left; }
    .cart-summary-total .amount { font-size: 17px; }
    .cart-checkout-btn { padding: 0 20px; height: 40px; font-size: 13px; }
  }
</style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════════════════════════════
     HEADER — identical to home.blade.php
     ═══════════════════════════════════════════════════════════════════════════ --}}

<!-- TOP BAR -->
<div class="top-bar">
  <a href="#" class="store-finder"><i class="fas fa-map-marker-alt"></i> STORE INFO</a>
  <span class="sep">|</span>
  <a href="#" class="track-order"><i class="fas fa-truck"></i> TRACK YOUR ORDER</a>
  <span class="sep">|</span>
  @guest
    <a href="#" id="loginBtn"><i class="fas fa-user"></i> LOG IN</a>
    <span class="sep">|</span>
    <a href="#" id="registerBtn"><i class="fas fa-user-plus"></i> REGISTER</a>
  @else

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

    <a href="{{ route('cart.index') }}" class="cart-btn">
      <div style="position:relative;">
        <i class="fas fa-shopping-cart icon-btn" id="cartIcon"></i>
        <div class="cart-badge" style="position:absolute;top:-8px;right:-8px;">0</div>
      </div>
    </a>
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
    <li>
      <a href="{{ route('home') }}" class="{{ !request()->filled('category_id') && !request()->filled('query') ? 'active' : '' }}">HOME</a>
    </li>
    @forelse(($categories ?? []) as $cat)
      <li class="{{ request('category_id') == $cat->id ? 'active' : '' }}">
        <a href="{{ route('home', ['category_id' => $cat->id]) }}">
          {{ strtoupper($cat->name ?? '') }}
        </a>
      </li>
    @empty
      @for($i = 0; $i < 6; $i++)
        <li>
          <a href="#">{{ 'NO CATEGORIES' ?? '' }}</a>
        </li>
      @endfor
    @endforelse
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
     CART PAGE CONTENT
     ═══════════════════════════════════════════════════════════════════════════ --}}

<div class="cart-page">

  <h1 class="cart-page-title">
    <i class="fas fa-shopping-cart" style="color:var(--red);"></i> My Cart
    @if($cartItems->isNotEmpty())
      <span class="count-pill" id="cartPageCount">{{ $cartItems->count() }} {{ $cartItems->count() === 1 ? 'item' : 'items' }}</span>
    @endif
  </h1>

  @if($cartItems->isEmpty())

    <div class="cart-empty-state">
      <i class="fas fa-shopping-cart"></i>
      <h2>Your cart is empty</h2>
      <p>Looks like you haven't added anything yet. Let's fix that!</p>
      <a href="{{ route('home') }}" class="shop-now-btn"><i class="fas fa-store"></i> Continue Shopping</a>
    </div>

  @else

    <div class="cart-table-wrap">
      <table class="cart-table" id="cartList">
        <thead>
          <tr>
            <th class="col-check"><input type="checkbox" class="cart-checkbox" id="selectAllTop"></th>
            <th class="col-product">Product</th>
            <th class="col-price">Unit Price</th>
            <th class="col-qty">Quantity</th>
            <th class="col-total">Total Price</th>
            <th class="col-action">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cartItems as $item)
            @php $product = $item->product; @endphp
            <tr class="cart-item" data-cart-id="{{ $item->id }}" data-price="{{ $product->price }}">

              <td class="col-check">
                <input type="checkbox" class="cart-checkbox cart-item-checkbox">
              </td>

              <td class="col-product">
                <div class="cart-item-product">
                  <a href="{{ route('product.show', $product) }}">
                    <img class="cart-item-img" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                  </a>
                  <div class="cart-item-details">
                    <a href="{{ route('product.show', $product) }}" class="cart-item-name">{{ $product->name }}</a>
                    <div class="cart-item-meta">
                      @if($product->brand)<span>{{ $product->brand->name }}</span>@endif
                      @if($product->requires_prescription)
                        <span class="cart-item-rx"><i class="fas fa-prescription"></i> Rx Required</span>
                      @endif
                    </div>
                  </div>
                </div>
              </td>

              <td class="col-price">
                <div class="cart-item-price">
                  @if($product->hasDiscount())
                    <span class="old">{{ $product->formattedOldPrice() }}</span>
                  @endif
                  <span class="now">{{ $product->formattedPrice() }}</span>
                </div>
              </td>

              <td class="col-qty">
                <div class="qty-stepper">
                  <button type="button" class="qty-minus" {{ $item->quantity <= 1 ? 'disabled' : '' }}>−</button>
                  <input type="number" class="qty-input" value="{{ $item->quantity }}" min="1" max="99" inputmode="numeric">
                  <button type="button" class="qty-plus" {{ $item->quantity >= 99 ? 'disabled' : '' }}>+</button>
                </div>
              </td>

              <td class="col-total">
                <div class="cart-item-subtotal">₱{{ number_format($item->subtotal(), 2) }}</div>
              </td>

              <td class="col-action">
                <button type="button" class="cart-item-remove" title="Remove from cart" aria-label="Remove from cart">Delete</button>
              </td>

            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- STICKY BOTTOM SUMMARY BAR -->
    <div class="cart-summary-bar">
      <div class="cart-summary-inner">
        <div class="cart-summary-select-all">
          <input type="checkbox" class="cart-checkbox" id="selectAllBottom">
          <label for="selectAllBottom" style="cursor:pointer;">All</label>
        </div>
        <button type="button" class="cart-summary-delete" id="deleteSelectedBtn"><i class="fas fa-trash-alt"></i> Delete Selected</button>
        <div class="cart-summary-spacer"></div>
        <div class="cart-summary-total">
          <div class="label"><span id="selectedCount">{{ $cartItems->count() }}</span> item(s) selected</div>
          <div class="amount">₱<span id="cartGrandTotal">{{ number_format($cartTotal, 2) }}</span></div>
        </div>
        <button type="button" class="cart-checkout-btn" id="checkoutBtn">Checkout</button>
      </div>
    </div>

    {{-- Posts the checked cart-row ids straight to the order-review page
         (myorder.blade.php). The cart page is already auth-only, so no
         guest gating is needed here. --}}
    <form id="checkoutForm" action="{{ route('order.checkout') }}" method="POST" style="display:none;">
      @csrf
      <div id="checkoutIdsContainer"></div>
    </form>

  @endif

</div><!-- /.cart-page -->

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
      <h4>ABOUT ['site_name']</h4>
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

{{-- Shared add-to-cart logic: POSTs to /cart/add, updates .cart-badge elements,
     and fires cart:guest / cart:added / cart:error events on document. --}}
<script src="{{ asset('js/cart.js') }}" data-add-url="{{ route('cart.add') }}" data-count-url="{{ route('cart.count') }}"></script>

<script>
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

  // Key used to remember a guest's "add to cart" click across the
  // login/register redirect (declared up here so the manual-login buttons
  // below can clear it — see the note further down for the full flow).
  const PENDING_CART_KEY = 'pendingCartItem';
  function clearPendingCartItem() {
    try { localStorage.removeItem(PENDING_CART_KEY); } catch (e) {}
  }

  const loginBtn         = document.getElementById('loginBtn');
  const registerBtn      = document.getElementById('registerBtn');
  const userIconLoginBtn = document.getElementById('userIconLoginBtn');
  // These are manual, deliberate "I want to log in/register" clicks from the
  // header — nothing to do with any product. Clear out any pending cart item
  // left over from an earlier, abandoned "add to cart while logged out"
  // attempt, so that logging in here never sneaks that old item into the
  // cart. (The item is only re-remembered by the 'cart:guest' handler below,
  // which fires when this same modal is opened *because* of an add-to-cart
  // click.)
  if (loginBtn)         loginBtn.addEventListener('click',         e => { e.preventDefault(); clearPendingCartItem(); openModal(loginModal); });
  if (registerBtn)      registerBtn.addEventListener('click',      e => { e.preventDefault(); clearPendingCartItem(); openModal(registerModal); });
  if (userIconLoginBtn) userIconLoginBtn.addEventListener('click', e => { e.preventDefault(); clearPendingCartItem(); openModal(loginModal); });

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

  // ── Cart: guest gating + auto add-to-cart after login/register ─────────────
  // cart.js already POSTs /cart/add for every .js-add-to-cart click. When the
  // visitor isn't logged in, CartController responds 401/{status:'guest'} and
  // cart.js turns that into a 'cart:guest' event here. We remember which
  // product (and quantity) was clicked in localStorage — so it survives the
  // full-page redirect the login/register forms do — open the login modal,
  // and on the next page load, if the user is now authenticated, replay the
  // add-to-cart call automatically.
  function rememberPendingCartItem(productId, quantity) {
    if (!productId) return;
    try {
      localStorage.setItem(PENDING_CART_KEY, JSON.stringify({ product_id: productId, quantity: quantity || 1 }));
    } catch (e) {
      // localStorage unavailable (private browsing, etc.) — the visitor can
      // just click "Add to Cart" again after logging in.
    }
  }

  document.addEventListener('cart:guest', (e) => {
    rememberPendingCartItem(e.detail?.product_id, e.detail?.quantity);
    openModal(loginModal);
  });

  // ── Add-to-cart toast + "flying image" animation ────────────────────────────
  // We record which .js-add-to-cart button was clicked (capture phase, so
  // this always runs before cart.js's own handler), then once cart.js
  // confirms success via 'cart:added' we animate that product's image
  // flying up into the header cart icon and show a toast.
  let lastAddToCartTrigger = null;

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.js-add-to-cart');
    if (btn) lastAddToCartTrigger = btn;
  }, true);

  function findProductImageFor(btn) {
    const mainImg = document.getElementById('mainImg'); // product detail page
    if (!btn) return mainImg || null;
    const card = btn.closest('.product-card'); // product cards / carousels
    if (card) {
      const cardImg = card.querySelector('.product-img img, img');
      if (cardImg) return cardImg;
    }
    return mainImg || document.querySelector('.product-img img, img');
  }

  function showCartToast(message) {
    let container = document.getElementById('cartToastContainer');
    if (!container) {
      container = document.createElement('div');
      container.id = 'cartToastContainer';
      container.className = 'cart-toast-container';
      document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = 'cart-toast';
    toast.innerHTML = '<i class="fas fa-check-circle"></i><span></span>';
    toast.querySelector('span').textContent = message;
    container.appendChild(toast);

    requestAnimationFrame(() => toast.classList.add('show'));

    setTimeout(() => {
      toast.classList.remove('show');
      toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    }, 2800);
  }

  function flyToCart(sourceImg) {
    const cartIcon = document.getElementById('cartIcon');
    if (!sourceImg || !cartIcon) return;

    const startRect = sourceImg.getBoundingClientRect();
    const endRect = cartIcon.getBoundingClientRect();
    if (!startRect.width || !startRect.height) return;

    const flyer = sourceImg.cloneNode(true);
    flyer.className = 'fly-to-cart-img';
    flyer.style.width = startRect.width + 'px';
    flyer.style.height = startRect.height + 'px';
    flyer.style.left = startRect.left + 'px';
    flyer.style.top = startRect.top + 'px';
    document.body.appendChild(flyer);

    // Force layout so the transform below is picked up as an actual change.
    void flyer.offsetWidth;

    const endX = (endRect.left + endRect.width / 2) - (startRect.left + startRect.width / 2);
    const endY = (endRect.top + endRect.height / 2) - (startRect.top + startRect.height / 2);

    flyer.style.transform = `translate(${endX}px, ${endY}px) scale(0.12)`;
    flyer.style.opacity = '0.25';

    flyer.addEventListener('transitionend', () => {
      flyer.remove();
      cartIcon.classList.add('cart-bump');
      setTimeout(() => cartIcon.classList.remove('cart-bump'), 400);
    }, { once: true });
  }

  document.addEventListener('cart:added', (e) => {
    flyToCart(findProductImageFor(lastAddToCartTrigger));
    showCartToast(e.detail?.message || 'Added to cart.');
  });

  document.addEventListener('cart:error', (e) => {
    // Visible, not just console — a silent failure here is why "add to
    // cart" can look broken with no clue why. Swap for a toast if the site
    // has one.
    alert(e.detail?.message || 'Could not add item to cart. Please try again.');
  });

  @auth
  // Only present in the markup when the visitor is actually logged in, so
  // this only ever fires right after a successful login/register redirect
  // (or on any later page load while a pending item is still queued).
  document.addEventListener('DOMContentLoaded', () => {
    let pending = null;
    try { pending = JSON.parse(localStorage.getItem(PENDING_CART_KEY) || 'null'); } catch (e) { pending = null; }

    if (pending && pending.product_id) {
      localStorage.removeItem(PENDING_CART_KEY);
      window.CartUI.addToCart(pending.product_id, pending.quantity || 1);
    }
  });
  @endauth

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
<script>
  // ── Cart page interactions (select all, qty steppers, remove, checkout) ────
  // Only runs on the /cart page (guarded by #cartList existing). Relies on
  // showCartToast(), which is already defined above for the add-to-cart flow.
  (function () {
    const cartList = document.getElementById('cartList');
    if (!cartList) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const selectAllTop = document.getElementById('selectAllTop');
    const selectAllBottom = document.getElementById('selectAllBottom');
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const selectedCountEl = document.getElementById('selectedCount');
    const grandTotalEl = document.getElementById('cartGrandTotal');
    const cartPageCount = document.getElementById('cartPageCount');

    function itemRows() {
      return Array.from(cartList.querySelectorAll('.cart-item'));
    }

    function formatPeso(amount) {
      return amount.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updateCartBadges(count) {
      if (typeof count === 'undefined') return;
      document.querySelectorAll('.cart-badge').forEach(b => { b.textContent = count; });
    }

    function recalcSummary() {
      const rows = itemRows();
      let selectedCount = 0;
      let total = 0;

      rows.forEach(row => {
        const checkbox = row.querySelector('.cart-item-checkbox');
        const price = parseFloat(row.dataset.price) || 0;
        const qty = parseInt(row.querySelector('.qty-input').value, 10) || 1;
        if (checkbox.checked) {
          selectedCount++;
          total += price * qty;
        }
      });

      if (selectedCountEl) selectedCountEl.textContent = selectedCount;
      if (grandTotalEl) grandTotalEl.textContent = formatPeso(total);
      if (checkoutBtn) checkoutBtn.disabled = selectedCount === 0;

      const allChecked = rows.length > 0 && rows.every(row => row.querySelector('.cart-item-checkbox').checked);
      [selectAllTop, selectAllBottom].forEach(cb => { if (cb) cb.checked = allChecked; });
    }

    function setAllChecked(checked) {
      itemRows().forEach(row => { row.querySelector('.cart-item-checkbox').checked = checked; });
      recalcSummary();
    }

    [selectAllTop, selectAllBottom].forEach(cb => {
      if (cb) cb.addEventListener('change', () => setAllChecked(cb.checked));
    });

    function updateItemSubtotalDisplay(row) {
      const price = parseFloat(row.dataset.price) || 0;
      const qty = parseInt(row.querySelector('.qty-input').value, 10) || 1;
      const subtotalEl = row.querySelector('.cart-item-subtotal');
      if (subtotalEl) subtotalEl.textContent = '\u20b1' + formatPeso(price * qty);
    }

    function sendQuantityUpdate(row, quantity) {
      const cartId = row.dataset.cartId;
      fetch(`/cart/${cartId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ quantity }),
      })
        .then(res => { if (!res.ok) throw new Error('update failed'); return res.json(); })
        .then(data => {
          updateCartBadges(data.cart_count);
          updateItemSubtotalDisplay(row);
          recalcSummary();
        })
        .catch(() => showCartToast('Could not update quantity. Please try again.'));
    }

    function stepQuantity(row, direction) {
      const input = row.querySelector('.qty-input');
      let value = parseInt(input.value, 10) || 1;
      value = direction === 'minus' ? Math.max(1, value - 1) : Math.min(99, value + 1);
      input.value = value;
      row.querySelector('.qty-minus').disabled = value <= 1;
      row.querySelector('.qty-plus').disabled = value >= 99;
      updateItemSubtotalDisplay(row);
      recalcSummary();
      sendQuantityUpdate(row, value);
    }

    function removeCartItem(row) {
      const cartId = row.dataset.cartId;
      fetch(`/cart/${cartId}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      })
        .then(res => { if (!res.ok) throw new Error('remove failed'); return res.json(); })
        .then(data => {
          updateCartBadges(data.cart_count);
          row.classList.add('removing');
          row.addEventListener('transitionend', () => {
            row.remove();
            const remaining = itemRows().length;
            if (cartPageCount) cartPageCount.textContent = remaining + (remaining === 1 ? ' item' : ' items');
            if (remaining === 0) { window.location.reload(); return; }
            recalcSummary();
          }, { once: true });
          showCartToast('Item removed from cart.');
        })
        .catch(() => showCartToast('Could not remove item. Please try again.'));
    }

    cartList.addEventListener('click', (e) => {
      const minusBtn = e.target.closest('.qty-minus');
      const plusBtn = e.target.closest('.qty-plus');
      const removeBtn = e.target.closest('.cart-item-remove');
      if (minusBtn) stepQuantity(e.target.closest('.cart-item'), 'minus');
      if (plusBtn) stepQuantity(e.target.closest('.cart-item'), 'plus');
      if (removeBtn) removeCartItem(e.target.closest('.cart-item'));
    });

    cartList.addEventListener('change', (e) => {
      if (e.target.classList.contains('cart-item-checkbox')) {
        recalcSummary();
        return;
      }
      if (!e.target.classList.contains('qty-input')) return;

      const row = e.target.closest('.cart-item');
      let value = parseInt(e.target.value, 10);
      if (isNaN(value) || value < 1) value = 1;
      if (value > 99) value = 99;
      e.target.value = value;
      row.querySelector('.qty-minus').disabled = value <= 1;
      row.querySelector('.qty-plus').disabled = value >= 99;
      updateItemSubtotalDisplay(row);
      recalcSummary();
      sendQuantityUpdate(row, value);
    });

    if (deleteSelectedBtn) {
      deleteSelectedBtn.addEventListener('click', () => {
        const selectedRows = itemRows().filter(row => row.querySelector('.cart-item-checkbox').checked);
        if (selectedRows.length === 0) {
          showCartToast('Select at least one item to delete.');
          return;
        }
        if (!confirm(`Remove ${selectedRows.length} item(s) from your cart?`)) return;

        const ids = selectedRows.map(row => row.dataset.cartId);
        fetch('/cart/selected', {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
          },
          body: JSON.stringify({ ids }),
        })
          .then(res => { if (!res.ok) throw new Error('bulk remove failed'); return res.json(); })
          .then(data => {
            updateCartBadges(data.cart_count);
            selectedRows.forEach(row => row.remove());
            const remaining = itemRows().length;
            if (cartPageCount) cartPageCount.textContent = remaining + (remaining === 1 ? ' item' : ' items');
            if (remaining === 0) { window.location.reload(); return; }
            recalcSummary();
            showCartToast('Selected items removed from cart.');
          })
          .catch(() => showCartToast('Could not remove selected items. Please try again.'));
      });
    }

    if (checkoutBtn) {
      checkoutBtn.addEventListener('click', () => {
        const selectedRows = itemRows().filter(row => row.querySelector('.cart-item-checkbox').checked);
        if (selectedRows.length === 0) {
          showCartToast('Select at least one item to checkout.');
          return;
        }

        const checkoutForm = document.getElementById('checkoutForm');
        const idsContainer = document.getElementById('checkoutIdsContainer');
        idsContainer.innerHTML = '';
        selectedRows.forEach(row => {
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'cart_ids[]';
          input.value = row.dataset.cartId;
          idsContainer.appendChild(input);
        });
        checkoutForm.submit();
      });
    }

    recalcSummary();
  })();
</script>

</body>
</html>