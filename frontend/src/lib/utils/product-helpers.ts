/**
 * Утилиты для работы с данными о товарах
 */

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
