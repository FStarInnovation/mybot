# Wiki Index

Каталог всех страниц вики проекта MyBot, организованный по категориям.

---

## Summaries

- [Architecture Plan](summaries/architecture-plan.md) — полная архитектура MyBot: 5 слоев, 8 шагов обработки запроса, план реализации
- [Deployment Guide](summaries/deployment-guide.md) — деплой SvelteKit + Laravel на Laravel Cloud (сборка, nginx, CSP)
- [Schema Plan](summaries/schema-plan.md) — ASCII-диаграмма системы: тиры данных, потоки между компонентами
- [RunPod Integration](summaries/runpod-integration.md) — спецификация API RunPod: LLaMA3, Jina, инструменты, SSE streaming
- [Quadratic Prompts Research](summaries/quadratic-prompts-research.md) — исследование промптов Quadratic: сборка, routing, provider adapters

---

## Entities

### Инфраструктура
- [Laravel Backend](entities/laravel-backend.md) — основной backend на Laravel 12: API-шлюз, контроллер диалога, инструменты
- [Laravel Cloud](entities/laravel-cloud.md) — платформа деплоя: автодеплой, cloud.yaml, health check
- [SvelteKit Frontend](entities/sveltekit-frontend.md) — веб-клиент: TanStack Query, PWA, adapter-static
- [SvelteKit Adapter Static](entities/sveltekit-adapter-static.md) — статическая сборка SvelteKit в public/

### AI/ML
- [RunPod VPS](entities/runpod-vps.md) — GPU-хостинг для LLaMA3
- [RunPod API Gateway](entities/runpod-api-gateway.md) — API gateway RunPod для внешнего доступа
- [LLaMA3 Chat Server](entities/llama3-chat-server.md) — сервер чата на LLaMA3 (llama-cpp-server)
- [Jina Embedding Server](entities/jina-embedding-server.md) — Jina Embeddings для векторизации текста
- [NLWeb](entities/nlweb.md) — NLWeb MCP-сервер
- [NLWeb MCP Server](entities/nlweb-mcp-server.md) — MCP-интерфейс для NLWeb

### Память и мониторинг
- [Upstash Redis](entities/upstash-redis.md) — краткосрочная память: последние N сообщений с TTL
- [Qdrant](entities/qdrant.md) — векторная БД для долгосрочной памяти и semantic search
- [Langfuse](entities/langfuse.md) — мониторинг LLM: телеметрия, usage, алерты

### Интеграции
- [Telegram Integration](entities/telegram-integration.md) — Telegram Bot API: webhook, форматирование, лимиты
- [WhatsApp Integration](entities/whatsapp-integration.md) — WhatsApp Business API через Twilio
- [Rossko API](entities/rossko-api.md) — API автозапчастей: SOAP + REST, VIN-подбор деталей ТО
- [Финуслуги Agent](entities/finuslugi-agent.md) — агентская платформа: страховые и финансовые продукты

---

## Concepts

### Архитектура
- [Memory Tiers](concepts/memory-tiers.md) — двухуровневая память: Redis (краткосрочная) + Qdrant (долгосрочная)
- [Memory Management](concepts/memory-management.md) — управление памятью: embedding, TTL, минимальное хранение
- [Tool Calling](concepts/tool-calling.md) — LLM вызывает инструменты: parse_url, search_memory, sandbox
- [Function Calling Pipeline](concepts/function-calling-pipeline.md) — pipeline вызова функций на RunPod
- [Request Processing Flow](concepts/request-processing-flow.md) — полный цикл обработки запроса (8 шагов)
- [API Gateway Pattern](concepts/api-gateway-pattern.md) — паттерн API gateway в архитектуре

### Промпты и модели
- [Prompt Engineering](concepts/prompt-engineering.md) — паттерны сборки промптов: system/tool/glue/context
- [Model Routing](concepts/model-routing.md) — маршрутизация между LLM-моделями по типу задачи
- [Provider Adapters](concepts/provider-adapters.md) — adapter pattern для мультипровайдерности

### Деплой
- [SPA Fallback](concepts/spa-fallback.md) — паттерн SPA-фолбэка через try_files в nginx
- [CSP Policy](concepts/csp-policy.md) — Content Security Policy для SvelteKit
- [SSE Streaming](concepts/sse-streaming.md) — Server-Sent Events для streaming ответов

---

## Raw Sources

- `raw/reports/plan.md` — Архитектурный план MyBot (79KB)
- `raw/reports/quadratic_prompts_report.md` — Исследование промптов Quadratic (285KB)
- `raw/reports/quadratic_llm_architecture_report.md` — Архитектура LLM Quadratic (14KB)
- `raw/specs/DEPLOYMENT.md` — Гайд по деплою SvelteKit + Laravel
- `raw/specs/schema_plan.md` — Схема архитектуры (ASCII-диаграмма)
- `raw/specs/integratio_runpod.md` — Спецификация RunPod API
