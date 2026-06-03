<?php

declare(strict_types=1);

function config(string $key, mixed $default = null): mixed
{
    static $cache = [];
    [$file, $item] = array_pad(explode('.', $key, 2), 2, null);
    if (!isset($cache[$file])) {
        $path = CONFIG_PATH . '/' . $file . '.php';
        $cache[$file] = is_readable($path) ? require $path : [];
    }
    if ($item === null) {
        return $cache[$file] ?? $default;
    }
    $segments = explode('.', $item);
    $value = $cache[$file];
    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }
    return $value;
}

function env(string $key, mixed $default = null): mixed
{
    $value = $_ENV[$key] ?? getenv($key);
    if ($value === false || $value === null) {
        return $default;
    }
    return $value;
}

function base_path(string $path = ''): string
{
    return rtrim(BASE_PATH, '/\\') . ($path ? DIRECTORY_SEPARATOR . ltrim($path, '/\\') : '');
}

function public_path(string $path = ''): string
{
    return rtrim(PUBLIC_PATH, '/\\') . ($path ? DIRECTORY_SEPARATOR . ltrim($path, '/\\') : '');
}

function storage_path(string $path = ''): string
{
    return rtrim(STORAGE_PATH, '/\\') . ($path ? DIRECTORY_SEPARATOR . ltrim($path, '/\\') : '');
}

function asset(string $path): string
{
    $base = rtrim(config('app.url'), '/');
    return $base . '/assets/' . ltrim($path, '/');
}

function url(string $path = ''): string
{
    $base = rtrim(config('app.url'), '/');
    return $path === '' ? $base : $base . '/' . ltrim($path, '/');
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function old(string $key, string $default = ''): string
{
    return e($_SESSION['_old'][$key] ?? $default);
}

function flash(string $key): ?string
{
    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-') ?: 'post';
}

function truncate(string $text, int $length = 160): string
{
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return rtrim(mb_substr($text, 0, $length - 3)) . '...';
}
