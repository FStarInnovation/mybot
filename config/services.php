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

    // ✅ вставляем LLM сюда, внутри return-массива:
    'llm' => [
        // Unified gateway according to integratio_runpod.md → POST {RUNPOD_API_URL}/chat
        'endpoint' => rtrim(env('RUNPOD_API_URL', 'http://localhost:10051'), '/') . '/chat',
    ],

    'embeddings' => [
        // Unified gateway → POST {RUNPOD_API_URL}/embedding
        'endpoint' => rtrim(env('RUNPOD_API_URL', 'http://localhost:10051'), '/') . '/embedding',
    ],

    'gateway' => [
        // Base URL for proxying NLWEB endpoints (e.g., /ask)
        'base' => env('GATEWAY_URL', rtrim(env('RUNPOD_API_URL', 'http://localhost:10051'), '/')),
    ],

];