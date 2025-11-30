<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [env('CORS_ALLOWED_ORIGINS', 'http://localhost:5173')],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    // Allow cookies (session) for cross-origin requests (required for chat routes using StartSession)
    'supports_credentials' => true,
];
