<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Service;

final class ServiceAdminController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $this->view('admin/services/index', [
            'title' => 'Services',
            'services' => Service::allAdmin(),
            'success' => flash('success'),
        ], 'layouts/admin');
    }

    public function create(): void
    {
        Auth::requireLogin();
        $this->view('admin/services/form', ['title' => 'Create Service', 'service' => null], 'layouts/admin');
    }

    public function store(): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        $data = $this->payloadFromPost();
        Service::create($data);
        Session::flash('success', 'Service created.');
        redirect('admin/services');
    }

    public function edit(array $params): void
    {
        Auth::requireLogin();
        $service = Service::find((int) ($params['id'] ?? 0));
        if (!$service) {
            redirect('admin/services');
        }
        $this->view('admin/services/form', ['title' => 'Edit Service', 'service' => $service], 'layouts/admin');
    }

    public function update(array $params): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        $id = (int) ($params['id'] ?? 0);
        Service::update($id, $this->payloadFromPost());
        Session::flash('success', 'Service updated.');
        redirect('admin/services');
    }

    public function destroy(array $params): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        Service::delete((int) ($params['id'] ?? 0));
        Session::flash('success', 'Service deleted.');
        redirect('admin/services');
    }

    /** @return array<string, mixed> */
    private function payloadFromPost(): array
    {
        $benefits = array_filter(array_map('trim', explode("\n", (string) ($_POST['benefits_lines'] ?? ''))));
        return [
            'title' => trim((string) ($_POST['title'] ?? '')),
            'slug' => slugify((string) ($_POST['slug'] ?? $_POST['title'] ?? '')),
            'excerpt' => trim((string) ($_POST['excerpt'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'benefits' => json_encode($benefits),
            'banner_image' => trim((string) ($_POST['banner_image'] ?? '')) ?: null,
            'icon' => trim((string) ($_POST['icon'] ?? '')) ?: null,
            'meta_title' => trim((string) ($_POST['meta_title'] ?? '')) ?: null,
            'meta_description' => trim((string) ($_POST['meta_description'] ?? '')) ?: null,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
        ];
    }
}
