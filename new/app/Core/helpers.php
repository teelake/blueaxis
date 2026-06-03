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

/**
 * Web path prefix where index.php lives (e.g. /new or /new/public).
 */
function app_base_path(): string
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }

    $fromEnv = rtrim((string) env('APP_BASE_PATH', ''), '/');
    if ($fromEnv !== '') {
        $cached = $fromEnv;
        return $cached;
    }

    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $dir = rtrim(dirname($script), '/');
    $cached = $dir === '' || $dir === '.' ? '' : $dir;
    return $cached;
}

/** Request path for routing (strips subdirectory base). */
function request_path(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = app_base_path();

    if ($base !== '' && str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base)) ?: '/';
    }

    return rtrim($uri, '/') ?: '/';
}

/**
 * URL path segment before /assets/ (e.g. /public when app runs from /new/).
 */
function asset_url_prefix(): string
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }

    $override = rtrim((string) env('APP_ASSET_PATH', ''), '/');
    if ($override !== '') {
        $cached = $override;
        return $cached;
    }

    $base = app_base_path();
    if ($base === '') {
        $cached = '';
        return $cached;
    }

    // Front controller at /new/index.php — files live in /new/public/assets/
    $cached = str_ends_with($base, '/public') ? '' : '/public';
    return $cached;
}

function asset(string $path): string
{
    $site = rtrim(config('app.url'), '/');
    $prefix = asset_url_prefix();

    return $site . $prefix . '/assets/' . ltrim($path, '/');
}

/** Public URL for uploaded media (paths stored as uploads/...). */
function media_url(?string $path): string
{
    if ($path === null || $path === '') {
        return '';
    }
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    $site = rtrim(config('app.url'), '/');
    $prefix = asset_url_prefix();

    return $site . $prefix . '/' . ltrim(str_replace('\\', '/', $path), '/');
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

/** @param array<string, array<string, array{content?: string, type?: string}>> $sections */
function block(array $sections, string $section, string $key, string $default = ''): string
{
    return (string) ($sections[$section][$key]['content'] ?? $default);
}

/** @param array<string, array{content?: string}> $section */
function section(array $section, string $key, string $default = ''): string
{
    return (string) ($section[$key]['content'] ?? $default);
}
