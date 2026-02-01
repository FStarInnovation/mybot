<script lang="ts">
  import { onMount } from 'svelte';
  
  let visible = false;
  
  onMount(() => {
    visible = true;
  });
  
  const benefits = [
    'Works on your website, Discord, and Slack',
    'Automatic support ticket creation',
    'Real-time analytics & insights'
  ];
</script>

<section class="hero" class:visible>
  <div class="container">
    <div class="content">
      <div class="badge">
        <span class="badge-dot"></span>
        <span>Powered by AI</span>
      </div>
      
      <h1>
        Turn your docs into an
        <span class="gradient-text">AI assistant</span>
        that answers instantly
      </h1>
      
      <p class="subtitle">
        Add an AI chatbot to your documentation in minutes. Users get instant answers 
        from your docs, without searching through hundreds of pages.
      </p>
      
      <div class="cta-group">
        <a href="/chat" class="btn-primary">
          <span>Start Free Trial</span>
          <svg viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/>
          </svg>
        </a>
        <a href="https://cal.com" target="_blank" class="btn-secondary">
          <svg viewBox="0 0 20 20" fill="currentColor">
            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
          </svg>
          <span>Book a Demo</span>
        </a>
      </div>
      
      <ul class="benefits">
        {#each benefits as benefit}
          <li>
            <svg class="check" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            {benefit}
          </li>
        {/each}
      </ul>
    </div>
    
    <div class="visual">
      <div class="chat-window">
        <div class="chat-header">
          <div class="chat-avatar">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
            </svg>
          </div>
          <div class="chat-title">
            <span class="chat-name">MyBot Assistant</span>
            <span class="chat-status">
              <span class="status-dot"></span>
              Online
            </span>
          </div>
        </div>
        
        <div class="chat-body">
          <div class="message user">
            <div class="bubble">How do I integrate the API?</div>
          </div>
          
          <div class="message bot">
            <div class="bubble">
              <p>Here's how to integrate:</p>
              <ol>
                <li>Get API key from dashboard</li>
                <li>Install: npm install @mybot/sdk</li>
                <li>Initialize with your key</li>
              </ol>
              <a href="#" class="source">View docs →</a>
            </div>
          </div>
          
          <div class="message user">
            <div class="bubble">Thanks! Works perfectly.</div>
          </div>
        </div>
        
        <div class="chat-input">
          <div class="input-placeholder">Type your message...</div>
          <button class="send-btn">
            <svg viewBox="0 0 24 24" fill="currentColor">
              <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
          </button>
        </div>
      </div>
      
      <div class="floating-card card-stats">
        <div class="card-icon">
          <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
          </svg>
        </div>
        <div class="card-content">
          <span class="card-value">98.5%</span>
          <span class="card-label">Accuracy</span>
        </div>
      </div>
      
      <div class="floating-card card-users">
        <div class="card-avatars">
          <div class="avatar">JD</div>
          <div class="avatar">MK</div>
          <div class="avatar">+2k</div>
        </div>
        <span class="card-label">Active users</span>
      </div>
    </div>
  </div>
</section>

<style>
  .hero {
    padding: 8rem 2rem 6rem;
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease;
  }
  
  .hero.visible {
    opacity: 1;
    transform: translateY(0);
  }
  
  .container {
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
  }
  
  .badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(99, 102, 241, 0.1);
    border: 1px solid rgba(99, 102, 241, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #818cf8;
    margin-bottom: 1.5rem;
  }
  
  .badge-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s infinite;
  }
  
  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
  }
  
  h1 {
    font-size: 4rem;
    font-weight: 800;
    line-height: 1.1;
    margin: 0 0 1.5rem;
    color: #fff;
    letter-spacing: -0.02em;
  }
  
  .gradient-text {
    background: linear-gradient(135deg, #818cf8 0%, #c084fc 50%, #f472b6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .subtitle {
    font-size: 1.25rem;
    color: #94a3b8;
    margin-bottom: 2rem;
    line-height: 1.7;
    max-width: 500px;
  }
  
  .cta-group {
    display: flex;
    gap: 1rem;
    margin-bottom: 2.5rem;
  }
  
  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    padding: 1rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
  }
  
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5);
  }
  
  .btn-primary svg {
    width: 20px;
    height: 20px;
    transition: transform 0.2s ease;
  }
  
  .btn-primary:hover svg {
    transform: translateX(4px);
  }
  
  .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.05);
    color: #e2e8f0;
    padding: 1rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.2s ease;
  }
  
  .btn-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
  }
  
  .btn-secondary svg {
    width: 18px;
    height: 18px;
  }
  
  .benefits {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
  }
  
  .benefits li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #94a3b8;
    font-size: 0.9375rem;
  }
  
  .check {
    width: 20px;
    height: 20px;
    color: #10b981;
    flex-shrink: 0;
  }
  
  .visual {
    position: relative;
    display: flex;
    justify-content: center;
  }
  
  .chat-window {
    background: rgba(30, 41, 59, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    width: 100%;
    max-width: 380px;
    overflow: hidden;
    backdrop-filter: blur(10px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
  }
  
  .chat-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    background: rgba(15, 23, 42, 0.5);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  }
  
  .chat-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
  }
  
  .chat-avatar svg {
    width: 22px;
    height: 22px;
  }
  
  .chat-title {
    display: flex;
    flex-direction: column;
  }
  
  .chat-name {
    font-weight: 600;
    font-size: 0.9375rem;
    color: #fff;
  }
  
  .chat-status {
    font-size: 0.75rem;
    color: #10b981;
    display: flex;
    align-items: center;
    gap: 0.375rem;
  }
  
  .status-dot {
    width: 6px;
    height: 6px;
    background: #10b981;
    border-radius: 50%;
  }
  
  .chat-body {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    min-height: 240px;
  }
  
  .message {
    display: flex;
  }
  
  .message.user {
    justify-content: flex-end;
  }
  
  .bubble {
    max-width: 85%;
    padding: 0.875rem 1rem;
    border-radius: 16px;
    font-size: 0.875rem;
    line-height: 1.5;
  }
  
  .message.user .bubble {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    border-bottom-right-radius: 4px;
  }
  
  .message.bot .bubble {
    background: rgba(255, 255, 255, 0.05);
    color: #e2e8f0;
    border-bottom-left-radius: 4px;
  }
  
  .bubble ol {
    margin: 0.5rem 0;
    padding-left: 1.25rem;
  }
  
  .bubble li {
    margin-bottom: 0.25rem;
  }
  
  .source {
    color: #818cf8;
    text-decoration: none;
    font-weight: 500;
  }
  
  .chat-input {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    background: rgba(15, 23, 42, 0.3);
  }
  
  .input-placeholder {
    flex: 1;
    color: #64748b;
    font-size: 0.875rem;
  }
  
  .send-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }
  
  .send-btn svg {
    width: 18px;
    height: 18px;
  }
  
  .floating-card {
    position: absolute;
    background: rgba(30, 41, 59, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 1rem;
    backdrop-filter: blur(10px);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
  }
  
  .card-stats {
    top: 10%;
    right: -20px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }
  
  .card-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #10b981, #34d399);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
  }
  
  .card-icon svg {
    width: 20px;
    height: 20px;
  }
  
  .card-content {
    display: flex;
    flex-direction: column;
  }
  
  .card-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #fff;
  }
  
  .card-label {
    font-size: 0.75rem;
    color: #94a3b8;
  }
  
  .card-users {
    bottom: 15%;
    left: -30px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
  }
  
  .card-avatars {
    display: flex;
  }
  
  .avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.625rem;
    font-weight: 600;
    color: white;
    border: 2px solid #1e293b;
    margin-left: -8px;
  }
  
  .avatar:first-child {
    margin-left: 0;
  }
  
  @media (max-width: 968px) {
    .container {
      grid-template-columns: 1fr;
      gap: 3rem;
      text-align: center;
    }
    
    h1 {
      font-size: 2.75rem;
    }
    
    .subtitle {
      margin-left: auto;
      margin-right: auto;
    }
    
    .cta-group {
      justify-content: center;
    }
    
    .benefits {
      align-items: center;
    }
    
    .visual {
      order: -1;
    }
    
    .chat-window {
      max-width: 340px;
    }
    
    .floating-card {
      display: none;
    }
  }
  
  @media (max-width: 480px) {
    .hero {
      padding: 6rem 1.5rem 4rem;
    }
    
    h1 {
      font-size: 2.25rem;
    }
    
    .cta-group {
      flex-direction: column;
    }
    
    .btn-primary,
    .btn-secondary {
      justify-content: center;
    }
  }
</style>
