---
title: "Laravel Cloud"
type: entity
tags: [laravel, cloud, hosting, deploy, gateway, scheduler, horizon, инфраструктура]
sources:
  - wiki/raw/specs/DEPLOYMENT.md
  - wiki/raw/specs/schema_plan.md
created: 2026-04-05
updated: 2026-04-05
---

# Laravel Cloud

Платформа для хостинга и автоматического деплоя Laravel-приложений. Используется в проекте как основная среда выполнения и оркестрации.

## Ключевые особенности в проекте

- **Автодеплой из GitHub**: при пуше в репозиторий Laravel Cloud автоматически разворачивает новую версию.
- **Конфигурация через `cloud.yaml`**: определяет health check, nginx inline-конфигурацию и другие параметры.
- **Health Check**: настроен на эндпоинт `/health` для проверки работоспособности приложения.
- **Nginx inline-конфигурация**: позволяет задавать кастомные правила прямо в `cloud.yaml` -- CSP-заголовки, кэширование, SPA-фолбэк.
- **URL проекта**: `mybot-main-3ztkqf.laravel.cloud`

## Архитектурные компоненты (из схемы)

### Web/API Gateway
- SvelteKit-фронт с серверным рендерингом (SSR)
- Авторизация через Sanctum/JWT
- Основной эндпоинт: `POST /chat`

### Memory Manager
- **Redis-Short** (Upstash KV-Store) -- хранение последних 20 реплик, TTL 2 часа
- **Supabase chat_log** (pgvector) -- долгосрочная память с векторным поиском

### Prompt Builder
- Формирует массив `messages[]` для LLM
- Прикладывает JSON-манифест доступных tools

### LLM Proxy
- Проксирует `/completion`-запросы на RunPod в режиме стриминга

### Scheduler & Horizon
- Cron: каждые 2 дня в 03:00 запускает `ingest_runner`
- Очереди: `queue:ingest`, `queue:alerts`

## Статусы деплоя

Деплой считается успешным при достижении статуса **Running**.

## See also

- [[wiki/summaries/schema-plan.md|Архитектурная схема системы]]
- [[wiki/summaries/deployment-guide.md|Руководство по деплою]]
- [[wiki/entities/runpod-vps.md|RunPod VPS]]
- [[wiki/entities/sveltekit-adapter-static.md|SvelteKit Adapter Static]]
- [[wiki/concepts/memory-management.md|Управление памятью]]
- [[wiki/concepts/spa-fallback.md|SPA Fallback]]
