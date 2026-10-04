<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Track Order {{ $order->order_number }} – {{ $settings['site_name'] ?? 'No Pharmacy Name' }}</title>
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
     TRACK ORDER — SHOPEE-STYLE LAYOUT
     Accent colour: change --t-accent to restyle the whole page.
     ══════════════════════════════════════════════════════════════════════ */
  .track-page { --t-accent: var(--green); --t-accent-dark: var(--dark-green); --t-line: rgba(0,0,0,.09); background: #f5f5f5; }
  .track-wrap { max-width: 1200px; margin: 0 auto; padding: 20px 40px 70px; }

  .t-card { background: #fff; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,.05); margin-bottom: 14px; overflow: hidden; }
  .t-card-title { padding: 16px 20px; border-bottom: 1px solid var(--t-line); font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 15px; color: var(--text); }

  /* 1 · Top bar: back + order id + status */
  .t-topbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 16px 20px; flex-wrap: wrap; }
  .back-link { display: inline-flex; align-items: center; gap: 8px; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 14px; color: #888; }
  .back-link:hover { color: var(--t-accent); }
  .t-topbar-right { display: flex; align-items: center; gap: 12px; font-family: 'Montserrat', sans-serif; font-size: 14px; text-transform: uppercase; }
  .t-topbar-right .oid { color: #555; font-weight: 600; }
  .t-topbar-right .sep { width: 1px; height: 16px; background: #ccc; }
  .t-topbar-right .st { color: var(--t-accent); font-weight: 700; }
  .t-topbar-right .st.done { color: #6b7280; }

  /* 2 · Map (top of page) */
  .t-map { position: relative; width: 100%; height: 340px; background: #eef0f2; }
  .t-map iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
  .t-place { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; padding: 16px 20px; border-top: 1px solid var(--t-line); }
  .t-place-main { display: flex; align-items: flex-start; gap: 12px; min-width: 0; }
  .t-place-pin { width: 38px; height: 38px; border-radius: 50%; background: #e8f5e9; color: var(--t-accent); display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
  .t-place-name { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 15px; }
  .t-place-line { font-size: 13px; color: #757575; margin-top: 2px; line-height: 1.5; }
  .t-actions { display: flex; gap: 8px; flex-wrap: wrap; }
  .t-btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; border-radius: 2px; padding: 10px 18px; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 13px; border: 1px solid var(--t-accent); white-space: nowrap; }
  .t-btn.primary { background: var(--t-accent); color: #fff; }
  .t-btn.primary:hover { background: var(--t-accent-dark); border-color: var(--t-accent-dark); }
  .t-btn.ghost { background: #fff; color: var(--t-accent); }
  .t-btn.ghost:hover { background: #f1f8f2; }

  /* 3 · Status + horizontal progress */
  .t-status { padding: 22px 20px 6px; display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
  .t-status h1 { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 18px; color: var(--t-accent); line-height: 1.3; }
  .t-status.is-done h1 { color: #4b5563; }
  .t-status p { font-size: 13px; color: #757575; margin-top: 4px; }
  .t-countdown { display: inline-flex; align-items: center; gap: 8px; background: #fff7e6; border: 1px solid #ffe0a3; color: #b26a00; border-radius: 2px; padding: 8px 12px; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 12.5px; }

  .t-steps { display: grid; grid-template-columns: repeat(4, 1fr); padding: 28px 20px 30px; }
  .t-step { text-align: center; position: relative; }
  .t-step::before { content: ''; position: absolute; top: 25px; left: -50%; width: 100%; height: 4px; background: #e0e0e0; z-index: 0; }
  .t-step:first-child::before { display: none; }
  .t-step.reached::before { background: var(--t-accent); }
  .t-step-icon { position: relative; z-index: 1; width: 54px; height: 54px; border-radius: 50%; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center; background: #fff; border: 4px solid #e0e0e0; color: #c4c4c4; font-size: 20px; }
  .t-step.reached .t-step-icon { background: var(--t-accent); border-color: var(--t-accent); color: #fff; }
  .t-step.current .t-step-icon { box-shadow: 0 0 0 6px rgba(46,125,50,.16); }
  .t-step-label { font-size: 13px; color: #9a9a9a; line-height: 1.35; }
  .t-step.reached .t-step-label { color: var(--text); font-weight: 600; }
  .t-step-time { font-size: 12px; color: #9a9a9a; margin-top: 3px; min-height: 16px; }
  .t-stripe { height: 3px; background: repeating-linear-gradient(45deg, #6fa6d6 0, #6fa6d6 33px, transparent 0, transparent 41px, #f18d9b 0, #f18d9b 74px, transparent 0, transparent 82px); }

  /* 4 · Details + timeline */
  .t-detail { display: grid; grid-template-columns: 300px 1fr; }
  .t-detail-side { padding: 20px; border-right: 1px solid var(--t-line); background: #fafafa; }
  .t-side-title { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 14px; margin-bottom: 12px; }
  .t-kv { display: flex; justify-content: space-between; gap: 12px; font-size: 13px; padding: 6px 0; }
  .t-kv span:first-child { color: #757575; }
  .t-kv span:last-child { font-weight: 600; text-align: right; }
  .t-side-block { margin-top: 18px; padding-top: 16px; border-top: 1px dashed #d9d9d9; }
  .t-side-line { font-size: 13px; color: #555; line-height: 1.55; display: flex; gap: 10px; margin-bottom: 8px; }
  .t-side-line i { width: 14px; margin-top: 3px; color: var(--t-accent); text-align: center; }
  .t-reminder { margin-top: 18px; background: #f1f8f2; border: 1px solid #b3d3b6; border-radius: 3px; padding: 12px 14px; font-size: 12.5px; color: var(--dark-green); line-height: 1.55; display: flex; gap: 10px; }
  .t-reminder i { margin-top: 2px; }

  .t-timeline { list-style: none; padding: 22px 24px 8px; }
  .t-event { display: grid; grid-template-columns: 110px 22px 1fr; column-gap: 14px; padding-bottom: 24px; position: relative; }
  .t-event-time { text-align: right; font-size: 13px; color: #9a9a9a; line-height: 1.45; }
  .t-event-time strong { display: block; font-weight: 600; color: #757575; }
  .t-event-dot { position: relative; display: flex; justify-content: center; }
  .t-event-dot::before { content: ''; position: absolute; top: 20px; bottom: -24px; width: 2px; background: #e0e0e0; }
  .t-event:last-child .t-event-dot::before { display: none; }
  .t-event-dot span { position: relative; z-index: 1; width: 10px; height: 10px; margin-top: 4px; border-radius: 50%; background: #d0d0d0; }
  .t-event.latest .t-event-dot span { width: 22px; height: 22px; margin-top: 0; background: var(--t-accent); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; }
  .t-event.latest .t-event-time, .t-event.latest .t-event-time strong { color: var(--t-accent); }
  .t-event-title { font-size: 14px; color: #757575; line-height: 1.4; }
  .t-event.latest .t-event-title { color: var(--t-accent); font-weight: 700; }
  .t-event-text { font-size: 13px; color: #9a9a9a; margin-top: 3px; line-height: 1.55; }
  .t-event.latest .t-event-text { color: #666; }

  /* 5 · Items */
  .t-item { display: flex; align-items: center; gap: 14px; padding: 14px 20px; border-bottom: 1px solid #f0f0f0; }
  .t-item img { width: 72px; height: 72px; object-fit: cover; border: 1px solid var(--t-line); flex-shrink: 0; background: #fafafa; }
  .t-item-name { flex: 1; min-width: 0; font-size: 14px; color: var(--text); line-height: 1.4; }
  .t-item-qty { font-size: 14px; color: #757575; white-space: nowrap; }
  .t-totals { background: #fffefb; border-top: 1px dotted #d9d9d9; }
  .t-total-row { display: flex; justify-content: flex-end; align-items: center; font-size: 13px; color: #757575; padding: 10px 20px; border-bottom: 1px dotted rgba(0,0,0,.09); }
  .t-total-row:last-child { border-bottom: none; }
  .t-total-row .lbl { width: 200px; text-align: right; padding-right: 20px; }
  .t-total-row .val { width: 140px; text-align: right; color: var(--text); }
  .t-total-row.grand .val { font-family: 'Montserrat', sans-serif; font-size: 22px; font-weight: 700; color: var(--t-accent); }
  .t-total-row.grand { padding: 16px 20px; }

  @media (max-width: 900px) {
    .t-detail { grid-template-columns: 1fr; }
    .t-detail-side { border-right: none; border-bottom: 1px solid var(--t-line); }
  }
  @media (max-width: 600px) {
    .track-wrap { padding: 10px 0 50px; }
    .t-card { border-radius: 0; margin-bottom: 8px; }
    .t-topbar-right { font-size: 12px; }
    .t-map { height: 260px; }
    .t-actions { width: 100%; }
    .t-actions .t-btn { flex: 1; }
    .t-steps { padding: 22px 6px 24px; }
    .t-step::before { top: 20px; height: 3px; }
    .t-step-icon { width: 42px; height: 42px; font-size: 16px; border-width: 3px; }
    .t-step-label { font-size: 11px; }
    .t-step-time { font-size: 10px; }
    .t-timeline { padding: 18px 14px 4px; }
    .t-event { grid-template-columns: 70px 20px 1fr; column-gap: 10px; }
    .t-event-time { font-size: 11px; }
    .t-item { padding: 12px 14px; }
    .t-item img { width: 60px; height: 60px; }
    .t-total-row { padding: 10px 14px; }
    .t-total-row .lbl { width: auto; flex: 1; }
    .t-total-row .val { width: auto; }
  }
  @media (prefers-reduced-motion: no-preference) {
    .t-step.current .t-step-icon { animation: tPulse 2.2s ease-in-out infinite; }
  }
  @keyframes tPulse { 0%, 100% { box-shadow: 0 0 0 5px rgba(46,125,50,.18); } 50% { box-shadow: 0 0 0 10px rgba(46,125,50,.04); } }
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
<div class="track-page">
<div class="track-wrap">

  @php
    $stepIndex = ['pending' => 0, 'confirmed' => 1, 'ready' => 2, 'picked_up' => 3][$order->status] ?? 0;
    $isDone    = $order->status === 'picked_up';
    $placeName = $settings['site_name'] ?? 'GG Pharmacy';
    $address   = collect([$settings['address_line1'] ?? null, $settings['address_line2'] ?? null, $settings['address_line3'] ?? null])->filter()->implode(', ');

    $statusLabel = [
      'pending'   => 'Waiting for confirmation',
      'confirmed' => 'Preparing your order',
      'ready'     => 'Ready for pickup',
      'picked_up' => 'Picked up',
    ][$order->status] ?? 'Processing';

    // label, icon, timestamp reached
    $steps = [
      ['Order placed',     'fa-clipboard-check', $order->created_at],
      ['Confirmed',        'fa-circle-check',    $order->confirmed_at],
      ['Ready for pickup', 'fa-box-open',        $order->ready_at],
      ['Picked up',        'fa-bag-shopping',    $order->picked_up_at],
    ];

    // Reached events only, newest first.
    $events = [];
    if ($order->picked_up_at) {
      $events[] = ['Order picked up', 'You collected your order. Thank you for shopping with us!', $order->picked_up_at];
    }
    if ($order->ready_at) {
      $events[] = ['Ready for pickup', 'Your items are set aside at the pharmacy counter. Give your order number to the pharmacist when you arrive.', $order->ready_at];
    }
    if ($order->confirmed_at) {
      $events[] = ['Order confirmed', 'The pharmacy confirmed your reservation and is preparing your items.', $order->confirmed_at];
    }
    $events[] = ['Order placed', 'We received your order and sent it to the pharmacy for confirmation.', $order->created_at];
  @endphp

  {{-- TOP BAR --}}
  <div class="t-card">
    <div class="t-topbar">
      <a href="{{ route('order.myorders') }}" class="back-link"><i class="fas fa-chevron-left"></i> BACK</a>
      <div class="t-topbar-right">
        <span class="oid">Order ID. {{ $order->order_number }}</span>
        <span class="sep"></span>
        <span class="st {{ $isDone ? 'done' : '' }}">{{ $statusLabel }}</span>
      </div>
    </div>
  </div>

  {{-- MAP (TOP) --}}
  <div class="t-card">
    <div class="t-map">
      <iframe
        src="{{ $settings['map_link'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125558.09496252601!2d124.99607994999998!3d10.44611855!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x330711db96aa79ff%3A0xc658142f36148f6c!2sSogod%2C%20Southern%20Leyte!5e0!3m2!1sen!2sph!4v1791036364789!5m2!1sen!2sph' }}"
        title="Map showing {{ $placeName }}"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="strict-origin-when-cross-origin"></iframe>
    </div>
    <div class="t-place">
      <div class="t-place-main">
        <div class="t-place-pin"><i class="fas fa-location-dot"></i></div>
        <div>
          <div class="t-place-name">{{ $placeName }}</div>
          @if($address)
            <div class="t-place-line">{{ $address }}</div>
          @endif
        </div>
      </div>
      <div class="t-actions">
        <a class="t-btn primary" target="_blank" rel="noopener"
           href="https://www.google.com/maps/dir/?api=1&destination=10.383637,124.9791196">
          <i class="fas fa-diamond-turn-right"></i> Get directions
        </a>
        @if(!empty($settings['phone']))
          <a class="t-btn ghost" href="tel:{{ preg_replace('/[^0-9+]/', '', $settings['phone']) }}"><i class="fas fa-phone-alt"></i> Call pharmacy</a>
        @endif
      </div>
    </div>
  </div>

  {{-- STATUS + PROGRESS --}}
  <div class="t-card">
    <div class="t-status {{ $isDone ? 'is-done' : '' }}">
      <div>
        @if($isDone)
          <h1>Order picked up</h1>
          <p>This order was collected on {{ $order->picked_up_at?->format('M j, Y g:i A') }}.</p>
        @elseif($order->status === 'ready')
          <h1>Your order is ready for pickup</h1>
          <p>Head to {{ $placeName }} and show your order number at the counter.</p>
        @elseif($order->status === 'confirmed')
          <h1>The pharmacy is preparing your order</h1>
          <p>We'll let you know as soon as it's ready for pickup.</p>
        @else
          <h1>Waiting for pharmacy confirmation</h1>
          <p>Your reservation has been sent. This usually doesn't take long.</p>
        @endif
      </div>
      @if($order->status === 'ready' && $pickupDeadline)
        <div class="t-countdown" id="pickupCountdown" data-deadline="{{ $pickupDeadline->toIso8601String() }}">
          <i class="fas fa-hourglass-half"></i>
          <span id="pickupCountdownText">Pick up before {{ $pickupDeadline->format('g:i A, M j') }}</span>
        </div>
      @endif
    </div>

    <div class="t-steps">
      @foreach($steps as $i => $step)
        @php
          $reached = $isDone || $i <= $stepIndex;
          $current = !$isDone && $i === $stepIndex;
        @endphp
        <div class="t-step {{ $reached ? 'reached' : '' }} {{ $current ? 'current' : '' }}">
          <div class="t-step-icon"><i class="fas {{ $step[1] }}"></i></div>
          <div class="t-step-label">{{ $step[0] }}</div>
          <div class="t-step-time">{{ $step[2] ? $step[2]->format('g:i A, M j') : '' }}</div>
        </div>
      @endforeach
    </div>
    <div class="t-stripe"></div>
  </div>

  {{-- DETAILS + TIMELINE --}}
  <div class="t-card">
    <div class="t-detail">
      <aside class="t-detail-side">
        <div class="t-side-title">Order details</div>
        <div class="t-kv"><span>Order number</span><span>{{ $order->order_number }}</span></div>
        <div class="t-kv"><span>Placed</span><span>{{ $order->created_at->format('M j, Y g:i A') }}</span></div>
        <div class="t-kv"><span>Payment</span><span>{{ $order->isOnlinePayment() ? 'App' : 'Cash on pickup' }}</span></div>

        <div class="t-side-block">
          <div class="t-side-title">Pickup address</div>
          <div class="t-side-line" style="font-weight:700;color:var(--text);"><i class="fas fa-store"></i> {{ $placeName }}</div>
          @if($address)
            <div class="t-side-line"><i class="fas fa-map-marker-alt"></i> {{ $address }}</div>
          @endif
          @if(!empty($settings['working_hours']))
            <div class="t-side-line"><i class="fas fa-clock"></i> {{ $settings['working_hours'] }}</div>
          @endif
          @if(!empty($settings['phone']))
            <div class="t-side-line"><i class="fas fa-phone-alt"></i> {{ $settings['phone'] }}</div>
          @endif
        </div>

        @if($order->status === 'ready')
          <div class="t-reminder">
            <i class="fas fa-circle-info"></i>
            <div>
              Bring a valid ID and your order number <strong>{{ $order->order_number }}</strong>.
              @if($order->isOnlinePayment())
                Your payment is made through the app, so there's nothing to pay at the counter.
              @else
                You'll pay <strong>₱{{ number_format($order->total, 2) }}</strong> in cash at the counter.
              @endif
            </div>
          </div>
        @endif
      </aside>

      <ul class="t-timeline">
        @foreach($events as $i => $event)
          <li class="t-event {{ $i === 0 ? 'latest' : '' }}">
            <div class="t-event-time">
              <strong>{{ $event[2]->format('g:i A') }}</strong>
              {{ $event[2]->format('M j, Y') }}
            </div>
            <div class="t-event-dot"><span>@if($i === 0)<i class="fas fa-check"></i>@endif</span></div>
            <div>
              <div class="t-event-title">{{ $event[0] }}</div>
              <div class="t-event-text">{{ $event[1] }}</div>
            </div>
          </li>
        @endforeach
      </ul>
    </div>
  </div>

  {{-- ITEMS --}}
  <div class="t-card">
    <div class="t-card-title">Items in this order</div>
    @foreach($order->items as $item)
      @php $product = $item->product; @endphp
      <div class="t-item">
        @if($product)
          <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
        @endif
        <div class="t-item-name">{{ $product->name ?? 'Product no longer available' }}</div>
        <div class="t-item-qty">× {{ $item->quantity }}</div>
      </div>
    @endforeach
    <div class="t-totals">
      <div class="t-total-row grand"><span class="lbl">Order total</span><span class="val">₱{{ number_format($order->total, 2) }}</span></div>
      <div class="t-total-row"><span class="lbl">Payment method</span><span class="val">{{ $order->isOnlinePayment() ? 'App' : 'Cash on pickup' }}</span></div>
    </div>
  </div>

</div>
</div>

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

<script>
  // Header: notification dropdown + cart badge (same behaviour as My Orders)
  (function () {
    var toggle = document.getElementById('notifToggle');
    var dropdown = document.getElementById('notifDropdown');
    var badge = document.getElementById('notifBadge');
    var markAll = document.getElementById('notifMarkAll');
    if (toggle && dropdown) {
      function refreshBadge() {
        var n = document.querySelectorAll('#notifList .notif-item.unread').length;
        if (badge) { badge.textContent = n; badge.style.display = n > 0 ? 'flex' : 'none'; }
      }
      toggle.addEventListener('click', function (e) { e.stopPropagation(); dropdown.classList.toggle('open'); });
      document.addEventListener('click', function (e) { if (!dropdown.contains(e.target)) dropdown.classList.remove('open'); });
      document.querySelectorAll('#notifList .notif-item').forEach(function (item) {
        item.addEventListener('click', function () {
          this.classList.remove('unread');
          var dot = this.querySelector('.notif-dot'); if (dot) dot.remove();
          refreshBadge();
        });
      });
      if (markAll) markAll.addEventListener('click', function () {
        document.querySelectorAll('#notifList .notif-item.unread').forEach(function (i) {
          i.classList.remove('unread'); var d = i.querySelector('.notif-dot'); if (d) d.remove();
        });
        refreshBadge();
      });
      refreshBadge();
    }
  })();

  document.addEventListener('DOMContentLoaded', function () {
    fetch('{{ route('cart.count') }}', { headers: { 'Accept': 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (d) { document.querySelectorAll('.cart-badge').forEach(function (b) { b.textContent = d.cart_count; }); })
      .catch(function () {});
  });

  // Pickup countdown
  (function () {
    var box = document.getElementById('pickupCountdown');
    var text = document.getElementById('pickupCountdownText');
    if (!box || !text) return;
    var deadline = new Date(box.dataset.deadline).getTime();
    function tick() {
      var ms = deadline - Date.now();
      if (ms <= 0) { text.textContent = 'Pickup window has ended. Call the pharmacy if you still need this order.'; return; }
      var h = Math.floor(ms / 3600000), m = Math.floor((ms % 3600000) / 60000);
      text.textContent = (h > 0 ? h + 'h ' : '') + m + 'm left to pick up';
      setTimeout(tick, 30000);
    }
    tick();
  })();
</script>

</body>
</html>