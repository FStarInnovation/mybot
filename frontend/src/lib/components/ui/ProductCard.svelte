<script lang="ts">
  import { useProduct } from '$lib/tanstack/product-queries';
  import { goto } from '$app/navigation';
  import { readable } from 'svelte/store';
  
  export let productId: string | number;
  export let compact: boolean = false; // Компактный режим отображения
  export let clickable: boolean = true; // Можно ли кликнуть по карточке

  // Опциональные поля, если товар передан напрямую
  export let title: string | undefined;
  export let price: number | string | undefined;
  export let image: string | undefined;
  export let url: string | undefined;

  // Создаём источник данных:
  const productQuery = (title !== undefined && price !== undefined)
    ? readable({
        isLoading: false,
        isError: false,
        data: {
          id: productId,
          name: title,
          price: typeof price === 'string' ? parseFloat(price as string) : (price as number),
          image,
          url,
          availability: true
        }
      })
    : useProduct(productId);
  
  // Форматирование цены
  function formatPrice(price: number): string {
    return new Intl.NumberFormat('ru-RU', {
      style: 'currency',
      currency: 'RUB',
      minimumFractionDigits: 0
    }).format(price);
  }
  
  // Обработчик клика по карточке
  function handleClick() {
    if (clickable && $productQuery.data) {
      if ($productQuery.data.url) {
        window.open($productQuery.data.url, '_blank');
      } else {
        goto(`/products/${$productQuery.data.id}`);
      }
    }
  }
  
  // Генерация звездного рейтинга
  function getRatingStars(rating: number) {
    const stars = [];
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    
    // Полные звезды
    for (let i = 0; i < fullStars; i++) {
      stars.push('★');
    }
    
    // Половинчатая звезда
    if (hasHalfStar) {
      stars.push('✫');
    }
    
    // Пустые звезды
    const emptyStars = 5 - stars.length;
    for (let i = 0; i < emptyStars; i++) {
      stars.push('☆');
    }
    
    return stars.join('');
  }
</script>

<div class="product-card" class:compact class:clickable on:click={handleClick}>
  {#if $productQuery.isLoading}
    <div class="loading-state">
      <div class="loading-spinner"></div>
      <span>Загрузка товара...</span>
    </div>
  {:else if $productQuery.isError}
    <div class="error-state">
      <span>Ошибка при загрузке товара</span>
      <button on:click={() => $productQuery.refetch()}>Повторить</button>
    </div>
  {:else if $productQuery.data}
    {#if $productQuery.data.image}
      <div class="product-image">
        <img src={$productQuery.data.image} alt={$productQuery.data.name} loading="lazy" />
        {#if !$productQuery.data.availability}
          <div class="availability-badge">Нет в наличии</div>
        {/if}
      </div>
    {/if}
    
    <div class="product-content">
      <h3 class="product-name">{$productQuery.data.name}</h3>
      
      {#if !compact}
        <p class="product-description">{$productQuery.data.description}</p>
      {/if}
      
      <div class="product-info">
        <span class="product-price">{formatPrice($productQuery.data.price)}</span>
        
        {#if $productQuery.data.rating !== undefined}
          <div class="product-rating">
            <span class="stars" title="Рейтинг: {$productQuery.data.rating} из 5">
              {getRatingStars($productQuery.data.rating)}
            </span>
            {#if $productQuery.data.reviewCount}
              <span class="reviews-count">({$productQuery.data.reviewCount})</span>
            {/if}
          </div>
        {/if}
        
        {#if !compact && $productQuery.data.category}
          <div class="product-category">
            <span>{$productQuery.data.category}</span>
          </div>
        {/if}
      </div>
      
      {#if !compact}
        <div class="product-actions">
          <button class="buy-button" disabled={!$productQuery.data.availability}>
            {$productQuery.data.availability ? 'Купить' : 'Нет в наличии'}
          </button>
          <button class="wishlist-button">
            <span class="heart-icon">♡</span>
          </button>
        </div>
      {/if}
    </div>
  {/if}
</div>

<style>
  .product-card {
    display: flex;
    flex-direction: column;
    background-color: var(--bg-secondary);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 100%;
  }
  
  .product-card.clickable {
    cursor: pointer;
  }
  
  .product-card.clickable:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  }
  
  .product-card.compact {
    flex-direction: row;
    align-items: center;
  }
  
  .product-image {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
  }
  
  .product-card.compact .product-image {
    width: 100px;
    height: 100px;
    flex-shrink: 0;
    margin-right: 1rem;
  }
  
  .product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }
  
  .product-card.clickable:hover .product-image img {
    transform: scale(1.05);
  }
  
  .availability-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: rgba(220, 53, 69, 0.85);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 500;
  }
  
  .product-content {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
  }
  
  .product-name {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  
  .product-description {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin: 0 0 0.75rem 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
  }
  
  .product-info {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    gap: 0.5rem;
  }
  
  .product-price {
    font-weight: 700;
    font-size: 1.2rem;
    color: var(--accent-primary);
  }
  
  .product-rating {
    display: flex;
    align-items: center;
    gap: 0.25rem;
  }
  
  .stars {
    color: #f9a825;
    letter-spacing: -2px;
  }
  
  .reviews-count {
    color: var(--text-secondary);
    font-size: 0.8rem;
  }
  
  .product-category {
    background-color: var(--bg-tertiary);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    color: var(--text-secondary);
  }
  
  .product-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: auto;
    padding-top: 0.5rem;
  }
  
  .buy-button {
    flex-grow: 1;
    padding: 0.6rem 1rem;
    background-color: var(--accent-primary);
    color: var(--button-text);
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
  }
  
  .buy-button:hover:not(:disabled) {
    background-color: var(--button-hover-bg);
  }
  
  .buy-button:disabled {
    background-color: var(--border-color);
    cursor: not-allowed;
    opacity: 0.7;
  }
  
  .wishlist-button {
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bg-tertiary);
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.2s ease, color 0.2s ease;
  }
  
  .wishlist-button:hover {
    background-color: rgba(var(--accent-primary-rgb), 0.1);
    color: var(--accent-primary);
  }
  
  .heart-icon {
    font-size: 1.25rem;
    transform: translateY(-1px);
  }
  
  /* Состояния загрузки и ошибки */
  .loading-state, .error-state {
    padding: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 1rem;
    color: var(--text-secondary);
    min-height: 200px;
  }
  
  .loading-spinner {
    width: 30px;
    height: 30px;
    border: 3px solid rgba(var(--accent-primary-rgb), 0.3);
    border-radius: 50%;
    border-top-color: var(--accent-primary);
    animation: spin 1s ease-in-out infinite;
  }
  
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  
  .error-state button {
    padding: 0.5rem 1rem;
    background-color: var(--accent-primary);
    color: var(--button-text);
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
  }
</style>
