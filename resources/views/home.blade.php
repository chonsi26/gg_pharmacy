<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $settings['site_name'] ?? 'GG Pharmacy' }} - {{ $settings['tagline'] ?? 'Search for Generic and Branded Medicine' }}</title>
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
</style>
</head>
<body>

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
    <img src="{{ asset($settings['logo'] ?? 'images/logo.png') }}" alt="{{ $settings['site_name'] ?? 'GG Pharmacy' }}">
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
    <i class="fas fa-user icon-btn"></i>
    <i class="far fa-heart icon-btn"></i>
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

<!-- HERO BANNER - IMAGE SLIDER -->
<div class="image-slider">
  <div class="slider-container">
    @foreach($sliders as $index => $slide)
      <div class="slide {{ $index === 0 ? 'active' : '' }}">
        <img src="{{ asset($slide->image) }}" alt="{{ $slide->alt }}">
      </div>
    @endforeach
  </div>
  <button class="slider-arrow prev" onclick="changeSlide(-1)" title="Previous"><i class="fas fa-chevron-left"></i></button>
  <button class="slider-arrow next" onclick="changeSlide(1)" title="Next"><i class="fas fa-chevron-right"></i></button>
  <div class="slider-controls">
    @foreach($sliders as $index => $slide)
      <span class="slider-dot {{ $index === 0 ? 'active' : '' }}" onclick="currentSlide({{ $index + 1 }})" title="Slide {{ $index + 1 }}"></span>
    @endforeach
  </div>
</div>

<script>
let currentIndex = 1, autoplayTimer;
function showSlide(n) {
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.slider-dot');
  if (n > slides.length) currentIndex = 1;
  if (n < 1) currentIndex = slides.length;
  slides.forEach(s => s.classList.remove('active'));
  dots.forEach(d => d.classList.remove('active'));
  slides[currentIndex - 1].classList.add('active');
  dots[currentIndex - 1].classList.add('active');
}
function changeSlide(n) { clearTimeout(autoplayTimer); showSlide(currentIndex += n); autoplay(); }
function currentSlide(n) { clearTimeout(autoplayTimer); showSlide(currentIndex = n); autoplay(); }
function autoplay() { autoplayTimer = setTimeout(() => { currentIndex++; showSlide(currentIndex); autoplay(); }, 4000); }
showSlide(currentIndex); autoplay();
</script>

<!-- BRAND LOGOS — loaded via Brand::ticker() scope -->
<div class="brands-ticker">
  <div class="brands-row">
    @foreach($tickerBrands as $brand)
      <img class="brand-logo" src="{{ asset($brand->ticker_image) }}" alt="{{ $brand->name }}">
    @endforeach
  </div>
</div>

<!-- SHOP BY CATEGORY -->
<div class="section-header">
  <h2>Shop by Category</h2>
  <p>View all products per category</p>
</div>
<div class="category-grid">
  @foreach($categories as $cat)
    <div class="cat-item">
      <div class="cat-circle" style="background:{{ $cat->bg_color }};">
        <i class="{{ $cat->icon_class }}" style="font-size:40px;color:{{ $cat->icon_color }};"></i>
      </div>
      <span>{{ $cat->name }}</span>
    </div>
  @endforeach
</div>

<!-- PROMO BANNERS -->
<div class="promo-banners">
  @foreach($promoBanners as $banner)
    <div class="promo-banner {{ $banner->type }}" style="gap:20px;justify-content:space-between;padding:0;">
      <img src="{{ $banner->image }}" alt="{{ $banner->alt }}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
    </div>
  @endforeach
</div>

<!-- FEATURED BRANDS — loaded via Brand::featured() scope -->
<div class="section-header">
  <h2>Featured Brands</h2>
  <p>All our exclusive brand selection</p>
</div>
<div class="featured-brands">
  <div class="brands-grid">
    @foreach($featuredBrands as $brand)
      <div class="brand-card {{ $brand->featured_color }}">
        <img src="{{ asset($brand->featured_image) }}" alt="{{ $brand->name }}" style="width:100%;height:100%;object-fit:cover;">
      </div>
    @endforeach
  </div>
</div>

<!-- OMRON BANNER -->
@if($bannerOmron)
<div class="fw-banner">
  <div style="border-radius:12px;padding:0;min-height:220px;overflow:hidden;">
    <img src="{{ $bannerOmron->image }}" alt="{{ $bannerOmron->alt }}" style="width:100%;height:100%;object-fit:cover;display:block;">
  </div>
</div>
@endif

<!-- HOT DEALS — products via Section hasMany relationship -->
<div style="padding: 0 40px 40px;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
      <h2 style="font-family:'Montserrat',sans-serif;font-size:22px;font-weight:800;color:var(--red);">{{ $hotDealsSection->label }}</h2>
      <p style="color:var(--gray);font-size:12px;">{{ $hotDealsSection->description }}</p>
    </div>
    <div style="display:flex;align-items:center;gap:12px;">
      <span style="font-family:'Montserrat',sans-serif;font-weight:800;font-size:13px;">End in:</span>
      <div style="display:flex;align-items:center;gap:6px;">
        <div><div class="count-block">00</div><div class="count-label">Days</div></div>
        <div><div class="count-block">00</div><div class="count-label">Hours</div></div>
        <div><div class="count-block">00</div><div class="count-label">Minutes</div></div>
        <div><div class="count-block">00</div><div class="count-label">Seconds</div></div>
      </div>
    </div>
  </div>
  <div class="product-carousel">
    @foreach($hotDealsSection->products as $product)
      @include('partials.product-card', ['product' => $product, 'showQty' => true])
    @endforeach
  </div>
</div>

<!-- RITEMED BANNER -->
@if($bannerRitemed)
<div class="fw-banner">
  <div style="border-radius:12px;padding:0;min-height:220px;overflow:hidden;">
    <img src="{{ $bannerRitemed->image }}" alt="{{ $bannerRitemed->alt }}" style="width:100%;height:100%;object-fit:cover;display:block;">
  </div>
</div>
@endif

<!-- SALE SECTION — products via Section hasMany relationship -->
<div class="section-row">
  <div>
    <h2>{{ $saleSection->label }}</h2>
    <p>{{ $saleSection->description }}</p>
  </div>
  <a href="{{ $saleSection->see_all_url ?? '#' }}" class="see-all-btn">See all products</a>
</div>
<div class="product-section">
  <div class="product-carousel">
    @foreach($saleSection->products as $product)
      @include('partials.product-card', ['product' => $product])
    @endforeach
  </div>
</div>

<!-- PROMO PACKS — products via Section hasMany relationship -->
<div class="section-row">
  <div>
    <h2 style="color:var(--text);">{{ $promoPackSection->label }}</h2>
    <p>{{ $promoPackSection->description }}</p>
  </div>
  <a href="{{ $promoPackSection->see_all_url ?? '#' }}" class="see-all-btn">See all products</a>
</div>
<div class="product-section">
  <div class="product-carousel">
    @foreach($promoPackSection->products as $product)
      @include('partials.product-card', ['product' => $product])
    @endforeach
  </div>
</div>

<!-- ALAXAN BANNER -->
<div class="fw-banner">
  <div style="border-radius:12px;padding:0;min-height:200px;overflow:hidden;">
    @php $bannerAlaxan = \App\Models\FullWidthBanner::forSection('alaxan'); @endphp
    @if($bannerAlaxan)
      <img src="{{ $bannerAlaxan->image }}" alt="{{ $bannerAlaxan->alt }}" style="width:100%;height:100%;object-fit:cover;display:block;">
    @endif
  </div>
</div>

<!-- BEST SELLERS -->
<div class="section-row">
  <div><h2 style="color:var(--text);">BEST SELLERS</h2><p>Top-Rated Favorites – Discover What Everyone's Loving</p></div>
  <a href="#" class="see-all-btn">See all products</a>
</div>
<div class="product-section">
  <div class="product-carousel">
    <div class="product-card"><div class="product-img"><img src="https://via.placeholder.com/140x140/eee/999?text=Immunpro" alt="Immunpro"><div class="most-sold">MOST SOLD</div></div><div class="product-info"><div class="product-cat">VITAMIN C</div><div class="product-name">Immunpro 500mg / 1...</div><div class="product-price">₱165.00</div></div></div>
    <div class="product-card"><div class="product-img"><img src="https://via.placeholder.com/140x140/eee/999?text=Fern-C" alt="Fern-C"><div class="most-sold">MOST SOLD</div></div><div class="product-info"><div class="product-cat">VITAMIN C</div><div class="product-name">Fern-C 568.18mg</div><div class="product-price">₱315.00</div></div></div>
    <div class="product-card"><div class="product-img"><img src="https://via.placeholder.com/140x140/eee/999?text=Bewell-C" alt="Bewell-C"><div class="most-sold">MOST SOLD</div></div><div class="product-info"><div class="product-cat">CALCIUM</div><div class="product-name">Bewell-C Plus Calcium</div><div class="product-price">₱11.50</div></div></div>
    <div class="product-card"><div class="product-img"><img src="https://via.placeholder.com/140x140/eee/999?text=Centrum" alt="Centrum"><div class="most-sold">MOST SOLD</div></div><div class="product-info"><div class="product-cat">MULTIVITAMIN ADULT</div><div class="product-name">Centrum Silver Advan...</div><div class="product-price">₱378.25</div></div></div>
    <div class="product-card"><div class="product-img"><img src="https://via.placeholder.com/140x140/eee/999?text=Berocca" alt="Berocca"><div class="most-sold">MOST SOLD</div></div><div class="product-info"><div class="product-cat">MULTIVITAMIN ADULT</div><div class="product-name">Berocca Performance...</div><div class="product-price">₱651.00</div></div></div>
    <div class="product-card"><div class="product-img"><img src="https://via.placeholder.com/140x140/eee/999?text=Fern-C+Caps" alt="Fern-C Caps"><div class="most-sold">MOST SOLD</div></div><div class="product-info"><div class="product-cat">VITAMIN C</div><div class="product-name">Fern-C 568.18Mg Cap...</div><div class="product-price">₱581.75</div></div></div>
  </div>
</div>

<!-- GUARDIAN SPECIAL DEALS — products via Section hasMany relationship -->
<div class="section-row">
  <div>
    <h2>{{ $guardianSection->label }}</h2>
    <p>{{ $guardianSection->description }}</p>
  </div>
  <a href="{{ $guardianSection->see_all_url ?? '#' }}" class="see-all-btn">See all products</a>
</div>
<div style="padding:0 40px 40px;">
  <div style="display:grid;grid-template-columns:280px repeat(4,1fr);gap:16px;">
    <div style="border:2px solid var(--red);border-radius:8px;padding:20px;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:280px;background:#fff;">
      <div class="most-sold" style="position:static;margin-bottom:10px;">MOST SOLD</div>
      <i class="fas fa-pump-soap" style="font-size:80px;color:#e91e63;margin-bottom:16px;"></i>
      <div style="font-size:9px;font-weight:800;color:var(--gray);text-transform:uppercase;letter-spacing:0.5px;">BABY LIQUID SOAP, PROMOTION</div>
      <div style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:13px;text-align:center;margin-top:4px;">Guardian Kids Strawberry Yogurt He...</div>
      <div style="font-family:'Montserrat',sans-serif;font-size:18px;font-weight:900;color:var(--red);margin-top:8px;">₱189.00</div>
    </div>
    @foreach($guardianSection->products as $product)
      @include('partials.product-card', ['product' => $product, 'imgHeight' => '150px'])
    @endforeach
    <div style="grid-column:2/span 4;display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:4px;">
      <div class="product-card"><div class="product-img" style="height:130px;"><img src="https://via.placeholder.com/110x110/eee/999?text=Jolly+Tots" alt="Jolly Tots"></div><div class="product-info"><div class="product-cat">BABY TAPED DIAPERS</div><div class="product-name">Jolly Tots Comfydri La...</div><div class="product-price">₱327.00</div></div></div>
      <div class="product-card"><div class="product-img" style="height:130px;"><img src="https://via.placeholder.com/110x110/eee/999?text=Baby+Daily" alt="Baby Daily"></div><div class="product-info"><div class="product-cat">BABY LIQUID SOAP</div><div class="product-name">Guardian Baby Daily ...</div><div class="product-price">₱189.00</div></div></div>
      <div class="product-card"><div class="product-img" style="height:130px;"><img src="https://via.placeholder.com/110x110/eee/999?text=Comfort+Pads+2" alt="Comfort Pads 2"></div><div class="product-info"><div class="product-cat">INCONTINENCE-OSTOMY AIDS</div><div class="product-name">Guardian Comfort &amp;A...</div><div class="product-price">₱195.00</div></div></div>
      <div class="product-card"><div class="product-img" style="height:130px;"><img src="https://via.placeholder.com/110x110/eee/999?text=Cotton+Pads" alt="Cotton Pads"></div><div class="product-info"><div class="product-cat">PADS</div><div class="product-name">GUARDIAN Cotton Faci...</div><div class="product-price">₱79.00</div></div></div>
    </div>
  </div>
</div>

<!-- SMART REFILLS BANNER -->
<div class="fw-banner">
  <div style="background:linear-gradient(135deg,var(--red),#880e4f,#4a148c);border-radius:12px;padding:40px 60px;color:#fff;display:flex;align-items:center;justify-content:space-between;min-height:220px;">
    <div>
      <div style="font-size:12px;color:rgba(255,255,255,0.7);font-weight:700;letter-spacing:2px;margin-bottom:6px;">{{ $settings['site_name'] ?? 'GGPharmacy' }}</div>
      <h2 style="font-family:'Montserrat',sans-serif;font-size:44px;font-weight:900;line-height:1.1;">SMART REFILLS,<br>BIGGER SAVINGS!</h2>
    </div>
    <div style="background:rgba(255,255,255,0.1);border:2px solid rgba(255,255,255,0.3);border-radius:8px;padding:20px;text-align:center;">
      <div style="font-size:10px;color:rgba(255,255,255,0.7);margin-bottom:4px;">{{ $settings['site_name'] ?? 'GGPharmacy' }}</div>
      <div style="font-family:'Montserrat',sans-serif;font-size:16px;font-weight:900;color:#fff;">Paracetamol</div>
      <div style="font-size:10px;color:rgba(255,255,255,0.7);">500mg Tablet<br>Analgesic/Antipyretic</div>
      <div style="font-family:'Montserrat',sans-serif;font-size:14px;font-weight:900;color:#ffd600;margin-top:6px;">100 Tablets</div>
    </div>
  </div>
</div>

<!-- GG PHARMACY GENERICS — products split into top/bottom rows -->
<div class="section-row">
  <div>
    <h2>{{ $genericsSection->label }}</h2>
    <p>{{ $genericsSection->description }}</p>
  </div>
  <a href="{{ $genericsSection->see_all_url ?? '#' }}" class="see-all-btn">See all products</a>
</div>
<div style="padding:0 40px 40px;">
  <div style="display:grid;grid-template-columns:280px repeat(4,1fr);gap:16px;">
    <div style="border:2px solid var(--red);border-radius:8px;padding:24px;background:#fff;display:flex;flex-direction:column;justify-content:space-between;">
      <div>
        <h3 style="font-family:'Montserrat',sans-serif;font-size:18px;font-weight:900;color:var(--red);margin-bottom:6px;">GG Pharmacy Generics</h3>
        <p style="font-size:12px;color:var(--gray);margin-bottom:12px;">the Brand you can Trust with Assured Quality &amp; Big Savings</p>
        <div style="font-size:12px;color:#555;margin-bottom:16px;">Safe • Quality • Effective</div>
        <div style="font-family:'Montserrat',sans-serif;font-weight:800;font-size:14px;color:var(--red);">Low Price Everyday</div>
      </div>
      <div>
        <a href="#" style="background:var(--green);color:#fff;font-family:'Montserrat',sans-serif;font-weight:800;font-size:13px;padding:10px 20px;border-radius:6px;display:inline-block;margin-bottom:12px;">SHOP NOW</a>
        <a href="#" style="display:block;text-align:center;background:var(--red);color:#fff;font-family:'Montserrat',sans-serif;font-weight:800;font-size:13px;padding:12px;border-radius:6px;">VIEW ALL NOW</a>
      </div>
    </div>
    @foreach($genericsTop as $product)
      @include('partials.product-card', ['product' => $product, 'imgHeight' => '150px'])
    @endforeach
    <div style="grid-column:2/span 4;display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:4px;">
      @foreach($genericsBottom as $product)
        @include('partials.product-card', ['product' => $product, 'imgHeight' => '130px'])
      @endforeach
    </div>
  </div>
</div>

<!-- FEATURED PRODUCTS — products via Section hasMany relationship -->
<div class="featured-section">
  <h2>{{ $featuredSection->label }}</h2>
  <div class="product-carousel">
    @foreach($featuredSection->products as $product)
      @include('partials.product-card', ['product' => $product])
    @endforeach
  </div>
</div>

<!-- EXCLUSIVELY FOR YOU -->
<div class="exclusively" style="padding:40px;">
  <div style="height:2px;background:var(--border);margin-bottom:20px;"></div>
  <h2>EXCLUSIVELY FOR YOU</h2>
  <div style="height:2px;background:var(--border);margin-top:20px;"></div>
</div>

<!-- BLOGS — category loaded via BelongsTo relationship -->
<div class="blogs">
  <h2>CATCH UP WITH OUR LATEST BLOGS</h2>
  <div class="blogs-grid">
    @foreach($blogs as $blog)
      <div class="blog-card">
        @if($blog->hasImage())
          <div class="blog-img">
            <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}">
          </div>
        @elseif($blog->hasIcon())
          <div class="blog-img" style="height:150px;background:{{ $blog->icon_bg ?? '#f0f0f0' }};display:flex;align-items:center;justify-content:center;">
            <i class="{{ $blog->icon_class }}" style="font-size:60px;color:{{ $blog->icon_color ?? '#999' }};opacity:0.4;"></i>
          </div>
        @endif
        <div style="display:flex;">
          <div class="blog-date" style="min-width:50px;"><span>{{ $blog->day }}</span><small>{{ $blog->month }}</small></div>
          <div class="blog-body" style="padding:14px 14px 10px;">
            {{-- Category name shown via BelongsTo relationship --}}
            @if($blog->category)
              <div class="product-cat" style="margin-bottom:4px;">{{ $blog->category->name }}</div>
            @endif
            <h4>{{ $blog->title }}</h4>
            <p>{{ $blog->excerpt }}</p>
            <div class="no-comments">{{ $blog->commentLabel() }}</div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

<!-- FOOTER -->
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
  {{ $settings['shipping_message'] ?? '' }} &nbsp;<a href="#">Dismiss</a>
</div>

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

  // Trigger buttons (only present for guests)
  const loginBtn    = document.getElementById('loginBtn');
  const registerBtn = document.getElementById('registerBtn');
  if (loginBtn)    loginBtn.addEventListener('click',    e => { e.preventDefault(); openModal(loginModal); });
  if (registerBtn) registerBtn.addEventListener('click', e => { e.preventDefault(); openModal(registerModal); });

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