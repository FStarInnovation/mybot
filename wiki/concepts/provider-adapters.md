---
title: "Provider Adapters"
type: concept
tags: [llm, adapter-pattern, multi-provider]
sources: [raw/reports/quadratic_prompts_report.md]
created: 2026-04-05
updated: 2026-04-05
---

# Provider Adapters

Паттерн адаптеров для работы с разными LLM-провайдерами через единый интерфейс.

## Проблема

Каждый LLM API имеет свой формат: Anthropic Messages, OpenAI Chat Completions, Google GenAI, AWS Bedrock. Прямая интеграция с каждым создаёт дублирование и хрупкость.

## Решение

Adapter pattern: внутренний формат сообщений → трансформация под каждый провайдер.

## Реализация в Quadratic

- `anthropic.helper.ts` — адаптер Anthropic (Claude)
- `openai.chatCompletions.helper.ts` — OpenAI Chat Completions
- `openai.responses.helper.ts` — OpenAI Responses API
- `genai.helper.ts` — Google GenAI (Gemini)
- `bedrock.helper.ts` — AWS Bedrock

Каждый адаптер:
1. Трансформирует `ChatMessage[]` → формат провайдера
2. Добавляет "glue prompts" после tool results
3. Обрабатывает ответ → единый внутренний формат

## Применение в MyBot

Если планируется поддержка нескольких моделей (LLaMA3 + облачные API), адаптеры позволят переключаться без изменения бизнес-логики.

## See also

- [[concepts/model-routing|Model Routing]]
- [[concepts/prompt-engineering|Prompt Engineering]]
- [[summaries/quadratic-prompts-research|Quadratic Prompts Research]]
