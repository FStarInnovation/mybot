<script lang="ts">
  import { onMount } from 'svelte';
  
  let isScrolled = false;
  let isMobileMenuOpen = false;
  
  const navItems = [
    { label: 'Features', href: '#features' },
    { label: 'How it Works', href: '#how-it-works' },
    { label: 'Pricing', href: '#pricing' },
    { label: 'FAQ', href: '#faq' }
  ];
  
  onMount(() => {
    const handleScroll = () => {
      isScrolled = window.scrollY > 20;
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  });
</script>

<header class:scrolled={isScrolled}>
  <div class="container">
    <a href="/" class="logo">
      <span class="logo-icon">🤖</span>
      <span class="logo-text">MyBot</span>
    </a>
    
    <nav class="desktop-nav">
      {#each navItems as item}
        <a href={item.href} class="nav-link">{item.label}</a>
      {/each}
    </nav>
    
    <div class="actions">
      <a href="/chat" class="btn-login">Login</a>
      <a href="/chat" class="btn-primary">Start Free Trial</a>
    </div>
    
    <button 
      class="mobile-menu-btn"
      on:click={() => isMobileMenuOpen = !isMobileMenuOpen}
      aria-label="Toggle menu"
    >
      <span class:open={isMobileMenuOpen}></span>
      <span class:open={isMobileMenuOpen}></span>
      <span class:open={isMobileMenuOpen}></span>
    </button>
  </div>
  
  {#if isMobileMenuOpen}
    <div class="mobile-menu">
      {#each navItems as item}
        <a href={item.href} class="mobile-nav-link" on:click={() => isMobileMenuOpen = false}>
          {item.label}
        </a>
      {/each}
      <div class="mobile-actions">
        <a href="/chat" class="btn-login">Login</a>
        <a href="/chat" class="btn-primary">Start Free Trial</a>
      </div>
    </div>
  {/if}
</header>

<style>
  header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: transparent;
    transition: all 0.3s ease;
  }
  
  header.scrolled {
    background: rgba(var(--bg-primary-rgb, 255, 255, 255), 0.9);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
  }
  
  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  
  .logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    text-decoration: none;
  }
  
  .logo-icon {
    font-size: 1.75rem;
  }
  
  .desktop-nav {
    display: flex;
    gap: 2rem;
  }
  
  .nav-link {
    color: var(--text-secondary);
    font-weight: 500;
    text-decoration: none;
    transition: color 0.2s ease;
  }
  
  .nav-link:hover {
    color: var(--accent-primary);
  }
  
  .actions {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .btn-login {
    color: var(--text-secondary);
    font-weight: 500;
    text-decoration: none;
    padding: 0.5rem 1rem;
  }
  
  .btn-login:hover {
    color: var(--text-primary);
  }
  
  .btn-primary {
    background: var(--accent-primary);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
  }
  
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--accent-primary-rgb), 0.4);
  }
  
  .mobile-menu-btn {
    display: none;
    flex-direction: column;
    gap: 5px;
    padding: 0.5rem;
    background: none;
    border: none;
    cursor: pointer;
  }
  
  .mobile-menu-btn span {
    display: block;
    width: 25px;
    height: 2px;
    background: var(--text-primary);
    transition: all 0.3s ease;
  }
  
  .mobile-menu-btn span.open:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
  }
  
  .mobile-menu-btn span.open:nth-child(2) {
    opacity: 0;
  }
  
  .mobile-menu-btn span.open:nth-child(3) {
    transform: rotate(-45deg) translate(5px, -5px);
  }
  
  .mobile-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--bg-primary);
    padding: 1rem 2rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    flex-direction: column;
    gap: 1rem;
  }
  
  .mobile-nav-link {
    color: var(--text-secondary);
    font-weight: 500;
    text-decoration: none;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
  }
  
  .mobile-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding-top: 1rem;
  }
  
  @media (max-width: 768px) {
    .desktop-nav,
    .actions {
      display: none;
    }
    
    .mobile-menu-btn,
    .mobile-menu {
      display: flex;
    }
  }
</style>
