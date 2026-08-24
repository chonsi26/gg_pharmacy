<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GG Pharmacy — Register</title>
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
    <img src="data:image/png;base64,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" alt="GG Pharmacy" class="logo-img">
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
          Secured · Zone 2, Sogod, Southern Leyte
        </p>
      </div>
    </div>

    <div class="login-row">
      Already have an account?<a href="{{ route('admin.login') }}">Sign in</a>
    </div>
    <div class="below-card">
      GG Pharmacy Inventory System &nbsp;·&nbsp; Admin Access Only
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
