/// <reference types="@sveltejs/kit" />
import { build, files, version } from '$service-worker';

// Create a unique cache name
const CACHE = `farmabot-cache-${version}`;

// List of files to cache
const ASSETS = [
  ...build, // the built files
  ...files  // everything in `static`
];

// Install event - cache all static assets
self.addEventListener('install', (event: ExtendableEvent) => {
  // Create a new cache and add all files to it
  async function addFilesToCache() {
    const cache = await caches.open(CACHE);
    await cache.addAll(ASSETS);
  }

  event.waitUntil(addFilesToCache());
});

// Activate event - clean up old caches
self.addEventListener('activate', (event: ExtendableEvent) => {
  // Remove previous cached data
  async function deleteOldCaches() {
    for (const key of await caches.keys()) {
      if (key !== CACHE) {
        await caches.delete(key);
      }
    }
  }

  event.waitUntil(deleteOldCaches());
});

// Fetch event - serve from cache, falling back to network
self.addEventListener('fetch', (event: FetchEvent) => {
  // Skip non-GET requests
  if (event.request.method !== 'GET') return;

  async function respond() {
    const url = new URL(event.request.url);
    const cache = await caches.open(CACHE);

    // Skip non-http(s) requests
    if (!url.protocol.startsWith('http')) {
      return fetch(event.request);
    }

    // Skip Vite dev server requests
    if (url.hostname === self.location.hostname && url.port !== self.location.port) {
      return fetch(event.request);
    }

    // For all navigation requests, try the network first, fall back to offline page
    if (event.request.mode === 'navigate') {
      try {
        const networkResponse = await fetch(event.request);
        return networkResponse;
      } catch (error) {
        const cachedResponse = await cache.match('/offline.html');
        return cachedResponse || Response.error();
      }
    }

    // For all other requests, try cache first, then network
    const cachedResponse = await cache.match(event.request);
    if (cachedResponse) {
      return cachedResponse;
    }

    // If not in cache, try network and cache the response
    try {
      const networkResponse = await fetch(event.request);
      
      // Only cache successful responses and non-opaque responses
      if (networkResponse.ok && networkResponse.type === 'basic') {
        const responseToCache = networkResponse.clone();
        cache.put(event.request, responseToCache);
      }
      
      return networkResponse;
    } catch (error) {
      // If network fails, return the offline page
      if (url.pathname.endsWith('.html')) {
        return cache.match('/offline.html');
      }
      throw error;
    }
  }

  event.respondWith(respond());
});

// Push event - handle incoming push notifications
self.addEventListener('push', (event: PushEvent) => {
  // Check if we have notification data
  let data = { title: 'Farmabot', body: 'У вас новое уведомление' };
  
  try {
    if (event.data) {
      data = event.data.json();
    }
  } catch (error) {
    console.error('Error parsing push data:', error);
  }

  // Show the notification
  const promiseChain = self.registration.showNotification(data.title, {
    body: data.body,
    icon: '/images/icon-192x192.png',
    badge: '/images/badge-72x72.png',
    data: {
      url: data.url || '/',
      timestamp: Date.now()
    },
    vibrate: [200, 100, 200, 100, 200, 100, 200],
    tag: 'farmabot-notification',
    ...data.options
  });

  event.waitUntil(promiseChain);
});

// Notification click event
self.addEventListener('notificationclick', (event: NotificationEvent) => {
  // Close the notification
  event.notification.close();

  // Handle the click
  const urlToOpen = event.notification.data?.url || '/';
  
  // Open the app or focus the window if it's already open
  const promiseChain = clients.matchAll({ 
    type: 'window',
    includeUncontrolled: true
  })
  .then((windowClients) => {
    const matchingClient = windowClients.find((client) => 
      client.url === urlToOpen && 'focus' in client
    );
    
    if (matchingClient) {
      return matchingClient.focus();
    } else if (clients.openWindow) {
      return clients.openWindow(urlToOpen);
    }
  });

  event.waitUntil(promiseChain);
});

// Background sync event for offline support
self.addEventListener('sync', (event: SyncEvent) => {
  if (event.tag === 'sync-messages') {
    // Handle background sync for messages
    console.log('Background sync for messages');
  }
});

// Push subscription change event (for VAPID key rotation)
self.addEventListener('pushsubscriptionchange', (event: PushSubscriptionChangeEvent) => {
  event.waitUntil(
    Promise.resolve().then(async () => {
      const subscription = await self.registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(PUBLIC_VAPID_KEY)
      });

      // Send the new subscription to the server
      await fetch('/api/push/subscribe', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(subscription.toJSON())
      });
    })
  );
});
