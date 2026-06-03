<?php

declare(strict_types=1);

namespace App\Models;

final class BlogPost extends Model
{
    public static function countPublished(): int
    {
        return (int) self::db()->query(
            "SELECT COUNT(*) FROM blog_posts WHERE status = 'published'"
        )->fetchColumn();
    }

    /** @return array{posts: array<int, array>, total: int} */
    public static function paginatePublished(int $page, int $perPage, ?string $search = null, ?int $categoryId = null): array
    {
        $where = ["status = 'published'", 'published_at <= NOW()'];
        $params = [];
        if ($search) {
            $where[] = '(title LIKE :search OR excerpt LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }
        if ($categoryId) {
            $where[] = 'category_id = :category_id';
            $params['category_id'] = $categoryId;
        }
        $whereSql = implode(' AND ', $where);
        $countStmt = self::db()->prepare("SELECT COUNT(*) FROM blog_posts WHERE {$whereSql}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $offset = max(0, ($page - 1) * $perPage);
        $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug, a.name AS author_name
                FROM blog_posts p
                LEFT JOIN blog_categories c ON c.id = p.category_id
                LEFT JOIN admins a ON a.id = p.author_id
                WHERE {$whereSql}
                ORDER BY published_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);
        return ['posts' => $stmt->fetchAll(), 'total' => $total];
    }

    public static function featured(): ?array
    {
        $stmt = self::db()->query(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug, a.name AS author_name
             FROM blog_posts p
             LEFT JOIN blog_categories c ON c.id = p.category_id
             LEFT JOIN admins a ON a.id = p.author_id
             WHERE p.status = 'published' AND p.is_featured = 1
             ORDER BY p.published_at DESC LIMIT 1"
        );
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findPublishedBySlug(string $slug): ?array
    {
        $stmt = self::db()->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug, a.name AS author_name
             FROM blog_posts p
             LEFT JOIN blog_categories c ON c.id = p.category_id
             LEFT JOIN admins a ON a.id = p.author_id
             WHERE p.slug = :slug AND p.status = 'published' AND p.published_at <= NOW()
             LIMIT 1"
        );
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** @return array<int, array> */
    public static function related(int $categoryId, int $excludeId, int $limit = 3): array
    {
        $stmt = self::db()->prepare(
            "SELECT id, title, slug, excerpt, featured_image, published_at
             FROM blog_posts
             WHERE category_id = :category_id AND id != :exclude_id AND status = 'published'
             ORDER BY published_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue(':exclude_id', $excludeId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** @return array<int, array> */
    public static function latest(int $limit = 3): array
    {
        $stmt = self::db()->prepare(
            "SELECT p.id, p.title, p.slug, p.excerpt, p.featured_image, p.published_at,
                    c.name AS category_name
             FROM blog_posts p
             LEFT JOIN blog_categories c ON c.id = p.category_id
             WHERE p.status = 'published' AND p.published_at <= NOW()
             ORDER BY p.published_at DESC LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /** @return array<int, array> */
    public static function allAdmin(string $status = '', int $page = 1, int $perPage = 20): array
    {
        $where = '1=1';
        $params = [];
        if ($status !== '') {
            $where = 'status = :status';
            $params['status'] = $status;
        }
        $total = (int) self::db()->prepare("SELECT COUNT(*) FROM blog_posts WHERE {$where}")
            ->execute($params) ?: 0;
        $countStmt = self::db()->prepare("SELECT COUNT(*) FROM blog_posts WHERE {$where}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $offset = ($page - 1) * $perPage;
        $stmt = self::db()->prepare(
            "SELECT p.*, c.name AS category_name FROM blog_posts p
             LEFT JOIN blog_categories c ON c.id = p.category_id
             WHERE {$where} ORDER BY p.updated_at DESC LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute($params);
        return ['posts' => $stmt->fetchAll(), 'total' => $total];
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM blog_posts WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = self::db()->prepare(
            'INSERT INTO blog_posts (category_id, author_id, title, slug, excerpt, content, featured_image,
             meta_title, meta_description, status, is_featured, published_at)
             VALUES (:category_id, :author_id, :title, :slug, :excerpt, :content, :featured_image,
             :meta_title, :meta_description, :status, :is_featured, :published_at)'
        );
        $stmt->execute($data);
        return (int) self::db()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $data['id'] = $id;
        $stmt = self::db()->prepare(
            'UPDATE blog_posts SET category_id = :category_id, title = :title, slug = :slug,
             excerpt = :excerpt, content = :content, featured_image = :featured_image,
             meta_title = :meta_title, meta_description = :meta_description, status = :status,
             is_featured = :is_featured, published_at = :published_at WHERE id = :id'
        );
        $stmt->execute($data);
    }

    public static function delete(int $id): void
    {
        $stmt = self::db()->prepare('DELETE FROM blog_posts WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $sql = 'SELECT id FROM blog_posts WHERE slug = :slug';
        $params = ['slug' => $slug];
        if ($excludeId) {
            $sql .= ' AND id != :id';
            $params['id'] = $excludeId;
        }
        $stmt = self::db()->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }
}
