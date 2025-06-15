<?php
header('Content-Type: application/json');

echo json_encode([
    'status' => 'ok',
    'php_version' => PHP_VERSION,
    'server' => $_SERVER,
    'time' => date('Y-m-d H:i:s'),
    'laravel_routes' => file_exists(base_path('routes/web.php')) ? 'exists' : 'missing',
    'app_path' => base_path(),
]);
