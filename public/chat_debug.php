<?php
// chat_debug.php - Отладка запросов к /api/chat/send
header('Content-Type: application/json');

// Получаем входные данные
$input = json_decode(file_get_contents('php://input'), true) ?: [];
$message = $input['message'] ?? 'No message provided';

// Логируем запрос
$logData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'headers' => getallheaders(),
    'input' => $input,
];

// Записываем в файл
$logFile = __DIR__ . '/../storage/logs/chat_debug.log';
file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

// Возвращаем ответ как если бы это был /api/chat/send
echo json_encode([
    'debug' => true,
    'received' => $message,
    'messages' => [
        ['role' => 'assistant', 'content' => 'Это отладочный ответ. Вы отправили: ' . $message]
    ]
]);
