<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Core\Session;
use App\Models\Service;
use App\Services\FormRules;
use App\Services\HtmlSanitizer;
use App\Services\MediaUploadHelper;

final class ServiceAdminController extends AdminController
{
    public function index(): void
    {
        $this->authorize(Permission::SERVICES);
        $this->view('admin/services/index', [
            'title' => 'Services',
            'pageDescription' => 'Manage the services shown on your website.',
            'services' => Service::allAdmin(),
            'success' => flash('success'),
        ], 'layouts/admin');
    }

    public function create(): void
    {
        $this->authorize(Permission::SERVICES);
        $this->view('admin/services/form', [
            'title' => 'New service',
            'pageDescription' => 'Add a service to show on your website.',
            'service' => null,
            'benefitItems' => [['text' => '']],
        ], 'layouts/admin');
    }

    public function store(): void
    {
        $this->authorize(Permission::SERVICES);
        $this->validateCsrf();
        $data = $this->payloadFromPost();
        $this->validateOrRedirect(FormRules::service($data), 'admin/services/create', $_POST);
        Service::create($data);
        Session::flash('success', 'Service created.');
        redirect('admin/services');
    }

    public function edit(array $params): void
    {
        $this->authorize(Permission::SERVICES);
        $service = Service::find((int) ($params['id'] ?? 0));
        if (!$service) {
            redirect('admin/services');
        }
        $benefits = [];
        if (!empty($service['benefits'])) {
            foreach (json_decode((string) $service['benefits'], true) ?: [] as $b) {
                $benefits[] = ['text' => is_string($b) ? $b : (string) ($b['text'] ?? '')];
            }
        }
        if ($benefits === []) {
            $benefits = [['text' => '']];
        }
        $this->view('admin/services/form', [
            'title' => 'Edit service',
            'pageDescription' => 'Update how this service appears on the site.',
            'service' => $service,
            'benefitItems' => $benefits,
        ], 'layouts/admin');
    }

    public function update(array $params): void
    {
        $this->authorize(Permission::SERVICES);
        $this->validateCsrf();
        $id = (int) ($params['id'] ?? 0);
        $data = $this->payloadFromPost();
        $this->validateOrRedirect(FormRules::service($data), 'admin/services/' . $id . '/edit', $_POST);
        Service::update($id, $data);
        Session::flash('success', 'Service updated.');
        redirect('admin/services');
    }

    public function destroy(array $params): void
    {
        $this->authorize(Permission::SERVICES);
        $this->validateCsrf();
        Service::delete((int) ($params['id'] ?? 0));
        Session::flash('success', 'Service deleted.');
        redirect('admin/services');
    }

    public function togglePublish(array $params): void
    {
        $this->authorize(Permission::SERVICES);
        $this->validateCsrf();
        $id = (int) ($params['id'] ?? 0);
        if (Service::find($id)) {
            Service::togglePublished($id);
            Session::flash('success', 'Service visibility updated.');
        }
        redirect('admin/services');
    }

    /** @return array<string, mixed> */
    private function payloadFromPost(): array
    {
        $benefits = [];
        foreach ($_POST['benefits'] ?? [] as $row) {
            if (!is_array($row)) {
                continue;
            }
            $text = trim((string) ($row['text'] ?? ''));
            if ($text !== '') {
                $benefits[] = $text;
            }
        }
        $title = trim((string) ($_POST['title'] ?? ''));
        $slug = slugify((string) ($_POST['slug'] ?? $title));
        if ($slug === '') {
            $slug = slugify($title) ?: 'service';
        }
        return [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => trim((string) ($_POST['excerpt'] ?? '')),
            'description' => HtmlSanitizer::clean((string) ($_POST['description'] ?? '')),
            'benefits' => json_encode($benefits),
            'banner_image' => MediaUploadHelper::resolve('banner_image'),
            'icon' => trim((string) ($_POST['icon'] ?? '')) ?: null,
            'meta_title' => trim((string) ($_POST['meta_title'] ?? '')) ?: null,
            'meta_description' => trim((string) ($_POST['meta_description'] ?? '')) ?: null,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
        ];
    }
}
