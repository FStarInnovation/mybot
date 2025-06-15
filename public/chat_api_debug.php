<?php
// chat_api_debug.php - Прямая отладка API чата
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
$logFile = __DIR__ . '/../storage/logs/chat_api_debug.log';
file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

// Загружаем Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Создаем запрос вручную
$request = \Illuminate\Http\Request::create(
    '/api/chat/send',
    'POST',
    [],
    [],
    [],
    ['CONTENT_TYPE' => 'application/json'],
    json_encode(['message' => $message])
);

// Добавляем заголовки из текущего запроса
foreach (getallheaders() as $key => $value) {
    $request->headers->set($key, $value);
}

// Устанавливаем IP и User-Agent
$request->server->set('REMOTE_ADDR', $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
$request->server->set('HTTP_USER_AGENT', $_SERVER['HTTP_USER_AGENT'] ?? 'Debug-Agent');

try {
    // Обрабатываем запрос напрямую через контроллер
    $controller = new \App\Http\Controllers\ChatController(
        app(\App\Services\Memory\MemoryService::class)
    );
    
    $response = $controller->send($request);
    
    // Возвращаем ответ
    echo $response->getContent();
    
} catch (\Exception $e) {
    // Логируем ошибку
    file_put_contents($logFile, "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\n", FILE_APPEND);
    
    // Возвращаем ошибку
    echo json_encode([
        'debug' => true,
        'error' => $e->getMessage(),
        'trace' => explode("\n", $e->getTraceAsString()),
        'received' => $message
    ]);
}
