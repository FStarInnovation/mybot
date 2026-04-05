---
title: "Архитектурная схема системы"
type: summary
tags: [архитектура, инфраструктура, laravel-cloud, runpod, llm, nlweb]
sources:
  - wiki/raw/specs/schema_plan.md
created: 2026-04-05
updated: 2026-04-05
---

# Архитектурная схема системы

Система состоит из двух основных узлов: **Laravel Cloud** (веб-приложение и оркестрация) и **RunPod VPS** (LLM-инференс, поиск, эмбеддинги, данные). Между ними -- интернет-канал.

## Laravel Cloud (компоненты 1--5)

1. **Web/API Gateway** -- SvelteKit-фронт (SSR) + Sanctum/JWT-авторизация. Точка входа: `POST /chat` для приёма реплик пользователя.
2. **Memory Manager** -- двухуровневая память: Redis-Short (Upstash KV, 20 реплик, TTL 2 ч) для оперативного контекста и Supabase `chat_log` (pgvector) для долгосрочной памяти.
3. **Prompt Builder** -- собирает массив `messages[]` и прикладывает JSON-манифест tools для function-calling.
4. **LLM Proxy** -- стримит запросы `/completion` на RunPod-LLM.
5. **Scheduler & Horizon** -- cron каждые 2 дня в 03:00 запускает `ingest_runner`; очереди `queue:ingest` и `queue:alerts`.

## RunPod VPS (компоненты 6--9)

6. **LLM Service** -- Mistral 7B Instruct (Q4_0 GGUF) через llama-cpp-server на порту 11434. Только reasoning и function-calling.
7. **NLWEB / MCP Server** -- Tornado/FastAPI на порту 10051 (Basic Auth). Эндпоинты: `/tool/search_products`, `/tool/crawl_single_page`. Использует Upstash-кеш, Jina-эмбеддинги, k-NN через Neon Postgres + pgvector/Qdrant.
8. **Jina Embedder** -- jina-embeddings-v2-base-es на HTTP-порту 8100, пакетная обработка до 96 элементов за запрос.
9. **Data Tier** -- Neon Postgres (`raw_products`, pgvector), Qdrant (опционально при >5M SKU), Upstash Redis (кеш `search_*`, TTL 48 ч).

## Поток данных

Пользователь -> Laravel Cloud (Gateway -> Memory -> Prompt Builder -> LLM Proxy) -> RunPod (LLM -> NLWEB/MCP -> Jina -> Data Tier) -> ответ обратно по цепочке.

## See also

- [[wiki/entities/laravel-cloud.md|Laravel Cloud]]
- [[wiki/entities/runpod-vps.md|RunPod VPS]]
- [[wiki/entities/nlweb-mcp-server.md|NLWEB / MCP Server]]
- [[wiki/concepts/memory-management.md|Управление памятью]]
- [[wiki/concepts/function-calling-pipeline.md|Пайплайн function-calling]]
