<?php

return [

    // Указываем фиктивный драйвер, чтобы Laravel не падал
    'default' => env('DB_CONNECTION', 'none'),

    'connections' => [
        'none' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ],
    ],

    'migrations' => [
        'table' => 'migrations',
    ],

    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'prefix' => env('REDIS_PREFIX', 'mybot_database_'),
            'exceptions' => true,
            'retry_interval' => 100,
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', '0'),
            'scheme' => 'tls',
            'read_timeout' => env('REDIS_READ_TIMEOUT', 10),
            'timeout' => env('REDIS_TIMEOUT', 5),
            'persistent' => false,
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_CACHE_DB', '1'),
            'scheme' => 'tls',
        ],

        'upstash' => [
            'url' => env('REDIS_URL'),
            'scheme' => 'tls',
            'read_timeout' => env('REDIS_READ_TIMEOUT', 10),
            'retry_interval' => 100,
            'persistent' => false,
            'timeout' => env('REDIS_TIMEOUT', 5),
        ],
    ],

];