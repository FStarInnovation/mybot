<?php
// Простой health check для Laravel Cloud
header('Content-Type: application/json');
echo json_encode([
    'status'     => 'ok',
    'timestamp'  => date(DATE_ISO8601),
    'framework'  => 'Laravel ' . (defined('LARAVEL_START') ? app()->version() : 'standalone'),
]);
