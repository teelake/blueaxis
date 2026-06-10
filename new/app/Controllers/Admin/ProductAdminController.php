<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Core\Session;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\FormRules;
use App\Services\HtmlSanitizer;
use App\Services\MediaUploadHelper;
use App\Services\ProductBulkImportService;

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

    public function bulkImport(): void
    {
        $this->authorize(Permission::PRODUCTS);
        $importErrors = $_SESSION['_import_errors'] ?? [];
        $importSummary = $_SESSION['_import_summary'] ?? null;
        unset($_SESSION['_import_errors'], $_SESSION['_import_summary']);
        $this->view('admin/products/bulk-import', [
            'title' => 'Bulk import products',
            'pageDescription' => 'Upload a CSV to create or update products in your catalog.',
            'columns' => ProductBulkImportService::COLUMNS,
            'success' => flash('success'),
            'error' => flash('error'),
            'importErrors' => $importErrors,
            'importSummary' => $importSummary,
        ], 'layouts/admin');
    }

    public function downloadBulkTemplate(): void
    {
        $this->authorize(Permission::PRODUCTS);
        (new ProductBulkImportService())->streamTemplate();
    }

    public function processBulkImport(): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->validateCsrf();

        $file = $_FILES['csv'] ?? null;
        if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            Session::flash('error', 'Please choose a CSV file to upload.');
            redirect('admin/products/bulk-import');
        }

        $tmp = (string) ($file['tmp_name'] ?? '');
        $name = (string) ($file['name'] ?? '');
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            Session::flash('error', 'Upload failed. Try again.');
            redirect('admin/products/bulk-import');
        }

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            Session::flash('error', 'Only .csv files are accepted.');
            redirect('admin/products/bulk-import');
        }

        $service = new ProductBulkImportService();
        $parsed = $service->parseFile($tmp);
        if ($parsed['errors'] !== []) {
            Session::flash('error', $parsed['errors'][0]);
            redirect('admin/products/bulk-import');
        }

        $result = $service->import($parsed['rows']);
        $_SESSION['_import_summary'] = [
            'created' => $result['created'],
            'updated' => $result['updated'],
        ];
        if ($result['errors'] !== []) {
            $_SESSION['_import_errors'] = $result['errors'];
        }

        $parts = [];
        if ($result['created'] > 0) {
            $parts[] = $result['created'] . ' created';
        }
        if ($result['updated'] > 0) {
            $parts[] = $result['updated'] . ' updated';
        }
        if ($parts === [] && $result['errors'] !== []) {
            Session::flash('error', 'Import finished with no changes. Review the errors below.');
        } else {
            $message = 'Import complete: ' . implode(', ', $parts) . '.';
            if ($result['errors'] !== []) {
                $message .= ' Some rows could not be imported.';
            }
            Session::flash('success', $message);
        }

        redirect('admin/products/bulk-import');
    }

    public function create(): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->view('admin/products/form', [
            'title' => 'New product',
            'pageDescription' => 'Add a product to your wholesale catalog.',
            'product' => null,
            'categories' => ProductCategory::allOrdered(),
        ], 'layouts/admin');
    }

    public function store(): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->validateCsrf();
        $payload = $this->payloadFromPost();
        $this->validateOrRedirect(FormRules::product($payload), 'admin/products/create', $_POST);
        Product::create($payload);
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
            'categories' => ProductCategory::allOrdered(),
        ], 'layouts/admin');
    }

    public function update(array $params): void
    {
        $this->authorize(Permission::PRODUCTS);
        $this->validateCsrf();
        $id = (int) ($params['id'] ?? 0);
        $payload = $this->payloadFromPost();
        $this->validateOrRedirect(FormRules::product($payload), 'admin/products/' . $id . '/edit', $_POST);
        Product::update($id, $payload);
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
        $title = trim((string) ($_POST['title'] ?? ''));
        $slug = slugify((string) ($_POST['slug'] ?? $title));
        if ($slug === '') {
            $slug = slugify($title) ?: 'product';
        }
        return [
            'title' => $title,
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
            'size' => trim((string) ($_POST['size'] ?? '')) ?: null,
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
