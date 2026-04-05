---
title: API Gateway RunPod
type: entity
tags: [runpod, api-gateway, fastapi, proxy]
sources:
  - wiki/raw/specs/integratio_runpod.md
created: 2026-04-05
updated: 2026-04-05
---

# API Gateway RunPod

FastAPI-прокси, работающий на порту **10051**, который служит единой точкой входа для всех сервисов RunPod-окружения.

## Роутинг

Gateway проксирует запросы к внутренним сервисам:

| Эндпоинт | Целевой сервис |
|-----------|---------------|
| `POST /chat` | Llama3 Chat Server (порт 1434) |
| `POST /embedding` | Jina Embedding Server (порт 1435) |
| `POST /ask` | NLWEB (порт 8000) — SSE-стриминг |
| `POST /tool/search_products` | Внутренний поиск товаров |
| `POST /tool/crawl_single_page` | Краулер веб-страниц |
| `POST /tool/mcp` | MCP-микросервис бизнес-задач |
| `POST /tool/ask` | NLWEB — синхронный ответ |

## Подключение из Laravel

```
RUNPOD_API_URL=http://<runpod-ip>:10051
RUNPOD_TIMEOUT=120
```

## HTTP-коды ответов

- `200` — успех
- `400` — некорректные параметры
- `404` — эндпоинт не найден
- `500` — внутренняя ошибка сервера

## See also

- [[wiki/summaries/runpod-integration|Интеграция Laravel с RunPod]]
- [[wiki/entities/llama3-chat-server|Llama3 Chat Server]]
- [[wiki/entities/jina-embedding-server|Jina Embedding Server]]
- [[wiki/entities/nlweb|NLWEB]]
- [[wiki/concepts/api-gateway-pattern|Паттерн API Gateway]]
