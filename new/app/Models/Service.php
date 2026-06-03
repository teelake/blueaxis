<?php

declare(strict_types=1);

namespace App\Models;

final class Service extends Model
{
    /** @return array<int, array> */
    public static function published(): array
    {
        return self::db()->query(
            'SELECT * FROM services WHERE is_published = 1 ORDER BY sort_order ASC, id ASC'
        )->fetchAll();
    }

    public static function findBySlug(string $slug): ?array
    {
        $stmt = self::db()->prepare(
            'SELECT * FROM services WHERE slug = :slug AND is_published = 1 LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        if ($row && !empty($row['benefits'])) {
            $row['benefits_list'] = json_decode((string) $row['benefits'], true) ?: [];
        }
        return $row ?: null;
    }

    /** @return array<int, array> */
    public static function allAdmin(): array
    {
        return self::db()->query('SELECT * FROM services ORDER BY sort_order ASC')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM services WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = self::db()->prepare(
            'INSERT INTO services (title, slug, excerpt, description, benefits, banner_image, icon,
             meta_title, meta_description, sort_order, is_published)
             VALUES (:title, :slug, :excerpt, :description, :benefits, :banner_image, :icon,
             :meta_title, :meta_description, :sort_order, :is_published)'
        );
        $stmt->execute($data);
        return (int) self::db()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = self::db()->prepare(
            'UPDATE services SET title = :title, slug = :slug, excerpt = :excerpt, description = :description,
             benefits = :benefits, banner_image = :banner_image, icon = :icon, meta_title = :meta_title,
             meta_description = :meta_description, sort_order = :sort_order, is_published = :is_published
             WHERE id = :id'
        );
        $stmt->execute($data);
    }

    public static function delete(int $id): void
    {
        $stmt = self::db()->prepare('DELETE FROM services WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
