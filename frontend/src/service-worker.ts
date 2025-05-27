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
