/// <reference types="@sveltejs/kit" />
/// <reference no-default-lib="true"/>
/// <reference lib="esnext" />
/// <reference lib="webworker" />

import { build, files, version } from '$service-worker';

// Создаем уникальное имя кеша на основе номера версии
const CACHE = `cache-${version}`;

// Список URL, которые будем кешировать
const ASSETS = [
  ...build, // файлы сборки приложения
  ...files  // статические файлы
];

// Service Worker устанавливается
self.addEventListener('install', (event) => {
  // Создаем кеш для необходимых ресурсов
  event.waitUntil(
    caches.open(CACHE).then((cache) => cache.addAll(ASSETS))
  );
});

// Service Worker активируется
self.addEventListener('activate', (event) => {
  // Очищаем старые кеши
  event.waitUntil(
    caches.keys().then(async (keys) => {
      for (const key of keys) {
        if (key !== CACHE) await caches.delete(key);
      }
    })
  );
});

// Обработка запросов
self.addEventListener('fetch', (event) => {
  // Игнорируем запросы, которые не относятся к http/https (например chrome-extension://)
  if (!event.request.url.startsWith('http')) {
    return;
  }
  // Пропускаем запросы к API
  if (event.request.url.includes('/api/') || event.request.url.includes('/llm/')) {
    return;
  }

  // Стратегия "сначала кеш, потом сеть"
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request).then((fetchResponse) => {
        return caches.open(CACHE).then((cache) => {
          cache.put(event.request, fetchResponse.clone());
          return fetchResponse;
        });
      });
    })
  );
});

// Handle push events and display notifications
self.addEventListener('push', (event: PushEvent) => {
  let data: any = {};
  if (event.data) {
    try {
      data = event.data.json();
    } catch {
      // if payload is plain text, wrap it
      data = { title: 'MyBot', body: event.data.text() };
    }
  }

  const title = data.title ?? 'MyBot';
  const options: NotificationOptions = {
    body: data.body ?? '',
    icon: data.icon ?? '/icon-192x192.png',
    badge: data.badge ?? '/icon-72x72.png',
    data: {
      url: data.url ?? '/'
    }
  };

  event.waitUntil(self.registration.showNotification(title, options));
});

// Focus or open tab when user clicks notification
self.addEventListener('notificationclick', (event: NotificationEvent) => {
  const notification = event.notification;
  const url = notification.data?.url || '/';
  event.notification.close();

  event.waitUntil(
    self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
      for (const client of clientList) {
        const win = client as WindowClient;
        if (win.url === url && 'focus' in win) {
          return win.focus();
        }
      }
      if (self.clients.openWindow) {
        return self.clients.openWindow(url);
      }
    })
  );
});
