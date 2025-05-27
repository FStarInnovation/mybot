<script lang="ts">
  import { useProducts, useProduct } from '$lib/tanstack/product-queries';

  // --- Fetch All Products ---
  const productsQuery = useProducts({ page: 1, limit: 5 }); // Fetch first 5 products for demo

  // --- Fetch Single Product ---
  let productToFetchIdInput: string = "1";
  let currentProductId: number | null = null;
  let singleProductQuery: ReturnType<typeof useProduct> | null = null;

  function handleFetchProduct() {
    const id = parseInt(productToFetchIdInput);
    if (!isNaN(id)) {
      currentProductId = id;
    } else {
      currentProductId = null;
    }
  }

  // Reactive declaration for singleProductQuery
  // This creates a new query store each time currentProductId changes.
  // Svelte's $ prefix will subscribe/unsubscribe appropriately.
  $: if (currentProductId !== null) {
    singleProductQuery = useProduct(currentProductId);
  } else {
    singleProductQuery = null;
  }

  async function setupTestDatabase() {
    try {
      const response = await fetch('/api/setup-test-products', { method: 'POST' });
      if (response.ok) {
        alert('Test database setup successfully!');
        productsQuery.refetch();
        if (singleProductQuery && currentProductId) {
          // Re-create the query for the single product to refetch with new data
          singleProductQuery = useProduct(currentProductId); 
        }
      } else {
        const errorData = await response.json();
        alert(`Failed to setup test database: ${errorData.message || response.statusText}`);
      }
    } catch (error) {
      alert(`Error setting up test database: ${error instanceof Error ? error.message : String(error)}`);
    }
  }

</script>

<svelte:head>
  <title>TanStack Query Test Page</title>
</svelte:head>

<div class="container mx-auto p-4">
  <h1 class="text-2xl font-bold mb-6">TanStack Query Test Page</h1>

  <div class="mb-6">
    <button
      on:click={setupTestDatabase}
      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
    >
      Setup/Reset Test Database
    </button>
    <p class="text-sm text-gray-600 mt-1">
      Click this to ensure test products are in the database.
    </p>
  </div>

  <!-- Section for All Products -->
  <section class="mb-8 p-4 border rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-3">Fetch All Products (First 5)</h2>
    {#if $productsQuery.isLoading}
      <p>Loading products...</p>
    {:else if $productsQuery.isError}
      <p class="text-red-500">Error fetching products: {$productsQuery.error?.message}</p>
    {:else if $productsQuery.data}
      <ul class="list-disc pl-5">
        {#each $productsQuery.data.products as product (product.id)}
          <li>{product.name} (ID: {product.id}) - Price: ${product.price}</li>
        {/each}
      </ul>
      {#if $productsQuery.data.products.length === 0}
        <p>No products found. Try setting up the test database.</p>
      {/if}
    {:else}
      <p>No data.</p>
    {/if}
    <button
      on:click={() => productsQuery.refetch()}
      disabled={$productsQuery.isFetching}
      class="mt-3 bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm"
    >
      {#if $productsQuery.isFetching}Refetching...{:else}Refetch Products List{/if}
    </button>
  </section>

  <!-- Section for Single Product -->
  <section class="p-4 border rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-3">Fetch Single Product by ID</h2>
    <div class="flex items-center space-x-2 mb-3">
      <input
        type="number"
        bind:value={productToFetchIdInput}
        placeholder="Enter Product ID"
        class="border p-2 rounded w-48"
      />
      <button
        on:click={handleFetchProduct}
        class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
      >
        Fetch Product
      </button>
    </div>

    {#if currentProductId === null}
      <p class="text-gray-500">Enter an ID and click "Fetch Product".</p>
    {:else if singleProductQuery}
      {#if $singleProductQuery.isLoading}
        <p>Loading product {currentProductId}...</p>
      {:else if $singleProductQuery.isError}
        <p class="text-red-500">Error fetching product {currentProductId}: {$singleProductQuery.error?.message}</p>
      {:else if $singleProductQuery.data}
        <div class="bg-gray-100 p-3 rounded">
          <h3 class="font-medium">Product Details (ID: {$singleProductQuery.data.id})</h3>
          <p><strong>Name:</strong> {$singleProductQuery.data.name}</p>
          <p><strong>Description:</strong> {$singleProductQuery.data.description}</p>
          <p><strong>Price:</strong> ${$singleProductQuery.data.price}</p>
          <p><strong>Category:</strong> {$singleProductQuery.data.category}</p>
          <p><strong>Stock:</strong> {$singleProductQuery.data.stock}</p>
          <img src={$singleProductQuery.data.image_url} alt={$singleProductQuery.data.name} class="w-32 h-32 object-cover mt-2 rounded"/>
        </div>
        <button
          on:click={() => singleProductQuery?.refetch()}
          disabled={$singleProductQuery.isFetching}
          class="mt-3 bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm"
        >
          {#if $singleProductQuery.isFetching}Refetching...{:else}Refetch Product {currentProductId}{/if}
        </button>
      {:else}
        <p>No data for product {currentProductId}. It might not exist or the database isn't set up.</p>
      {/if}
    {/if}
  </section>
</div>
