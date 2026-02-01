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
      <div class="logo-badge">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" fill="currentColor"/>
        </svg>
      </div>
      <span class="logo-text">MyBot</span>
    </a>
    
    <nav class="desktop-nav">
      {#each navItems as item}
        <a href={item.href} class="nav-link">{item.label}</a>
      {/each}
    </nav>
    
    <div class="actions">
      <a href="/chat" class="btn-login">Sign In</a>
      <a href="/chat" class="btn-primary">
        <span>Get Started</span>
        <svg class="arrow" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/>
        </svg>
      </a>
    </div>
    
    <button 
      class="mobile-menu-btn"
      class:active={isMobileMenuOpen}
      on:click={() => isMobileMenuOpen = !isMobileMenuOpen}
      aria-label="Toggle menu"
    >
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
  
  {#if isMobileMenuOpen}
    <div class="mobile-menu" on:click={() => isMobileMenuOpen = false}>
      <div class="mobile-menu-content" on:click|stopPropagation>
        {#each navItems as item}
          <a href={item.href} class="mobile-nav-link" on:click={() => isMobileMenuOpen = false}>
            {item.label}
          </a>
        {/each}
        <div class="mobile-actions">
          <a href="/chat" class="btn-login-mobile">Sign In</a>
          <a href="/chat" class="btn-primary-mobile">Get Started</a>
        </div>
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
    transition: all 0.3s ease;
  }
  
  header.scrolled {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
    gap: 0.75rem;
    text-decoration: none;
  }
  
  .logo-badge {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
  }
  
  .logo-badge svg {
    width: 20px;
    height: 20px;
  }
  
  .logo-text {
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #fff 0%, #e2e8f0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .desktop-nav {
    display: flex;
    gap: 2rem;
  }
  
  .nav-link {
    color: #94a3b8;
    font-weight: 500;
    font-size: 0.9375rem;
    text-decoration: none;
    transition: color 0.2s ease;
    position: relative;
  }
  
  .nav-link:hover {
    color: #fff;
  }
  
  .nav-link::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #6366f1, #d946ef);
    transition: width 0.2s ease;
  }
  
  .nav-link:hover::after {
    width: 100%;
  }
  
  .actions {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .btn-login {
    color: #94a3b8;
    font-weight: 500;
    text-decoration: none;
    padding: 0.5rem 1rem;
    transition: color 0.2s ease;
  }
  
  .btn-login:hover {
    color: #fff;
  }
  
  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    padding: 0.625rem 1.25rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9375rem;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
  }
  
  .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
  }
  
  .arrow {
    width: 18px;
    height: 18px;
    transition: transform 0.2s ease;
  }
  
  .btn-primary:hover .arrow {
    transform: translateX(2px);
  }
  
  .mobile-menu-btn {
    display: none;
    flex-direction: column;
    gap: 5px;
    padding: 0.5rem;
    background: none;
    border: none;
    cursor: pointer;
    position: relative;
    z-index: 1002;
  }
  
  .mobile-menu-btn span {
    display: block;
    width: 24px;
    height: 2px;
    background: #fff;
    border-radius: 2px;
    transition: all 0.3s ease;
  }
  
  .mobile-menu-btn.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
  }
  
  .mobile-menu-btn.active span:nth-child(2) {
    opacity: 0;
  }
  
  .mobile-menu-btn.active span:nth-child(3) {
    transform: rotate(-45deg) translate(5px, -5px);
  }
  
  .mobile-menu {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1001;
  }
  
  .mobile-menu-content {
    position: absolute;
    top: 72px;
    left: 1rem;
    right: 1rem;
    background: rgba(30, 41, 59, 0.95);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .mobile-nav-link {
    color: #e2e8f0;
    font-weight: 500;
    text-decoration: none;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    transition: all 0.2s ease;
  }
  
  .mobile-nav-link:hover {
    background: rgba(99, 102, 241, 0.1);
    color: #6366f1;
  }
  
  .mobile-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
  }
  
  .btn-login-mobile {
    color: #94a3b8;
    font-weight: 500;
    text-decoration: none;
    padding: 0.75rem;
    text-align: center;
  }
  
  .btn-primary-mobile {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    padding: 0.75rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
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
