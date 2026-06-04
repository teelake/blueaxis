<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\Admin;

final class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $email = trim($email);
        $admin = Admin::findByEmail($email);
        if (!$admin) {
            ErrorLogger::log('auth', 'Login failed: no admin for email ' . $email);
            return false;
        }
        if (!$admin['is_active']) {
            ErrorLogger::log('auth', 'Login failed: admin inactive for ' . $email);
            return false;
        }
        $storedHash = (string) ($admin['password'] ?? '');
        if ($storedHash === '' || !password_verify($password, $storedHash)) {
            ErrorLogger::log('auth', 'Login failed: invalid password for ' . $email);
            return false;
        }
        session_regenerate_id(true);
        $_SESSION['admin'] = [
            'id' => (int) $admin['id'],
            'name' => $admin['name'],
            'email' => $admin['email'],
            'role' => $admin['role_slug'],
            'role_id' => (int) $admin['role_id'],
            'role_name' => $admin['role_name'] ?? '',
            'permissions' => Permission::forRole((string) $admin['role_slug']),
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

    public static function can(string $permission): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }
        $role = (string) ($user['role'] ?? '');
        if ($role === 'super_admin') {
            return true;
        }
        $perms = $user['permissions'] ?? null;
        if (!is_array($perms)) {
            return Permission::roleCan($role, $permission);
        }
        return in_array($permission, $perms, true);
    }

    public static function requirePermission(string $permission): void
    {
        self::requireLogin();
        if (!self::can($permission)) {
            Session::flash('error', 'You do not have permission to access that area.');
            redirect('admin/dashboard');
        }
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
