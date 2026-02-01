<script lang="ts">
  const benefits = [
    'Works on your website, Discord, and Slack',
    'Shows you questions being asked and ways to improve',
    'Automatically creates support tickets when AI can\'t help'
  ];
</script>

<section class="hero">
  <div class="container">
    <div class="content">
      <h1>
        Turn your documentation into an 
        <span class="gradient-text">AI assistant</span> 
        that answers questions instantly
      </h1>
      
      <p class="subtitle">
        Add an AI chatbot to your documentation website. Users can ask questions 
        and get instant answers from your docs, without searching through pages.
      </p>
      
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
      
      <div class="cta-buttons">
        <a href="/chat" class="btn-primary">
          Start Free Trial
          <svg class="arrow" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
          </svg>
        </a>
        <a href="https://cal.com" target="_blank" class="btn-secondary">
          Book a Demo
        </a>
      </div>
    </div>
    
    <div class="visual">
      <div class="chat-preview">
        <div class="chat-header">
          <div class="chat-avatar">🤖</div>
          <div class="chat-info">
            <div class="chat-name">MyBot Assistant</div>
            <div class="chat-status">Online</div>
          </div>
        </div>
        <div class="chat-messages">
          <div class="message user">
            <div class="message-bubble">How do I integrate the API?</div>
          </div>
          <div class="message bot">
            <div class="message-bubble">
              You can integrate the API by following these steps:
              <br><br>
              1. Get your API key from the dashboard<br>
              2. Install our SDK: npm install @mybot/sdk<br>
              3. Initialize with your API key
              <br><br>
              <a href="#" class="source-link">📄 View documentation</a>
            </div>
          </div>
          <div class="message user">
            <div class="message-bubble">Thanks! That worked perfectly.</div>
          </div>
        </div>
        <div class="chat-input">
          <div class="input-placeholder">Type your message...</div>
          <div class="send-btn">➤</div>
        </div>
      </div>
      
      <div class="floating-badge badge-1">
        <span class="badge-icon">⚡</span>
        <span>Instant Answers</span>
      </div>
      <div class="floating-badge badge-2">
        <span class="badge-icon">📊</span>
        <span>Analytics</span>
      </div>
      <div class="floating-badge badge-3">
        <span class="badge-icon">🔌</span>
        <span>Easy Integration</span>
      </div>
    </div>
  </div>
</section>

<style>
  .hero {
    padding: 6rem 2rem 4rem;
    background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 50%, var(--bg-primary) 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
  
  .container {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
  }
  
  h1 {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.1;
    margin: 0 0 1.5rem;
    color: var(--text-primary);
  }
  
  .gradient-text {
    background: linear-gradient(135deg, var(--accent-primary), #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .subtitle {
    font-size: 1.25rem;
    color: var(--text-secondary);
    margin-bottom: 2rem;
    line-height: 1.6;
  }
  
  .benefits {
    list-style: none;
    padding: 0;
    margin: 0 0 2rem;
  }
  
  .benefits li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    color: var(--text-secondary);
    font-size: 1rem;
  }
  
  .check {
    width: 20px;
    height: 20px;
    color: #10b981;
    flex-shrink: 0;
  }
  
  .cta-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
  }
  
  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--accent-primary);
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    text-decoration: none;
    transition: all 0.3s ease;
  }
  
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(var(--accent-primary-rgb), 0.4);
  }
  
  .arrow {
    width: 20px;
    height: 20px;
    transition: transform 0.2s ease;
  }
  
  .btn-primary:hover .arrow {
    transform: translateX(4px);
  }
  
  .btn-secondary {
    display: inline-flex;
    align-items: center;
    background: transparent;
    color: var(--text-primary);
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    text-decoration: none;
    border: 2px solid var(--border-color);
    transition: all 0.3s ease;
  }
  
  .btn-secondary:hover {
    border-color: var(--accent-primary);
    color: var(--accent-primary);
  }
  
  .visual {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .chat-preview {
    background: var(--bg-primary);
    border-radius: 20px;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 400px;
    overflow: hidden;
    border: 1px solid var(--border-color);
  }
  
  .chat-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
  }
  
  .chat-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-primary), #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
  }
  
  .chat-name {
    font-weight: 600;
    color: var(--text-primary);
  }
  
  .chat-status {
    font-size: 0.875rem;
    color: #10b981;
  }
  
  .chat-messages {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    min-height: 280px;
  }
  
  .message {
    display: flex;
  }
  
  .message.user {
    justify-content: flex-end;
  }
  
  .message-bubble {
    max-width: 80%;
    padding: 1rem;
    border-radius: 16px;
    font-size: 0.9rem;
    line-height: 1.5;
  }
  
  .message.user .message-bubble {
    background: var(--accent-primary);
    color: white;
    border-bottom-right-radius: 4px;
  }
  
  .message.bot .message-bubble {
    background: var(--bg-secondary);
    color: var(--text-primary);
    border-bottom-left-radius: 4px;
  }
  
  .source-link {
    color: var(--accent-primary);
    text-decoration: none;
    font-weight: 500;
  }
  
  .chat-input {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border-color);
    background: var(--bg-primary);
  }
  
  .input-placeholder {
    flex: 1;
    color: var(--text-muted);
    font-size: 0.9rem;
  }
  
  .send-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--accent-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }
  
  .floating-badge {
    position: absolute;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: var(--bg-primary);
    border-radius: 50px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    animation: float 3s ease-in-out infinite;
  }
  
  .badge-1 {
    top: 10%;
    right: -20px;
    animation-delay: 0s;
  }
  
  .badge-2 {
    bottom: 30%;
    left: -30px;
    animation-delay: 1s;
  }
  
  .badge-3 {
    bottom: 10%;
    right: 0;
    animation-delay: 2s;
  }
  
  .badge-icon {
    font-size: 1.25rem;
  }
  
  @keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
  }
  
  @media (max-width: 968px) {
    .container {
      grid-template-columns: 1fr;
      text-align: center;
    }
    
    h1 {
      font-size: 2.5rem;
    }
    
    .benefits li {
      justify-content: center;
    }
    
    .cta-buttons {
      justify-content: center;
    }
    
    .visual {
      order: -1;
    }
    
    .chat-preview {
      max-width: 320px;
    }
    
    .floating-badge {
      display: none;
    }
  }
</style>
