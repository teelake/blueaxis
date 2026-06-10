<?php

declare(strict_types=1);

namespace App\Models;

use PDOException;

final class ProductCategory extends Model
{
    public static function tableExists(): bool
    {
        static $exists = null;
        if ($exists !== null) {
            return $exists;
        }
        try {
            self::db()->query('SELECT 1 FROM product_categories LIMIT 1');
            $exists = true;
        } catch (PDOException) {
            $exists = false;
        }
        return $exists;
    }

    /** @return array<int, array<string, mixed>> */
    public static function allOrdered(): array
    {
        if (!self::tableExists()) {
            return [];
        }
        return self::db()->query(
            'SELECT * FROM product_categories ORDER BY sort_order ASC, name ASC'
        )->fetchAll();
    }

    /** @return list<string> */
    public static function names(): array
    {
        return array_column(self::allOrdered(), 'name');
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM product_categories WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function findByName(string $name): ?array
    {
        $name = trim($name);
        if ($name === '') {
            return null;
        }
        $stmt = self::db()->prepare('SELECT * FROM product_categories WHERE name = :name LIMIT 1');
        $stmt->execute(['name' => $name]);
        return $stmt->fetch() ?: null;
    }

    public static function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $slug = trim($slug);
        if ($slug === '') {
            return false;
        }
        $sql = 'SELECT id FROM product_categories WHERE slug = :slug';
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

    public static function nameExists(string $name, ?int $excludeId = null): bool
    {
        $name = trim($name);
        if ($name === '') {
            return false;
        }
        $sql = 'SELECT id FROM product_categories WHERE name = :name';
        $params = ['name' => $name];
        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $excludeId;
        }
        $sql .= ' LIMIT 1';
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    /** @return array<int, array<string, mixed>> */
    public static function allWithProductCounts(): array
    {
        if (!self::tableExists()) {
            return [];
        }
        $rows = self::db()->query(
            'SELECT c.*, COUNT(p.id) AS product_count
             FROM product_categories c
             LEFT JOIN products p ON p.category = c.name
             GROUP BY c.id
             ORDER BY c.sort_order ASC, c.name ASC'
        )->fetchAll();
        return $rows;
    }

    public static function productCount(string $name): int
    {
        $stmt = self::db()->prepare('SELECT COUNT(*) FROM products WHERE category = :name');
        $stmt->execute(['name' => $name]);
        return (int) $stmt->fetchColumn();
    }

    /** @param array{name: string, slug: string, sort_order: int} $data */
    public static function create(array $data): int
    {
        $stmt = self::db()->prepare(
            'INSERT INTO product_categories (name, slug, sort_order) VALUES (:name, :slug, :sort_order)'
        );
        $stmt->execute($data);
        return (int) self::db()->lastInsertId();
    }

    /** @param array{name: string, slug: string, sort_order: int} $data */
    public static function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = self::db()->prepare(
            'UPDATE product_categories SET name = :name, slug = :slug, sort_order = :sort_order WHERE id = :id'
        );
        $stmt->execute($data);
    }

    public static function renameProducts(string $oldName, string $newName): void
    {
        if ($oldName === $newName) {
            return;
        }
        $stmt = self::db()->prepare('UPDATE products SET category = :new WHERE category = :old');
        $stmt->execute(['new' => $newName, 'old' => $oldName]);
    }

    public static function clearProductsCategory(string $name): void
    {
        $stmt = self::db()->prepare('UPDATE products SET category = NULL WHERE category = :name');
        $stmt->execute(['name' => $name]);
    }

    public static function delete(int $id): void
    {
        $stmt = self::db()->prepare('DELETE FROM product_categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
