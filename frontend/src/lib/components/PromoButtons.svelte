<script lang="ts">
  import { createEventDispatcher } from 'svelte';
  
  const dispatch = createEventDispatcher();
  
  // List of promotional brands with their display names
  const promoBrands = [
    { key: 'ACTRON', name: 'ACTRON', color: '#FF6B6B' },
    { key: 'IBUPROFENO', name: 'Ibuprofeno', color: '#4ECDC4' },
    { key: 'PARACETAMOL', name: 'Paracetamol', color: '#45B7D1' },
    { key: 'ASPIRINA', name: 'Aspirina', color: '#96CEB4' },
    { key: 'VITAMINA_C', name: 'Vitamina C', color: '#FFEAA7' },
  ];
  
  function handlePromoClick(brand: string) {
    dispatch('promoClick', { brand });
  }
</script>

<div class="promo-buttons-container">
  <h3 class="promo-title">Ofertas</h3>
  <div class="promo-buttons-grid">
    {#each promoBrands as brand}
      <button 
        class="promo-button"
        style="--brand-color: {brand.color}"
        on:click={() => handlePromoClick(brand.key)}
      >
        <span class="brand-dot" aria-hidden="true"></span>
        <div class="brand-info">
          <span class="brand-name">{brand.name}</span>
          <span class="brand-slogan">Mejor precio</span>
        </div>
      </button>
    {/each}
  </div>
</div>

<style>
  .promo-buttons-container {
    background: transparent;
    border-radius: 12px;
    padding: 10px 12px;
    margin: 10px 0;
    border: 1px solid rgba(0, 0, 0, 0.06);
  }
  
  .promo-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 8px 0;
    text-align: left;
  }
  
  .promo-buttons-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }
  
  .promo-button {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 10px;
    background: var(--bg-secondary);
    cursor: pointer;
    transition: background-color 0.15s ease, border-color 0.15s ease;
    position: relative;
    overflow: hidden;
  }
  
  .promo-button:hover {
    background: rgba(var(--accent-primary-rgb), 0.06);
    border-color: rgba(var(--accent-primary-rgb), 0.25);
  }

  .brand-dot {
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: var(--brand-color);
    flex-shrink: 0;
  }
  
  .brand-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }
  
  .brand-name {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--text-primary);
    line-height: 1.1;
  }
  
  .brand-slogan {
    font-size: 0.72rem;
    color: var(--text-secondary);
    font-weight: 500;
  }
  
  @media (max-width: 768px) {
    .promo-buttons-grid {
      flex-wrap: nowrap;
      overflow-x: auto;
      padding-bottom: 6px;
    }
  }
</style>
