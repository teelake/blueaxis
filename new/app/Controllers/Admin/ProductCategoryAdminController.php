<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Core\Session;
use App\Models\ProductCategory;
use App\Services\FormRules;

final class ProductCategoryAdminController extends AdminController
{
    public function index(): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->view('admin/products/categories/index', [
            'title' => 'Product categories',
            'pageDescription' => 'Organize your catalog filters. Assign categories when editing products.',
            'categories' => ProductCategory::allWithProductCounts(),
            'success' => flash('success'),
            'error' => flash('error'),
        ], 'layouts/admin');
    }

    public function create(): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->view('admin/products/categories/form', [
            'title' => 'New category',
            'pageDescription' => 'Add a category for the product catalog.',
            'category' => null,
        ], 'layouts/admin');
    }

    public function store(): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->validateCsrf();
        $payload = $this->payloadFromPost();
        $this->validateOrRedirect(FormRules::productCategory($payload), 'admin/products/categories/create', $_POST);
        ProductCategory::create($payload);
        Session::flash('success', 'Category created.');
        redirect('admin/products/categories');
    }

    public function edit(array $params): void
    {
        $this->authorize(Permission::PRODUCTS);
        $category = ProductCategory::find((int) ($params['id'] ?? 0));
        if (!$category) {
            redirect('admin/products/categories');
        }
        $this->view('admin/products/categories/form', [
            'title' => 'Edit category',
            'pageDescription' => 'Update how this category appears in the catalog.',
            'category' => $category,
        ], 'layouts/admin');
    }

    public function update(array $params): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->validateCsrf();
        $id = (int) ($params['id'] ?? 0);
        $existing = ProductCategory::find($id);
        if (!$existing) {
            redirect('admin/products/categories');
        }
        $payload = $this->payloadFromPost();
        $payload['id'] = $id;
        $this->validateOrRedirect(FormRules::productCategory($payload, true), 'admin/products/categories/' . $id . '/edit', $_POST);

        $oldName = (string) $existing['name'];
        ProductCategory::update($id, $payload);
        if ($oldName !== $payload['name']) {
            ProductCategory::renameProducts($oldName, $payload['name']);
        }
        Session::flash('success', 'Category updated.');
        redirect('admin/products/categories');
    }

    public function destroy(array $params): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->validateCsrf();
        $id = (int) ($params['id'] ?? 0);
        $category = ProductCategory::find($id);
        if (!$category) {
            redirect('admin/products/categories');
        }
        $count = ProductCategory::productCount((string) $category['name']);
        if ($count > 0) {
            Session::flash('error', 'Cannot delete — ' . $count . ' product(s) still use this category. Reassign them first.');
            redirect('admin/products/categories');
        }
        ProductCategory::delete($id);
        Session::flash('success', 'Category deleted.');
        redirect('admin/products/categories');
    }

    /** @return array{name: string, slug: string, sort_order: int} */
    private function payloadFromPost(): array
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        $slugInput = trim((string) ($_POST['slug'] ?? ''));
        $slug = $slugInput !== '' ? slugify($slugInput) : slugify($name);
        if ($slug === '' || $slug === 'post') {
            $slug = slugify($name) ?: 'category';
        }
        return [
            'name' => $name,
            'slug' => $slug,
            'sort_order' => max(0, min(9999, (int) ($_POST['sort_order'] ?? 0))),
        ];
    }
}
