<?php

declare(strict_types=1);

namespace App\Models;

final class Admin extends Model
{
    public static function findByEmail(string $email): ?array
    {
        $stmt = self::db()->prepare(
            'SELECT a.*, r.slug AS role_slug, r.name AS role_name
             FROM admins a
             JOIN roles r ON r.id = a.role_id
             WHERE a.email = :email LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function updateLastLogin(int $id): void
    {
        $stmt = self::db()->prepare('UPDATE admins SET last_login_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
