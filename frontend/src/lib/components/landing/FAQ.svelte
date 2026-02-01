<script lang="ts">
  let openIndex: number | null = 0;
  
  const faqs = [
    {
      question: 'How do I train with my documentation?',
      answer: 'Simply upload your documents or provide URLs to your documentation pages. Our system will automatically crawl and index your content, making it searchable by the AI. You can add multiple sources including websites, PDFs, and markdown files.'
    },
    {
      question: 'I already use other chatbots, why do I switch?',
      answer: 'MyBot is specifically designed for technical documentation with features like source citations, Discord/Slack integration, data gap analysis, and support ticket creation. Unlike generic chatbots, we understand developer workflows and documentation structures.'
    },
    {
      question: 'Do I need to bring my own OpenAI API key?',
      answer: 'No! We provide everything you need. Your subscription includes message credits that work with our AI models. No need to manage separate API keys or worry about unexpected bills.'
    },
    {
      question: 'Does it support other languages?',
      answer: 'Yes! MyBot supports multiple languages. Your documentation can be in any language, and users can ask questions in their preferred language. The AI will respond in the same language as the question.'
    },
    {
      question: 'Can I try it out first?',
      answer: 'Absolutely! We offer a 7-day free trial with no credit card required. You can test all features and see how MyBot works with your documentation before committing to a paid plan.'
    },
    {
      question: 'How can I integrate the Ask AI widget to my website?',
      answer: 'Integration is simple! Just copy a single line of JavaScript code from your dashboard and paste it into your website\'s HTML. The widget will automatically appear on your site and start answering questions.'
    },
    {
      question: 'How can I integrate it with Slack or Discord?',
      answer: 'We provide native bots for both platforms. For Discord, simply add our bot to your server. For Slack, install our app from the Slack App Directory. Both integrations use the same knowledge base as your web widget.'
    },
    {
      question: 'What kind of analytics does it provide?',
      answer: 'Our analytics dashboard shows you daily message counts, popular questions, unanswered queries (data gaps), user satisfaction scores, source usage, and conversation trends. This helps you identify areas where your documentation needs improvement.'
    }
  ];
  
  function toggle(index: number) {
    openIndex = openIndex === index ? null : index;
  }
</script>

<section id="faq" class="faq">
  <div class="container">
    <div class="section-header">
      <h2>Frequently Asked Questions</h2>
      <p>Everything you need to know about MyBot. Can't find what you're looking for? <a href="mailto:support@mybot.app">Contact us</a>.</p>
    </div>
    
    <div class="faq-list">
      {#each faqs as faq, index}
        <div class="faq-item" class:open={openIndex === index}>
          <button class="faq-question" on:click={() => toggle(index)}>
            <span>{faq.question}</span>
            <svg class="chevron" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
          </button>
          {#if openIndex === index}
            <div class="faq-answer">
              <p>{faq.answer}</p>
            </div>
          {/if}
        </div>
      {/each}
    </div>
  </div>
</section>

<style>
  .faq {
    padding: 6rem 2rem;
    background: var(--bg-primary);
  }
  
  .container {
    max-width: 800px;
    margin: 0 auto;
  }
  
  .section-header {
    text-align: center;
    margin-bottom: 3rem;
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
  }
  
  .section-header a {
    color: var(--accent-primary);
    text-decoration: none;
  }
  
  .faq-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  
  .faq-item {
    background: var(--bg-secondary);
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--border-color);
    transition: box-shadow 0.3s ease;
  }
  
  .faq-item.open {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  }
  
  .faq-question {
    width: 100%;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: none;
    border: none;
    cursor: pointer;
    text-align: left;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    transition: color 0.2s ease;
  }
  
  .faq-question:hover {
    color: var(--accent-primary);
  }
  
  .chevron {
    width: 20px;
    height: 20px;
    transition: transform 0.3s ease;
    flex-shrink: 0;
    margin-left: 1rem;
  }
  
  .faq-item.open .chevron {
    transform: rotate(180deg);
  }
  
  .faq-answer {
    padding: 0 1.5rem 1.5rem;
    animation: slideDown 0.3s ease;
  }
  
  .faq-answer p {
    margin: 0;
    color: var(--text-secondary);
    line-height: 1.6;
  }
  
  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
