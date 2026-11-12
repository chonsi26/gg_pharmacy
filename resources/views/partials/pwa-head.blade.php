{{-- PWA tags. Put @include('partials.pwa-head') inside <head> of every page that should be installable. --}}
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#C01A1A">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/favicon-32.png') }}">
<link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="{{ $settings['site_name'] ?? 'GG Pharmacy' }}">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<script defer src="{{ asset('js/pwa.js') }}" data-sw="{{ asset('sw.js') }}"></script>
