<script lang="ts">
  import '../app.css';
  import { registerSW } from '$lib/registerSW';
  import PWA from '$lib/components/PWA.svelte';
  import PWASplash from '$lib/components/PWASplash.svelte';
  import ThemeToggle from '$lib/components/ThemeToggle.svelte';
  import { onMount } from 'svelte';
  
  // TanStack Query
  import { QueryClientProvider } from '@tanstack/svelte-query';
  import { queryClient } from '$lib/tanstack/client';
  
  // Check if the app is running as a PWA
  let isPWA = false;
  
  onMount(() => {
    // Register service worker
    if (typeof window !== 'undefined') {
      registerSW({
        onOfflineReady: () => {
          console.log('App ready to work offline');
        },
        onNeedRefresh: () => {
          console.log('New content available, please refresh');
        }
      });
      
      // Check if the app is running in standalone mode (installed PWA)
      isPWA = window.matchMedia('(display-mode: standalone)').matches || 
              (window.navigator as any).standalone === true ||
              document.referrer.includes('android-app://');
    }
  });
</script>

<!-- PWA Splash Screen -->
{#if isPWA}
  <PWASplash />
{/if}

<QueryClientProvider client={queryClient}>
  <main class="app-container">
    <slot />
  </main>
</QueryClientProvider>

<!-- PWA Install/Update Prompt -->
<PWA />

<div class="theme-toggle-container">
  <ThemeToggle />
</div>

<style>
  :global(html, body) {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    overflow-x: hidden;
  }
  
  :global(#svelte) {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
  
  .theme-toggle-container {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 1000; /* Ensure it's above other content */
  }

  .app-container {
    flex: 1;
    display: flex;
    flex-direction: column;
  }
</style>
