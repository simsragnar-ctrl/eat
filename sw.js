/**
 * Time2Eat Service Worker
 * Provides offline functionality and caching for PWA
 */

const CACHE_NAME = 'time2eat-v1.0.0';
const STATIC_CACHE = 'time2eat-static-v1.0.0';
const DYNAMIC_CACHE = 'time2eat-dynamic-v1.0.0';

// Files to cache immediately
const STATIC_FILES = [
    '/',
    '/browse',
    '/about',
    '/public/css/app.css',
    '/public/js/app.js',
    '/public/images/hero.webp',
    '/manifest.json',
    'https://cdn.tailwindcss.com',
    'https://unpkg.com/feather-icons',
    'https://fonts.googleapis.com/icon?family=Material+Icons',
    'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined',
    'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap'
];

// Files to cache dynamically
const DYNAMIC_FILES = [
    '/api/',
    '/dashboard',
    '/customer/',
    '/vendor/',
    '/rider/',
    '/admin/'
];

// Install event - cache static files
self.addEventListener('install', event => {
    console.log('Service Worker: Installing...');
    
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => {
                console.log('Service Worker: Caching static files');
                return cache.addAll(STATIC_FILES);
            })
            .then(() => {
                console.log('Service Worker: Static files cached');
                return self.skipWaiting();
            })
            .catch(err => {
                console.error('Service Worker: Error caching static files', err);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    console.log('Service Worker: Activating...');
    
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                return Promise.all(
                    cacheNames.map(cacheName => {
                        if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                            console.log('Service Worker: Deleting old cache', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('Service Worker: Activated');
                return self.clients.claim();
            })
    );
});

// Fetch event - serve from cache or network
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }
    
    // Skip chrome-extension and other non-http requests
    if (!url.protocol.startsWith('http')) {
        return;
    }
    
    event.respondWith(
        caches.match(request)
            .then(cachedResponse => {
                if (cachedResponse) {
                    console.log('Service Worker: Serving from cache', request.url);
                    return cachedResponse;
                }
                
                // Not in cache, fetch from network
                return fetch(request)
                    .then(networkResponse => {
                        // Check if valid response
                        if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
                            return networkResponse;
                        }
                        
                        // Clone response for caching
                        const responseToCache = networkResponse.clone();
                        
                        // Cache dynamic content
                        if (shouldCacheDynamically(request.url)) {
                            caches.open(DYNAMIC_CACHE)
                                .then(cache => {
                                    console.log('Service Worker: Caching dynamic content', request.url);
                                    cache.put(request, responseToCache);
                                });
                        }
                        
                        return networkResponse;
                    })
                    .catch(err => {
                        console.log('Service Worker: Network fetch failed', err);
                        
                        // Return offline fallback for HTML pages
                        if (request.headers.get('accept').includes('text/html')) {
                            return caches.match('/offline.html') || 
                                   new Response('<h1>Offline</h1><p>Please check your internet connection.</p>', {
                                       headers: { 'Content-Type': 'text/html' }
                                   });
                        }
                        
                        // Return offline fallback for images
                        if (request.headers.get('accept').includes('image')) {
                            return caches.match('/public/images/offline.png') ||
                                   new Response('', { status: 404 });
                        }
                        
                        throw err;
                    });
            })
    );
});

// Helper function to determine if content should be cached dynamically
function shouldCacheDynamically(url) {
    return DYNAMIC_FILES.some(pattern => url.includes(pattern)) ||
           url.includes('/public/images/') ||
           url.includes('/api/menu/') ||
           url.includes('/api/restaurants/');
}

// Background sync for offline orders
self.addEventListener('sync', event => {
    console.log('Service Worker: Background sync', event.tag);
    
    if (event.tag === 'order-sync') {
        event.waitUntil(syncOrders());
    }
    
    if (event.tag === 'cart-sync') {
        event.waitUntil(syncCart());
    }
});

// Sync offline orders when connection is restored
async function syncOrders() {
    try {
        const orders = await getOfflineOrders();
        
        for (const order of orders) {
            try {
                const response = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(order)
                });
                
                if (response.ok) {
                    await removeOfflineOrder(order.id);
                    console.log('Service Worker: Order synced successfully', order.id);
                }
            } catch (err) {
                console.error('Service Worker: Failed to sync order', order.id, err);
            }
        }
    } catch (err) {
        console.error('Service Worker: Error syncing orders', err);
    }
}

// Sync cart data
async function syncCart() {
    try {
        const cart = await getOfflineCart();
        
        if (cart && cart.items.length > 0) {
            const response = await fetch('/api/cart/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(cart)
            });
            
            if (response.ok) {
                await clearOfflineCart();
                console.log('Service Worker: Cart synced successfully');
            }
        }
    } catch (err) {
        console.error('Service Worker: Error syncing cart', err);
    }
}

// Push notification handling
self.addEventListener('push', event => {
    console.log('Service Worker: Push notification received');
    
    const options = {
        body: 'Your order status has been updated!',
        icon: '/public/images/icon-192x192.png',
        badge: '/public/images/badge-72x72.png',
        vibrate: [200, 100, 200],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'view',
                title: 'View Order',
                icon: '/public/images/view-icon.png'
            },
            {
                action: 'close',
                title: 'Close',
                icon: '/public/images/close-icon.png'
            }
        ]
    };
    
    if (event.data) {
        const data = event.data.json();
        options.body = data.message || options.body;
        options.data = { ...options.data, ...data };
    }
    
    event.waitUntil(
        self.registration.showNotification('Time2Eat', options)
    );
});

// Notification click handling
self.addEventListener('notificationclick', event => {
    console.log('Service Worker: Notification clicked');
    
    event.notification.close();
    
    if (event.action === 'view') {
        event.waitUntil(
            clients.openWindow('/dashboard')
        );
    } else if (event.action === 'close') {
        // Just close the notification
        return;
    } else {
        // Default action - open app
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// Helper functions for offline storage
async function getOfflineOrders() {
    // Implementation would use IndexedDB
    return [];
}

async function removeOfflineOrder(orderId) {
    // Implementation would use IndexedDB
    return true;
}

async function getOfflineCart() {
    // Implementation would use IndexedDB
    return null;
}

async function clearOfflineCart() {
    // Implementation would use IndexedDB
    return true;
}
