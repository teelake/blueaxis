<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Page;
use App\Models\Product;
use App\Models\Service;

final class SeoService
{
    public static function metaForPage(?array $page, array $overrides = []): array
    {
        $title = $overrides['title'] ?? $page['meta_title'] ?? $page['title'] ?? config('app.name');
        $description = $overrides['description'] ?? $page['meta_description'] ?? '';
        $canonical = $overrides['canonical'] ?? $page['canonical_url'] ?? null;
        $ogImage = $overrides['og_image'] ?? $page['og_image'] ?? asset('images/og-default.jpg');

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical ? (str_starts_with($canonical, 'http') ? $canonical : url(ltrim($canonical, '/'))) : null,
            'og_image' => str_starts_with((string) $ogImage, 'http') ? $ogImage : url(ltrim((string) $ogImage, '/')),
        ];
    }

    public static function sitemapXml(): string
    {
        $base = rtrim(config('app.url'), '/');
        $urls = [
            ['loc' => $base . '/', 'priority' => '1.0'],
            ['loc' => $base . '/about', 'priority' => '0.8'],
            ['loc' => $base . '/services', 'priority' => '0.9'],
            ['loc' => $base . '/blog', 'priority' => '0.8'],
            ['loc' => $base . '/quote', 'priority' => '0.9'],
            ['loc' => $base . '/contact', 'priority' => '0.7'],
        ];

        foreach (Service::published() as $service) {
            $urls[] = ['loc' => $base . '/services/' . $service['slug'], 'priority' => '0.7'];
        }

        $stmt = \App\Core\Database::connection()->query(
            "SELECT slug, updated_at FROM blog_posts WHERE status = 'published' ORDER BY published_at DESC"
        );
        foreach ($stmt->fetchAll() as $post) {
            $urls[] = [
                'loc' => $base . '/blog/' . $post['slug'],
                'lastmod' => date('Y-m-d', strtotime($post['updated_at'])),
                'priority' => '0.6',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc']) . "</loc>\n";
            if (!empty($u['lastmod'])) {
                $xml .= '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
            }
            $xml .= '    <priority>' . ($u['priority'] ?? '0.5') . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';
        return $xml;
    }

    public static function organizationSchema(): string
    {
        $contact = [
            'phone' => \App\Models\Setting::get('company_phone'),
            'email' => \App\Models\Setting::get('company_email'),
        ];
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => config('app.url'),
            'logo' => asset('images/BLUEAXIS_logo.png'),
            'description' => 'Canadian logistics company specializing in African food importation, warehousing, and B2B distribution.',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Winnipeg',
                'addressRegion' => 'MB',
                'addressCountry' => 'CA',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => $contact['phone'],
                'email' => $contact['email'],
                'contactType' => 'sales',
            ],
        ];
        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?: '{}';
    }
}
