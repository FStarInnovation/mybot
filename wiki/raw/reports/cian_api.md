# CIAN API — Карточка объявления (offer card)

**Базовый URL:** `https://api.cian.ru/`
**Приложение:** `frontend-offer-card` (версия `fc9e3bb9`)
**JS-бандлы:** `https://static.cdn-cian.ru/frontend/frontend-offer-card/`

---

## Архитектурная особенность

Основные данные объявления (цена, площадь, адрес, описание, фото) **встроены в HTML через SSR** — отдельного API-запроса при загрузке страницы не происходит. Дополнительные данные догружаются асинхронно через REST API.

---

## API карточки объявления

### Избранное

```
GET /v1/get-folders-for-offer/
```
- **Микросервис:** `favorites`
- **Описание:** Папки избранного, в которых сохранено объявление
- **Статусы:** 200, 400

---

### Чат-бот

```
POST /v1/get-offer-suggestions/
Content-Type: application/json
```
- **Микросервис:** `cian-chat-bot`
- **Описание:** Подсказки ответов чат-бота по данному объявлению

---

### Карта / Инфраструктура

```
GET /v1/get-infrastructure-microfrontend/
```
- **Микросервис:** `map-search-frontend`
- **Описание:** Загружает микрофронтенд карты с инфраструктурой вокруг объявления

---

### Оценка и история цен

```
GET /v1/nearby-houses-explanation/?offerId=<ID>
```
- **Микросервис:** `valuation-offer-history`
- **Описание:** Информация о похожих домах рядом, используемых для оценки стоимости
- **Параметры:** `offerId`
- **Статусы:** 200, 400

---

### Чат

```
GET /v1/chat-messages-hints-by-offer/?dealType=&offerCategory=&offerType=&shortHints=
```
- **Микросервис:** `chats`
- **Описание:** Готовые подсказки для чата с продавцом
- **Параметры:** `dealType`, `offerCategory`, `offerType`, `shortHints`
- **Статусы:** 200, 400

---

### Реклама

```
POST /v1/get-banner-enrichments/
```
- **Микросервис:** `ad-banner`
- **Описание:** Данные для обогащения рекламных баннеров

---

### Сравнение объявлений

```
POST /v2/add-offer-to-comparison/
POST /v1/delete-offer-from-comparison/
```
- **Микросервис:** `offers-comparison`
- **Описание:** Добавление/удаление объявления из списка сравнения

---

### Контакты / Телефон

```
/v1/check-contacts-access
```
- **Описание:** Проверка доступа к контактным данным продавца (телефон)

---

### Модальные окна

```
/v1/open-modal
```
- **Описание:** Открытие модального окна (например, для показа телефона)

---

### Прочие микрофронтенды

```
GET /v1/get-early-access-paywall-microfrontend/
GET /v1/get-save-search-microfrontend/
GET /v2/get-chats-widget/
```

---

## Служебные / аналитические API

| Эндпоинт | Описание |
|---|---|
| `POST /uxfeedback/v1/get-uxfeedback-event-name-for-show-now-desktop/` | UX-фидбек (опросники) |
| `POST /utr/v1/u/t` | Трекинг действий пользователя |
| `/ab-use/` | A/B тесты |
| `/ebc-analytics/` | Аналитика событий |
| `/sopr-experiments/` | Эксперименты (sopr) |

---

## Рабочий API поиска объявлений (проверено)

### `POST /search-offers/v2/search-offers-desktop/`

**Статус: РАБОТАЕТ (200 OK)**
- Требует браузерные куки (CORS разрешён с `*.cian.ru`)
- Из curl без куки — `403 WAF Block`
- Из браузера с `credentials: 'include'` — работает

```
POST https://api.cian.ru/search-offers/v2/search-offers-desktop/
Content-Type: application/json

{
  "jsonQuery": {
    "_type": "flatsale",
    "region": {"type": "terms", "value": [4593]},
    "room": {"type": "terms", "value": [3]},
    "engine_version": {"type": "term", "value": 2},
    "page": {"type": "term", "value": 1}
  }
}
```

### Параметры `jsonQuery`

| Поле | Тип | Описание | Пример |
|---|---|---|---|
| `_type` | string | Тип сделки | `"flatsale"`, `"flatrent"` |
| `region` | terms | ID региона | `[4593]` — Москва и МО |
| `room` | terms | Кол-во комнат | `[1]`, `[2]`, `[3]` |
| `engine_version` | term | Версия движка | `2` |
| `page` | term | Страница | `1` |

### Структура ответа

```json
{
  "status": "ok",
  "data": {
    "offersSerialized": [...],  // массив объявлений
    "offerCount": 28,
    "aggregatedCount": 12345,
    "jsonQuery": {...},
    "queryString": "...",
    "breadcrumbs": [...],
    "quickLinks": [...],
    "seoData": {...},
    "fullUrl": "...",
    "searchUuid": "...",
    "searchRequestId": "...",
    "mlSearchSessionGuid": "...",
    "mlRankingGuid": "...",
    "collections": [...],
    ...
  }
}
```

### Поля объявления (`offersSerialized[n]`)

```
id/cianId          — ID объявления
title              — Заголовок
dealType           — Тип сделки ("sale")
offerType          — Тип объекта ("flat")
category           — Категория
roomsCount         — Кол-во комнат
totalArea          — Общая площадь
livingArea         — Жилая площадь
kitchenArea        — Площадь кухни
floorNumber        — Этаж
fullUrl            — Полная ссылка

bargainTerms       — Условия сделки:
  price            — Цена
  priceRur         — Цена в рублях
  currency         — Валюта
  mortgageAllowed  — Ипотека доступна
  saleType         — Тип продажи

geo                — География:
  address          — Адрес
  coordinates      — Координаты
  undergrounds     — Метро
  highways         — Шоссе
  districts        — Районы

building           — Здание:
  buildYear        — Год постройки
  floorsCount      — Этажность
  materialType     — Материал
  type             — Тип дома
  parking          — Парковка

photos             — Фото
phones             — Телефоны (могут быть скрыты)
description        — Описание
added/addedTimestamp — Дата публикации
newbuilding        — Данные новостройки
decoration         — Отделка
isApartments       — Апартаменты ли
isPremium          — Премиум-объявление
formattedFullPrice — Цена форматированная
formattedShortPrice — Цена краткая
```

---

## Серверный API карточки объявления (заблокирован)

### `POST /offer-card/v1/get-offer-card/`

```
POST https://api.cian.ru/offer-card/v1/get-offer-card/
Content-Type: application/json

{"offerId": 321470767}
```

- Из curl: `403 WAF Block`
- Из браузера (fetch): `CORS Error` — нет заголовка `Access-Control-Allow-Origin`
- **Вывод:** эндпоинт только для серверного SSR (server-to-server), не доступен ни из браузера, ни через curl

---

## Как работать с API

### Вариант 1: Из браузера (fetch с куки)

```js
const r = await fetch('https://api.cian.ru/search-offers/v2/search-offers-desktop/', {
  method: 'POST',
  credentials: 'include',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({
    jsonQuery: {
      _type: "flatsale",
      region: {type: "terms", value: [4593]},
      room: {type: "terms", value: [3]},
      engine_version: {type: "term", value: 2},
      page: {type: "term", value: 1}
    }
  })
});
const data = await r.json();
console.log(data.data.offersSerialized);
```

### Вариант 2: Playwright / Puppeteer

Открыть cian.ru, получить куки, затем делать API-запросы с ними.

### Вариант 3: Парсинг HTML (SSR)

```
GET https://krasnogorsk.cian.ru/sale/flat/<OFFER_ID>/
```

Данные объявления встроены в HTML. Искать `window.__initialState__` или `<script>` с JSON-данными.

---

## ID тестового объявления

- **ID:** `321470767`
- **URL:** `https://krasnogorsk.cian.ru/sale/flat/321470767/`
- **Тип:** Продажа, 3-комнатная квартира, 72.9м², Красногорск, Павшинская ул., 2
