<script lang="ts">
  import { useProduct } from '$lib/tanstack/product-queries';
  import { goto } from '$app/navigation';
  import { extractTabletsCount, calculatePricePerTablet, formatPrice as formatPriceUtil } from '$lib/utils/product-helpers';
  
  export let productId: string | number;
  export let compact: boolean = false; // Компактный режим отображения
  export let clickable: boolean = true; // Можно ли кликнуть по карточке
  
  // Загружаем данные о товаре с помощью TanStack Query
  const productQuery = useProduct(productId);
  
  // Форматирование цены
  function formatPrice(price: number): string {
    return formatPriceUtil(price, 'RUB', 0);
  }
  
  // Получение количества таблеток из названия продукта
  function getTabletsCount(product: any): number | null {
    if (product?.attributes?.tabletsCount) {
      return product.attributes.tabletsCount;
    }
    return product?.name ? extractTabletsCount(product.name) : null;
  }
  
  // Расчет цены за одну таблетку в блистере
  function getPricePerTablet(product: any): number | null {
    const tabletsCount = getTabletsCount(product);
    return calculatePricePerTablet(product.price, tabletsCount);
  }
  
  // Форматирование цены за таблетку
  function formatPricePerTablet(product: any): string {
    const pricePerTablet = getPricePerTablet(product);
    return pricePerTablet !== null ? formatPriceUtil(pricePerTablet, 'RUB', 2) : 'Н/Д';
  }
  
  // Обработчик клика по карточке
  function handleClick() {
    if (clickable && $productQuery.data) {
      // Навигация на страницу товара
      goto(`/products/${$productQuery.data.id}`);
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

<div class="product-card {$$props.class || ''}" class:compact class:clickable
  role="button" 
  tabindex="0"
  on:click={handleClick}
  on:keydown={e => e.key === 'Enter' && handleClick()}
>
  <!-- Очевидное изменение для проверки деплоя -->
  <div class="deploy-test-banner">ТЕСТ ДЕПЛОЯ v2 {new Date().toISOString().substr(0, 10)}</div>
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
        
        {#if getTabletsCount($productQuery.data) && !compact}
          <div class="tablets-count">
            {getTabletsCount($productQuery.data)} таб. в упаковке
          </div>
        {/if}
        
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
            {$productQuery.data.availability ? 'Mejor precio ibuprofeno' : 'Sin existencias'}
          </button>
          {#if getPricePerTablet($productQuery.data) !== null}
            <div class="price-per-tablet">
              {formatPricePerTablet($productQuery.data)}/таблетка
            </div>
          {/if}
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
  
  .deploy-test-banner {
    background-color: #ff5722;
    color: white;
    text-align: center;
    padding: 8px;
    font-weight: bold;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
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
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  
  .product-description {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
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
    flex-wrap: wrap;
    margin-top: auto;
    padding-top: 1rem;
    gap: 0.5rem;
  }
  
  .buy-button {
    flex: 1;
    background-color: var(--accent-color);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.6rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
  }
  
  .price-per-tablet {
    width: 100%;
    font-size: 0.9rem;
    color: var(--accent-color);
    font-weight: bold;
    text-align: center;
    margin-top: 0.3rem;
  }
  
  .tablets-count {
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin-top: 0.2rem;
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
