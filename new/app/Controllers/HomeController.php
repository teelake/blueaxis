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
        $testimonials = [];
        if (!empty($blocks['testimonials']['items']['content'])) {
            $testimonials = json_decode((string) $blocks['testimonials']['items']['content'], true) ?: [];
        }
        if ($testimonials === []) {
            $testimonials = [
                [
                    'quote' => 'BlueAxis gave us predictable inbound timing and clear inventory visibility—exactly what our wholesale program needed.',
                    'name' => 'Sarah M.',
                    'role' => 'Procurement Director',
                    'company' => 'Regional Grocery Group',
                ],
                [
                    'quote' => 'From import coordination to Manitoba fulfillment, their team operates with the discipline we expect from a long-term logistics partner.',
                    'name' => 'James D.',
                    'role' => 'Operations Manager',
                    'company' => 'Artisan Foods Wholesale',
                ],
                [
                    'quote' => 'Transparent communication at every stage. We scaled storage and distribution without disrupting our retail network.',
                    'name' => 'Priya K.',
                    'role' => 'Supply Chain Lead',
                    'company' => 'National Food Distributor',
                ],
            ];
        }
        $newsletter = $blocks['newsletter'] ?? [];

        $services = Service::published();
        $posts = BlogPost::latest(3);
        $seo = SeoService::metaForPage($page);

        $this->view('public/home', [
            'seo' => $seo,
            'hero' => $hero,
            'about' => $about,
            'cta' => $cta,
            'trustItems' => $trustItems,
            'testimonials' => $testimonials,
            'testimonialsTitle' => section($blocks['testimonials'] ?? [], 'eyebrow', 'Testimonials'),
            'testimonialsHeading' => section($blocks['testimonials'] ?? [], 'title', 'Trusted by wholesale partners'),
            'testimonialsLead' => section($blocks['testimonials'] ?? [], 'lead', 'BlueAxis ensures seamless Manitoba and Canada-wide simplified import, storage, and distribution.'),
            'newsletterEyebrow' => section($newsletter, 'eyebrow', 'Stay informed'),
            'newsletterTitle' => section($newsletter, 'title', 'Logistics insights for your inbox'),
            'newsletterLead' => section($newsletter, 'lead', 'Industry updates and supply chain perspectives for B2B partners.'),
            'services' => $services,
            'posts' => $posts,
            'schema' => SeoService::organizationSchema(),
        ]);
    }
}
