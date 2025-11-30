<?php

return [
    'gateway' => [
        'url' => env('LLM_GATEWAY_URL', 'http://localhost:10051'),
        'timeout' => env('LLM_TIMEOUT', 150),
        'retry_attempts' => 3,
        'retry_delay' => 100,
    ],
    'retry_codes' => [502, 503, 504],
    'default_model' => 'llama3',
    // Sampling parameters
    'temperature'   => env('LLM_TEMPERATURE', 0.3),
    'top_p'        => env('LLM_TOP_P', 0.9),
    // System and generic prompts are intentionally left empty:
    // all instruction text is now handled on the LLM backend (RunPod gateway).
    'system_prompt' => '',
    'generic_prompt' => '',
];
