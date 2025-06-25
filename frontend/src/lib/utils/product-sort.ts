import { extractTabletsCount } from './product-helpers';
import type { Product } from '$lib/types/product';

/**
 * Расчет цены за таблетку для сортировки
 * @param product Объект продукта
 * @returns Цена за таблетку или Infinity если не удалось рассчитать
 */
export function getPricePerTabletForSort(product: Product): number {
  // Проверяем, есть ли информация о количестве таблеток в атрибутах
  if (product?.attributes?.tabletsCount) {
    return product.price / product.attributes.tabletsCount;
  }
  
  // Если в атрибутах нет, пытаемся извлечь из названия
  const tabletsCount = product?.name ? extractTabletsCount(product.name) : null;
  
  // Если удалось извлечь количество таблеток, рассчитываем цену за таблетку
  if (tabletsCount && tabletsCount > 0) {
    return product.price / tabletsCount;
  }
  
  // Если не удалось рассчитать, возвращаем Infinity для сортировки
  return Infinity;
}

/**
 * Сортировка списка продуктов по цене за таблетку (по возрастанию)
 */
export function sortProductsByPricePerTabletAsc(products: Product[]): Product[] {
  return [...products].sort((a, b) => getPricePerTabletForSort(a) - getPricePerTabletForSort(b));
}

/**
 * Сортировка списка продуктов по цене за таблетку (по убыванию)
 */
export function sortProductsByPricePerTabletDesc(products: Product[]): Product[] {
  return [...products].sort((a, b) => getPricePerTabletForSort(b) - getPricePerTabletForSort(a));
}
