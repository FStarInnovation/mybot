---
title: "Telegram Integration"
type: entity
tags: [integration, telegram, messaging, bot]
sources: [raw/reports/plan.md]
created: 2026-04-05
updated: 2026-04-05
---

# Telegram Integration

Интеграция с Telegram Bot API для приёма/отправки сообщений.

## Архитектура

- Webhook `/webhook/telegram` в Laravel
- Входящее сообщение трансформируется в формат внутреннего запроса
- Привязка Telegram-аккаунта к пользователю системы
- Ответ отправляется через Telegram Bot API

## Ограничения

- Лимит длины сообщения — длинные ответы разбиваются
- Ограничение скорости отправки
- Markdown-форматирование (ограниченный HTML)
- Платные уведомления должны соответствовать политикам Telegram

## See also

- [[entities/whatsapp-integration|WhatsApp Integration]]
- [[entities/laravel-backend|Laravel Backend]]
- [[summaries/architecture-plan|Архитектурный план]]
