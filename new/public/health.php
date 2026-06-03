<?php

declare(strict_types=1);

/**
 * Deployment check — visit /new/public/health.php or add route later.
 * Does not use the main router.
 */

require dirname(__DIR__) . '/bootstrap.php';

header('Content-Type: application/json');

$checks = [
    'php_version' => PHP_VERSION,
    'app_url' => config('app.url'),
    'app_base_path' => app_base_path(),
    'request_path' => request_path(),
    'script_name' => $_SERVER['SCRIPT_NAME'] ?? '',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
    'log_file' => \App\Core\ErrorLogger::logFile(),
    'log_exists' => is_file(\App\Core\ErrorLogger::logFile()),
    'log_writable' => false,
    'storage_writable' => is_writable(storage_path('logs')),
    'env_file' => is_readable(BASE_PATH . '/.env'),
    'pdo_mysql' => extension_loaded('pdo_mysql'),
    'front_controller' => is_readable(BASE_PATH . '/index.php'),
    'htaccess_new' => is_readable(BASE_PATH . '/.htaccess'),
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
