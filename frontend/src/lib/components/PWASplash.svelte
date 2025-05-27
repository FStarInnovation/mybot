<script lang="ts">
  import { onMount } from 'svelte';
  
  let showSplash = true;
  
  onMount(() => {
    // Hide splash screen after 2 seconds
    const timer = setTimeout(() => {
      showSplash = false;
    }, 2000);
    
    return () => clearTimeout(timer);
  });
</script>

{#if showSplash}
  <div class="splash-screen">
    <div class="splash-content">
      <img src="/pwa-192x192.png" alt="MyBot Logo" class="splash-logo" />
      <h1>MyBot</h1>
      <div class="splash-spinner"></div>
    </div>
  </div>
{/if}

<style>
  .splash-screen {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    transition: opacity 0.5s ease-out;
  }
  
  .splash-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem;
  }
  
  .splash-logo {
    width: 120px;
    height: 120px;
    margin-bottom: 1.5rem;
    animation: pulse 2s infinite;
  }
  
  .splash-content h1 {
    font-size: 2rem;
    color: #1f2937;
    margin: 0 0 2rem 0;
  }
  
  .splash-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #e5e7eb;
    border-top: 4px solid #3b82f6;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  @keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
  }
</style>
