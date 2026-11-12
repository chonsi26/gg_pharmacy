/*
 * PWA bootstrap: registers the service worker and powers the footer
 * "Install App" button (#pwaInstall / #pwaInstallBtn / #pwaInstallHint).
 * Loaded with: <script defer src=".../js/pwa.js" data-sw=".../sw.js">
 */
(function () {
  var me = document.currentScript;

  // ── 1. Service worker ─────────────────────────────────────────────────────
  if ('serviceWorker' in navigator) {
    var swUrl = (me && me.dataset.sw) || '/sw.js';
    window.addEventListener('load', function () {
      navigator.serviceWorker.register(swUrl).catch(function (err) {
        console.warn('Service worker registration failed:', err);
      });
    });
  }

  // ── 2. Install button ─────────────────────────────────────────────────────
  function init() {
    var box  = document.getElementById('pwaInstall');
    var btn  = document.getElementById('pwaInstallBtn');
    var hint = document.getElementById('pwaInstallHint');
    if (!box || !btn) return;

    // Already running as an installed app → nothing to offer.
    var installed = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
    if (installed) return;

    var deferred = null;
    var isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent) ||
                (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);

    // Chrome / Edge / Samsung Internet / Android: browser says "installable".
    window.addEventListener('beforeinstallprompt', function (e) {
      e.preventDefault();
      deferred = e;
      box.hidden = false;
    });

    window.addEventListener('appinstalled', function () {
      deferred = null;
      box.hidden = true;
    });

    // iOS Safari never fires the event, so show the button + manual steps.
    if (isIOS) box.hidden = false;

    btn.addEventListener('click', function () {
      if (deferred) {
        deferred.prompt();
        deferred.userChoice.then(function () {
          deferred = null;
          box.hidden = true;
        });
      } else if (isIOS && hint) {
        hint.hidden = !hint.hidden;
      }
    });
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
