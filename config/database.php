<?php

use Illuminate\Support\Str;

return [

    'default' => env('DB_CONNECTION', 'none'),

    'connections' => [

        'none' => [
            'driver' => 'array',
        ],

    ],

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    'redis' => [],

];