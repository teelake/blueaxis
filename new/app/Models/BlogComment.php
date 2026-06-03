<?php

declare(strict_types=1);

namespace App\Models;

final class BlogComment extends Model
{
    public static function create(array $data): int
    {
        $stmt = self::db()->prepare(
            'INSERT INTO blog_comments (post_id, author_name, email, body, status, ip_address)
             VALUES (:post_id, :author_name, :email, :body, :status, :ip_address)'
        );
        $stmt->execute($data);
        return (int) self::db()->lastInsertId();
    }

    /** @return array<int, array> */
    public static function forPost(int $postId, ?string $status = null): array
    {
        $sql = 'SELECT * FROM blog_comments WHERE post_id = :post_id';
        $params = ['post_id' => $postId];
        if ($status !== null) {
            $sql .= ' AND status = :status';
            $params['status'] = $status;
        }
        $sql .= ' ORDER BY created_at DESC';
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** @return array<int, array> */
    public static function approvedForPost(int $postId): array
    {
        return self::forPost($postId, 'approved');
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM blog_comments WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function updateStatus(int $id, string $status): void
    {
        $stmt = self::db()->prepare('UPDATE blog_comments SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public static function delete(int $id): void
    {
        $stmt = self::db()->prepare('DELETE FROM blog_comments WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function countPending(): int
    {
        return (int) self::db()->query(
            "SELECT COUNT(*) FROM blog_comments WHERE status = 'pending'"
        )->fetchColumn();
    }
}
