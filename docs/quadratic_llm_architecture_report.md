# Отчёт: как в Quadratic сделана подключаемость LLM + где лежат промпты

Источник: https://github.com/quadratichq/quadratic (ветка `qa`).

Цель отчёта:
- Понять, какие инженерные решения в Quadratic позволяют подключать разные LLM/провайдеры с минимальными изменениями.
- Зафиксировать, какие типы промптов используются и где они находятся.
- Выделить паттерны, которые можно перенести в Farmabot.

---

## 1) Краткий вывод (executive summary)

В Quadratic хорошо реализован **"контрактный" слой между продуктом и LLM**:
- Модели описаны декларативно (единый реестр конфигураций).
- Провайдеры подключаются через небольшое число адаптеров, а большая часть логики общая.
- Инструменты (tools/function calling) описаны как единый контракт: **schema + prompt + ограничения** в одном месте.
- Промпты не размазаны по проекту: есть явные фабрики контекста (system/tool-use), плюс prompts на каждый tool.

Результат: можно добавлять модели/провайдеры предсказуемо, с хорошей типобезопасностью и минимальным риском регрессий.

---

## 2) Основные компоненты архитектуры LLM

### 2.1 Единый реестр моделей (Single Source of Truth)

Файл:
- `quadratic-shared/ai/models/AI_MODELS.ts`

Ключевая идея:
- Все модели описываются через `MODELS_CONFIGURATION: { [key in AIModelKey]: AIModelConfig }`.

Что хранится в конфиге (по сути capability matrix):
- `provider` (каким адаптером обслуживать: `openai`, `anthropic`, `vertexai`, `bedrock`, `open-router` и т.д.)
- `model` (строка, которую реально отправляют в API провайдера)
- `canStream`, `canStreamWithToolCalls`
- `thinking`, `thinkingToggle`, `thinkingBudget`
- `imageSupport`
- `promptCaching`, `strictParams`, `supportsReasoning`, `serviceTier`, sampling параметры
- `backupModelKey` (фоллбек-модель)
- тарифы/стоимость: `rate_per_million_*`

Сильная сторона:
- подключаемость провайдеров становится в первую очередь задачей конфигурации, а не переписывания логики.


### 2.2 Жёсткая типизация провайдеров и model keys

Файл:
- `quadratic-shared/typesAndSchemasAI.ts`

Ключевая идея:
- `AIModelKey` и `provider` не произвольные строки, а `zod`-enum/union.
- Это защищает от опечаток и «внезапных» моделей в рантайме.

Пример формата ключей:
- `openai:gpt-5-codex`
- `azure-openai:gpt-4.1`
- `vertexai:gemini-2.5-flash:thinking-toggle-on`
- `bedrock:us.deepseek.r1-v1:0`


### 2.3 Провайдеры как SDK-клиенты (wiring)

Файл:
- `quadratic-api/src/ai/providers.ts`

Ключевая идея:
- В одном месте создаются клиенты SDK и задаются `apiKey`, `baseURL`, заголовки.
- Для многих провайдеров используется OpenAI SDK с `baseURL` (xAI/Baseten/Fireworks/OpenRouter) → быстрое подключение OpenAI-compatible API.


### 2.4 Единый диспетчер запросов в LLM

Файл:
- `quadratic-api/src/ai/handler/ai.handler.ts`

Как работает:
- Получает `modelKey`.
- Через type-guards из `quadratic-shared/ai/helpers/model.helper.ts` определяет провайдера.
- Вызывает нужный handler:
  - `anthropic.handler.ts`
  - `bedrock.handler.ts`
  - `genai.handler.ts`
  - `openai.responses.handler.ts` или `openai.chatCompletions.handler.ts`

Сильные стороны:
- единая точка обработки ошибок, логов, Sentry.
- фоллбек на `backupModelKey` в проде.


### 2.5 Унификация streaming и tool calls

Ключевые файлы:
- `quadratic-api/src/ai/helpers/openai.responses.helper.ts`
- `quadratic-api/src/ai/helpers/openai.chatCompletions.helper.ts`
- `quadratic-api/src/ai/helpers/anthropic.helper.ts`
- `quadratic-api/src/ai/helpers/genai.helper.ts`
- `quadratic-api/src/ai/helpers/bedrock.helper.ts`

Что важно:
- Для каждого провайдера есть слой:
  - сборки аргументов API из внутренних сообщений + tools
  - парсинга обычного ответа
  - парсинга stream ответа
- На выходе приводят к общему формату (внутренний `ParsedAIResponse` + SSE в ответ пользователю).


### 2.6 Управление стоимостью

Файлы:
- `quadratic-shared/ai/models/AI_RATES.ts`
- `quadratic-api/src/ai/helpers/usage.helper.ts`

Ключевая идея:
- стоимость считается из usage токенов + cache read/write.
- тарифы лежат рядом с конфигом моделей.

---

## 3) Инструменты (tools): где лежит контракт и почему это круто

### 3.1 Single Source of Truth для tools

Файл:
- `quadratic-shared/ai/specs/aiToolsSpec.ts`

Что там есть для каждого tool:
- `description` (короткое, для tool definition)
- `parameters` (JSON Schema для function calling)
- `responseSchema` (zod для валидации аргументов tool call)
- `prompt` (подробные внутренние инструкции, как и когда использовать tool)
- `sources` (какие "роли"/режимы агента могут использовать tool)
- `aiModelModes` (в каких режимах модели tool доступен)

Сильная сторона:
- Инструменты можно расширять без рассинхронизации фронта/бэка: spec переиспользуется.


### 3.2 Упорядочивание tools

Файл:
- `quadratic-api/src/ai/helpers/tools.ts`

Ключевая идея:
- order инструментов задаётся перечислением `AITool`.
- затем есть helper, который возвращает tools в фиксированном порядке.


### 3.3 Ограничение доступности tools

Идея:
- tool-list для конкретного запроса фильтруется по `source` (например `AIAnalyst`, `AIAssistant`, `ModelRouter`) и по `aiModelMode`.
- это даёт контролируемую поверхность возможностей: разные режимы/экраны продукта дают разные tool-наборы.

---

## 4) Промпты: какие они и где лежат

### 4.1 "System" / контекст приложения

Файл:
- `quadratic-api/src/ai/helpers/context.helper.ts`

Что делает:
- `getQuadraticContext(...)` собирает внутренний контекст:
  - описание роли ассистента (внутри спредшита)
  - правила поведения (не угадывать данные, быть агентом, использовать tools)
  - подключение docs-блоков (QuadraticDocs, PythonDocs, JavascriptDocs, FormulaDocs, ConnectionDocs…)
- `getAIRulesContext(...)` добавляет team/user правила
- `getAILanguagesContext(...)` ограничивает языки ответа

Важно:
- Эти сообщения помечены как internal (“do not quote”), и дальше попадают в `system`-часть при сборке провайдер-специфичных сообщений.


### 4.2 Tool-use prompt (инструкции по tools)

Файл:
- `quadratic-api/src/ai/helpers/context.helper.ts`

Что делает:
- `getToolUseContext(source, modelKey)`:
  - динамически перечисляет tools
  - подставляет `aiToolsSpec[tool].prompt` для каждого доступного tool

Итого:
- prompts на tools не размазаны, а централизованы в `aiToolsSpec.ts`.


### 4.3 Prompts на каждый tool

Файл:
- `quadratic-shared/ai/specs/aiToolsSpec.ts`

Особенность:
- `prompt` у tool обычно содержит конкретные operational правила (например, правила размещения таблиц и code cells, запреты на spill, требование получать схемы БД перед SQL и т.д.).


### 4.4 ModelRouter prompt (автовыбор модели)

Файл:
- `quadratic-api/src/ai/helpers/modelRouter.helper.ts`

Как устроено:
- Это отдельная "мини-задача": выбрать один из вариантов (`Claude` или `4.1`) строго в заданном формате.
- Результат возвращается через tool call `set_ai_model`.
- Маппинг "коротких" имён в реальные `AIModelKey` лежит в:
  - `quadratic-shared/ai/specs/aiToolsSpec.ts` (`MODELS_ROUTER_CONFIGURATION`).

---

## 5) Управление контекстом и сообщениями

Файл:
- `quadratic-shared/ai/helpers/message.helper.ts`

Ключевые моменты:
- Разделение сообщений по назначению: system/internal/userPrompt/toolResult.
- `getSystemPromptMessages` отделяет system-внутренности от диалога.
- `replaceOldGetToolCallResults` предотвращает рост контекста (схлопывает старые тяжёлые tool results после N вызовов).

---

## 6) Что именно сделано особенно хорошо (как инженерные практики)

- Единый реестр моделей + строгие ключи (`AIModelKey`).
- Capability matrix: стриминг/инструменты/изображения/thinking задаются конфигом, а не ветвлениями в коде.
- Tools как единый контракт: JSON Schema + zod + prompt.
- Чёткое разделение responsibilities:
  - shared: типы/спеки/модели
  - api: провайдеры/адаптеры/парсеры/маршрутизация
- Контроль стоимости и телеметрия usage.
- Контроль размера контекста.
- Фоллбек модели в проде.

---

## 7) Идеи, которые можно перенести в Farmabot

Минимальный набор, который даёт максимальный эффект:
- Ввести `ModelRegistry` (единый список моделей) + capability flags.
- Ввести `ModelKey` и `Provider` как enum (хотя бы на уровне приложения) и валидировать входящие значения.
- Разделить:
  - wiring клиентов провайдеров
  - маршрутизацию запросов
  - форматирование/парсинг ответов
- Завести `ToolSpec` как единый контракт (schema + prompt + разрешения).
- Добавить контекст-слой (system/tool-use), который собирается функциями (а не склеивается в контроллере).
- Сделать fallback цепочку (и метрики/стоимость).

---

## 8) Ограничения/компромиссы подхода

- Модели перечислены явно (enum’ы) → нужно обновлять при добавлении новых моделей.
- Большие prompts (docs + tool prompts) легко раздувают context window → поэтому важны меры вроде `replaceOldGetToolCallResults`.
- Разные провайдеры имеют разные несовместимости (stream+tools, reasoning, caching) → решается через capability flags, но требует дисциплины при расширении.

---

## 9) Ссылки на ключевые файлы (Quadratic)

(ветка `qa`)
- Model registry:
  - https://github.com/quadratichq/quadratic/blob/qa/quadratic-shared/ai/models/AI_MODELS.ts
  - https://github.com/quadratichq/quadratic/blob/qa/quadratic-shared/ai/models/AI_RATES.ts
- Tool spec:
  - https://github.com/quadratichq/quadratic/blob/qa/quadratic-shared/ai/specs/aiToolsSpec.ts
- Типы/схемы:
  - https://github.com/quadratichq/quadratic/blob/qa/quadratic-shared/typesAndSchemasAI.ts
- Provider clients:
  - https://github.com/quadratichq/quadratic/blob/qa/quadratic-api/src/ai/providers.ts
- Router/dispatcher:
  - https://github.com/quadratichq/quadratic/blob/qa/quadratic-api/src/ai/handler/ai.handler.ts
- Context/prompt builders:
  - https://github.com/quadratichq/quadratic/blob/qa/quadratic-api/src/ai/helpers/context.helper.ts
- Model router:
  - https://github.com/quadratichq/quadratic/blob/qa/quadratic-api/src/ai/helpers/modelRouter.helper.ts
- Message/context utils:
  - https://github.com/quadratichq/quadratic/blob/qa/quadratic-shared/ai/helpers/message.helper.ts
