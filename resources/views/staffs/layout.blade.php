<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Staff') | {{ $siteName ?? 'Pharmacy' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: #12302b;
            --ink-soft: #3d5a54;
            --panel: #0e3b36;
            --panel-text: #dcece8;
            --paper: #f4f7f6;
            --field: #ffffff;
            --line: #c9d8d4;
            --accent: #e8a317;
            --danger: #b3261e;
            --ok-bg: #e3f3ec;
            --ok-text: #14573f;
            --radius: 10px;
        }

        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; }

        body {
            font-family: 'Figtree', system-ui, -apple-system, 'Segoe UI', sans-serif;
            color: var(--ink);
            background: var(--paper);
            line-height: 1.5;
        }

        .shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(280px, 5fr) 7fr;
        }

        /* ── Brand panel ─────────────────────────────── */
        .brand {
            background: var(--panel);
            color: var(--panel-text);
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 32px;
        }
        .brand-mark { display: flex; align-items: center; gap: 12px; }
        .brand-mark img { height: 44px; width: auto; max-width: 160px; object-fit: contain; }
        .brand-name {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            color: #fff;
        }
        .brand h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 700;
            font-size: clamp(2rem, 3.4vw, 3rem);
            line-height: 1.08;
            letter-spacing: -0.02em;
            color: #fff;
            margin: 0 0 14px;
            max-width: 14ch;
        }
        .brand p { margin: 0; max-width: 34ch; color: var(--panel-text); }
        .brand-address { font-size: .875rem; color: #a9c7c0; }
        .brand-address span { display: block; }

        /* ── Form pane ───────────────────────────────── */
        .pane {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 28px;
        }
        .card { width: 100%; max-width: 460px; }
        .card.wide { max-width: 580px; }

        .card h1 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: -0.02em;
            margin: 0 0 6px;
        }
        .card .lede { margin: 0 0 28px; color: var(--ink-soft); }

        .alert {
            padding: 12px 14px;
            border-radius: var(--radius);
            font-size: .925rem;
            margin-bottom: 20px;
        }
        .alert-ok { background: var(--ok-bg); color: var(--ok-text); }
        .alert-err { background: #fbe9e7; color: var(--danger); }
        .alert-err ul { margin: 0; padding-left: 18px; }

        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid .full { grid-column: 1 / -1; }

        .field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
        .grid .field { margin-bottom: 0; }
        label { font-size: .9rem; font-weight: 600; }
        .opt { font-weight: 400; color: var(--ink-soft); }

        input[type=text], input[type=email], input[type=password], input[type=file], textarea {
            width: 100%;
            font: inherit;
            color: var(--ink);
            background: var(--field);
            border: 1.5px solid var(--line);
            border-radius: var(--radius);
            padding: 11px 13px;
        }
        textarea { resize: vertical; min-height: 72px; }
        input[type=file] { padding: 9px 11px; font-size: .9rem; }
        input:focus, textarea:focus {
            outline: 3px solid color-mix(in srgb, var(--accent) 45%, transparent);
            outline-offset: 0;
            border-color: var(--accent);
        }
        .is-invalid { border-color: var(--danger) !important; }
        .error { color: var(--danger); font-size: .85rem; }

        .pw { position: relative; }
        .pw input { padding-right: 64px; }
        .pw button {
            position: absolute; right: 6px; top: 50%; transform: translateY(-50%);
            background: none; border: 0; cursor: pointer;
            font: inherit; font-size: .85rem; font-weight: 600;
            color: var(--ink-soft); padding: 6px 8px; border-radius: 6px;
        }
        .pw button:hover { color: var(--ink); }
        .pw button:focus-visible { outline: 3px solid color-mix(in srgb, var(--accent) 45%, transparent); }

        .phone { display: flex; }
        .phone .prefix {
            display: inline-flex; align-items: center;
            padding: 0 12px;
            background: #e6eeeb;
            border: 1.5px solid var(--line); border-right: 0;
            border-radius: var(--radius) 0 0 var(--radius);
            font-weight: 600; color: var(--ink-soft);
        }
        .phone input { border-radius: 0 var(--radius) var(--radius) 0; }

        .row { display: flex; align-items: center; justify-content: space-between; margin: 4px 0 22px; }
        .check { display: flex; align-items: center; gap: 8px; font-weight: 400; font-size: .9rem; }
        .check input { width: 17px; height: 17px; accent-color: var(--panel); }

        .btn {
            width: 100%;
            font: inherit; font-weight: 600; font-size: 1rem;
            color: #fff; background: var(--panel);
            border: 0; border-radius: var(--radius);
            padding: 13px 18px; cursor: pointer;
            transition: background .15s;
        }
        .btn:hover { background: #0a2d29; }
        .btn:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; }
        .btn-quiet {
            width: auto; background: transparent; color: var(--ink);
            border: 1.5px solid var(--line); padding: 10px 18px;
        }
        .btn-quiet:hover { background: #e6eeeb; }

        .switch { margin: 22px 0 0; text-align: center; color: var(--ink-soft); font-size: .925rem; }
        .switch a { color: var(--ink); font-weight: 600; }

        @media (max-width: 860px) {
            .shell { grid-template-columns: 1fr; }
            .brand { padding: 24px 22px; gap: 14px; }
            .brand h2 { font-size: 1.6rem; max-width: none; margin-bottom: 6px; }
            .brand-address { display: none; }
            .pane { align-items: flex-start; padding: 28px 18px 40px; }
        }
        @media (max-width: 520px) {
            .grid { grid-template-columns: 1fr; }
        }
        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; }
        }
    </style>
</head>
<body>
<main class="shell">
    <aside class="brand">
        <div class="brand-mark">
            @if (!empty($logo))
                <img src="{{ asset($logo) }}" alt="{{ $siteName ?? 'Pharmacy' }}">
            @else
                <span class="brand-name">{{ $siteName ?? 'Pharmacy' }}</span>
            @endif
        </div>

        <div>
            <h2>@yield('headline', 'Staff portal')</h2>
            <p>@yield('subline', 'Sign in to handle orders, stock and customers for the pharmacy.')</p>
        </div>

        <div class="brand-address">
            @if (!empty($address_line1)) <span>{{ $address_line1 }}</span> @endif
            @if (!empty($address_line2)) <span>{{ $address_line2 }}</span> @endif
        </div>
    </aside>

    <section class="pane">
        <div class="card @yield('card_class')">
            @if (session('status'))
                <div class="alert alert-ok" role="status">{{ session('status') }}</div>
            @endif

            @yield('content')
        </div>
    </section>
</main>

<script>
    // Show / hide password toggles
    document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.togglePassword);
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.textContent = show ? 'Hide' : 'Show';
            btn.setAttribute('aria-pressed', show ? 'true' : 'false');
        });
    });
</script>
</body>
</html>