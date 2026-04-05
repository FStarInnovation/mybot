# CIAN: Инструкция по извлечению данных объявлений

## Оглавление

1. [Два способа получения данных](#два-способа-получения-данных)
2. [Способ 1: Search API (список объявлений)](#способ-1-search-api)
3. [Способ 2: SSR-парсинг HTML (одно объявление по ID)](#способ-2-ssr-парсинг-html)
4. [Защита CIAN и как её обходить](#защита-cian)
5. [Задание для разработчика: автоматизированный сборщик](#задание-для-разработчика)

---

## Два способа получения данных

| Задача | Способ | Метод |
|---|---|---|
| Поиск объявлений по фильтрам | Search API | `POST /search-offers/v2/search-offers-desktop/` |
| Получение полных данных одного объявления по ID | SSR HTML-парсинг | `GET /sale/flat/<ID>/` + извлечение `offerData` |

**Важно:** оба способа требуют браузерные куки CIAN. Без них WAF возвращает 403.

---

## Способ 1: Search API

### Эндпоинт

```
POST https://api.cian.ru/search-offers/v2/search-offers-desktop/
Content-Type: application/json
Cookie: <браузерные куки>
```

### Тело запроса

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

### Параметры jsonQuery

| Параметр | Формат | Описание | Примеры значений |
|---|---|---|---|
| `_type` | `string` | Тип сделки+объекта (строго lowercase!) | см. таблицу ниже |
| `region` | `{type:"terms", value:[...]}` | ID регионов | `[1]` Москва, `[4593]` МО, `[2]` СПб |
| `room` | `{type:"terms", value:[...]}` | Кол-во комнат | `[1]`, `[2]`, `[3]`, `[4]` (4 = 4+), `[9]` студия |
| `engine_version` | `{type:"term", value:2}` | Версия движка поиска | Всегда `2` |
| `page` | `{type:"term", value:N}` | Номер страницы | `1`, `2`, ... |
| `price` | `{type:"range", value:{gte:X, lte:Y}}` | Диапазон цены | `{gte:5000000, lte:15000000}` |
| `total_area` | `{type:"range", value:{gte:X, lte:Y}}` | Площадь, м² | `{gte:50, lte:100}` |
| `floor` | `{type:"range", value:{gte:X, lte:Y}}` | Этаж | `{gte:2, lte:10}` |
| `building_type` | `{type:"terms", value:[...]}` | Тип дома | `[1]` кирпич, `[2]` монолит, `[3]` панель |
| `is_first_floor` | `{type:"term", value:false}` | Не первый этаж | `false` |
| `is_last_floor` | `{type:"term", value:false}` | Не последний этаж | `false` |
| `is_by_homeowner` | `{type:"term", value:true}` | Только от собственников | `true` — без агентов и застройщиков |

### Допустимые значения `_type`

⚠️ **КРИТИЧНО:** типы пишутся **строго в нижнем регистре**. При неправильном регистре (например `suburbanSale` вместо `suburbansale`) API **молча подменяет тип на `flatrent`** без ошибки! Всегда проверяй поле `data.jsonQuery._type` в ответе.

| `_type` | Описание | Пример категории в ответе |
|---|---|---|
| `flatsale` | Продажа квартир | `flatSale` |
| `flatrent` | Аренда квартир | `flatRent` |
| `suburbansale` | Продажа загородной недвижимости | `cottageSale`, `houseSale`, `landSale` |
| `suburbanrent` | Аренда загородной недвижимости | `cottageRent`, `houseRent` |
| `commercialsale` | Продажа коммерческой недвижимости | `officeSale`, `shoppingSale` |
| `commercialrent` | Аренда коммерческой недвижимости | `officeRent`, `shoppingRent` |

**Не существующие типы** (фоллбэк на `flatrent`): `roomsale`, `roomrent`, `newbuildingsale`. Комнаты ищутся через `flatsale` с соответствующими фильтрами.

### Статистика объявлений на CIAN (март 2026)

| Категория | Москва | МО | СПб | Итого (3 рег.) |
|---|---|---|---|---|
| **Продажа квартир** | 52 545 | 36 268 | 25 279 | 114 092 |
| **Аренда квартир** | 79 283 | 35 072 | 66 798 | 181 153 |
| **Продажа загородной** | 5 073 | 72 927 | 1 445 | 79 445 |
| **Аренда загородной** | 1 094 | 12 107 | 529 | 13 730 |
| **Продажа коммерции** | 27 297 | 12 435 | 5 416 | 45 148 |
| **Аренда коммерции** | 45 411 | 17 674 | 10 782 | 73 867 |
| **ИТОГО** | | | | **~507 000** |

*Примечание: это только 3 топ-региона. По всей России значительно больше.*

### ID регионов (проверенные)

| ID | Регион |
|---|---|
| `1` | Москва |
| `4593` | Московская область |
| `2` | Санкт-Петербург |

### Ответ

```json
{
  "status": "ok",
  "data": {
    "offersSerialized": [ ... ],   // массив объявлений (обычно 28 шт на страницу)
    "offerCount": 28,              // кол-во на текущей странице
    "aggregatedCount": 12345,      // всего найдено
    "fullUrl": "...",
    "searchUuid": "...",
    ...
  }
}
```

Каждый элемент `offersSerialized` содержит полные данные объявления: цена, площадь, адрес, координаты, фото, описание, телефон.

### Типы продавцов (поле `user` в объявлении)

| `user.accountType` | `user.userType` | `fromDeveloper` | Описание |
|---|---|---|---|
| `"owner"` | `"homeowner"` | `false` | Собственник |
| `"agency"` | `"developer"` | `true` | Застройщик (новостройки) |
| `"specialist"` | `"realtor_based"` | `false` | Частный риелтор / агент |
| `"specialist"` | `"realtor_not_commerce"` | `false` | Риелтор (некоммерческий) |
| `"agency"` | `"realtor_based"` | `false` | Агентство недвижимости |
| `"managementCompany"` | `"realtor_based"` | `false` | Управляющая компания |
| `"rentDepartment"` | — | `false` | Отдел аренды |

**Фильтрация по типу продавца:**
- `is_by_homeowner: true` — только собственники (проверено: из 7941 → 1878 объявлений)
- `fromDeveloper: true` в ответе — признак застройщика/новостройки

### Структура продавцов квартир (flatsale) — март 2026

Данные по выборке 140 объявлений на регион (5 страниц):

| Тип продавца | Москва | МО | СПб | В среднем |
|---|---|---|---|---|
| **Застройщик** (`developer`) | 48 (34.3%) | 49 (35.0%) | 47 (33.6%) | **~34%** |
| **Частный риелтор** (`specialist`) | 65 (46.4%) | 65 (46.4%) | 67 (47.9%) | **~47%** |
| **Агентство** (`agency`) | 23 (16.4%) | 10 (7.1%) | 21 (15.0%) | **~13%** |
| **Собственник** (`owner`) | 0 (0%) | 0 (0%) | 0 (0%) | **0%** |
| **Прочие** | 4 (2.9%) | 16 (11.4%) | 5 (3.6%) | **~6%** |

*Примечание: собственники не попадают в топ выдачи — они расположены дальше (стр. 10+), т.к. не платят за продвижение.*

**Экстраполяция на все 114 092 объявления продажи квартир:**

| Тип | Доля | Оценка кол-ва |
|---|---|---|
| Собственники | ~15.6% (по `is_by_homeowner`) | ~17 800 |
| Застройщики (новостройки) | ~29% | ~33 000 |
| Частные риелторы | ~40% | ~45 600 |
| Агентства | ~11% | ~12 500 |
| Прочие | ~4% | ~5 100 |

**Вывод:** из ~85% юрлиц/агентов — **~34% приходится на застройщиков (новостройки)**, остальные ~51% — риелторы и агентства.

### Пагинация

- На одной странице ~28 объявлений
- `data.aggregatedCount` — общее количество
- Максимум ~54 страницы (ограничение CIAN)
- Итерировать: `page: 1`, `page: 2`, ... пока `offersSerialized` не пустой

---

## Способ 2: SSR-парсинг HTML

Для получения данных **одного конкретного объявления по ID**.

### Алгоритм

1. Загрузить HTML-страницу объявления
2. Найти самый большой `<script>` содержащий ID объявления
3. В нём найти подстроку `"offerData":{"offer":{`
4. Извлечь JSON-объект `offer` с помощью подсчёта скобок
5. Распарсить JSON

### URL страницы

```
https://www.cian.ru/sale/flat/<OFFER_ID>/
```

Примечание: CIAN может редиректить на поддомен (например `krasnogorsk.cian.ru`). Используй `www.cian.ru` — он сам перенаправит.

### Как найти данные в HTML

В HTML-странице есть `<script>` (~100-120 KB) содержащий SSR-стейт приложения. Внутри него:

```
..."offerData":{"offer":{...ПОЛНЫЙ JSON ОБЪЯВЛЕНИЯ...},"similar":[...],...}...
```

### Алгоритм извлечения (псевдокод)

```python
import re, json

html = requests.get(url, headers=headers, cookies=cookies).text

# Найти позицию offerData
idx = html.find('"offerData"')
# Найти начало объекта offer
offer_start = html.find('"offer":{', idx)
# Найти открывающую скобку объекта
brace_start = html.index('{', offer_start + 9)

# Подсчёт скобок для нахождения конца объекта
depth = 0
for i in range(brace_start, len(html)):
    if html[i] == '{':
        depth += 1
    elif html[i] == '}':
        depth -= 1
        if depth == 0:
            brace_end = i + 1
            break

offer_json = html[brace_start:brace_end]
offer = json.loads(offer_json)
```

### Структура объекта offer (SSR)

```
category         — "houseSale", "flatSale", ...
status           — "published"
dealType         — "sale", "rent"
offerType        — "flat", "suburban", "commercial"
id / cianId      — ID объявления
totalArea        — "248.0" (строка)
livingArea       — Жилая площадь
kitchenArea      — Кухня
floorNumber      — Этаж
description      — Полное описание
creationDate     — "2025-09-05T13:41:38.103"
editDate         — Дата последнего редактирования

bargainTerms:
  price          — 48500000 (число)
  currency       — "rur"
  mortgageAllowed
  saleType

geo:
  address        — [{fullName, id, name}, ...]
  coordinates    — {lat, lng}
  undergrounds   — [{name, travelTime, travelType}, ...]

building:
  materialType   — "brick", "monolith", "panel"
  floorsCount    — Этажность дома
  buildYear

land:            — (для загородки)
  area           — "10.0"
  areaUnitType   — "sotka"
  status         — "individualHousingConstruction"

phones:
  [{countryCode: "+7", number: "9855395423"}, ...]

photos:
  [{fullUrl, thumbnailUrl, isDefault}, ...]

userId / cianUserId — ID продавца
```

---

## Защита CIAN

### WAF (Yandex Cloud Application Load Balancer)

- Все API-запросы без браузерных куки получают `403` с заголовком `waf-verdict: block`
- WAF проверяет: куки сессии, User-Agent, возможно fingerprint
- Серверный эндпоинт `offer-card/v1/get-offer-card/` дополнительно блокирует CORS

### Куки, необходимые для работы

При первом заходе на cian.ru браузер получает набор куки:
- `_CIAN_GK` — основной идентификатор
- `session_region_id` — регион
- `cookie_agreement` — согласие на куки
- `_yasc` — Yandex Smart Captcha
- Другие сессионные куки

### Как получить куки

**Вариант A: Playwright (рекомендуется)**

```python
from playwright.sync_api import sync_playwright

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()
    page.goto('https://www.cian.ru/')
    page.wait_for_timeout(3000)  # дождаться загрузки и установки куки
    cookies = context.cookies()
    # Сохранить cookies для дальнейших запросов
```

**Вариант B: Из Chrome вручную (для отладки)**

1. Открыть cian.ru в Chrome
2. DevTools -> Application -> Cookies
3. Скопировать все куки для `.cian.ru`

### Rate Limiting

- Нет жёсткого rate limit на Search API, но при массовых запросах могут появиться капчи
- Рекомендация: пауза 2-5 сек между запросами
- При капче — обновить куки через Playwright

---

## Задание для разработчика

### Цель

Создать сервис автоматического сбора объявлений недвижимости с CIAN.

### Стек

- **Python 3.11+**
- **Playwright** — управление браузером и получение куки
- **httpx** или **aiohttp** — HTTP-запросы к API
- **PostgreSQL** — хранение данных
- Опционально: **Redis** — кеширование куки

### Архитектура

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│  Cookie      │────>│  Search API  │────>│  PostgreSQL   │
│  Manager     │     │  Collector   │     │  Storage      │
│  (Playwright)│     │  (httpx)     │     │              │
└──────────────┘     └──────┬───────┘     └──────────────┘
                           │
                     ┌─────▼────────┐
                     │  Offer Page  │
                     │  Parser      │
                     │  (SSR HTML)  │
                     └──────────────┘
```

### Модули

#### 1. Cookie Manager (`cookie_manager.py`)

**Задача:** получать и обновлять браузерные куки CIAN через Playwright.

- Запускать headless-браузер
- Заходить на `https://www.cian.ru/`
- Ждать загрузки (3 сек)
- Сохранять куки в файл/Redis
- Обновлять куки, если API вернул 403
- Метод: `get_cookies() -> dict`

#### 2. Search Collector (`search_collector.py`)

**Задача:** собирать списки объявлений через Search API.

- Формировать запрос к `POST /search-offers/v2/search-offers-desktop/`
- Передавать куки из Cookie Manager
- Пагинация: итерировать по страницам пока есть результаты
- Сохранять `cianId` каждого объявления и базовые данные (цена, площадь, адрес)
- Обрабатывать 403 — вызывать обновление куки
- Пауза 2-5 сек между запросами

**Входные параметры:**
```python
search_params = {
    "_type": "flatsale",
    "region": [1],              # Москва
    "room": [2, 3],             # 2-3 комнаты
    "price_min": 10000000,
    "price_max": 30000000,
    "is_by_homeowner": True,    # только от собственников
}
```

#### 3. Offer Parser (`offer_parser.py`)

**Задача:** получать полные данные одного объявления по ID через парсинг HTML.

- Загружать `https://www.cian.ru/sale/flat/<ID>/` с куки
- Находить `"offerData":{"offer":{` в HTML
- Извлекать JSON-объект подсчётом скобок `{}`
- Парсить JSON и возвращать структурированный объект
- Фоллбэк: если `offerData` не найден — парсить LD+JSON (`<script type="application/ld+json">`)

#### 4. Storage (`storage.py`)

**Задача:** сохранять данные в PostgreSQL.

**Таблица `cian_offers`:**

```sql
CREATE TABLE cian_offers (
    id BIGINT PRIMARY KEY,          -- cianId
    category VARCHAR(50),            -- flatSale, houseSale, ...
    deal_type VARCHAR(20),           -- sale, rent
    offer_type VARCHAR(20),          -- flat, suburban
    status VARCHAR(20),              -- published, removed
    price BIGINT,                    -- цена в рублях
    currency VARCHAR(5),
    total_area DECIMAL(10,2),
    living_area DECIMAL(10,2),
    kitchen_area DECIMAL(10,2),
    rooms_count INT,
    floor_number INT,
    floors_count INT,
    building_year INT,
    material_type VARCHAR(30),
    address TEXT,
    lat DECIMAL(10,6),
    lng DECIMAL(10,6),
    metro_name VARCHAR(100),
    metro_time INT,
    seller_type VARCHAR(30),         -- owner, specialist, agency, managementCompany
    seller_user_type VARCHAR(30),     -- homeowner, realtor_based, developer
    phone VARCHAR(20),
    description TEXT,
    photos JSONB,                    -- массив URL фото
    url TEXT,
    raw_json JSONB,                  -- полный оригинальный JSON
    created_at TIMESTAMP,            -- дата публикации на CIAN
    collected_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE INDEX idx_cian_offers_price ON cian_offers(price);
CREATE INDEX idx_cian_offers_area ON cian_offers(total_area);
CREATE INDEX idx_cian_offers_geo ON cian_offers(lat, lng);
```

### Сценарий работы

```
1. Cookie Manager получает свежие куки
2. Search Collector делает запрос с фильтрами
3. Для каждой страницы результатов:
   a. Сохраняет базовые данные из Search API в БД
   b. Для новых объявлений (которых нет в БД) — запускает Offer Parser
4. Offer Parser загружает HTML каждого нового объявления
5. Извлекает полный JSON из SSR-данных
6. Storage сохраняет полные данные в PostgreSQL
7. Повторять по расписанию (например, каждые 6 часов)
```

### Обработка ошибок

| Ситуация | Действие |
|---|---|
| API вернул 403 | Обновить куки через Cookie Manager, повторить запрос |
| Капча при загрузке страницы | Пауза 30 сек, обновить куки, повторить |
| `offerData` не найден в HTML | Попробовать LD+JSON, записать в лог |
| Timeout / 5xx | Повторить через 10 сек, максимум 3 попытки |
| Объявление снято (`status != published`) | Обновить статус в БД |

### Ограничения

- CIAN ограничивает выдачу ~54 страницы (~1500 объявлений) на один поисковый запрос
- Для полного покрытия нужно дробить запросы по: региону, кол-ву комнат, диапазону цен
- Телефоны в Search API могут быть скрыты — полные телефоны в SSR-данных HTML-страницы
- Search API не поддерживает поиск по ID объявления — только фильтры
- `_type` обязательно в нижнем регистре (`flatsale`, не `flatSale`) — иначе молчаливый фоллбэк на `flatrent`
- Параметр `region` обязателен — без него API возвращает 500

### Пример использования (итоговый)

```python
# Получить куки
cookies = cookie_manager.get_cookies()

# Поиск 2-3 комнатных квартир в Москве до 20 млн
offers = search_collector.search(
    type="flatsale",
    region=[1],
    rooms=[2, 3],
    price_max=20000000,
    cookies=cookies
)

# Получить детали конкретного объявления
offer = offer_parser.parse(offer_id=321543884, cookies=cookies)
print(offer['bargainTerms']['price'])  # 48500000
print(offer['geo']['coordinates'])     # {lat: 55.905, lng: 37.102}
print(offer['phones'])                 # [{countryCode: "+7", number: "9855395423"}]
```
