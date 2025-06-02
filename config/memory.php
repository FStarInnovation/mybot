<?php

return [
    'short_term' => [
        'driver' => 'redis',
        'ttl' => env('SHORT_TERM_TTL', 60 * 60 * 24 * 7), // 1 неделя
        'prefix' => 'chat:memory:',
        'max_messages' => 10, // Максимальное количество сообщений в истории
    ],
    'long_term' => [
        'driver' => 'neon',
        'similarity_threshold' => 0.7, // Порог похожести для релевантности
        'max_results' => 3, // Максимальное количество результатов поиска
    ],
];
