---
title: "CIAN SSR Parser"
type: entity
tags: [cian, scraping, ssr, html-parsing]
sources: [raw/reports/cian_scraper_guide.md]
created: 2026-04-05
updated: 2026-04-05
---

# CIAN SSR Parser

Способ получения полных данных одного объявления CIAN по его ID через парсинг SSR HTML.

## Зачем

CIAN встраивает все данные объявления в HTML через SSR — отдельного API нет. Парсим `<script>` с состоянием приложения.

## URL

```
GET https://www.cian.ru/sale/flat/<OFFER_ID>/
Cookie: <браузерные куки CIAN>
```

*CIAN может редиректить на поддомен (например `krasnogorsk.cian.ru`). Использовать `www.cian.ru` — сам перенаправит.*

## Алгоритм извлечения

1. Загрузить HTML страницы
2. Найти самый большой `<script>` (~100-120 KB) содержащий ID объявления
3. Найти подстроку `"offerData":{"offer":{`
4. Извлечь JSON-объект методом **подсчёта скобок** (не регулярками — JSON вложенный)
5. Распарсить JSON → полные данные объявления

## Структура данных в SSR

```
..."offerData":{"offer":{
  "id": 321543884,
  "price": {"value": 12500000, "currency": "RUB"},
  "totalArea": 65.5,
  "roomsCount": 2,
  "address": {...},
  "geo": {"lat": 55.75, "lng": 37.61},
  "photos": [...],
  "description": "...",
  "phones": [...],
  "user": {"accountType": "owner", "userType": "homeowner"},
  ...
},...}...
```

## Типы продавцов

| `user.accountType` | `user.userType` | Описание |
|---|---|---|
| `"owner"` | `"homeowner"` | Собственник |
| `"agency"` | `"developer"` | Застройщик |
| `"specialist"` | `"realtor_based"` | Частный риелтор |
| `"agency"` | `"realtor_based"` | Агентство |

## See also

- [[entities/cian-search-api|CIAN Search API]]
- [[concepts/cian-auth|CIAN: Авторизация и WAF]]
- [[summaries/cian-api-research|CIAN API — Исследование]]
