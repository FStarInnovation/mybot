import { createQuery } from '@tanstack/svelte-query';
import { getProductById, getProducts, getProductsByCategory, searchProducts } from '$lib/api/products';
import type { ProductQueryParams } from '$lib/types/product';

/**
 * Хук для получения товара по ID
 */
export function useProduct(id: string | number) {
  return createQuery({
    queryKey: ['product', id],
    queryFn: () => getProductById(id),
  });
}

/**
 * Хук для получения списка товаров с возможностью фильтрации
 */
export function useProducts(params?: ProductQueryParams) {
  return createQuery({
    queryKey: ['products', params],
    queryFn: () => getProducts(params),
  });
}

/**
 * Хук для получения товаров по категории
 */
export function useProductsByCategory(category: string, params?: Omit<ProductQueryParams, 'category'>) {
  return createQuery({
    queryKey: ['products', 'category', category, params],
    queryFn: () => getProductsByCategory(category, params),
  });
}

/**
 * Хук для поиска товаров по ключевому слову
 */
export function useProductSearch(query: string, params?: Omit<ProductQueryParams, 'search'>) {
  return createQuery({
    queryKey: ['products', 'search', query, params],
    queryFn: () => searchProducts(query, params),
    enabled: query.length > 2, // Активируем запрос только если длина поискового запроса > 2 символов
  });
}
