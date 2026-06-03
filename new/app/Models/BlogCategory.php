<?php

declare(strict_types=1);

namespace App\Models;

final class BlogCategory extends Model
{
    /** @return array<int, array> */
    public static function all(): array
    {
        return self::db()->query('SELECT * FROM blog_categories ORDER BY name ASC')->fetchAll();
    }

    public static function findBySlug(string $slug): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM blog_categories WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM blog_categories WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }
}
