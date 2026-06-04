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

/** Normalize a social profile URL from admin input (empty or invalid placeholder → hidden). */
function normalize_social_url(string $url): string
{
    $url = trim($url);
    if ($url === '' || $url === '#') {
        return '';
    }
    if (!preg_match('#^https?://#i', $url)) {
        $url = 'https://' . ltrim($url, '/');
    }
    return $url;
}

/**
 * Active social links from settings (non-empty URLs only).
 *
 * @return array<int, array{id: string, label: string, url: string}>
 */
function social_links(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $definitions = [
        'social_linkedin' => ['id' => 'linkedin', 'label' => 'LinkedIn'],
        'social_facebook' => ['id' => 'facebook', 'label' => 'Facebook'],
        'social_instagram' => ['id' => 'instagram', 'label' => 'Instagram'],
        'social_x' => ['id' => 'x', 'label' => 'X'],
        'social_youtube' => ['id' => 'youtube', 'label' => 'YouTube'],
    ];

    $cache = [];
    foreach ($definitions as $key => $meta) {
        $raw = \App\Models\Setting::get($key, '');
        $url = normalize_social_url((string) $raw);
        if ($url !== '' && filter_var($url, FILTER_VALIDATE_URL)) {
            $cache[] = [
                'id' => $meta['id'],
                'label' => $meta['label'],
                'url' => $url,
            ];
        }
    }

    return $cache;
}

/** Inline SVG icon for a social platform id. */
function social_icon_svg(string $id, string $class = 'w-5 h-5'): string
{
    $c = e($class);
    return match ($id) {
        'linkedin' => '<svg class="' . $c . '" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 114.127 0 2.062 2.062 0 01-2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
        'facebook' => '<svg class="' . $c . '" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'instagram' => '<svg class="' . $c . '" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>',
        'x' => '<svg class="' . $c . '" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'youtube' => '<svg class="' . $c . '" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
        default => '<svg class="' . $c . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>',
    };
}

/** Whether a product has a public list price configured. */
function product_has_price(array $product): bool
{
    if (!array_key_exists('price', $product) || $product['price'] === null || $product['price'] === '') {
        return false;
    }
    return (float) $product['price'] >= 0;
}

/** Formatted list price for display, or null when not set. */
function format_product_price(array $product): ?string
{
    if (!product_has_price($product)) {
        return null;
    }
    $amount = (float) $product['price'];
    $formatted = 'CAD $' . number_format($amount, $amount == floor($amount) ? 0 : 2);
    $unit = trim((string) ($product['price_unit'] ?? ''));
    if ($unit !== '') {
        $formatted .= ' ' . $unit;
    }
    return $formatted;
}

/** Number of products in the session quote list. */
function quote_cart_count(): int
{
    return \App\Services\QuoteCartService::count();
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
