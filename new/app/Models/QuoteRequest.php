<?php

declare(strict_types=1);

namespace App\Models;

final class QuoteRequest extends Model
{
    public static function create(array $data): int
    {
        $stmt = self::db()->prepare(
            'INSERT INTO quote_requests (name, company, email, phone, service_needed, message, products_json)
             VALUES (:name, :company, :email, :phone, :service_needed, :message, :products_json)'
        );
        $stmt->execute([
            'name' => $data['name'],
            'company' => $data['company'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'service_needed' => $data['service_needed'],
            'message' => $data['message'],
            'products_json' => $data['products_json'] ?? null,
        ]);
        return (int) self::db()->lastInsertId();
    }

    public static function count(): int
    {
        return (int) self::db()->query('SELECT COUNT(*) FROM quote_requests')->fetchColumn();
    }

    public static function countByStatus(string $status): int
    {
        $stmt = self::db()->prepare('SELECT COUNT(*) FROM quote_requests WHERE status = :status');
        $stmt->execute(['status' => $status]);
        return (int) $stmt->fetchColumn();
    }

    /** @return array{items: array<int, array>, total: int} */
    public static function paginate(int $page, int $perPage, string $search = '', string $status = ''): array
    {
        $parts = ['1=1'];
        $params = [];
        if ($search !== '') {
            $parts[] = '(name LIKE :q OR company LIKE :q OR email LIKE :q OR service_needed LIKE :q)';
            $params['q'] = '%' . $search . '%';
        }
        if ($status !== '') {
            $parts[] = 'status = :status';
            $params['status'] = $status;
        }
        $where = implode(' AND ', $parts);
        $countStmt = self::db()->prepare("SELECT COUNT(*) FROM quote_requests WHERE {$where}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $offset = ($page - 1) * $perPage;
        $stmt = self::db()->prepare(
            "SELECT * FROM quote_requests WHERE {$where} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);
        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM quote_requests WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function updateStatus(int $id, string $status, ?string $notes = null): void
    {
        $stmt = self::db()->prepare(
            'UPDATE quote_requests SET status = :status, admin_notes = COALESCE(:notes, admin_notes) WHERE id = :id'
        );
        $stmt->execute(['id' => $id, 'status' => $status, 'notes' => $notes]);
    }

    /** @return array<int, array> */
    public static function exportAll(): array
    {
        return self::db()->query('SELECT * FROM quote_requests ORDER BY created_at DESC')->fetchAll();
    }
}
