<?php
// api_route_test.php - Диагностический скрипт для проверки маршрутизации API
header('Content-Type: text/plain');

echo "API Route Test - " . date('Y-m-d H:i:s') . "\n\n";

// Получаем информацию о запросе
echo "Информация о запросе:\n";
echo "- REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "- REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "- SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "- SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "- DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n\n";

// Проверяем наличие файла index.php
echo "Проверка файла index.php:\n";
$indexPath = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
if (file_exists($indexPath)) {
    echo "✓ Файл $indexPath существует\n";
    echo "  Размер: " . filesize($indexPath) . " байт\n";
    echo "  Права доступа: " . substr(sprintf('%o', fileperms($indexPath)), -4) . "\n";
} else {
    echo "❌ Файл $indexPath не существует\n";
}
echo "\n";

// Проверяем наличие файла .htaccess
echo "Проверка файла .htaccess:\n";
$htaccessPath = $_SERVER['DOCUMENT_ROOT'] . '/.htaccess';
if (file_exists($htaccessPath)) {
    echo "✓ Файл $htaccessPath существует\n";
    echo "  Размер: " . filesize($htaccessPath) . " байт\n";
    echo "  Содержимое:\n";
    echo "  " . str_replace("\n", "\n  ", file_get_contents($htaccessPath)) . "\n";
} else {
    echo "❌ Файл $htaccessPath не существует\n";
}
echo "\n";

// Проверяем конфигурацию PHP
echo "Конфигурация PHP:\n";
echo "- PHP версия: " . phpversion() . "\n";
echo "- Загруженные расширения: " . implode(", ", get_loaded_extensions()) . "\n";
echo "- display_errors: " . ini_get('display_errors') . "\n";
echo "- error_reporting: " . ini_get('error_reporting') . "\n";
echo "- max_execution_time: " . ini_get('max_execution_time') . "\n\n";

// Проверяем маршруты Laravel (если возможно)
echo "Проверка маршрутов Laravel:\n";
try {
    // Попытка загрузить Laravel
    $basePath = realpath($_SERVER['DOCUMENT_ROOT'] . '/..');
    if (file_exists($basePath . '/bootstrap/app.php')) {
        echo "✓ Файл bootstrap/app.php найден\n";
        
        // Попытка вывести список маршрутов API
        echo "Попытка вывести список маршрутов API...\n";
        echo "Это может не сработать из-за ограничений окружения\n";
        
        // Проверяем наличие файлов маршрутов
        if (file_exists($basePath . '/routes/api.php')) {
            echo "✓ Файл routes/api.php найден\n";
            echo "  Содержимое:\n";
            echo "  " . str_replace("\n", "\n  ", file_get_contents($basePath . '/routes/api.php')) . "\n";
        } else {
            echo "❌ Файл routes/api.php не найден\n";
        }
        
        if (file_exists($basePath . '/routes/web.php')) {
            echo "✓ Файл routes/web.php найден\n";
            echo "  Содержимое:\n";
            echo "  " . str_replace("\n", "\n  ", file_get_contents($basePath . '/routes/web.php')) . "\n";
        } else {
            echo "❌ Файл routes/web.php не найден\n";
        }
    } else {
        echo "❌ Файл bootstrap/app.php не найден\n";
    }
} catch (Exception $e) {
    echo "❌ Ошибка при проверке маршрутов Laravel: " . $e->getMessage() . "\n";
}
echo "\n";

echo "Тест маршрутизации API завершен.\n";
