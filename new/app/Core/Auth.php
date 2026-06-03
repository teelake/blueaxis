<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Admin;

final class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $admin = Admin::findByEmail($email);
        if (!$admin || !$admin['is_active']) {
            return false;
        }
        if (!password_verify($password, $admin['password'])) {
            return false;
        }
        session_regenerate_id(true);
        $_SESSION['admin'] = [
            'id' => (int) $admin['id'],
            'name' => $admin['name'],
            'email' => $admin['email'],
            'role' => $admin['role_slug'],
            'role_id' => (int) $admin['role_id'],
        ];
        Admin::updateLastLogin((int) $admin['id']);
        return true;
    }

    public static function check(): bool
    {
        return isset($_SESSION['admin']['id']);
    }

    public static function user(): ?array
    {
        return $_SESSION['admin'] ?? null;
    }

    public static function id(): ?int
    {
        return isset($_SESSION['admin']['id']) ? (int) $_SESSION['admin']['id'] : null;
    }

    public static function hasRole(string ...$roles): bool
    {
        $user = self::user();
        return $user && in_array($user['role'], $roles, true);
    }

    public static function logout(): void
    {
        unset($_SESSION['admin']);
        session_regenerate_id(true);
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect('admin/login');
        }
    }
}
