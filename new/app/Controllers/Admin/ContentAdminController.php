<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\ContentBlock;
use App\Services\HtmlSanitizer;

final class ContentAdminController extends Controller
{
    public function editHome(): void
    {
        Auth::requireLogin();
        $blocks = ContentBlock::forPage('home');
        $this->view('admin/content/home', [
            'title' => 'Home Page',
            'pageDescription' => 'Edit the content visitors see on your homepage.',
            'blocks' => $blocks,
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
        Auth::requireLogin();
        $this->validateCsrf();
        $sections = ['hero', 'about', 'cta'];
        foreach ($sections as $section) {
            if (!isset($_POST[$section]) || !is_array($_POST[$section])) {
                continue;
            }
            foreach ($_POST[$section] as $key => $value) {
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
        Auth::requireLogin();
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
        Auth::requireLogin();
        $this->validateCsrf();
        foreach (['overview', 'mission', 'vision'] as $section) {
            if (!isset($_POST[$section])) {
                continue;
            }
            foreach ($_POST[$section] as $key => $value) {
                $type = $key === 'body' ? 'html' : 'text';
                $val = $type === 'html' ? HtmlSanitizer::clean((string) $value) : trim((string) $value);
                ContentBlock::upsert('about', $section, $key, $val, $type);
            }
        }
        ContentBlock::upsert('about', 'values', 'content', json_encode($this->parseValueItems()), 'json');
        Session::flash('success', 'About page saved successfully.');
        redirect('admin/content/about');
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
