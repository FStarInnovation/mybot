<?php
// validation_debug.php - Отладка валидации запросов
header('Content-Type: application/json');

// Загрузка Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Получаем входные данные
$input = json_decode(file_get_contents('php://input'), true) ?: [];
$request = \Illuminate\Http\Request::capture();

// Создаем валидатор вручную
$validator = \Illuminate\Support\Facades\Validator::make($input, [
    'message' => 'required|string|max:1000',
]);

// Проверяем валидацию
$passes = $validator->passes();
$errors = $validator->errors()->toArray();

// Проверяем сессию
$hasSession = $request->hasSession();
$sessionId = $hasSession ? $request->session()->getId() : null;

// Логируем запрос
$logData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'headers' => getallheaders(),
    'input' => $input,
    'validation' => [
        'passes' => $passes,
        'errors' => $errors
    ],
    'session' => [
        'has_session' => $hasSession,
        'session_id' => $sessionId
    ]
];

// Записываем в файл
$logFile = __DIR__ . '/../storage/logs/validation_debug.log';
file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

// Возвращаем результат
echo json_encode([
    'debug' => true,
    'validation' => [
        'passes' => $passes,
        'errors' => $errors
    ],
    'session' => [
        'has_session' => $hasSession,
        'session_id' => $sessionId
    ],
    'received' => $input
]);
