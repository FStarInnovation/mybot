---
title: "Архитектурный план MyBot"
type: summary
source: raw/reports/plan.md
tags: [architecture, plan, overview]
created: 2026-04-05
updated: 2026-04-05
---

# Архитектурный план MyBot

Мастер-документ, описывающий полную архитектуру AI-ассистента MyBot — от ролей команд до пошагового плана реализации.

## Основная идея

MyBot — AI-ассистент с чат-интерфейсом, доступный через веб (SvelteKit), Telegram и WhatsApp. Использует LLaMA3 на RunPod для генерации ответов с поддержкой tool calling.

## Архитектура (5 слоев)

1. **Клиенты** — SvelteKit SPA, Telegram Bot, WhatsApp Business API
2. **Backend** — Laravel Cloud: API-шлюз, контроллер диалога, интерфейс к памяти, инструменты для LLM, сервис уведомлений
3. **AI-платформа** — RunPod: LLaMA3, модуль tool-calling (агент), Jina Embeddings, Tornado API server
4. **Система памяти** — Upstash Redis (краткосрочная), Neon/Qdrant (долгосрочная, векторная)
5. **Мониторинг** — Langfuse: телеметрия запросов, usage токенов, вызовы инструментов

## Обработка запроса (8 шагов)

1. Получение запроса от клиента
2. Извлечение последних N сообщений из Redis (краткосрочная память)
3. Семантический поиск в Qdrant (долгосрочная память) — по embedding запроса через Jina
4. Формирование prompt: сообщение + история + контекст из памяти + системные инструкции
5. LLaMA3 генерирует ответ, при необходимости вызывает инструменты (parse_url, search_memory...)
6. Tornado перехватывает вызовы инструментов, выполняет через Laravel API, возвращает результат модели
7. Пост-обработка: форматирование, сохранение в память, логирование в Langfuse
8. Доставка ответа клиенту (HTTP/WebSocket/Telegram/WhatsApp)

## Распределение логики

| Laravel (Backend) | LLaMA3 (AI) |
|---|---|
| Аутентификация, биллинг, права | Генерация ответов |
| Вызовы внешних API | Решение какие инструменты вызвать |
| Парсинг веб-страниц (Puppeteer) | Обработка неструктурированных данных |
| Управление состоянием/памятью | Гибкая логика диалога |
| Предобработка/постобработка | Семантический анализ |

## Принцип минимального хранения

- Redis: только последние N сообщений с TTL
- Долгосрочная память: только summary и ключевые факты, не сырые диалоги
- Embedding + метаданные вместо полных текстов
- Периодическая очистка неактуальных данных

## Plan реализации (10 этапов)

1. Базовая инфраструктура (Laravel, RunPod, Redis, Qdrant)
2. Скелет backend + frontend (заглушки)
3. Первичная интеграция LLM (без инструментов)
4. Краткосрочная память (Redis контекст диалога)
5. Долгосрочная память (Qdrant + Jina embeddings)
6. Tool calling (парсинг, поиск по памяти)
7. Telegram/WhatsApp интеграция
8. Мониторинг (Langfuse)
9. AG-UI (опционально)
10. Оптимизация и масштабирование

## See also

- [[entities/laravel-cloud|Laravel Cloud]]
- [[entities/runpod-vps|RunPod VPS]]
- [[entities/jina-embedding-server|Jina Embeddings]]
- [[concepts/memory-management|Memory Management]]
- [[concepts/function-calling-pipeline|Tool Calling Pipeline]]
- [[concepts/api-gateway-pattern|API Gateway Pattern]]
- [[summaries/deployment-guide|Deployment Guide]]
- [[summaries/runpod-integration|RunPod Integration]]
