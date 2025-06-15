<?php
// session_test.php - Тестирование сессий и валидации в Laravel
header('Content-Type: text/plain');

echo "Laravel Session and Validation Test - " . date('Y-m-d H:i:s') . "\n\n";

// Проверка загрузки Laravel
echo "Проверка загрузки Laravel:\n";
$bootstrapPath = __DIR__ . '/../bootstrap/app.php';
$vendorPath = __DIR__ . '/../vendor/autoload.php';

if (file_exists($vendorPath) && file_exists($bootstrapPath)) {
    echo "✓ Файлы Laravel найдены\n";
    
    try {
        // Загрузка Laravel
        require $vendorPath;
        $app = require_once $bootstrapPath;
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        
        echo "✓ Laravel загружен успешно\n\n";
        
        // Проверка сессии
        echo "Проверка сессии:\n";
        $sessionDriver = config('session.driver');
        $sessionLifetime = config('session.lifetime');
        $sessionCookie = config('session.cookie');
        $sessionSecure = config('session.secure');
        
        echo "- Драйвер сессии: $sessionDriver\n";
        echo "- Время жизни сессии: $sessionLifetime минут\n";
        echo "- Имя cookie сессии: $sessionCookie\n";
        echo "- Secure cookie: " . ($sessionSecure ? 'Да' : 'Нет') . "\n\n";
        
        // Проверка валидации запроса
        echo "Проверка валидации запроса:\n";
        $request = \Illuminate\Http\Request::capture();
        echo "- Метод запроса: " . $request->method() . "\n";
        echo "- Content-Type: " . $request->header('Content-Type') . "\n";
        echo "- Accept: " . $request->header('Accept') . "\n";
        
        // Проверка CSRF
        echo "- CSRF защита: " . (config('session.http_only') ? 'Включена' : 'Отключена') . "\n";
        echo "- CSRF токен присутствует: " . ($request->hasSession() && $request->session()->has('_token') ? 'Да' : 'Нет') . "\n\n";
        
        // Проверка маршрутов API
        echo "Проверка маршрутов API:\n";
        $routes = app('router')->getRoutes();
        $apiRoutes = [];
        
        foreach ($routes as $route) {
            $uri = $route->uri();
            if (strpos($uri, 'api/') === 0) {
                $apiRoutes[] = [
                    'uri' => $uri,
                    'methods' => implode('|', $route->methods()),
                    'name' => $route->getName(),
                    'middleware' => implode('|', $route->middleware()),
                ];
            }
        }
        
        echo "- Найдено API маршрутов: " . count($apiRoutes) . "\n";
        foreach ($apiRoutes as $idx => $route) {
            echo "  " . ($idx + 1) . ". {$route['methods']} {$route['uri']} (middleware: {$route['middleware']})\n";
        }
        echo "\n";
        
        // Проверка маршрута /api/chat/send
        echo "Проверка маршрута /api/chat/send:\n";
        $chatSendRoute = null;
        foreach ($apiRoutes as $route) {
            if ($route['uri'] === 'api/chat/send') {
                $chatSendRoute = $route;
                break;
            }
        }
        
        if ($chatSendRoute) {
            echo "✓ Маршрут /api/chat/send найден\n";
            echo "  - Методы: {$chatSendRoute['methods']}\n";
            echo "  - Middleware: {$chatSendRoute['middleware']}\n";
            
            // Проверка контроллера
            $controller = app('router')->getRoutes()->getByName($chatSendRoute['name'])->getAction('controller');
            if ($controller) {
                echo "  - Контроллер: $controller\n";
                
                // Проверка существования контроллера
                list($controllerClass, $method) = explode('@', $controller);
                if (class_exists($controllerClass)) {
                    echo "  - Класс контроллера существует: Да\n";
                    
                    // Проверка метода
                    if (method_exists($controllerClass, $method)) {
                        echo "  - Метод контроллера существует: Да\n";
                        
                        // Получение зависимостей метода
                        $reflectionMethod = new \ReflectionMethod($controllerClass, $method);
                        $parameters = $reflectionMethod->getParameters();
                        echo "  - Параметры метода: " . count($parameters) . "\n";
                        foreach ($parameters as $param) {
                            echo "    - {$param->getName()}: " . ($param->getType() ? $param->getType()->getName() : 'mixed') . "\n";
                        }
                    } else {
                        echo "  - Метод контроллера существует: Нет\n";
                    }
                } else {
                    echo "  - Класс контроллера существует: Нет\n";
                }
            } else {
                echo "  - Контроллер не найден\n";
            }
        } else {
            echo "❌ Маршрут /api/chat/send не найден\n";
        }
        
        // Проверка сервисов
        echo "\nПроверка сервисов:\n";
        $services = [
            'App\Services\MemoryService',
            'App\Services\LlmGatewayService',
            'App\Services\ToolManifestService',
        ];
        
        foreach ($services as $service) {
            if (class_exists($service)) {
                echo "✓ Сервис $service существует\n";
                try {
                    $instance = app($service);
                    echo "  - Экземпляр создан успешно\n";
                } catch (\Exception $e) {
                    echo "  - Ошибка создания экземпляра: " . $e->getMessage() . "\n";
                }
            } else {
                echo "❌ Сервис $service не существует\n";
            }
        }
        
        // Проверка конфигурации CORS
        echo "\nПроверка конфигурации CORS:\n";
        $corsConfig = config('cors');
        if ($corsConfig) {
            echo "- Paths: " . implode(', ', $corsConfig['paths'] ?? ['не указано']) . "\n";
            echo "- Allowed Methods: " . implode(', ', $corsConfig['allowed_methods'] ?? ['не указано']) . "\n";
            echo "- Allowed Origins: " . implode(', ', $corsConfig['allowed_origins'] ?? ['не указано']) . "\n";
            echo "- Allowed Headers: " . implode(', ', $corsConfig['allowed_headers'] ?? ['не указано']) . "\n";
            echo "- Exposed Headers: " . implode(', ', $corsConfig['exposed_headers'] ?? ['не указано']) . "\n";
            echo "- Max Age: " . ($corsConfig['max_age'] ?? 'не указано') . "\n";
            echo "- Supports Credentials: " . ($corsConfig['supports_credentials'] ? 'Да' : 'Нет') . "\n";
        } else {
            echo "❌ Конфигурация CORS не найдена\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Ошибка загрузки Laravel: " . $e->getMessage() . "\n";
        echo "Трассировка:\n" . $e->getTraceAsString() . "\n";
    }
} else {
    echo "❌ Файлы Laravel не найдены\n";
    if (!file_exists($vendorPath)) {
        echo "  - Отсутствует vendor/autoload.php\n";
    }
    if (!file_exists($bootstrapPath)) {
        echo "  - Отсутствует bootstrap/app.php\n";
    }
}

echo "\nТест сессий и валидации завершен.\n";
