<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>My Orders – {{ $settings['site_name'] ?? 'No Pharmacy Name' }}</title>
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

  /* TOP BAR */
  .top-bar { background: #fff; border-bottom: 1px solid var(--border); padding: 6px 40px; display: flex; justify-content: flex-end; align-items: center; gap: 20px; font-size: 12px; }
  .top-bar a { color: #333; font-weight: 600; display: flex; align-items: center; gap: 5px; }
  .top-bar a:hover { color: var(--red); }
  .top-bar .sep { color: #ccc; }
  .top-bar .social { display: flex; gap: 10px; margin-left: 10px; }
  .top-bar .social a { width: 24px; height: 24px; border-radius: 50%; background: #1877f2; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; }
  .top-bar .social a.insta { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fd5949 45%, #d6249f 60%, #285aeb 90%); }

  /* HEADER */
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

  /* NOTIFICATION DROPDOWN */
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

  /* NAV */
  nav { background: var(--red); border-bottom: 2px solid var(--dark-red); }
  nav ul { display: flex; align-items: center; list-style: none; padding: 0 40px; }
  nav ul li { position: relative; }
  nav ul li > a { display: block; padding: 14px 16px; font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700; color: #fff; white-space: nowrap; }
  nav ul li > a:hover, nav ul li.active > a { color: #ffd600; }
  nav ul li > a .fa-chevron-down { font-size: 9px; margin-left: 4px; }

  /* HAMBURGER */
  .hamburger { display: none; background: none; border: none; cursor: pointer; padding: 10px 16px; color: #fff; font-size: 22px; margin-left: auto; }

  /* GREEN STRIP */
  .green-strip { background: var(--green); padding: 5px 40px; font-size: 11px; color: #fff; font-weight: 700; text-align: center; }

  /* BRANDS TICKER */
  .brands-ticker { background: #fff; padding: 16px 40px; border-bottom: 1px solid var(--border); }
  .brands-row { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: center; }
  .brand-logo { height: 36px; object-fit: contain; filter: grayscale(30%); opacity: 0.8; font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 13px; padding: 4px 10px; border-radius: 4px; display: inline-flex; align-items: center; }

  /* SECTION HEADERS */
  .section-header { padding: 40px 40px 20px; }
  .section-header h2 { font-family: 'Montserrat', sans-serif; font-size: 22px; font-weight: 800; color: var(--text); }
  .section-header p { color: var(--gray); font-size: 13px; margin-top: 4px; }

  /* CATEGORY GRID */
  .category-grid { display: flex; gap: 20px; padding: 0 40px 40px; overflow-x: auto; }
  .cat-item { text-align: center; flex: 0 0 120px; }
  .cat-circle { width: 120px; height: 120px; border-radius: 50%; background: #f5f5f5; display: flex; align-items: center; justify-content: center; overflow: hidden; margin: 0 auto 10px; border: 3px solid #eee; }
  .cat-circle img { width: 80px; height: 80px; object-fit: contain; }
  .cat-item span { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 12px; color: var(--text); }

  /* PROMO BANNERS */
  .promo-banners { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 0 40px 40px; }
  .promo-banner { border-radius: 10px; overflow: hidden; display: flex; align-items: center; padding: 30px; min-height: 160px; }
  .promo-banner.red { background: var(--red); }
  .promo-banner.green-promo { background: var(--green); }
  .promo-banner.beige { background: #fff3e0; border: 2px solid #ffd700; }

  /* FEATURED BRANDS */
  .featured-brands { padding: 0 40px 40px; }
  .brands-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; }
  .brand-card { border-radius: 8px; overflow: hidden; height: 130px; display: flex; align-items: center; justify-content: center; cursor: pointer; }
  .brand-card.orange { background: #e65100; }
  .brand-card.darkred { background: var(--dark-red); }
  .brand-card.darkgreen { background: var(--green); }
  .brand-card.white { background: #fff; border: 2px solid var(--border); }
  .brand-card.green { background: var(--green); }
  .brand-card.salmon { background: #fff; border: 2px solid var(--border); }
  .brand-card span { font-family: 'Montserrat', sans-serif; font-weight: 900; font-size: 18px; color: #fff; text-align: center; padding: 10px; }
  .brand-card.white span, .brand-card.salmon span { color: var(--text); }

  /* OMRON BANNER */
  .fw-banner { margin: 0 40px 40px; border-radius: 12px; overflow: hidden; }

  /* HOT DEALS */
  .hot-deals { padding: 0 40px 40px; }
  .count-block { background: #f5f5f5; border-radius: 6px; padding: 6px 12px; font-family: 'Montserrat', sans-serif; font-size: 22px; font-weight: 900; color: var(--red); }
  .count-label { font-size: 11px; color: var(--gray); font-weight: 600; }

  /* PRODUCT CAROUSELS */
  .product-carousel { display: flex; gap: 16px; overflow-x: auto; padding-bottom: 10px; }
  .product-card { flex: 0 0 200px; border: 1px solid var(--border); border-radius: 8px; overflow: hidden; background: #fff; transition: box-shadow 0.2s; }
  .product-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
  .product-img { height: 180px; display: flex; align-items: center; justify-content: center; background: #fafafa; padding: 16px; position: relative; }
  .product-img img { max-width: 140px; max-height: 140px; object-fit: contain; }
  .badge { position: absolute; top: 10px; left: 10px; background: var(--red); color: #fff; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 4px; font-family: 'Montserrat', sans-serif; }
  .badge.sale-badge { background: #e65100; }
  .product-info { padding: 12px; }
  .product-cat { font-size: 9px; font-weight: 800; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
  .product-name { font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; color: var(--text); margin-bottom: 8px; line-height: 1.4; min-height: 34px; }
  .product-price { font-family: 'Montserrat', sans-serif; font-size: 16px; font-weight: 900; color: var(--red); margin-bottom: 10px; }
  .product-price .old-price { text-decoration: line-through; color: var(--gray); font-size: 12px; font-weight: 400; margin-right: 6px; }
  .qty-add { display: flex; align-items: center; gap: 8px; }
  .qty-btn { width: 28px; height: 28px; border: 2px solid var(--red); background: #fff; color: var(--red); font-size: 16px; font-weight: 800; border-radius: 4px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
  .qty-input { width: 36px; text-align: center; border: 1px solid var(--border); border-radius: 4px; padding: 4px; font-weight: 700; }
  .add-cart-btn { flex: 1; background: var(--red); color: #fff; border: none; border-radius: 4px; padding: 8px; font-size: 11px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px; font-family: 'Montserrat', sans-serif; }
  .add-cart-btn:hover { background: var(--dark-red); }

  /* SECTION WITH SEE ALL */
  .section-row { display: flex; align-items: flex-end; justify-content: space-between; padding: 30px 40px 16px; }
  .section-row h2 { font-family: 'Montserrat', sans-serif; font-size: 22px; font-weight: 800; color: var(--red); }
  .section-row p { color: var(--gray); font-size: 12px; margin-top: 2px; }
  .see-all-btn { background: var(--green); color: #fff; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 13px; padding: 10px 24px; border-radius: 6px; white-space: nowrap; }
  .see-all-btn:hover { background: var(--dark-green); }

  /* PRODUCT SECTION WRAPPER */
  .product-section { padding: 0 40px 40px; }
  .dual-row-wrap { padding: 0 40px 40px; }
  .dual-row-grid { display: grid; grid-template-columns: 280px repeat(4,1fr); gap: 16px; }
  .dual-row-bottom-grid { grid-column: 2 / span 4; display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-top: 4px; }

  /* MOST SOLD BADGE */
  .most-sold { background: var(--green); color: #fff; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 4px; font-family: 'Montserrat', sans-serif; position: absolute; top: 10px; left: 10px; }

  /* SCROLLBAR */
  .product-carousel::-webkit-scrollbar { height: 4px; }
  .product-carousel::-webkit-scrollbar-track { background: #f5f5f5; }
  .product-carousel::-webkit-scrollbar-thumb { background: var(--red); border-radius: 4px; }

  /* FEATURED PRODUCTS */
  .featured-section { background: var(--red); padding: 40px; margin-bottom: 40px; }
  .featured-section h2 { font-family: 'Montserrat', sans-serif; font-size: 22px; font-weight: 900; color: #fff; text-align: center; letter-spacing: 2px; margin-bottom: 24px; }
  .featured-section .product-carousel .product-card { background: rgba(255,255,255,0.95); }

  /* EXCLUSIVELY FOR YOU */
  .exclusively { text-align: center; padding: 30px 40px; }
  .exclusively h2 { font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 900; color: var(--red); letter-spacing: 2px; }

  /* BLOGS */
  .blogs { padding: 0 40px 40px; }
  .blogs h2 { font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 900; letter-spacing: 2px; text-align: center; margin-bottom: 24px; color: var(--text); }
  .blogs-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
  .blog-card { border: 1px solid var(--border); border-radius: 8px; overflow: hidden; }
  .blog-date { background: var(--red); color: #fff; width: 50px; height: 50px; display: flex; flex-direction: column; align-items: center; justify-content: center; font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 900; min-width: 50px; }
  .blog-date small { font-size: 10px; font-weight: 700; }
  .blog-img { height: 150px; background: #f0f0f0; overflow: hidden; }
  .blog-img img { width: 100%; height: 100%; object-fit: cover; }
  .blog-body { padding: 14px; }
  .blog-body h4 { font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
  .blog-body p { font-size: 12px; color: var(--gray); line-height: 1.5; }
  .blog-body .no-comments { font-size: 11px; color: #aaa; margin-top: 10px; }

  /* FOOTER */
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

  /* FREE SHIPPING BAR */
  .shipping-bar { background: var(--green); color: #fff; padding: 10px; text-align: center; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 10px; position: sticky; bottom: 0; z-index: 100; }
  .shipping-bar a { color: #ffd600; font-weight: 800; }

  /* LOGIN MODAL */
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

  /* REGISTER MODAL */
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
  .modal-field { margin-bottom: 14px; }
  .modal-field label { display: block; font-size: 11px; font-weight: 700; color: #555; margin-bottom: 5px; font-family: 'Montserrat', sans-serif; letter-spacing: 0.3px; }
  .input-icon-wrap { position: relative; }
  .input-icon-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #bbb; font-size: 13px; pointer-events: none; }
  .input-icon-wrap input { width: 100%; padding: 10px 14px 10px 36px; border: 1.5px solid var(--border); border-radius: 6px; font-size: 13px; outline: none; transition: border-color 0.2s; font-family: 'Open Sans', sans-serif; background: #fafafa; }
  .input-icon-wrap input:focus { border-color: var(--red); background: #fff; }
  .modal-field textarea { width: 100%; padding: 10px 14px 10px 36px; border: 1.5px solid var(--border); border-radius: 6px; font-size: 13px; outline: none; resize: none; font-family: 'Open Sans', sans-serif; background: #fafafa; transition: border-color 0.2s; }
  .modal-field textarea:focus { border-color: var(--red); background: #fff; }
  .textarea-icon-wrap { position: relative; }
  .textarea-icon-wrap i { position: absolute; left: 12px; top: 12px; color: #bbb; font-size: 13px; pointer-events: none; }
  .textarea-icon-wrap textarea { padding-left: 36px; }

  @media (max-width: 560px) { .modal-fields-row { grid-template-columns: 1fr; } }

  /* FORM VALIDATION STYLES */
  .input-error { border-color: var(--red) !important; background: #fff5f5 !important; }
  .modal-alert-error { background: #fff0f0; border: 1px solid #ffc5c5; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; font-size: 12px; color: var(--red); display: flex; flex-direction: column; gap: 4px; }
  .modal-alert-error i { margin-right: 5px; }
  @media (max-height: 680px) {
    .modal-box.wide { padding: 20px 28px 18px; }
    .reg-avatar-ring { width: 70px; height: 70px; }
    .modal-box.wide .modal-logo { margin-bottom: 8px; }
    .modal-box.wide .modal-subtitle { margin-bottom: 16px; }
    .modal-field { margin-bottom: 10px; }
  }

  /* IMAGE SLIDER */
  .image-slider { position: relative; width: 100%; height: 380px; overflow: hidden; background: #f5f5f5; }
  .slider-container { position: relative; width: 100%; height: 100%; }
  .slide { position: absolute; width: 100%; height: 100%; opacity: 0; transition: opacity 0.8s ease-in-out; top: 0; left: 0; }
  .slide.active { opacity: 1; }
  .slide img { width: 100%; height: 100%; object-fit: cover; }
  .slider-controls { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 10px; z-index: 10; }
  .slider-dot { width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.5); cursor: pointer; border: 2px solid transparent; transition: all 0.3s; }
  .slider-dot.active { background: #fff; border-color: var(--red); }
  .slider-arrow { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: #fff; border: none; padding: 12px 16px; font-size: 18px; cursor: pointer; z-index: 10; transition: background 0.3s; }
  .slider-arrow:hover { background: rgba(0,0,0,0.8); }
  .slider-arrow.prev { left: 20px; }
  .slider-arrow.next { right: 20px; }

  @media (max-width: 1024px) {
    .brands-grid { grid-template-columns: repeat(3, 1fr); }
    .footer-grid { grid-template-columns: 1fr 1fr; }
    .blogs-grid { grid-template-columns: 1fr 1fr; }
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
    .image-slider { height: 200px; }
    .slider-arrow { padding: 8px 10px; font-size: 14px; }
    .brands-ticker { padding: 12px 16px; }
    .brands-row { gap: 10px; }
    .brand-logo { height: 26px; padding: 3px 6px; }
    .section-header { padding: 24px 16px 14px; }
    .section-header h2 { font-size: 18px; }
    .category-grid { padding: 0 16px 24px; gap: 12px; }
    .cat-item { flex: 0 0 90px; }
    .cat-circle { width: 80px; height: 80px; }
    .cat-circle i { font-size: 28px !important; }
    .promo-banners { grid-template-columns: 1fr; padding: 0 16px 24px; gap: 12px; }
    .promo-banner { padding: 20px; min-height: 130px; }
    .featured-brands, .section-header + .featured-brands { padding: 0 16px 24px; }
    .brands-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .brand-card { height: 100px; }
    .fw-banner { margin: 0 16px 24px; }
    .fw-banner > div { flex-direction: column !important; padding: 24px 20px !important; gap: 16px; text-align: center; }
    .fw-banner h2 { font-size: 18px !important; }
    .count-block { font-size: 16px; padding: 4px 8px; }
    .count-label { font-size: 9px; }
    .product-section { padding: 0 16px 24px; }
    .dual-row-wrap { padding: 0 16px 24px; }
    .dual-row-grid { grid-template-columns: 1fr; }
    .dual-row-bottom-grid { grid-column: 1; grid-template-columns: 1fr; margin-top: 0; }
    .dual-row-grid .product-card,
    .dual-row-bottom-grid .product-card { flex: none; width: 100%; }
    .product-card { flex: 0 0 160px; }
    .product-img { height: 150px; }
    .product-img img { max-width: 110px; max-height: 110px; }
    .section-row { padding: 20px 16px 12px; flex-direction: column; align-items: flex-start; gap: 10px; }
    .section-row h2 { font-size: 18px; }
    .see-all-btn { font-size: 12px; padding: 8px 18px; }
    .featured-section { padding: 24px 16px; }
    .featured-section h2 { font-size: 18px; }
    .exclusively { padding: 24px 16px; }
    .exclusively h2 { font-size: 16px; }
    .blogs { padding: 0 16px 24px; }
    .blogs h2 { font-size: 16px; }
    .blogs-grid { grid-template-columns: 1fr; gap: 14px; }
    footer { padding: 30px 16px 20px; }
    .footer-grid { grid-template-columns: 1fr; gap: 24px; }
    .footer-bottom { flex-direction: column; gap: 12px; text-align: center; }
    .shipping-bar { font-size: 11px; padding: 8px 12px; gap: 6px; flex-wrap: wrap; }
  }
  @media (max-width: 400px) {
    .product-card { flex: 0 0 145px; }
    .cat-item { flex: 0 0 76px; }
    .cat-circle { width: 70px; height: 70px; }
  }

  /* ══════════════════════════════════════════════════════════════════════
     MY ORDERS — PAGE-SPECIFIC STYLES
     ══════════════════════════════════════════════════════════════════════ */
  .page-wrap { max-width: 100%; margin: 0 auto; padding: 30px 40px 70px; }
  .page-title { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 24px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }

  .flash { border-radius: 8px; padding: 14px 18px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
  .flash.success { background: #e8f5e9; color: var(--dark-green); border: 1px solid #b6dfb9; }
  .flash.error   { background: #fdecea; color: var(--dark-red); border: 1px solid #f3c2bd; }

  /* ── REVIEW PANEL (Shopee-style order table) ────────────────────────────── */
  .review-panel { background: #fff; border: 1px solid #d5d8dc; border-radius: 4px; padding: 0; margin-bottom: 34px; overflow: hidden; }
  .review-panel h2 { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 15px; margin: 0; padding: 14px 18px; border-bottom: 1px solid #d5d8dc; display: flex; align-items: center; gap: 8px; color: var(--text); }
  .review-panel h2 i { color: #6b7280; font-size: 14px; }

  /* shared 4-column table grid used by both the review panel and order history */
  .otable-head, .review-line, .order-item-row {
    display: grid;
    grid-template-columns: minmax(0,1fr) 130px 110px 130px;
    align-items: center;
    gap: 14px;
  }
  .otable-head { padding: 10px 14px; background: #f3f4f6; border-top: 1px solid #d5d8dc; border-bottom: 1px solid #d5d8dc; }
  .otable-head span { font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px; color: #4b5563; }
  .order-card .otable-head, .review-panel .otable-head { padding-left: 18px; padding-right: 18px; border-top: none; }
  .otable-head span.col-price, .otable-head span.col-sub { text-align: right; }
  .otable-head span.col-qty { text-align: center; }

  .review-line { padding: 14px 18px; border-bottom: 1px solid #e5e7eb; }
  .review-line:last-of-type { border-bottom: 1px solid #d5d8dc; }
  .review-line-product { display: flex; align-items: center; gap: 14px; min-width: 0; }
  .review-line img { width: 48px; height: 48px; object-fit: cover; border-radius: 2px; border: 1px solid #e5e7eb; flex-shrink: 0; }
  .review-line-info { flex: 1; min-width: 0; }
  .review-line-name { font-weight: 600; font-size: 13px; }
  .review-line-meta { font-size: 12px; color: var(--gray); margin-top: 2px; }
  .review-line-rx { color: #4b5563; font-weight: 600; }
  .review-line-price { font-size: 13px; color: var(--gray); text-align: right; white-space: nowrap; }
  .review-line-qty { font-size: 13px; color: var(--text); text-align: center; white-space: nowrap; }
  .review-line-sub { font-weight: 600; font-size: 13px; white-space: nowrap; text-align: right; color: var(--text); }
  .review-warning { background: #fafafa; color: #7a4a00; border: 1px solid #e5e7eb; border-left: 3px solid #b7791f; border-radius: 3px; padding: 10px 14px; font-size: 12.5px; margin: 10px 0; grid-column: 1 / -1; }
  .review-footer { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; padding: 14px 18px; background: #f9fafb; border-top: 1px solid #d5d8dc; }
  .btn-discard-link { background: #fff; border: 1px solid #d5d8dc; border-radius: 3px; color: #374151; font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 600; cursor: pointer; padding: 10px 20px; }
  .btn-discard-link:hover { background: #f3f4f6; border-color: #9ca3af; }
  .review-footer-summary { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; margin-left: auto; }
  .review-footer-total { text-align: right; }
  .review-footer-total .label { display: block; font-size: 12px; color: #6b7280; font-weight: 600; }
  .review-footer-total .amount { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 20px; color: var(--text); line-height: 1.2; }
  .btn-place-order {
    background: var(--green); color: #fff; border: 1px solid var(--dark-green); border-radius: 3px;
    height: 42px; padding: 0 26px; font-family: 'Montserrat', sans-serif;
    font-weight: 600; font-size: 13px; letter-spacing: 0.3px;
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: background 0.15s ease;
    white-space: nowrap;
  }
  .btn-place-order:hover { background: var(--dark-green); }
  @media (max-width: 560px) {
    .review-footer { flex-direction: column; align-items: stretch; }
    .review-footer-summary { margin-left: 0; justify-content: space-between; width: 100%; }
    .btn-discard-link { width: 100%; }
    .btn-place-order { flex: 1; }
  }

  /* ── PAYMENT METHOD (review panel) ─────────────────────────────────────── */
  .pay-box { padding: 18px; }
  .pay-box-title { font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px; color: #4b5563; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
  .pay-options { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  .pay-option { position: relative; cursor: pointer; }
  .pay-option input { position: absolute; opacity: 0; pointer-events: none; }
  .pay-option-body { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 1px solid #d5d8dc; border-radius: 4px; transition: border-color 0.15s, background 0.15s; height: 100%; }
  .pay-option-body > i { font-size: 20px; color: var(--gray); width: 24px; text-align: center; }
  .pay-option-body strong { display: block; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 13px; }
  .pay-option-body small { display: block; font-size: 11.5px; color: var(--gray); margin-top: 2px; line-height: 1.4; }
  .pay-option:hover .pay-option-body { border-color: #9ca3af; }
  .pay-option input:checked + .pay-option-body { border-color: #374151; background: #f9fafb; box-shadow: inset 0 0 0 1px #374151; }
  .pay-option input:checked + .pay-option-body > i { color: #374151; }
  .pay-option input:focus-visible + .pay-option-body { outline: 2px solid #374151; outline-offset: 2px; }
  .pay-note { margin-top: 10px; background: #f9fafb; color: #4b5563; border: 1px solid #e5e7eb; border-radius: 3px; padding: 10px 14px; font-size: 12.5px; }
  .pay-error { color: var(--red); font-size: 12px; margin-top: 6px; }
  @media (max-width: 560px) { .pay-options { grid-template-columns: 1fr; } }

  /* ── PAYMENT INFO (order history) ──────────────────────────────────────── */
  .order-pay { padding: 12px 18px; border-top: 1px solid #d5d8dc; display: flex; flex-direction: column; gap: 8px; font-size: 12.5px; }
  .order-pay-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
  .order-pay-label { color: var(--gray); font-weight: 600; }
  .pay-pill { font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 11px; padding: 3px 10px; border-radius: 3px; background: #f3f4f6; color: #374151; border: 1px solid #d5d8dc; display: inline-flex; align-items: center; gap: 5px; }
  .pay-pill.app { background: #f3f4f6; color: #374151; }
  .pay-pill.cash { background: #f3f4f6; color: #374151; }
  .proof-box { padding: 10px 12px; background: #f5faff; border: 1px dashed #0b4a8f; border-radius: 6px; }
  .proof-box .proof-label { color: #0b4a8f; font-weight: 700; font-size: 12px; display: flex; align-items: center; gap: 6px; margin-bottom: 6px; }
  .proof-box form { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
  .proof-box input[type=file] { font-size: 12px; }
  .proof-upload-btn { background: #0b4a8f; color: #fff; border: none; border-radius: 6px; padding: 6px 14px; font-size: 12px; font-weight: 700; cursor: pointer; }
  .proof-upload-btn:hover { background: #083769; }
  .proof-hint { color: var(--gray); font-size: 12px; }
  .proof-uploaded { color: var(--dark-green); font-weight: 700; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
  .proof-error { color: var(--red); font-size: 11.5px; margin-top: 6px; }

  /* ── PAYMENT ACCOUNTS LINK + MODAL ────────────────────────────────────── */
  .pay-accounts-link { margin-left: auto; color: #0b4a8f; font-size: 12px; font-weight: 700; text-decoration: underline; cursor: pointer; font-family: 'Montserrat', sans-serif; }
  .pay-accounts-link:hover { color: #083769; }
  .pa-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 2100; align-items: center; justify-content: center; padding: 16px; }
  .pa-overlay.open { display: flex; }
  .pa-box { background: #fff; border-radius: 12px; width: 100%; max-width: 760px; max-height: 92vh; display: flex; flex-direction: column; box-shadow: 0 12px 40px rgba(0,0,0,0.2); }
  .pa-head { display: flex; align-items: center; gap: 10px; padding: 16px 22px; border-bottom: 1px solid var(--border); }
  .pa-head h3 { font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 800; flex: 1; }
  .pa-back { background: none; border: none; color: #0b4a8f; font-size: 13px; font-weight: 700; cursor: pointer; display: none; align-items: center; gap: 5px; padding: 0; font-family: 'Montserrat', sans-serif; }
  .pa-back:hover { text-decoration: underline; }
  .pa-close { background: none; border: none; font-size: 22px; color: #888; cursor: pointer; line-height: 1; }
  .pa-close:hover { color: var(--red); }
  .pa-body { padding: 18px 22px 22px; overflow-y: auto; flex: 1 1 auto; }
  .pa-hint { font-size: 12px; color: var(--gray); margin-bottom: 12px; }
  .pa-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px; }
  .pa-app-btn { display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 12px 14px; background: #fff; border: 1.5px solid var(--border); border-radius: 8px; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 13px; color: var(--text); cursor: pointer; text-align: left; transition: border-color .15s, background .15s; }
  .pa-app-btn:hover { border-color: #0b4a8f; background: #f5faff; }
  .pa-app-btn i { color: #9ca3af; font-size: 12px; }
  /* Landscape detail: account info on the left, screenshot on the right */
  .pa-detail { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: start; }
  .pa-detail.no-image { grid-template-columns: 1fr; }
  .pa-detail-app { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 18px; margin-bottom: 14px; }
  .pa-row { display: flex; flex-direction: column; gap: 2px; padding: 10px 12px; background: #f9fafb; border: 1px solid var(--border); border-radius: 8px; margin-bottom: 8px; }
  .pa-row .pa-label { font-size: 10.5px; font-weight: 700; color: var(--gray); text-transform: uppercase; letter-spacing: .05em; }
  .pa-row .pa-value { font-size: 14px; font-weight: 700; word-break: break-all; }
  .pa-number-line { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
  .pa-copy { background: #fff; color: #0b4a8f; border: 1px solid #0b4a8f; border-radius: 6px; padding: 4px 10px; font-size: 11px; font-weight: 700; cursor: pointer; white-space: nowrap; }
  .pa-copy:hover { background: #0b4a8f; color: #fff; }
  .pa-image { text-align: center; }
  .pa-image img { max-width: 100%; max-height: 300px; border: 1px solid var(--border); border-radius: 8px; cursor: zoom-in; }
  .pa-image-hint { font-size: 11px; color: var(--gray); margin-top: 6px; }
  .pa-empty { text-align: center; color: var(--gray); font-size: 13px; padding: 20px 0; }
  @media (max-width: 600px) { .pa-detail { grid-template-columns: 1fr; } }
  /* Full-size image viewer */
  .pa-lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.88); z-index: 2200; align-items: center; justify-content: center; padding: 16px; cursor: zoom-out; }
  .pa-lightbox.open { display: flex; }
  .pa-lightbox img { max-width: 96vw; max-height: 94vh; object-fit: contain; border-radius: 6px; background: #fff; }
  .pa-lightbox-close { position: absolute; top: 14px; right: 20px; background: none; border: none; color: #fff; font-size: 34px; line-height: 1; cursor: pointer; }

  /* ── ORDER HISTORY ────────────────────────────────────────────────────── */
  .orders-heading { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 16px; margin-bottom: 14px; }
  .order-card { background: #fff; border: 1px solid #d5d8dc; border-radius: 4px; margin-bottom: 20px; overflow: hidden; }
  .order-card-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: #fff; border-bottom: 1px solid #d5d8dc; flex-wrap: wrap; gap: 8px; }
  .order-number { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 14px; }
  .order-date { font-size: 12px; color: var(--gray); margin-top: 2px; }
  .status-pill { font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 11px; padding: 4px 10px; border-radius: 3px; border: 1px solid transparent; text-transform: uppercase; letter-spacing: 0.5px; }
  .status-pending   { background: #fafafa; color: #6b5b00; border-color: #d9cf9a; }
  .status-confirmed { background: #fafafa; color: #1e4f8a; border-color: #b4c9e3; }
  .status-ready     { background: #fafafa; color: #2b6a30; border-color: #b3d3b6; }
  .status-picked_up { background: #f3f4f6; color: #4b5563; border-color: #d5d8dc; }
  .status-cancelled { background: #fafafa; color: #9b2c2c; border-color: #e0b4b4; }

  .order-card-body { padding: 0; }
  .order-item-row { padding: 14px 18px; border-bottom: 1px solid #e5e7eb; }
  .order-item-row:last-of-type { border-bottom: none; }
  .order-item-product { display: flex; align-items: center; gap: 14px; min-width: 0; }
  .order-item-row img { width: 48px; height: 48px; object-fit: cover; border-radius: 2px; border: 1px solid #e5e7eb; flex-shrink: 0; }
  .order-item-info { flex: 1; min-width: 0; }
  .order-item-name { font-weight: 600; font-size: 13px; }
  .order-item-meta { font-size: 12px; color: var(--gray); margin-top: 2px; }
  .order-item-price { font-size: 12.5px; color: var(--gray); text-align: right; white-space: nowrap; }
  .order-item-qty { font-size: 13px; color: var(--text); text-align: center; white-space: nowrap; }
  .order-item-sub { font-weight: 600; font-size: 13px; white-space: nowrap; text-align: right; }

  .rx-box { margin-top: 8px; padding: 10px 12px; background: #fff8f8; border: 1px dashed var(--red); border-radius: 6px; }
  .rx-box .rx-label { color: var(--red); font-weight: 700; font-size: 12px; display: flex; align-items: center; gap: 6px; margin-bottom: 6px; }
  .rx-box form, .rx-box .rx-file-row { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
  .rx-box input[type=file] { font-size: 12px; }
  .rx-upload-btn { background: var(--red); color: #fff; border: none; border-radius: 6px; padding: 6px 14px; font-size: 12px; font-weight: 700; cursor: pointer; }
  .rx-upload-btn:hover { background: var(--dark-red); }
  .rx-uploaded { color: var(--dark-green); font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 6px; margin-top: 8px; }
  /* Review panel's own Rx box sits as a full-width grid row under its line */
  .review-line .rx-box { grid-column: 1 / -1; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 3px; }
  .review-line .rx-box .rx-label { color: #374151; }
  .rx-box .rx-file-error { color: var(--red); font-size: 11.5px; margin-top: 6px; }

  .order-card-foot { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; background: #f9fafb; border-top: 1px solid #d5d8dc; flex-wrap: wrap; gap: 10px; }
  .order-total { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 15px; }
  .order-cancel-btn { background: #fff; color: var(--red); border: 1px solid var(--red); border-radius: 3px; padding: 8px 18px; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 12px; cursor: pointer; }
  .order-cancel-btn:hover { background: var(--red); color: #fff; }
  .cancel-reason { font-size: 12px; color: var(--dark-red); font-style: italic; }

  /* ── VIEW RECEIPT / PROOF OF REFUND BUTTONS + RECEIPT MODAL ───────────── */
  .order-receipt-btn { background: #fff; color: var(--dark-green); border: 1px solid var(--dark-green); border-radius: 3px; padding: 8px 18px; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
  .order-receipt-btn:hover { background: var(--dark-green); color: #fff; }
  .order-track-btn { background: var(--dark-green); color: #fff; border: 1px solid var(--dark-green); border-radius: 3px; padding: 8px 18px; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
  .order-track-btn:hover { background: var(--green); border-color: var(--green); }
  .refund-proof-btn { background: #fff; color: #0b4a8f; border: 1px solid #0b4a8f; border-radius: 6px; padding: 6px 14px; font-size: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
  .refund-proof-btn:hover { background: #0b4a8f; color: #fff; }
  .rcpt-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.55); z-index: 2100; align-items: center; justify-content: center; padding: 16px; }
  .rcpt-overlay.open { display: flex; }
  .rcpt-box { background: #fff; border-radius: 12px; width: 100%; max-width: 460px; max-height: 92vh; display: flex; flex-direction: column; box-shadow: 0 12px 40px rgba(0,0,0,0.2); }
  .rcpt-head { display: flex; align-items: center; justify-content: space-between; padding: 16px 22px; border-bottom: 1px solid var(--border); }
  .rcpt-head h3 { font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 800; }
  .rcpt-close { background: none; border: none; font-size: 22px; color: #888; cursor: pointer; line-height: 1; }
  .rcpt-close:hover { color: var(--red); }
  .rcpt-body { padding: 18px 22px; overflow-y: auto; flex: 1 1 auto; }
  .rcpt-header { text-align: center; padding-bottom: 14px; border-bottom: 1px dashed var(--border); margin-bottom: 14px; }
  .rcpt-logo { font-family: 'Montserrat', sans-serif; font-size: 18px; font-weight: 800; }
  .rcpt-sub { font-size: 11px; color: var(--gray); margin-top: 2px; }
  .rcpt-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 12px; font-size: 12px; margin-bottom: 14px; }
  .rcpt-meta .rm-label { color: var(--gray); }
  .rcpt-meta .rm-value { font-weight: 600; text-align: right; }
  .rcpt-items { width: 100%; font-size: 12px; border-collapse: collapse; margin-bottom: 12px; }
  .rcpt-items thead th { font-size: 10px; text-transform: uppercase; letter-spacing: .05em; color: var(--gray); padding: 4px 0; border-bottom: 1px solid var(--border); text-align: left; }
  .rcpt-items thead th:last-child, .rcpt-items tbody td:last-child, .rcpt-items tfoot td:last-child { text-align: right; }
  .rcpt-items tbody td { padding: 7px 0; border-bottom: 1px solid var(--border); }
  .rcpt-items tbody td:last-child { font-weight: 600; }
  .rcpt-items tfoot td { padding: 8px 0 2px; font-size: 13px; font-weight: 700; }
  .rcpt-proof { margin-bottom: 14px; }
  .rcpt-proof-label { font-size: 11px; font-weight: 700; color: var(--gray); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; }
  .rcpt-proof img { max-width: 100%; max-height: 220px; border: 1px solid var(--border); border-radius: 8px; display: block; }
  .rcpt-proof-note { font-size: 12px; color: var(--gray); margin-top: 6px; }
  .rcpt-status { padding: 10px 12px; border-radius: 10px; background: #f0fdf4; color: #166534; font-size: 13px; font-weight: 600; }
  .rcpt-foot { padding: 12px 22px 18px; display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid var(--border); }
  .rcpt-btn { background: var(--gray-light); color: var(--text); border: 1px solid var(--border); border-radius: 6px; padding: 8px 18px; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 12px; cursor: pointer; }
  .rcpt-btn:hover { background: #e8e8e8; }
  @media print {
    body > *:not(#receiptModal) { display: none !important; }
    #receiptModal { display: block !important; position: static; background: none; padding: 0; }
    .rcpt-box { box-shadow: none; max-height: none; max-width: 100%; }
    .rcpt-foot, .rcpt-close { display: none !important; }
  }

  .empty-orders { text-align: center; padding: 60px 20px; color: var(--gray); }
  .empty-orders i { font-size: 40px; color: var(--border); margin-bottom: 14px; }
  .empty-orders a { color: var(--red); font-weight: 700; }

  /* ── ORDER TABLE — mobile: collapse the 4 columns into a stacked card row ─ */
  @media (max-width: 600px) {
    .otable-head { display: none; }
    .review-line, .order-item-row {
      grid-template-columns: 1fr 1fr;
      grid-template-areas: "product product" "price qty" "sub sub";
      row-gap: 6px;
    }
    .review-line-product, .order-item-product { grid-area: product; }
    .review-line-price, .order-item-price { grid-area: price; text-align: left; }
    .review-line-qty, .order-item-qty { grid-area: qty; text-align: right; }
    .review-line-qty::before, .order-item-qty::before { content: "Qty: "; color: var(--gray); }
    .review-line-sub, .order-item-sub { grid-area: sub; text-align: right; padding-top: 4px; border-top: 1px solid #e5e7eb; }
    .order-card .otable-head { display: none; }
  }
</style>
</head>
<body>

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
    <img src="{{ asset($settings['logo'] ?? 'https://www.bticino.ph/modules/custom/legrand_ecat/assets/img/no-image.png') }}" alt="{{ $settings['site_name'] ?? 'No Pharmacy Name' }}">
  </div>
  
  <form action="{{ route('home') }}" method="GET" class="search-bar">
    <input type="text" name="query" value="{{ $searchQuery ?? '' }}" placeholder="Search for Generic and Branded Medicine">
    <button type="submit"><i class="fas fa-search"></i></button>
  </form>
  <div class="header-right">
    <div class="phone-box">
      <i class="fas fa-phone-alt ph-icon"></i>
      <div>
        <small style="color:#555;font-size:10px;font-weight:600;">{{ $settings['phone_label'] ?? 'Call Us Now' }}</small>
        <span>{{ $settings['phone'] ?? 'No phone number available' }}</span>
      </div>
    </div>
    @auth
    <a href="{{ route('profile') }}" title="My Profile"><i class="fas fa-user icon-btn"></i></a>
    @else
    <a href="#" id="userIconLoginBtn" title="Log In" style="display:flex;align-items:center;"><i class="fas fa-user icon-btn"></i></a>
    @endauth

    {{-- NOTIFICATION HEART — dropdown only for logged-in users --}}
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
          <div class="notif-item" data-id="5">
            <div class="notif-icon-wrap promo"><i class="fas fa-percent"></i></div>
            <div class="notif-body">
              <p>Earn <strong>double points</strong> on all branded medicines this weekend.</p>
              <span class="notif-time"><i class="fas fa-clock" style="margin-right:3px;"></i>2 days ago</span>
            </div>
          </div>
        </div>
        <div class="notif-footer">
          <a href="#">View all notifications</a>
        </div>
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
    <span class="nav-brand" style="display:none" id="nav-brand-label">{{ $settings['site_name'] ?? 'NO PHARMACY NAME' }}</span>
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
  {{ $settings['location_strip'] ?? 'No location available' }} &nbsp;|&nbsp;
  <i class="fas fa-phone-alt"></i> {{ $settings['phone'] ?? 'No phone number available' }} &nbsp;|&nbsp;
  <i class="fas fa-clock"></i> {{ $settings['working_hours'] ?? 'No working hours available' }}
</div>
<div class="page-wrap">

  <h1 class="page-title"><i class="fas fa-receipt" style="color:var(--red);"></i> My Orders</h1>

  @if(session('success'))
    <div class="flash success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="flash error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
  @endif

  {{-- ═══════════════════════════════════════════════════════════════════
       REVIEW PANEL — staged by "Buy Now" or the cart's "Checkout" button.
       Nothing here is in the database yet.
       ═══════════════════════════════════════════════════════════════════ --}}
  @if($review)
    <div class="review-panel">
      <h2><i class="fas fa-clipboard-check"></i> Review Your Order</h2>

      {{-- One form wraps the whole review + the Place Order button, since a
           required item's prescription file now has to travel in with the
           order-placement request itself (enctype must be multipart). The
           "Cancel" button below submits a separate, unnested tiny form. --}}
      <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="otable-head">
          <span class="col-product">Product</span>
          <span class="col-price">Unit Price</span>
          <span class="col-qty">Quantity</span>
          <span class="col-sub">Item Subtotal</span>
        </div>

        @foreach($review['lines'] as $line)
          @php $product = $line['product']; @endphp
          <div class="review-line">
            <div class="review-line-product">
              <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
              <div class="review-line-info">
                <div class="review-line-name">{{ $product->name }}</div>
                @if($product->requires_prescription)
                  <div class="review-line-meta"><span class="review-line-rx"><i class="fas fa-prescription"></i> Rx Required</span></div>
                @endif
              </div>
            </div>
            <div class="review-line-price">{{ $product->formattedPrice() }}</div>
            <div class="review-line-qty">{{ $line['quantity'] }}</div>
            <div class="review-line-sub">₱{{ number_format($line['subtotal'], 2) }}</div>
            @if($line['available_stock'] < $line['quantity'])
              <div class="review-warning">Only {{ $line['available_stock'] }} left in stock — reduce the quantity to place this order.</div>
            @endif

            {{-- Rx items must have a prescription attached before the order
                 can be placed at all — no more "upload it after" for these. --}}
            @if($product->requires_prescription)
              <div class="rx-box">
                <div class="rx-label"><i class="fas fa-prescription"></i> Upload prescription to place this order</div>
                <div class="rx-file-row">
                  <input type="file" name="prescriptions[{{ $product->id }}]" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
                @error('prescriptions.' . $product->id)
                  <div class="rx-file-error">{{ $message }}</div>
                @enderror
              </div>
            @endif
          </div>
        @endforeach

        {{-- Payment method — chosen here, before the order is placed. Proof of
             payment (for "app") can only be uploaded later, once the pharmacy
             has confirmed the order. --}}
        @php $selectedPayment = old('payment_method', 'cash'); @endphp
        <div class="pay-box">
          <div class="pay-box-title"><i class="fas fa-wallet" style="color:#6b7280;"></i> Payment Method</div>
          <div class="pay-options">
            <label class="pay-option">
              <input type="radio" name="payment_method" value="cash" {{ $selectedPayment === 'cash' ? 'checked' : '' }} required>
              <span class="pay-option-body">
                <i class="fas fa-money-bill-wave"></i>
                <span><strong>Cash</strong><small>Pay at the pharmacy when you pick up your order</small></span>
              </span>
            </label>
            <label class="pay-option">
              <input type="radio" name="payment_method" value="app" {{ $selectedPayment === 'app' ? 'checked' : '' }} required>
              <span class="pay-option-body">
                <i class="fas fa-mobile-alt"></i>
                <span><strong>App</strong><small>Pay through an online app (e.g. GCash, Maya)</small></span>
              </span>
            </label>
          </div>
          <div class="pay-note" id="payAppNote" style="{{ $selectedPayment === 'app' ? '' : 'display:none;' }}">
            <i class="fas fa-info-circle"></i> You'll be able to upload your proof of payment here in My Orders once the pharmacy confirms your order.
          </div>
          @error('payment_method')
            <div class="pay-error">{{ $message }}</div>
          @enderror
        </div>

        <div class="review-footer">
          <button type="button" class="btn-discard-link" onclick="document.getElementById('discardReviewForm').submit();">Cancel</button>

          <div class="review-footer-summary">
            <div class="review-footer-total">
              <span class="label">Total ({{ count($review['lines']) }} item{{ count($review['lines']) == 1 ? '' : 's' }})</span>
              <span class="amount">₱{{ number_format($review['total'], 2) }}</span>
            </div>
            <button type="submit" class="btn-place-order"><i class="fas fa-check"></i> Place Order</button>
          </div>
        </div>
      </form>

      <form id="discardReviewForm" action="{{ route('order.review.discard') }}" method="POST" style="display:none;">
        @csrf
      </form>
    </div>
  @endif

  {{-- ═══════════════════════════════════════════════════════════════════
       ORDER HISTORY
       ═══════════════════════════════════════════════════════════════════ --}}
  @if($orders->isEmpty())

    <div class="empty-orders">
      <i class="fas fa-receipt"></i>
      <h2 style="margin-bottom:8px;">No orders yet</h2>
      <p>Once you place an order, it'll show up here.</p>
      <p style="margin-top:14px;"><a href="{{ route('home') }}">Start shopping →</a></p>
    </div>

  @else

    <div class="orders-heading">Order History</div>

    @foreach($orders as $order)
      <div class="order-card">
        <div class="order-card-head">
          <div>
            <div class="order-number">{{ $order->order_number }}</div>
            <div class="order-date">Placed {{ $order->created_at->format('M j, Y g:i A') }}</div>
          </div>
          <span class="status-pill status-{{ $order->status }}">{{ str_replace('_', ' ', $order->status) }}</span>
        </div>

        <div class="order-card-body">
          <div class="otable-head">
            <span class="col-product">Product</span>
            <span class="col-price">Unit Price</span>
            <span class="col-qty">Quantity</span>
            <span class="col-sub">Item Subtotal</span>
          </div>

          @foreach($order->items as $item)
            @php $product = $item->product; @endphp
            <div class="order-item-row">
              <div class="order-item-product">
                @if($product)
                  <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                @endif
                <div class="order-item-info">
                  <div class="order-item-name">{{ $product->name ?? 'Product no longer available' }}</div>

                  @if($item->requiresPrescription())
                    @if($item->prescription_file)
                      <div class="rx-uploaded"><i class="fas fa-check-circle"></i> Prescription uploaded &nbsp;·&nbsp; <a href="{{ asset($item->prescription_file) }}" target="_blank" style="text-decoration:underline;">View</a></div>
                    @elseif(!$order->isTerminal())
                      <div class="rx-box">
                        <div class="rx-label"><i class="fas fa-prescription"></i> Prescription required for this item</div>
                        <form action="{{ route('order.prescription.upload', $item) }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <input type="file" name="prescription" accept=".jpg,.jpeg,.png,.pdf" required>
                          <button type="submit" class="rx-upload-btn">Upload</button>
                        </form>
                      </div>
                    @else
                      <div class="rx-box"><span style="color:var(--gray);font-size:12px;">Prescription was not uploaded before this order was closed.</span></div>
                    @endif
                  @endif
                </div>
              </div>
              <div class="order-item-price">₱{{ number_format($item->unit_price, 2) }}</div>
              <div class="order-item-qty">{{ $item->quantity }}</div>
              <div class="order-item-sub">₱{{ number_format($item->subtotal, 2) }}</div>
            </div>
          @endforeach
        </div>

        {{-- Payment method + proof of payment. Proof can only be uploaded while
             the order is "confirmed". --}}
        <div class="order-pay">
          <div class="order-pay-row">
            <span class="order-pay-label">Payment:</span>
            @if($order->isOnlinePayment())
              <span class="pay-pill app"><i class="fas fa-mobile-alt"></i> App</span>
              @if($order->status === 'confirmed')
                <a href="#" class="pay-accounts-link" onclick="openPaymentAccounts(); return false;">Payment Accounts</a>
              @endif
            @else
              <span class="pay-pill cash"><i class="fas fa-money-bill-wave"></i> Cash</span>
            @endif
          </div>

          @if($order->isOnlinePayment())
            @if($order->canUploadProofOfPayment())
              <div class="proof-box">
                <div class="proof-label"><i class="fas fa-receipt"></i> {{ $order->hasProofOfPayment() ? 'Replace your proof of payment' : 'Your order is confirmed — upload your proof of payment' }}</div>
                @if($order->hasProofOfPayment())
                  <div class="proof-uploaded" style="margin-bottom:8px;"><i class="fas fa-check-circle"></i> Proof uploaded &nbsp;·&nbsp; <a href="{{ asset($order->proof_of_payment) }}" target="_blank" style="text-decoration:underline;">View</a></div>
                @endif
                <form action="{{ route('order.proof.upload', $order) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf" required>
                  <button type="submit" class="proof-upload-btn">Upload</button>
                </form>
                @if($errors->getBag('proof_' . $order->id)->has('proof_of_payment'))
                  <div class="proof-error">{{ $errors->getBag('proof_' . $order->id)->first('proof_of_payment') }}</div>
                @endif
              </div>
            @elseif($order->hasProofOfPayment())
              <div class="proof-uploaded"><i class="fas fa-check-circle"></i> Proof of payment uploaded &nbsp;·&nbsp; <a href="{{ asset($order->proof_of_payment) }}" target="_blank" style="text-decoration:underline;">View</a></div>
            @elseif($order->status === 'pending')
              <div class="proof-hint"><i class="fas fa-hourglass-half"></i> You can upload your proof of payment once the pharmacy confirms this order.</div>
            @elseif($order->status !== 'cancelled')
              <div class="proof-hint">No proof of payment was uploaded for this order.</div>
            @endif

            {{-- Cancelled after paying online and the pharmacy has already refunded --}}
            @if($order->status === 'cancelled' && $order->hasProofOfRefund())
              <div class="proof-uploaded"><i class="fas fa-check-circle"></i> Your refund has been sent &nbsp;·&nbsp;
                <a href="{{ asset($order->proof_of_refund) }}" target="_blank" rel="noopener" style="text-decoration:underline;">View</a>
              </div>
            @endif
          @endif
        </div>

        <div class="order-card-foot">
          <div>
            <div class="order-total">Total: ₱{{ number_format($order->total, 2) }}</div>
            @if($order->status === 'cancelled' && $order->cancellation_reason)
              <div class="cancel-reason">Reason: {{ $order->cancellation_reason }}</div>
            @endif
          </div>

          @if($order->status === 'picked_up')
            <button type="button" class="order-receipt-btn" onclick="openReceipt({{ $order->id }})"><i class="fas fa-receipt"></i> View Receipt</button>
          @endif

          @if($order->status === 'ready')
            <a href="{{ route('order.track', $order) }}" class="order-track-btn"><i class="fas fa-map-marked-alt"></i> Track Order</a>
          @endif

          @if($order->isCancellable())
            <form action="{{ route('order.cancel', $order) }}" method="POST" onsubmit="return confirm('Cancel this order? This cannot be undone.');">
              @csrf
              @method('PUT')
              <button type="submit" class="order-cancel-btn"><i class="fas fa-times"></i> Cancel Order</button>
            </form>
          @endif
        </div>
      </div>
    @endforeach

  @endif

</div><!-- /.page-wrap -->
<!-- FOOTER -->

<footer>
  <div class="footer-grid">
    <div class="footer-section">
      <h4>CONTACT INFO</h4>
      <div class="label">ADDRESS:</div>
      <p>{{ $settings['address_line1'] ?? 'N/A' }}</p>
      <p>{{ $settings['address_line2'] ?? 'N/A' }}</p>
      <p>{{ $settings['address_line3'] ?? 'N/A' }}</p>
      <div class="label" style="margin-top:10px;">PHONE:</div>
      <p>{{ $settings['phone'] ?? 'N/A' }}</p>
      <div class="label" style="margin-top:10px;">EMAIL:</div>
      <p>{{ $settings['email'] ?? 'N/A' }}</p>
      <div class="label" style="margin-top:10px;">WORKING DAYS/HOURS:</div>
      <p>{{ $settings['working_hours'] ?? 'N/A' }}</p>
      <div class="footer-social">
        <a href="{{ $settings['facebook_url'] ?? '#' }}" class="fb"><i class="fab fa-facebook-f"></i></a>
        <a href="{{ $settings['instagram_url'] ?? '#' }}" class="ig"><i class="fab fa-instagram"></i></a>
      </div>
    </div>
    <div class="footer-section">
      <h4>ABOUT {{ $settings['site_name'] ?? 'No Pharmacy Name' }}</h4>
      <a href="#">About Us</a>
      <a href="#">Careers</a>
      <a href="#">Store Information</a>
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
    <div class="modal-logo"><span>{{ strtoupper($settings['site_name'] ?? 'NO PHARMACY NAME') }}</span></div>
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
        <input type="email" id="loginEmail" name="email"
               placeholder="Enter your email address"
               value="{{ old('email') }}"
               class="{{ $errors->getBag('login')->has('email') ? 'input-error' : '' }}">
      </div>
      <div class="modal-field">
        <label for="loginPassword">Password</label>
        <input type="password" id="loginPassword" name="password"
               placeholder="Enter your password"
               class="{{ $errors->getBag('login')->has('password') ? 'input-error' : '' }}">
      </div>
      <div class="modal-forgot"><a href="#">Forgot Password?</a></div>
      <button type="submit" class="modal-login-btn">LOG IN</button>
    </form>

    <div class="modal-divider"><hr><span>Don't have an account?</span><hr></div>
    <div class="modal-register">New to {{ $settings['site_name'] ?? 'No Pharmacy Name' }}? <a href="#" id="switchToRegister">Register here</a></div>
  </div>
</div>

<!-- REGISTER MODAL -->
<div class="modal-overlay" id="registerModal">
  <div class="modal-box wide">
    <button class="modal-close" id="registerModalClose" aria-label="Close" type="button">&times;</button>
    <div class="modal-logo"><span>{{ strtoupper($settings['site_name'] ?? 'NO PHARMACY NAME') }}</span></div>
    <div class="modal-title">Create an Account</div>
    <div class="modal-subtitle">Join {{ $settings['site_name'] ?? 'No Pharmacy Name' }} and start shopping today</div>

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
            <input type="text" id="regFirstName" name="first_name"
                   placeholder="Juan"
                   value="{{ old('first_name') }}"
                   class="{{ $errors->getBag('register')->has('first_name') ? 'input-error' : '' }}">
          </div>
        </div>
        <div class="modal-field">
          <label for="regLastName">Last Name</label>
          <div class="input-icon-wrap">
            <i class="fas fa-user"></i>
            <input type="text" id="regLastName" name="last_name"
                   placeholder="dela Cruz"
                   value="{{ old('last_name') }}"
                   class="{{ $errors->getBag('register')->has('last_name') ? 'input-error' : '' }}">
          </div>
        </div>
      </div>

      <div class="modal-fields-row">
        <div class="modal-field">
          <label for="regEmail">Email Address</label>
          <div class="input-icon-wrap">
            <i class="fas fa-envelope"></i>
            <input type="email" id="regEmail" name="email"
                   placeholder="you@example.com"
                   value="{{ old('email') }}"
                   class="{{ $errors->getBag('register')->has('email') ? 'input-error' : '' }}">
          </div>
        </div>
        <div class="modal-field">
          <label for="regContact">Contact Number</label>
          <div class="input-icon-wrap">
            <i class="fas fa-phone-alt"></i>
            <input type="tel" id="regContact" name="contact_number"
                   placeholder="09XXXXXXXXX"
                   value="{{ old('contact_number') }}"
                   class="{{ $errors->getBag('register')->has('contact_number') ? 'input-error' : '' }}">
          </div>
        </div>
      </div>

      <div class="modal-field">
        <label for="regAddress">Address</label>
        <div class="textarea-icon-wrap">
          <i class="fas fa-map-marker-alt"></i>
          <textarea id="regAddress" name="address" rows="2"
                    placeholder="House No., Street, Barangay, City/Municipality, Province"
                    class="{{ $errors->getBag('register')->has('address') ? 'input-error' : '' }}">{{ old('address') }}</textarea>
        </div>
      </div>

      <div class="reg-section-label">Security</div>

      <div class="modal-fields-row">
        <div class="modal-field">
          <label for="regPassword">Password</label>
          <div class="input-icon-wrap">
            <i class="fas fa-lock"></i>
            <input type="password" id="regPassword" name="password"
                   placeholder="Create a strong password"
                   class="{{ $errors->getBag('register')->has('password') ? 'input-error' : '' }}">
          </div>
        </div>
        <div class="modal-field">
          <label for="regPasswordConfirm">Confirm Password</label>
          <div class="input-icon-wrap">
            <i class="fas fa-lock"></i>
            <input type="password" id="regPasswordConfirm" name="password_confirmation"
                   placeholder="Repeat your password">
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
  {{ $settings['shipping_message'] ?? 'No shipping message' }} &nbsp;<a href="#">Dismiss</a>
</div>
{{-- ═══════════ PAYMENT ACCOUNTS MODAL (confirmed app-payment orders) ═══════════ --}}
@php
  $paymentAccountsData = collect($paymentAccounts ?? [])->map(fn ($a) => [
      'id'     => $a->id,
      'app'    => $a->payment_app,
      'name'   => $a->account_name,
      'number' => $a->account_number,
      'image'  => $a->image_url,
  ])->values();
@endphp
<div class="pa-overlay" id="paymentAccountsModal">
  <div class="pa-box">
    <div class="pa-head">
      <button type="button" class="pa-back" id="paBack"><i class="fas fa-arrow-left"></i> Back</button>
      <h3 id="paTitle">Payment Accounts</h3>
      <button type="button" class="pa-close" aria-label="Close" onclick="closePaymentAccounts()">&times;</button>
    </div>
    <div class="pa-body">
      <div id="paListView">
        <div class="pa-hint">Choose a payment app to see where to send your payment.</div>
        <div class="pa-list" id="paList"></div>
        <div class="pa-empty" id="paEmpty" style="display:none;">No payment accounts are available right now. Please contact the pharmacy.</div>
      </div>
      <div id="paDetailView" style="display:none;">
        <div class="pa-detail" id="paDetail">
          <div class="pa-info">
            <div class="pa-detail-app" id="paDetailApp"></div>
            <div class="pa-row"><span class="pa-label">Account Name</span><span class="pa-value" id="paDetailName"></span></div>
            <div class="pa-row">
              <span class="pa-label">Account Number</span>
              <div class="pa-number-line">
                <span class="pa-value" id="paDetailNumber"></span>
                <button type="button" class="pa-copy" id="paCopy">Copy</button>
              </div>
            </div>
          </div>
          <div class="pa-image" id="paDetailImage" style="display:none;"></div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Full-size viewer for the payment account image --}}
<div class="pa-lightbox" id="paLightbox">
  <button type="button" class="pa-lightbox-close" aria-label="Close">&times;</button>
  <img src="" alt="Payment account full image" id="paLightboxImg">
</div>

<script>
(function () {
  const ACCOUNTS = @json($paymentAccountsData);
  const modal    = document.getElementById('paymentAccountsModal');
  const lightbox = document.getElementById('paLightbox');
  const $        = id => document.getElementById(id);

  function showList() {
    $('paListView').style.display   = '';
    $('paDetailView').style.display = 'none';
    $('paBack').style.display       = 'none';
    $('paTitle').textContent        = 'Payment Accounts';
  }

  function openLightbox(src, alt) {
    $('paLightboxImg').src = src;
    $('paLightboxImg').alt = alt || 'Payment account full image';
    lightbox.classList.add('open');
  }
  function closeLightbox() { lightbox.classList.remove('open'); }

  function showDetail(acc) {
    $('paDetailApp').textContent    = acc.app;
    $('paDetailName').textContent   = acc.name;
    $('paDetailNumber').textContent = acc.number;
    $('paCopy').textContent         = 'Copy';

    const imgBox = $('paDetailImage');
    imgBox.innerHTML = '';
    if (acc.image) {
      const img = document.createElement('img');
      img.src = acc.image;
      img.alt = acc.app + ' account';
      img.title = 'Click to view full image';
      img.addEventListener('click', () => openLightbox(acc.image, img.alt));
      const hint = document.createElement('div');
      hint.className = 'pa-image-hint';
      hint.textContent = 'Click the image to view it full size';
      imgBox.appendChild(img);
      imgBox.appendChild(hint);
      imgBox.style.display = '';
      $('paDetail').classList.remove('no-image');
    } else {
      imgBox.style.display = 'none';
      $('paDetail').classList.add('no-image');
    }

    $('paListView').style.display   = 'none';
    $('paDetailView').style.display = '';
    $('paBack').style.display       = 'inline-flex';
    $('paTitle').textContent        = acc.app;
  }

  // Build the list of payment apps once
  const list = $('paList');
  ACCOUNTS.forEach(acc => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'pa-app-btn';
    const label = document.createElement('span');
    label.textContent = acc.app;
    const chev = document.createElement('i');
    chev.className = 'fas fa-chevron-right';
    btn.appendChild(label);
    btn.appendChild(chev);
    btn.addEventListener('click', () => showDetail(acc));
    list.appendChild(btn);
  });
  $('paEmpty').style.display = ACCOUNTS.length ? 'none' : '';

  $('paBack').addEventListener('click', showList);

  $('paCopy').addEventListener('click', function () {
    const text = $('paDetailNumber').textContent;
    const done = () => { this.textContent = 'Copied!'; };
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(done).catch(() => {});
    } else {
      const ta = document.createElement('textarea');
      ta.value = text;
      document.body.appendChild(ta);
      ta.select();
      try { document.execCommand('copy'); done(); } catch (e) {}
      document.body.removeChild(ta);
    }
  });

  window.openPaymentAccounts  = function () { showList(); modal.classList.add('open'); };
  window.closePaymentAccounts = function () { closeLightbox(); modal.classList.remove('open'); };
  modal.addEventListener('click', e => { if (e.target === modal) closePaymentAccounts(); });
  lightbox.addEventListener('click', closeLightbox);
  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    if (lightbox.classList.contains('open')) closeLightbox();
    else closePaymentAccounts();
  });
})();
</script>

{{-- ═══════════ RECEIPT MODAL (picked-up orders) — same layout as the admin receipt ═══════════ --}}
@php
  $receiptData = $orders->where('status', 'picked_up')->mapWithKeys(function ($o) {
      return [$o->id => [
          'orderNumber' => $o->order_number,
          'placed'      => $o->created_at->format('M j, Y g:i A'),
          'pickedUp'    => optional($o->picked_up_at)->format('M j, Y g:i A'),
          'method'      => $o->isOnlinePayment() ? 'App' : 'Cash',
          'online'      => $o->isOnlinePayment(),
          'total'       => (float) $o->total,
          'proof'       => $o->hasProofOfPayment() ? asset($o->proof_of_payment) : null,
          'items'       => $o->items->map(fn ($i) => [
              'name'  => $i->product->name ?? 'Product no longer available',
              'qty'   => (int) $i->quantity,
              'price' => (float) $i->unit_price,
              'sub'   => (float) $i->subtotal,
          ])->values(),
      ]];
  });
  $receiptCustomer = Auth::user() ? Auth::user()->full_name : '';
@endphp
<div class="rcpt-overlay" id="receiptModal">
  <div class="rcpt-box">
    <div class="rcpt-head">
      <h3>Order Receipt — <span id="rcptOrderId"></span></h3>
      <button type="button" class="rcpt-close" aria-label="Close" onclick="closeReceipt()">&times;</button>
    </div>
    <div class="rcpt-body">
      <div class="rcpt-header">
        <div class="rcpt-logo">💊 {{ $settings['site_name'] ?? 'No Pharmacy Name' }}</div>
        <div class="rcpt-sub">Official Sales Receipt</div>
      </div>
      <div class="rcpt-meta">
        <span class="rm-label">Customer</span><span class="rm-value" id="rcptCustomer">—</span>
        <span class="rm-label">Order #</span><span class="rm-value" id="rcptNumber">—</span>
        <span class="rm-label">Date</span><span class="rm-value" id="rcptDate">—</span>
        <span class="rm-label">Picked up</span><span class="rm-value" id="rcptPickedUp">—</span>
        <span class="rm-label">Payment</span><span class="rm-value" id="rcptMethod">—</span>
      </div>
      <table class="rcpt-items">
        <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
        <tbody id="rcptItems"></tbody>
        <tfoot><tr><td colspan="3">Total</td><td id="rcptTotal">—</td></tr></tfoot>
      </table>
      <div class="rcpt-proof" id="rcptProofSection" style="display:none;">
        <div class="rcpt-proof-label">Proof of Payment</div>
        <div id="rcptProofMedia"></div>
        <div class="rcpt-proof-note" id="rcptProofNote"></div>
      </div>
      <div class="rcpt-status">Transaction completed.</div>
    </div>
    <div class="rcpt-foot">
      <button type="button" class="rcpt-btn" onclick="window.print()"><i class="fas fa-print"></i> Print Receipt</button>
      <button type="button" class="rcpt-btn" onclick="closeReceipt()">Close</button>
    </div>
  </div>
</div>

<script>
(function () {
  const RECEIPTS  = @json($receiptData);
  const CUSTOMER  = @json($receiptCustomer);
  const modal     = document.getElementById('receiptModal');
  const peso      = n => '₱' + Number(n).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  const $         = id => document.getElementById(id);

  window.openReceipt = function (id) {
    const r = RECEIPTS[id];
    if (!r) return;

    $('rcptOrderId').textContent  = r.orderNumber;
    $('rcptNumber').textContent   = r.orderNumber;
    $('rcptCustomer').textContent = CUSTOMER || '—';
    $('rcptDate').textContent     = r.placed;
    $('rcptPickedUp').textContent = r.pickedUp || '—';
    $('rcptMethod').textContent   = r.method;
    $('rcptTotal').textContent    = peso(r.total);

    const tbody = $('rcptItems');
    tbody.innerHTML = '';
    r.items.forEach(item => {
      const tr = document.createElement('tr');
      [item.name, item.qty, peso(item.price), peso(item.sub)].forEach(val => {
        const td = document.createElement('td');
        td.textContent = val;
        tr.appendChild(td);
      });
      tbody.appendChild(tr);
    });

    // Proof of payment — app orders only (image if uploaded, notice if not)
    const section = $('rcptProofSection');
    const media   = $('rcptProofMedia');
    media.innerHTML = '';
    if (r.proof) {
      if (/\.pdf$/i.test(r.proof)) {
        const a = document.createElement('a');
        a.href = r.proof; a.target = '_blank'; a.rel = 'noopener';
        a.textContent = 'Open proof of payment (PDF)';
        media.appendChild(a);
      } else {
        const img = document.createElement('img');
        img.src = r.proof; img.alt = 'Proof of payment';
        media.appendChild(img);
      }
      $('rcptProofNote').textContent = 'Customer already paid via online app.';
      section.style.display = '';
    } else if (r.online) {
      $('rcptProofNote').textContent = 'No proof of payment uploaded.';
      section.style.display = '';
    } else {
      section.style.display = 'none';
    }

    modal.classList.add('open');
  };

  window.closeReceipt = function () { modal.classList.remove('open'); };
  modal.addEventListener('click', e => { if (e.target === modal) closeReceipt(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeReceipt(); });
})();
</script>

{{-- Shared add-to-cart logic: POSTs to /cart/add, updates .cart-badge elements,
     and fires cart:guest / cart:added / cart:error events on document. --}}
<script src="{{ asset('js/cart.js') }}" data-add-url="{{ route('cart.add') }}" data-count-url="{{ route('cart.count') }}"></script>

<script>
  function updateCountdown() {
    const now = new Date();
    const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
    const diff = endOfMonth - now;
    if (diff <= 0) return;
    const days = Math.floor(diff / 86400000);
    const hours = Math.floor((diff % 86400000) / 3600000);
    const mins = Math.floor((diff % 3600000) / 60000);
    const secs = Math.floor((diff % 60000) / 1000);
    const blocks = document.querySelectorAll('.count-block');
    if (blocks.length >= 4) {
      blocks[0].textContent = String(days).padStart(2, '0');
      blocks[1].textContent = String(hours).padStart(2, '0');
      blocks[2].textContent = String(mins).padStart(2, '0');
      blocks[3].textContent = String(secs).padStart(2, '0');
    }
  }
  updateCountdown();
  setInterval(updateCountdown, 1000);

  // ── Modal helpers ──────────────────────────────────────────────────────────
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

  // Trigger buttons (only present for guests)
  const loginBtn        = document.getElementById('loginBtn');
  const registerBtn     = document.getElementById('registerBtn');
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

  // Close buttons
  const modalClose         = document.getElementById('modalClose');
  const registerModalClose = document.getElementById('registerModalClose');
  if (modalClose)         modalClose.addEventListener('click',         () => closeModal(loginModal));
  if (registerModalClose) registerModalClose.addEventListener('click', () => closeModal(registerModal));

  // Click-outside to close
  if (loginModal)    loginModal.addEventListener('click',    e => { if (e.target === loginModal)    closeModal(loginModal); });
  if (registerModal) registerModal.addEventListener('click', e => { if (e.target === registerModal) closeModal(registerModal); });

  // Switch links
  const switchToLogin    = document.getElementById('switchToLogin');
  const switchToRegister = document.getElementById('switchToRegister');
  if (switchToLogin)    switchToLogin.addEventListener('click',    e => { e.preventDefault(); closeModal(registerModal); openModal(loginModal); });
  if (switchToRegister) switchToRegister.addEventListener('click', e => { e.preventDefault(); closeModal(loginModal); openModal(registerModal); });

  // Escape key
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeModal(loginModal); closeModal(registerModal); }
  });

  // ── Cart: guest gating + auto add-to-cart after login/register ─────────────
  // cart.js already POSTs /cart/add for every .js-add-to-cart click (product
  // cards on this page, and the ADD TO CART button on the product page).
  // When the visitor isn't logged in, CartController responds with
  // 401/{status:'guest'} and cart.js turns that into a 'cart:guest' event
  // here. We remember which product (and quantity) was clicked in
  // localStorage — so it survives the full-page redirect the login/register
  // forms do — open the login modal, and on the next page load, if the user
  // is now authenticated, replay the add-to-cart call automatically.
  //
  // Logging in through the header's LOG IN/REGISTER buttons instead uses
  // this exact same modal and form, so it's the button that was clicked
  // (handled above), not this handler, that keeps a manual login from
  // triggering an unwanted auto add-to-cart.

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

  // ── Add-to-cart toast + "flying image" animation ────────────────────────
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

    // ── Buy Now auto-continue ──────────────────────────────────────────────
    // "Buy Now" on the product page remembers the click the same way
    // add-to-cart does (see product.blade.php), then opens the login modal.
    // Login/register always redirect back here, so this is the page that
    // actually finishes the job: build the same hidden form the product
    // page would have submitted, and POST it straight to the order-review
    // route now that the visitor is authenticated.
    let pendingBuy = null;
    try { pendingBuy = JSON.parse(localStorage.getItem('pendingBuyNowItem') || 'null'); } catch (e) { pendingBuy = null; }

    if (pendingBuy && pendingBuy.product_id) {
      localStorage.removeItem('pendingBuyNowItem');
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ route('order.buyNow') }}';
      form.style.display = 'none';
      form.innerHTML = `
        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
        <input type="hidden" name="product_id" value="${pendingBuy.product_id}">
        <input type="hidden" name="quantity" value="${pendingBuy.quantity || 1}">
      `;
      document.body.appendChild(form);
      form.submit();
    }
  });
  @endauth

  // Auto-open modal when Laravel returns validation errors
  @if($errors->hasBag('login') && $errors->getBag('login')->any())
    document.addEventListener('DOMContentLoaded', () => openModal(loginModal));
  @elseif($errors->hasBag('register') && $errors->getBag('register')->any())
    document.addEventListener('DOMContentLoaded', () => openModal(registerModal));
  @endif

  // Profile picture preview (register modal)
  const profilePicInput = document.getElementById('profilePicInput');
  if (profilePicInput) {
    profilePicInput.addEventListener('change', function() {
      const file = this.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        const img  = document.getElementById('avatarImg');
        const icon = document.querySelector('#avatarPreview i');
        img.src = e.target.result;
        img.style.display = 'block';
        if (icon) icon.style.display = 'none';
      };
      reader.readAsDataURL(file);
    });
  }

  document.querySelector('.shipping-bar a').addEventListener('click', e => {
    e.preventDefault();
    e.target.closest('.shipping-bar').style.display = 'none';
  });

  // ── Notification dropdown (auth-only) ─────────────────────────────────────
  (function () {
    const toggle   = document.getElementById('notifToggle');
    const dropdown = document.getElementById('notifDropdown');
    const badge    = document.getElementById('notifBadge');
    const markAll  = document.getElementById('notifMarkAll');

    if (!toggle || !dropdown) return; // guest: elements don't exist

    function countUnread() {
      return document.querySelectorAll('#notifList .notif-item.unread').length;
    }

    function refreshBadge() {
      const n = countUnread();
      badge.textContent = n;
      badge.style.display = n > 0 ? 'flex' : 'none';
    }

    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      dropdown.classList.toggle('open');
    });

    // Click on a notification → mark it read
    document.querySelectorAll('#notifList .notif-item').forEach(item => {
      item.addEventListener('click', function () {
        this.classList.remove('unread');
        const dot = this.querySelector('.notif-dot');
        if (dot) dot.remove();
        refreshBadge();
      });
    });

    // Mark all as read
    if (markAll) {
      markAll.addEventListener('click', function () {
        document.querySelectorAll('#notifList .notif-item.unread').forEach(item => {
          item.classList.remove('unread');
          const dot = item.querySelector('.notif-dot');
          if (dot) dot.remove();
        });
        refreshBadge();
      });
    }

    // Close when clicking outside
    document.addEventListener('click', function (e) {
      if (!dropdown.contains(e.target) && e.target !== toggle) {
        dropdown.classList.remove('open');
      }
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') dropdown.classList.remove('open');
    });

    refreshBadge();
  })();

  // ── Payment method: show the "proof comes later" note when App is chosen ───
  (function () {
    const note = document.getElementById('payAppNote');
    if (!note) return;
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
      radio.addEventListener('change', () => {
        const chosen = document.querySelector('input[name="payment_method"]:checked');
        note.style.display = chosen && chosen.value === 'app' ? '' : 'none';
      });
    });
  })();

  document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const input = this.parentElement.querySelector('.qty-input');
      let val = parseInt(input.value) || 1;
      if (this.textContent === '+') val = Math.min(val + 1, 99);
      else val = Math.max(val - 1, 1);
      input.value = val;
    });
  });
</script>

@if(!empty($settings['chatbase_id']))
<script>
(function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="{{ $settings['chatbase_id'] }}";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
</script>
@endif

<script>
  // Keep the header cart badge in sync on load (cart.js also updates it on
  // every add-to-cart, this just covers the initial page load).
  document.addEventListener('DOMContentLoaded', () => {
    fetch('{{ route('cart.count') }}', { headers: { 'Accept': 'application/json' } })
      .then(res => res.json())
      .then(data => {
        document.querySelectorAll('.cart-badge').forEach(b => { b.textContent = data.cart_count; });
      })
      .catch(() => {});
  });
</script>

</body>
</html>