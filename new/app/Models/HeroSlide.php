<?php

declare(strict_types=1);

namespace App\Models;

final class HeroSlide extends Model
{
    /** @return array<int, array> */
    public static function active(): array
    {
        return self::db()->query(
            'SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        )->fetchAll();
    }

    /** @return array<int, array> */
    public static function allOrdered(): array
    {
        return self::db()->query(
            'SELECT * FROM hero_slides ORDER BY sort_order ASC, id ASC'
        )->fetchAll();
    }

    /** @param list<array<string, mixed>> $slides */
    public static function syncAll(array $slides): void
    {
        $pdo = self::db();
        $pdo->exec('DELETE FROM hero_slides');
        $stmt = $pdo->prepare(
            'INSERT INTO hero_slides (title, subtitle, image_path, link_url, link_label, sort_order, is_active)
             VALUES (:title, :subtitle, :image_path, :link_url, :link_label, :sort_order, :is_active)'
        );
        foreach ($slides as $i => $row) {
            $image = trim((string) ($row['image_path'] ?? ''));
            if ($image === '') {
                continue;
            }
            $stmt->execute([
                'title' => trim((string) ($row['title'] ?? '')) ?: null,
                'subtitle' => trim((string) ($row['subtitle'] ?? '')) ?: null,
                'image_path' => $image,
                'link_url' => trim((string) ($row['link_url'] ?? '')) ?: null,
                'link_label' => trim((string) ($row['link_label'] ?? '')) ?: null,
                'sort_order' => $i,
                'is_active' => !empty($row['is_active']) ? 1 : 0,
            ]);
        }
    }
}
