const CACHE_NAME = 'joeldent-v1';
const OFFLINE_URL = '/offline.html';

const ASSETS_TO_CACHE = [
    '/',
    '/build/assets/app.css', // O la ruta correcta de Vite compilada (se ignorará si no match exacto, usamos fetch event)
    '/manifest.json'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Estrategia Network First, falling back to cache
self.addEventListener('fetch', (event) => {
    // Si la request es de nuestro dominio e intenta acceder a HTML
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match(event.request).then((response) => {
                    if (response) {
                        return response;
                    }
                    // Si tenemos una página offline fallback
                    return caches.match(OFFLINE_URL);
                });
            })
        );
        return;
    }

    // Para el resto de assets
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request).then((fetchResponse) => {
                // Opcional: Cachear dinámicamente assets que van llegando
                return fetchResponse;
            });
        })
    );
});
