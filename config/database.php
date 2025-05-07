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

    'redis' => [],

];