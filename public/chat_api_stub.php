<?php
// chat_api_stub.php - Заглушка для API чата без использования Laravel
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
    'input' => $input,
];

// Создаем директорию для логов, если она не существует
$logDir = __DIR__ . '/../storage/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

// Записываем в файл
$logFile = $logDir . '/chat_api_stub.log';
file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND);

// Имитация карточек продуктов для определенных запросов
if (stripos($message, 'ибупрофен') !== false || stripos($message, 'ibuprofeno') !== false || 
    stripos($message, 'ibuprofen') !== false) {
    // Имитация ответа с карточкой продукта
    $productCard = [
        'id' => 'product-1',
        'title' => 'Ибупрофен 200 мг',
        'description' => 'Противовоспалительное средство, 20 таблеток',
        'price' => '199 руб.',
        'image' => 'https://via.placeholder.com/150',
        'url' => '#',
        'rating' => 4.7,
        'reviews' => 120
    ];
    
    $response = [
        'messages' => [
            [
                'role' => 'assistant', 
                'content' => 'Вот информация об ибупрофене:',
                'tool_calls' => [
                    [
                        'id' => 'call_' . uniqid(),
                        'type' => 'function',
                        'function' => [
                            'name' => 'search_products',
                            'arguments' => json_encode(['query' => 'ибупрофен'])
                        ]
                    ]
                ]
            ],
            [
                'role' => 'tool', 
                'content' => json_encode([
                    'products' => [
                        $productCard,
                        [
                            'id' => 'product-2',
                            'title' => 'Ибупрофен 400 мг',
                            'description' => 'Противовоспалительное средство, 10 таблеток',
                            'price' => '249 руб.',
                            'image' => 'https://via.placeholder.com/150',
                            'url' => '#',
                            'rating' => 4.5,
                            'reviews' => 85
                        ]
                    ]
                ]),
                'tool_call_id' => 'call_' . uniqid()
            ],
            [
                'role' => 'assistant',
                'content' => 'Ибупрофен - это нестероидный противовоспалительный препарат, который используется для снижения высокой температуры и облегчения боли. Он доступен в различных формах, включая таблетки, капсулы и сиропы. Выше представлены некоторые варианты препарата.'
            ]
        ]
    ];
    
    echo json_encode($response);
} elseif (stripos($message, 'аспирин') !== false || stripos($message, 'aspirina') !== false || 
         stripos($message, 'aspirin') !== false) {
    // Имитация ответа с карточкой продукта для аспирина
    $response = [
        'messages' => [
            [
                'role' => 'assistant', 
                'content' => 'Вот информация об аспирине:',
                'tool_calls' => [
                    [
                        'id' => 'call_' . uniqid(),
                        'type' => 'function',
                        'function' => [
                            'name' => 'search_products',
                            'arguments' => json_encode(['query' => 'аспирин'])
                        ]
                    ]
                ]
            ],
            [
                'role' => 'tool', 
                'content' => json_encode([
                    'products' => [
                        [
                            'id' => 'product-3',
                            'title' => 'Аспирин 500 мг',
                            'description' => 'Ацетилсалициловая кислота, 20 таблеток',
                            'price' => '129 руб.',
                            'image' => 'https://via.placeholder.com/150',
                            'url' => '#',
                            'rating' => 4.3,
                            'reviews' => 210
                        ],
                        [
                            'id' => 'product-4',
                            'title' => 'Аспирин Кардио',
                            'description' => 'Для профилактики тромбообразования, 28 таблеток',
                            'price' => '349 руб.',
                            'image' => 'https://via.placeholder.com/150',
                            'url' => '#',
                            'rating' => 4.8,
                            'reviews' => 156
                        ]
                    ]
                ]),
                'tool_call_id' => 'call_' . uniqid()
            ],
            [
                'role' => 'assistant',
                'content' => 'Аспирин (ацетилсалициловая кислота) - это препарат с жаропонижающим, противовоспалительным и обезболивающим действием. Также используется для профилактики тромбообразования. Выше представлены некоторые варианты препарата.'
            ]
        ]
    ];
    
    echo json_encode($response);
} elseif (stripos($message, 'парацетамол') !== false || stripos($message, 'paracetamol') !== false) {
    // Имитация ответа с карточкой продукта для парацетамола
    $response = [
        'messages' => [
            [
                'role' => 'assistant', 
                'content' => 'Вот информация о парацетамоле:',
                'tool_calls' => [
                    [
                        'id' => 'call_' . uniqid(),
                        'type' => 'function',
                        'function' => [
                            'name' => 'search_products',
                            'arguments' => json_encode(['query' => 'парацетамол'])
                        ]
                    ]
                ]
            ],
            [
                'role' => 'tool', 
                'content' => json_encode([
                    'products' => [
                        [
                            'id' => 'product-5',
                            'title' => 'Парацетамол 500 мг',
                            'description' => 'Жаропонижающее средство, 20 таблеток',
                            'price' => '59 руб.',
                            'image' => 'https://via.placeholder.com/150',
                            'url' => '#',
                            'rating' => 4.2,
                            'reviews' => 180
                        ]
                    ]
                ]),
                'tool_call_id' => 'call_' . uniqid()
            ],
            [
                'role' => 'assistant',
                'content' => 'Парацетамол - это анальгетик и антипиретик, который используется для снижения температуры и облегчения боли. Он доступен в различных формах, включая таблетки, сиропы и суппозитории.'
            ]
        ]
    ];
    
    echo json_encode($response);
} else {
    // Обычный текстовый ответ для других запросов
    $response = '';
    if (stripos($message, 'привет') !== false || stripos($message, 'здравствуй') !== false || stripos($message, 'hola') !== false) {
        $response = 'Здравствуйте! Чем я могу вам помочь сегодня?';
    } else {
        $response = 'Я могу помочь вам найти информацию о лекарствах и ответить на вопросы о здоровье. Что вас интересует?';
    }
    
    echo json_encode([
        'messages' => [
            ['role' => 'assistant', 'content' => $response]
        ]
    ]);
}
