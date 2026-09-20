<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $siteName }} — Register</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --red:       #CC1F1F;
    --red-h:     #b51a1a;
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
    min-height: 100%;
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
    align-items: flex-start;
    justify-content: center;
    padding: 48px 24px 60px;
    gap: 52px;
  }

  /* ── Left panel ── */
  .left {
    flex: 0 1 340px;
    position: sticky;
    top: 48px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 20px;
    animation: fadeUp .45s ease both;
  }

  .logo-img {
    width: 100%;
    max-width: 320px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
  }

  .brand-tag {
    font-size: 17px;
    font-weight: 400;
    color: var(--muted);
    line-height: 1.6;
  }

  /* Steps sidebar */
  .steps {
    display: flex;
    flex-direction: column;
    gap: 0;
    width: 100%;
    margin-top: 8px;
  }
  .step {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 10px 0;
    position: relative;
  }
  .step:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 13px;
    top: 36px;
    width: 2px;
    height: calc(100% - 16px);
    background: var(--border);
    border-radius: 1px;
    transition: background .3s;
  }
  .step.done::after { background: var(--green); }
  .step.active::after { background: var(--border); }

  .step-dot {
    width: 28px; height: 28px;
    border-radius: 50%;
    border: 2px solid var(--border);
    background: var(--surface);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700;
    color: var(--subtle);
    flex-shrink: 0;
    transition: all .3s;
    position: relative; z-index: 1;
  }
  .step.active .step-dot {
    border-color: var(--red);
    color: var(--red);
    box-shadow: 0 0 0 3px var(--focus);
  }
  .step.done .step-dot {
    border-color: var(--green);
    background: var(--green);
    color: #fff;
  }
  .step-info { padding-top: 4px; }
  .step-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    transition: color .3s;
  }
  .step.inactive .step-title { color: var(--subtle); }
  .step-sub {
    font-size: 11px;
    color: var(--subtle);
    margin-top: 1px;
  }

  /* ── Right / form card ── */
  .right {
    flex: 0 1 460px;
    animation: fadeUp .45s ease .05s both;
  }

  .card {
    background: var(--surface);
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,.08), 0 8px 24px rgba(0,0,0,.06);
    overflow: hidden;
    transition: background .3s;
  }
  [data-theme="dark"] .card {
    box-shadow: 0 2px 8px rgba(0,0,0,.4);
  }

  /* Progress bar at top of card */
  .progress-track {
    height: 4px;
    background: var(--border);
    position: relative;
  }
  .progress-fill {
    height: 100%;
    background: var(--red);
    border-radius: 0 2px 2px 0;
    transition: width .4s cubic-bezier(.4,0,.2,1);
  }

  .card-body { padding: 24px 20px 28px; }

  .card-header {
    margin-bottom: 22px;
  }
  .card-header h2 {
    font-size: 18px;
    font-weight: 700;
    color: var(--text);
  }
  .card-header p {
    font-size: 13px;
    color: var(--muted);
    margin-top: 4px;
  }

  /* Field */
  .field { margin-bottom: 14px; }
  .field label {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 6px;
    padding-left: 1px;
  }
  .field label .opt {
    font-weight: 400;
    text-transform: none;
    letter-spacing: 0;
    font-size: 11px;
    color: var(--subtle);
  }

  .row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
  .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

  input, select {
    width: 100%;
    height: 48px;
    padding: 0 14px;
    background: var(--input-bg);
    border: 1.5px solid var(--border);
    border-radius: 7px;
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    color: var(--text);
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .3s;
    -webkit-appearance: none;
  }
  input::placeholder { color: var(--subtle); font-size: 14px; }
  input:focus, select:focus {
    border-color: var(--red);
    box-shadow: 0 0 0 3px var(--focus);
  }
  select { cursor: pointer; }

  .pw-wrap { position: relative; display: flex; align-items: center; }
  .pw-wrap input { padding-right: 46px; }
  .pw-btn {
    position: absolute; right: 12px;
    background: none; border: none;
    cursor: pointer; color: var(--subtle);
    display: flex; padding: 4px;
    transition: color .2s;
  }
  .pw-btn:hover { color: var(--muted); }

  /* Strength */
  .strength-bar { display: flex; gap: 4px; margin-top: 7px; }
  .strength-bar span {
    flex: 1; height: 3px; border-radius: 2px;
    background: var(--border); transition: background .3s;
  }
  .strength-label {
    font-size: 11px; color: var(--subtle);
    margin-top: 4px; padding-left: 1px;
    min-height: 14px; transition: color .3s;
  }

  /* Avatar upload */
  .avatar-upload {
    display: flex;
    align-items: center;
    gap: 14px;
  }
  .avatar-preview {
    width: 72px; height: 72px;
    border-radius: 50%;
    border: 2px dashed var(--border);
    background: var(--bg);
    overflow: hidden;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    color: var(--subtle);
    transition: border-color .2s, background .3s;
    cursor: pointer;
  }
  .avatar-preview:hover { border-color: var(--red); }
  .avatar-preview img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: none;
  }
  .avatar-preview .icon-ph { transition: opacity .2s; }
  .avatar-info { flex: 1; }
  .avatar-info p {
    font-size: 13px; color: var(--text); font-weight: 500;
  }
  .avatar-info span {
    font-size: 11px; color: var(--subtle);
    display: block; margin-top: 2px;
  }
  .avatar-info input[type="file"] { display: none; }
  .avatar-info .btn-pick {
    display: inline-flex; align-items: center; gap: 5px;
    margin-top: 8px;
    padding: 6px 12px;
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: 6px;
    font-size: 12px; font-weight: 600;
    color: var(--text); cursor: pointer;
    transition: border-color .2s, color .2s;
  }
  .avatar-info .btn-pick:hover { border-color: var(--red); color: var(--red); }
  .avatar-filename {
    font-size: 11px; color: var(--muted);
    margin-top: 5px; display: none;
  }

  /* Err */
  .err-msg {
    display: none; font-size: 12px; color: var(--red);
    margin-top: 5px; padding-left: 1px;
  }
  .err-msg.on { display: block; }

  /* Nav buttons */
  .btn-row {
    display: flex;
    gap: 10px;
    margin-top: 6px;
  }
  .btn-back {
    height: 50px; flex: 0 0 100px;
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: 7px;
    font-family: 'Inter', sans-serif;
    font-size: 15px; font-weight: 600;
    color: var(--muted); cursor: pointer;
    transition: border-color .2s, color .2s;
    display: flex; align-items: center; justify-content: center; gap: 6px;
  }
  .btn-back:hover { border-color: var(--red); color: var(--text); }

  .btn-next {
    height: 50px; flex: 1;
    background: var(--red);
    border: none;
    border-radius: 7px;
    font-family: 'Inter', sans-serif;
    font-size: 16px; font-weight: 700;
    color: var(--white); cursor: pointer;
    transition: background .2s, box-shadow .2s;
    display: flex; align-items: center; justify-content: center; gap: 8px;
  }
  .btn-next:hover { background: var(--red-h); box-shadow: 0 2px 10px rgba(204,31,31,.3); }
  .btn-next:disabled { opacity: .65; cursor: not-allowed; box-shadow: none; }
  .btn-next.green { background: var(--green); }
  .btn-next.green:hover { background: #256325; box-shadow: 0 2px 10px rgba(45,122,45,.3); }

  .spinner {
    width: 16px; height: 16px;
    border: 2px solid rgba(255,255,255,.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .6s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* Step panels */
  .step-panel { display: none; }
  .step-panel.active { display: block; animation: fadeUp .3s ease both; }

  /* Card footer */
  .card-footer {
    padding: 14px 20px;
    border-top: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center; gap: 6px;
  }
  .card-footer p {
    font-size: 12px; color: var(--muted);
    display: flex; align-items: center; gap: 5px;
  }
  .card-footer svg { color: var(--green); }

  /* Below card */
  .login-row {
    text-align: center;
    margin-top: 16px;
    font-size: 14px;
    color: var(--muted);
  }
  .login-row a {
    color: var(--red);
    font-weight: 600;
    text-decoration: none;
    margin-left: 5px;
    transition: color .2s;
  }
  .login-row a:hover { color: var(--red-h); text-decoration: underline; }

  .below-card {
    margin-top: 12px;
    text-align: center;
    font-size: 12px;
    color: var(--subtle);
  }

  /* Animations */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .shake { animation: shake .35s ease; }
  @keyframes shake {
    0%,100% { transform: translateX(0); }
    25%      { transform: translateX(-5px); }
    75%      { transform: translateX(5px); }
  }

  /* Toast */
  .toast {
    position: fixed;
    bottom: 24px; left: 50%;
    transform: translateX(-50%) translateY(60px);
    background: var(--green);
    color: var(--white);
    padding: 10px 18px;
    border-radius: 7px;
    font-size: 13px; font-weight: 500;
    display: flex; align-items: center; gap: 7px;
    box-shadow: 0 4px 14px rgba(0,0,0,.15);
    transition: transform .35s cubic-bezier(.22,1,.36,1);
    z-index: 99;
    white-space: nowrap;
  }
  .toast.on { transform: translateX(-50%) translateY(0); }

  /* Phone prefix */
  .phone-wrap { display: flex; gap: 8px; align-items: center; }
  .phone-prefix {
    height: 48px;
    padding: 0 12px;
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: 7px;
    font-size: 14px; font-weight: 600;
    color: var(--muted);
    white-space: nowrap;
    display: flex; align-items: center; gap: 5px;
    flex-shrink: 0;
  }

  @media (max-width: 820px) {
    .page { flex-direction: column; padding: 32px 16px 60px; gap: 24px; }
    .left { position: static; flex: none; width: 100%; align-items: center; }
    .steps { display: none; }
    .logo-img { max-width: 260px; }
    .brand-tag { text-align: center; font-size: 14px; }
    .right { flex: none; width: 100%; max-width: 500px; }
    .row-3 { grid-template-columns: 1fr; }
    .row-2 { grid-template-columns: 1fr; }
  }
</style>
</head>
<body>

<button class="theme-toggle" id="themeBtn" aria-label="Toggle theme">
  <span id="themeIco"></span>
</button>

<div class="toast" id="toast">
  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
  Account created! Redirecting to login…
</div>

<div class="page">

  <!-- Left -->
  <div class="left">
    <img src="{{ $logo ? asset($logo) : 'https://www.bticino.ph/modules/custom/legrand_ecat/assets/img/no-image.png' }}" alt="{{ $siteName ?? 'N/A' }}" class="logo-img">
    <div class="brand-tag">Create your admin account<br>for the Inventory Management System.</div>

    <div class="steps">
      <div class="step active" id="sStep1">
        <div class="step-dot" id="sDot1">1</div>
        <div class="step-info">
          <div class="step-title">Personal Info</div>
          <div class="step-sub">Name details</div>
        </div>
      </div>
      <div class="step inactive" id="sStep2">
        <div class="step-dot" id="sDot2">2</div>
        <div class="step-info">
          <div class="step-title">Contact & Account</div>
          <div class="step-sub">Email, phone, username</div>
        </div>
      </div>
      <div class="step inactive" id="sStep3">
        <div class="step-dot" id="sDot3">3</div>
        <div class="step-info">
          <div class="step-title">Security & Profile</div>
          <div class="step-sub">Password & photo</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right -->
  <div class="right">
    <div class="card" id="card">

      <div class="progress-track">
        <div class="progress-fill" id="progressFill" style="width:33.33%"></div>
      </div>

      <form id="registerForm" method="POST" action="{{ route('admin.register') }}" enctype="multipart/form-data" novalidate>
        @csrf
      <div class="card-body">

        <!-- Step 1: Personal Info -->
        <div class="step-panel active" id="panel1">
          <div class="card-header">
            <h2>Personal Information</h2>
            <p>Step 1 of 3 — Enter your full name</p>
          </div>

          <div class="row-3">
            <div class="field">
              <label>First Name</label>
              <input id="fname" name="first_name" type="text" value="{{ old('first_name') }}" placeholder="Juan" autocomplete="given-name">
              <p class="err-msg {{ $errors->has('first_name') ? 'on' : '' }}" id="fnameErr">{{ $errors->first('first_name') ?: 'Required.' }}</p>
            </div>
            <div class="field">
              <label>Middle Name</label>
              <input id="mname" name="middle_name" type="text" value="{{ old('middle_name') }}" placeholder="Santos" autocomplete="additional-name">
            </div>
            <div class="field">
              <label>Last Name</label>
              <input id="lname" name="last_name" type="text" value="{{ old('last_name') }}" placeholder="dela Cruz" autocomplete="family-name">
              <p class="err-msg {{ $errors->has('last_name') ? 'on' : '' }}" id="lnameErr">{{ $errors->first('last_name') ?: 'Required.' }}</p>
            </div>
          </div>

          <!-- Preview -->
          <div style="margin-top:6px; padding:12px 14px; background:var(--bg); border-radius:7px; border:1px solid var(--border);">
            <p style="font-size:11px; text-transform:uppercase; letter-spacing:.5px; color:var(--subtle); margin-bottom:4px;">Full Name Preview</p>
            <p id="namePreview" style="font-size:16px; font-weight:600; color:var(--text);">—</p>
          </div>

          <div class="btn-row" style="margin-top:18px;">
            <button class="btn-next" id="next1" type="button">
              Continue
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
          </div>
        </div>

        <!-- Step 2: Contact & Account -->
        <div class="step-panel" id="panel2">
          <div class="card-header">
            <h2>Contact &amp; Account</h2>
            <p>Step 2 of 3 — How we reach you &amp; your login details</p>
          </div>

          <div class="field">
            <label>Email Address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="juan@ggpharmacy.com" autocomplete="email">
            <p class="err-msg {{ $errors->has('email') ? 'on' : '' }}" id="emailErr">{{ $errors->first('email') ?: 'Enter a valid email address.' }}</p>
          </div>

          <div class="field">
            <label>Phone Number</label>
            <div class="phone-wrap">
              <div class="phone-prefix">🇵🇭 +63</div>
              <input id="phone" name="phone_number" type="tel" value="{{ old('phone_number') }}" placeholder="9XX XXX XXXX" autocomplete="tel" maxlength="10">
            </div>
            <p class="err-msg {{ $errors->has('phone_number') ? 'on' : '' }}" id="phoneErr">{{ $errors->first('phone_number') ?: 'Enter a valid 10-digit PH mobile number.' }}</p>
          </div>

          <div class="field">
            <label>Username</label>
            <input id="username" name="username" type="text" value="{{ old('username') }}" placeholder="e.g. jdelacruz" autocomplete="username" spellcheck="false">
            <p class="err-msg {{ $errors->has('username') ? 'on' : '' }}" id="usernameErr">{{ $errors->first('username') ?: 'Username is required (letters, numbers, _ only).' }}</p>
          </div>

          <div class="btn-row" style="margin-top:18px;">
            <button class="btn-back" id="back2" type="button">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
              Back
            </button>
            <button class="btn-next" id="next2" type="button">
              Continue
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
          </div>
        </div>

        <!-- Step 3: Security & Profile -->
        <div class="step-panel" id="panel3">
          <div class="card-header">
            <h2>Security &amp; Profile</h2>
            <p>Step 3 of 3 — Set your password and upload a photo</p>
          </div>

          <div class="field">
            <label>Password</label>
            <div class="pw-wrap">
              <input id="pass" name="password" type="password" placeholder="Create a strong password" autocomplete="new-password">
              <button type="button" class="pw-btn" id="pwBtn1" aria-label="Show/hide password">
                <svg id="eyeIco1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            <div class="strength-bar" id="strengthBar">
              <span id="s1"></span><span id="s2"></span><span id="s3"></span><span id="s4"></span>
            </div>
            <p class="strength-label" id="strengthLabel"></p>
            <p class="err-msg {{ $errors->has('password') ? 'on' : '' }}" id="passErr">{{ $errors->first('password') ?: 'Password must be at least 8 characters.' }}</p>
          </div>

          <div class="field">
            <label>Confirm Password</label>
            <div class="pw-wrap">
              <input id="confirm" name="password_confirmation" type="password" placeholder="Re-enter your password" autocomplete="new-password">
              <button type="button" class="pw-btn" id="pwBtn2" aria-label="Show/hide confirm">
                <svg id="eyeIco2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            <p class="err-msg" id="confirmErr">Passwords do not match.</p>
          </div>

          <div class="field" style="margin-top:18px;">
            <label>Profile Image <span class="opt">(optional · JPG/PNG/WEBP · max 2MB)</span></label>
            <div class="avatar-upload">
              <div class="avatar-preview" id="avatarPreview" title="Click to pick image">
                <img id="avatarImg" src="" alt="Preview">
                <svg class="icon-ph" id="avatarIcon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              </div>
              <div class="avatar-info">
                <p>Upload a profile photo</p>
                <span>JPG, PNG, WEBP — max 2 MB</span>
                <input type="file" id="avatarFile" name="profile_picture" accept="image/jpeg,image/png,image/webp">
                <label for="avatarFile" class="btn-pick">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                  Choose File
                </label>
                <p class="avatar-filename" id="avatarFilename"></p>
              </div>
            </div>
            <p class="err-msg {{ $errors->has('profile_picture') ? 'on' : '' }}" id="avatarErr">{{ $errors->first('profile_picture') ?: 'File is too large or an invalid type.' }}</p>
          </div>

          <div class="btn-row" style="margin-top:22px;">
            <button class="btn-back" id="back3" type="button">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
              Back
            </button>
            <button class="btn-next green" id="submitBtn" type="submit">
              <span id="btnLabel">Create Account</span>
            </button>
          </div>
        </div>

      </div><!-- /card-body -->
      </form>

      <div class="card-footer">
        <p>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Secured · {{ $address_line1 }}, {{ $address_line2 }}
        </p>
      </div>
    </div>

    <div class="login-row">
      Already have an account?<a href="{{ route('admin.login') }}">Sign in</a>
    </div>
    <div class="below-card">
      {{ $siteName }} Inventory System &nbsp;·&nbsp; Admin Access Only
    </div>
  </div>

</div><!-- /page -->

<script>
// ── Theme ──────────────────────────────────────────────
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

// ── Step system ────────────────────────────────────────
let currentStep = {{ $errors->has('email') || $errors->has('phone_number') || $errors->has('username') ? 2 : ($errors->has('password') || $errors->has('profile_picture') ? 3 : 1) }};
const totalSteps = 3;
const progressFill = document.getElementById('progressFill');

const STEP_META = [
  { stepEl: 'sStep1', dotEl: 'sDot1' },
  { stepEl: 'sStep2', dotEl: 'sDot2' },
  { stepEl: 'sStep3', dotEl: 'sDot3' },
];
const CHECK_SVG = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`;

function goTo(step) {
  document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('panel' + step).classList.add('active');

  STEP_META.forEach((m, i) => {
    const stepEl = document.getElementById(m.stepEl);
    const dotEl  = document.getElementById(m.dotEl);
    stepEl.classList.remove('active', 'done', 'inactive');
    if (i + 1 < step) {
      stepEl.classList.add('done');
      dotEl.innerHTML = CHECK_SVG;
    } else if (i + 1 === step) {
      stepEl.classList.add('active');
      dotEl.textContent = i + 1;
    } else {
      stepEl.classList.add('inactive');
      dotEl.textContent = i + 1;
    }
  });

  progressFill.style.width = (step / totalSteps * 100) + '%';
  currentStep = step;
}
goTo(currentStep);

// ── Name preview ───────────────────────────────────────
['fname','mname','lname'].forEach(id => {
  document.getElementById(id).addEventListener('input', updatePreview);
});
function updatePreview() {
  const f = document.getElementById('fname').value.trim();
  const m = document.getElementById('mname').value.trim();
  const l = document.getElementById('lname').value.trim();
  const parts = [f, m, l].filter(Boolean);
  document.getElementById('namePreview').textContent = parts.length ? parts.join(' ') : '—';
}
updatePreview();

// ── Step 1 validation ──────────────────────────────────
document.getElementById('next1').addEventListener('click', () => {
  const fname = document.getElementById('fname').value.trim();
  const lname = document.getElementById('lname').value.trim();
  let ok = true;
  const fe = document.getElementById('fnameErr');
  const le = document.getElementById('lnameErr');
  fe.classList.remove('on'); le.classList.remove('on');
  if (!fname) { fe.classList.add('on'); ok = false; }
  if (!lname) { le.classList.add('on'); ok = false; }
  if (!ok) { shake(); return; }
  goTo(2);
});

// ── Step 2 validation ──────────────────────────────────
document.getElementById('back2').addEventListener('click', () => goTo(1));
document.getElementById('next2').addEventListener('click', () => {
  const email    = document.getElementById('email').value.trim();
  const phone    = document.getElementById('phone').value.trim();
  const username = document.getElementById('username').value.trim();
  let ok = true;

  const ee = document.getElementById('emailErr');
  const pe = document.getElementById('phoneErr');
  const ue = document.getElementById('usernameErr');
  ee.classList.remove('on'); pe.classList.remove('on'); ue.classList.remove('on');

  const emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!email || !emailRx.test(email)) { ee.classList.add('on'); ok = false; }

  const phoneRx = /^9\d{9}$/;
  if (!phone || !phoneRx.test(phone.replace(/\s/g,''))) { pe.classList.add('on'); ok = false; }

  const userRx = /^[a-zA-Z0-9_]{3,}$/;
  if (!username || !userRx.test(username)) { ue.classList.add('on'); ok = false; }

  if (!ok) { shake(); return; }
  goTo(3);
});

// ── Password toggles ───────────────────────────────────
function makeToggle(btnId, inputId, iconId) {
  const btn = document.getElementById(btnId);
  const inp = document.getElementById(inputId);
  const ico = document.getElementById(iconId);
  btn.addEventListener('click', () => {
    const show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    ico.innerHTML = show
      ? `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`
      : `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
  });
}
makeToggle('pwBtn1','pass','eyeIco1');
makeToggle('pwBtn2','confirm','eyeIco2');

// ── Strength meter ─────────────────────────────────────
const passInput = document.getElementById('pass');
const sBars = ['s1','s2','s3','s4'].map(id => document.getElementById(id));
const strengthLabel = document.getElementById('strengthLabel');
const levels = [
  { color: '#CC1F1F', label: 'Weak' },
  { color: '#e07b00', label: 'Fair' },
  { color: '#c8b400', label: 'Good' },
  { color: '#2D7A2D', label: 'Strong' },
];
passInput.addEventListener('input', () => {
  const v = passInput.value;
  let score = 0;
  if (v.length >= 8)                         score++;
  if (/[A-Z]/.test(v) && /[a-z]/.test(v))   score++;
  if (/[0-9]/.test(v))                       score++;
  if (/[^A-Za-z0-9]/.test(v))               score++;
  sBars.forEach((b, i) => {
    b.style.background = i < score ? levels[score - 1].color : '';
  });
  strengthLabel.textContent = v ? (levels[score - 1]?.label || '') : '';
  strengthLabel.style.color = v ? (levels[score - 1]?.color || '') : '';
});

// ── Avatar upload ──────────────────────────────────────
document.getElementById('avatarFile').addEventListener('change', function() {
  const file = this.files[0];
  if (!file) return;

  const filename = file.name;
  document.getElementById('avatarFilename').textContent = filename;
  document.getElementById('avatarFilename').style.display = 'block';

  const reader = new FileReader();
  reader.onload = e => {
    const img = document.getElementById('avatarImg');
    const icon = document.getElementById('avatarIcon');
    img.src = e.target.result;
    img.style.display = 'block';
    icon.style.display = 'none';
    document.getElementById('avatarPreview').style.border = '2px solid var(--green)';
  };
  reader.readAsDataURL(file);
});
document.getElementById('avatarPreview').addEventListener('click', () => {
  document.getElementById('avatarFile').click();
});

// ── Step 3 client-side check (final submit is native form POST) ──
document.getElementById('back3').addEventListener('click', () => goTo(2));

document.getElementById('registerForm').addEventListener('submit', (e) => {
  const pass    = passInput.value;
  const confirm = document.getElementById('confirm').value;
  let ok = true;

  const pe  = document.getElementById('passErr');
  const ce  = document.getElementById('confirmErr');
  pe.classList.remove('on'); ce.classList.remove('on');

  if (!pass || pass.length < 8) { pe.classList.add('on'); ok = false; }
  if (!confirm || confirm !== pass) {
    ce.textContent = !confirm ? 'Please confirm your password.' : 'Passwords do not match.';
    ce.classList.add('on'); ok = false;
  }

  if (!ok) { e.preventDefault(); shake(); return; }

  const btn = document.getElementById('submitBtn');
  const lbl = document.getElementById('btnLabel');
  btn.disabled = true;
  lbl.innerHTML = `<span class="spinner"></span>`;
});

// ── Shake helper ──────────────────────────────────────
function shake() {
  const card = document.getElementById('card');
  card.classList.add('shake');
  setTimeout(() => card.classList.remove('shake'), 380);
}

@if ($errors->any())
shake();
@endif
</script>
</body>
</html>
