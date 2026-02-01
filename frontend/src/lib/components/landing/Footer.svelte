<script lang="ts">
  const footerLinks = {
    product: [
      { label: 'Features', href: '#features' },
      { label: 'Pricing', href: '#pricing' },
      { label: 'Documentation', href: '#' },
      { label: 'Changelog', href: '#' }
    ],
    company: [
      { label: 'About', href: '#' },
      { label: 'Blog', href: '#' },
      { label: 'Careers', href: '#' },
      { label: 'Contact', href: '#' }
    ],
    legal: [
      { label: 'Privacy Policy', href: '#' },
      { label: 'Terms of Service', href: '#' },
      { label: 'Cookie Policy', href: '#' }
    ],
    social: [
      { label: 'Twitter', href: '#', icon: '🐦' },
      { label: 'GitHub', href: '#', icon: '🐙' },
      { label: 'Discord', href: '#', icon: '💬' }
    ]
  };
</script>

<footer class="footer">
  <div class="container">
    <div class="cta-section">
      <h2>Power up your tech documentation with AI today!</h2>
      <p>Join thousands of teams already using MyBot to improve their documentation experience.</p>
      <div class="cta-buttons">
        <a href="/chat" class="btn-primary">Start Free Trial</a>
        <a href="https://cal.com" target="_blank" class="btn-secondary">Book a Demo</a>
      </div>
    </div>
    
    <div class="footer-content">
      <div class="brand">
        <a href="/" class="logo">
          <span class="logo-icon">🤖</span>
          <span class="logo-text">MyBot</span>
        </a>
        <p class="tagline">Turn your documentation into an AI assistant that answers questions instantly.</p>
        <div class="social-links">
          {#each footerLinks.social as link}
            <a href={link.href} class="social-link" aria-label={link.label}>
              <span class="social-icon">{link.icon}</span>
            </a>
          {/each}
        </div>
      </div>
      
      <div class="link-groups">
        <div class="link-group">
          <h4>Product</h4>
          <ul>
            {#each footerLinks.product as link}
              <li><a href={link.href}>{link.label}</a></li>
            {/each}
          </ul>
        </div>
        
        <div class="link-group">
          <h4>Company</h4>
          <ul>
            {#each footerLinks.company as link}
              <li><a href={link.href}>{link.label}</a></li>
            {/each}
          </ul>
        </div>
        
        <div class="link-group">
          <h4>Legal</h4>
          <ul>
            {#each footerLinks.legal as link}
              <li><a href={link.href}>{link.label}</a></li>
            {/each}
          </ul>
        </div>
      </div>
    </div>
    
    <div class="footer-bottom">
      <p>&copy; {new Date().getFullYear()} MyBot. All rights reserved.</p>
      <p class="made-with">Made with ❤️ for developers</p>
    </div>
  </div>
</footer>

<style>
  .footer {
    background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
    padding: 6rem 2rem 2rem;
  }
  
  .container {
    max-width: 1200px;
    margin: 0 auto;
  }
  
  .cta-section {
    text-align: center;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, var(--accent-primary), #8b5cf6);
    border-radius: 24px;
    margin-bottom: 4rem;
    color: white;
  }
  
  .cta-section h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: white;
  }
  
  .cta-section p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
  }
  
  .cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
  }
  
  .btn-primary {
    display: inline-block;
    background: white;
    color: var(--accent-primary);
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
  }
  
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  }
  
  .btn-secondary {
    display: inline-block;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
  }
  
  .btn-secondary:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
  }
  
  .footer-content {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 4rem;
    padding-bottom: 3rem;
    border-bottom: 1px solid var(--border-color);
  }
  
  .brand {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
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
  
  .tagline {
    font-size: 0.95rem;
    color: var(--text-secondary);
    line-height: 1.6;
    max-width: 280px;
  }
  
  .social-links {
    display: flex;
    gap: 1rem;
  }
  
  .social-link {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--bg-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid var(--border-color);
  }
  
  .social-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
  
  .social-icon {
    font-size: 1.25rem;
  }
  
  .link-groups {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
  }
  
  .link-group h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1rem;
  }
  
  .link-group ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .link-group li {
    margin-bottom: 0.75rem;
  }
  
  .link-group a {
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.95rem;
    transition: color 0.2s ease;
  }
  
  .link-group a:hover {
    color: var(--accent-primary);
  }
  
  .footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 2rem;
    font-size: 0.875rem;
    color: var(--text-muted);
  }
  
  @media (max-width: 768px) {
    .footer-content {
      grid-template-columns: 1fr;
      gap: 2rem;
    }
    
    .link-groups {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .footer-bottom {
      flex-direction: column;
      gap: 0.5rem;
      text-align: center;
    }
  }
</style>
