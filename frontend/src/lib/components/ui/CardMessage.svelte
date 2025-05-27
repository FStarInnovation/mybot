<script lang="ts">
  import type { AgUICard } from '../../ag-ui/types';

  export let card: AgUICard;
  
  // Обработчик клика по карточке
  function handleCardClick() {
    if (card.url) {
      window.open(card.url, '_blank');
    }
  }
  
  // Обработчик клика по действию
  function handleActionClick(actionId: string) {
    const event = new CustomEvent('action', {
      detail: { actionId, cardId: card.title }
    });
    document.dispatchEvent(event);
  }
</script>

<div class="card-message" class:clickable={!!card.url} on:click={card.url ? handleCardClick : undefined}>
  {#if card.image}
    <div class="card-image">
      <img src={card.image} alt={card.title} loading="lazy" />
    </div>
  {/if}
  <div class="card-content">
    <h3 class="card-title">{card.title}</h3>
    {#if card.description}
      <p class="card-description">{card.description}</p>
    {/if}
    
    {#if card.fields && card.fields.length > 0}
      <div class="card-fields">
        {#each card.fields as field}
          <div class="card-field" class:inline={field.inline}>
            <span class="field-name">{field.name}:</span>
            <span class="field-value">{field.value}</span>
          </div>
        {/each}
      </div>
    {/if}
    
    {#if card.footer}
      <div class="card-footer">{card.footer}</div>
    {/if}
  </div>
  
  {#if card.actions && card.actions.length > 0}
    <div class="card-actions">
      {#each card.actions as action}
        <button 
          class="card-action" 
          class:primary={action.style === 'primary'}
          class:secondary={action.style === 'secondary'}
          class:danger={action.style === 'danger'}
          class:success={action.style === 'success'}
          class:warning={action.style === 'warning'}
          class:info={action.style === 'info'}
          disabled={action.disabled}
          on:click|stopPropagation={() => handleActionClick(action.id)}
        >
          {#if action.icon}
            <span class="action-icon">{action.icon}</span>
          {/if}
          <span class="action-label">{action.label}</span>
        </button>
      {/each}
    </div>
  {/if}
</div>

<style>
  .card-message {
    display: flex;
    flex-direction: column;
    background-color: var(--bg-secondary);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    max-width: 320px;
    margin: 0.5rem 0;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  
  .clickable {
    cursor: pointer;
  }
  
  .clickable:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
  
  .card-image {
    width: 100%;
    height: 160px;
    overflow: hidden;
  }
  
  .card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }
  
  .clickable:hover .card-image img {
    transform: scale(1.05);
  }
  
  .card-content {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .card-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
  }
  
  .card-description {
    margin: 0;
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.4;
  }
  
  .card-fields {
    margin-top: 0.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }
  
  .card-field {
    display: flex;
    font-size: 0.85rem;
  }
  
  .card-field.inline {
    display: inline-flex;
    margin-right: 1rem;
  }
  
  .field-name {
    color: var(--text-secondary);
    margin-right: 0.25rem;
    font-weight: 500;
  }
  
  .field-value {
    color: var(--text-primary);
  }
  
  .card-footer {
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid rgba(0,0,0,0.05);
    font-size: 0.8rem;
    color: var(--text-secondary);
  }
  
  .card-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    padding: 0 1rem 1rem;
  }
  
  .card-action {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-size: 0.85rem;
    font-weight: 500;
    border: none;
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;
  }
  
  .card-action:hover {
    background-color: var(--border-color);
    transform: translateY(-1px);
  }
  
  .card-action:active {
    transform: translateY(0);
  }
  
  .card-action.primary {
    background-color: var(--accent-primary);
    color: var(--button-text);
  }
  
  .card-action.secondary {
    background-color: var(--accent-secondary);
    color: var(--button-text);
  }
  
  .card-action.danger {
    background-color: #ef4444;
    color: white;
  }
  
  .card-action.success {
    background-color: #22c55e;
    color: white;
  }
  
  .card-action.warning {
    background-color: #f59e0b;
    color: white;
  }
  
  .card-action.info {
    background-color: #3b82f6;
    color: white;
  }
  
  .card-action:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .action-icon {
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>
