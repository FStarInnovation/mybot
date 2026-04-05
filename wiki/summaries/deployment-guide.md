---
title: "Руководство по деплою SvelteKit + Laravel"
type: summary
tags:
  - deploy
  - sveltekit
  - laravel
  - laravel-cloud
  - nginx
sources:
  - wiki/raw/specs/DEPLOYMENT.md
created: 2026-04-05
updated: 2026-04-05
---

# Руководство по деплою SvelteKit + Laravel

## Архитектура

Проект состоит из двух частей: **SvelteKit** (фронтенд) с `adapter-static`, который собирается в директорию `public/` Laravel, и **Laravel** (бэкенд), обслуживающий API и статические файлы. Деплой осуществляется через **Laravel Cloud** с автоматическим деплоем из GitHub.

## Сборка фронтенда

1. В `svelte.config.js` настроен `adapter-static`: страницы и ассеты выгружаются в `../public`, с фолбэком на `index.html`.
2. Сборка запускается командой `npm run build` из директории `frontend/`.
3. После сборки в `public/` должен появиться `index.html`, а в `public/_app/immutable/` — JS/CSS ассеты. Вложенная структура `_app/_app/` недопустима.
4. Результат коммитится и пушится в GitHub, что запускает автодеплой.

## Настройка Laravel Cloud

- **Health Check**: настроен на `/health` через `cloud.yaml`, маршрут в `routes/web.php` и файл `public/health.php`.
- **Nginx**: в `cloud.yaml` задана inline-конфигурация — CSP-заголовки (разрешён `unsafe-eval` для SvelteKit), кэширование `/_app/` на 30 дней, SPA-фолбэк через `try_files`.

## Проверка деплоя

После достижения статуса **Running** в Laravel Cloud проверяются эндпоинты `/health`, `/_app/immutable/entry/start.*.js` и `/chat` — все должны возвращать `200 OK`. Консоль браузера проверяется на отсутствие ошибок.

## Устранение неполадок

| Проблема | Решение |
|---|---|
| 404 на статику | Проверить файлы в `public/_app/`, `.gitignore`, nginx-конфиг |
| Health Check 404 | Проверить маршрут `/health`, файл `public/health.php`, `cloud.yaml` |
| CSP блокирует SvelteKit | Добавить `unsafe-eval` в `script-src`, `unsafe-inline` в `style-src` |
| Бесконечная загрузка SPA | Проверить доступность API и ошибки fetch/XHR в консоли |

## See also

- [[wiki/entities/laravel-cloud.md|Laravel Cloud]]
- [[wiki/entities/sveltekit-adapter-static.md|SvelteKit Adapter Static]]
- [[wiki/concepts/spa-fallback.md|SPA Fallback]]
- [[wiki/concepts/csp-policy.md|Content Security Policy]]
