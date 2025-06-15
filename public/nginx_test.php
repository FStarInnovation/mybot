<?php
// nginx_test.php - Диагностический скрипт для проверки конфигурации Nginx
header('Content-Type: text/plain');

echo "Nginx Configuration Test - " . date('Y-m-d H:i:s') . "\n\n";

// Функция для выполнения HTTP-запроса
function make_request($url, $method = 'GET', $data = null) {
    $options = [
        'http' => [
            'method' => $method,
            'header' => 'Content-Type: application/json',
            'ignore_errors' => true
        ]
    ];
    
    if ($data && $method !== 'GET') {
        $options['http']['content'] = json_encode($data);
    }
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    return [
        'status' => $http_response_header[0] ?? 'Unknown',
        'headers' => $http_response_header ?? [],
        'body' => $result
    ];
}

// Получаем базовый URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = "$protocol://$host";

echo "Базовый URL: $baseUrl\n\n";

// Тестируем различные маршруты
$routes = [
    '/api/ping' => 'GET',
    '/api/chat/send' => 'POST',
    '/api/search_products' => 'POST',
    '/api/v1/ask' => 'POST',
    '/health' => 'GET',
    '/health.php' => 'GET'
];

$testData = [
    'message' => 'test message',
    'query' => 'test query'
];

echo "Тестирование маршрутов:\n";
foreach ($routes as $route => $method) {
    $url = $baseUrl . $route;
    echo "- Тестирование $method $route... ";
    
    $data = null;
    if ($method === 'POST') {
        $data = $testData;
    }
    
    $response = make_request($url, $method, $data);
    echo $response['status'] . "\n";
    
    // Показываем заголовки для диагностики
    echo "  Заголовки ответа:\n";
    foreach ($response['headers'] as $header) {
        echo "  $header\n";
    }
    
    // Показываем тело ответа (первые 200 символов)
    $body = $response['body'];
    if (strlen($body) > 200) {
        $body = substr($body, 0, 200) . '...';
    }
    echo "  Тело ответа: " . $body . "\n\n";
}

// Проверяем конфигурацию Nginx через HTTP-заголовки
echo "Проверка HTTP-заголовков сервера:\n";
$headers = get_headers($baseUrl, 1);
echo "- Server: " . ($headers['Server'] ?? 'Not found') . "\n";
echo "- X-Powered-By: " . ($headers['X-Powered-By'] ?? 'Not found') . "\n";
echo "- Content-Type: " . ($headers['Content-Type'] ?? 'Not found') . "\n";

// Проверяем наличие файла cloud.yaml (если доступен)
echo "\nПроверка конфигурации cloud.yaml:\n";
$cloudYamlPath = $_SERVER['DOCUMENT_ROOT'] . '/../cloud.yaml';
if (file_exists($cloudYamlPath)) {
    echo "✓ Файл cloud.yaml найден\n";
    echo "  Размер: " . filesize($cloudYamlPath) . " байт\n";
    
    // Попытка прочитать и показать секцию nginx_inline
    $content = file_get_contents($cloudYamlPath);
    if (preg_match('/nginx_inline:\s*\|(.*?)(?:\n\S|\z)/s', $content, $matches)) {
        echo "  Секция nginx_inline найдена:\n";
        echo "  " . str_replace("\n", "\n  ", trim($matches[1])) . "\n";
    } else {
        echo "  Секция nginx_inline не найдена\n";
    }
} else {
    echo "❌ Файл cloud.yaml не найден или недоступен\n";
}

echo "\nТест конфигурации Nginx завершен.\n";
