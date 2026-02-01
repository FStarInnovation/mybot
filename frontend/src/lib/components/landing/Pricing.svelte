<script lang="ts">
  const plans = [
    {
      name: 'Launch',
      description: 'Get started with AI chatbot',
      price: 29,
      period: '/month',
      features: [
        '2,000 pages',
        '800 message credits/month',
        '1 collection',
        '1 team member',
        'Website widget',
        'Basic analytics'
      ],
      cta: 'Start Free Trial',
      popular: false
    },
    {
      name: 'Grow',
      description: 'For growing teams and projects',
      price: 69,
      period: '/month',
      features: [
        '5,000 pages',
        '2,000 message credits/month',
        '2 collections',
        '2 team members',
        'Website + Discord bot',
        'Advanced analytics',
        'Priority support'
      ],
      cta: 'Start Free Trial',
      popular: true
    },
    {
      name: 'Accelerate',
      description: 'For teams that need more power',
      price: 229,
      period: '/month',
      features: [
        '14,000 pages',
        '7,000 message credits/month',
        '3 collections',
        '5 team members',
        'All integrations',
        'Custom AI training',
        'Dedicated support',
        'SLA guarantee'
      ],
      cta: 'Start Free Trial',
      popular: false
    }
  ];
</script>

<section id="pricing" class="pricing">
  <div class="container">
    <div class="section-header">
      <h2>Pricing for everyone</h2>
      <p>Choose the plan that best fits your needs. Start with a 7-day free trial and cancel anytime.</p>
    </div>
    
    <div class="pricing-grid">
      {#each plans as plan}
        <div class="pricing-card" class:popular={plan.popular}>
          {#if plan.popular}
            <div class="popular-badge">Most Popular</div>
          {/if}
          <h3>{plan.name}</h3>
          <p class="description">{plan.description}</p>
          <div class="price">
            <span class="currency">$</span>
            <span class="amount">{plan.price}</span>
            <span class="period">{plan.period}</span>
          </div>
          <ul class="features">
            {#each plan.features as feature}
              <li>
                <svg class="check" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                {feature}
              </li>
            {/each}
          </ul>
          <a href="/chat" class="cta-button" class:primary={plan.popular}>
            {plan.cta}
          </a>
        </div>
      {/each}
    </div>
    
    <div class="guarantee">
      <span class="icon">🛡️</span>
      <span>7-day free trial • No credit card required • Cancel anytime</span>
    </div>
  </div>
</section>

<style>
  .pricing {
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
  
  .pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    align-items: start;
  }
  
  .pricing-card {
    position: relative;
    padding: 2.5rem 2rem;
    background: var(--bg-primary);
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid var(--border-color);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .pricing-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
  }
  
  .pricing-card.popular {
    border: 2px solid var(--accent-primary);
    transform: scale(1.05);
  }
  
  .pricing-card.popular:hover {
    transform: scale(1.05) translateY(-5px);
  }
  
  .popular-badge {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, var(--accent-primary), #8b5cf6);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .pricing-card h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
  }
  
  .description {
    font-size: 0.95rem;
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
  }
  
  .price {
    display: flex;
    align-items: baseline;
    gap: 0.25rem;
    margin-bottom: 2rem;
  }
  
  .currency {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
  }
  
  .amount {
    font-size: 3.5rem;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
  }
  
  .period {
    font-size: 1rem;
    color: var(--text-muted);
  }
  
  .features {
    list-style: none;
    padding: 0;
    margin: 0 0 2rem;
  }
  
  .features li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    color: var(--text-secondary);
    font-size: 0.95rem;
  }
  
  .check {
    width: 20px;
    height: 20px;
    color: #10b981;
    flex-shrink: 0;
  }
  
  .cta-button {
    display: block;
    width: 100%;
    padding: 1rem;
    text-align: center;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid var(--border-color);
    color: var(--text-primary);
  }
  
  .cta-button:hover {
    border-color: var(--accent-primary);
    color: var(--accent-primary);
  }
  
  .cta-button.primary {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
  }
  
  .cta-button.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(var(--accent-primary-rgb), 0.4);
  }
  
  .guarantee {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    margin-top: 3rem;
    padding: 1rem 2rem;
    background: var(--bg-primary);
    border-radius: 50px;
    color: var(--text-secondary);
    font-size: 0.95rem;
  }
  
  .guarantee .icon {
    font-size: 1.25rem;
  }
  
  @media (max-width: 768px) {
    .pricing-card.popular {
      transform: none;
    }
    
    .pricing-card.popular:hover {
      transform: translateY(-5px);
    }
  }
</style>
