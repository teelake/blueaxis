<?php

declare(strict_types=1);

namespace App\Models;

final class Contact extends Model
{
    public static function create(array $data): int
    {
        $stmt = self::db()->prepare(
            'INSERT INTO contacts (name, company, email, phone, message) VALUES (:name, :company, :email, :phone, :message)'
        );
        $stmt->execute($data);
        return (int) self::db()->lastInsertId();
    }

    public static function count(): int
    {
        return (int) self::db()->query('SELECT COUNT(*) FROM contacts')->fetchColumn();
    }

    public static function countUnread(): int
    {
        return (int) self::db()->query('SELECT COUNT(*) FROM contacts WHERE is_read = 0')->fetchColumn();
    }

    /** @return array{items: array<int, array>, total: int} */
    public static function paginate(int $page, int $perPage, string $search = ''): array
    {
        $where = '1=1';
        $params = [];
        if ($search !== '') {
            $where = '(name LIKE :q OR company LIKE :q OR email LIKE :q OR message LIKE :q)';
            $params['q'] = '%' . $search . '%';
        }
        $countStmt = self::db()->prepare("SELECT COUNT(*) FROM contacts WHERE {$where}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $offset = ($page - 1) * $perPage;
        $stmt = self::db()->prepare(
            "SELECT * FROM contacts WHERE {$where} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);
        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM contacts WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function markRead(int $id): void
    {
        $stmt = self::db()->prepare('UPDATE contacts SET is_read = 1 WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    /** @return array<int, array> */
    public static function exportAll(): array
    {
        return self::db()->query('SELECT * FROM contacts ORDER BY created_at DESC')->fetchAll();
    }
}
