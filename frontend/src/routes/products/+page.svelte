<script lang="ts">
  import { useProducts } from '$lib/tanstack/product-queries';
  import ProductCard from '$lib/components/ui/ProductCard.svelte';
  import { onMount } from 'svelte';
  
  // Параметры запроса к API
  let page = 1;
  let limit = 12;
  let category = '';
  let search = '';
  let sort = 'name_asc';
  
  // Загружаем данные о товарах
  const productsQuery = useProducts({ page, limit, category, search, sort });
  
  // Создаем реактивное выражение для переключения страниц
  $: {
    // Обновляем запрос при изменении параметров
    $productsQuery.setQueryKey(['products', { page, limit, category, search, sort }]);
    $productsQuery.refetch();
  }
  
  // Обработчик поиска
  function handleSearch(event: Event) {
    event.preventDefault();
    page = 1; // Сбрасываем страницу при новом поиске
  }
  
  // Изменение сортировки
  function handleSortChange(event: Event) {
    const select = event.target as HTMLSelectElement;
    sort = select.value;
    page = 1; // Сбрасываем страницу при изменении сортировки
  }
  
  // Функция для перехода на предыдущую страницу
  function previousPage() {
    if (page > 1) {
      page--;
    }
  }
  
  // Функция для перехода на следующую страницу
  function nextPage() {
    if ($productsQuery.data && page < Math.ceil($productsQuery.data.total / limit)) {
      page++;
    }
  }
  
  // Функция для создания тестовой таблицы в базе данных Neon, если у нас её еще нет
  async function createTestProductsTable() {
    try {
      const response = await fetch('/api/setup-test-products', {
        method: 'POST'
      });
      
      if (response.ok) {
        const result = await response.json();
        alert(`Таблица товаров создана: ${result.message}`);
        // Обновляем данные
        $productsQuery.refetch();
      } else {
        const error = await response.json();
        alert(`Ошибка: ${error.error}`);
      }
    } catch (error) {
      console.error('Ошибка при создании тестовых данных:', error);
      alert('Произошла ошибка при создании тестовых данных');
    }
  }
</script>

<svelte:head>
  <title>Каталог товаров</title>
</svelte:head>

<div class="products-page">
  <header class="products-header">
    <h1>Каталог товаров</h1>
    
    <div class="controls">
      <form on:submit={handleSearch} class="search-form">
        <input 
          type="text" 
          bind:value={search} 
          placeholder="Поиск товаров..." 
          aria-label="Поиск"
        />
        <button type="submit">Искать</button>
      </form>
      
      <div class="sort-control">
        <label for="sort">Сортировка:</label>
        <select id="sort" on:change={handleSortChange} bind:value={sort}>
          <option value="name_asc">По названию (А-Я)</option>
          <option value="name_desc">По названию (Я-А)</option>
          <option value="price_asc">Сначала дешевые</option>
          <option value="price_desc">Сначала дорогие</option>
          <option value="rating">По рейтингу</option>
          <option value="newest">Сначала новые</option>
        </select>
      </div>
    </div>
    
    <button class="setup-button" on:click={createTestProductsTable}>
      Создать тестовые товары
    </button>
  </header>
  
  <div class="products-container">
    {#if $productsQuery.isLoading}
      <div class="loading-state">
        <div class="spinner"></div>
        <p>Загрузка товаров...</p>
      </div>
    {:else if $productsQuery.isError}
      <div class="error-state">
        <h2>Ошибка при загрузке товаров</h2>
        <p>Проверьте подключение к интернету или попробуйте позже</p>
        <button on:click={() => $productsQuery.refetch()}>Повторить</button>
      </div>
    {:else if $productsQuery.data && $productsQuery.data.products.length === 0}
      <div class="empty-state">
        <h2>Товары не найдены</h2>
        <p>Попробуйте изменить параметры поиска или создать тестовые товары</p>
      </div>
    {:else if $productsQuery.data}
      <div class="products-grid">
        {#each $productsQuery.data.products as product (product.id)}
          <div class="product-item">
            <ProductCard productId={product.id} />
          </div>
        {/each}
      </div>
      
      <div class="pagination">
        <button 
          on:click={previousPage} 
          disabled={page === 1}
          class="pagination-button"
        >
          Предыдущая
        </button>
        
        <span class="pagination-info">
          Страница {page} из {Math.ceil($productsQuery.data.total / limit)}
          (Всего товаров: {$productsQuery.data.total})
        </span>
        
        <button 
          on:click={nextPage} 
          disabled={page >= Math.ceil($productsQuery.data.total / limit)}
          class="pagination-button"
        >
          Следующая
        </button>
      </div>
    {/if}
  </div>
</div>

<style>
  .products-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
  }
  
  .products-header {
    margin-bottom: 2rem;
  }
  
  .products-header h1 {
    margin-bottom: 1.5rem;
    font-size: 2rem;
    color: var(--text-primary);
  }
  
  .controls {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
  }
  
  .search-form {
    display: flex;
    flex-grow: 1;
    max-width: 500px;
  }
  
  .search-form input {
    flex-grow: 1;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 6px 0 0 6px;
    font-size: 0.95rem;
    background-color: var(--bg-primary);
    color: var(--text-primary);
  }
  
  .search-form input:focus {
    outline: none;
    border-color: var(--accent-primary);
  }
  
  .search-form button {
    padding: 0.75rem 1.25rem;
    background-color: var(--accent-primary);
    color: var(--button-text);
    border: none;
    border-radius: 0 6px 6px 0;
    cursor: pointer;
    font-weight: 500;
  }
  
  .sort-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .sort-control label {
    font-size: 0.9rem;
    color: var(--text-secondary);
  }
  
  .sort-control select {
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.9rem;
  }
  
  .setup-button {
    padding: 0.75rem 1.25rem;
    background-color: var(--accent-secondary);
    color: var(--button-text);
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    margin-top: 1rem;
  }
  
  .products-container {
    min-height: 400px;
  }
  
  .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
  }
  
  .product-item {
    height: 100%;
  }
  
  .pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
    padding: 1rem 0;
    border-top: 1px solid var(--border-color);
  }
  
  .pagination-button {
    padding: 0.6rem 1.2rem;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s ease;
  }
  
  .pagination-button:hover:not(:disabled) {
    background-color: var(--accent-primary-faded);
  }
  
  .pagination-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  .pagination-info {
    color: var(--text-secondary);
    font-size: 0.9rem;
  }
  
  /* Состояния загрузки, ошибки и пустой результат */
  .loading-state,
  .error-state,
  .empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 4rem 2rem;
    gap: 1rem;
    background-color: var(--bg-secondary);
    border-radius: 12px;
  }
  
  .spinner {
    width: 40px;
    height: 40px;
    border: 4px solid rgba(var(--accent-primary-rgb), 0.3);
    border-radius: 50%;
    border-top-color: var(--accent-primary);
    animation: spin 1s ease-in-out infinite;
  }
  
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  
  .error-state h2,
  .empty-state h2 {
    color: var(--text-primary);
    margin: 0;
  }
  
  .error-state p,
  .empty-state p {
    color: var(--text-secondary);
    margin: 0 0 1rem 0;
  }
  
  .error-state button {
    padding: 0.6rem 1.2rem;
    background-color: var(--accent-primary);
    color: var(--button-text);
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
  }
  
  @media (max-width: 768px) {
    .controls {
      flex-direction: column;
    }
    
    .search-form {
      max-width: 100%;
    }
    
    .products-grid {
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 1rem;
    }
    
    .pagination {
      flex-direction: column;
      gap: 1rem;
    }
  }
</style>
