<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Core\Session;
use App\Models\ContentBlock;
use App\Models\HeroSlide;
use App\Models\Setting;
use App\Services\FormRules;
use App\Services\HtmlSanitizer;
use App\Services\Validator;

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
        $slides = $this->parseHeroSlides();
        $slideErrors = $this->validateHeroSlides($slides);
        if ($slideErrors !== []) {
            Session::setErrors($slideErrors);
            Session::flash('error', reset($slideErrors) ?: 'Please fix hero slide links.');
            redirect('admin/content/home');
        }
        HeroSlide::syncAll($slides);

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
            'leadershipMembers' => content_json_list($blocks, 'leadership', 'members', [
                ['name' => '', 'role' => '', 'bio' => '', 'image_path' => ''],
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

        foreach (['title', 'lead'] as $key) {
            if (isset($_POST['leadership'][$key])) {
                ContentBlock::upsert('about', 'leadership', $key, trim((string) $_POST['leadership'][$key]), 'text');
            }
        }
        ContentBlock::upsert('about', 'leadership', 'members', json_encode($this->parseLeadershipMembers()), 'json');

        Session::flash('success', 'About page saved successfully.');
        redirect('admin/content/about');
    }

    public function editFooter(): void
    {
        $this->authorize(Permission::CONTENT);
        $blocks = ContentBlock::forPage('footer');
        $contact = Setting::allByGroup('contact');
        $this->view('admin/content/footer', [
            'title' => 'Footer',
            'pageDescription' => 'Edit the site footer shown on every public page.',
            'blocks' => $blocks,
            'contact' => $contact,
            'navLinks' => content_json_list($blocks, 'company_nav', 'links', self::defaultFooterNavLinks()),
            'showCredit' => ($blocks['credit']['show']['content'] ?? '1') !== '0',
            'success' => flash('success'),
        ], 'layouts/admin');
    }

    public function saveFooter(): void
    {
        $this->authorize(Permission::CONTENT);
        $this->validateCsrf();

        $contactInput = [
            'company_address' => trim((string) ($_POST['contact']['company_address'] ?? '')),
            'company_email' => trim((string) ($_POST['contact']['company_email'] ?? '')),
            'company_phone' => trim((string) ($_POST['contact']['company_phone'] ?? '')),
        ];
        $contactErrors = FormRules::footerContact($contactInput)->errors();
        if ($contactErrors !== []) {
            Session::setErrors($contactErrors);
            Session::setOld($_POST);
            Session::flash('error', reset($contactErrors) ?: 'Please correct the contact details.');
            redirect('admin/content/footer');
        }
        Session::clearErrors();

        if (isset($_POST['brand']['blurb'])) {
            ContentBlock::upsert('footer', 'brand', 'blurb', trim((string) $_POST['brand']['blurb']), 'text');
        }
        foreach (['title'] as $key) {
            if (isset($_POST['company_nav'][$key])) {
                ContentBlock::upsert('footer', 'company_nav', $key, trim((string) $_POST['company_nav'][$key]), 'text');
            }
        }
        ContentBlock::upsert('footer', 'company_nav', 'links', json_encode($this->parseFooterNavLinks()), 'json');

        foreach ($contactInput as $key => $value) {
            Setting::set($key, $value, 'text', 'contact');
        }
        if (isset($_POST['contact_col']['title'])) {
            ContentBlock::upsert('footer', 'contact_col', 'title', trim((string) $_POST['contact_col']['title']), 'text');
        }

        foreach (['copyright', 'tagline'] as $key) {
            if (isset($_POST['bar'][$key])) {
                ContentBlock::upsert('footer', 'bar', $key, trim((string) $_POST['bar'][$key]), 'text');
            }
        }
        ContentBlock::upsert(
            'footer',
            'credit',
            'show',
            isset($_POST['credit']['show']) ? '1' : '0',
            'text'
        );

        Session::flash('success', 'Footer saved successfully.');
        redirect('admin/content/footer');
    }

    /** @return list<array{label: string, url: string}> */
    private static function defaultFooterNavLinks(): array
    {
        return [
            ['label' => 'About', 'url' => '/about'],
            ['label' => 'Services', 'url' => '/services'],
            ['label' => 'Blog', 'url' => '/blog'],
            ['label' => 'Request a Quote', 'url' => '/quote'],
            ['label' => 'Contact', 'url' => '/contact'],
        ];
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
            $linkUrl = trim((string) ($row['link_url'] ?? ''));
            $slides[] = [
                'title' => trim((string) ($row['title'] ?? '')),
                'subtitle' => trim((string) ($row['subtitle'] ?? '')),
                'image_path' => $image,
                'link_url' => $linkUrl,
                'link_label' => trim((string) ($row['link_label'] ?? '')),
                'is_active' => !empty($row['is_active']),
            ];
        }
        return $slides;
    }

    /** @param list<array<string, mixed>> $slides @return array<string, string> */
    private function validateHeroSlides(array $slides): array
    {
        $errors = [];
        foreach ($slides as $i => $slide) {
            $url = (string) ($slide['link_url'] ?? '');
            if ($url === '') {
                continue;
            }
            $v = new Validator();
            $v->url('hero_slides', $url, true, 'Slide ' . ($i + 1) . ': enter a valid link URL or leave blank.');
            foreach ($v->errors() as $field => $message) {
                $errors['hero_slide_' . $i] = $message;
            }
        }
        return $errors;
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

    /** @return list<array{name: string, role: string, bio: string, image_path: string}> */
    private function parseLeadershipMembers(): array
    {
        $items = [];
        foreach ($_POST['leadership_items'] ?? [] as $row) {
            if (!is_array($row)) {
                continue;
            }
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $items[] = [
                'name' => $name,
                'role' => trim((string) ($row['role'] ?? '')),
                'bio' => trim((string) ($row['bio'] ?? '')),
                'image_path' => trim((string) ($row['image_path'] ?? '')),
            ];
        }
        return $items;
    }

    /** @return list<array{label: string, url: string}> */
    private function parseFooterNavLinks(): array
    {
        $items = [];
        foreach ($_POST['nav_links'] ?? [] as $row) {
            if (!is_array($row)) {
                continue;
            }
            $label = trim((string) ($row['label'] ?? ''));
            $linkUrl = trim((string) ($row['url'] ?? ''));
            if ($label === '' && $linkUrl === '') {
                continue;
            }
            if ($label === '') {
                continue;
            }
            $items[] = ['label' => $label, 'url' => $linkUrl !== '' ? $linkUrl : '#'];
        }
        return $items;
    }
}
