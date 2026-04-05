---
title: Интеграция Laravel с RunPod
type: summary
tags: [runpod, laravel, api, llm, integration]
sources:
  - wiki/raw/specs/integratio_runpod.md
created: 2026-04-05
updated: 2026-04-05
---

# Интеграция Laravel с RunPod

## Обзор

Документ описывает интеграцию Laravel-приложения с окружением RunPod, которое хостит несколько сервисов:

| Сервис | Порт | Назначение |
|--------|------|------------|
| Llama3 Chat Server | 1434 | LLM для рассуждений и function-calling |
| Jina Embedding Server | 1435 | Генерация эмбеддингов |
| API Gateway (FastAPI) | 10051 | Единая точка входа — прокси ко всем сервисам |
| NLWEB | 8000 | Веб-интерфейс и API |

Все запросы из Laravel направляются через **API Gateway** на порту 10051.

## Эндпоинты

1. **`POST /chat`** — чат-комплишн через Llama3. Поддерживает стриминг (SSE) при `stream: true`.
2. **`POST /tool/search_products`** — поиск товаров по текстовому запросу с параметром `limit`.
3. **`POST /tool/crawl_single_page`** — краулинг одной веб-страницы, возвращает title и content.
4. **`POST /ask`** — стриминговый (SSE) прокси к NLWEB `/api/v1/ask` для чат-подобных ответов.
5. **`POST /embedding`** — генерация векторных эмбеддингов через Jina.
6. **`POST /tool/mcp`** — вызов бизнес-задач на MCP-микросервисе (reprice, sync_stock, import_catalog).
7. **`POST /tool/ask`** — синхронный (не стриминговый) запрос к NLWEB.

## Реализация в Laravel

- Сервисный класс `RunPodService` инкапсулирует HTTP-вызовы через `Illuminate\Support\Facades\Http`.
- URL берётся из переменной окружения `RUNPOD_API_URL` (по умолчанию `http://localhost:10051`).
- Таймаут настраивается через `RUNPOD_TIMEOUT` (по умолчанию 120 сек).
- Конфигурация размещается в `config/services.php` под ключом `runpod`.

## Обработка ошибок

Стандартные HTTP-коды: 200, 400, 404, 500. Рекомендуется retry-логика для временных сбоев.

## Безопасность

- API-ключи или JWT для продакшена.
- Rate limiting на стороне Laravel.
- Валидация входных параметров перед отправкой.
- Логирование ошибок.

## See also

- [[wiki/entities/runpod-api-gateway|API Gateway RunPod]]
- [[wiki/entities/llama3-chat-server|Llama3 Chat Server]]
- [[wiki/entities/jina-embedding-server|Jina Embedding Server]]
- [[wiki/entities/nlweb|NLWEB]]
- [[wiki/concepts/api-gateway-pattern|Паттерн API Gateway]]
- [[wiki/concepts/sse-streaming|SSE-стриминг]]
