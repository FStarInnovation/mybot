<?php
// send.php - Заглушка для API /api/chat/send
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

// Создаем директорию для логов, если она не существует
$logDir = __DIR__ . '/../../../storage/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

// Записываем в файл
$logFile = $logDir . '/chat_send_stub.log';
file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

// Простая логика для ответов
$response = '';
if (stripos($message, 'ibuprofeno') !== false || stripos($message, 'ибупрофен') !== false) {
    $response = 'Ибупрофен - это нестероидный противовоспалительный препарат, который используется для снижения высокой температуры и облегчения боли. Он доступен в различных формах, включая таблетки, капсулы и сиропы.';
} elseif (stripos($message, 'привет') !== false || stripos($message, 'здравствуй') !== false || stripos($message, 'hola') !== false) {
    $response = 'Здравствуйте! Чем я могу вам помочь сегодня?';
} else {
    $response = 'Я могу помочь вам найти информацию о лекарствах и ответить на вопросы о здоровье. Что вас интересует?';
}

// Возвращаем ответ
echo json_encode([
    'messages' => [
        ['role' => 'assistant', 'content' => $response]
    ]
]);
