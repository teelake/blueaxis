<?php

declare(strict_types=1);

namespace App\Core;

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        session_name(config('app.session_name'));
        $cookiePath = app_install_path() !== '' ? app_install_path() : '/';
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => $cookiePath,
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    public static function flash(string $key, string $message): void
    {
        $_SESSION['_flash'][$key] = $message;
    }

    public static function setOld(array $input): void
    {
        $_SESSION['_old'] = $input;
    }

    public static function clearOld(): void
    {
        unset($_SESSION['_old']);
    }

    /** @param array<string, string> $errors */
    public static function setErrors(array $errors): void
    {
        $_SESSION['_errors'] = $errors;
    }

    /** @return array<string, string> */
    public static function errors(): array
    {
        return $_SESSION['_errors'] ?? [];
    }

    public static function error(string $field): ?string
    {
        return $_SESSION['_errors'][$field] ?? null;
    }

    public static function clearErrors(): void
    {
        unset($_SESSION['_errors']);
    }
}
