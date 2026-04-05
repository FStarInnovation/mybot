---
title: Паттерн API Gateway
type: concept
tags: [architecture, api-gateway, proxy, microservices]
sources:
  - wiki/raw/specs/integratio_runpod.md
created: 2026-04-05
updated: 2026-04-05
---

# Паттерн API Gateway

## Описание

В архитектуре RunPod-интеграции применяется паттерн API Gateway: единый FastAPI-прокси на порту 10051, через который проходят все запросы от Laravel к внутренним сервисам.

## Преимущества в данном контексте

- **Единая точка входа** — Laravel-приложению не нужно знать адреса и порты внутренних сервисов (1434, 1435, 8000).
- **Унификация формата** — Gateway нормализует запросы и ответы.
- **Упрощение безопасности** — достаточно защитить один порт вместо нескольких.
- **Возможность добавления middleware** — аутентификация, rate limiting, логирование на уровне шлюза.

## Реализация

Gateway реализован на FastAPI и маршрутизирует запросы по URL-путям:

- `/chat` -> Llama3 (порт 1434)
- `/embedding` -> Jina (порт 1435)
- `/ask` -> NLWEB (порт 8000)
- `/tool/*` -> внутренние инструменты и микросервисы

## Рекомендации по безопасности

- API-ключи или JWT-токены для продакшена
- Rate limiting на стороне Laravel
- Валидация входных данных перед отправкой

## See also

- [[wiki/entities/runpod-api-gateway|API Gateway RunPod]]
- [[wiki/summaries/runpod-integration|Интеграция Laravel с RunPod]]
