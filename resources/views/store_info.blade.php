<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Store Info - {{ $settings['site_name'] ?? 'No Pharmacy Name' }}</title>
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

  /* ============ STORE INFO PAGE (page-specific, prefixed .si-) ============ */
  .si-wrap { max-width: 1400px; margin: 0 auto; padding: 34px 40px 56px; }
  .si-crumbs { font-size: 12px; color: var(--gray); margin-bottom: 14px; }
  .si-crumbs a:hover { color: var(--red); text-decoration: underline; }
  .si-title { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 30px; color: var(--text); }
  .si-lead { margin-top: 8px; font-size: 15px; line-height: 1.7; color: var(--gray); max-width: 70ch; }
  .si-layout { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.15fr); gap: 28px; margin-top: 28px; align-items: stretch; }
  .si-info { border-top: 3px solid var(--red); }
  .si-row { display: grid; grid-template-columns: 26px minmax(0, 1fr); gap: 18px; padding: 22px 0; border-bottom: 1px solid var(--border); }
  .si-row > i { color: var(--red); font-size: 18px; line-height: 1.4; text-align: center; padding-top: 2px; }
  .si-row h3 { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 11.5px; letter-spacing: 1.4px; text-transform: uppercase; color: var(--gray); margin-bottom: 6px; }
  .si-row p { font-size: 15px; line-height: 1.7; color: var(--text); }
  .si-row p + p { margin-top: 4px; color: #555; font-size: 14px; }
  .si-info a.si-link { color: var(--red); font-weight: 700; }
  .si-info a.si-link:hover { text-decoration: underline; }
  .si-social { display: flex; gap: 10px; margin-top: 12px; }
  .si-social a { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 15px; }
  .si-social a.fb { background: #1877f2; }
  .si-social a.ig { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fd5949 45%, #d6249f 60%, #285aeb 90%); }
  .si-map { position: relative; min-height: 460px; }
  .si-map iframe { position: absolute; inset: 0; display: block; width: 100%; height: 100%; border: 1px solid var(--border); border-radius: 10px; background: var(--gray-light); }
  .si-map-empty { height: 100%; min-height: 300px; border: 1px dashed var(--border); border-radius: 10px; display: flex; align-items: center; justify-content: center; text-align: center; color: var(--gray); padding: 24px; line-height: 1.6; }
  .si-map-empty i { font-size: 28px; display: block; margin-bottom: 10px; }
  .si-section { margin-top: 48px; }
  .si-section-head { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; border-bottom: 2px solid var(--red); padding-bottom: 10px; margin-bottom: 20px; }
  .si-section-head h2 { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 20px; }
  .si-section-head span { font-size: 12px; color: var(--gray); }
  .si-media { margin-top: 26px; }
  .si-tabs { display: inline-flex; gap: 6px; padding: 5px; background: var(--gray-light); border-radius: 999px; margin-bottom: 16px; }
  .si-tab { border: 0; background: transparent; padding: 10px 22px; border-radius: 999px; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 13px; color: var(--gray); cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: background .2s, color .2s; }
  .si-tab:hover { color: var(--red); }
  .si-tab[aria-selected="true"] { background: var(--red); color: #fff; box-shadow: 0 4px 12px rgba(192,26,26,.28); }
  .si-tab small { font-size: 11px; font-weight: 700; opacity: .85; }
  .si-panel[hidden] { display: none; }
  .si-slider { position: relative; border-radius: 16px; overflow: hidden; background: #fff; border: 0; box-shadow: none; outline: none; }
  .si-viewport { overflow: hidden; }
  .si-track { display: flex; transition: transform .55s cubic-bezier(.4,0,.2,1); will-change: transform; }
  .si-slide { flex: 0 0 100%; position: relative; height: clamp(300px, 74vh, 740px); background: #fff; }
  .si-slide .si-photo { position: absolute; inset: 0; padding: 0; border: 0; background: #fff; cursor: zoom-in; display: block; width: 100%; height: 100%; }
  .si-slide .si-photo img { width: 100%; height: 100%; object-fit: contain; display: block; }
  .si-slide video { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; background: #fff; display: block; }
  .si-cap { position: absolute; left: 0; right: 0; bottom: 0; padding: 60px 28px 22px; color: #fff; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 18px; background: linear-gradient(transparent, rgba(0,0,0,.75)); pointer-events: none; text-align: left; }
  .si-slide.is-video .si-cap { bottom: 54px; padding-bottom: 14px; }
  .si-nav { position: absolute; top: 50%; transform: translateY(-50%); width: 52px; height: 52px; border-radius: 50%; border: 0; background: rgba(255,255,255,.92); color: var(--red); font-size: 18px; cursor: pointer; z-index: 5; box-shadow: 0 4px 16px rgba(0,0,0,.25); transition: background .2s, color .2s, transform .2s; display: flex; align-items: center; justify-content: center; }
  .si-nav:hover { background: var(--red); color: #fff; transform: translateY(-50%) scale(1.06); }
  .si-prev { left: 18px; }
  .si-next { right: 18px; }
  .si-count { position: absolute; top: 16px; right: 16px; z-index: 5; background: rgba(0,0,0,.6); color: #fff; font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 999px; letter-spacing: .5px; }
  .si-dots { position: absolute; left: 50%; bottom: 14px; transform: translateX(-50%); display: flex; gap: 8px; z-index: 5; max-width: 80%; flex-wrap: wrap; justify-content: center; }
  .si-slider.has-video-active .si-dots { display: none; }
  .si-dot { width: 10px; height: 10px; border-radius: 50%; border: 0; padding: 0; background: rgba(0,0,0,.25); cursor: pointer; transition: all .25s; }
  .si-dot[aria-current="true"] { background: var(--red); width: 26px; border-radius: 6px; }
  .si-thumbs { display: flex; gap: 10px; margin-top: 12px; overflow-x: auto; padding: 4px 2px 8px; scrollbar-width: thin; scrollbar-color: var(--red) #eee; }
  .si-thumbs::-webkit-scrollbar { height: 5px; }
  .si-thumbs::-webkit-scrollbar-thumb { background: var(--red); border-radius: 4px; }
  .si-thumb { flex: 0 0 100px; height: 60px; border-radius: 8px; overflow: hidden; border: 3px solid transparent; padding: 0; background: #ddd; cursor: pointer; opacity: .65; transition: opacity .2s, border-color .2s; }
  .si-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .si-thumb:hover { opacity: 1; }
  .si-thumb[aria-current="true"] { border-color: var(--red); opacity: 1; }
  .si-thumb.vid { display: flex; align-items: center; justify-content: center; gap: 8px; background: #222; color: #fff; font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; flex-basis: 150px; padding: 0 10px; }
  .si-thumb.vid i { color: #ffd600; }
  .si-thumb.vid span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .si-details-head { text-align: center; margin: 54px 0 4px; }
  .si-details-head h2 { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 24px; }
  .si-details-head h2::after { content: ''; display: block; width: 56px; height: 3px; background: var(--red); border-radius: 2px; margin: 10px auto 0; }
  .si-details-head p { margin-top: 10px; color: var(--gray); font-size: 14px; }
  .si-empty { color: var(--gray); padding: 40px 26px; border: 1px dashed var(--border); border-radius: 12px; text-align: center; background: var(--gray-light); }
  .si-empty i { display: block; font-size: 28px; margin-bottom: 10px; color: #bbb; }
  .si-lightbox { position: fixed; inset: 0; background: rgba(0,0,0,.88); display: none; align-items: center; justify-content: center; z-index: 9999; padding: 24px; }
  .si-lightbox.open { display: flex; }
  .si-lightbox figure { max-width: 1100px; max-height: 100%; text-align: center; }
  .si-lightbox img { max-width: 100%; max-height: 82vh; border-radius: 6px; }
  .si-lightbox figcaption { color: #fff; margin-top: 10px; font-size: 14px; }
  .si-lb-btn { position: absolute; background: rgba(255,255,255,.14); border: 0; color: #fff; width: 44px; height: 44px; border-radius: 50%; font-size: 18px; cursor: pointer; }
  .si-lb-btn:hover { background: rgba(255,255,255,.28); }
  .si-lb-close { top: 18px; right: 18px; }
  .si-lb-prev { left: 18px; top: 50%; transform: translateY(-50%); }
  .si-lb-next { right: 18px; top: 50%; transform: translateY(-50%); }
  .si-wrap :focus-visible { outline: 3px solid var(--green); outline-offset: 2px; }
  @media (max-width: 900px) {
    .si-layout { grid-template-columns: 1fr; }
    .si-map { min-height: 320px; }
    .si-nav { width: 44px; height: 44px; font-size: 15px; }
  }
  @media (max-width: 640px) {
    .si-wrap { padding: 24px 16px 40px; }
    .si-title { font-size: 24px; }
      .si-slider { border-radius: 12px; }
    .si-slide { height: clamp(240px, 52vh, 440px); }
    .si-cap { font-size: 14px; padding: 40px 16px 14px; }
    .si-slide.is-video .si-cap { bottom: 48px; }
    .si-nav { width: 38px; height: 38px; font-size: 13px; }
    .si-prev { left: 8px; } .si-next { right: 8px; }
    .si-thumb { flex-basis: 92px; height: 58px; }
    .si-thumb.vid { flex-basis: 120px; }
    .si-details-head { margin-top: 40px; }
  }
  @media (prefers-reduced-motion: reduce) { .si-track { transition: none; } }

</style>
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
  <a href="#" class="store-finder"><i class="fas fa-map-marker-alt"></i> STORE INFO</a>
  <span class="sep">|</span>
  <a href="{{ route('order.myorders') }}" class="track-order"><i class="fas fa-truck"></i> MY ORDERS</a>
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
    <img src="{{ asset($settings['logo'] ?? 'https://www.bticino.ph/modules/custom/legrand_ecat/assets/img/no-image.png') }}" alt="{{ $settings['site_name'] ?? 'No Pharmacy Name' }}">
</a>
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

@php
  // ---- Build readable sentences from the settings table ------------------
  $val  = fn ($k) => filled($settings[$k] ?? null) ? trim($settings[$k]) : null;
  $list = function (array $a) {
      $a = array_values(array_filter($a, fn ($x) => filled($x)));
      return count($a) > 1 ? implode(', ', array_slice($a, 0, -1)) . ' and ' . end($a) : ($a[0] ?? '');
  };

  $name     = $val('site_name') ?? 'our pharmacy';
  $tagline  = $val('tagline');
  $address  = implode(', ', array_filter([$val('address_line1'), $val('address_line2'), $val('address_line3')]));
  $strip    = $val('location_strip');
  $phone    = $val('phone');
  $email    = $val('email');
  $hours    = $val('working_hours');
  $fb       = $val('facebook_url');
  $ig       = $val('instagram_url');
  $payments = $val('payment_methods')
      ? array_values(array_filter(array_map('trim', explode(',', $val('payment_methods')))))
      : [];
@endphp

<main class="si-wrap">
  <div class="si-crumbs"><a href="{{ route('home') }}">Home</a> / Store Info</div>
  <h1 class="si-title">Store Info</h1>
  <p class="si-lead">
    Welcome to {{ $name }}.
    @if($tagline) {{ rtrim($tagline, '.') }}. @endif
    Here is everything you need to find us, reach us and see what our store looks like.
  </p>

  {{-- ===== MEDIA SLIDERS (first part of the page) ===== --}}
  @php $hasPhotos = $photos->count() > 0; $hasVideos = $videos->count() > 0; @endphp
  <section class="si-media" aria-label="Store photos and videos">
    @if(!$hasPhotos && !$hasVideos)
      <div class="si-empty"><i class="fas fa-images"></i>No photos or videos have been added yet.</div>
    @else
      @if($hasPhotos && $hasVideos)
      <div class="si-tabs" role="tablist" aria-label="Media type">
        <button type="button" class="si-tab" role="tab" id="siTabPhotos" aria-controls="siPanelPhotos" aria-selected="true" data-panel="siPanelPhotos">
          <i class="fas fa-camera"></i> Photos <small>{{ $photos->count() }}</small>
        </button>
        <button type="button" class="si-tab" role="tab" id="siTabVideos" aria-controls="siPanelVideos" aria-selected="false" data-panel="siPanelVideos">
          <i class="fas fa-video"></i> Videos <small>{{ $videos->count() }}</small>
        </button>
      </div>
      @endif

      @if($hasPhotos)
      <div class="si-panel" id="siPanelPhotos" role="tabpanel" aria-label="Photos">
        <div class="si-slider" data-type="photo" data-autoplay="5500" tabindex="0" aria-roledescription="carousel" aria-label="Store photos">
          <div class="si-viewport">
            <div class="si-track">
              @foreach($photos as $photo)
                <div class="si-slide" role="group" aria-roledescription="slide" aria-label="Photo {{ $loop->iteration }} of {{ $photos->count() }}">
                  <button type="button" class="si-photo" data-src="{{ asset($photo->file) }}" data-caption="{{ $photo->title }}" aria-label="Enlarge photo{{ $photo->title ? ': ' . $photo->title : '' }}">
                    <img src="{{ asset($photo->file) }}" alt="{{ $photo->title ?: 'Photo of ' . $name }}" @if(!$loop->first) loading="lazy" @endif>
                  </button>
                  @if($photo->title)<div class="si-cap">{{ $photo->title }}</div>@endif
                </div>
              @endforeach
            </div>
          </div>
          @if($photos->count() > 1)
            <button type="button" class="si-nav si-prev" aria-label="Previous photo"><i class="fas fa-chevron-left"></i></button>
            <button type="button" class="si-nav si-next" aria-label="Next photo"><i class="fas fa-chevron-right"></i></button>
            <div class="si-dots">
              @foreach($photos as $photo)<button type="button" class="si-dot" aria-label="Go to photo {{ $loop->iteration }}"></button>@endforeach
            </div>
          @endif
          <div class="si-count"><span class="si-cur">1</span> / {{ $photos->count() }}</div>
        </div>
        @if($photos->count() > 1)
        <div class="si-thumbs" aria-label="Photo thumbnails">
          @foreach($photos as $photo)
            <button type="button" class="si-thumb" aria-label="Show photo {{ $loop->iteration }}"><img src="{{ asset($photo->file) }}" alt="" loading="lazy"></button>
          @endforeach
        </div>
        @endif
      </div>
      @endif

      @if($hasVideos)
      <div class="si-panel" id="siPanelVideos" role="tabpanel" aria-label="Videos" @if($hasPhotos) hidden @endif>
        <div class="si-slider" data-type="video" tabindex="0" aria-roledescription="carousel" aria-label="Store videos">
          <div class="si-viewport">
            <div class="si-track">
              @foreach($videos as $video)
                <div class="si-slide is-video" role="group" aria-roledescription="slide" aria-label="Video {{ $loop->iteration }} of {{ $videos->count() }}">
                  <video controls preload="metadata" playsinline src="{{ asset($video->file) }}"></video>
                  @if($video->title)<div class="si-cap">{{ $video->title }}</div>@endif
                </div>
              @endforeach
            </div>
          </div>
          @if($videos->count() > 1)
            <button type="button" class="si-nav si-prev" aria-label="Previous video"><i class="fas fa-chevron-left"></i></button>
            <button type="button" class="si-nav si-next" aria-label="Next video"><i class="fas fa-chevron-right"></i></button>
          @endif
          <div class="si-count"><span class="si-cur">1</span> / {{ $videos->count() }}</div>
        </div>
        @if($videos->count() > 1)
        <div class="si-thumbs" aria-label="Video list">
          @foreach($videos as $video)
            <button type="button" class="si-thumb vid" aria-label="Show video {{ $loop->iteration }}"><i class="fas fa-play-circle"></i><span>{{ $video->title ?: 'Video ' . $loop->iteration }}</span></button>
          @endforeach
        </div>
        @endif
      </div>
      @endif
    @endif
  </section>

  {{-- ===== STORE DETAILS ===== --}}
  <div class="si-details-head">
    <h2>Visit Us</h2>
    <p>Find our address, contact details, opening hours and location on the map.</p>
  </div>

  <div class="si-layout">
    <div class="si-info">

      <div class="si-row">
        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
        <div>
          <h3>Address</h3>
          @if($address)
            <p>{{ $address }}</p>
          @else
            <p>Our store address has not been added yet.</p>
          @endif
          @if($strip)
            <p>{{ rtrim($strip, '.') }}</p>
          @endif
        </div>
      </div>

      <div class="si-row">
        <i class="fas fa-phone-alt" aria-hidden="true"></i>
        <div>
          <h3>Contact</h3>
          @if($phone)
            <p>Phone: <a class="si-link" href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">{{ $phone }}</a></p>
          @endif
          @if($email)
            <p>Email: <a class="si-link" href="mailto:{{ $email }}">{{ $email }}</a></p>
          @endif
          @if(!$phone && !$email)
            <p>Our contact details have not been added yet.</p>
          @endif
        </div>
      </div>

      <div class="si-row">
        <i class="fas fa-clock" aria-hidden="true"></i>
        <div>
          <h3>Opening Hours</h3>
          @if($hours)
            <p>{{ rtrim($hours, '.') }}</p>
          @else
            <p>Our working hours have not been added yet.</p>
          @endif
        </div>
      </div>

      @if(count($payments))
      <div class="si-row">
        <i class="fas fa-credit-card" aria-hidden="true"></i>
        <div>
          <h3>Payment Methods</h3>
          <p>{{ $list($payments) }}</p>
        </div>
      </div>
      @endif

      @if($fb || $ig)
      <div class="si-row">
        <i class="fas fa-share-alt" aria-hidden="true"></i>
        <div>
          <h3>Follow Us</h3>
          <p>News and updates from {{ $name }} on {{ $list(array_filter([$fb ? 'Facebook' : null, $ig ? 'Instagram' : null])) }}.</p>
          <div class="si-social">
            @if($fb)<a href="{{ $fb }}" class="fb" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>@endif
            @if($ig)<a href="{{ $ig }}" class="ig" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>@endif
          </div>
        </div>
      </div>
      @endif

    </div>

    <div class="si-map">
      @if($mapEmbedUrl)
        <iframe src="{{ $mapEmbedUrl }}" title="Map showing the location of {{ $name }}" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>
      @else
        <div class="si-map-empty"><div><i class="fas fa-map"></i>The map for {{ $name }} has not been added yet.</div></div>
      @endif
    </div>
  </div>
</main>

<div class="si-lightbox" id="siLightbox" role="dialog" aria-modal="true" aria-label="Photo viewer">
  <button class="si-lb-btn si-lb-close" id="siLbClose" aria-label="Close"><i class="fas fa-times"></i></button>
  <button class="si-lb-btn si-lb-prev" id="siLbPrev" aria-label="Previous photo"><i class="fas fa-chevron-left"></i></button>
  <figure><img id="siLbImg" src="" alt=""><figcaption id="siLbCap"></figcaption></figure>
  <button class="si-lb-btn si-lb-next" id="siLbNext" aria-label="Next photo"><i class="fas fa-chevron-right"></i></button>
</div>

<script>
(function () {
  /* ---------- Sliders ---------- */
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var sliders = [];

  Array.prototype.forEach.call(document.querySelectorAll('.si-slider'), function (root) {
    var track  = root.querySelector('.si-track');
    var slides = root.querySelectorAll('.si-slide');
    var dots   = root.querySelectorAll('.si-dot');
    var thumbs = root.parentNode.querySelectorAll('.si-thumb');
    var cur    = root.querySelector('.si-cur');
    var isVideo = root.getAttribute('data-type') === 'video';
    var delay  = parseInt(root.getAttribute('data-autoplay'), 10) || 0;
    var n = slides.length, idx = 0, timer = null, paused = false;

    function pauseVideos() {
      Array.prototype.forEach.call(root.querySelectorAll('video'), function (v) { v.pause(); });
    }
    function go(i) {
      idx = (i + n) % n;
      track.style.transform = 'translateX(' + (-100 * idx) + '%)';
      if (cur) cur.textContent = idx + 1;
      Array.prototype.forEach.call(dots, function (d, k) { d.setAttribute('aria-current', k === idx ? 'true' : 'false'); });
      Array.prototype.forEach.call(thumbs, function (t, k) {
        t.setAttribute('aria-current', k === idx ? 'true' : 'false');
        if (k === idx && t.scrollIntoView) {
          var box = t.parentNode;
          box.scrollTo({ left: t.offsetLeft - (box.clientWidth - t.clientWidth) / 2, behavior: reduce ? 'auto' : 'smooth' });
        }
      });
      if (isVideo) pauseVideos();
    }
    function next() { go(idx + 1); }
    function prev() { go(idx - 1); }
    function stop()  { if (timer) { clearInterval(timer); timer = null; } }
    function start() { stop(); if (delay && !reduce && n > 1 && !paused) timer = setInterval(next, delay); }

    var p = root.querySelector('.si-prev'), nx = root.querySelector('.si-next');
    if (p)  p.addEventListener('click',  function () { prev(); start(); });
    if (nx) nx.addEventListener('click', function () { next(); start(); });
    Array.prototype.forEach.call(dots,   function (d, k) { d.addEventListener('click', function () { go(k); start(); }); });
    Array.prototype.forEach.call(thumbs, function (t, k) { t.addEventListener('click', function () { go(k); start(); }); });

    root.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft')  { prev(); start(); }
      if (e.key === 'ArrowRight') { next(); start(); }
    });
    root.addEventListener('mouseenter', function () { paused = true; stop(); });
    root.addEventListener('mouseleave', function () { paused = false; start(); });
    root.addEventListener('focusin',    function () { paused = true; stop(); });
    root.addEventListener('focusout',   function () { paused = false; start(); });

    // swipe
    var sx = null, sy = null;
    root.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; sy = e.touches[0].clientY; stop(); }, { passive: true });
    root.addEventListener('touchend', function (e) {
      if (sx === null) return;
      var dx = e.changedTouches[0].clientX - sx, dy = e.changedTouches[0].clientY - sy;
      if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy)) { dx < 0 ? next() : prev(); }
      sx = sy = null; start();
    }, { passive: true });

    if (isVideo) {
      Array.prototype.forEach.call(root.querySelectorAll('video'), function (v) {
        v.addEventListener('play',  function () { root.classList.add('has-video-active'); });
        v.addEventListener('pause', function () { root.classList.remove('has-video-active'); });
      });
    }

    go(0); start();
    sliders.push({ root: root, start: start, stop: stop, pauseVideos: pauseVideos });
  });

  document.addEventListener('visibilitychange', function () {
    sliders.forEach(function (s) { document.hidden ? s.stop() : s.start(); });
  });

  /* ---------- Photos / Videos tabs ---------- */
  var tabs = document.querySelectorAll('.si-tab');
  Array.prototype.forEach.call(tabs, function (tab) {
    tab.addEventListener('click', function () {
      Array.prototype.forEach.call(tabs, function (t) {
        var on = t === tab;
        t.setAttribute('aria-selected', on ? 'true' : 'false');
        var panel = document.getElementById(t.getAttribute('data-panel'));
        if (panel) panel.hidden = !on;
      });
      sliders.forEach(function (s) {
        if (s.root.offsetParent === null) { s.stop(); s.pauseVideos(); } else { s.start(); }
      });
    });
  });

  /* ---------- Photo lightbox ---------- */
  var items = Array.prototype.slice.call(document.querySelectorAll('.si-photo'));
  if (!items.length) return;
  var box = document.getElementById('siLightbox'), img = document.getElementById('siLbImg'),
      cap = document.getElementById('siLbCap'), cur = 0, opener = null;
  function show(i) {
    cur = (i + items.length) % items.length;
    img.src = items[cur].dataset.src;
    cap.textContent = items[cur].dataset.caption || '';
    img.alt = items[cur].dataset.caption || 'Store photo';
  }
  function open(i) { opener = items[i]; show(i); box.classList.add('open'); document.getElementById('siLbClose').focus(); }
  function close() { box.classList.remove('open'); img.src = ''; if (opener) opener.focus(); }
  items.forEach(function (el, i) { el.addEventListener('click', function () { open(i); }); });
  document.getElementById('siLbClose').onclick = close;
  document.getElementById('siLbPrev').onclick = function () { show(cur - 1); };
  document.getElementById('siLbNext').onclick = function () { show(cur + 1); };
  box.addEventListener('click', function (e) { if (e.target === box) close(); });
  document.addEventListener('keydown', function (e) {
    if (!box.classList.contains('open')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') show(cur - 1);
    if (e.key === 'ArrowRight') show(cur + 1);
  });
})();
</script>

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
      <a href="{{ route('store.info') }}">Store Information</a>
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
      <a href="{{ route('order.myorders') }}">My Orders</a>
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
</body>
</html>