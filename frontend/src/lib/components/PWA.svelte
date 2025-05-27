<script lang="ts">
  import { onMount } from 'svelte';
  import { pwaInstallEvent, pwaUpdateEvent } from '$lib/registerSW';

  let deferredPrompt: any = null;
  let showInstallPrompt = false;
  let showUpdatePrompt = false;

  onMount(() => {
    // Handle beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', handleBeforeInstallPrompt);
    
    // Listen for custom PWA events
    document.addEventListener('pwa:install', handlePWAInstall);
    document.addEventListener('pwa:update', handlePWAUpdate);

    return () => {
      window.removeEventListener('beforeinstallprompt', handleBeforeInstallPrompt);
      document.removeEventListener('pwa:install', handlePWAInstall);
      document.removeEventListener('pwa:update', handlePWAUpdate);
    };
  });

  function handleBeforeInstallPrompt(e: Event) {
    // Prevent the default install prompt
    e.preventDefault();
    // Stash the event so it can be triggered later
    deferredPrompt = e;
    showInstallPrompt = true;
  }

  function handlePWAInstall() {
    console.log('App ready to be installed');
    showInstallPrompt = true;
  }

  function handlePWAUpdate() {
    console.log('New version available!');
    showUpdatePrompt = true;
  }

  async function installApp() {
    if (!deferredPrompt) return;
    
    // Show the install prompt
    deferredPrompt.prompt();
    
    // Wait for the user to respond to the prompt
    const { outcome } = await deferredPrompt.userChoice;
    
    // Log the result
    console.log(`User response to the install prompt: ${outcome}`);
    
    // Reset the deferred prompt variable
    deferredPrompt = null;
    showInstallPrompt = false;
  }

  function updateApp() {
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.getRegistration().then((registration) => {
        if (registration?.waiting) {
          // Send a message to the service worker to skip waiting
          registration.waiting.postMessage({ type: 'SKIP_WAITING' });
        }
      });
    }
    showUpdatePrompt = false;
    // Reload the page to apply the update
    window.location.reload();
  }
</script>

{#if showInstallPrompt}
  <div class="pwa-prompt">
    <div class="pwa-content">
      <h3>Install MyBot</h3>
      <p>Add MyBot to your home screen for a better experience</p>
      <div class="pwa-buttons">
        <button on:click={installApp} class="btn btn-primary">Install</button>
        <button on:click={() => showInstallPrompt = false} class="btn btn-secondary">Not Now</button>
      </div>
    </div>
  </div>
{/if}

{#if showUpdatePrompt}
  <div class="pwa-prompt">
    <div class="pwa-content">
      <h3>Update Available</h3>
      <p>A new version of MyBot is available. Update now?</p>
      <div class="pwa-buttons">
        <button on:click={updateApp} class="btn btn-primary">Update</button>
        <button on:click={() => showUpdatePrompt = false} class="btn btn-secondary">Later</button>
      </div>
    </div>
  </div>
{/if}

<style>
  .pwa-prompt {
    position: fixed;
    bottom: 1rem;
    right: 1rem;
    max-width: 320px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    padding: 1rem;
    z-index: 1000;
  }

  .pwa-content {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
  }

  .pwa-content h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
  }

  .pwa-content p {
    margin: 0;
    font-size: 0.9rem;
    color: #4b5563;
  }

  .pwa-buttons {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
  }

  .btn {
    padding: 0.5rem 1rem;
    border-radius: 4px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    border: none;
  }

  .btn-primary {
    background-color: #3b82f6;
    color: white;
  }

  .btn-primary:hover {
    background-color: #2563eb;
  }

  .btn-secondary {
    background-color: #e5e7eb;
    color: #4b5563;
  }

  .btn-secondary:hover {
    background-color: #d1d5db;
  }
</style>
