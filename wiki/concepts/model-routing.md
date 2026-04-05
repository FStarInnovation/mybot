---
title: "Model Routing"
type: concept
tags: [llm, routing, multi-model, pattern]
sources: [raw/reports/quadratic_prompts_report.md]
created: 2026-04-05
updated: 2026-04-05
---

# Model Routing

Паттерн маршрутизации запросов между разными LLM-моделями в зависимости от типа задачи.

## Идея

Не все задачи требуют самой мощной (и дорогой) модели. Router анализирует запрос и выбирает оптимальную модель.

## Реализация в Quadratic

- `modelRouter.helper.ts` — решает Claude vs GPT-4.1
- Критерии: тип задачи, сложность, требуемые capabilities
- Каждая модель имеет свой adapter для трансформации формата

## Trade-offs

| Подход | Плюсы | Минусы |
|---|---|---|
| Одна модель | Простота, предсказуемость | Переплата за простые запросы |
| Router | Оптимизация стоимости/скорости | Сложность, возможные ошибки роутинга |
| Каскад (fallback) | Надёжность | Увеличенная задержка при fallback |

## See also

- [[concepts/prompt-engineering|Prompt Engineering]]
- [[concepts/provider-adapters|Provider Adapters]]
- [[summaries/quadratic-prompts-research|Quadratic Prompts Research]]
