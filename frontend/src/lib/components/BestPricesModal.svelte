<script lang="ts">
  import { createEventDispatcher } from 'svelte';
  
  export let data: {
    brand: string;
    total_products: number;
    best_price_overall: {
      title: string;
      price_per_unit: string;
      formatted_dosage: string;
      total_price: string;
      units: number;
      unit_label: string;
    } | null;
    products_by_form: Array<{
      form: string;
      products: Array<{
        id: number;
        title: string;
        brand: string;
        price_per_unit: string;
        total_price: string;
        units: number;
        unit_label: string;
        dosage: string;
        has_promotion: boolean;
        promotion_type?: string;
        url: string;
        source_site: string;
      }>;
    }>;
  };
  
  const dispatch = createEventDispatcher();
  
  function formatPrice(price: string): string {
    return `$${price}`;
  }
  
  function handleProductClick(product: any) {
    dispatch('productClick', { product });
  }
</script>

<div class="best-prices-container">
  <div class="header">
    <h2 class="title">💊 {data.brand} - Mejores Precios</h2>
    <span class="total-products">{data.total_products} productos encontrados</span>
  </div>
  
  {#if data.best_price_overall}
    <div class="best-price-card">
      <div class="best-price-label">⭐ MEJOR PRECIO POR UNIDAD</div>
      <div class="best-price-content">
        <div class="product-info">
          <h3 class="product-title">{data.best_price_overall.title}</h3>
          <span class="dosage">{data.best_price_overall.formatted_dosage}</span>
        </div>
        <div class="price-info">
          <div class="price-per-unit">{formatPrice(data.best_price_overall.price_per_unit)} por unidad</div>
          <div class="total-price">Total: {formatPrice(data.best_price_overall.total_price)} 
            ({data.best_price_overall.units} {data.best_price_overall.unit_label})</div>
        </div>
      </div>
    </div>
  {/if}
  
  <div class="products-by-form">
    {#each data.products_by_form as formGroup}
      <div class="form-section">
        <h3 class="form-title">{formGroup.form}</h3>
        <div class="products-list">
          {#each formGroup.products as product}
            <div 
              class="product-card"
              class:has-promotion={product.has_promotion}
              on:click={() => handleProductClick(product)}
            >
              <div class="product-header">
                <h4 class="product-name">{product.title}</h4>
                {#if product.has_promotion}
                  <span class="promo-badge">OFERTA</span>
                {/if}
              </div>
              
              <div class="product-details">
                <div class="dosage-info">
                  <span class="dosage">{product.dosage}</span>
                  <span class="units">{product.units} {product.unit_label}</span>
                </div>
                
                <div class="price-details">
                  <div class="price-per-unit">{formatPrice(product.price_per_unit)} 
                    <span class="unit-label">/ unidad</span>
                  </div>
                  <div class="total-price">{formatPrice(product.total_price)} total</div>
                </div>
              </div>
              
              <div class="product-footer">
                <span class="source">{product.source_site}</span>
                <button class="view-button">Ver →</button>
              </div>
            </div>
          {/each}
        </div>
      </div>
    {/each}
  </div>
  
  <div class="footer">
    <button class="close-button" on:click={() => dispatch('close')}>
      Cerrar
    </button>
  </div>
</div>

<style>
  .best-prices-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    max-height: 80vh;
    overflow-y: auto;
    margin: 16px 0;
  }
  
  .header {
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 16px 16px 0 0;
  }
  
  .title {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
  }
  
  .total-products {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
  }
  
  .best-price-card {
    margin: 20px;
    padding: 20px;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 12px;
    color: white;
  }
  
  .best-price-label {
    font-size: 12px;
    font-weight: 600;
    opacity: 0.9;
    margin-bottom: 8px;
  }
  
  .best-price-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .product-info h3 {
    margin: 0 0 4px 0;
    font-size: 18px;
  }
  
  .dosage {
    opacity: 0.9;
    font-size: 14px;
  }
  
  .price-info {
    text-align: right;
  }
  
  .price-per-unit {
    font-size: 24px;
    font-weight: 700;
  }
  
  .total-price {
    opacity: 0.9;
    font-size: 14px;
  }
  
  .products-by-form {
    padding: 20px;
  }
  
  .form-section {
    margin-bottom: 24px;
  }
  
  .form-title {
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
    margin: 0 0 12px 0;
    padding-bottom: 8px;
    border-bottom: 2px solid #e0e0e0;
  }
  
  .products-list {
    display: grid;
    gap: 12px;
  }
  
  .product-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .product-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
  }
  
  .product-card.has-promotion {
    border-color: #f5576c;
    background: linear-gradient(to right, rgba(245, 87, 108, 0.05), transparent);
  }
  
  .product-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
  }
  
  .product-name {
    margin: 0;
    font-size: 16px;
    font-weight: 500;
    color: #2c3e50;
  }
  
  .promo-badge {
    background: #f5576c;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
  }
  
  .product-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
  }
  
  .dosage-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }
  
  .dosage {
    color: #7f8c8d;
    font-size: 14px;
  }
  
  .units {
    color: #95a5a6;
    font-size: 12px;
  }
  
  .price-details {
    text-align: right;
  }
  
  .price-per-unit {
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
  }
  
  .unit-label {
    font-size: 12px;
    color: #7f8c8d;
    font-weight: normal;
  }
  
  .total-price {
    color: #7f8c8d;
    font-size: 14px;
  }
  
  .product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .source {
    color: #95a5a6;
    font-size: 12px;
  }
  
  .view-button {
    background: #667eea;
    color: white;
    border: none;
    padding: 6px 16px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  
  .view-button:hover {
    background: #5a6fd8;
  }
  
  .footer {
    padding: 20px;
    border-top: 1px solid #e0e0e0;
    text-align: center;
  }
  
  .close-button {
    background: #ecf0f1;
    border: none;
    padding: 10px 24px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  
  .close-button:hover {
    background: #d5dbdd;
  }
</style>
