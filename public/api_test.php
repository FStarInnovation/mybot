<?php
// Простой API-тест для проверки обработки PHP-запросов
header('Content-Type: application/json');

echo json_encode([
    'status' => 'ok',
    'message' => 'API test is working',
    'time' => date('Y-m-d H:i:s'),
    'request_method' => $_SERVER['REQUEST_METHOD'],
    'request_uri' => $_SERVER['REQUEST_URI'],
    'php_version' => phpversion(),
]);
