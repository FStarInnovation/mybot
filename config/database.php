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
        'client' => env('REDIS_CLIENT', 'predis'),

        'options' => [
            'prefix' => env('REDIS_PREFIX', 'mybot_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'token' => env('REDIS_TOKEN'),
            'scheme' => 'tls',
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'token' => env('REDIS_TOKEN'),
            'scheme' => 'tls',
        ],
    ],

];