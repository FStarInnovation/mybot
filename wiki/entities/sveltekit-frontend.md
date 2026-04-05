---
title: "SvelteKit Frontend"
type: entity
tags: [frontend, sveltekit, ui, pwa]
sources: [raw/reports/plan.md, raw/specs/DEPLOYMENT.md]
created: 2026-04-05
updated: 2026-04-05
---

# SvelteKit Frontend

Веб-клиент MyBot на SvelteKit с TanStack Query и PWA-поддержкой.

## Функции

- Чат-интерфейс для общения с AI-ассистентом
- Streaming-ответы (постепенная печать) или индикатор "typing..."
- Настройки профиля, история запросов
- Управление подписками на уведомления
- PWA — работа оффлайн, установка как приложение

## Стек

- **SvelteKit** — фреймворк
- **TanStack Query** — управление состоянием данных, кеширование
- **Vite** — сборщик
- **adapter-static** — статическая сборка в `public/` для Laravel

## Деплой

- Собирается как статика → копируется в `public/` Laravel
- Laravel Cloud обслуживает через nginx
- SPA fallback: `try_files` → `200.html`

## AG-UI (опционально)

Рассматривается интеграция AG-UI как улучшенного интерфейса для LLM-диалогов (подсветка шагов, визуализация источников, мульти-диалоги).

## See also

- [[entities/laravel-cloud|Laravel Cloud]]
- [[entities/sveltekit-adapter-static|SvelteKit Adapter Static]]
- [[concepts/spa-fallback|SPA Fallback]]
- [[summaries/deployment-guide|Deployment Guide]]
