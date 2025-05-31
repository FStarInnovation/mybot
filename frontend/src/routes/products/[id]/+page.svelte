<script lang="ts">
  import { page } from '$app/stores';
  import { useProduct } from '$lib/tanstack/product-queries';
  import ProductCard from '$lib/components/ui/ProductCard.svelte';

  let productId: string | number;
  $: productId = $page.params.id;

  const productQuery = useProduct(productId);
</script>

<svelte:head>
  <title>Товар {$productQuery.data ? $productQuery.data.name : ''}</title>
</svelte:head>

<div class="product-detail-page">
  {#if $productQuery.isLoading}
    <p>Загрузка товара...</p>
  {:else if $productQuery.isError}
    <p>Ошибка при загрузке товара</p>
  {:else if $productQuery.data}
    <ProductCard productId={productId} clickable={false} />
  {/if}

  <a href="/products" class="back-link">← Назад к каталогу</a>
</div>

<style>
  .product-detail-page {
    max-width: 600px;
    margin: 2rem auto;
    padding: 0 1rem;
  }
  .back-link {
    display: inline-block;
    margin-top: 1rem;
    color: var(--link-color, #2563eb);
  }
</style>
