<?php

declare(strict_types=1);

/**
 * Sync admin login password from .env (ADMIN_EMAIL, ADMIN_PASSWORD).
 * Run on the server: php database/reset-admin-password.php
 */

$isCli = PHP_SAPI === 'cli';
if (!$isCli) {
    http_response_code(403);
    exit('CLI only');
}

require dirname(__DIR__) . '/bootstrap.php';

restore_error_handler();
restore_exception_handler();

use App\Core\Database;
use App\Models\Admin;

$envFile = BASE_PATH . '/.env';
if (!is_readable($envFile)) {
    fwrite(STDERR, "Missing .env at {$envFile}" . PHP_EOL);
    exit(1);
}

$email = (string) env('ADMIN_EMAIL', 'admin@blueaxis.com');
$pass = (string) env('ADMIN_PASSWORD', 'ChangeMe123!');

try {
    $pdo = Database::connection();
} catch (\Throwable $e) {
    fwrite(STDERR, 'Database connection failed: ' . $e->getMessage() . PHP_EOL);
    fwrite(STDERR, 'Check DB_* in .env and that MySQL is running.' . PHP_EOL);
    exit(1);
}

$admin = Admin::findByEmail($email);
if ($admin === null) {
    fwrite(STDERR, "No admin with email {$email}. Run: php database/migrate.php --seed" . PHP_EOL);
    exit(1);
}

$hash = password_hash($pass, PASSWORD_BCRYPT);
$stmt = $pdo->prepare('UPDATE admins SET password = :p WHERE id = :id');
$stmt->execute(['p' => $hash, 'id' => $admin['id']]);

if (!password_verify($pass, $hash)) {
    fwrite(STDERR, 'Password hash verification failed after update.' . PHP_EOL);
    exit(1);
}

echo "Admin password updated for {$email}" . PHP_EOL;
echo 'You can now sign in at: ' . site_url_base() . '/admin/login' . PHP_EOL;
