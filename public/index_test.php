<?php
// index_test.php - Тестирование маршрутизации через index.php
header('Content-Type: text/plain');

echo "Index.php Routing Test - " . date('Y-m-d H:i:s') . "\n\n";

// Проверка наличия и доступности index.php
$indexPath = __DIR__ . '/index.php';
echo "Проверка файла index.php:\n";
if (file_exists($indexPath)) {
    echo "✓ Файл index.php существует\n";
    echo "  Размер: " . filesize($indexPath) . " байт\n";
    echo "  Права доступа: " . substr(sprintf('%o', fileperms($indexPath)), -4) . "\n";
    echo "  Владелец: " . posix_getpwuid(fileowner($indexPath))['name'] . "\n";
    echo "  Группа: " . posix_getgrgid(filegroup($indexPath))['name'] . "\n\n";
} else {
    echo "❌ Файл index.php не существует\n\n";
}

// Создаем тестовый API-маршрут напрямую в этом файле
echo "Создание тестового API-маршрута:\n";
echo "- URL: " . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/direct_api_test.php\n";
echo "- Метод: POST\n";
echo "- Данные: {\"message\": \"test\"}\n\n";

// Создаем тестовый API-файл
$testApiPath = __DIR__ . '/direct_api_test.php';
$testApiContent = <<<'EOD'
<?php
header('Content-Type: application/json');

// Получаем данные запроса
$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? 'No message provided';

// Отвечаем JSON
echo json_encode([
    'status' => 'success',
    'received' => $message,
    'timestamp' => date('Y-m-d H:i:s'),
    'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
]);
EOD;

// Записываем файл
if (file_put_contents($testApiPath, $testApiContent) !== false) {
    echo "✓ Файл direct_api_test.php создан\n";
    echo "  Размер: " . filesize($testApiPath) . " байт\n";
    echo "  Права доступа: " . substr(sprintf('%o', fileperms($testApiPath)), -4) . "\n\n";
} else {
    echo "❌ Не удалось создать файл direct_api_test.php\n\n";
}

// Проверяем наличие и содержимое .htaccess
$htaccessPath = __DIR__ . '/.htaccess';
echo "Проверка файла .htaccess:\n";
if (file_exists($htaccessPath)) {
    echo "✓ Файл .htaccess существует\n";
    echo "  Размер: " . filesize($htaccessPath) . " байт\n";
    echo "  Содержимое:\n";
    echo "  " . str_replace("\n", "\n  ", file_get_contents($htaccessPath)) . "\n\n";
} else {
    echo "❌ Файл .htaccess не существует\n\n";
}

// Проверяем окружение PHP-FPM
echo "Проверка окружения PHP-FPM:\n";
echo "- PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "- SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "- SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "- DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "- SERVER_SOFTWARE: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "- GATEWAY_INTERFACE: " . ($_SERVER['GATEWAY_INTERFACE'] ?? 'Unknown') . "\n\n";

// Проверяем, может ли PHP выполнить системные команды
echo "Проверка возможности выполнения системных команд:\n";
if (function_exists('shell_exec') && !in_array('shell_exec', explode(',', ini_get('disable_functions')))) {
    echo "✓ Функция shell_exec доступна\n";
    
    // Проверяем наличие файла index.php через ls
    $lsOutput = shell_exec('ls -la ' . escapeshellarg(__DIR__));
    echo "  Вывод ls -la:\n";
    echo "  " . str_replace("\n", "\n  ", $lsOutput) . "\n\n";
    
    // Проверяем конфигурацию Nginx
    echo "  Проверка конфигурации Nginx:\n";
    $nginxOutput = shell_exec('find /etc/nginx -type f -name "*.conf" 2>/dev/null | xargs grep -l "location" 2>/dev/null | head -5');
    if ($nginxOutput) {
        echo "  Найдены конфигурационные файлы Nginx:\n";
        echo "  " . str_replace("\n", "\n  ", $nginxOutput) . "\n\n";
    } else {
        echo "  Не удалось найти конфигурационные файлы Nginx\n\n";
    }
} else {
    echo "❌ Функция shell_exec недоступна\n\n";
}

echo "Тест маршрутизации через index.php завершен.\n";
echo "Пожалуйста, проверьте созданный файл direct_api_test.php для тестирования прямого API-запроса.\n";
