<?php
// chat_stub.php - Заглушка для API чата
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Обработка OPTIONS запроса для CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

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
$logFile = __DIR__ . '/../storage/logs/chat_stub.log';
file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

// Возвращаем заглушку ответа
echo json_encode([
    'messages' => [
        ['role' => 'assistant', 'content' => 'Это заглушка ответа. Вы отправили: ' . $message]
    ]
]);
