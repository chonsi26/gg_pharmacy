<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $siteName }} — Admin Login</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --red:       #CC1F1F;
    --red-h:     #b51a1a;
    --red-light: #f5e0e0;
    --green:     #2D7A2D;
    --white:     #ffffff;
    --bg:        #f0f2f5;
    --surface:   #ffffff;
    --border:    #dddfe2;
    --text:      #1c1e21;
    --muted:     #65676b;
    --subtle:    #a0a3a7;
    --input-bg:  #ffffff;
    --focus:     rgba(204,31,31,0.12);
  }

  [data-theme="dark"] {
    --bg:        #18191a;
    --surface:   #242526;
    --border:    #3a3b3c;
    --text:      #e4e6eb;
    --muted:     #b0b3b8;
    --subtle:    #6a6b6c;
    --input-bg:  #3a3b3c;
    --focus:     rgba(204,31,31,0.18);
  }

  html, body {
    height: 100%;
    font-family: 'Inter', sans-serif;
    background: var(--bg);
    color: var(--text);
    transition: background .3s, color .3s;
  }

  .theme-toggle {
    position: fixed;
    top: 16px; right: 16px; z-index: 10;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 6px;
    width: 34px; height: 34px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--muted);
    transition: color .2s, border-color .2s, background .3s;
  }
  .theme-toggle:hover { border-color: var(--red); color: var(--text); }

  .page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 24px;
    gap: 52px;
  }

  .left {
    flex: 0 1 440px;
    padding-bottom: 60px;
    animation: fadeUp .45s ease both;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 20px;
  }

  .logo-img {
    width: 100%;
    max-width: 360px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
  }

  .brand-tag {
    font-size: 18px;
    font-weight: 400;
    color: var(--muted);
    line-height: 1.5;
  }

  .right {
    flex: 0 1 396px;
    animation: fadeUp .45s ease .05s both;
  }

  .card {
    background: var(--surface);
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,.1), 0 8px 16px rgba(0,0,0,.06);
    padding: 20px 16px 24px;
    transition: background .3s;
  }
  [data-theme="dark"] .card {
    box-shadow: 0 2px 8px rgba(0,0,0,.4);
  }

  .card-header {
    text-align: center;
    margin-bottom: 18px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border);
  }
  .card-header h2 {
    font-size: 17px;
    font-weight: 600;
    color: var(--text);
  }
  .card-header p {
    font-size: 13px;
    color: var(--muted);
    margin-top: 3px;
  }

  .field { margin-bottom: 12px; }

  input {
    width: 100%;
    height: 52px;
    padding: 0 14px;
    background: var(--input-bg);
    border: 1.5px solid var(--border);
    border-radius: 6px;
    font-family: 'Inter', sans-serif;
    font-size: 16px;
    color: var(--text);
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .3s;
    
  }
  input::placeholder { color: var(--subtle); font-size: 15px; }
  input:focus {
    border-color: var(--red);
    box-shadow: 0 0 0 3px var(--focus);
  }

  .pw-wrap {
    position: relative;
    display: flex;
    align-items: center;
  }
  .pw-wrap input { padding-right: 44px; }
  .pw-btn {
    position: absolute; right: 12px;
    background: none; border: none;
    cursor: pointer; color: var(--subtle);
    display: flex; padding: 2px;
    transition: color .2s;
  }
  .pw-btn:hover { color: var(--muted); }

  .err-msg {
    display: none;
    font-size: 12px;
    color: var(--red);
    margin-top: 5px;
    padding-left: 2px;
  }
  .err-msg.on { display: block; }

  .btn-signin {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    height: 52px;
    margin-top: 4px;
    background: var(--red);
    color: var(--white);
    border: none;
    border-radius: 6px;
    font-family: 'Inter', sans-serif;
    font-size: 20px;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s, box-shadow .2s;
  }
  .btn-signin:hover {
    background: var(--red-h);
    box-shadow: 0 2px 10px rgba(204,31,31,.3);
  }
  .btn-signin:disabled { opacity: .65; cursor: not-allowed; box-shadow: none; }

  .spinner {
    width: 16px; height: 16px;
    border: 2px solid rgba(255,255,255,.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .6s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  .divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 18px 0;
  }
  .div-line { flex: 1; height: 1px; background: var(--border); }
  .div-text { font-size: 13px; color: var(--subtle); }

  .card-footer {
    text-align: center;
    padding-top: 18px;
    border-top: 1px solid var(--border);
  }
  .card-footer p {
    font-size: 12px;
    color: var(--muted);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
  }
  .card-footer svg { color: var(--green); }

  .below-card {
    margin-top: 20px;
    text-align: center;
    font-size: 12px;
    color: var(--muted);
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .shake { animation: shake .35s ease; }
  @keyframes shake {
    0%,100% { transform: translateX(0); }
    25%      { transform: translateX(-5px); }
    75%      { transform: translateX(5px); }
  }

  .toast {
    position: fixed;
    bottom: 24px; left: 50%;
    transform: translateX(-50%) translateY(60px);
    background: var(--green);
    color: var(--white);
    padding: 10px 18px;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 500;
    display: flex; align-items: center; gap: 7px;
    box-shadow: 0 4px 14px rgba(0,0,0,.15);
    transition: transform .35s cubic-bezier(.22,1,.36,1);
    z-index: 99;
    white-space: nowrap;
  }
  .toast.on { transform: translateX(-50%) translateY(0); }

  @media (max-width: 768px) {
    .page {
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      padding: 36px 16px 52px;
      gap: 20px;
    }
    .left {
      align-items: center;
      padding-bottom: 0;
      flex: none;
      width: 100%;
    }
    .brand-tag { font-size: 15px; text-align: center; }
    .right { flex: none; width: 100%; max-width: 420px; }
    .logo-img { max-width: 280px; }
  }

  /* Register link */
  .register-row {
    text-align: center;
    margin-top: 14px;
    font-size: 14px;
    color: var(--muted);
  }
  .register-row a {
    color: var(--red);
    font-weight: 600;
    text-decoration: none;
    margin-left: 5px;
    transition: color .2s;
  }
  .register-row a:hover { color: var(--red-h); text-decoration: underline; }
</style>
</head>
<body>

<button class="theme-toggle" id="themeBtn" aria-label="Toggle theme">
  <span id="themeIco"></span>
</button>

@if (session('status'))
<div class="toast on" id="toast">
  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
  {{ session('status') }}
</div>
@else
<div class="toast" id="toast">
  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
  Access granted. Redirecting…
</div>
@endif

<div class="page">

  <div class="left">
    <img src="{{ $logo ? asset($logo) : 'https://www.bticino.ph/modules/custom/legrand_ecat/assets/img/no-image.png' }}" alt="{{ $siteName }}" class="logo-img">
    <div class="brand-tag">Admin portal for the<br>Inventory Management System.</div>
  </div>

  <div class="right">
    <div class="card" id="card">

      <div class="card-header">
        <h2>Admin Sign In</h2>
        <p>Enter your credentials to access the system</p>
      </div>

      <form id="form" method="POST" action="{{ route('admin.login') }}" novalidate>
        @csrf
        <div class="field">
          <input id="code" name="login" type="text" value="{{ old('login') }}" placeholder="Admin Code (username or email)" autocomplete="username" spellcheck="false">
          <p class="err-msg {{ $errors->has('login') ? 'on' : '' }}" id="codeErr">{{ $errors->first('login') ?: 'Admin code is required.' }}</p>
        </div>

        <div class="field">
          <div class="pw-wrap">
            <input id="pass" name="password" type="password" placeholder="Password" autocomplete="current-password">
            <button type="button" class="pw-btn" id="pwBtn" aria-label="Show/hide password">
              <svg id="eyeIco" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
          <p class="err-msg {{ $errors->has('password') ? 'on' : '' }}" id="passErr">{{ $errors->first('password') ?: 'Password is required.' }}</p>
        </div>

        <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);margin:-4px 0 10px;">
          <input type="checkbox" name="remember" style="width:auto;height:auto;" value="1"> Remember me
        </label>

        <button class="btn-signin" type="submit" id="btn">
          <span id="btnLabel">Log In</span>
        </button>
      </form>

      <div class="divider">
        <span class="div-line"></span>
        <span class="div-text">or</span>
        <span class="div-line"></span>
      </div>

      <div class="card-footer">
        <p>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Secured · {{ $address_line1 }}, {{ $address_line2 }}
        </p>
      </div>

    </div>

    <div class="register-row">
      Don't have an account?<a href="{{ route('admin.register') }}">Create one</a>
    </div>
    <div class="below-card">
      {{ $siteName }} Inventory System &nbsp;·&nbsp; Admin Access Only
    </div>
  </div>

</div>

<script>
  const html = document.documentElement;
  const themeBtn = document.getElementById('themeBtn');
  const themeIco = document.getElementById('themeIco');

  const MOON = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>`;
  const SUN  = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>`;

  const setTheme = dark => {
    html.setAttribute('data-theme', dark ? 'dark' : 'light');
    themeIco.innerHTML = dark ? SUN : MOON;
    localStorage.setItem('theme', dark ? 'dark' : 'light');
  };

  const saved = localStorage.getItem('theme');
  setTheme(saved ? saved === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches);
  themeBtn.addEventListener('click', () => setTheme(html.getAttribute('data-theme') !== 'dark'));

  const passInput = document.getElementById('pass');
  const eyeIco = document.getElementById('eyeIco');
  document.getElementById('pwBtn').addEventListener('click', () => {
    const show = passInput.type === 'password';
    passInput.type = show ? 'text' : 'password';
    eyeIco.innerHTML = show
      ? `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`
      : `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
  });

  const form = document.getElementById('form');
  const card = document.getElementById('card');

  @if ($errors->any())
  card.classList.add('shake');
  setTimeout(() => card.classList.remove('shake'), 380);
  @endif

  form.addEventListener('submit', () => {
    const btn = document.getElementById('btn');
    const btnLabel = document.getElementById('btnLabel');
    btn.disabled = true;
    btnLabel.innerHTML = `<span class="spinner"></span>`;
  });
</script>
</body>
</html>
