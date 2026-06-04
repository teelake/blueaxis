<?php

declare(strict_types=1);

namespace App\Models;

final class Role extends Model
{
    /** @return array<int, array> */
    public static function all(): array
    {
        return self::db()->query('SELECT * FROM roles ORDER BY id')->fetchAll();
    }

    public static function findBySlug(string $slug): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM roles WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }
}
