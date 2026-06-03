<?php

declare(strict_types=1);

namespace App\Models;

final class ContentBlock extends Model
{
    /** @return array<string, array<string, mixed>> */
    public static function forPage(string $pageSlug): array
    {
        $stmt = self::db()->prepare(
            'SELECT section_key, block_key, content, content_type
             FROM content_blocks WHERE page_slug = :page ORDER BY sort_order ASC'
        );
        $stmt->execute(['page' => $pageSlug]);
        $sections = [];
        foreach ($stmt->fetchAll() as $row) {
            $sections[$row['section_key']][$row['block_key']] = [
                'content' => $row['content'],
                'type' => $row['content_type'],
            ];
        }
        return $sections;
    }

    public static function get(string $page, string $section, string $block, string $default = ''): string
    {
        $stmt = self::db()->prepare(
            'SELECT content FROM content_blocks
             WHERE page_slug = :page AND section_key = :section AND block_key = :block LIMIT 1'
        );
        $stmt->execute(['page' => $page, 'section' => $section, 'block' => $block]);
        $row = $stmt->fetch();
        return $row ? (string) $row['content'] : $default;
    }

    public static function upsert(string $page, string $section, string $block, ?string $content, string $type = 'text'): void
    {
        $stmt = self::db()->prepare(
            'INSERT INTO content_blocks (page_slug, section_key, block_key, content, content_type)
             VALUES (:page, :section, :block, :content, :type)
             ON DUPLICATE KEY UPDATE content = VALUES(content), content_type = VALUES(content_type)'
        );
        $stmt->execute([
            'page' => $page,
            'section' => $section,
            'block' => $block,
            'content' => $content,
            'type' => $type,
        ]);
    }
}
