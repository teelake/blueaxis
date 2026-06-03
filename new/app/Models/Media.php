<?php

declare(strict_types=1);

namespace App\Models;

final class Media extends Model
{
    public static function create(array $data): int
    {
        $stmt = self::db()->prepare(
            'INSERT INTO media (filename, original_name, mime_type, file_size, path, alt_text, uploaded_by)
             VALUES (:filename, :original_name, :mime_type, :file_size, :path, :alt_text, :uploaded_by)'
        );
        $stmt->execute($data);
        return (int) self::db()->lastInsertId();
    }

    /** @return array{items: array<int, array>, total: int} */
    public static function paginate(int $page, int $perPage): array
    {
        $total = (int) self::db()->query('SELECT COUNT(*) FROM media')->fetchColumn();
        $offset = ($page - 1) * $perPage;
        $items = self::db()->query(
            "SELECT m.*, a.name AS uploader_name FROM media m
             LEFT JOIN admins a ON a.id = m.uploaded_by
             ORDER BY m.created_at DESC LIMIT {$perPage} OFFSET {$offset}"
        )->fetchAll();
        return ['items' => $items, 'total' => $total];
    }

    /** @return array<int, array> */
    public static function recent(int $limit = 48): array
    {
        $limit = max(1, min(100, $limit));
        return self::db()->query(
            "SELECT * FROM media ORDER BY created_at DESC LIMIT {$limit}"
        )->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM media WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function delete(int $id): void
    {
        $stmt = self::db()->prepare('DELETE FROM media WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
