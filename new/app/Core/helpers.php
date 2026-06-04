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

/** Site route prefix (e.g. /new), without /public. */
function app_install_path(): string
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }

    $override = rtrim((string) env('APP_BASE_PATH', ''), '/');
    if ($override !== '') {
        $cached = str_ends_with($override, '/public') ? substr($override, 0, -7) : $override;
        return $cached;
    }

    $base = app_base_path();
    if (str_ends_with($base, '/public')) {
        $cached = substr($base, 0, -7) ?: '';
    } else {
        $cached = $base;
    }
    return $cached;
}

/** Web path to the public/ folder (e.g. /new/public). */
function app_public_web_path(): string
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }

    $override = rtrim((string) env('APP_PUBLIC_PATH', ''), '/');
    if ($override !== '') {
        $cached = $override;
        return $cached;
    }

    $install = app_install_path();
    $cached = $install === '' ? '' : $install . '/public';
    return $cached;
}

function app_url_origin(): string
{
    $configured = rtrim(config('app.url'), '/');
    $parts = parse_url($configured);
    if (!empty($parts['scheme']) && !empty($parts['host'])) {
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        return $parts['scheme'] . '://' . $parts['host'] . $port;
    }

    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme . '://' . $host;
}

/** Base URL for pages and routes. */
function site_url_base(): string
{
    $configured = rtrim(config('app.url'), '/');
    $parts = parse_url($configured);
    if (!empty($parts['path']) && $parts['path'] !== '/') {
        return $configured;
    }

    return app_url_origin() . app_install_path();
}

/** Request path for routing (strips install/public prefixes). */
function request_path(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

    foreach ([app_public_web_path(), app_install_path(), app_base_path()] as $base) {
        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base)) ?: '/';
            break;
        }
    }

    return rtrim($uri, '/') ?: '/';
}

function asset(string $path): string
{
    return app_url_origin() . app_public_web_path() . '/assets/' . ltrim($path, '/');
}

/** Google Maps embed URL from settings or company address. */
function map_embed_url(): string
{
    $custom = \App\Models\Setting::get('map_embed_url');
    if ($custom !== null && trim($custom) !== '') {
        return trim($custom);
    }

    $query = urlencode(
        \App\Models\Setting::get('company_address', 'Winnipeg, Manitoba, Canada') ?? 'Winnipeg, Manitoba, Canada'
    );

    return 'https://www.google.com/maps?q=' . $query . '&z=12&output=embed';
}

function media_url(?string $path): string
{
    if ($path === null || $path === '') {
        return '';
    }
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    return app_url_origin() . app_public_web_path() . '/' . ltrim(str_replace('\\', '/', $path), '/');
}

/** Logo alt text from admin branding settings. */
function site_logo_alt(): string
{
    return \App\Models\Setting::get('site_logo_alt', 'BlueAxis Logistics & Warehousing')
        ?? 'BlueAxis Logistics & Warehousing';
}

/** Whether the footer should apply a white/invert treatment to the logo. */
function site_logo_footer_invert(): bool
{
    return \App\Models\Setting::get('site_logo_footer_invert', '1') !== '0';
}

/**
 * Public URL for the site logo (uploaded in admin or default asset).
 * @param string $variant header|footer — used only for default fallback asset
 */
function site_logo_url(string $variant = 'header'): string
{
    $path = \App\Models\Setting::get('site_logo_path');
    if ($path !== null && trim($path) !== '') {
        return media_url($path);
    }

    return $variant === 'footer'
        ? asset('images/BLUEAXIS_logo.png')
        : asset('images/blueaxis-logistics.png');
}

/** Favicon URL for browser tab (uploaded in admin or default asset). */
function site_favicon_url(): string
{
    $path = \App\Models\Setting::get('site_favicon_path');
    if ($path !== null && trim($path) !== '') {
        return media_url($path);
    }

    return asset('images/BLUEAXIS_logo.png');
}

function url(string $path = ''): string
{
    $base = site_url_base();
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

/** @param array<string, array<string, array{content?: string}>> $blocks */
function content_json_list(array $blocks, string $section, string $key, array $default = []): array
{
    $raw = $blocks[$section][$key]['content'] ?? null;
    if ($raw === null || trim((string) $raw) === '') {
        return $default;
    }
    $decoded = json_decode((string) $raw, true);
    return is_array($decoded) ? $decoded : $default;
}
