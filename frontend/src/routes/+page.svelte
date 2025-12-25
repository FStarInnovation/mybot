<script lang="ts">
  import { onMount } from 'svelte';
  import { fade, fly } from 'svelte/transition';
  
  let features = [
    {
      icon: '🧠',
      title: 'AI-Powered Analysis',
      description: 'Advanced language models process your data with deep understanding'
    },
    {
      icon: '📊',
      title: 'Multiple Data Sources',
      description: 'Upload files or connect APIs for comprehensive data analysis'
    },
    {
      icon: '⚡',
      title: 'Real-time Responses',
      description: 'Get instant answers from your processed data using intelligent search'
    },
    {
      icon: '🔗',
      title: 'API Integration',
      description: 'Seamlessly connect your existing systems with our powerful API'
    },
    {
      icon: '🛡️',
      title: 'Secure Processing',
      description: 'Your data is processed with enterprise-grade security measures'
    },
    {
      icon: '🎯',
      title: 'Smart Context',
      description: 'Context-aware responses that understand your specific domain'
    }
  ];
  
  let useCases = [
    {
      title: 'Healthcare & Pharma',
      description: 'Medical data analysis, drug information, patient records',
      gradient: 'from-blue-600 to-cyan-600'
    },
    {
      title: 'Business Intelligence',
      description: 'Market analysis, reports, KPIs, business metrics',
      gradient: 'from-purple-600 to-pink-600'
    },
    {
      title: 'Research & Development',
      description: 'Scientific papers, experimental data, research analysis',
      gradient: 'from-green-600 to-emerald-600'
    },
    {
      title: 'Customer Support',
      description: 'Ticket analysis, customer queries, knowledge base',
      gradient: 'from-orange-600 to-red-600'
    }
  ];
  
  let isOnline = true;
  let scrolled = false;
  
  onMount(() => {
    // Update online status
    const updateOnlineStatus = () => {
      isOnline = navigator.onLine;
    };
    
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    
    // Handle scroll effects
    const handleScroll = () => {
      scrolled = window.scrollY > 50;
    };
    
    window.addEventListener('scroll', handleScroll);
    
    return () => {
      window.removeEventListener('online', updateOnlineStatus);
      window.removeEventListener('offline', updateOnlineStatus);
      window.removeEventListener('scroll', handleScroll);
    };
  });
</script>

<main class="main-content">
  <!-- Navigation Header -->
  <header class:scrolled class="header">
    <nav class="nav-container">
      <div class="nav-brand">
        <span class="brand-text">MyBot</span>
      </div>
      <div class="nav-links">
        <a href="#features" class="nav-link">Features</a>
        <a href="#use-cases" class="nav-link">Use Cases</a>
        <a href="#api" class="nav-link">API</a>
        <a href="/chat" class="nav-link btn-try">Try Now</a>
      </div>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <div class="hero-badge" in:fly={{ y: -20, duration: 800 }}>
        <span class="badge-icon">✨</span>
        <span>Powered by Advanced AI</span>
      </div>
      
      <h1 in:fly={{ y: 20, delay: 200, duration: 800 }}>
        Ask Questions About 
        <span class="gradient-text">Your Data</span>
      </h1>
      
      <p class="subtitle" in:fly={{ y: 20, delay: 400, duration: 800 }}>
        Transform any data source into intelligent conversations. Upload files, connect APIs, 
        and get instant answers powered by cutting-edge language models.
      </p>
      
      <div class="cta-buttons" in:fly={{ y: 20, delay: 600, duration: 800 }}>
        <a href="/chat" class="btn btn-primary">
          <span>Start Chatting</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
        <a href="#api" class="btn btn-outline">
          <span>View API Docs</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14,2 14,8 20,8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10,9 9,9 8,9"/>
          </svg>
        </a>
      </div>
      
      <div class="hero-stats" in:fly={{ y: 20, delay: 800, duration: 800 }}>
        <div class="stat">
          <span class="stat-number">10M+</span>
          <span class="stat-label">Data Points Processed</span>
        </div>
        <div class="stat">
          <span class="stat-number">99.9%</span>
          <span class="stat-label">Uptime</span>
        </div>
        <div class="stat">
          <span class="stat-number">&lt;100ms</span>
          <span class="stat-label">Response Time</span>
        </div>
      </div>
      
      {#if !isOnline}
        <div class="offline-notice" transition:fade>
          <span class="offline-icon">⚠️</span>
          <span>You are currently offline. Some features may be limited.</span>
        </div>
      {/if}
    </div>
    
    <div class="hero-visual">
      <div class="floating-cards">
        <div class="card card-1">📊 Data Analysis</div>
        <div class="card card-2">🤖 AI Processing</div>
        <div class="card card-3">💬 Smart Chat</div>
      </div>
    </div>
  </section>

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

  <!-- Use Cases Section -->
  <section id="use-cases" class="use-cases">
    <div class="section-header">
      <h2>Perfect for Any Industry</h2>
      <p>From healthcare to finance, transform your data into intelligent insights</p>
    </div>
    
    <div class="use-cases-grid">
      {#each useCases as useCase, i}
        <div class="use-case-card" in:fly={{ y: 20, delay: 100 * i, duration: 600 }}>
          <div class="use-case-gradient {useCase.gradient}"></div>
          <div class="use-case-content">
            <h3>{useCase.title}</h3>
            <p>{useCase.description}</p>
          </div>
        </div>
      {/each}
    </div>
  </section>

  <!-- API Section -->
  <section id="api" class="api-section">
    <div class="api-content">
      <div class="api-text">
        <h2>Simple API Integration</h2>
        <p>Get started in minutes with our RESTful API. Upload data, ask questions, get answers.</p>
        
        <div class="code-snippet">
          <pre><code>curl -X POST https://api.mybot.com/chat \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '&#123;
    "message": "What are our top products?",
    "data_source": "your_data_id"
  &#125;'</code></pre>
        </div>
        
        <div class="api-buttons">
          <a href="/docs" class="btn btn-primary">View Documentation</a>
          <a href="/chat" class="btn btn-outline">Try Demo</a>
        </div>
      </div>
      
      <div class="api-visual">
        <div class="api-flow">
          <div class="flow-step">
            <span class="step-number">1</span>
            <span class="step-text">Upload Data</span>
          </div>
          <div class="flow-arrow">→</div>
          <div class="flow-step">
            <span class="step-number">2</span>
            <span class="step-text">AI Processing</span>
          </div>
          <div class="flow-arrow">→</div>
          <div class="flow-step">
            <span class="step-number">3</span>
            <span class="step-text">Get Answers</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-brand">
        <span class="brand-text">MyBot</span>
        <p>Transform your data into intelligent conversations</p>
      </div>
      
      <div class="footer-links">
        <div class="link-group">
          <h4>Product</h4>
          <a href="#features">Features</a>
          <a href="#api">API</a>
          <a href="/chat">Demo</a>
        </div>
        <div class="link-group">
          <h4>Company</h4>
          <a href="/about">About</a>
          <a href="/blog">Blog</a>
          <a href="/careers">Careers</a>
        </div>
        <div class="link-group">
          <h4>Resources</h4>
          <a href="/docs">Documentation</a>
          <a href="/support">Support</a>
          <a href="/status">Status</a>
        </div>
      </div>
    </div>
    
    <div class="footer-bottom">
      <p>&copy; 2025 MyBot. All rights reserved.</p>
    </div>
  </footer>
</main>

<style>
  :global(body) {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background: #000;
    color: #fff;
    overflow-x: hidden;
  }

  .main-content {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background: #000;
  }

  /* Header */
  .header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
  }

  .header.scrolled {
    background: rgba(0, 0, 0, 0.95);
    backdrop-filter: blur(20px);
  }

  .nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .brand-text {
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .nav-links {
    display: flex;
    align-items: center;
    gap: 2rem;
  }

  .nav-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.2s ease;
  }

  .nav-link:hover {
    color: #fff;
  }

  .btn-try {
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.2s ease;
  }

  .btn-try:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
  }

  /* Hero Section */
  .hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 2rem;
    background: radial-gradient(ellipse at center top, #0a0e27 0%, #000 50%);
    position: relative;
    overflow: hidden;
  }

  .hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
      radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
    pointer-events: none;
  }

  .hero-content {
    max-width: 1200px;
    width: 100%;
    z-index: 1;
    position: relative;
  }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2rem;
  }

  .badge-icon {
    color: #3b82f6;
  }

  h1 {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    font-weight: 700;
    line-height: 1.1;
    color: #fff;
  }

  .gradient-text {
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .subtitle {
    font-size: 1.5rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 3rem;
    line-height: 1.5;
    max-width: 800px;
  }

  .cta-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 4rem;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    border: 2px solid transparent;
    font-size: 1rem;
  }

  .btn-primary {
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    color: white;
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
  }

  .btn-outline {
    background: rgba(255, 255, 255, 0.05);
    color: white;
    border-color: rgba(255, 255, 255, 0.2);
  }

  .btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
  }

  .hero-stats {
    display: flex;
    gap: 4rem;
    margin-bottom: 2rem;
  }

  .stat {
    text-align: center;
  }

  .stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
  }

  .stat-label {
    display: block;
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 0.25rem;
  }

  .hero-visual {
    position: absolute;
    top: 50%;
    right: 10%;
    transform: translateY(-50%);
    width: 300px;
    height: 300px;
  }

  .floating-cards {
    position: relative;
    width: 100%;
    height: 100%;
  }

  .card {
    position: absolute;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 1rem;
    backdrop-filter: blur(10px);
    animation: float 6s ease-in-out infinite;
  }

  .card-1 {
    top: 0;
    left: 0;
    animation-delay: 0s;
  }

  .card-2 {
    top: 50%;
    right: 0;
    animation-delay: 2s;
  }

  .card-3 {
    bottom: 0;
    left: 50%;
    animation-delay: 4s;
  }

  @keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
  }

  .offline-notice {
    background: rgba(251, 191, 36, 0.1);
    color: #fbbf24;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    border: 1px solid rgba(251, 191, 36, 0.2);
  }

  .offline-icon {
    font-size: 1.2rem;
  }

  /* Features Section */
  .features {
    padding: 8rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
  }

  .features h2 {
    text-align: center;
    font-size: 3rem;
    margin-bottom: 4rem;
    color: #fff;
  }

  .features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
  }

  .feature-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    padding: 2.5rem;
    text-align: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
  }

  .feature-card:hover {
    background: rgba(255, 255, 255, 0.05);
    transform: translateY(-5px);
    border-color: rgba(59, 130, 246, 0.3);
  }

  .feature-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
  }

  .feature-card h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #fff;
  }

  .feature-card p {
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
    line-height: 1.6;
  }

  /* Use Cases Section */
  .use-cases {
    padding: 8rem 2rem;
    background: rgba(255, 255, 255, 0.02);
  }

  .section-header {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 4rem;
  }

  .section-header h2 {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #fff;
  }

  .section-header p {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.7);
  }

  .use-cases-grid {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
  }

  .use-case-card {
    position: relative;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    padding: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .use-case-card:hover {
    transform: translateY(-5px);
    border-color: rgba(255, 255, 255, 0.2);
  }

  .use-case-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    border-radius: 1rem 1rem 0 0;
  }

  .from-blue-600 { background: linear-gradient(90deg, #2563eb, #0891b2); }
  .from-purple-600 { background: linear-gradient(90deg, #9333ea, #ec4899); }
  .from-green-600 { background: linear-gradient(90deg, #059669, #10b981); }
  .from-orange-600 { background: linear-gradient(90deg, #ea580c, #ef4444); }

  .use-case-content h3 {
    font-size: 1.5rem;
    margin-bottom: 0.75rem;
    color: #fff;
  }

  .use-case-content p {
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
  }

  /* API Section */
  .api-section {
    padding: 8rem 2rem;
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
  }

  .api-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
  }

  .api-text h2 {
    font-size: 3rem;
    margin-bottom: 1.5rem;
    color: #fff;
  }

  .api-text p {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 2rem;
  }

  .code-snippet {
    background: rgba(0, 0, 0, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    overflow-x: auto;
  }

  .code-snippet code {
    font-family: 'Monaco', 'Menlo', monospace;
    font-size: 0.875rem;
    color: #e5e7eb;
    white-space: pre;
  }

  .api-buttons {
    display: flex;
    gap: 1rem;
  }

  .api-visual {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .api-flow {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .flow-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
  }

  .step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.25rem;
  }

  .step-text {
    font-size: 0.875rem;
    color: rgba(255, 255, 255, 0.8);
  }

  .flow-arrow {
    font-size: 2rem;
    color: rgba(255, 255, 255, 0.4);
  }

  /* Footer */
  .footer {
    background: rgba(0, 0, 0, 0.5);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding: 4rem 2rem 2rem;
    margin-top: auto;
  }

  .footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 4rem;
    margin-bottom: 3rem;
  }

  .footer-brand p {
    color: rgba(255, 255, 255, 0.7);
    margin-top: 0.5rem;
  }

  .footer-links {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
  }

  .link-group h4 {
    color: #fff;
    margin-bottom: 1rem;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .link-group a {
    display: block;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    margin-bottom: 0.5rem;
    transition: color 0.2s ease;
  }

  .link-group a:hover {
    color: #fff;
  }

  .footer-bottom {
    max-width: 1200px;
    margin: 0 auto;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.875rem;
  }

  /* Responsive */
  @media (max-width: 768px) {
    h1 {
      font-size: 2.5rem;
    }
    
    .subtitle {
      font-size: 1.125rem;
    }
    
    .cta-buttons {
      flex-direction: column;
    }
    
    .btn {
      width: 100%;
      justify-content: center;
    }
    
    .hero-stats {
      gap: 2rem;
    }
    
    .hero-visual {
      display: none;
    }
    
    .nav-links {
      display: none;
    }
    
    .api-content {
      grid-template-columns: 1fr;
      gap: 2rem;
    }
    
    .footer-content {
      grid-template-columns: 1fr;
      gap: 2rem;
    }
    
    .footer-links {
      grid-template-columns: 1fr;
    }
  }
</style>
