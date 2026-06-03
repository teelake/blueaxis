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
    echo "Admin user seeded (login uses password hash in admins table)." . PHP_EOL;
    echo "Default: admin@blueaxis.com / ChangeMe123! — run php database/set-admin-password.php to change." . PHP_EOL;
}

echo "Done." . PHP_EOL;
