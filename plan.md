# 🧠 Farmabot Frontend Plan (SvelteKit + TanStack Query + AG-UI)

## 🎯 Цель
Создать современное, реактивное PWA-приложение с:
- Chat-интерфейсом на основе AG-UI
- Карточками товаров через TanStack Query
- Push-уведомлениями

---

## 🟩 ЭТАП 1 — Инициализация проекта

### Задачи:
- [ ] `npm create svelte@latest farmabot-ui`
- [ ] Установка зависимостей:
  - `@tanstack/svelte-query`
  - `vite-plugin-pwa`
  - `@copilotkit/react-core`
- [ ] Настройка `vite.config.ts` для PWA
- [ ] Добавление `QueryClientProvider` в `+layout.svelte`

---

## 🟦 ЭТАП 2 — Чат-интерфейс

### Задачи:
- [x] Создать компоненты чата:
  - `ChatInterface.svelte` — контейнер чата
  - `ChatMessage.svelte` — компонент сообщений
  - `ChatInput.svelte` — поле ввода
- [x] Реализовать минималистичный дизайн в стиле Gemini:
  - Округлые пузыри сообщений без аватарок
  - Зеленый пульсирующий индикатор "Online"
  - Динамические эффекты при взаимодействии
- [x] Добавить темную/светлую тему
- [x] Интегрировать с AG-UI и бэкендом:
  - Потоковая обработка ответов бота
  - Интерактивные действия через AG-UI tools

### Завершено:
- Создан базовый чат-интерфейс с современным дизайном
- Добавлены анимации и транзишены

---

## 🟨 ЭТАП 3 — TanStack Query + карточки товаров

### Задачи:
- [x] Создать `<ProductCard productId={id}>`
- [x] Использовать `createQuery` для загрузки JSON из API
- [x] Настроить API-эндпоинты для продуктов (`/api/products`, `/api/products/[id]`)
- [x] Реализовать эндпоинт для создания тестовых данных (`/api/setup-test-products`)
- [x] Создать страницу для тестирования TanStack Query (`/tanstack-test`)
- [ ] Вставка карточек из ответа AG-UI

### Завершено:
- Успешно интегрирован TanStack Query для получения данных о продуктах.
- Созданы и протестированы API-эндпоинты для управления продуктами.
- Реализована страница для демонстрации и тестирования функциональности TanStack Query.

---

## 🟥 ЭТАП 4 — Push-уведомления

### Задачи:
- [ ] Настроить Service Worker (через PWA-плагин)
- [ ] Подписка пользователя на уведомления
- [ ] Обработка перехода по пушу в `/p/:id`

---

## 📦 Файловая структура (Текущая)

/Users/zv/projects/mybot/frontend/src/
├── routes/
│   ├── chat/+page.svelte         # страница чата
│   ├── dashboard/+layout.svelte  # макет панели управления
│   ├── dashboard/+page.svelte    # страница панели управления
│   ├── +layout.svelte            # основной макет с ThemeToggle
│   └── +page.svelte              # главная страница
├── lib/
│   ├── components/
│   │   ├── ChatInterface.svelte  # контейнер чата
│   │   ├── ChatMessage.svelte    # компонент сообщений
│   │   ├── ChatInput.svelte      # поле ввода сообщений
│   │   ├── ThemeToggle.svelte    # переключатель темы
│   │   ├── PWA.svelte            # компонент PWA 
│   │   └── PWASplash.svelte      # заставка PWA
│   └── stores/
│       └── theme.ts              # хранилище для темы

---

## 📌 Интеграции
- AG-UI работает через `/api/chat`
- ProductCard получает данные из `/api/product/:id`
- Все данные кэшируются TanStack Query
- PWA + Push работают через `vite-plugin-pwa`

---

## 📈 Метрики
- Время ответа чата < 1.5с
- 100% данных через AG-UI stream
- Покрытие карточек товаров ≥ 90%
- Подписка на пуши ≥ 30% активных пользователей