/**
 * Утилиты для работы с данными о товарах
 */
import type { Product } from '$lib/types/product';

/**
 * Извлекает количество таблеток из названия продукта
 * Поддерживает форматы:
 * - "Ибупрофен 200 мг 20 таб"
 * - "Парацетамол 500мг №10"
 * - "Аспирин 30 таблеток"
 * - "Ибупрофен 400 мг, блистер 10 шт"
 */
export function extractTabletsCount(productName: string): number | null {
  // Нормализуем строку для упрощения обработки
  const normalizedName = productName.toLowerCase().replace(/,/g, ' ');
  
  // Поиск форматов с "таб", "таблеток", "табл"
  const tabletRegex = /\b(\d+)\s*(таб|табл|таблет|таблеток)\b/;
  const tabletMatch = normalizedName.match(tabletRegex);
  
  if (tabletMatch) {
    return parseInt(tabletMatch[1], 10);
  }
  
  // Поиск формата с "№10", "N20" и т.д.
  const numRegex = /\b[№nN]\s*(\d+)\b/;
  const numMatch = normalizedName.match(numRegex);
  
  if (numMatch) {
    return parseInt(numMatch[1], 10);
  }
  
  // Поиск формата с "блистер 10 шт", "упаковка 20 шт" и т.д.
  const packRegex = /\b(блистер|упаковка|уп)\s+(\d+)\s+(шт|штук)\b/;
  const packMatch = normalizedName.match(packRegex);
  
  if (packMatch) {
    return parseInt(packMatch[2], 10);
  }
  
  // Если не удалось найти информацию о количестве, возвращаем null
  return null;
}

/**
 * Рассчитывает цену за одну таблетку, используя общую цену и количество таблеток
 */
export function calculatePricePerTablet(price: number, tabletsCount: number | null): number | null {
  if (tabletsCount && tabletsCount > 0) {
    return price / tabletsCount;
  }
  return null;
}

/**
 * Форматирует цену в формате валюты
 */
export function formatPrice(price: number, currency: string = 'RUB', minimumFractionDigits: number = 0): string {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: currency,
    minimumFractionDigits: minimumFractionDigits,
    maximumFractionDigits: minimumFractionDigits === 0 ? 0 : 2
  }).format(price);
}

/**
 * Получает количество таблеток для товара из атрибутов или названия
 * @param product товар для анализа
 * @returns количество таблеток или null, если не удалось определить
 */
export function getTabletsCount(product: Product): number | null {
  // Сначала проверяем атрибуты - если есть явное указание количества таблеток
  if (product.attributes && typeof product.attributes === 'object' && 'tablets_count' in product.attributes) {
    const tabletCountValue = product.attributes.tablets_count;
    if (typeof tabletCountValue === 'string') {
      const tabletCount = parseInt(tabletCountValue, 10);
      if (!isNaN(tabletCount) && tabletCount > 0) {
        return tabletCount;
      }
    } else if (typeof tabletCountValue === 'number' && tabletCountValue > 0) {
      return tabletCountValue;
    }
  }
  
  // Если в атрибутах нет информации, извлекаем из названия
  return extractTabletsCount(product.name);
}

/**
 * Рассчитывает цену за таблетку для указанного товара
 * @param product товар для расчета
 * @returns цена за таблетку или null, если не удалось рассчитать
 */
export function getPricePerTablet(product: Product): number | null {
  const tabletCount = getTabletsCount(product);
  return calculatePricePerTablet(product.price, tabletCount);
}

/**
 * Форматирует цену за таблетку для отображения
 * @param product товар для форматирования цены за таблетку
 * @returns отформатированная цена за таблетку или 'Н/Д', если не удалось рассчитать
 */
export function formatPricePerTablet(product: Product): string {
  const pricePerTablet = getPricePerTablet(product);
  if (pricePerTablet === null) {
    return 'Н/Д';
  }
  return formatPrice(pricePerTablet, 'RUB', 2);
}

/**
 * Находит товар с самой низкой ценой за таблетку из списка товаров
 * @param products список товаров для анализа
 * @returns товар с самой низкой ценой за таблетку или null, если таких нет
 */
export function findCheapestPerTablet(products: Product[]): Product | null {
  if (!products || products.length === 0) return null;
  
  // Фильтруем товары, для которых можно рассчитать цену за таблетку
  const productsWithTabletPrice = products.filter(product => {
    const tabletCount = getTabletsCount(product);
    return tabletCount !== null && tabletCount > 0 && product.price > 0;
  });
  
  if (productsWithTabletPrice.length === 0) return null;
  
  // Сортируем по цене за таблетку (от меньшей к большей)
  return productsWithTabletPrice.sort((a, b) => {
    const priceA = getPricePerTablet(a) || Infinity;
    const priceB = getPricePerTablet(b) || Infinity;
    return priceA - priceB;
  })[0];
}
