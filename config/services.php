<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'llm' => [
        // Unified gateway chat endpoint
        'endpoint' => env('LLM_GATEWAY_URL', 'http://localhost:10051') . '/chat',
    ],

    'embeddings' => [
        'endpoint' => env('EMBEDDINGS_API_URL', 'http://localhost:8000/embedding'),
    ],

    'mcp' => [
        'endpoint'      => env('MCP_BASE_URL', 'http://localhost:10051'),
        'auth_username' => env('MCP_USERNAME'),
        'auth_password' => env('MCP_PASSWORD'),
    ],

    // Base URL для поиска товаров (NLWeb или другой сервис)
    'product_api' => [
        'url' => env('PRODUCT_API_URL', env('SEARCH_PRODUCTS_URL', '')),
    ],

];