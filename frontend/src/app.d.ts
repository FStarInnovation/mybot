// See https://kit.svelte.dev/docs/types#app
// for information about these interfaces
declare global {
  namespace App {
    // interface Error {}
    // interface Locals {}
    // interface PageData {}
    // interface Platform {}
  }

  // Extend the ServiceWorkerGlobalScope to include VAPID_PUBLIC_KEY
  interface ServiceWorkerGlobalScope {
    __WB_MANIFEST: string[];
    skipWaiting(): Promise<void>;
  }

  // Extend the Window interface for TypeScript
  interface Window {
    workbox: any; // Or import the actual type if available
  }
}

export {};
