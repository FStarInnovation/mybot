---
title: "SvelteKit Adapter Static"
type: entity
tags:
  - sveltekit
  - frontend
  - static-site
  - build
sources:
  - wiki/raw/specs/DEPLOYMENT.md
created: 2026-04-05
updated: 2026-04-05
---

# SvelteKit Adapter Static

Адаптер для SvelteKit (`adapter-static`), генерирующий статические файлы из SvelteKit-приложения. В проекте используется для сборки фронтенда в директорию `public/` Laravel.

## Конфигурация

В `svelte.config.js` адаптер настроен следующим образом:

- `pages`: `../public` — HTML-страницы выгружаются в корень `public/`
- `assets`: `../public` — статические ассеты также в `public/`
- `fallback`: `index.html` — фолбэк для SPA-маршрутизации

## Результат сборки

После выполнения `npm run build` в директории `frontend/`:

- `public/index.html` — точка входа SPA
- `public/_app/immutable/` — JS и CSS ассеты с иммутабельными хэшами в именах

## Известные проблемы

- Вложенная структура `_app/_app/` указывает на ошибку конфигурации адаптера.
- Файлы сборки должны быть закоммичены в Git, так как Laravel Cloud не выполняет сборку фронтенда.

## See also

- [[wiki/summaries/deployment-guide.md|Руководство по деплою]]
- [[wiki/entities/laravel-cloud.md|Laravel Cloud]]
- [[wiki/concepts/spa-fallback.md|SPA Fallback]]
