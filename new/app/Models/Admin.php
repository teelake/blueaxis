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
}
