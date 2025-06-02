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
        'endpoint' => env('LLM_API_URL', 'http://localhost:11434/completion'),
    ],

    'embeddings' => [
        'endpoint' => env('EMBEDDINGS_API_URL', 'http://localhost:8000/embedding'),
    ],

];