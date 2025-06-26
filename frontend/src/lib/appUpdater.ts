/**
 * Утилита для обновления PWA приложения, очистки кэша и перезагрузки Service Worker
 */

/**
 * Отправляет сигнал обновления всем клиентам 
 * (для уведомления об обновлении без перезагрузки страницы)
 */
async function updateServiceWorker() {
  if ('serviceWorker' in navigator) {
    try {
      const registration = await navigator.serviceWorker.ready;
      
      // Отправляем сигнал SKIP_WAITING для запуска новой версии SW
      if (registration?.waiting) {
        registration.waiting.postMessage({ type: 'SKIP_WAITING' });
      }
      
      // Логирование успешного обновления
      console.log('[AppUpdater] Service worker update initiated');
      
      return true;
    } catch (err) {
      console.error('[AppUpdater] Failed to update service worker:', err);
      return false;
    }
  }
  return false;
}

/**
 * Очищает все кэши для данного домена
 */
async function clearCaches() {
  if ('caches' in window) {
    try {
      // Получаем список всех кэшей
      const cacheNames = await caches.keys();
      
      // Удаляем каждый кэш
      await Promise.all(
        cacheNames.map(cacheName => {
          console.log(`[AppUpdater] Deleting cache: ${cacheName}`);
          return caches.delete(cacheName);
        })
      );
      
      console.log('[AppUpdater] All caches cleared successfully');
      return true;
    } catch (err) {
      console.error('[AppUpdater] Failed to clear caches:', err);
      return false;
    }
  }
  return false;
}

/**
 * Отменяет регистрацию всех service worker'ов
 */
async function unregisterServiceWorkers() {
  if ('serviceWorker' in navigator) {
    try {
      // Получаем все регистрации service worker'ов
      const registrations = await navigator.serviceWorker.getRegistrations();
      
      // Отменяем регистрацию каждого service worker'а
      await Promise.all(
        registrations.map(registration => {
          console.log(`[AppUpdater] Unregistering service worker: ${registration.scope}`);
          return registration.unregister();
        })
      );
      
      console.log('[AppUpdater] All service workers unregistered');
      return true;
    } catch (err) {
      console.error('[AppUpdater] Failed to unregister service workers:', err);
      return false;
    }
  }
  return false;
}

/**
 * Полное обновление приложения:
 * 1. Отправляет сигнал обновления service worker
 * 2. Очищает кэш браузера для данного домена
 * 3. Отменяет регистрацию service worker
 * 4. Перезагружает страницу для получения свежих данных
 */
export async function forceUpdateApplication() {
  console.log('[AppUpdater] Starting force update process...');
  
  // Сначала пробуем обновить SW
  const swUpdated = await updateServiceWorker();
  console.log(`[AppUpdater] Service worker update ${swUpdated ? 'initiated' : 'failed'}`);
  
  // Очищаем кэши
  const cachesCleared = await clearCaches();
  console.log(`[AppUpdater] Caches ${cachesCleared ? 'cleared' : 'clear failed'}`);
  
  // Отменяем регистрацию SW
  const swUnregistered = await unregisterServiceWorkers();
  console.log(`[AppUpdater] Service workers ${swUnregistered ? 'unregistered' : 'unregister failed'}`);
  
  // Создаем запись в локальном хранилище как флаг для страницы после перезагрузки
  localStorage.setItem('app_just_updated', Date.now().toString());
  
  // Добавляем случайный query-параметр для полной перезагрузки без кэша
  const url = new URL(window.location.href);
  url.searchParams.set('v', Date.now().toString());
  
  console.log('[AppUpdater] Reloading page with cache-busting parameter');
  window.location.href = url.toString();
  
  return { swUpdated, cachesCleared, swUnregistered };
}

/**
 * Проверяет, было ли только что выполнено обновление приложения
 */
export function checkJustUpdated(): boolean {
  const justUpdated = localStorage.getItem('app_just_updated');
  
  if (justUpdated) {
    // Удаляем флаг обновления
    localStorage.removeItem('app_just_updated');
    
    // Вычисляем, прошло ли менее 5 секунд с момента обновления
    const updateTime = parseInt(justUpdated);
    const justNow = Date.now() - updateTime < 5000;
    
    return justNow;
  }
  
  return false;
}
