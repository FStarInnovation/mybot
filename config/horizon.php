<?php

return [
    'default' => 'redis',
    'environments' => [
        'production' => [
            'supervisor-normalize' => [
                'connection' => 'redis',
                'queue' => ['normalize'],
                'balance' => 'simple',
                'processes' => 3,
                'tries' => 3,
            ],
        ],
        'local' => [
            'supervisor-normalize' => [
                'connection' => 'redis',
                'queue' => ['normalize'],
                'balance' => 'simple',
                'processes' => 1,
                'tries' => 1,
            ],
        ],
    ],
];
