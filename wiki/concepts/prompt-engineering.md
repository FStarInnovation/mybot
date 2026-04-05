---
title: "Prompt Engineering"
type: concept
tags: [llm, prompts, architecture, pattern]
sources: [raw/reports/quadratic_prompts_report.md]
created: 2026-04-05
updated: 2026-04-05
---

# Prompt Engineering

Паттерны сборки и организации промптов для LLM, извлечённые из исследования Quadratic.

## Слои промпта

1. **System prompt** — внутренний контекст (роль, правила, ограничения)
2. **Tool context** — описания и подсказки для инструментов
3. **User context** — история диалога + данные из памяти
4. **User message** — текущий запрос

## Паттерны из Quadratic

### Разделение System/Prompt
Сообщения разделяются на системные (невидимые для пользователя) и prompt (видимый диалог). Это позволяет инжектировать контекст без загрязнения истории.

### Tool Prompts
Каждый инструмент имеет не только schema (description + parameters), но и отдельный `prompt` — детальные инструкции, инжектируемые в системный контекст. Это даёт модели больше информации о том, как и когда использовать инструмент.

### Glue Prompts
Provider-адаптеры добавляют маленькие промпты-связки после tool results. Это помогает модели корректно обработать результат инструмента.

### Context Assembly
Контекст собирается программно из нескольких источников (не один большой промпт, а композиция).

## See also

- [[concepts/model-routing|Model Routing]]
- [[concepts/provider-adapters|Provider Adapters]]
- [[summaries/quadratic-prompts-research|Quadratic Prompts Research]]
