// Type definitions for the PWA registration
type RegisterSWOptions = {
  onNeedRefresh?: () => void;
  onOfflineReady?: () => void;
  onRegistered?: (registration: ServiceWorkerRegistration | null) => void;
  onRegisterError?: (error: any) => void;
};

// Extend the existing Window interface
declare global {
  interface Window {
    workbox: any;
    __WB_MANIFEST: any;
    skipWaiting: () => void;
  }
}

export const pwaInstallEvent = new Event('pwa:install');
export const pwaUpdateEvent = new Event('pwa:update');

// Default options for the service worker registration
const defaultOptions: RegisterSWOptions = {
  onNeedRefresh() {
    document.dispatchEvent(pwaUpdateEvent);
  },
  onOfflineReady() {
    document.dispatchEvent(pwaInstallEvent);
  },
  onRegistered(registration: ServiceWorkerRegistration | null) {
    console.log('Service Worker registered');
    if (registration) {
      // Check for updates every hour
      setInterval(() => {
        registration.update().catch(console.error);
      }, 3600000);
    }
  },
  onRegisterError(error) {
    console.error('Error during service worker registration:', error);
  }
};

/**
 * Register the service worker with the given options
 */
export function registerSW(options: Partial<RegisterSWOptions> = {}) {
  if (typeof window === 'undefined' || !('serviceWorker' in navigator)) {
    return;
  }

  const mergedOptions: RegisterSWOptions = { ...defaultOptions, ...options };
  const { onNeedRefresh, onOfflineReady, onRegistered, onRegisterError } = mergedOptions;

  // Register the service worker
  window.addEventListener('load', () => {
    navigator.serviceWorker
      .register('/sw.js', { type: 'module' })
      .then((registration) => {
        onRegistered?.(registration);

        // Handle updates
        if (registration.waiting) {
          onNeedRefresh?.();
        }

        // Listen for new service worker installation
        registration.addEventListener('updatefound', () => {
          const newWorker = registration.installing;
          if (!newWorker) return;

          newWorker.addEventListener('statechange', () => {
            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
              onNeedRefresh?.();
            } else if (newWorker.state === 'activated') {
              onOfflineReady?.();
            }
          });
        });
      })
      .catch((error) => {
        console.error('Service worker registration failed:', error);
        onRegisterError?.(error);
      });
  });

  // Listen for the controllerchange event to detect when a new service worker takes over
  navigator.serviceWorker.addEventListener('controllerchange', () => {
    window.location.reload();
  });
}

// Listen for the "Skip Waiting" message from the service worker
if (typeof window !== 'undefined' && 'serviceWorker' in navigator) {
  navigator.serviceWorker.addEventListener('message', (event) => {
    if (event.data?.type === 'SKIP_WAITING') {
      navigator.serviceWorker.getRegistration().then((registration) => {
        if (registration?.waiting) {
          registration.waiting.postMessage({ type: 'SKIP_WAITING' });
        }
      });
    }
  });
}
