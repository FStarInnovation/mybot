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
  <h3 class="promo-title">💊 Ofertas Especiales</h3>
  <div class="promo-buttons-grid">
    {#each promoBrands as brand}
      <button 
        class="promo-button"
        style="--brand-color: {brand.color}"
        on:click={() => handlePromoClick(brand.key)}
      >
        <span class="brand-icon">💊</span>
        <div class="brand-info">
          <span class="brand-name">{brand.name}</span>
          <span class="brand-slogan">Mejor Precio</span>
        </div>
        <span class="arrow">→</span>
      </button>
    {/each}
  </div>
</div>

<style>
  .promo-buttons-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 16px;
    padding: 16px;
    margin: 16px 0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }
  
  .promo-title {
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
    margin: 0 0 12px 0;
    text-align: center;
  }
  
  .promo-buttons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
  }
  
  .promo-button {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border: none;
    border-radius: 12px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
  }
  
  .promo-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--brand-color);
    transition: width 0.3s ease;
  }
  
  .promo-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }
  
  .promo-button:hover::before {
    width: 100%;
    opacity: 0.1;
  }
  
  .brand-icon {
    font-size: 24px;
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
    font-size: 16px;
    color: #2c3e50;
  }
  
  .brand-slogan {
    font-size: 12px;
    color: #7f8c8d;
    font-weight: 500;
  }
  
  .arrow {
    font-size: 18px;
    color: var(--brand-color);
    transition: transform 0.3s ease;
  }
  
  .promo-button:hover .arrow {
    transform: translateX(4px);
  }
  
  @media (max-width: 768px) {
    .promo-buttons-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
