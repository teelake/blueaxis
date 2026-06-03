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
            'title' => 'Edit Home Page',
            'blocks' => $blocks,
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
        if (isset($_POST['trust_items_json'])) {
            ContentBlock::upsert('home', 'trust', 'items', $_POST['trust_items_json'], 'json');
        }
        Session::flash('success', 'Home page content saved.');
        redirect('admin/content/home');
    }

    public function editAbout(): void
    {
        Auth::requireLogin();
        $this->view('admin/content/about', [
            'title' => 'Edit About Page',
            'blocks' => ContentBlock::forPage('about'),
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
        if (isset($_POST['values_json'])) {
            ContentBlock::upsert('about', 'values', 'content', $_POST['values_json'], 'json');
        }
        Session::flash('success', 'About page content saved.');
        redirect('admin/content/about');
    }
}
