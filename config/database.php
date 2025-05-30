<?php

return [

    // Используем PostgreSQL как основную БД
    'default' => env('DB_CONNECTION', 'pgsql'),

    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => env('DB_SSLMODE', 'require'),
        ],

        // Connection to Neon branch with test table `farma`
        'farma' => [
            'driver' => 'pgsql',
            'host' => env('FARMA_PG_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('FARMA_PG_PORT', 5432),
            'database' => env('FARMA_PG_DATABASE', 'neondb'),
            'username' => env('FARMA_PG_USERNAME', env('DB_USERNAME', 'forge')),
            'password' => env('FARMA_PG_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'require',
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
            'scheme' => env('REDIS_SCHEME', 'tls'),
            'read_timeout' => env('REDIS_READ_TIMEOUT', 10), // seconds
            'timeout' => env('REDIS_TIMEOUT', 5), // seconds for connection
            'persistent' => false,
            'keepalive' => env('REDIS_KEEPALIVE', 30), // seconds for TCP keepalive
            // 'ssl' => [ ... ], // Intentionally removed
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_CACHE_DB', '1'),
            'scheme' => env('REDIS_SCHEME', 'tls'),
            'read_timeout' => env('REDIS_READ_TIMEOUT', 10), // seconds
            'timeout' => env('REDIS_TIMEOUT', 5), // seconds for connection
            'persistent' => false, // Assuming cache connections don't need to be persistent
            'keepalive' => env('REDIS_KEEPALIVE', 30), // seconds for TCP keepalive
            // 'ssl' => [ ... ], // Intentionally removed
        ],

    ],

];