<?php

declare(strict_types=1);

/**
 * Deployment check — delete or protect this file on production after setup.
 * Visit: /health.php
 */

require dirname(__DIR__) . '/bootstrap.php';

header('Content-Type: application/json');

$checks = [
    'php_version' => PHP_VERSION,
    'log_file' => \App\Core\ErrorLogger::logFile(),
    'log_writable' => false,
    'storage_writable' => is_writable(storage_path('logs')),
    'env_file' => is_readable(BASE_PATH . '/.env'),
    'pdo_mysql' => extension_loaded('pdo_mysql'),
];

$logDir = dirname(\App\Core\ErrorLogger::logFile());
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}
$checks['log_writable'] = is_writable($logDir);

try {
    \App\Core\Database::connection()->query('SELECT 1');
    $checks['database'] = 'ok';
} catch (\Throwable $e) {
    $checks['database'] = 'failed: ' . $e->getMessage();
    \App\Core\ErrorLogger::logThrowable($e);
}

$checks['ok'] = $checks['storage_writable'] && $checks['env_file'] && $checks['pdo_mysql'] && $checks['database'] === 'ok';

http_response_code($checks['ok'] ? 200 : 503);
echo json_encode($checks, JSON_PRETTY_PRINT);
