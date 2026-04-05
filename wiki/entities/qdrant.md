---
title: "Qdrant"
type: entity
tags: [memory, vector-db, long-term, semantic-search]
sources: [raw/reports/plan.md]
created: 2026-04-05
updated: 2026-04-05
---

# Qdrant

Векторная база данных для долгосрочной памяти ассистента. Альтернатива — Supabase с pgvector.

## Назначение

- Хранение embedding'ов: факты, summary диалогов, документы
- Семантический поиск по запросу пользователя (cosine similarity)
- Изоляция данных по `user_id` в метаданных

## Схема данных

- **embedding** (vector) — Jina Embedding вектор
- **text** — исходный текст фрагмента
- **метаданные** — user_id, источник, теги, timestamp

## Поток записи

1. Laravel/ML извлекает важную информацию из диалога
2. Jina Embedding генерирует вектор
3. Вектор + текст + метаданные сохраняются в Qdrant
4. Хранятся только сжатые summary и ключевые факты, не сырые диалоги

## Поток поиска

1. Embedding запроса пользователя через Jina
2. Qdrant ищет top-N ближайших векторов
3. Результаты фильтруются по порогу similarity
4. Релевантные фрагменты включаются в prompt LLM

## See also

- [[entities/upstash-redis|Upstash Redis]] — краткосрочная память
- [[entities/jina-embedding-server|Jina Embeddings]]
- [[concepts/memory-management|Memory Management]]
- [[entities/laravel-backend|Laravel Backend]]
