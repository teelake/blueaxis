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
    'app_url_recommended' => site_url_base(),
    'app_install_path' => app_install_path(),
    'app_public_web_path' => app_public_web_path(),
    'app_base_path' => app_base_path(),
    'site_url_base' => site_url_base(),
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
    'asset_css_url' => asset('css/app.css'),
    'asset_css_exists' => is_readable(PUBLIC_PATH . '/assets/css/app.css'),
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

$sessionPath = session_save_path() !== '' ? session_save_path() : sys_get_temp_dir();
$checks['session'] = [
    'save_path' => $sessionPath,
    'save_path_writable' => is_writable($sessionPath),
    'cookie_path' => app_install_path() !== '' ? app_install_path() : '/',
];

$adminEmail = 'admin@blueaxis.com';
try {
    $admin = \App\Models\Admin::findByEmail($adminEmail);
    $checks['admin_login'] = [
        'source' => 'database (admins.password bcrypt hash)',
        'default_email' => $adminEmail,
        'exists' => $admin !== null,
        'active' => $admin !== null && (bool) ($admin['is_active'] ?? false),
        'password_hash_valid' => $admin !== null && str_starts_with((string) ($admin['password'] ?? ''), '$2y$'),
        'reset_password_cli' => 'php database/set-admin-password.php admin@blueaxis.com \'YourPassword\'',
    ];
} catch (\Throwable $e) {
    $checks['admin_login'] = ['error' => $e->getMessage()];
}

$checks['admin_login_url'] = site_url_base() . '/admin/login';
$checks['ok'] = $checks['storage_writable'] && $checks['env_file'] && $checks['pdo_mysql'] && $checks['database'] === 'ok';

http_response_code($checks['ok'] ? 200 : 503);
echo json_encode($checks, JSON_PRETTY_PRINT);
