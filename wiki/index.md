# Wiki Index

Каталог всех страниц вики.

---

## Summaries

- [CIAN Аналитика рынка](summaries/cian-analytics.md) — ~507K объявлений, доли собственников/агентов/застройщиков, региональная разбивка
- [CIAN API — Исследование](summaries/cian-api-research.md) — Search API, SSR-парсинг, статистика объявлений, структура продавцов
- [Quadratic Prompts Research](summaries/quadratic-prompts-research.md) — исследование промптов Quadratic: сборка, routing, provider adapters

---

## Entities

### CIAN
- [CIAN Search API](entities/cian-search-api.md) — POST /search-offers/, jsonQuery параметры, типы _type, пагинация
- [CIAN SSR Parser](entities/cian-ssr-parser.md) — извлечение offerData из HTML, алгоритм подсчёта скобок, структура данных

### Финуслуги
- [Финуслуги Agent](entities/finuslugi-agent.md) — агентская платформа: страховые и финансовые продукты, база знаний

### NLWeb / MCP
- [NLWeb](entities/nlweb.md) — NLWeb для работы LLM с веб-данными
- [NLWeb MCP Server](entities/nlweb-mcp-server.md) — MCP-интерфейс для NLWeb

---

## Concepts

### CIAN
- [CIAN: Авторизация и WAF](concepts/cian-auth.md) — браузерные куки, WAF 403, ротация, Playwright

### Финуслуги — Процессы
- [Оформление каско](concepts/finuslugi-kasko-oformlenie.md) — 5 шагов: расчёт → выбор СК → данные → осмотр → оплата

### LLM / Промпты
- [Prompt Engineering](concepts/prompt-engineering.md) — паттерны сборки промптов: system/tool/glue/context
- [Model Routing](concepts/model-routing.md) — маршрутизация между LLM-моделями по типу задачи
- [Provider Adapters](concepts/provider-adapters.md) — adapter pattern для мультипровайдерности

---

## Raw Sources

- `raw/articles/finuslugi-kasko-oformlenie.md` — Урок Школы Агента: оформление полиса каско
- `raw/reports/CIAN_Analytics_slides.md` — Содержимое презентации аналитики (9 слайдов)
- `raw/reports/cian_api.md` — CIAN API карточки объявления (микросервисы)
- `raw/reports/cian_scraper_guide.md` — Гайд по сбору данных CIAN
- `raw/reports/cian_offer_321543884.json` — Пример данных одного объявления
- `raw/reports/quadratic_prompts_report.md` — Исследование промптов Quadratic (285KB)
- `raw/reports/quadratic_llm_architecture_report.md` — Архитектура LLM Quadratic (14KB)
