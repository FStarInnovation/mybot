---
title: "WhatsApp Integration"
type: entity
tags: [integration, whatsapp, messaging]
sources: [raw/reports/plan.md]
created: 2026-04-05
updated: 2026-04-05
---

# WhatsApp Integration

Интеграция с WhatsApp Business API (через Twilio) для обмена сообщениями.

## Архитектура

- Webhook в Laravel для приёма сообщений
- Twilio SDK для отправки ответов
- Привязка номера телефона к пользователю системы

## Ограничения

- Требуются шаблоны для исходящих уведомлений (WhatsApp policy)
- Формат сообщений ограничен

## See also

- [[entities/telegram-integration|Telegram Integration]]
- [[entities/laravel-backend|Laravel Backend]]
- [[summaries/architecture-plan|Архитектурный план]]
