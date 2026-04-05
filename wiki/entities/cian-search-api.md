---
title: "CIAN Search API"
type: entity
tags: [cian, api, search, real-estate]
sources: [raw/reports/cian_scraper_guide.md]
created: 2026-04-05
updated: 2026-04-05
---

# CIAN Search API

REST API для поиска объявлений недвижимости на CIAN по фильтрам.

## Эндпоинт

```
POST https://api.cian.ru/search-offers/v2/search-offers-desktop/
Content-Type: application/json
Cookie: <браузерные куки CIAN>
```

## Тело запроса

```json
{
  "jsonQuery": {
    "_type": "flatsale",
    "region": {"type": "terms", "value": [1]},
    "room": {"type": "terms", "value": [2, 3]},
    "engine_version": {"type": "term", "value": 2},
    "page": {"type": "term", "value": 1}
  }
}
```

## Параметры jsonQuery

| Параметр | Формат | Описание |
|---|---|---|
| `_type` | `string` | Тип сделки+объекта (строго lowercase!) |
| `region` | `{type:"terms", value:[...]}` | ID регионов |
| `room` | `{type:"terms", value:[...]}` | Кол-во комнат: 1,2,3,4=4+, 9=студия |
| `engine_version` | `{type:"term", value:2}` | Всегда 2 |
| `page` | `{type:"term", value:N}` | Номер страницы |
| `price` | `{type:"range", value:{gte:X, lte:Y}}` | Диапазон цены |
| `total_area` | `{type:"range", value:{gte:X, lte:Y}}` | Площадь, м² |
| `is_by_homeowner` | `{type:"term", value:true}` | Только собственники |
| `building_type` | `{type:"terms", value:[...]}` | 1=кирпич, 2=монолит, 3=панель |

## ⚠️ Критично: типы `_type`

Пишутся **строго в нижнем регистре**. При ошибке регистра API **молча подменяет на `flatrent`** без ошибки!

| `_type` | Описание |
|---|---|
| `flatsale` | Продажа квартир |
| `flatrent` | Аренда квартир |
| `suburbansale` | Продажа загородной |
| `suburbanrent` | Аренда загородной |
| `commercialsale` | Продажа коммерческой |
| `commercialrent` | Аренда коммерческой |

Всегда проверяй `data.jsonQuery._type` в ответе!

## ID регионов

| ID | Регион |
|---|---|
| `1` | Москва |
| `4593` | Московская область |
| `2` | Санкт-Петербург |

## Ответ

```json
{
  "status": "ok",
  "data": {
    "offersSerialized": [...],  // ~28 объявлений на страницу
    "offerCount": 28,
    "aggregatedCount": 12345   // всего найдено
  }
}
```

## Пагинация

- ~28 объявлений на страницу
- Максимум ~54 страницы (ограничение CIAN)
- Итерировать пока `offersSerialized` не пустой

## See also

- [[entities/cian-ssr-parser|CIAN SSR Parser]]
- [[concepts/cian-auth|CIAN: Авторизация и WAF]]
- [[summaries/cian-api-research|CIAN API — Исследование]]
