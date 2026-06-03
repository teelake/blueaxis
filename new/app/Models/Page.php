<?php

declare(strict_types=1);

namespace App\Models;

final class Page extends Model
{
    public static function findBySlug(string $slug): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM pages WHERE slug = :slug AND is_published = 1 LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function updateSeo(int $id, array $data): void
    {
        $stmt = self::db()->prepare(
            'UPDATE pages SET meta_title = :meta_title, meta_description = :meta_description,
             og_image = :og_image, canonical_url = :canonical_url WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'og_image' => $data['og_image'] ?? null,
            'canonical_url' => $data['canonical_url'] ?? null,
        ]);
    }

    /** @return array<int, array> */
    public static function all(): array
    {
        return self::db()->query('SELECT * FROM pages ORDER BY id ASC')->fetchAll();
    }
}
