<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Upstash Redis Configuration
    |--------------------------------------------------------------------------
    |
    | Здесь хранятся настройки для подключения к Upstash Redis.
    | Основные настройки уже находятся в .env файле.
    |
    */

    'redis' => [
        'host' => env('REDIS_HOST'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT'),
        'scheme' => 'tls',
        'read_timeout' => env('REDIS_READ_TIMEOUT', 10),
        'timeout' => env('REDIS_TIMEOUT', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    |
    | Настройки для работы с очередями через Upstash.
    |
    */
    'queue' => [
        'connection' => 'upstash',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
];
