// Custom service worker with robust error handling and automatic cache versioning
const timestamp = Date.now(); // Автоматическая версия кэша на основе времени сборки
const cacheId = `mybot-cache-${timestamp}`;
const BASE_PATH = location.pathname.split('/').slice(0, -1).join('/');

// Files that require network-first approach
const API_ROUTES = ['/api/', '/llm/'];
const APP_ASSET_PREFIXES = ['/_app/'];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(cacheId).then(async cache => {
      // Не используем cache.addAll, так как это требует успешного выполнения для всех файлов
      const failedItems = [];
      
      // Динамически определяем все статические файлы
      // В продакшн это лучше заменить на автоматически генерируемый список из сборки
      const staticAssets = await getStaticAssets();
      
      for (const url of staticAssets) {
        try {
          // Кэшируем каждый файл отдельно
          await cache.add(url);
        } catch (error) {
          console.warn(`[Service Worker] Failed to cache: ${url}`, error);
          failedItems.push({ url, error: error.message });
        }
      }
      
      console.log(`[Service Worker] Installation complete. Cache version: ${timestamp}`);
      if (failedItems.length > 0) {
        console.warn(`[Service Worker] Failed to cache ${failedItems.length} items:`);
        console.warn(failedItems);
      }
    })
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(async cacheNames => {
      // Удаляем все старые кэши
      for (const cacheName of cacheNames) {
        if (cacheName !== cacheId) {
          console.log(`[Service Worker] Deleting old cache: ${cacheName}`);
          await caches.delete(cacheName);
        }
      }
      // Активный контроль над всеми клиентами без перезагрузки
      await self.clients.claim();
      console.log('[Service Worker] Now controlling all open pages');
    })
  );
});

self.addEventListener('fetch', event => {
  // Пропускаем не-HTTP запросы
  if (!event.request.url.startsWith('http')) {
    return;
  }

  // Определяем стратегию кэширования на основе URL
  const url = new URL(event.request.url);
  const isAPIRequest = API_ROUTES.some(route => url.pathname.includes(route));
  const isAppAsset = APP_ASSET_PREFIXES.some(prefix => url.pathname.startsWith(prefix));
  const accept = event.request.headers.get('Accept') || '';
  const isNavigation = event.request.mode === 'navigate' || accept.includes('text/html');

  if (isAPIRequest || isNavigation || isAppAsset) {
    // Для API запросов всегда свежие данные (network-first)
    event.respondWith(networkFirst(event.request));
  } else {
    // Для статики сначала кэш, потом сеть (cache-first)
    event.respondWith(cacheFirst(event.request));
  }
});

// Обработка push-уведомлений
self.addEventListener('push', event => {
  let data = {};
  
  if (event.data) {
    try {
      data = event.data.json();
    } catch {
      data = { title: 'MyBot', body: event.data.text() };
    }
  }
  
  const title = data.title ?? 'MyBot';
  const options = {
    body: data.body ?? '',
    icon: data.icon ?? '/icon-192x192.png',
    badge: data.badge ?? '/icon-72x72.png',
    data: { url: data.url ?? '/' }
  };
  
  event.waitUntil(self.registration.showNotification(title, options));
});

// Обработка клика по уведомлению
self.addEventListener('notificationclick', event => {
  const url = event.notification.data?.url || '/';
  event.notification.close();
  
  event.waitUntil(
    self.clients.matchAll({ type: 'window', includeUncontrolled: true })
      .then(clientList => {
        for (const client of clientList) {
          if (client.url === url && 'focus' in client) {
            return client.focus();
          }
        }
        
        if (self.clients.openWindow) {
          return self.clients.openWindow(url);
        }
      })
  );
});

// Вспомогательные функции
async function getStaticAssets() {
  // В идеале этот список должен генерироваться автоматически при сборке
  return [
    `${BASE_PATH}/favicon.png`,
    `${BASE_PATH}/manifest.json`,
    `${BASE_PATH}/offline.html`,
    `${BASE_PATH}/pwa-192x192.png`,
    `${BASE_PATH}/pwa-512x512.png`
    // Скомпилированные JS/CSS будут добавлены здесь при сборке
  ];
}

async function cacheFirst(request) {
  try {
    // Сначала пробуем получить из кэша
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
      return cachedResponse;
    }
    
    // Если нет в кэше, идем в сеть
    const networkResponse = await fetch(request);
    
    // Кэшируем полученный ресурс (только если это успешный ответ)
    if (networkResponse.ok) {
      const cache = await caches.open(cacheId);
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    console.error(`[Service Worker] Cache-first strategy failed for: ${request.url}`, error);
    
    // Fallback для HTML страниц
    if (request.headers.get('Accept')?.includes('text/html')) {
      return caches.match('/offline.html');
    }
    
    // По умолчанию просто показываем ошибку
    return new Response('Network error happened', {
      status: 408,
      headers: { 'Content-Type': 'text/plain' }
    });
  }
}

async function networkFirst(request) {
  try {
    // Сначала пробуем получить из сети
    const networkResponse = await fetch(request);
    
    // Кэшируем полученный ресурс
    const cache = await caches.open(cacheId);
    cache.put(request, networkResponse.clone());
    
    return networkResponse;
  } catch (error) {
    // Если сеть недоступна, используем кэш
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
      return cachedResponse;
    }
    
    // Если и в кэше нет, возвращаем ошибку
    console.error(`[Service Worker] Network-first strategy failed for: ${request.url}`, error);
    return new Response('Network error happened', {
      status: 408,
      headers: { 'Content-Type': 'text/plain' }
    });
  }
}
