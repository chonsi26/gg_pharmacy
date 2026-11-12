{{-- Footer "Install App" banner. Stays hidden until the browser says the app is installable. --}}
<style>
  .pwa-install { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; background: #2b2b2b; border: 1px solid #444; border-radius: 10px; padding: 14px 18px; margin-bottom: 30px; }
  .pwa-install[hidden], .pwa-install-hint[hidden] { display: none; }
  .pwa-install-icon { width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0; }
  .pwa-install-text { flex: 1 1 220px; min-width: 0; }
  .pwa-install-text strong { display: block; font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 800; color: #fff; }
  .pwa-install-text span { display: block; font-size: 12px; color: #aaa; margin-top: 2px; }
  .pwa-install-btn { display: inline-flex; align-items: center; gap: 8px; background: var(--green, #2E7D32); color: #fff; border: none; border-radius: 6px; padding: 11px 22px; font-family: 'Montserrat', sans-serif; font-weight: 800; font-size: 13px; cursor: pointer; white-space: nowrap; transition: background .2s; }
  .pwa-install-btn:hover { background: var(--dark-green, #1B5E20); }
  .pwa-install-btn:focus-visible { outline: 2px solid #ffd600; outline-offset: 2px; }
  .pwa-install-hint { flex: 1 0 100%; font-size: 12px; color: #ccc; line-height: 1.6; border-top: 1px solid #444; padding-top: 10px; }
  @media (max-width: 768px) { .pwa-install-btn { width: 100%; justify-content: center; } }
</style>
<div class="pwa-install" id="pwaInstall" hidden>
  <img class="pwa-install-icon" src="{{ asset('icons/icon-192.png') }}" alt="">
  <div class="pwa-install-text">
    <strong>Get the {{ $settings['site_name'] ?? 'GG Pharmacy' }} app</strong>
    <span>Install it for one-tap access, right from your home screen.</span>
  </div>
  <button type="button" class="pwa-install-btn" id="pwaInstallBtn"><i class="fas fa-download"></i> Install App</button>
  <p class="pwa-install-hint" id="pwaInstallHint" hidden>
    On iPhone/iPad: tap the <i class="fas fa-arrow-up-from-bracket"></i> Share button in Safari, then choose <strong>Add to Home Screen</strong>.
  </p>
</div>
