// ==============================================================================
// Service Worker - RestPOS Mobile & Desktop PWA v1.3
// ==============================================================================

const CACHE_NAME = 'restpos-pwa-v1.3';
const STATIC_ASSETS = [
    './favicon.png',
    './apple-touch-icon.png',
    './manifest.json'
];

// Instalação do Service Worker
self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS).catch((err) => {
                console.warn('Erro ao pré-armazenar assets no cache:', err);
            });
        })
    );
});

// Ativação e limpeza de versões antigas do cache
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((name) => {
                    if (name !== CACHE_NAME) {
                        return caches.delete(name);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Interceção de requisições com Stale-While-Revalidate e Network-First
self.addEventListener('fetch', (event) => {
    const request = event.request;

    // Ignorar requisições não-GET
    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    // Bypass estrito de rotas transacionais, sincronização e polling ao vivo da cozinha
    if (url.pathname.includes('/api/sync/') || 
        url.pathname.includes('/process-sale') ||
        url.pathname.includes('/pos/checkout') ||
        url.pathname.includes('/orders/kitchen/live') ||
        url.pathname.includes('/dashboard/stats') ||
        url.pathname.includes('/call-waiter/status') ||
        url.pathname.includes('/api/ping')) {
        return;
    }

    // Para navegação HTML: Network First com fallback para cache
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => {
                return caches.match(request).then((response) => {
                    return response || caches.match('./favicon.png');
                });
            })
        );
        return;
    }

    // Para ficheiros estáticos (CSS, JS, Imagens, Fontes): Stale-While-Revalidate
    const isStaticAsset = 
        request.destination === 'style' || 
        request.destination === 'script' || 
        request.destination === 'image' || 
        request.destination === 'font' ||
        url.pathname.match(/\.(png|jpg|jpeg|svg|webp|css|js|woff2|woff|ttf)$/);

    if (isStaticAsset) {
        event.respondWith(
            caches.open(CACHE_NAME).then((cache) => {
                return cache.match(request).then((cachedResponse) => {
                    const fetchPromise = fetch(request).then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200) {
                            cache.put(request, networkResponse.clone());
                        }
                        return networkResponse;
                    }).catch(() => cachedResponse);

                    return cachedResponse || fetchPromise;
                });
            })
        );
    }
});
