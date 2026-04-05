---
title: Jina Embedding Server
type: entity
tags: [jina, embeddings, vectors, runpod]
sources:
  - wiki/raw/specs/integratio_runpod.md
created: 2026-04-05
updated: 2026-04-05
---

# Jina Embedding Server

Сервис генерации векторных эмбеддингов, работающий на порту **1435** внутри RunPod-окружения. Использует модель `jina-embeddings-v2-base-es`.

## Доступ

Через API Gateway: `POST /embedding` на порту 10051.

## Формат запроса

- `input` — массив строк для генерации эмбеддингов
- `model` — идентификатор модели (например, `"jina-embeddings-v2-base-es"`)

## Формат ответа

Массив `data`, где каждый элемент содержит поле `embedding` — вектор чисел с плавающей запятой.

## Применение

- Семантический поиск (similarity search)
- Хранение векторов для последующего поиска

## See also

- [[wiki/summaries/runpod-integration|Интеграция Laravel с RunPod]]
- [[wiki/entities/runpod-api-gateway|API Gateway RunPod]]
