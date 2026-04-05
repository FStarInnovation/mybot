---
title: "NLWEB / MCP Server"
type: entity
tags: [mcp, nlweb, api, search, fastapi, tornado]
sources:
  - wiki/raw/specs/schema_plan.md
created: 2026-04-05
updated: 2026-04-05
---

# NLWEB / MCP Server

Сервис поиска и обработки продуктовых данных, развёрнутый на RunPod VPS. Реализует протокол MCP (Model Context Protocol) для интеграции с LLM через function-calling.

## Технические детали

- **Фреймворк**: Tornado / FastAPI
- **Порт**: 10051
- **Аутентификация**: Basic Auth

## Эндпоинты (tools)

| Эндпоинт | Назначение |
|---|---|
| `/tool/search_products` | Поиск продуктов по запросу |
| `/tool/crawl_single_page` | Краулинг отдельной страницы |

## Зависимости

- **Upstash Redis** -- кеширование результатов (GET/SET)
- **Jina Embedder** -- генерация векторных представлений запросов
- **Neon Postgres + pgvector** -- k-NN поиск по эмбеддингам
- **Qdrant** -- альтернативный векторный движок (опционально)

## See also

- [[wiki/summaries/schema-plan.md|Архитектурная схема системы]]
- [[wiki/entities/runpod-vps.md|RunPod VPS]]
- [[wiki/concepts/function-calling-pipeline.md|Пайплайн function-calling]]
