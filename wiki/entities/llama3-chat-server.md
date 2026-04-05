---
title: Llama3 Chat Server
type: entity
tags: [llm, llama3, chat, runpod]
sources:
  - wiki/raw/specs/integratio_runpod.md
created: 2026-04-05
updated: 2026-04-05
---

# Llama3 Chat Server

LLM-сервис на базе Llama3, работающий на порту **1434** внутри RunPod-окружения. Используется для рассуждений (reasoning) и вызова функций (function-calling).

## Доступ

Запросы направляются через API Gateway: `POST /chat` на порту 10051.

## Формат запроса

Совместим с OpenAI Chat Completion API:

- `model` — идентификатор модели (по умолчанию `"llama"`)
- `messages` — массив сообщений с полями `role` и `content`
- `stream` — булевый флаг для SSE-стриминга (по умолчанию `false`)

## Формат ответа

Стандартный формат Chat Completion: объект с `choices[].message.content` и `finish_reason`.

При `stream: true` ответ приходит как Server-Sent Events с `content-type: text/event-stream`.

## See also

- [[wiki/summaries/runpod-integration|Интеграция Laravel с RunPod]]
- [[wiki/entities/runpod-api-gateway|API Gateway RunPod]]
- [[wiki/concepts/sse-streaming|SSE-стриминг]]
