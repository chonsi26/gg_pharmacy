<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $settings['site_name'] ?? 'GG Pharmacy' }} - My Wishlist</title>
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
    --red-tint: #fff3f3;
    --green-tint: #e8f5e9;
    --surface: #f6f6f7;
  }
  body { font-family: 'Open Sans', sans-serif; font-size: 14px; color: var(--text); background: #f9f9f9; }
  a { text-decoration: none; color: inherit; }
  button { font-family: inherit; }
  :focus-visible { outline: 2px solid var(--red); outline-offset: 2px; }
  [hidden] { display: none !important; }

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
  .notif-footer { padding: 11px 16px; border-top: 1px solid var(--border); text-align: center; }
  .notif-footer a { font-size: 12px; font-weight: 700; color: var(--red); font-family: 'Montserrat', sans-serif; }
  .notif-footer a:hover { text-decoration: underline; }

  /* NAV */
  nav { background: var(--red); border-bottom: 2px solid var(--dark-red); }
  nav ul { display: flex; align-items: center; list-style: none; padding: 0 40px; }
  nav ul li { position: relative; }
  nav ul li > a { display: block; padding: 14px 16px; font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 700; color: #fff; white-space: nowrap; }
  nav ul li > a:hover, nav ul li.active > a { color: #ffd600; }

  /* HAMBURGER */
  .hamburger { display: none; background: none; border: none; cursor: pointer; padding: 10px 16px; color: #fff; font-size: 22px; margin-left: auto; }

  /* GREEN STRIP */
  .green-strip { background: var(--green); padding: 5px 40px; font-size: 11px; color: #fff; font-weight: 700; text-align: center; }

  /* ── WISHLIST PAGE ─────────────────────────────────────────────────────── */
  .wl-bg { background: var(--surface); }
  .page { width: 100%; max-width: 1180px; margin: 0 auto; padding: 24px 24px 56px; min-height: 420px; }
  .crumbs { font-size: 12px; color: var(--gray); margin-bottom: 14px; display: flex; gap: 8px; align-items: center; }
  .crumbs a { font-weight: 600; color: #444; }
  .crumbs a:hover { color: var(--red); }
  .crumbs i { font-size: 9px; color: #bbb; }

  .page-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
  .page-head h1 { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 28px; line-height: 1.15; color: var(--text); }
  .page-head p { margin-top: 6px; color: var(--gray); font-size: 13.5px; }
  .btn-text { background: #fff; border: 1px solid var(--border); color: var(--gray); border-radius: 6px; padding: 9px 14px; font-weight: 700; font-size: 12.5px; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; transition: all 0.15s; }
  .btn-text:hover { color: var(--red); border-color: var(--red); }

  .alert-success-banner { background: #e8f5e9; border: 1px solid #c8e6c9; color: var(--dark-green); border-radius: 6px; padding: 14px; margin-bottom: 20px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 10px; }

  .layout { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 24px; align-items: start; }

  /* List */
  .wl-list { background: #fff; border: 1px solid var(--border); border-radius: 10px; overflow: hidden; }
  .wl-item { display: grid; grid-template-columns: 96px minmax(0, 1fr) 150px auto; gap: 20px; align-items: center; padding: 18px 20px; border-bottom: 1px solid #efefef; transition: opacity 0.25s ease, transform 0.25s ease; }
  .wl-item:last-child { border-bottom: none; }
  .wl-item.is-removing { opacity: 0; transform: translateX(16px); }

  .wl-thumb { width: 96px; height: 96px; border: 1px solid #efefef; border-radius: 8px; background: #fafafa; display: flex; align-items: center; justify-content: center; overflow: hidden; }
  .wl-thumb img { width: 100%; height: 100%; object-fit: contain; padding: 6px; }

  .wl-info { min-width: 0; }
  .wl-meta { font-size: 12px; color: var(--gray); font-weight: 600; margin-bottom: 3px; }
  .wl-name { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 15px; line-height: 1.3; color: var(--text); display: block; }
  a.wl-name:hover { color: var(--red); }
  .wl-generic { font-size: 12.5px; color: var(--gray); margin-top: 2px; }
  .wl-tags { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 9px; }
  .tag { display: inline-flex; align-items: center; gap: 5px; font-size: 11.5px; font-weight: 700; padding: 3px 9px; border-radius: 20px; }
  .tag-stock { background: var(--green-tint); color: var(--green); }
  .tag-low { background: #fff4e0; color: #b25e00; }
  .tag-out { background: #eee; color: #777; }
  .tag-rx { background: #eef3ff; color: #2a4fa3; }
  .wl-added { font-size: 11.5px; color: #999; }

  .wl-price { text-align: right; }
  .wl-price .now { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 17px; color: var(--text); }
  .wl-price .was { font-size: 12px; color: #999; text-decoration: line-through; margin-right: 6px; }
  .wl-price .save { display: inline-block; margin-top: 4px; font-size: 11.5px; font-weight: 700; color: var(--red); background: var(--red-tint); padding: 2px 8px; border-radius: 20px; }

  .wl-actions { display: flex; align-items: center; gap: 8px; }
  .btn-cart { background: var(--red); color: #fff; border: none; border-radius: 6px; height: 40px; padding: 0 18px; font-weight: 800; font-size: 12.5px; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; transition: background 0.15s; white-space: nowrap; }
  .btn-cart:hover { background: var(--dark-red); }
  .btn-cart:disabled { background: #d5d5d5; color: #888; cursor: not-allowed; }
  .btn-cart.is-loading { opacity: 0.7; cursor: wait; }
  .btn-remove { width: 40px; height: 40px; border: 1px solid var(--border); background: #fff; border-radius: 6px; color: #888; cursor: pointer; font-size: 14px; transition: all 0.15s; }
  .btn-remove:hover { color: var(--red); border-color: var(--red); background: var(--red-tint); }
  .btn-remove:disabled { opacity: 0.5; cursor: wait; }

  /* Summary */
  .summary { background: #fff; border: 1px solid var(--border); border-radius: 10px; padding: 20px; position: sticky; top: 16px; }
  .summary h2 { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 16px; margin-bottom: 14px; }
  .sum-row { display: flex; justify-content: space-between; align-items: baseline; padding: 9px 0; font-size: 13.5px; color: var(--gray); border-bottom: 1px solid #f0f0f0; }
  .sum-row strong { color: var(--text); font-weight: 700; }
  .sum-row.total { border-bottom: none; padding-top: 14px; }
  .sum-row.total span { color: var(--text); font-weight: 700; }
  .sum-row.total strong { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 20px; color: var(--red); }
  .btn-all { width: 100%; margin-top: 10px; height: 46px; justify-content: center; font-size: 13.5px; }
  .summary .link { display: block; text-align: center; margin-top: 14px; font-weight: 700; font-size: 13px; color: var(--red); }
  .summary .link:hover { text-decoration: underline; }
  .summary .note { margin-top: 14px; font-size: 12px; color: #888; line-height: 1.5; display: flex; gap: 8px; }
  .summary .note i { color: var(--red); margin-top: 2px; }

  /* Empty state */
  .empty { background: #fff; border: 1px solid var(--border); border-radius: 10px; padding: 72px 24px; text-align: center; }
  .empty .ring { width: 84px; height: 84px; border-radius: 50%; background: var(--red-tint); color: var(--red); display: flex; align-items: center; justify-content: center; font-size: 34px; margin: 0 auto 20px; }
  .empty h2 { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 20px; }
  .empty p { color: var(--gray); margin: 8px auto 22px; max-width: 380px; line-height: 1.55; }
  .empty .btn-cart { height: 44px; padding: 0 26px; }

  /* Clear dialog */
  dialog { border: none; border-radius: 12px; padding: 26px; width: min(92vw, 400px); box-shadow: 0 20px 60px rgba(0,0,0,0.25); margin: auto; }
  dialog::backdrop { background: rgba(0,0,0,0.45); }
  dialog h3 { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 17px; margin-bottom: 8px; }
  dialog p { color: var(--gray); line-height: 1.55; }
  .dlg-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; }

  /* Toast */
  .toast-wrap { position: fixed; top: 90px; right: 20px; z-index: 10000; display: flex; flex-direction: column; gap: 10px; pointer-events: none; }
  .toast { background: #fff; border-left: 4px solid var(--red); box-shadow: 0 6px 22px rgba(0,0,0,0.15); border-radius: 6px; padding: 12px 16px; font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 10px; min-width: 220px; max-width: 320px; opacity: 0; transform: translateX(30px); transition: opacity 0.3s ease, transform 0.3s ease; }
  .toast.show { opacity: 1; transform: translateX(0); }
  .toast i { color: #2e9e4f; }
  .toast.error i { color: var(--red); }

  /* FOOTER */
  footer { background: #222; color: #aaa; padding: 50px 40px 30px; margin-top: 40px; }
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

  /* RESPONSIVE */
  @media (max-width: 1024px) {
    .footer-grid { grid-template-columns: 1fr 1fr; }
  }
  @media (max-width: 960px) {
    .layout { grid-template-columns: 1fr; }
    .summary { position: static; order: -1; }
  }
  @media (max-width: 768px) {
    .top-bar { padding: 6px 16px; gap: 10px; flex-wrap: wrap; justify-content: center; }
    .top-bar .sep { display: none; }
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
    footer { padding: 30px 16px 20px; }
    .footer-grid { grid-template-columns: 1fr; gap: 24px; }
    .footer-bottom { flex-direction: column; gap: 12px; text-align: center; }
    .page { padding: 18px 14px 40px; }
    .page-head h1 { font-size: 23px; }
    .wl-item { grid-template-columns: 80px minmax(0, 1fr); gap: 12px 14px; padding: 16px 14px; }
    .wl-thumb { width: 80px; height: 80px; }
    .wl-price { grid-column: 2; text-align: left; }
    .wl-actions { grid-column: 1 / -1; }
    .btn-cart { flex: 1; justify-content: center; }
    .toast-wrap { top: auto; bottom: 70px; left: 12px; right: 12px; align-items: center; }
  }
  @media (prefers-reduced-motion: reduce) {
    .wl-item, .toast { transition: none; }
  }
</style>
</head>
<body>

@php
  $count = $wishlists->count();

  // Stock: use $stockByProduct when the controller provides it, otherwise fall back to isInStock().
  $stockByProduct = $stockByProduct ?? null;
  $stockQty = function ($product) use ($stockByProduct) {
      if (is_array($stockByProduct) && array_key_exists($product->id, $stockByProduct)) {
          return (int) $stockByProduct[$product->id];
      }
      return $product->isInStock() ? 999 : 0;
  };

  $availableItems = $wishlists->filter(fn ($w) => $stockQty($w->product) > 0);
  $availableTotal = $availableItems->sum(fn ($w) => $w->product->price);
  $wlCount        = $count;
@endphp

<div class="top-bar">
  <a href="{{ route('store.info') }}" class="store-finder"><i class="fas fa-map-marker-alt"></i> STORE INFO</a>
  <span class="sep">|</span>
  <a href="{{ route('order.myorders') }}" class="track-order"><i class="fas fa-truck"></i> MY ORDERS</a>
  <span class="sep">|</span>
  <a href="{{ route('wishlists.index') }}"><i class="fas fa-heart"></i> WISHLIST</a>
  <span class="sep">|</span>
  @auth
    <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="margin:0;">
      @csrf
      <a href="#" onclick="event.preventDefault();document.getElementById('logoutForm').submit();" style="color:#333;font-weight:600;display:flex;align-items:center;gap:5px;">
        <i class="fas fa-sign-out-alt"></i> LOG OUT
      </a>
    </form>
  @endauth
  <div class="social">
    <a href="{{ $settings['facebook_url'] ?? '#' }}"><i class="fab fa-facebook-f"></i></a>
    <a href="{{ $settings['instagram_url'] ?? '#' }}" class="insta"><i class="fab fa-instagram"></i></a>
  </div>
</div>

<div class="header">
  <div class="logo">
    <a href="{{ route('home') }}">
      <img src="{{ asset($settings['logo'] ?? 'images/logo.png') }}" alt="{{ $settings['site_name'] ?? 'GG Pharmacy' }}">
    </a>
  </div>

  <form action="{{ route('home') }}" method="GET" class="search-bar">
    <input type="text" name="query" placeholder="Search for Generic and Branded Medicine">
    <button type="submit"><i class="fas fa-search"></i></button>
  </form>

  <div class="header-right">
    <div class="phone-box">
      <i class="fas fa-phone-alt ph-icon"></i>
      <div>
        <small style="color:#555;font-size:10px;font-weight:600;">{{ $settings['phone_label'] ?? 'Call Us Now' }}</small>
        <span>{{ $settings['phone'] ?? '' }}</span>
      </div>
    </div>

    <a href="{{ route('profile') }}" title="My Profile"><i class="fas fa-user icon-btn" style="color:var(--red);"></i></a>

    {{-- NOTIFICATION HEART --}}
    <div class="notif-wrapper" id="notifWrapper">
      <i class="far fa-heart icon-btn" id="notifToggle" title="Notifications"></i>
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
        </div>
        <div class="notif-footer">
          <a href="#">View all notifications</a>
        </div>
      </div>
    </div>

    <a href="{{ route('cart.index') }}" class="cart-btn" title="My Cart">
      <div style="position:relative;">
        <i class="fas fa-shopping-cart icon-btn"></i>
        <div class="cart-badge" style="position:absolute;top:-8px;right:-8px;">0</div>
      </div>
    </a>
  </div>
</div>

<nav>
  <div class="nav-inner">
    <span class="nav-brand" style="display:none" id="nav-brand-label">{{ $settings['site_name'] ?? 'GG PHARMACY' }}</span>
    <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu">
      <i class="fas fa-bars"></i>
    </button>
  </div>
  <ul id="navMenu">
    <li><a href="{{ route('home') }}">HOME</a></li>
    {{-- Falls back safely to dynamic Categories directly if home variables are absent --}}
    @foreach($categories ?? \App\Models\Category::active()->orderBy('sort_order')->get() as $cat)
      <li>
        <a href="{{ route('home', ['category_id' => $cat->id]) }}">
          {{ strtoupper($cat->name) }}
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

<div class="green-strip">
  {{ $settings['location_strip'] ?? '' }} &nbsp;|&nbsp;
  <i class="fas fa-phone-alt"></i> {{ $settings['phone'] ?? '' }} &nbsp;|&nbsp;
  <i class="fas fa-clock"></i> {{ $settings['working_hours'] ?? '' }}
</div>

<div class="wl-bg">
<main class="page">

  <div class="crumbs" aria-label="Breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('profile') }}">My Profile</a>
    <i class="fas fa-chevron-right"></i>
    <span>My Wishlist</span>
  </div>

  <div class="page-head">
    <div>
      <h1>My Wishlist</h1>
      <p id="headSub">
        @if($count > 0)
          {{ $count }} {{ \Illuminate\Support\Str::plural('product', $count) }} saved for later
        @else
          Products you save will appear here.
        @endif
      </p>
    </div>
    <button type="button" class="btn-text" id="clearBtn" @if($count === 0) hidden @endif>
      <i class="far fa-trash-alt"></i> Clear wishlist
    </button>
  </div>

  @if(session('success'))
    <div class="alert-success-banner"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif

  {{-- Empty state --}}
  <section class="empty" id="emptyState" @if($count > 0) hidden @endif>
    <div class="ring"><i class="far fa-heart"></i></div>
    <h2>Your wishlist is empty</h2>
    <p>Select the heart on any product page to save it here, so it's easy to find and buy later.</p>
    <a href="{{ route('home') }}" class="btn-cart"><i class="fas fa-pills"></i> Browse products</a>
  </section>

  @if($count > 0)
  <div class="layout" id="wlLayout">

    <section class="wl-list" aria-label="Wishlist items">
      {{-- Single hidden quantity shared by every "Add to cart" button (cart.js reads data-qty-target) --}}
      <input type="hidden" id="wlQty" value="1">

      @foreach($wishlists as $item)
        @php
          $p       = $item->product;
          $qty     = $stockQty($p);
          $inStock = $qty > 0;
        @endphp
        <article class="wl-item"
                 data-product-id="{{ $p->id }}"
                 data-url="{{ route('wishlists.destroy', $p) }}"
                 data-price="{{ $p->price }}"
                 data-available="{{ $inStock ? 1 : 0 }}">

          <a href="{{ route('product.show', $p) }}" class="wl-thumb" aria-label="View {{ $p->name }}">
            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}" loading="lazy">
          </a>

          <div class="wl-info">
            @if($p->category || $p->brand)
              <div class="wl-meta">{{ collect([$p->category?->name, $p->brand?->name])->filter()->implode(' · ') }}</div>
            @endif
            <a href="{{ route('product.show', $p) }}" class="wl-name">{{ $p->name }}</a>
            @if($p->generic_name)
              <div class="wl-generic">{{ $p->generic_name }}</div>
            @endif
            <div class="wl-tags">
              @if(! $inStock)
                <span class="tag tag-out"><i class="fas fa-circle-xmark"></i> Out of stock</span>
              @elseif($qty <= 10)
                <span class="tag tag-low"><i class="fas fa-circle-exclamation"></i> Only {{ $qty }} left</span>
              @else
                <span class="tag tag-stock"><i class="fas fa-circle-check"></i> In stock</span>
              @endif
              @if($p->requires_prescription)
                <span class="tag tag-rx"><i class="fas fa-file-prescription"></i> Prescription required</span>
              @endif
              <span class="wl-added">Saved {{ $item->created_at->format('M j, Y') }}</span>
            </div>
          </div>

          <div class="wl-price">
            @if($p->hasDiscount())
              <span class="was">{{ $p->formattedOldPrice() }}</span>
            @endif
            <span class="now">{{ $p->formattedPrice() }}</span>
            @if($p->hasDiscount())
              <div><span class="save">Save {{ $p->discountPercent() }}%</span></div>
            @endif
          </div>

          <div class="wl-actions">
            <button type="button"
                    class="btn-cart js-add-to-cart"
                    data-product-id="{{ $p->id }}"
                    data-qty-target="#wlQty"
                    @disabled(! $inStock)>
              <i class="fas fa-cart-plus"></i> {{ $inStock ? 'Add to cart' : 'Unavailable' }}
            </button>
            <button type="button" class="btn-remove js-remove" title="Remove from wishlist" aria-label="Remove {{ $p->name }} from wishlist">
              <i class="far fa-trash-alt"></i>
            </button>
          </div>
        </article>
      @endforeach
    </section>

    <aside class="summary" aria-label="Wishlist summary">
      <h2>Wishlist summary</h2>
      <div class="sum-row"><span>Saved products</span><strong id="sumCount">{{ $count }}</strong></div>
      <div class="sum-row"><span>Available now</span><strong id="sumAvail">{{ $availableItems->count() }}</strong></div>
      <div class="sum-row total"><span>Total of available</span><strong id="sumTotal">₱{{ number_format($availableTotal, 2) }}</strong></div>

      <button type="button" class="btn-cart btn-all" id="addAllBtn" @disabled($availableItems->isEmpty())>
        <i class="fas fa-cart-plus"></i> Add all available to cart
      </button>
      <a href="{{ route('home') }}" class="link">Continue shopping</a>

      <div class="note">
        <i class="fas fa-circle-info"></i>
        <span>Prices and availability can change. Items stay in your wishlist after you add them to the cart.</span>
      </div>
    </aside>
  </div>
  @endif
</main>
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
      <h4>ABOUT {{ $settings['site_name'] }}</h4>
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

<div class="shipping-bar">
  {{ $settings['shipping_message'] ?? '' }} &nbsp;<a href="#">Dismiss</a>
</div>

{{-- Clear-all confirmation --}}
<dialog id="clearDialog" aria-labelledby="clearTitle">
  <h3 id="clearTitle">Clear your wishlist?</h3>
  <p>All saved products will be removed from your wishlist. This can't be undone.</p>
  <div class="dlg-actions">
    <button type="button" class="btn-text" id="clearCancel">Cancel</button>
    <button type="button" class="btn-cart" id="clearConfirm">Clear wishlist</button>
  </div>
</dialog>

<div class="toast-wrap" id="toastWrap" aria-live="polite"></div>

{{-- Shared add-to-cart logic: POSTs to /cart/add, updates .cart-badge elements,
     and fires cart:added / cart:error events on document. --}}
<script src="{{ asset('js/cart.js') }}" data-add-url="{{ route('cart.add') }}" data-count-url="{{ route('cart.count') }}"></script>

<script>
(function () {
  const csrf       = document.querySelector('meta[name="csrf-token"]').content;
  const clearUrl   = @json(\Illuminate\Support\Facades\Route::has('wishlists.clear') ? route('wishlists.clear') : null);

  const layout      = document.getElementById('wlLayout');
  const emptyState  = document.getElementById('emptyState');
  const clearBtn    = document.getElementById('clearBtn');
  const headSub     = document.getElementById('headSub');
  const headerCount = document.getElementById('wishlistBadge');
  const sumCount    = document.getElementById('sumCount');
  const sumAvail    = document.getElementById('sumAvail');
  const sumTotal    = document.getElementById('sumTotal');
  const addAllBtn   = document.getElementById('addAllBtn');

  // Shipping bar dismiss
  const shipLink = document.querySelector('.shipping-bar a');
  if (shipLink) shipLink.addEventListener('click', e => {
    e.preventDefault();
    e.target.closest('.shipping-bar').style.display = 'none';
  });

  // ── Toast ────────────────────────────────────────────────────────────────
  function toast(message, isError) {
    const wrap = document.getElementById('toastWrap');
    const el = document.createElement('div');
    el.className = 'toast' + (isError ? ' error' : '');
    el.innerHTML = '<i class="fas ' + (isError ? 'fa-circle-exclamation' : 'fa-circle-check') + '"></i><span></span>';
    el.querySelector('span').textContent = message;
    wrap.appendChild(el);
    requestAnimationFrame(() => el.classList.add('show'));
    setTimeout(() => { el.classList.remove('show'); setTimeout(() => el.remove(), 350); }, 2800);
  }

  async function request(url, method) {
    const res = await fetch(url, {
      method,
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
      credentials: 'same-origin'
    });
    if (!res.ok) throw new Error('Request failed');
    return res.json().catch(() => ({}));
  }

  // ── Keep counts / totals in sync after any change ────────────────────────
  const peso = (n) => '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

  function refresh() {
    const items = Array.from(document.querySelectorAll('.wl-item'));
    const avail = items.filter(i => i.dataset.available === '1');
    const total = avail.reduce((s, i) => s + parseFloat(i.dataset.price || 0), 0);
    const n = items.length;

    if (sumCount) sumCount.textContent = n;
    if (sumAvail) sumAvail.textContent = avail.length;
    if (sumTotal) sumTotal.textContent = peso(total);
    if (addAllBtn) addAllBtn.disabled = avail.length === 0;
    if (headerCount) { headerCount.textContent = n; headerCount.style.display = n > 0 ? 'flex' : 'none'; }
    if (headSub) headSub.textContent = n > 0
      ? n + ' ' + (n === 1 ? 'product' : 'products') + ' saved for later'
      : 'Products you save will appear here.';

    if (n === 0) {
      if (layout) layout.hidden = true;
      if (clearBtn) clearBtn.hidden = true;
      if (emptyState) emptyState.hidden = false;
    }
  }

  // ── Remove one item ──────────────────────────────────────────────────────
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.js-remove');
    if (!btn || btn.disabled) return;
    const row = btn.closest('.wl-item');
    btn.disabled = true;
    try {
      const data = await request(row.dataset.url, 'DELETE');
      row.classList.add('is-removing');
      setTimeout(() => { row.remove(); refresh(); }, 250);
      toast(data.message || 'Removed from your wishlist.');
    } catch (err) {
      btn.disabled = false;
      toast('Could not remove this item. Please try again.', true);
    }
  });

  // ── Clear everything ─────────────────────────────────────────────────────
  const dlg = document.getElementById('clearDialog');
  if (clearBtn) clearBtn.addEventListener('click', () => dlg.showModal());
  document.getElementById('clearCancel').addEventListener('click', () => dlg.close());
  document.getElementById('clearConfirm').addEventListener('click', async () => {
    try {
      if (clearUrl) {
        await request(clearUrl, 'DELETE');
      } else {
        // No bulk route defined: remove each item individually
        const urls = Array.from(document.querySelectorAll('.wl-item')).map(i => i.dataset.url);
        await Promise.all(urls.map(u => request(u, 'DELETE')));
      }
      dlg.close();
      document.querySelectorAll('.wl-item').forEach(i => i.remove());
      refresh();
      toast('Your wishlist has been cleared.');
    } catch (err) {
      dlg.close();
      toast('Could not clear your wishlist. Please try again.', true);
    }
  });

  // ── Add to cart (single) — feedback from cart.js events ──────────────────
  let bulk = null; // { added, failed, total }
  document.addEventListener('cart:added', (e) => {
    if (bulk) { bulk.added++; bulk.check(); return; }
    toast((e.detail && e.detail.message) || 'Added to your cart!');
  });
  document.addEventListener('cart:error', (e) => {
    if (bulk) { bulk.failed++; bulk.check(); return; }
    toast((e.detail && e.detail.message) || 'Could not add to cart. Please try again.', true);
  });

  // ── Add all available to cart ────────────────────────────────────────────
  if (addAllBtn) {
    addAllBtn.addEventListener('click', () => {
      const ids = Array.from(document.querySelectorAll('.wl-item[data-available="1"]')).map(i => i.dataset.productId);
      if (!ids.length || !window.CartUI || typeof window.CartUI.addToCart !== 'function') return;

      addAllBtn.disabled = true;
      addAllBtn.classList.add('is-loading');

      let timer;
      const finish = () => {
        clearTimeout(timer);
        const { added, failed } = bulk;
        bulk = null;
        addAllBtn.classList.remove('is-loading');
        addAllBtn.disabled = false;
        if (added) toast(added + ' ' + (added === 1 ? 'item' : 'items') + ' added to your cart.');
        if (failed) toast(failed + ' ' + (failed === 1 ? 'item' : 'items') + ' could not be added.', true);
      };

      bulk = {
        added: 0, failed: 0, total: ids.length,
        check() { if (this.added + this.failed >= this.total) finish(); }
      };
      timer = setTimeout(() => { if (bulk) finish(); }, 10000); // safety net

      ids.forEach(id => window.CartUI.addToCart(id, 1));
    });
  }

  // ── Notification dropdown ────────────────────────────────────────────────
  (function () {
    const toggle   = document.getElementById('notifToggle');
    const dropdown = document.getElementById('notifDropdown');
    const badge    = document.getElementById('notifBadge');
    const markAll  = document.getElementById('notifMarkAll');
    if (!toggle || !dropdown) return;

    function refreshBadge() {
      const n = document.querySelectorAll('#notifList .notif-item.unread').length;
      badge.textContent = n;
      badge.style.display = n > 0 ? 'flex' : 'none';
    }
    function markRead(item) {
      item.classList.remove('unread');
      const dot = item.querySelector('.notif-dot');
      if (dot) dot.remove();
    }

    toggle.addEventListener('click', e => { e.stopPropagation(); dropdown.classList.toggle('open'); });

    document.querySelectorAll('#notifList .notif-item').forEach(item => {
      item.addEventListener('click', () => { markRead(item); refreshBadge(); });
    });
    if (markAll) markAll.addEventListener('click', () => {
      document.querySelectorAll('#notifList .notif-item.unread').forEach(markRead);
      refreshBadge();
    });

    document.addEventListener('click', e => {
      if (!dropdown.contains(e.target) && e.target !== toggle) dropdown.classList.remove('open');
    });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') dropdown.classList.remove('open'); });

    refreshBadge();
  })();
})();
</script>

@if(!empty($settings['chatbase_id']))
<script>
(function(){if(!window.chatbase||window.chatbase("getState")!=="initialized"){window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}})}const onLoad=function(){const script=document.createElement("script");script.src="https://www.chatbase.co/embed.min.js";script.id="{{ $settings['chatbase_id'] }}";script.domain="www.chatbase.co";document.body.appendChild(script)};if(document.readyState==="complete"){onLoad()}else{window.addEventListener("load",onLoad)}})();
</script>
@endif
</body>
</html>