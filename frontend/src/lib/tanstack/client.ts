import { browser } from '$app/environment';
import { QueryClient } from '@tanstack/svelte-query';

// Создаем QueryClient с настройками по умолчанию
export const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      enabled: browser, // Запросы активны только в браузере
      refetchOnWindowFocus: true, // Обновление данных при фокусе окна
      staleTime: 1000 * 60 * 5, // Данные считаются устаревшими через 5 минут
      retry: 1, // Повторная попытка при ошибке (только один раз)
    },
  },
});
