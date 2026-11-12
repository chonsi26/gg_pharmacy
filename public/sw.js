/*
 * GG Pharmacy service worker.
 *
 * Deliberately conservative: this site is server-rendered with CSRF tokens,
 * login sessions and no-cache headers (PreventBackHistory), so HTML pages and
 * API/JSON calls are NEVER cached. Caching them would bring back "419 Page
 * Expired" errors and show stale carts, prices and stock.
 *
 * What it does:
 *   - Pages:   always network. If offline, show /offline.html.
 *   - Assets:  stale-while-revalidate for /build/, /icons/ and /images/ only.
 *   - Everything else (POSTs, JSON, product photos, CDN fonts): untouched.
 *
 * Bump VERSION whenever you change this file or offline.html.
 */
const VERSION = 'v1';
const CACHE = 'gg-pharmacy-' + VERSION;

// Resolve paths relative to where sw.js lives, so it also works in a subfolder.
const BASE = new URL('./', self.location).pathname;
const OFFLINE_URL = BASE + 'offline.html';
const PRECACHE = [OFFLINE_URL, BASE + 'images/logo.png', BASE + 'icons/icon-192.png'];
const ASSET_PREFIXES = [BASE + 'build/', BASE + 'icons/', BASE + 'images/'];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE).then((cache) => cache.addAll(PRECACHE)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k))))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const req = event.request;
  if (req.method !== 'GET') return;                       // never touch POST/PUT/DELETE

  const url = new URL(req.url);
  if (url.origin !== self.location.origin) return;        // leave CDNs / fonts alone

  // Page loads: network only, branded fallback when offline.
  if (req.mode === 'navigate') {
    event.respondWith(
      fetch(req).catch(() => caches.match(OFFLINE_URL))
    );
    return;
  }

  // Static assets: serve from cache instantly, refresh in the background.
  if (ASSET_PREFIXES.some((p) => url.pathname.startsWith(p))) {
    event.respondWith(
      caches.open(CACHE).then((cache) =>
        cache.match(req).then((cached) => {
          const network = fetch(req).then((res) => {
            if (res && res.ok) cache.put(req, res.clone());
            return res;
          }).catch(() => cached);
          return cached || network;
        })
      )
    );
  }
});
