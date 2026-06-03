<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\Database;

$pdo = Database::connection();
$files = glob(dirname(__DIR__) . '/database/migrations/*.sql') ?: [];
sort($files);

foreach ($files as $file) {
    echo "Running: " . basename($file) . PHP_EOL;
    $sql = file_get_contents($file);
    $pdo->exec($sql);
}

$seed = dirname(__DIR__) . '/database/seeds/001_seed.sql';
if (is_readable($seed) && ($argv[1] ?? '') === '--seed') {
    echo "Seeding..." . PHP_EOL;
    $pdo->exec(file_get_contents($seed));
    $email = env('ADMIN_EMAIL', 'admin@blueaxis.com');
    $pass = env('ADMIN_PASSWORD', 'ChangeMe123!');
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE admins SET password = :p WHERE email = :e');
    $stmt->execute(['p' => $hash, 'e' => $email]);
    echo "Admin password set for {$email}" . PHP_EOL;
}

echo "Done." . PHP_EOL;
