import type { Product, ProductsResponse, ProductQueryParams } from '$lib/types/product';

const API_BASE_URL = '/api';

/**
 * Получение списка товаров с возможностью фильтрации и пагинации
 */
export async function getProducts(params?: ProductQueryParams): Promise<ProductsResponse> {
  // Формируем параметры запроса
  const queryParams = new URLSearchParams();
  if (params?.page) queryParams.append('page', params.page.toString());
  if (params?.limit) queryParams.append('limit', params.limit.toString());
  if (params?.category) queryParams.append('category', params.category);
  if (params?.search) queryParams.append('search', params.search);
  if (params?.sort) queryParams.append('sort', params.sort);
  
  const url = `${API_BASE_URL}/products?${queryParams.toString()}`;
  const response = await fetch(url);
  
  if (!response.ok) {
    throw new Error(`Ошибка при получении списка товаров: ${response.status}`);
  }
  
  return await response.json();
}

/**
 * Получение информации о конкретном товаре по ID
 */
export async function getProductById(id: string | number): Promise<Product> {
  const url = `${API_BASE_URL}/products/${id}`;
  const response = await fetch(url);
  
  if (!response.ok) {
    throw new Error(`Товар с ID ${id} не найден`);
  }
  
  return await response.json();
}

/**
 * Получение товаров определенной категории
 */
export async function getProductsByCategory(category: string, params?: Omit<ProductQueryParams, 'category'>): Promise<ProductsResponse> {
  return getProducts({ ...params, category });
}

/**
 * Поиск товаров по ключевому слову
 */
export async function searchProducts(query: string, params?: Omit<ProductQueryParams, 'search'>): Promise<ProductsResponse> {
  return getProducts({ ...params, search: query });
}
