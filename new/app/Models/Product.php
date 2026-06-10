<?php

declare(strict_types=1);

namespace App\Models;

final class Product extends Model
{
    /** @return array<int, array<string, mixed>> */
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
        if (self::hasCategoryTable()) {
            $rows = self::db()->query(
                'SELECT c.name FROM product_categories c
                 WHERE EXISTS (SELECT 1 FROM products p WHERE p.category = c.name AND p.is_published = 1)
                 ORDER BY c.sort_order ASC, c.name ASC'
            )->fetchAll();
            $names = array_column($rows, 'name');
            if ($names !== []) {
                return $names;
            }
        }
        return self::distinctCategories(true);
    }

    /** @return list<string> */
    public static function distinctCategories(bool $publishedOnly = false): array
    {
        if (self::hasCategoryTable()) {
            return ProductCategory::names();
        }
        $where = "category IS NOT NULL AND category != ''";
        if ($publishedOnly) {
            $where .= ' AND is_published = 1';
        }
        $rows = self::db()->query(
            "SELECT DISTINCT category FROM products WHERE {$where} ORDER BY category"
        )->fetchAll();
        return array_column($rows, 'category');
    }

    private static function hasCategoryTable(): bool
    {
        return ProductCategory::tableExists();
    }

    public static function findPublishedBySlug(string $slug): ?array
    {
        $stmt = self::db()->prepare(
            'SELECT * FROM products WHERE slug = :slug AND is_published = 1 LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function findBySku(string $sku): ?array
    {
        $sku = trim($sku);
        if ($sku === '') {
            return null;
        }
        $stmt = self::db()->prepare('SELECT * FROM products WHERE sku = :sku LIMIT 1');
        $stmt->execute(['sku' => $sku]);
        return $stmt->fetch() ?: null;
    }

    public static function findByTitleExact(string $title): ?array
    {
        $title = trim($title);
        if ($title === '') {
            return null;
        }
        $stmt = self::db()->prepare('SELECT * FROM products WHERE title = :title LIMIT 1');
        $stmt->execute(['title' => $title]);
        return $stmt->fetch() ?: null;
    }

    public static function skuExists(string $sku, ?int $excludeId = null): bool
    {
        $sku = trim($sku);
        if ($sku === '') {
            return false;
        }
        $sql = 'SELECT id FROM products WHERE sku = :sku';
        $params = ['sku' => $sku];
        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $excludeId;
        }
        $sql .= ' LIMIT 1';
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    public static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $base = slugify($title) ?: 'product';
        $candidate = $base;
        $suffix = 2;
        while (self::slugExists($candidate, $excludeId)) {
            $candidate = $base . '-' . $suffix;
            $suffix++;
        }
        return $candidate;
    }

    public static function generateSku(?int $excludeId = null): string
    {
        $stmt = self::db()->query("SELECT sku FROM products WHERE sku IS NOT NULL AND sku != ''");
        $max = 0;
        foreach ($stmt->fetchAll(\PDO::FETCH_COLUMN) as $sku) {
            if (preg_match('/(\d+)\s*$/', (string) $sku, $matches)) {
                $max = max($max, (int) $matches[1]);
            }
        }
        $num = $max + 1;
        do {
            $candidate = 'BAX-' . str_pad((string) $num, 5, '0', STR_PAD_LEFT);
            $num++;
        } while (self::skuExists($candidate, $excludeId));
        return $candidate;
    }

    /**
     * @param array<string, mixed>|null $existing
     * @return array{slug: string, sku: string}
     */
    public static function resolveIdentifiers(string $title, ?array $existing = null): array
    {
        if ($existing === null) {
            return [
                'slug' => self::generateUniqueSlug($title),
                'sku' => self::generateSku(),
            ];
        }

        $id = (int) $existing['id'];
        $titleChanged = trim($title) !== trim((string) $existing['title']);
        $sku = trim((string) ($existing['sku'] ?? ''));

        return [
            'slug' => $titleChanged ? self::generateUniqueSlug($title, $id) : (string) $existing['slug'],
            'sku' => $sku !== '' ? $sku : self::generateSku($id),
        ];
    }

    public static function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $slug = trim($slug);
        if ($slug === '') {
            return false;
        }
        $sql = 'SELECT id FROM products WHERE slug = :slug';
        $params = ['slug' => $slug];
        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $excludeId;
        }
        $sql .= ' LIMIT 1';
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    /** @return array{items: array<int, array<string, mixed>>, total: int} */
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

    /** @param array<string, mixed> $data */
    public static function create(array $data): int
    {
        $stmt = self::db()->prepare(
            'INSERT INTO products (title, slug, category, sku, price, price_unit, excerpt, description, image_path,
             origin_region, pack_format, size, storage_notes, is_featured, is_published, sort_order,
             meta_title, meta_description)
             VALUES (:title, :slug, :category, :sku, :price, :price_unit, :excerpt, :description, :image_path,
             :origin_region, :pack_format, :size, :storage_notes, :is_featured, :is_published, :sort_order,
             :meta_title, :meta_description)'
        );
        $stmt->execute($data);
        return (int) self::db()->lastInsertId();
    }

    /** @param array<string, mixed> $data */
    public static function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = self::db()->prepare(
            'UPDATE products SET title = :title, slug = :slug, category = :category, sku = :sku,
             price = :price, price_unit = :price_unit, excerpt = :excerpt, description = :description, image_path = :image_path,
             origin_region = :origin_region, pack_format = :pack_format, size = :size, storage_notes = :storage_notes,
             is_featured = :is_featured, is_published = :is_published, sort_order = :sort_order,
             meta_title = :meta_title, meta_description = :meta_description WHERE id = :id'
        );
        $stmt->execute($data);
    }

    /**
     * Update only fields supplied by CSV bulk import (preserves description, media, SEO, visibility).
     *
     * @param array<string, mixed> $data
     */
    public static function updateBulkFields(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = self::db()->prepare(
            'UPDATE products SET title = :title, slug = :slug, category = :category, sku = :sku,
             price = :price, price_unit = :price_unit, excerpt = :excerpt,
             origin_region = :origin_region, pack_format = :pack_format, size = :size,
             storage_notes = :storage_notes, sort_order = :sort_order WHERE id = :id'
        );
        $stmt->execute($data);
    }

    /** @param array<string, mixed> $data */
    public static function createFromBulk(array $data): int
    {
        $stmt = self::db()->prepare(
            'INSERT INTO products (title, slug, category, sku, price, price_unit, excerpt, description, image_path,
             origin_region, pack_format, size, storage_notes, is_featured, is_published, sort_order,
             meta_title, meta_description)
             VALUES (:title, :slug, :category, :sku, :price, :price_unit, :excerpt, NULL, NULL,
             :origin_region, :pack_format, :size, :storage_notes, 0, 1, :sort_order, NULL, NULL)'
        );
        $stmt->execute($data);
        return (int) self::db()->lastInsertId();
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
