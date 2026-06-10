<?php

declare(strict_types=1);

namespace App\Models;

final class HeroSlide extends Model
{
    /** @return array<int, array<string, mixed>> */
    public static function active(): array
    {
        return self::db()->query(
            'SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        )->fetchAll();
    }

    /** @return array<int, array<string, mixed>> */
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
            'INSERT INTO hero_slides (
                slide_type, title, subtitle, eyebrow, image_path,
                cta_primary_label, cta_primary_url, cta_secondary_label, cta_secondary_url,
                link_url, link_label, sort_order, is_active
             ) VALUES (
                :slide_type, :title, :subtitle, :eyebrow, :image_path,
                :cta_primary_label, :cta_primary_url, :cta_secondary_label, :cta_secondary_url,
                :link_url, :link_label, :sort_order, :is_active
             )'
        );
        foreach ($slides as $i => $row) {
            $stmt->execute([
                'slide_type' => (string) ($row['slide_type'] ?? 'text'),
                'title' => self::nullableTrim($row['title'] ?? null),
                'subtitle' => self::nullableTrim($row['subtitle'] ?? null),
                'eyebrow' => self::nullableTrim($row['eyebrow'] ?? null),
                'image_path' => self::nullableTrim($row['image_path'] ?? null),
                'cta_primary_label' => self::nullableTrim($row['cta_primary_label'] ?? null),
                'cta_primary_url' => self::nullableTrim($row['cta_primary_url'] ?? null),
                'cta_secondary_label' => self::nullableTrim($row['cta_secondary_label'] ?? null),
                'cta_secondary_url' => self::nullableTrim($row['cta_secondary_url'] ?? null),
                'link_url' => self::nullableTrim($row['cta_primary_url'] ?? $row['link_url'] ?? null),
                'link_label' => self::nullableTrim($row['cta_primary_label'] ?? $row['link_label'] ?? null),
                'sort_order' => $i,
                'is_active' => !empty($row['is_active']) ? 1 : 0,
            ]);
        }
    }

    private static function nullableTrim(mixed $value): ?string
    {
        $text = trim((string) $value);
        return $text === '' ? null : $text;
    }
}
