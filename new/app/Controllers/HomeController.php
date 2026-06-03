<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BlogPost;
use App\Models\ContentBlock;
use App\Models\Page;
use App\Models\Service;
use App\Services\SeoService;

final class HomeController extends Controller
{
    public function index(): void
    {
        $page = Page::findBySlug('home');
        $blocks = ContentBlock::forPage('home');
        $hero = $blocks['hero'] ?? [];
        $about = $blocks['about'] ?? [];
        $cta = $blocks['cta'] ?? [];
        $trustItems = [];
        if (!empty($blocks['trust']['items']['content'])) {
            $trustItems = json_decode((string) $blocks['trust']['items']['content'], true) ?: [];
        }
        if ($trustItems === []) {
            $trustItems = [
                ['stat' => 'B2B', 'label' => 'Wholesale focus'],
                ['stat' => 'MB + CA', 'label' => 'Regional & national reach'],
                ['stat' => '3-in-1', 'label' => 'Import · Store · Distribute'],
                ['stat' => 'Food-grade', 'label' => 'Disciplined operations'],
            ];
        }
        $services = Service::published();
        $posts = BlogPost::latest(3);
        $seo = SeoService::metaForPage($page);

        $this->view('public/home', [
            'seo' => $seo,
            'hero' => $hero,
            'about' => $about,
            'cta' => $cta,
            'trustItems' => $trustItems,
            'services' => $services,
            'posts' => $posts,
            'schema' => SeoService::organizationSchema(),
        ]);
    }
}
