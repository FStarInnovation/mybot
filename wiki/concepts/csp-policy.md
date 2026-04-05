---
title: "Content Security Policy"
type: concept
tags:
  - security
  - csp
  - nginx
  - sveltekit
sources:
  - wiki/raw/specs/DEPLOYMENT.md
created: 2026-04-05
updated: 2026-04-05
---

# Content Security Policy (CSP)

Механизм безопасности браузера, ограничивающий источники загрузки скриптов, стилей, изображений и других ресурсов. В проекте настраивается через nginx-заголовок `Content-Security-Policy`.

## Конфигурация в проекте

```
default-src 'self'; script-src 'self' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;
```

### Директивы

| Директива | Значение | Причина |
|---|---|---|
| `default-src` | `'self'` | Загрузка ресурсов только с собственного домена |
| `script-src` | `'self' 'unsafe-eval'` | SvelteKit требует `eval` для работы |
| `style-src` | `'self' 'unsafe-inline'` | Инлайн-стили SvelteKit |
| `img-src` | `'self' data:` | Поддержка data-URI для изображений |

## Особенности SvelteKit

SvelteKit в режиме разработки и при определённых конфигурациях использует `eval()` для загрузки модулей, поэтому `unsafe-eval` необходим в `script-src`. Без этой директивы браузер блокирует выполнение скриптов SvelteKit.

## Диагностика

Ошибки CSP отображаются в консоли браузера. При блокировке скриптов SvelteKit приложение не загрузится.

## See also

- [[wiki/summaries/deployment-guide.md|Руководство по деплою]]
- [[wiki/concepts/spa-fallback.md|SPA Fallback]]
- [[wiki/entities/laravel-cloud.md|Laravel Cloud]]
