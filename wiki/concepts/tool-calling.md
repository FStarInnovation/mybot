---
title: "Tool Calling"
type: concept
tags: [architecture, llm, tool-calling, agent]
sources: [raw/reports/plan.md]
created: 2026-04-05
updated: 2026-04-05
---

# Tool Calling

Паттерн, позволяющий LLM вызывать внешние инструменты для получения данных.

## Как работает

1. LLM генерирует ответ и определяет, что нужны дополнительные данные
2. Формирует запрос инструмента (JSON с именем функции и параметрами)
3. Tornado/агент перехватывает запрос и выполняет действие
4. Результат возвращается LLM для продолжения генерации
5. Цепочка может включать несколько последовательных вызовов

## Доступные инструменты

| Инструмент | Назначение | Реализация |
|---|---|---|
| `parse_url(url)` | Парсинг веб-страницы | Puppeteer + BeautifulSoup |
| `search_memory(query)` | Поиск в долгосрочной памяти | Jina Embedding → Qdrant |
| Калькулятор | Вычисления | Функция на Tornado |
| Внешние API | Погода, новости и т.д. | Laravel эндпоинты |

## Безопасность (Sandbox)

- LLM не выполняет произвольный код
- Laravel проверяет допустимость каждого вызова (whitelist URL, разрешённые команды)
- Обработка ошибок и таймаутов инструментов
- Дублирование: если LLM не вызвала поиск, Laravel может сделать это превентивно

## Универсальный интерфейс

Обобщённый эндпоинт `/api/tool/{tool_name}` — добавление новых инструментов без изменения логики модели.

## See also

- [[concepts/function-calling-pipeline|Function Calling Pipeline]]
- [[entities/laravel-backend|Laravel Backend]]
- [[entities/llama3-chat-server|LLaMA3 Chat Server]]
- [[entities/runpod-vps|RunPod VPS]]
