<script lang="ts">
  import { theme, toggleTheme, type Theme } from '$lib/stores/theme';
  import { onMount } from 'svelte';

  let currentTheme: Theme;
  let mounted = false;

  theme.subscribe(value => {
    currentTheme = value;
  });

  onMount(() => {
    mounted = true;
  });

  function handleToggle() {
    toggleTheme();
  }
</script>

{#if mounted}
  <button 
    class="theme-toggle"
    on:click={handleToggle} 
    aria-label="Toggle theme"
    title="Toggle theme"
  >
    {#if currentTheme === 'light'}
      <span class="icon light-icon" role="img" aria-label="Light mode icon">☀️</span>
    {:else}
      <span class="icon dark-icon" role="img" aria-label="Dark mode icon">🌙</span>
    {/if}
  </button>
{/if}

<style>
  .theme-toggle {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s ease;
    position: relative;
    width: 2.5rem; /* 40px */
    height: 2.5rem; /* 40px */
  }

  .theme-toggle:hover {
    background-color: var(--theme-toggle-hover-bg, #f0f0f0);
  }

  .icon {
    font-size: 1.25rem; /* 20px */
    line-height: 1;
    transition: transform 0.3s ease-out, opacity 0.3s ease-out;
    position: absolute;
  }

  .theme-toggle .light-icon {
    opacity: var(--theme-light-icon-opacity, 1);
    transform: var(--theme-light-icon-transform, scale(1) rotate(0deg));
  }

  .theme-toggle .dark-icon {
    opacity: var(--theme-dark-icon-opacity, 1);
    transform: var(--theme-dark-icon-transform, scale(1) rotate(0deg));
  }

  /* Hide icons based on theme */
  :global([data-theme='dark']) .light-icon {
    opacity: 0;
    transform: scale(0.5) rotate(-90deg);
  }

  :global([data-theme='light']) .dark-icon {
    opacity: 0;
    transform: scale(0.5) rotate(90deg);
  }
</style>
