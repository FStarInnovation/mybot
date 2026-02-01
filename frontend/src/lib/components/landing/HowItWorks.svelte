<script lang="ts">
  const steps = [
    {
      number: '01',
      title: 'Make knowledge base',
      description: 'Add your existing documents or web pages to create your knowledge base. Import documentation from multiple private and public sources in minutes.',
      icon: '📚'
    },
    {
      number: '02',
      title: 'Integrate',
      description: 'Embed the Ask AI widget on your website, Discord server, or Slack workspace. Customize the bot\'s UI and behaviour.',
      icon: '🔌'
    },
    {
      number: '03',
      title: 'Analyse performance',
      description: 'Monitor all messages and conversations. Track performance scores, data gaps, and more.',
      icon: '📊'
    }
  ];
</script>

<section id="how-it-works" class="how-it-works">
  <div class="container">
    <div class="section-header">
      <h2>Works in three simple steps</h2>
      <p>Transform your documentation into an AI-ready knowledge base for your community in three simple steps.</p>
    </div>
    
    <div class="steps">
      {#each steps as step, i}
        <div class="step">
          <div class="step-number">{step.number}</div>
          <div class="step-icon">{step.icon}</div>
          <h3>{step.title}</h3>
          <p>{step.description}</p>
        </div>
        {#if i < steps.length - 1}
          <div class="connector">
            <svg viewBox="0 0 100 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M0 10H95M85 2L95 10L85 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        {/if}
      {/each}
    </div>
  </div>
</section>

<style>
  .how-it-works {
    padding: 6rem 2rem;
    background: var(--bg-secondary);
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
  
  .steps {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
  }
  
  .step {
    flex: 1;
    min-width: 280px;
    max-width: 350px;
    text-align: center;
    padding: 2rem;
    background: var(--bg-primary);
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .step:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
  }
  
  .step-number {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--accent-primary), #8b5cf6);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
  }
  
  .step-icon {
    font-size: 3rem;
    margin: 1rem 0 1.5rem;
  }
  
  .step h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--text-primary);
  }
  
  .step p {
    font-size: 1rem;
    color: var(--text-secondary);
    line-height: 1.6;
  }
  
  .connector {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    color: var(--accent-primary);
    opacity: 0.5;
  }
  
  @media (max-width: 900px) {
    .steps {
      flex-direction: column;
      align-items: center;
    }
    
    .connector {
      transform: rotate(90deg);
      margin: 1rem 0;
    }
  }
</style>
