<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $settings['site_name'] ?? 'GG Pharmacy' }} - My Profile</title>
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
  body { font-family: 'Open Sans', sans-serif; font-size: 14px; color: var(--text); background: #f9f9f9; }
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

  /* ── PROFILE WORKSPACE STYLES ─────────────────────────────────────────── */
  .profile-container { max-width: 1200px; margin: 40px auto; padding: 0 40px; display: grid; grid-template-columns: 280px 1fr; gap: 30px; }
  
  .profile-sidebar { background: #fff; border: 1px solid var(--border); border-radius: 10px; padding: 30px 24px; text-align: center; height: fit-content; }
  .profile-avatar-wrap { width: 120px; height: 120px; border-radius: 50%; background: #f5f5f5; border: 3px solid #eee; display: flex; align-items: center; justify-content: center; overflow: hidden; margin: 0 auto 16px; position: relative; }
  .profile-avatar-wrap img { width: 100%; height: 100%; object-fit: cover; }
  .profile-avatar-wrap i { font-size: 50px; color: #ccc; }
  
  .profile-user-name { font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 16px; color: var(--text); margin-bottom: 4px; }
  .profile-user-email { font-size: 12px; color: var(--gray); margin-bottom: 24px; word-break: break-all; }
  
  .profile-nav-tabs { display: flex; flex-direction: column; gap: 8px; text-align: left; }
  .profile-tab-btn { display: flex; align-items: center; gap: 12px; padding: 12px 16px; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 13px; color: var(--text); background: #fafafa; border: 1px solid var(--border); border-radius: 6px; cursor: pointer; transition: all 0.2s; width: 100%; }
  .profile-tab-btn:hover, .profile-tab-btn.active { background: var(--red); color: #fff; border-color: var(--dark-red); }
  
  .profile-content-box { background: #fff; border: 1px solid var(--border); border-radius: 10px; padding: 40px; }
  .profile-section-pane { display: none; }
  .profile-section-pane.active { display: block; }
  
  .profile-pane-title { font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); margin-bottom: 6px; border-bottom: 2px solid var(--gray-light); padding-bottom: 12px; }
  .profile-pane-subtitle { font-size: 12px; color: var(--gray); margin-bottom: 24px; }
  
  /* Form components matching Modal styles */
  .form-fields-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }
  .form-field { margin-bottom: 20px; }
  .form-field label { display: block; font-size: 12px; font-weight: 700; color: #444; margin-bottom: 6px; font-family: 'Montserrat', sans-serif; }
  .form-input-wrap { position: relative; }
  .form-input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #bbb; font-size: 13px; pointer-events: none; }
  .form-input-wrap input { width: 100%; padding: 12px 14px 12px 38px; border: 1.5px solid var(--border); border-radius: 6px; font-size: 13px; outline: none; transition: border-color 0.2s; font-family: 'Open Sans', sans-serif; background: #fafafa; }
  .form-input-wrap input:focus, .form-field textarea:focus { border-color: var(--red); background: #fff; }
  
  .form-field textarea { width: 100%; padding: 12px 14px 12px 38px; border: 1.5px solid var(--border); border-radius: 6px; font-size: 13px; outline: none; resize: none; font-family: 'Open Sans', sans-serif; background: #fafafa; transition: border-color 0.2s; }
  .form-textarea-wrap { position: relative; }
  .form-textarea-wrap i { position: absolute; left: 14px; top: 14px; color: #bbb; font-size: 13px; pointer-events: none; }
  
  .avatar-upload-block { display: flex; align-items: center; gap: 20px; margin-bottom: 24px; background: #fafafa; padding: 16px; border-radius: 8px; border: 1px dashed var(--border); }
  .avatar-upload-btn { background: #fff; border: 1.5px solid var(--border); padding: 8px 16px; font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: 12px; border-radius: 4px; cursor: pointer; }
  .avatar-upload-btn:hover { border-color: var(--red); color: var(--red); }
  
  .form-submit-btn { background: var(--green); color: #fff; border: none; border-radius: 6px; padding: 12px 30px; font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 13px; cursor: pointer; transition: background 0.2s; letter-spacing: 0.5px; }
  .form-submit-btn:hover { background: var(--dark-green); }
  
  /* Status indicators */
  .alert-success-banner { background: #e8f5e9; border: 1px solid #c8e6c9; color: var(--dark-green); border-radius: 6px; padding: 14px; margin-bottom: 24px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
  .field-error-msg { color: var(--red); font-size: 11px; font-weight: 700; margin-top: 4px; display: block; }
  .input-error { border-color: var(--red) !important; background: #fff5f5 !important; }

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

  @media (max-width: 1024px) {
    .profile-container { grid-template-columns: 1fr; padding: 0 16px; margin: 20px auto; }
    .footer-grid { grid-template-columns: 1fr 1fr; }
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
    .form-fields-row { grid-template-columns: 1fr; }
    .profile-content-box { padding: 24px; }
    footer { padding: 30px 16px 20px; }
    .footer-grid { grid-template-columns: 1fr; gap: 24px; }
    .footer-bottom { flex-direction: column; gap: 12px; text-align: center; }
  }
</style>
</head>
<body>

<div class="top-bar">
  <a href="#" class="store-finder"><i class="fas fa-map-marker-alt"></i> STORE INFO</a>
  <span class="sep">|</span>
  <a href="#" class="track-order"><i class="fas fa-truck"></i> TRACK YOUR ORDER</a>
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

    <div class="cart-btn">
      <div style="position:relative;">
        <i class="fas fa-shopping-cart icon-btn"></i>
        <div class="cart-badge" style="position:absolute;top:-8px;right:-8px;">0</div>
      </div>
    </div>
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

<div class="profile-container">
  
  <div class="profile-sidebar">
    <div class="profile-avatar-wrap" id="sidebarAvatarContainer">
      @if(Auth::user()->profile_picture)
        <img src="{{ asset(Auth::user()->profile_picture) }}" alt="Avatar" id="sidebarAvatarImg">
      @else
        <i class="fas fa-user" id="sidebarAvatarIcon"></i>
        <img src="" alt="Avatar" id="sidebarAvatarImg" style="display:none;">
      @endif
    </div>
    <div class="profile-user-name">{{ Auth::user()->full_name }}</div>
    <div class="profile-user-email">{{ Auth::user()->email }}</div>
    
    @php
      // Dynamic pane retention state managed across post response redirects
      $currentPane = request('tab', session('tab', 'profile'));
    @endphp

    <div class="profile-nav-tabs">
      <button class="profile-tab-btn {{ $currentPane === 'profile' ? 'active' : '' }}" onclick="switchProfilePane('profile', this)">
        <i class="fas fa-id-card"></i> Personal Info
      </button>
      <button class="profile-tab-btn {{ $currentPane === 'password' ? 'active' : '' }}" onclick="switchProfilePane('password', this)">
        <i class="fas fa-lock"></i> Change Password
      </button>
    </div>
  </div>

  <div class="profile-content-box">
    
    @if(session('success'))
      <div class="alert-success-banner">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
      </div>
    @endif

    <div id="pane-profile" class="profile-section-pane {{ $currentPane === 'profile' ? 'active' : '' }}">
      <div class="profile-pane-title">Personal Information</div>
      <div class="profile-pane-subtitle">Manage your account basic details, contact addresses and profile picture setup.</div>
      
      <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        <div class="avatar-upload-block">
          <div class="profile-avatar-wrap" style="margin:0; width:70px; height:70px;" id="formAvatarPreview">
            @if(Auth::user()->profile_picture)
              <img src="{{ asset(Auth::user()->profile_picture) }}" alt="Avatar" id="formAvatarImg">
            @else
              <i class="fas fa-user" id="formAvatarIcon"></i>
              <img src="" alt="Avatar" id="formAvatarImg" style="display:none;">
            @endif
          </div>
          <div>
            <label class="avatar-upload-btn" for="profileFileInput">Upload New Photo</label>
            <input type="file" id="profileFileInput" name="profile_picture" accept="image/*" style="display:none;">
            <div style="font-size:11px; color:#aaa; margin-top:6px;">JPG, PNG or GIF. Max size limit 2MB.</div>
            @error('profile_picture')
              <span class="field-error-msg">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="form-fields-row">
          <div class="form-field">
            <label for="inputFirstName">First Name</label>
            <div class="form-input-wrap">
              <i class="fas fa-user"></i>
              <input type="text" id="inputFirstName" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" class="{{ $errors->has('first_name') ? 'input-error' : '' }}">
            </div>
            @error('first_name') <span class="field-error-msg">{{ $message }}</span> @enderror
          </div>
          
          <div class="form-field">
            <label for="inputLastName">Last Name</label>
            <div class="form-input-wrap">
              <i class="fas fa-user"></i>
              <input type="text" id="inputLastName" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" class="{{ $errors->has('last_name') ? 'input-error' : '' }}">
            </div>
            @error('last_name') <span class="field-error-msg">{{ $message }}</span> @enderror
          </div>
        </div>

        <div class="form-fields-row">
          <div class="form-field">
            <label for="inputEmail">Email Address</label>
            <div class="form-input-wrap">
              <i class="fas fa-envelope"></i>
              <input type="email" id="inputEmail" name="email" value="{{ old('email', Auth::user()->email) }}" class="{{ $errors->has('email') ? 'input-error' : '' }}">
            </div>
            @error('email') <span class="field-error-msg">{{ $message }}</span> @enderror
          </div>
          
          <div class="form-field">
            <label for="inputContact">Contact Number</label>
            <div class="form-input-wrap">
              <i class="fas fa-phone-alt"></i>
              <input type="tel" id="inputContact" name="contact_number" value="{{ old('contact_number', Auth::user()->contact_number) }}" class="{{ $errors->has('contact_number') ? 'input-error' : '' }}">
            </div>
            @error('contact_number') <span class="field-error-msg">{{ $message }}</span> @enderror
          </div>
        </div>

        <div class="form-field">
          <label for="inputAddress">Delivery Address</label>
          <div class="form-textarea-wrap">
            <i class="fas fa-map-marker-alt"></i>
            <textarea id="inputAddress" name="address" rows="3" class="{{ $errors->has('address') ? 'input-error' : '' }}">{{ old('address', Auth::user()->address) }}</textarea>
          </div>
          @error('address') <span class="field-error-msg">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="form-submit-btn">SAVE CHANGES</button>
      </form>
    </div>

    <div id="pane-password" class="profile-section-pane {{ $currentPane === 'password' ? 'active' : '' }}">
      <div class="profile-pane-title">Security Settings</div>
      <div class="profile-pane-subtitle">Change your system password periodically to keep authentication secure.</div>
      
      <form method="POST" action="{{ route('profile.password') }}" novalidate>
        @csrf
        @method('PUT')

        <div class="form-field" style="max-width: 500px;">
          <label for="inputCurrentPass">Current Password</label>
          <div class="form-input-wrap">
            <i class="fas fa-lock-open"></i>
            <input type="password" id="inputCurrentPass" name="current_password" placeholder="Enter existing password" class="{{ $errors->password->has('current_password') ? 'input-error' : '' }}">
          </div>
          @if($errors->password->has('current_password'))
            <span class="field-error-msg">{{ $errors->password->first('current_password') }}</span>
          @endif
        </div>

        <div class="form-fields-row" style="max-width: 500px; grid-template-columns: 1fr;">
          <div class="form-field">
            <label for="inputNewPass">New Password</label>
            <div class="form-input-wrap">
              <i class="fas fa-lock"></i>
              <input type="password" id="inputNewPass" name="password" placeholder="Minimum 8 characters long" class="{{ $errors->password->has('password') ? 'input-error' : '' }}">
            </div>
            @if($errors->password->has('password'))
              <span class="field-error-msg">{{ $errors->password->first('password') }}</span>
            @endif
          </div>
          
          <div class="form-field">
            <label for="inputConfirmPass">Confirm New Password</label>
            <div class="form-input-wrap">
              <i class="fas fa-check-double"></i>
              <input type="password" id="inputConfirmPass" name="password_confirmation" placeholder="Repeat new password code">
            </div>
          </div>
        </div>

        <button type="submit" class="form-submit-btn">UPDATE PASSWORD</button>
      </form>
    </div>

  </div>
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

<div class="shipping-bar">
  {{ $settings['shipping_message'] ?? '' }} &nbsp;<a href="#">Dismiss</a>
</div>

<script>
  // Pane handling mechanism
  function switchProfilePane(paneId, button) {
    document.querySelectorAll('.profile-section-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.profile-tab-btn').forEach(b => b.classList.remove('active'));
    
    document.getElementById('pane-' + paneId).classList.add('active');
    button.classList.add('active');

    // Update query params context safely without reloading
    const url = new URL(window.location);
    url.searchParams.set('tab', paneId);
    window.history.pushState({}, '', url);
  }

  // Dismiss sticky bar
  document.querySelector('.shipping-bar a').addEventListener('click', e => {
    e.preventDefault();
    e.target.closest('.shipping-bar').style.display = 'none';
  });

  // Photo live presentation updating engine
  const profileFileInput = document.getElementById('profileFileInput');
  if (profileFileInput) {
    profileFileInput.addEventListener('change', function() {
      const file = this.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        // Form Preview components
        const formImg = document.getElementById('formAvatarImg');
        const formIcon = document.getElementById('formAvatarIcon');
        formImg.src = e.target.result;
        formImg.style.display = 'block';
        if (formIcon) formIcon.style.display = 'none';

        // Sidebar card preview components synchronization 
        const sideImg = document.getElementById('sidebarAvatarImg');
        const sideIcon = document.getElementById('sidebarAvatarIcon');
        sideImg.src = e.target.result;
        sideImg.style.display = 'block';
        if (sideIcon) sideIcon.style.display = 'none';
      };
      reader.readAsDataURL(file);
    });
  }

  // Notification engine implementation
  (function () {
    const toggle   = document.getElementById('notifToggle');
    const dropdown = document.getElementById('notifDropdown');
    const badge    = document.getElementById('notifBadge');
    const markAll  = document.getElementById('notifMarkAll');

    if (!toggle || !dropdown) return;

    function countUnread() { return document.querySelectorAll('#notifList .notif-item.unread').length; }
    function refreshBadge() {
      const n = countUnread();
      badge.textContent = n;
      badge.style.display = n > 0 ? 'flex' : 'none';
    }

    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      dropdown.classList.toggle('open');
    });

    document.querySelectorAll('#notifList .notif-item').forEach(item => {
      item.addEventListener('click', function () {
        this.classList.remove('unread');
        const dot = this.querySelector('.notif-dot');
        if (dot) dot.remove();
        refreshBadge();
      });
    });

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

    document.addEventListener('click', function (e) {
      if (!dropdown.contains(e.target) && e.target !== toggle) {
        dropdown.classList.remove('open');
      }
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') dropdown.classList.remove('open');
    });

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