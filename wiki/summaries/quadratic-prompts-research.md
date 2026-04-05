---
title: "Quadratic Prompts Research"
type: summary
source: raw/reports/quadratic_prompts_report.md
tags: [research, prompts, llm, quadratic]
created: 2026-04-05
updated: 2026-04-05
---

# Quadratic Prompts Research

Исследование архитектуры промптов в проекте Quadratic (spreadsheet с AI) — как они собирают, маршрутизируют и адаптируют промпты для разных LLM-провайдеров.

## Архитектура промптов

- Сообщения представлены как `ChatMessage[]` с полями `role` и `contextType`
- `getSystemPromptMessages()` разделяет на **системные** (внутренний контекст) и **prompt** (видимый диалог + tool results)
- Системный контекст собирается в `getQuadraticContext()`, инструменты — в `getToolUseContext()`

## Инструменты (Tools)

Каждый инструмент имеет:
- `description` + `parameters` — отправляются как schema
- `prompt` — внутренний контекст, инжектируется как toolUse context

## Provider Adapters

Адаптеры трансформируют сообщения под API провайдера:
- **Anthropic** (Claude)
- **OpenAI** (Chat Completions + Responses API)
- **Google GenAI** (Gemini)
- **AWS Bedrock**

Каждый адаптер добавляет "glue prompts" после tool results.

## Model Routing

Маршрутизация между Claude и GPT-4.1 в зависимости от типа задачи.

## Ключевые файлы

- `context.helper.ts` — сборка системного контекста
- `modelRouter.helper.ts` — выбор модели
- `message.helper.ts` — обработка сообщений
- `aiToolsSpec.ts` — каталог инструментов

## See also

- [[concepts/prompt-engineering|Prompt Engineering]]
- [[concepts/model-routing|Model Routing]]
- [[concepts/provider-adapters|Provider Adapters]]
