<?php
// API Bridge для обхода проблем с маршрутизацией
header('Content-Type: application/json');

// Получаем запрошенный путь
$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Определяем, какой эндпоинт был запрошен
if (strpos($requestUri, '/api/chat/send') !== false && $method === 'POST') {
    // Получаем JSON из тела запроса
    $input = json_decode(file_get_contents('php://input'), true);
    $message = $input['message'] ?? 'No message provided';
    
    // Имитируем ответ от ChatController
    echo json_encode([
        'status' => 'success',
        'messages' => [
            ['role' => 'assistant', 'content' => "Вы отправили: " . $message]
        ]
    ]);
} elseif (strpos($requestUri, '/api/search_products') !== false && $method === 'POST') {
    // Получаем JSON из тела запроса
    $input = json_decode(file_get_contents('php://input'), true);
    $query = $input['query'] ?? 'No query provided';
    
    // Имитируем ответ от ProductController
    echo json_encode([
        'status' => 'success',
        'products' => [
            [
                'name' => 'Product 1 for ' . $query,
                'price' => 100,
                'currency' => 'USD',
                'url' => 'https://example.com/product1'
            ],
            [
                'name' => 'Product 2 for ' . $query,
                'price' => 200,
                'currency' => 'USD',
                'url' => 'https://example.com/product2'
            ]
        ]
    ]);
} else {
    // Неизвестный эндпоинт
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unknown endpoint: ' . $requestUri
    ]);
}
