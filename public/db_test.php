<?php
// db_test.php - Диагностический скрипт для проверки подключения к Neon DB
header('Content-Type: text/plain');

try {
    $host = 'ep-small-thunder-a4xssxb3-pooler.us-east-1.aws.neon.tech';
    $db   = 'neondb';
    $user = 'neondb_owner';
    $pass = 'npg_d4my6EWUeDvM';
    $port = 5432;
    
    echo "Время начала теста: " . date('Y-m-d H:i:s') . "\n";
    echo "Параметры подключения:\n";
    echo "- Host: $host\n";
    echo "- Database: $db\n";
    echo "- User: $user\n";
    echo "- Port: $port\n";
    echo "- SSL Mode: require\n\n";
    
    // Тест 1: Базовое подключение с SSL
    echo "Тест 1: Базовое подключение с SSL...\n";
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require;connect_timeout=30";
    $start = microtime(true);
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $elapsed = microtime(true) - $start;
    echo "✓ Подключение успешно! Время: " . round($elapsed, 2) . " сек.\n\n";
    
    // Тест 2: Выполнение простого запроса
    echo "Тест 2: Выполнение простого запроса...\n";
    $start = microtime(true);
    $stmt = $pdo->query('SELECT version()');
    $version = $stmt->fetchColumn();
    $elapsed = microtime(true) - $start;
    echo "✓ Запрос выполнен успешно! Время: " . round($elapsed, 2) . " сек.\n";
    echo "PostgreSQL версия: $version\n\n";
    
    // Тест 3: Проверка существования таблиц
    echo "Тест 3: Проверка существования таблиц...\n";
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' LIMIT 5");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (count($tables) > 0) {
        echo "✓ Найдены таблицы: " . implode(", ", $tables) . "\n\n";
    } else {
        echo "⚠ Таблицы в схеме 'public' не найдены\n\n";
    }
    
    // Тест 4: Проверка задержки сети
    echo "Тест 4: Проверка задержки сети (10 запросов)...\n";
    $start = microtime(true);
    for ($i = 0; $i < 10; $i++) {
        $pdo->query('SELECT 1');
    }
    $elapsed = microtime(true) - $start;
    $avg = $elapsed / 10;
    echo "✓ Средняя задержка: " . round($avg, 3) . " сек. на запрос\n";
    echo "  Общее время: " . round($elapsed, 2) . " сек. для 10 запросов\n\n";
    
    echo "Все тесты подключения к базе данных выполнены успешно!\n";
    echo "Время окончания теста: " . date('Y-m-d H:i:s') . "\n";
    
} catch (PDOException $e) {
    echo "❌ Ошибка подключения: " . $e->getMessage() . "\n";
    
    // Дополнительная диагностика
    echo "\nДополнительная диагностика:\n";
    
    // Проверка DNS
    echo "Проверка DNS для $host...\n";
    $ip = gethostbyname($host);
    if ($ip != $host) {
        echo "✓ DNS разрешен: $ip\n";
    } else {
        echo "❌ Не удалось разрешить DNS для $host\n";
    }
    
    // Проверка соединения через сокет
    echo "Проверка соединения через сокет...\n";
    $socket = @fsockopen($host, $port, $errno, $errstr, 5);
    if ($socket) {
        echo "✓ Соединение через сокет успешно\n";
        fclose($socket);
    } else {
        echo "❌ Ошибка соединения через сокет: $errstr ($errno)\n";
    }
}
