---
title: "Пайплайн function-calling"
type: concept
tags: [function-calling, llm, tools, prompt-builder, mcp]
sources:
  - wiki/raw/specs/schema_plan.md
created: 2026-04-05
updated: 2026-04-05
---

# Пайплайн function-calling

Архитектурный паттерн, описывающий цепочку вызовов от пользовательского запроса до выполнения инструментов (tools) и возврата результата.

## Этапы

1. **Приём реплики** -- Gateway принимает `POST /chat` от пользователя
2. **Сборка контекста** -- Memory Manager загружает историю из Redis и Supabase
3. **Формирование промпта** -- Prompt Builder собирает `messages[]` и прикладывает JSON-манифест доступных tools
4. **Инференс** -- LLM Proxy стримит запрос на RunPod LLM Service (Mistral 7B)
5. **Вызов инструмента** -- LLM возвращает function call, система маршрутизирует его на NLWEB/MCP Server
6. **Выполнение** -- MCP Server обрабатывает запрос (поиск продуктов, краулинг), используя эмбеддинги и векторную БД
7. **Ответ** -- результат возвращается в LLM для финальной генерации ответа пользователю

## Ключевые решения

- LLM Service выполняет **только** reasoning и function-calling -- без прямой генерации контента
- Манифест tools передаётся в формате JSON при каждом запросе через Prompt Builder
- NLWEB/MCP Server предоставляет tools по протоколу MCP с Basic Auth

## See also

- [[wiki/summaries/schema-plan.md|Архитектурная схема системы]]
- [[wiki/entities/nlweb-mcp-server.md|NLWEB / MCP Server]]
- [[wiki/entities/runpod-vps.md|RunPod VPS]]
- [[wiki/concepts/memory-management.md|Управление памятью]]
