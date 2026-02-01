<script lang="ts">
  const features = [
    {
      icon: '🌐',
      title: 'Embed on your website',
      description: 'Embed the Ask AI widget on your website in minutes. Copy the embed code and paste it into your site. Instantly deploy an AI assistant.'
    },
    {
      icon: '🎨',
      title: 'Customise',
      description: "Customize the widget's appearance and response tone to match your brand. Add your colors, logo, and customize AI behavior."
    },
    {
      icon: '👥',
      title: 'Human support',
      description: "The bot recognizes when it doesn't have an answer. Users can seamlessly escalate to human support via tickets."
    },
    {
      icon: '📈',
      title: 'Useful analytics',
      description: 'Track daily messages, score distribution, data gaps, popular knowledge pages, categories, and sentiment analysis.'
    },
    {
      icon: '💬',
      title: 'View questions',
      description: 'Review user questions, identify gaps in your documentation, and improve accordingly. Group questions into categories.'
    },
    {
      icon: '👨‍💻',
      title: 'Teams',
      description: 'Collaborate with multiple teams on your documentation. Enable team collaboration to improve docs and support users.'
    },
    {
      icon: '🤖',
      title: 'Discord & Slack',
      description: 'Add the bot to your Discord server or Slack workspace. Anyone can resolve queries by tagging the bot.'
    },
    {
      icon: '📚',
      title: 'Sources',
      description: 'The bot includes source citations with every answer, helping users dive deeper into your documentation.'
    }
  ];
</script>

<section id="features" class="features">
  <div class="container">
    <div class="section-header">
      <h2>Power up your tech documentation with AI</h2>
      <p>Stop making users search through hundreds of pages. Add AI to your documentation and let users find instant answers right where they need them.</p>
    </div>
    
    <div class="features-grid">
      {#each features as feature}
        <div class="feature-card">
          <div class="feature-icon">{feature.icon}</div>
          <h3>{feature.title}</h3>
          <p>{feature.description}</p>
        </div>
      {/each}
    </div>
  </div>
</section>

<style>
  .features {
    padding: 6rem 2rem;
    background: var(--bg-primary);
  }
  
  .container {
    max-width: 1200px;
    margin: 0 auto;
  }
  
  .section-header {
    text-align: center;
    margin-bottom: 4rem;
  }
  
  .section-header h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--text-primary);
  }
  
  .section-header p {
    font-size: 1.125rem;
    color: var(--text-secondary);
    max-width: 600px;
    margin: 0 auto;
  }
  
  .features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
  }
  
  .feature-card {
    padding: 2rem;
    background: var(--bg-secondary);
    border-radius: 16px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid var(--border-color);
  }
  
  .feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  }
  
  .feature-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
  }
  
  .feature-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: var(--text-primary);
  }
  
  .feature-card p {
    font-size: 0.95rem;
    color: var(--text-secondary);
    line-height: 1.6;
  }
</style>
