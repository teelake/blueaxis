<?php

declare(strict_types=1);

namespace App\Models;

final class Admin extends Model
{
    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare(
            'SELECT a.*, r.slug AS role_slug, r.name AS role_name
             FROM admins a
             JOIN roles r ON r.id = a.role_id
             WHERE a.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function updateProfile(int $id, string $name, string $email): void
    {
        $stmt = self::db()->prepare(
            'UPDATE admins SET name = :name, email = :email WHERE id = :id'
        );
        $stmt->execute(['name' => $name, 'email' => $email, 'id' => $id]);
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = self::db()->prepare(
            'SELECT a.*, r.slug AS role_slug, r.name AS role_name
             FROM admins a
             JOIN roles r ON r.id = a.role_id
             WHERE LOWER(a.email) = LOWER(:email) LIMIT 1'
        );
        $stmt->execute(['email' => trim($email)]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function updateLastLogin(int $id): void
    {
        $stmt = self::db()->prepare('UPDATE admins SET last_login_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function setPassword(int $id, string $plainPassword): void
    {
        $hash = password_hash($plainPassword, PASSWORD_BCRYPT);
        if (!password_verify($plainPassword, $hash)) {
            throw new \RuntimeException('Failed to generate a valid password hash.');
        }
        $stmt = self::db()->prepare('UPDATE admins SET password = :p WHERE id = :id');
        $stmt->execute(['p' => $hash, 'id' => $id]);
    }

    /** @return array<int, array> */
    public static function allWithRoles(): array
    {
        return self::db()->query(
            'SELECT a.id, a.name, a.email, a.is_active, a.last_login_at, a.created_at,
                    r.slug AS role_slug, r.name AS role_name
             FROM admins a
             JOIN roles r ON r.id = a.role_id
             ORDER BY a.name'
        )->fetchAll();
    }

    public static function createUser(string $name, string $email, string $password, int $roleId): int
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = self::db()->prepare(
            'INSERT INTO admins (role_id, name, email, password, is_active) VALUES (:role_id, :name, :email, :password, 1)'
        );
        $stmt->execute([
            'role_id' => $roleId,
            'name' => $name,
            'email' => $email,
            'password' => $hash,
        ]);
        return (int) self::db()->lastInsertId();
    }

    public static function updateUser(int $id, string $name, string $email, int $roleId, bool $isActive): void
    {
        $stmt = self::db()->prepare(
            'UPDATE admins SET name = :name, email = :email, role_id = :role_id, is_active = :active WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role_id' => $roleId,
            'active' => $isActive ? 1 : 0,
        ]);
    }

    public static function emailTakenByOther(string $email, int $excludeId): bool
    {
        $stmt = self::db()->prepare(
            'SELECT id FROM admins WHERE LOWER(email) = LOWER(:email) AND id != :id LIMIT 1'
        );
        $stmt->execute(['email' => $email, 'id' => $excludeId]);
        return (bool) $stmt->fetch();
    }

    public static function countActiveByRoleSlug(string $roleSlug): int
    {
        $stmt = self::db()->prepare(
            'SELECT COUNT(*) FROM admins a
             JOIN roles r ON r.id = a.role_id
             WHERE r.slug = :slug AND a.is_active = 1'
        );
        $stmt->execute(['slug' => $roleSlug]);
        return (int) $stmt->fetchColumn();
    }
}

