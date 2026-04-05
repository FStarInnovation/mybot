---
title: "CIAN: Авторизация и WAF"
type: concept
tags: [cian, auth, waf, scraping, cookies]
sources: [raw/reports/cian_scraper_guide.md]
created: 2026-04-05
updated: 2026-04-05
---

# CIAN: Авторизация и WAF

Защита CIAN от автоматических запросов и способы работы с ней.

## Проблема

CIAN использует WAF (Web Application Firewall). Без браузерных куки → **403 Forbidden**.

## Решение: браузерные куки

Оба метода (Search API и SSR-парсинг) требуют куки реального браузера с авторизованной или активной сессией CIAN.

## Способы получения куки

1. **Вручную** — открыть cian.ru в браузере, скопировать куки из DevTools
2. **Playwright/Puppeteer** — запустить headless-браузер, получить куки программно
3. **Rotating proxies + браузерный fingerprint** — для масштабного сбора

## Рекомендации

- Ротировать куки при получении 403
- Добавлять задержки между запросами
- Использовать реалистичные User-Agent заголовки
- При парсинге SSR — обрабатывать редиректы на региональные поддомены

## See also

- [[entities/cian-search-api|CIAN Search API]]
- [[entities/cian-ssr-parser|CIAN SSR Parser]]
- [[summaries/cian-api-research|CIAN API — Исследование]]
