---
title: "Upstash Redis"
type: entity
tags: [memory, redis, short-term, cache]
sources: [raw/reports/plan.md]
created: 2026-04-05
updated: 2026-04-05
---

# Upstash Redis

Облачный serverless Redis для хранения краткосрочной памяти диалогов.

## Назначение

- Хранение последних N сообщений диалога (user + assistant реплики)
- Привязка по ключу `session:{user_id}:recent_messages`
- TTL на записях — автоочистка через несколько часов/дней

## Принцип работы

1. При новом запросе — Laravel извлекает из Redis последние N реплик
2. Эти реплики включаются в prompt для LLM как контекст диалога
3. После получения ответа — Redis обновляется (новая пара, удаление старых)

## Особенности

- Serverless — не требует управления подключениями
- Низкая задержка — выбирать регион близко к Laravel/RunPod
- Не хранит полную историю — только скользящее окно
- Мониторинг через Upstash dashboard

## See also

- [[entities/qdrant|Qdrant]] — долгосрочная память
- [[concepts/memory-management|Memory Management]]
- [[entities/laravel-backend|Laravel Backend]]
