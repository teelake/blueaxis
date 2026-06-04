<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ContentBlock;
use App\Models\Page;
use App\Services\SeoService;

final class AboutController extends Controller
{
    public function index(): void
    {
        $page = Page::findBySlug('about');
        $blocks = ContentBlock::forPage('about');
        $values = [];
        if (!empty($blocks['values']['content']['content'])) {
            $values = json_decode((string) $blocks['values']['content']['content'], true) ?: [];
        }

        $leadershipMembers = content_json_list($blocks, 'leadership', 'members', []);

        $this->view('public/about', [
            'seo' => SeoService::metaForPage($page),
            'blocks' => $blocks,
            'values' => $values,
            'leadershipMembers' => $leadershipMembers,
        ]);
    }
}
