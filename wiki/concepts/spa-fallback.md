---
title: "SPA Fallback"
type: concept
tags:
  - spa
  - nginx
  - routing
  - architecture
sources:
  - wiki/raw/specs/DEPLOYMENT.md
created: 2026-04-05
updated: 2026-04-05
---

# SPA Fallback

Паттерн маршрутизации для одностраничных приложений (SPA), при котором все запросы, не соответствующие реальным файлам, перенаправляются на `index.html`.

## Реализация в проекте

В nginx-конфигурации (`cloud.yaml`) используется цепочка `try_files`:

```
try_files $uri $uri/ /index.html /index.php?$query_string;
```

Порядок разрешения запроса:

1. **`$uri`** — ищется точный файл (например, `/_app/immutable/start.js`)
2. **`$uri/`** — ищется директория
3. **`/index.html`** — фолбэк на SvelteKit SPA
4. **`/index.php?$query_string`** — фолбэк на Laravel (для API-маршрутов)

Эта цепочка обеспечивает корректную работу как статических ассетов SvelteKit, так и API-маршрутов Laravel, а также клиентской маршрутизации SPA.

## Кэширование статики

Для директории `/_app/` настроено долгосрочное кэширование (30 дней), так как ассеты SvelteKit содержат хэши в именах файлов и являются иммутабельными.

## See also

- [[wiki/summaries/deployment-guide.md|Руководство по деплою]]
- [[wiki/entities/laravel-cloud.md|Laravel Cloud]]
- [[wiki/concepts/csp-policy.md|Content Security Policy]]
