---
title: "Memory Tiers"
type: concept
tags: [architecture, memory, redis, qdrant, pattern]
sources: [raw/reports/plan.md]
created: 2026-04-05
updated: 2026-04-05
---

# Memory Tiers

Двухуровневая система памяти AI-ассистента: краткосрочная + долгосрочная.

## Мотивация

Система работает "от запроса пользователя" — минимизирует постоянное хранение, подтягивая контекст on-demand. Это обеспечивает масштабируемость: новые пользователи не приводят к экспоненциальному росту хранимых данных.

## Tier 1: Краткосрочная память (Upstash Redis)

- **Что хранит**: последние N реплик диалога (user + assistant)
- **TTL**: несколько часов/дней
- **Ключ**: `session:{user_id}:recent_messages`
- **Когда используется**: всегда — при каждом запросе

## Tier 2: Долгосрочная память (Qdrant / Supabase pgvector)

- **Что хранит**: summary диалогов, ключевые факты, документы (embedding + текст + метаданные)
- **Когда используется**: по необходимости — если запрос связан с прошлым контекстом
- **Поиск**: семантический через Jina Embeddings (cosine similarity)

## Принцип минимального хранения

- Не сырые диалоги, а сжатые summary
- Embedding + метаданные вместо полных текстов
- Периодическая очистка неактуальных данных
- TTL для Redis, архивация для векторной БД

## Поток данных

```
Запрос → Redis (всегда) → Qdrant (по необходимости) → prompt LLM
                                                          ↓
Ответ ← LLM ← пост-обработка ← Redis update ← Qdrant update (async)
```

## See also

- [[entities/upstash-redis|Upstash Redis]]
- [[entities/qdrant|Qdrant]]
- [[entities/jina-embedding-server|Jina Embeddings]]
- [[concepts/memory-management|Memory Management]]
- [[summaries/architecture-plan|Архитектурный план]]
