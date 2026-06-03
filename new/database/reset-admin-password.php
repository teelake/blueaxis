<?php

declare(strict_types=1);

/**
 * Sync admin login password from .env (ADMIN_EMAIL, ADMIN_PASSWORD).
 * Run after changing .env or if admin login fails: php database/reset-admin-password.php
 */

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\Database;

$email = env('ADMIN_EMAIL', 'admin@blueaxis.com');
$pass = env('ADMIN_PASSWORD', 'ChangeMe123!');
$hash = password_hash($pass, PASSWORD_BCRYPT);

$pdo = Database::connection();
$stmt = $pdo->prepare('UPDATE admins SET password = :p WHERE email = :e');
$stmt->execute(['p' => $hash, 'e' => $email]);

if ($stmt->rowCount() === 0) {
    fwrite(STDERR, "No admin found with email {$email}. Run migrate with --seed first." . PHP_EOL);
    exit(1);
}

echo "Admin password updated for {$email}" . PHP_EOL;
