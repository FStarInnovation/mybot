---
title: "RunPod VPS"
type: entity
tags: [инфраструктура, runpod, llm, gpu, inference]
sources:
  - wiki/raw/specs/schema_plan.md
created: 2026-04-05
updated: 2026-04-05
---

# RunPod VPS

Серверный узел для LLM-инференса, поиска по продуктам, генерации эмбеддингов и хранения данных.

## Компоненты

### LLM Service
- Модель: **Mistral 7B Instruct** (квантизация Q4_0, формат GGUF)
- Сервер: llama-cpp-server, порт 11434
- Назначение: reasoning и function-calling (без генерации контента напрямую)

### NLWEB / MCP Server
- Tornado/FastAPI, порт 10051, Basic Auth
- Эндпоинты: `/tool/search_products`, `/tool/crawl_single_page`
- Кеширование через Upstash
- Эмбеддинги через Jina server
- Векторный поиск: Neon Postgres (pgvector) / Qdrant

### Jina Embedder
- Модель: jina-embeddings-v2-base-es
- HTTP-порт 8100
- Пакетная обработка: до 96 элементов за запрос

### Data Tier
- **Neon Postgres** -- таблица `raw_products`, расширение pgvector
- **Qdrant** -- опционально, при масштабе >5M SKU
- **Upstash Redis** -- кеш ключей `search_*`, TTL 48 часов

## See also

- [[wiki/summaries/schema-plan.md|Архитектурная схема системы]]
- [[wiki/entities/laravel-cloud.md|Laravel Cloud]]
- [[wiki/entities/nlweb-mcp-server.md|NLWEB / MCP Server]]
