// Hour Wash Progressive Web App (PWA) Service Worker
const CACHE_NAME = 'hourwash-v1';
const ASSETS = [
    '/',
    '/favicon.svg',
    '/favicon.png',
    '/favicon-192.png',
    '/manifest.json'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS).catch((err) => console.log('SW cacheAdd skip:', err));
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;
    if (!event.request.url.startsWith(self.location.origin)) return;

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                if (response && response.status === 200 && event.request.mode === 'navigate') {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                }
                return response;
            })
            .catch(async () => {
                const cached = await caches.match(event.request);
                if (cached) return cached;
                if (event.request.mode === 'navigate') {
                    const rootCache = await caches.match('/');
                    if (rootCache) return rootCache;
                }
                return new Response(
                    '<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Hour Wash - Offline</title><style>body{font-family:sans-serif;background:#09090b;color:#fff;text-align:center;padding:40px 20px}h1{color:#2563eb}.btn{display:inline-block;margin-top:20px;padding:12px 24px;background:#2563eb;color:#fff;text-decoration:none;border-radius:8px;font-weight:bold}</style></head><body><h1>Hour Wash Laundry</h1><p>The app server is waking up or network is offline.</p><a href="/" class="btn" onclick="location.reload();return false">Tap to Retry</a></body></html>',
                    { headers: { 'Content-Type': 'text/html' } }
                );
            })
    );
});
