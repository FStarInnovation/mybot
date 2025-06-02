import { browser } from '$app/environment';
import { PUBLIC_VAPID_KEY } from '$env/static/public';

// Backend base URL, should include trailing /api if you want absolute path
// e.g. http://127.0.0.1:8001/api  OR leave empty string to use same-origin /api
const API_BASE: string = (import.meta.env.VITE_API_BASE_URL as string) ?? (import.meta.env.DEV ? 'http://127.0.0.1:8000/api' : '/api');

// Convert VAPID public key from base64 to Uint8Array
const urlBase64ToUint8Array = (base64String: string): Uint8Array => {
  const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
  const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
  const rawData = atob(base64);
  const outputArray = new Uint8Array(rawData.length);

  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
};

// Subscribe to push notifications
export const subscribeToPush = async (): Promise<PushSubscription | null> => {
  if (!browser || !('serviceWorker' in navigator) || !('PushManager' in window)) {
    console.warn('Push notifications are not supported in this browser');
    return null;
  }

  try {
    const registration = await navigator.serviceWorker.ready;
    
    // Check if already subscribed
    let subscription = await registration.pushManager.getSubscription();
    if (subscription) {
      console.log('Already subscribed to push notifications – syncing with backend');
      try {
        await fetch(`${API_BASE}/push/subscribe`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(subscription.toJSON())
        });
      } catch (e) {
        console.warn('Failed to resync existing subscription:', e);
      }
      return subscription;
    }

    // Subscribe to push notifications
    subscription = await registration.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: urlBase64ToUint8Array(PUBLIC_VAPID_KEY)
    });

    // Send subscription to server
    await fetch(`${API_BASE}/push/subscribe`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(subscription.toJSON())
    });

    console.log('Successfully subscribed to push notifications');
    return subscription;
  } catch (error) {
    console.error('Error subscribing to push notifications:', error);
    return null;
  }
};

// Unsubscribe from push notifications
export const unsubscribeFromPush = async (): Promise<boolean> => {
  if (!browser || !('serviceWorker' in navigator) || !('PushManager' in window)) {
    return false;
  }

  try {
    const registration = await navigator.serviceWorker.ready;
    const subscription = await registration.pushManager.getSubscription();
    
    if (!subscription) {
      return true; // Already unsubscribed
    }

    // Unsubscribe from push service
    const success = await subscription.unsubscribe();
    
    if (success) {
      // Notify server to remove subscription
      await fetch(`${API_BASE}/push/unsubscribe`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ endpoint: subscription.endpoint })
      });
      console.log('Successfully unsubscribed from push notifications');
    }
    
    return success;
  } catch (error) {
    console.error('Error unsubscribing from push notifications:', error);
    return false;
  }
};

// Request notification permission
export const requestNotificationPermission = async (): Promise<NotificationPermission> => {
  if (!browser || !('Notification' in window)) {
    return 'denied';
  }

  if (Notification.permission === 'granted') {
    return 'granted';
  }

  // Request permission
  const permission = await Notification.requestPermission();
  
  if (permission === 'granted') {
    // Automatically subscribe if permission granted
    await subscribeToPush();
  }
  
  return permission;
};

// Show a test notification
export const showTestNotification = (title: string, options?: NotificationOptions): void => {
  if (!browser || !('Notification' in window) || Notification.permission !== 'granted') {
    return;
  }

  // Show notification
  const notification = new Notification(title, {
    icon: '/images/icon-192x192.png',
    badge: '/images/badge-72x72.png',
    ...options
  });

  // Handle click on notification
  notification.onclick = (event) => {
    event.preventDefault();
    window.focus();
    if (options.data?.url) {
      window.open(options.data.url, '_blank');
    }
  };
};
