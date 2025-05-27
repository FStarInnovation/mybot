<script lang="ts">
  import { onMount } from 'svelte';
  import { page } from '$app/stores';
  import { fade } from 'svelte/transition';
  
  let currentPath = '/';
  let isMobileMenuOpen = false;
  
  const navItems = [
    { name: 'Chat', icon: '💬', path: '/chat' },
    { name: 'Dashboard', icon: '📊', path: '/dashboard' },
    { name: 'Bots', icon: '🤖', path: '/dashboard/bots' },
    { name: 'Analytics', icon: '📈', path: '/dashboard/analytics' },
    { name: 'Templates', icon: '📋', path: '/dashboard/templates' },
    { name: 'Integrations', icon: '🔌', path: '/dashboard/integrations' },
    { name: 'Settings', icon: '⚙️', path: '/dashboard/settings' },
  ];
  
  $: currentPath = $page.url.pathname;
  
  function toggleMobileMenu() {
    isMobileMenuOpen = !isMobileMenuOpen;
  }
  
  // Close mobile menu when clicking outside
  function handleClickOutside(event: MouseEvent) {
    const target = event.target as HTMLElement;
    if (isMobileMenuOpen && !target.closest('.sidebar') && !target.closest('.mobile-menu-button')) {
      isMobileMenuOpen = false;
    }
  }
  
  onMount(() => {
    document.addEventListener('click', handleClickOutside);
    return () => {
      document.removeEventListener('click', handleClickOutside);
    };
  });
</script>

<div class="dashboard-layout">
  <!-- Mobile Header -->
  <header class="mobile-header">
    <button class="mobile-menu-button" on:click={toggleMobileMenu}>
      {#if isMobileMenuOpen}
        <span>✕</span>
      {:else}
        <span>☰</span>
      {/if}
    </button>
    <h1>MyBot</h1>
  </header>
  
  <!-- Sidebar -->
  <aside class="sidebar" class:open={isMobileMenuOpen} transition:fade>
    <div class="sidebar-header">
      <div class="logo">MyBot</div>
      <div class="user-info">
        <div class="user-avatar">👤</div>
        <div class="user-details">
          <div class="user-name">John Doe</div>
          <div class="user-email">john@example.com</div>
        </div>
      </div>
    </div>
    
    <nav class="sidebar-nav">
      <ul>
        {#each navItems as item}
          <li>
            <a 
              href={item.path} 
              class:active={currentPath === item.path}
              on:click={() => isMobileMenuOpen = false}
            >
              <span class="nav-icon">{item.icon}</span>
              <span class="nav-text">{item.name}</span>
            </a>
          </li>
        {/each}
      </ul>
    </nav>
    
    <div class="sidebar-footer">
      <a href="/logout" class="logout-btn">
        <span class="nav-icon">🚪</span>
        <span class="nav-text">Logout</span>
      </a>
    </div>
  </aside>
  
  <!-- Main Content -->
  <main class="main-content">
    <slot />
  </main>
</div>

<style>
  :global(body) {
    margin: 0;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    background-color: #f9fafb;
    color: #1f2937;
  }
  
  .dashboard-layout {
    display: grid;
    grid-template-columns: 240px 1fr;
    min-height: 100vh;
  }
  
  /* Mobile Header */
  .mobile-header {
    display: none;
    padding: 1rem;
    align-items: center;
    background: white;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    z-index: 40;
  }
  
  .mobile-menu-button {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    margin-right: 1rem;
    color: #4b5563;
  }
  
  .mobile-header h1 {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
  }
  
  /* Sidebar */
  .sidebar {
    background: white;
    border-right: 1px solid #e5e7eb;
    height: 100vh;
    position: sticky;
    top: 0;
    display: flex;
    flex-direction: column;
    z-index: 50;
  }
  
  .sidebar-header {
    padding: 1.5rem 1rem 1rem;
    border-bottom: 1px solid #f3f4f6;
  }
  
  .logo {
    font-size: 1.5rem;
    font-weight: 700;
    color: #3b82f6;
    margin-bottom: 1.5rem;
    padding: 0 0.5rem;
  }
  
  .user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: background-color 0.2s ease;
  }
  
  .user-info:hover {
    background-color: #f9fafb;
  }
  
  .user-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: #e0f2fe;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
  }
  
  .user-details {
    flex: 1;
    min-width: 0;
  }
  
  .user-name {
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
  .user-email {
    font-size: 0.75rem;
    color: #6b7280;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
  .sidebar-nav {
    flex: 1;
    overflow-y: auto;
    padding: 1rem 0.5rem;
  }
  
  .sidebar-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .sidebar-nav li {
    margin-bottom: 0.25rem;
  }
  
  .sidebar-nav a {
    display: flex;
    align-items: center;
    padding: 0.75rem 0.75rem;
    border-radius: 0.5rem;
    color: #4b5563;
    text-decoration: none;
    transition: all 0.2s ease;
  }
  
  .sidebar-nav a:hover {
    background-color: #f3f4f6;
    color: #1f2937;
  }
  
  .sidebar-nav a.active {
    background-color: #e0f2fe;
    color: #0369a1;
    font-weight: 500;
  }
  
  .nav-icon {
    font-size: 1.25rem;
    width: 1.75rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  
  .nav-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  
  .sidebar-footer {
    padding: 1rem;
    border-top: 1px solid #f3f4f6;
  }
  
  .logout-btn {
    display: flex;
    align-items: center;
    padding: 0.75rem 0.75rem;
    border-radius: 0.5rem;
    color: #ef4444;
    text-decoration: none;
    transition: all 0.2s ease;
  }
  
  .logout-btn:hover {
    background-color: #fee2e2;
  }
  
  /* Main Content */
  .main-content {
    flex: 1;
    min-height: 100vh;
    overflow-x: hidden;
    background-color: #f9fafb;
  }
  
  /* Responsive */
  @media (max-width: 1024px) {
    .dashboard-layout {
      grid-template-columns: 1fr;
    }
    
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      transform: translateX(-100%);
      transition: transform 0.3s ease;
      box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
    }
    
    .sidebar.open {
      transform: translateX(0);
    }
    
    .mobile-header {
      display: flex;
    }
  }
  
  /* Animation */
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s;
  }
  .fade-enter, .fade-leave-to {
    opacity: 0;
  }
</style>
