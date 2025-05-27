<script lang="ts">
  import { onMount } from 'svelte';
  import { fade } from 'svelte/transition';
  
  let features = [
    {
      icon: '🚀',
      title: 'Fast & Lightweight',
      description: 'Built with SvelteKit for optimal performance'
    },
    {
      icon: '📱',
      title: 'PWA Support',
      description: 'Installable on any device and works offline'
    },
    {
      icon: '🎨',
      title: 'Modern UI',
      description: 'Clean and responsive design that works on all devices'
    },
    {
      icon: '⚡',
      title: 'Instant Loading',
      description: 'Fast page loads with service worker caching'
    }
  ];
  
  let isOnline = true;
  
  onMount(() => {
    // Update online status
    const updateOnlineStatus = () => {
      isOnline = navigator.onLine;
    };
    
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    
    return () => {
      window.removeEventListener('online', updateOnlineStatus);
      window.removeEventListener('offline', updateOnlineStatus);
    };
  });
</script>

<main class="main-content">
  <div class="hero">
    <div class="hero-content">
      <h1>Welcome to <span class="gradient-text">MyBot</span></h1>
      <p class="subtitle">A modern web application built with SvelteKit</p>
      
      <div class="cta-buttons">
        <a href="/chat" class="btn btn-primary">Start Chatting</a>
        <a href="#features" class="btn btn-outline">Learn More</a>
      </div>
      
      {#if !isOnline}
        <div class="offline-notice" transition:fade>
          <span>You are currently offline. Some features may be limited.</span>
        </div>
      {/if}
    </div>
  </div>

  <section id="features" class="features">
    <h2>Features</h2>
    <div class="features-grid">
      {#each features as feature, i}
        <div class="feature-card" in:fade={{ delay: 100 * i }}>
          <div class="feature-icon">{feature.icon}</div>
          <h3>{feature.title}</h3>
          <p>{feature.description}</p>
        </div>
      {/each}
    </div>
  </section>
</main>

<style>
  .main-content {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }
  
  .hero {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  }
  
  .hero-content {
    max-width: 800px;
    margin: 0 auto;
  }
  
  h1 {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    font-weight: 800;
    line-height: 1.2;
    color: #1f2937;
  }
  
  .gradient-text {
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .subtitle {
    font-size: 1.5rem;
    color: #4b5563;
    margin-bottom: 2.5rem;
  }
  
  .cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 2rem;
  }
  
  .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    border: 2px solid transparent;
  }
  
  .btn-primary {
    background-color: #3b82f6;
    color: white;
  }
  
  .btn-primary:hover {
    background-color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
  }
  
  .btn-outline {
    background-color: white;
    color: #3b82f6;
    border-color: #3b82f6;
  }
  
  .btn-outline:hover {
    background-color: #f8fafc;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
  }
  
  .offline-notice {
    background-color: #fef3c7;
    color: #92400e;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    margin-top: 1.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
  }
  
  .features {
    padding: 6rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
  }
  
  .features h2 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 3rem;
    color: #1f2937;
  }
  
  .features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
  }
  
  .feature-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }
  
  .feature-icon {
    font-size: 2.5rem;
    margin-bottom: 1.5rem;
  }
  
  .feature-card h3 {
    font-size: 1.25rem;
    margin-bottom: 0.75rem;
    color: #1f2937;
  }
  
  .feature-card p {
    color: #6b7280;
    margin: 0;
  }
  
  @media (max-width: 768px) {
    h1 {
      font-size: 2.5rem;
    }
    
    .subtitle {
      font-size: 1.25rem;
    }
    
    .cta-buttons {
      flex-direction: column;
    }
    
    .btn {
      width: 100%;
    }
  }
  
  @media (max-width: 480px) {
    h1 {
      font-size: 2rem;
    }
    
    .features {
      padding: 3rem 1rem;
    }
    
    .features h2 {
      font-size: 2rem;
    }
  }
</style>
