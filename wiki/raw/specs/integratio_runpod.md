# Laravel to RunPod Integration Documentation

## Overview

This document describes how Laravel applications should format requests to interact with the RunPod services. The RunPod environment hosts several services including:

1. **Llama3 Chat Server** (port 1434) - LLM service for reasoning and function-calling
2. **Jina Embedding Server** (port 1435) - Embedding generation service
3. **API Gateway** (port 10051) - FastAPI proxy for accessing tools and services
4. **NLWEB** (port 8000) - Web interface and API endpoints

Laravel applications should interact with these services through the API Gateway, which serves as the main entry point for all requests.

## API Endpoints

### 1. Chat Completion

**Endpoint:** `POST http://<runpod-ip>:10051/chat`

This endpoint proxies requests to the Llama3 Chat Server for LLM completions.

**Request Format:**
```json
{
  "model": "llama",
  "messages": [
    {
      "role": "system",
      "content": "You are a helpful assistant."
    },
    {
      "role": "user",
      "content": "Find products related to skincare."
    }
  ],
  "stream": false
}
```

**Parameters:**
- `model`: Model identifier (default: "llama")
- `messages`: Array of message objects with `role` and `content`
- `stream`: Boolean flag to enable streaming responses (optional, default: false)

**Response Format (non-streaming):**
```json
{
  "id": "chatcmpl-123",
  "object": "chat.completion",
  "created": 1686901234,
  "model": "llama",
  "choices": [
    {
      "index": 0,
      "message": {
        "role": "assistant",
        "content": "Here are some skincare products..."
      },
      "finish_reason": "stop"
    }
  ]
}
```

**Streaming Response:**
If `stream: true` is set, the response will be sent as Server-Sent Events (SSE) with content-type `text/event-stream`.

### 2. Product Search Tool

**Endpoint:** `POST http://<runpod-ip>:10051/tool/search_products`

This endpoint allows searching for products in the database.

**Request Format:**
```json
{
  "query": "moisturizer for dry skin",
  "limit": 10
}
```

**Parameters:**
- `query`: Search query string (required)
- `limit`: Maximum number of results to return (optional, default: 10)

**Response Format:**
```json
{
  "status": "success",
  "query": "moisturizer for dry skin",
  "results": [
    {
      "id": "123",
      "name": "Hydrating Face Cream",
      "price": 29.99,
      "description": "Deep hydration for dry skin",
      "url": "https://example.com/product/123",
      "relevance": 0.95
    },
    // Additional products...
  ]
}
```

### 3. Single Page Crawler Tool

**Endpoint:** `POST http://<runpod-ip>:10051/tool/crawl_single_page`

This endpoint crawls a single web page and returns its content.

**Request Format:**
```json
{
  "url": "https://example.com/product/123"
}
```

**Parameters:**
- `url`: URL to crawl (required)

**Response Format:**
```json
{
  "status": "success",
  "url": "https://example.com/product/123",
  "data": {
    "title": "Product Title",
    "content": "Product description and details...",
    "timestamp": "2025-06-16T16:45:00.000Z"
  }
}
```

## Additional Endpoints

### 4. Unified Ask Endpoint (NLWEB SSE)

**Endpoint:** `POST http://<runpod-ip>:10051/ask`

This endpoint proxies requests to NLWEB’s unified `/api/v1/ask` endpoint and returns **Server-Sent Events (SSE)**.  Use it for chat-like streaming responses that may internally trigger tool calls handled by NLWEB.

**Request Format:**
```json
{
  "service": "search",             // or "analyze" depending on NLWEB routing
  "messages": [
    { "role": "user", "content": "¿Cuáles son los mejores laptops 2024?" }
  ]
}
```

Laravel can consume the SSE stream with a library such as `sse-client-php` or by using Guzzle’s streaming interface:
```php
$response = Http::withHeaders(['Accept' => 'text/event-stream'])
    ->withOptions(['stream' => true])
    ->post("{$this->apiUrl}/ask", [
        'service'  => 'search',
        'messages' => $messages,
    ]);
foreach ($response->stream() as $chunk) {
    echo $chunk->getContent(); // Handle each SSE data: line
}
```

### 5. Embedding Generation

**Endpoint:** `POST http://<runpod-ip>:10051/embedding`

Generate vector embeddings via the Jina Embedding Server.

**Request Format:**
```json
{
  "input": ["Text 1", "Text 2"],
  "model": "jina-embeddings-v2-base-es"
}
```

**Response Format:**
```json
{
  "data": [
    { "embedding": [0.123, 0.456, ...] },
    { "embedding": [0.789, 0.012, ...] }
  ],
  "model": "jina-embeddings-v2-base-es"
}
```

Use this endpoint for similarity search or storing vectors.

### 6. MCP Business Action Tool

**Endpoint:** `POST http://<runpod-ip>:10051/tool/mcp`

Invokes a task on the MCP micro-service.

**Required Arguments (JSON):**
| Field | Type | Description |
|-------|------|-------------|
| `task` | string | Identifier of the business task to perform. Examples: `reprice`, `sync_stock`, `import_catalog`. |
| `payload` | object | Optional nested parameters specific to the task (free-form). |

**Minimal Example:**
```json
{
  "task": "reprice",
  "payload": {
    "sku": "ABC123",
    "discount": 0.10
  }
}
```

**Success Response:**
```json
{
  "status": "queued",
  "task_id": "job-789"
}
```

### 7. NLWEB Ask Tool (sync)

**Endpoint:** `POST http://<runpod-ip>:10051/tool/ask`

Sends a JSON request to NLWEB `/ask` endpoint and returns a single JSON reply (non-stream). For streaming use, hit `/ask` directly with SSE as described earlier.

**Required Arguments:**
| Field | Type | Description |
|-------|------|-------------|
| `messages` | array<object> | Conversation in OpenAI chat format. Must contain at least one user message. |
| `service` | string | Optional NLWEB service route (`search`, `analyze`), default `search`. |

**Example:**
```json
{
  "service": "search",
  "messages": [
    { "role": "user", "content": "Можете найти витамины с витамином D?" }
  ]
}
```

**Response:** identical to NLWEB JSON reply schema, typically:
```json
{
  "answer": "Вот лучшие витамины…",
  "sources": [ ... ]
}
```

## Implementation in Laravel

### Example: Making a Chat Request

```php
use Illuminate\Support\Facades\Http;

class RunPodService
{
    protected $apiUrl;
    
    public function __construct()
    {
        $this->apiUrl = env('RUNPOD_API_URL', 'http://localhost:10051');
    }
    
    public function chatCompletion($messages, $stream = false)
    {
        $response = Http::post("{$this->apiUrl}/chat", [
            'model' => 'llama',
            'messages' => $messages,
            'stream' => $stream
        ]);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Failed to get chat completion: ' . $response->body());
    }
    
    public function searchProducts($query, $limit = 10)
    {
        $response = Http::post("{$this->apiUrl}/tool/search_products", [
            'query' => $query,
            'limit' => $limit
        ]);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Failed to search products: ' . $response->body());
    }
    
    public function crawlPage($url)
    {
        $response = Http::post("{$this->apiUrl}/tool/crawl_single_page", [
            'url' => $url
        ]);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        throw new \Exception('Failed to crawl page: ' . $response->body());
    }
}
```

### Example: Controller Usage

```php
use App\Services\RunPodService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $runpodService;
    
    public function __construct(RunPodService $runpodService)
    {
        $this->runpodService = $runpodService;
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        $limit = $request->input('limit', 10);
        
        $results = $this->runpodService->searchProducts($query, $limit);
        
        return response()->json($results);
    }
    
    public function chat(Request $request)
    {
        $messages = $request->input('messages', []);
        
        // Add system message if not provided
        if (!collect($messages)->contains('role', 'system')) {
            array_unshift($messages, [
                'role' => 'system',
                'content' => 'You are a helpful product recommendation assistant.'
            ]);
        }
        
        $response = $this->runpodService->chatCompletion($messages);
        
        return response()->json($response);
    }
}
```

## Error Handling

The API Gateway returns standard HTTP status codes:

- `200 OK`: Request successful
- `400 Bad Request`: Invalid request parameters
- `404 Not Found`: Endpoint not found
- `500 Internal Server Error`: Server-side error

Laravel applications should handle these status codes appropriately and implement retry logic for temporary failures.

## Security Considerations

1. **API Authentication**: Consider implementing API keys or JWT authentication for production environments
2. **Rate Limiting**: Implement rate limiting on the Laravel side to prevent abuse
3. **Input Validation**: Always validate input parameters before sending to RunPod
4. **Error Handling**: Implement proper error handling and logging

## Environment Configuration

Add the following to your Laravel `.env` file:

```
RUNPOD_API_URL=http://<runpod-ip>:10051
RUNPOD_TIMEOUT=120
```

And update your `config/services.php`:

```php
'runpod' => [
    'url' => env('RUNPOD_API_URL', 'http://localhost:10051'),
    'timeout' => env('RUNPOD_TIMEOUT', 120),
],
```

## Conclusion

This documentation provides the necessary information for integrating Laravel applications with RunPod services. By following these guidelines, you can ensure proper communication between your Laravel application and the RunPod environment.
