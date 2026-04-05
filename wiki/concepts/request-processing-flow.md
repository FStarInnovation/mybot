---
title: "Request Processing Flow"
type: concept
tags: [architecture, flow, pipeline]
sources: [raw/reports/plan.md]
created: 2026-04-05
updated: 2026-04-05
---

# Request Processing Flow

Полный цикл обработки пользовательского запроса в MyBot (8 шагов).

## Поток

### 1. Получение запроса
Пользователь отправляет сообщение через Web UI / Telegram / WhatsApp → Laravel получает через REST API или webhook.

### 2. Краткосрочная память
Laravel извлекает из Upstash Redis последние N реплик диалога для контекста.

### 3. Долгосрочная память (опционально)
Если нужен исторический контекст:
- Jina генерирует embedding запроса
- Qdrant ищет top-N ближайших фрагментов
- Результаты включаются в prompt

### 4. Формирование prompt
Объединение: сообщение + история (Redis) + контекст (Qdrant) + системные инструкции → отправка на RunPod Tornado API.

### 5. LLaMA3 генерация
Модель генерирует ответ. При необходимости — tool calling (парсинг, поиск, вычисления).

### 6. Tool calling (опционально)
Tornado перехватывает вызовы → выполняет через Laravel API → возвращает результат модели → модель продолжает.

### 7. Пост-обработка
- Форматирование ответа
- Сохранение в Redis (обновление контекста)
- Сохранение в Qdrant (если есть новые факты) — async
- Логирование в Langfuse — async

### 8. Доставка
JSON по HTTP / WebSocket → Web UI, или API Telegram/WhatsApp → мессенджер.

## Принцип

Каждый запрос обрабатывается "с нуля" с подтягиванием контекста on-demand. Минимальные требования к хранению.

## See also

- [[concepts/memory-tiers|Memory Tiers]]
- [[concepts/tool-calling|Tool Calling]]
- [[entities/laravel-backend|Laravel Backend]]
- [[entities/runpod-vps|RunPod VPS]]
- [[summaries/architecture-plan|Архитектурный план]]
