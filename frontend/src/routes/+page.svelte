<script lang="ts">
  import { onMount } from 'svelte';
  import { fade, fly } from 'svelte/transition';
  
  let isDarkMode = true;
  let isOnline = true;
  let scrolled = false;
  
  let features = [
    {
      icon: '🧠',
      title: 'AI-Powered Analysis',
      description: 'Advanced natural language processing understands context and provides accurate answers based on your data.'
    },
    {
      icon: '📊',
      title: 'Multiple Data Sources',
      description: 'Connect APIs, upload files, or integrate databases. Support for XLS, CSV, JSON, PDF, and more formats.'
    },
    {
      icon: '📸',
      title: 'OCR & Barcode Recognition',
      description: 'Extract text from images and scan barcodes/QR codes instantly. Perfect for processing documents and product information.'
    },
    {
      icon: '⚡',
      title: 'Real-time Responses',
      description: 'Get instant answers with our optimized infrastructure. Average response time under 100ms.'
    },
    {
      icon: '🔌',
      title: 'Easy API Integration',
      description: 'Simple RESTful API with comprehensive documentation. Integrate in minutes with any platform.'
    },
    {
      icon: '🔒',
      title: 'Enterprise Security',
      description: 'Bank-level encryption and compliance. Your data is always protected and never shared.'
    }
  ];
  
  let useCases = [
    {
      title: 'Healthcare & Pharma',
      description: 'Medical data analysis, drug information, patient records',
      gradient: 'from-blue-600'
    },
    {
      title: 'Document Processing',
      description: 'OCR for invoices, contracts, and reports. Barcode scanning for inventory',
      gradient: 'from-purple-600'
    },
    {
      title: 'Business Intelligence',
      description: 'Sales analytics, customer insights, market trends',
      gradient: 'from-green-600'
    },
    {
      title: 'R&D Innovation',
      description: 'Research data analysis, patent searches, scientific literature',
      gradient: 'from-orange-600'
    }
  ];
  
  onMount(() => {
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
      isDarkMode = false;
      document.body.classList.add('light');
    } else {
      isDarkMode = true;
      document.body.classList.remove('light');
    }
    
    // Update online status
    const updateOnlineStatus = () => {
      isOnline = navigator.onLine;
    };
    
    updateOnlineStatus();
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
    
    // Handle scroll
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
  
  function toggleTheme() {
    isDarkMode = !isDarkMode;
    if (isDarkMode) {
      document.body.classList.remove('light');
      localStorage.setItem('theme', 'dark');
    } else {
      document.body.classList.add('light');
      localStorage.setItem('theme', 'light');
    }
  }
  
  function scrollToSection(sectionId: string) {
    const element = document.getElementById(sectionId);
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
  }
</script>

<main class="main-content">
  <!-- Navigation Header -->
  <header class:scrolled class="header">
    <nav class="nav-container">
      <div class="nav-brand">
        <span class="brand-text">MyBot</span>
      </div>
      <div class="nav-links">
        <a href="#features" class="nav-link" on:click={() => scrollToSection('features')}>Features</a>
        <a href="#use-cases" class="nav-link" on:click={() => scrollToSection('use-cases')}>Use Cases</a>
        <a href="#api" class="nav-link" on:click={() => scrollToSection('api')}>API</a>
        <button class="theme-toggle" on:click={toggleTheme} title="Toggle theme">
          {#if isDarkMode}
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="5"/>
              <line x1="12" y1="1" x2="12" y2="3"/>
              <line x1="12" y1="21" x2="12" y2="23"/>
              <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
              <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
              <line x1="1" y1="12" x2="3" y2="12"/>
              <line x1="21" y1="12" x2="23" y2="12"/>
              <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
              <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
            </svg>
          {:else}
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
          {/if}
        </button>
        <a href="/chat" class="nav-link btn-try">Try Now</a>
      </div>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <div class="hero-left">
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
          <button class="btn btn-outline" on:click={() => {
            // Open Google Drive folder in new tab
            window.open('https://drive.google.com/drive/folders/1LHRUV9j5IoFu2GGuWGkOd6aFmoQ1FLW3?usp=share_link', '_blank');
          }}>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="17,8 12,3 7,8"/>
              <line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            <span>Download Sample Files</span>
          </button>
          <a href="#api" class="btn btn-secondary">
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
  <section class="use-cases" id="use-cases">
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
  <section class="api-section" id="api">
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
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Inter', 'SF Pro Display', -apple-system, sans-serif;
    background: #0a0a0a;
    color: #ffffff;
    overflow-x: hidden;
    font-weight: 400;
    transition: background 0.3s ease, color 0.3s ease;
  }

  :global(body.light) {
    background: #ffffff;
    color: #1a1a1a;
  }

  .main-content {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background: #0a0a0a;
    transition: background 0.3s ease;
  }

  .light .main-content {
    background: #ffffff;
  }

  /* Header */
  .header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: rgba(10, 10, 10, 0.7);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    transition: all 0.3s ease;
  }

  .light .header {
    background: rgba(255, 255, 255, 0.7);
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
  }

  .header.scrolled {
    background: rgba(10, 10, 10, 0.95);
    backdrop-filter: blur(30px);
  }

  .light .header.scrolled {
    background: rgba(255, 255, 255, 0.95);
  }

  .nav-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1.2rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .brand-text {
    font-size: 1.75rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #feca57 75%, #ff6b6b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    background-size: 200% 200%;
    animation: gradient-shift 8s ease infinite;
  }

  @keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
  }

  .nav-links {
    display: flex;
    align-items: center;
    gap: 2.5rem;
  }

  .nav-link {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.95rem;
    font-weight: 500;
    position: relative;
  }

  .light .nav-link {
    color: rgba(0, 0, 0, 0.7);
  }

  .nav-link::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #667eea, #f093fb);
    transition: width 0.3s ease;
  }

  .nav-link:hover {
    color: #ffffff;
  }

  .light .nav-link:hover {
    color: #1a1a1a;
  }

  .nav-link:hover::after {
    width: 100%;
  }

  .theme-toggle {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .light .theme-toggle {
    color: rgba(0, 0, 0, 0.7);
  }

  .theme-toggle:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
  }

  .light .theme-toggle:hover {
    background: rgba(0, 0, 0, 0.1);
    color: #1a1a1a;
  }

  .btn-try {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.6rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
  }

  .btn-try:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
  }

  /* Hero Section */
  .hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 2rem;
    background: 
      radial-gradient(ellipse 150% 100% at 50% 0%, rgba(102, 126, 234, 0.15) 0%, transparent 50%),
      radial-gradient(ellipse 100% 150% at 80% 100%, rgba(240, 147, 251, 0.1) 0%, transparent 50%),
      radial-gradient(ellipse 120% 120% at 20% 50%, rgba(118, 75, 162, 0.1) 0%, transparent 50%),
      linear-gradient(180deg, #0a0a0a 0%, #1a1a2e 100%);
    position: relative;
    overflow: hidden;
    transition: background 0.3s ease;
  }

  .light .hero {
    background: 
      radial-gradient(ellipse 150% 100% at 50% 0%, rgba(102, 126, 234, 0.08) 0%, transparent 50%),
      radial-gradient(ellipse 100% 150% at 80% 100%, rgba(240, 147, 251, 0.05) 0%, transparent 50%),
      radial-gradient(ellipse 120% 120% at 20% 50%, rgba(118, 75, 162, 0.05) 0%, transparent 50%),
      linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
  }

  .hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
      radial-gradient(circle at 20% 30%, rgba(102, 126, 234, 0.2) 0%, transparent 40%),
      radial-gradient(circle at 80% 70%, rgba(240, 147, 251, 0.15) 0%, transparent 40%),
      radial-gradient(circle at 50% 50%, rgba(118, 75, 162, 0.1) 0%, transparent 60%);
    pointer-events: none;
    animation: float-gradient 20s ease-in-out infinite;
  }

  .light .hero::before {
    background-image: 
      radial-gradient(circle at 20% 30%, rgba(102, 126, 234, 0.1) 0%, transparent 40%),
      radial-gradient(circle at 80% 70%, rgba(240, 147, 251, 0.08) 0%, transparent 40%),
      radial-gradient(circle at 50% 50%, rgba(118, 75, 162, 0.05) 0%, transparent 60%);
  }

  @keyframes float-gradient {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(-20px, -20px) rotate(1deg); }
    66% { transform: translate(20px, -10px) rotate(-1deg); }
  }

  .hero-content {
    max-width: 1400px;
    width: 100%;
    z-index: 1;
    position: relative;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
  }

  .hero-left {
    max-width: 700px;
  }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 0.75rem 1.5rem;
    border-radius: 100px;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2.5rem;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
  }

  .light .hero-badge {
    background: rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.1);
    color: rgba(0, 0, 0, 0.9);
  }

  .hero-badge:hover {
    background: rgba(255, 255, 255, 0.08);
    transform: translateY(-2px);
  }

  .light .hero-badge:hover {
    background: rgba(0, 0, 0, 0.08);
  }

  .badge-icon {
    background: linear-gradient(135deg, #667eea, #f093fb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  h1 {
    font-size: 4.5rem;
    margin-bottom: 1.5rem;
    font-weight: 800;
    line-height: 1.1;
    color: #ffffff;
    letter-spacing: -0.02em;
  }

  .light h1 {
    color: #1a1a1a;
  }

  .gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #feca57 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    background-size: 200% 200%;
    animation: gradient-shift 8s ease infinite;
  }

  .subtitle {
    font-size: 1.5rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 3rem;
    line-height: 1.6;
    max-width: 600px;
    font-weight: 400;
  }

  .light .subtitle {
    color: rgba(0, 0, 0, 0.7);
  }

  .cta-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 4rem;
    flex-wrap: wrap;
    justify-content: center;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
    font-size: 1rem;
    position: relative;
    overflow: hidden;
  }

  .btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
  }

  .btn:hover::before {
    left: 100%;
  }

  .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
  }

  .btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
  }

  .btn-outline {
    background: rgba(255, 255, 255, 0.05);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
  }

  .light .btn-outline {
    background: rgba(0, 0, 0, 0.05);
    color: #1a1a1a;
    border: 1px solid rgba(0, 0, 0, 0.1);
  }

  .btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-3px);
    border-color: rgba(255, 255, 255, 0.2);
  }

  .light .btn-outline:hover {
    background: rgba(0, 0, 0, 0.1);
    border-color: rgba(0, 0, 0, 0.2);
  }

  .btn-secondary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
  }

  .btn-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
  }

  .hero-stats {
    display: flex;
    gap: 3rem;
  }

  .stat {
    text-align: center;
  }

  .stat-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, #667eea, #f093fb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .stat-label {
    display: block;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 0.5rem;
    font-weight: 500;
  }

  .light .stat-label {
    color: rgba(0, 0, 0, 0.6);
  }

  .hero-visual {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .floating-cards {
    position: relative;
    width: 400px;
    height: 400px;
  }

  .card {
    position: absolute;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1.5rem;
    border-radius: 20px;
    backdrop-filter: blur(20px);
    animation: float 8s ease-in-out infinite;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.9);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
  }

  .light .card {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.02) 100%);
    border: 1px solid rgba(0, 0, 0, 0.1);
    color: rgba(0, 0, 0, 0.9);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  }

  .card-1 {
    top: 0;
    left: 0;
    animation-delay: 0s;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.1) 100%);
  }

  .light .card-1 {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.05) 100%);
  }

  .card-2 {
    top: 50%;
    right: 0;
    animation-delay: 2.5s;
    background: linear-gradient(135deg, rgba(240, 147, 251, 0.2) 0%, rgba(254, 202, 87, 0.1) 100%);
  }

  .light .card-2 {
    background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(254, 202, 87, 0.05) 100%);
  }

  .card-3 {
    bottom: 0;
    left: 50%;
    animation-delay: 5s;
    background: linear-gradient(135deg, rgba(255, 107, 107, 0.2) 0%, rgba(102, 126, 234, 0.1) 100%);
  }

  .light .card-3 {
    background: linear-gradient(135deg, rgba(255, 107, 107, 0.1) 0%, rgba(102, 126, 234, 0.05) 100%);
  }

  @keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    25% { transform: translateY(-20px) rotate(1deg); }
    75% { transform: translateY(-10px) rotate(-1deg); }
  }

  .offline-notice {
    background: linear-gradient(135deg, rgba(254, 202, 87, 0.1) 0%, rgba(255, 107, 107, 0.1) 100%);
    color: #feca57;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.9rem;
    border: 1px solid rgba(254, 202, 87, 0.2);
    backdrop-filter: blur(10px);
  }

  .offline-icon {
    font-size: 1.2rem;
  }

  /* Features Section */
  .features {
    padding: 10rem 2rem;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
  }

  .features h2 {
    text-align: center;
    font-size: 3.5rem;
    margin-bottom: 4rem;
    color: #ffffff;
    font-weight: 800;
    letter-spacing: -0.02em;
  }

  .light .features h2 {
    color: #1a1a1a;
  }

  .features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
  }

  .feature-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    padding: 3rem;
    text-align: center;
    transition: all 0.4s ease;
    backdrop-filter: blur(20px);
    position: relative;
    overflow: hidden;
  }

  .light .feature-card {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.02) 0%, rgba(0, 0, 0, 0.01) 100%);
    border: 1px solid rgba(0, 0, 0, 0.06);
  }

  .feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .feature-card:hover {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.04) 100%);
    transform: translateY(-8px);
    border-color: rgba(102, 126, 234, 0.3);
    box-shadow: 0 30px 60px rgba(102, 126, 234, 0.15);
  }

  .light .feature-card:hover {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.04) 0%, rgba(0, 0, 0, 0.02) 100%);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
  }

  .feature-card:hover::before {
    opacity: 1;
  }

  .feature-icon {
    font-size: 3.5rem;
    margin-bottom: 2rem;
    display: block;
  }

  .feature-card h3 {
    font-size: 1.75rem;
    margin-bottom: 1.25rem;
    color: #ffffff;
    font-weight: 700;
  }

  .light .feature-card h3 {
    color: #1a1a1a;
  }

  .feature-card p {
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
    line-height: 1.7;
    font-size: 1.05rem;
  }

  .light .feature-card p {
    color: rgba(0, 0, 0, 0.7);
  }

  /* Use Cases Section */
  .use-cases {
    padding: 10rem 2rem;
    background: linear-gradient(180deg, #0a0a0a 0%, #1a1a2e 50%, #0a0a0a 100%);
    transition: background 0.3s ease;
  }

  .light .use-cases {
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%);
  }

  .section-header {
    text-align: center;
    max-width: 900px;
    margin: 0 auto 5rem;
  }

  .section-header h2 {
    font-size: 3.5rem;
    margin-bottom: 1.5rem;
    color: #ffffff;
    font-weight: 800;
    letter-spacing: -0.02em;
  }

  .light .section-header h2 {
    color: #1a1a1a;
  }

  .section-header p {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.6;
  }

  .light .section-header p {
    color: rgba(0, 0, 0, 0.7);
  }

  .use-cases-grid {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
  }

  .use-case-card {
    position: relative;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    padding: 2.5rem;
    overflow: hidden;
    transition: all 0.4s ease;
    backdrop-filter: blur(20px);
  }

  .light .use-case-card {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.02) 0%, rgba(0, 0, 0, 0.01) 100%);
    border: 1px solid rgba(0, 0, 0, 0.06);
  }

  .use-case-card:hover {
    transform: translateY(-8px);
    border-color: rgba(255, 255, 255, 0.15);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
  }

  .light .use-case-card:hover {
    border-color: rgba(0, 0, 0, 0.1);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
  }

  .use-case-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    border-radius: 24px 24px 0 0;
  }

  .from-blue-600 { background: linear-gradient(90deg, #667eea, #764ba2); }
  .from-purple-600 { background: linear-gradient(90deg, #764ba2, #f093fb); }
  .from-green-600 { background: linear-gradient(90deg, #00b4db, #0083b0); }
  .from-orange-600 { background: linear-gradient(90deg, #f093fb, #f5576c); }

  .use-case-content h3 {
    font-size: 1.75rem;
    margin-bottom: 1rem;
    color: #ffffff;
    font-weight: 700;
  }

  .light .use-case-content h3 {
    color: #1a1a1a;
  }

  .use-case-content p {
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
    line-height: 1.6;
  }

  .light .use-case-content p {
    color: rgba(0, 0, 0, 0.7);
  }

  /* API Section */
  .api-section {
    padding: 10rem 2rem;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
  }

  .light .api-section {
    background: transparent;
  }

  .api-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5rem;
    align-items: center;
  }

  .api-text h2 {
    font-size: 3.5rem;
    margin-bottom: 2rem;
    color: #ffffff;
    font-weight: 800;
    letter-spacing: -0.02em;
  }

  .light .api-text h2 {
    color: #1a1a1a;
  }

  .api-text p {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 2.5rem;
    line-height: 1.6;
  }

  .light .api-text p {
    color: rgba(0, 0, 0, 0.7);
  }

  .code-snippet {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.5) 0%, rgba(26, 26, 46, 0.5) 100%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2.5rem;
    overflow-x: auto;
    backdrop-filter: blur(20px);
  }

  .light .code-snippet {
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.02) 100%);
    border: 1px solid rgba(0, 0, 0, 0.1);
  }

  .code-snippet code {
    font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', monospace;
    font-size: 0.9rem;
    color: #e5e7eb;
    white-space: pre;
    line-height: 1.6;
  }

  .light .code-snippet code {
    color: #1a1a1a;
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
    gap: 1.5rem;
  }

  .flow-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
  }

  .step-number {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.5rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    color: white;
  }

  .step-text {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
  }

  .light .step-text {
    color: rgba(0, 0, 0, 0.8);
  }

  .flow-arrow {
    font-size: 2.5rem;
    color: rgba(255, 255, 255, 0.4);
    font-weight: 300;
  }

  .light .flow-arrow {
    color: rgba(0, 0, 0, 0.4);
  }

  /* Footer */
  .footer {
    background: linear-gradient(180deg, #0a0a0a 0%, #1a1a2e 100%);
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    padding: 5rem 2rem 2rem;
    margin-top: auto;
  }

  .light .footer {
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    border-top: 1px solid rgba(0, 0, 0, 0.08);
  }

  .footer-content {
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 4rem;
    margin-bottom: 3rem;
  }

  .footer-brand p {
    color: rgba(255, 255, 255, 0.7);
    margin-top: 0.75rem;
    font-size: 1.05rem;
  }

  .light .footer-brand p {
    color: rgba(0, 0, 0, 0.7);
  }

  .footer-links {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
  }

  .link-group h4 {
    color: #ffffff;
    margin-bottom: 1.25rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
  }

  .light .link-group h4 {
    color: #1a1a1a;
  }

  .link-group a {
    display: block;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
    font-size: 0.95rem;
  }

  .light .link-group a {
    color: rgba(0, 0, 0, 0.7);
  }

  .link-group a:hover {
    color: #ffffff;
    transform: translateX(4px);
  }

  .light .link-group a:hover {
    color: #1a1a1a;
  }

  .footer-bottom {
    max-width: 1400px;
    margin: 0 auto;
    padding-top: 2.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    text-align: center;
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.9rem;
  }

  .light .footer-bottom {
    border-top: 1px solid rgba(0, 0, 0, 0.08);
    color: rgba(0, 0, 0, 0.5);
  }

  /* Responsive */
  @media (max-width: 1024px) {
    .hero-content {
      grid-template-columns: 1fr;
      gap: 3rem;
      text-align: center;
    }
    
    .hero-visual {
      order: -1;
    }
    
    .api-content {
      grid-template-columns: 1fr;
      gap: 3rem;
      text-align: center;
    }
    
    .nav-container {
      padding: 1rem 1.5rem;
    }
    
    .nav-links {
      gap: 1.5rem;
    }
  }

  @media (max-width: 768px) {
    h1 {
      font-size: 2.5rem;
    }
    
    .subtitle {
      font-size: 1.125rem;
    }
    
    .cta-buttons {
      flex-direction: column;
      align-items: center;
      width: 100%;
      max-width: 300px;
      margin: 0 auto 4rem;
    }
    
    .btn {
      width: 100%;
      justify-content: center;
    }
    
    .hero-stats {
      gap: 2rem;
      justify-content: center;
    }
    
    .hero-visual {
      display: none;
    }
    
    .nav-links {
      gap: 1rem;
    }
    
    .nav-link:not(.btn-try) {
      display: none;
    }
    
    .features {
      padding: 6rem 1.5rem;
    }
    
    .features h2,
    .section-header h2,
    .api-text h2 {
      font-size: 2.5rem;
    }
    
    .features-grid {
      grid-template-columns: 1fr;
      gap: 1.5rem;
    }
    
    .feature-card {
      padding: 2rem;
    }
    
    .use-cases {
      padding: 6rem 1.5rem;
    }
    
    .use-cases-grid {
      grid-template-columns: 1fr;
      gap: 1.5rem;
    }
    
    .use-case-card {
      padding: 2rem;
    }
    
    .api-section {
      padding: 6rem 1.5rem;
    }
    
    .code-snippet {
      padding: 1.5rem;
    }
    
    .api-flow {
      flex-direction: column;
      gap: 2rem;
    }
    
    .flow-arrow {
      transform: rotate(90deg);
    }
    
    .footer {
      padding: 3rem 1.5rem 1.5rem;
    }
    
    .footer-content {
      grid-template-columns: 1fr;
      gap: 2rem;
    }
    
    .footer-links {
      grid-template-columns: 1fr;
      gap: 1.5rem;
    }
  }

  @media (max-width: 480px) {
    .nav-container {
      padding: 1rem;
    }
    
    h1 {
      font-size: 2rem;
    }
    
    .hero-badge {
      font-size: 0.8rem;
      padding: 0.5rem 1rem;
    }
    
    .features,
    .use-cases,
    .api-section {
      padding: 4rem 1rem;
    }
    
    .feature-card,
    .use-case-card {
      padding: 1.5rem;
    }
    
    .code-snippet {
      padding: 1rem;
      font-size: 0.8rem;
    }
    
    .stat-number {
      font-size: 2rem;
    }
    
    .stat-label {
      font-size: 0.8rem;
    }
  }
</style>
