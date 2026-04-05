---
title: NLWEB
type: entity
tags: [nlweb, search, api, runpod]
sources:
  - wiki/raw/specs/integratio_runpod.md
created: 2026-04-05
updated: 2026-04-05
---

# NLWEB

Веб-интерфейс и API-сервис, работающий на порту **8000** внутри RunPod-окружения. Предоставляет чат-подобные ответы с возможностью внутреннего вызова инструментов.

## Режимы доступа

### SSE-стриминг (через `/ask`)

`POST /ask` на API Gateway — проксирует к NLWEB `/api/v1/ask`, возвращает Server-Sent Events. Поддерживает сервисные маршруты: `search`, `analyze`.

### Синхронный режим (через `/tool/ask`)

`POST /tool/ask` на API Gateway — возвращает единый JSON-ответ (не стриминговый).

## Формат ответа

```json
{
  "answer": "Текст ответа...",
  "sources": [...]
}
```

## Параметры запроса

- `messages` — массив сообщений в формате OpenAI Chat (обязателен хотя бы один user-message)
- `service` — маршрут сервиса (`search` или `analyze`, по умолчанию `search`)

## See also

- [[wiki/summaries/runpod-integration|Интеграция Laravel с RunPod]]
- [[wiki/entities/runpod-api-gateway|API Gateway RunPod]]
- [[wiki/concepts/sse-streaming|SSE-стриминг]]
