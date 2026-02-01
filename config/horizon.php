<?php

return [
    'use' => false, // Временно отключено из-за проблем с Redis
    'defaults' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['default'],
            'balance' => 'auto',
            'processes' => 1,
            'tries' => 3,
        ],
    ],
    'environments' => [
        'production' => [],
        'local' => [],
    ],
];
