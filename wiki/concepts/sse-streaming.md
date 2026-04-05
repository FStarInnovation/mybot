---
title: SSE-стриминг
type: concept
tags: [sse, streaming, server-sent-events, realtime]
sources:
  - wiki/raw/specs/integratio_runpod.md
created: 2026-04-05
updated: 2026-04-05
---

# SSE-стриминг (Server-Sent Events)

## Описание

В RunPod-интеграции SSE используется для потоковой передачи ответов от LLM и NLWEB к Laravel-приложению. Ответ приходит с `content-type: text/event-stream`.

## Где применяется

1. **`POST /chat`** с параметром `stream: true` — потоковый ответ от Llama3 Chat Server.
2. **`POST /ask`** — потоковый ответ от NLWEB, который может включать внутренние вызовы инструментов.

## Потребление SSE в Laravel

Для чтения SSE-потока используется Guzzle с параметром `stream: true` или библиотека `sse-client-php`:

```php
$response = Http::withHeaders(['Accept' => 'text/event-stream'])
    ->withOptions(['stream' => true])
    ->post("{$apiUrl}/ask", $payload);

foreach ($response->stream() as $chunk) {
    echo $chunk->getContent();
}
```

## Альтернатива

Для случаев, когда стриминг не нужен, существуют синхронные аналоги:
- `/chat` с `stream: false` (по умолчанию)
- `/tool/ask` — синхронная версия `/ask`

## See also

- [[wiki/entities/llama3-chat-server|Llama3 Chat Server]]
- [[wiki/entities/nlweb|NLWEB]]
- [[wiki/summaries/runpod-integration|Интеграция Laravel с RunPod]]
