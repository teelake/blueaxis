<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Core\Session;
use App\Models\ContentBlock;
use App\Models\HeroSlide;
use App\Services\HtmlSanitizer;

final class ContentAdminController extends AdminController
{
    public function editHome(): void
    {
        $this->authorize(Permission::CONTENT);
        $blocks = ContentBlock::forPage('home');
        $slideRows = HeroSlide::allOrdered();
        if ($slideRows === []) {
            $slideRows = [['title' => '', 'subtitle' => '', 'image_path' => '', 'link_url' => '', 'link_label' => '', 'is_active' => 1]];
        }
        $this->view('admin/content/home', [
            'title' => 'Home Page',
            'pageDescription' => 'Edit the content visitors see on your homepage.',
            'blocks' => $blocks,
            'heroSlides' => $slideRows,
            'trustItems' => content_json_list($blocks, 'trust', 'items', [
                ['stat' => 'B2B', 'label' => 'Wholesale focus'],
                ['stat' => 'MB + CA', 'label' => 'Regional & national reach'],
            ]),
            'testimonialItems' => content_json_list($blocks, 'testimonials', 'items', [
                ['quote' => '', 'name' => '', 'role' => '', 'company' => ''],
            ]),
            'success' => flash('success'),
        ], 'layouts/admin');
    }

    public function saveHome(): void
    {
        $this->authorize(Permission::CONTENT);
        $this->validateCsrf();
        HeroSlide::syncAll($this->parseHeroSlides());

        $sections = ['hero', 'about', 'industries', 'cta'];
        foreach ($sections as $section) {
            if (!isset($_POST[$section]) || !is_array($_POST[$section])) {
                continue;
            }
            foreach ($_POST[$section] as $key => $value) {
                if ($key === 'image') {
                    ContentBlock::upsert('home', $section, 'image', trim((string) $value), 'text');
                    continue;
                }
                $type = str_contains($key, 'body') ? 'html' : 'text';
                $val = $type === 'html' ? HtmlSanitizer::clean((string) $value) : trim((string) $value);
                ContentBlock::upsert('home', $section, $key, $val, $type);
            }
        }

        ContentBlock::upsert('home', 'trust', 'items', json_encode($this->parseTrustItems()), 'json');
        ContentBlock::upsert('home', 'testimonials', 'items', json_encode($this->parseTestimonialItems()), 'json');

        foreach (['eyebrow', 'title', 'lead'] as $key) {
            if (isset($_POST['testimonials'][$key])) {
                ContentBlock::upsert('home', 'testimonials', $key, trim((string) $_POST['testimonials'][$key]), 'text');
            }
        }
        foreach (['eyebrow', 'title', 'lead'] as $key) {
            if (isset($_POST['newsletter'][$key])) {
                ContentBlock::upsert('home', 'newsletter', $key, trim((string) $_POST['newsletter'][$key]), 'text');
            }
        }
        Session::flash('success', 'Home page saved successfully.');
        redirect('admin/content/home');
    }

    public function editAbout(): void
    {
        $this->authorize(Permission::CONTENT);
        $blocks = ContentBlock::forPage('about');
        $this->view('admin/content/about', [
            'title' => 'About Page',
            'pageDescription' => 'Tell visitors who BlueAxis is and what you stand for.',
            'blocks' => $blocks,
            'valueItems' => content_json_list($blocks, 'values', 'content', [
                ['title' => '', 'description' => ''],
            ]),
            'success' => flash('success'),
        ], 'layouts/admin');
    }

    public function saveAbout(): void
    {
        $this->authorize(Permission::CONTENT);
        $this->validateCsrf();
        foreach (['overview', 'mission', 'vision'] as $section) {
            if (!isset($_POST[$section])) {
                continue;
            }
            foreach ($_POST[$section] as $key => $value) {
                if ($key === 'image') {
                    ContentBlock::upsert('about', $section, 'image', trim((string) $value), 'text');
                    continue;
                }
                $type = $key === 'body' ? 'html' : 'text';
                $val = $type === 'html' ? HtmlSanitizer::clean((string) $value) : trim((string) $value);
                ContentBlock::upsert('about', $section, $key, $val, $type);
            }
        }
        ContentBlock::upsert('about', 'values', 'content', json_encode($this->parseValueItems()), 'json');
        Session::flash('success', 'About page saved successfully.');
        redirect('admin/content/about');
    }

    /** @return list<array<string, mixed>> */
    private function parseHeroSlides(): array
    {
        $slides = [];
        foreach ($_POST['hero_slides'] ?? [] as $row) {
            if (!is_array($row)) {
                continue;
            }
            $image = trim((string) ($row['image_path'] ?? ''));
            if ($image === '') {
                continue;
            }
            $slides[] = [
                'title' => trim((string) ($row['title'] ?? '')),
                'subtitle' => trim((string) ($row['subtitle'] ?? '')),
                'image_path' => $image,
                'link_url' => trim((string) ($row['link_url'] ?? '')),
                'link_label' => trim((string) ($row['link_label'] ?? '')),
                'is_active' => !empty($row['is_active']),
            ];
        }
        return $slides;
    }

    /** @return list<array{stat: string, label: string}> */
    private function parseTrustItems(): array
    {
        $items = [];
        foreach ($_POST['trust_items'] ?? [] as $row) {
            if (!is_array($row)) {
                continue;
            }
            $stat = trim((string) ($row['stat'] ?? ''));
            $label = trim((string) ($row['label'] ?? ''));
            if ($stat === '' && $label === '') {
                continue;
            }
            $items[] = ['stat' => $stat, 'label' => $label];
        }
        return $items;
    }

    /** @return list<array{quote: string, name: string, role: string, company: string}> */
    private function parseTestimonialItems(): array
    {
        $items = [];
        foreach ($_POST['testimonial_items'] ?? [] as $row) {
            if (!is_array($row)) {
                continue;
            }
            $quote = trim((string) ($row['quote'] ?? ''));
            if ($quote === '') {
                continue;
            }
            $items[] = [
                'quote' => $quote,
                'name' => trim((string) ($row['name'] ?? '')),
                'role' => trim((string) ($row['role'] ?? '')),
                'company' => trim((string) ($row['company'] ?? '')),
            ];
        }
        return $items;
    }

    /** @return list<array{title: string, description: string}> */
    private function parseValueItems(): array
    {
        $items = [];
        foreach ($_POST['value_items'] ?? [] as $row) {
            if (!is_array($row)) {
                continue;
            }
            $title = trim((string) ($row['title'] ?? ''));
            $description = trim((string) ($row['description'] ?? ''));
            if ($title === '' && $description === '') {
                continue;
            }
            $items[] = ['title' => $title, 'description' => $description];
        }
        return $items;
    }
}
