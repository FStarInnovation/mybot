---
title: "Laravel Backend"
type: entity
tags: [backend, api, laravel, php]
sources: [raw/reports/plan.md]
created: 2026-04-05
updated: 2026-04-05
---

# Laravel Backend

Основной backend-сервер MyBot на Laravel 12, развёрнутый на Laravel Cloud.

## Роль в архитектуре

- **API-шлюз**: принимает запросы от SvelteKit (HTTP/WebSocket) и вебхуки от Telegram/WhatsApp
- **Контроллер диалога**: формирует контекст, обращается к LLM, возвращает ответ
- **Интерфейс к памяти**: Redis (краткосрочная) и Qdrant (долгосрочная)
- **Инструменты для LLM**: эндпоинты `parse_url`, `search_memory` и другие
- **Сервис уведомлений**: отправка через Telegram/WhatsApp/email

## Ключевые компоненты

- Laravel Horizon — очереди задач (async jobs для LLM, парсинг)
- Laravel Loop — WebSocket поддержка для real-time
- Guzzle — HTTP-клиент для обращения к RunPod/Tornado API
- Predis — клиент для Upstash Redis

## Принципы

- Laravel — "источник правды" и sandbox для AI
- Все критичные операции (auth, платежи, API) только через Laravel
- LLM не имеет прямого доступа к внешним системам — только через Laravel-эндпоинты
- Инструменты проверяют допустимость запросов (whitelist URL, разрешённые команды)

## See also

- [[entities/laravel-cloud|Laravel Cloud]]
- [[entities/runpod-vps|RunPod VPS]]
- [[concepts/api-gateway-pattern|API Gateway Pattern]]
- [[concepts/function-calling-pipeline|Tool Calling Pipeline]]
- [[entities/upstash-redis|Upstash Redis]]
- [[entities/qdrant|Qdrant]]
