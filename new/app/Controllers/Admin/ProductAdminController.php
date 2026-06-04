<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Core\Session;
use App\Models\Product;
use App\Services\HtmlSanitizer;
use App\Services\MediaUploadHelper;

final class ProductAdminController extends AdminController
{
    public function index(): void
    {
        $this->authorize(Permission::PRODUCTS);
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $search = trim((string) ($_GET['q'] ?? ''));
        $result = Product::allAdmin($page, (int) config('app.per_page_admin'), $search);
        $this->view('admin/products/index', [
            'title' => 'Products',
            'pageDescription' => 'Manage your B2B product catalog shown on the website.',
            'products' => $result['items'],
            'total' => $result['total'],
            'page' => $page,
            'search' => $search,
            'success' => flash('success'),
        ], 'layouts/admin');
    }

    public function create(): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->view('admin/products/form', [
            'title' => 'New product',
            'pageDescription' => 'Add a product to your wholesale catalog.',
            'product' => null,
            'categories' => Product::distinctCategories(),
        ], 'layouts/admin');
    }

    public function store(): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->validateCsrf();
        Product::create($this->payloadFromPost());
        Session::flash('success', 'Product created.');
        redirect('admin/products');
    }

    public function edit(array $params): void
    {
        $this->authorize(Permission::PRODUCTS);
        $product = Product::find((int) ($params['id'] ?? 0));
        if (!$product) {
            redirect('admin/products');
        }
        $this->view('admin/products/form', [
            'title' => 'Edit product',
            'pageDescription' => 'Update catalog details and imagery.',
            'product' => $product,
            'categories' => Product::distinctCategories(),
        ], 'layouts/admin');
    }

    public function update(array $params): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->validateCsrf();
        $id = (int) ($params['id'] ?? 0);
        Product::update($id, $this->payloadFromPost());
        Session::flash('success', 'Product updated.');
        redirect('admin/products');
    }

    public function destroy(array $params): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->validateCsrf();
        Product::delete((int) ($params['id'] ?? 0));
        Session::flash('success', 'Product deleted.');
        redirect('admin/products');
    }

    public function togglePublish(array $params): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->validateCsrf();
        $id = (int) ($params['id'] ?? 0);
        if (Product::find($id)) {
            Product::togglePublished($id);
            Session::flash('success', 'Product visibility updated.');
        }
        redirect('admin/products');
    }

    /** @return array<string, mixed> */
    private function payloadFromPost(): array
    {
        $slug = slugify((string) ($_POST['slug'] ?? $_POST['title'] ?? ''));
        return [
            'title' => trim((string) ($_POST['title'] ?? '')),
            'slug' => $slug,
            'category' => trim((string) ($_POST['category'] ?? '')) ?: null,
            'sku' => trim((string) ($_POST['sku'] ?? '')) ?: null,
            'price' => self::parsePrice($_POST['price'] ?? null),
            'price_unit' => trim((string) ($_POST['price_unit'] ?? '')) ?: null,
            'excerpt' => trim((string) ($_POST['excerpt'] ?? '')) ?: null,
            'description' => HtmlSanitizer::clean((string) ($_POST['description'] ?? '')),
            'image_path' => MediaUploadHelper::resolve('image_path'),
            'origin_region' => trim((string) ($_POST['origin_region'] ?? '')) ?: null,
            'pack_format' => trim((string) ($_POST['pack_format'] ?? '')) ?: null,
            'storage_notes' => trim((string) ($_POST['storage_notes'] ?? '')) ?: null,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_published' => isset($_POST['is_published']) ? 1 : 0,
            'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            'meta_title' => trim((string) ($_POST['meta_title'] ?? '')) ?: null,
            'meta_description' => trim((string) ($_POST['meta_description'] ?? '')) ?: null,
        ];
    }

    private static function parsePrice(mixed $raw): ?float
    {
        $value = trim((string) $raw);
        if ($value === '') {
            return null;
        }
        if (!is_numeric($value)) {
            return null;
        }
        $price = round((float) $value, 2);
        return $price >= 0 ? $price : null;
    }
}
