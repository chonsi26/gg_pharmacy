<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $siteName }} — Registration Access</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --red: #CC1F1F; --red-h: #b51a1a;
    --bg: #f0f2f5; --surface: #ffffff; --border: #dddfe2;
    --text: #1c1e21; --muted: #65676b; --subtle: #a0a3a7;
    --input-bg: #ffffff; --focus: rgba(204,31,31,0.12);
  }
  [data-theme="dark"] {
    --bg: #18191a; --surface: #242526; --border: #3a3b3c;
    --text: #e4e6eb; --muted: #b0b3b8; --subtle: #6a6b6c;
    --input-bg: #3a3b3c; --focus: rgba(204,31,31,0.18);
  }
  html, body { min-height: 100%; font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); }
  .wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
  .box { width: 100%; max-width: 400px; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 32px 28px; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
  .logo { display: block; max-width: 160px; margin: 0 auto 20px; border-radius: 8px; }
  h2 { font-size: 20px; font-weight: 700; text-align: center; margin-bottom: 6px; }
  p.sub { font-size: 13px; color: var(--muted); text-align: center; margin-bottom: 22px; line-height: 1.5; }
  label { display: block; font-size: 12px; font-weight: 600; color: var(--muted); margin-bottom: 6px; }
  .pw-wrap { position: relative; display: flex; align-items: center; }
  input[type=password], input[type=text] {
    width: 100%; padding: 11px 44px 11px 12px; font: inherit; font-size: 14px;
    background: var(--input-bg); color: var(--text);
    border: 1px solid var(--border); border-radius: 8px; outline: none;
  }
  input:focus { border-color: var(--red); box-shadow: 0 0 0 3px var(--focus); }
  .pw-btn { position: absolute; right: 10px; background: none; border: none; cursor: pointer; color: var(--subtle); font-size: 12px; font-weight: 600; }
  .pw-btn:hover { color: var(--muted); }
  .err { font-size: 12px; color: var(--red); margin-top: 8px; }
  button.go { width: 100%; margin-top: 18px; padding: 12px; font: inherit; font-weight: 600; font-size: 14px; color: #fff; background: var(--red); border: none; border-radius: 8px; cursor: pointer; }
  button.go:hover { background: var(--red-h); }
  .back { display: block; text-align: center; margin-top: 16px; font-size: 13px; color: var(--muted); text-decoration: none; }
  .back:hover { color: var(--red); }
</style>
</head>
<body>
<script>
  (function () {
    var s = localStorage.getItem('theme');
    var dark = s ? s === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
    document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
  })();
</script>

<div class="wrap">
  <form class="box" method="POST" action="{{ route('admin.register.unlock') }}" autocomplete="off">
    @csrf
    @if($logo)
      <img class="logo" src="{{ asset($logo) }}" alt="{{ $siteName }}">
    @endif

    <h2>Registration Access</h2>
    <p class="sub">Enter the registration password to continue to the admin sign-up page.</p>

    <label for="gate_password">Password</label>
    <div class="pw-wrap">
      <input type="password" id="gate_password" name="gate_password" required autofocus>
      <button type="button" class="pw-btn" id="pwToggle">Show</button>
    </div>
    @error('gate_password')
      <div class="err">{{ $message }}</div>
    @enderror

    <button type="submit" class="go">Continue</button>
    <a class="back" href="{{ route('admin.login') }}">← Back to sign in</a>
  </form>
</div>

<script>
  document.getElementById('pwToggle').addEventListener('click', function () {
    var i = document.getElementById('gate_password');
    var show = i.type === 'password';
    i.type = show ? 'text' : 'password';
    this.textContent = show ? 'Hide' : 'Show';
  });
</script>
</body>
</html>
