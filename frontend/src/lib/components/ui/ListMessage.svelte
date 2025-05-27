<script lang="ts">
  import { createEventDispatcher } from 'svelte';
  import type { AgUIListEvent } from '../../ag-ui/types';
  
  export let list: AgUIListEvent;
  
  const dispatch = createEventDispatcher();
  
  // Обработчик клика по элементу списка
  function handleItemClick(itemId: string) {
    dispatch('itemSelect', {
      listId: list.id,
      itemId
    });
  }
  
  // Обработчик действия
  function handleActionClick(itemId: string, actionId: string) {
    dispatch('action', {
      listId: list.id,
      itemId,
      actionId
    });
  }
</script>

<div class="list-message">
  {#if list.title}
    <h3 class="list-title">{list.title}</h3>
  {/if}
  
  <div class="list-items">
    {#each list.items as item}
      <div class="list-item" on:click={() => handleItemClick(item.id)}>
        {#if item.image}
          <div class="item-image">
            <img src={item.image} alt={item.title} loading="lazy" />
          </div>
        {/if}
        <div class="item-content">
          <h4 class="item-title">{item.title}</h4>
          {#if item.description}
            <p class="item-description">{item.description}</p>
          {/if}
          
          {#if item.actions && item.actions.length > 0}
            <div class="item-actions">
              {#each item.actions as action}
                <button 
                  class="item-action"
                  on:click|stopPropagation={() => handleActionClick(item.id, action.id)}
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
      </div>
    {/each}
  </div>
</div>

<style>
  .list-message {
    background-color: var(--bg-secondary);
    border-radius: 12px;
    padding: 0.75rem;
    margin: 0.5rem 0;
    max-width: 500px;
    width: 100%;
  }
  
  .list-title {
    margin: 0 0 0.75rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    padding: 0 0.5rem;
  }
  
  .list-items {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .list-item {
    display: flex;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 8px;
    background-color: var(--bg-primary);
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  
  .list-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  }
  
  .item-image {
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    border-radius: 6px;
    overflow: hidden;
  }
  
  .item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .item-content {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    gap: 0.25rem;
  }
  
  .item-title {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--text-primary);
  }
  
  .item-description {
    margin: 0;
    font-size: 0.8rem;
    color: var(--text-secondary);
    line-height: 1.4;
  }
  
  .item-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
  }
  
  .item-action {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.35rem 0.6rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    border: none;
    background-color: var(--accent-primary-faded);
    color: var(--accent-primary);
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;
  }
  
  .item-action:hover {
    background-color: rgba(var(--accent-primary-rgb), 0.2);
    transform: translateY(-1px);
  }
  
  .item-action:active {
    transform: translateY(0);
  }
  
  .action-icon {
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>
