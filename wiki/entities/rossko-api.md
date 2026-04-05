---
title: "Rossko API"
type: entity
tags: [api, auto-parts, integration, soap, rest]
sources: [../.claude/skills/rossko/SKILL.md]
created: 2026-04-05
updated: 2026-04-05
---

# Rossko API

API поставщика автозапчастей Rossko. Три раздельных API для разных задач.

## API

| # | API | URL | Протокол | Назначение |
|---|-----|-----|----------|------------|
| 1 | SOAP API v2.1 | `api.rossko.ru/service/v2.1/{METHOD}` | SOAP XML | Поиск по артикулу, заказы, доставки |
| 2 | OEM-каталог | `oem-catalog.rossko.ru/api/` | REST JSON | VIN → OE-номера деталей ТО (Laximo) |
| 3 | ProductCard | `productcard.rossko.ru/api/` | REST JSON | Полные данные аналогов: артикул, цена, наличие, сроки |

## Авторизация

- **SOAP**: два ключа KEY1/KEY2 в каждом запросе
- **OEM + ProductCard**: заголовки `Authorization-Session` (токен из cookie `auth` на himki.rossko.ru), `Authorization-Domain`, `source`
- Токен с префиксом `u-` = авторизованный, `0-`/`q-`/`y-` = гостевой (не работает)
- При 401 → обновить токен из cookie

## Подбор деталей ТО по VIN (4 шага)

1. **VIN → каталог**: `GET /api/car/search?query={VIN}` → id, catalog, ssd, brand
2. **Группы ТО**: `GET /api/catalog/quick/groups?ssd=...` → плоский массив, найти "Детали для ТО"
3. **OE-номера**: `GET /api/catalog/quick/detail?...&groupId=...` → parts[], фильтр `match='t'`, ключ `nsiPartId`
4. **Аналоги**: `GET /api/Product/Crosses/{nsiPartId}?...` → crosses[] с brandName, partNumber, stocks[{basePrice, inventory, deliveryInterval}]

## Связанный проект

Проект **carbotmax** (`/Users/zv/projects/carbotmax`):
- `src/services/rossko-catalog.service.ts` — VIN → ТО-детали
- `src/services/rossko-search.service.ts` — SOAP поиск
- `src/commands/parts.ts` — команда подбора в боте

## See also

- [[entities/laravel-backend|Laravel Backend]]
- [[summaries/architecture-plan|Архитектурный план]]
