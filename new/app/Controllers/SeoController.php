<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\SeoService;

final class SeoController
{
    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        echo SeoService::sitemapXml();
    }

    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        $base = rtrim(config('app.url'), '/');
        echo "User-agent: *\nAllow: /\nDisallow: /admin/\n\nSitemap: {$base}/sitemap.xml\n";
    }
}
