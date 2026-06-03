<?php

declare(strict_types=1);

namespace App\Models;

final class BlogTag extends Model
{
    /** @return array<int, array> */
    public static function forPost(int $postId): array
    {
        $stmt = self::db()->prepare(
            'SELECT t.* FROM blog_tags t
             INNER JOIN blog_post_tags pt ON pt.tag_id = t.id
             WHERE pt.post_id = :post_id ORDER BY t.name'
        );
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll();
    }

    /** @param list<string> $names */
    public static function syncForPost(int $postId, array $names): void
    {
        $pdo = self::db();
        $pdo->prepare('DELETE FROM blog_post_tags WHERE post_id = :id')->execute(['id' => $postId]);

        foreach ($names as $raw) {
            $name = trim($raw);
            if ($name === '') {
                continue;
            }
            $slug = slugify($name);
            $tagId = self::findOrCreate($name, $slug);
            $pdo->prepare(
                'INSERT IGNORE INTO blog_post_tags (post_id, tag_id) VALUES (:post_id, :tag_id)'
            )->execute(['post_id' => $postId, 'tag_id' => $tagId]);
        }
    }

    public static function findOrCreate(string $name, string $slug): int
    {
        $stmt = self::db()->prepare('SELECT id FROM blog_tags WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        if ($row) {
            return (int) $row['id'];
        }
        self::db()->prepare('INSERT INTO blog_tags (name, slug) VALUES (:name, :slug)')
            ->execute(['name' => $name, 'slug' => $slug]);
        return (int) self::db()->lastInsertId();
    }

    /** @return array<int, string> */
    public static function namesForPost(int $postId): array
    {
        return array_column(self::forPost($postId), 'name');
    }
}
