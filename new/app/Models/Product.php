<?php

declare(strict_types=1);

namespace App\Models;

final class Product extends Model
{
    /** @return array<int, array> */
    public static function published(?string $category = null, ?string $search = null): array
    {
        $where = ['is_published = 1'];
        $params = [];
        if ($category !== null && $category !== '') {
            $where[] = 'category = :category';
            $params['category'] = $category;
        }
        if ($search !== null && $search !== '') {
            $where[] = '(title LIKE :q OR excerpt LIKE :q OR sku LIKE :q OR category LIKE :q)';
            $params['q'] = '%' . $search . '%';
        }
        $sql = 'SELECT * FROM products WHERE ' . implode(' AND ', $where)
            . ' ORDER BY is_featured DESC, sort_order ASC, title ASC';
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** @return list<string> */
    public static function categories(): array
    {
        $rows = self::db()->query(
            "SELECT DISTINCT category FROM products WHERE is_published = 1 AND category IS NOT NULL AND category != '' ORDER BY category"
        )->fetchAll();
        return array_column($rows, 'category');
    }

    public static function findPublishedBySlug(string $slug): ?array
    {
        $stmt = self::db()->prepare(
            'SELECT * FROM products WHERE slug = :slug AND is_published = 1 LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    /** @return array{items: array<int, array>, total: int} */
    public static function allAdmin(int $page, int $perPage, string $search = ''): array
    {
        $where = '1=1';
        $params = [];
        if ($search !== '') {
            $where = '(title LIKE :q OR category LIKE :q OR sku LIKE :q)';
            $params['q'] = '%' . $search . '%';
        }
        $countStmt = self::db()->prepare("SELECT COUNT(*) FROM products WHERE {$where}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $offset = max(0, ($page - 1) * $perPage);
        $stmt = self::db()->prepare(
            "SELECT * FROM products WHERE {$where} ORDER BY sort_order ASC, title ASC LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);
        return ['items' => $stmt->fetchAll(), 'total' => $total];
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = self::db()->prepare(
            'INSERT INTO products (title, slug, category, sku, excerpt, description, image_path,
             origin_region, pack_format, storage_notes, is_featured, is_published, sort_order,
             meta_title, meta_description)
             VALUES (:title, :slug, :category, :sku, :excerpt, :description, :image_path,
             :origin_region, :pack_format, :storage_notes, :is_featured, :is_published, :sort_order,
             :meta_title, :meta_description)'
        );
        $stmt->execute($data);
        return (int) self::db()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = self::db()->prepare(
            'UPDATE products SET title = :title, slug = :slug, category = :category, sku = :sku,
             excerpt = :excerpt, description = :description, image_path = :image_path,
             origin_region = :origin_region, pack_format = :pack_format, storage_notes = :storage_notes,
             is_featured = :is_featured, is_published = :is_published, sort_order = :sort_order,
             meta_title = :meta_title, meta_description = :meta_description WHERE id = :id'
        );
        $stmt->execute($data);
    }

    public static function delete(int $id): void
    {
        $stmt = self::db()->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function togglePublished(int $id): void
    {
        $stmt = self::db()->prepare(
            'UPDATE products SET is_published = IF(is_published = 1, 0, 1) WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
    }
}
