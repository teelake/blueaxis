<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Page;
use App\Models\Service;
use App\Services\SeoService;

final class ServiceController extends Controller
{
    public function index(): void
    {
        $page = Page::findBySlug('services');
        $this->view('public/services/index', [
            'seo' => SeoService::metaForPage($page),
            'services' => Service::published(),
        ]);
    }

    public function show(array $params): void
    {
        $service = Service::findBySlug($params['slug'] ?? '');
        if (!$service) {
            http_response_code(404);
            $this->view('public/errors/404', ['title' => 'Service Not Found'], 'layouts/public');
            return;
        }
        $this->view('public/services/show', [
            'seo' => SeoService::metaForPage(null, [
                'title' => $service['meta_title'] ?: $service['title'] . ' | BlueAxis',
                'description' => $service['meta_description'] ?: $service['excerpt'],
            ]),
            'service' => $service,
        ]);
    }
}
