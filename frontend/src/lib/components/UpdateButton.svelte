<script lang="ts">
  import { forceUpdateApplication, checkJustUpdated } from '$lib/appUpdater';
  import { onMount } from 'svelte';
  
  // Props
  export let variant: 'icon' | 'text' | 'full' = 'full';
  export let buttonClass = '';
  
  // State
  let updating = false;
  let justUpdated = false;
  let fadeTimeout: NodeJS.Timeout | null = null;
  
  onMount(() => {
    // Проверяем, было ли только что выполнено обновление
    justUpdated = checkJustUpdated();
    
    // Если было обновление, показываем уведомление на 3 секунды
    if (justUpdated) {
      fadeTimeout = setTimeout(() => {
        justUpdated = false;
      }, 3000);
    }
    
    return () => {
      if (fadeTimeout) clearTimeout(fadeTimeout);
    };
  });
  
  // Выполнение обновления
  async function handleUpdate() {
    if (updating) return;
    
    updating = true;
    try {
      await forceUpdateApplication();
    } catch (err) {
      console.error('Failed to update application:', err);
      updating = false;
    }
  }
</script>

{#if justUpdated}
  <div class="update-notification">
    <span class="update-icon">✓</span>
    <span>Приложение обновлено</span>
  </div>
{:else}
  {#if variant === 'icon'}
    <button 
      on:click={handleUpdate} 
      class="update-button-icon {buttonClass}" 
      disabled={updating}
      title="Обновить приложение"
      aria-label="Обновить приложение">
      {#if updating}
        <span class="update-spinner"></span>
      {:else}
        <span class="update-icon">↻</span>
      {/if}
    </button>
  {:else if variant === 'text'}
    <button 
      on:click={handleUpdate} 
      class="update-button-text {buttonClass}" 
      disabled={updating}>
      {#if updating}
        Обновление...
      {:else}
        Обновить
      {/if}
    </button>
  {:else}
    <button 
      on:click={handleUpdate} 
      class="update-button-full {buttonClass}" 
      disabled={updating}>
      {#if updating}
        <span class="update-spinner"></span>
        <span>Обновление...</span>
      {:else}
        <span class="update-icon">↻</span>
        <span>Обновить приложение</span>
      {/if}
    </button>
  {/if}
{/if}

<style>
  .update-button-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background-color: #3b82f6;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s, transform 0.2s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .update-button-icon:hover {
    background-color: #2563eb;
    transform: scale(1.05);
  }
  
  .update-button-icon:disabled {
    background-color: #93c5fd;
    cursor: not-allowed;
    transform: none;
  }
  
  .update-button-text {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    background-color: #3b82f6;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s;
    font-weight: 500;
  }
  
  .update-button-text:hover {
    background-color: #2563eb;
  }
  
  .update-button-text:disabled {
    background-color: #93c5fd;
    cursor: not-allowed;
  }
  
  .update-button-full {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    background-color: #3b82f6;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s, transform 0.2s;
    font-weight: 500;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .update-button-full:hover {
    background-color: #2563eb;
    transform: translateY(-1px);
  }
  
  .update-button-full:disabled {
    background-color: #93c5fd;
    cursor: not-allowed;
    transform: none;
  }
  
  .update-spinner {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s infinite linear;
  }
  
  .update-icon {
    font-size: 1.25rem;
    line-height: 1;
  }
  
  .update-notification {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    background-color: #10b981;
    color: white;
    font-weight: 500;
    animation: fadeIn 0.3s ease-in-out;
  }
  
  @keyframes spin {
    from {
      transform: rotate(0deg);
    }
    to {
      transform: rotate(360deg);
    }
  }
  
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
