<?php
// error_debug.php - Отладка ошибок Laravel
header('Content-Type: application/json');

// Получаем входные данные
$input = json_decode(file_get_contents('php://input'), true) ?: [];

// Логируем запрос
$logData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'headers' => getallheaders(),
    'input' => $input,
];

// Записываем в файл
$logFile = __DIR__ . '/../storage/logs/error_debug.log';
file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

// Пытаемся загрузить Laravel
try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // Получаем последние ошибки из лога
    $laravelLogPath = __DIR__ . '/../storage/logs/laravel.log';
    $lastErrors = [];
    
    if (file_exists($laravelLogPath)) {
        $logContent = file_get_contents($laravelLogPath);
        $logLines = explode("\n", $logContent);
        $lastLines = array_slice($logLines, -50); // Последние 50 строк
        $lastErrors = implode("\n", $lastLines);
    }
    
    // Возвращаем ответ
    echo json_encode([
        'debug' => true,
        'received' => $input,
        'laravel_errors' => $lastErrors,
        'app_loaded' => true
    ]);
} catch (\Exception $e) {
    // Если не удалось загрузить Laravel
    echo json_encode([
        'debug' => true,
        'received' => $input,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'app_loaded' => false
    ]);
}
