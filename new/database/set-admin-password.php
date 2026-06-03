<?php

declare(strict_types=1);

/**
 * Set admin login password in the database (not .env).
 * CLI: php database/set-admin-password.php admin@blueaxis.com 'YourNewPassword'
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

require dirname(__DIR__) . '/bootstrap.php';

restore_error_handler();
restore_exception_handler();

use App\Core\Database;
use App\Models\Admin;

$email = $argv[1] ?? '';
$password = $argv[2] ?? '';

if ($email === '' || $password === '') {
    fwrite(STDERR, "Usage: php database/set-admin-password.php <email> <password>" . PHP_EOL);
    exit(1);
}

if (strlen($password) < 8) {
    fwrite(STDERR, 'Password must be at least 8 characters.' . PHP_EOL);
    exit(1);
}

try {
    Database::connection();
} catch (\Throwable $e) {
    fwrite(STDERR, 'Database connection failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

$admin = Admin::findByEmail($email);
if ($admin === null) {
    fwrite(STDERR, "No admin found for {$email}. Check the admins table or run migrate with --seed." . PHP_EOL);
    exit(1);
}

Admin::setPassword((int) $admin['id'], $password);

echo "Password updated in database for {$email}" . PHP_EOL;
echo 'Sign in at: ' . site_url_base() . '/admin/login' . PHP_EOL;
