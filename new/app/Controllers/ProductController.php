<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Page;
use App\Models\Product;
use App\Services\SeoService;

final class ProductController extends Controller
{
    public function index(): void
    {
        $page = Page::findBySlug('products');
        $category = trim((string) ($_GET['category'] ?? ''));
        $search = trim((string) ($_GET['q'] ?? ''));
        $products = Product::published($category !== '' ? $category : null, $search !== '' ? $search : null);
        $showFeatured = $category === '' && $search === '';
        $featured = $showFeatured
            ? array_values(array_filter($products, static fn (array $p): bool => (bool) $p['is_featured']))
            : [];
        $gridProducts = $showFeatured && $featured !== []
            ? array_values(array_filter($products, static fn (array $p): bool => !(bool) $p['is_featured']))
            : $products;
        $this->view('public/products/index', [
            'seo' => SeoService::metaForPage($page),
            'products' => $gridProducts,
            'featured' => $featured,
            'categories' => Product::categories(),
            'activeCategory' => $category,
            'search' => $search,
        ]);
    }

    public function show(array $params): void
    {
        $product = Product::findPublishedBySlug($params['slug'] ?? '');
        if (!$product) {
            http_response_code(404);
            $this->view('public/errors/404', ['title' => 'Product Not Found'], 'layouts/public');
            return;
        }
        $this->view('public/products/show', [
            'seo' => SeoService::metaForPage(null, [
                'title' => $product['meta_title'] ?: $product['title'] . ' | BlueAxis Products',
                'description' => $product['meta_description'] ?: ($product['excerpt'] ?? ''),
                'og_image' => !empty($product['image_path']) ? media_url($product['image_path']) : null,
            ]),
            'product' => $product,
            'related' => array_slice(
                array_values(array_filter(
                    Product::published($product['category'] ?? null),
                    static fn (array $p): bool => $p['slug'] !== $product['slug']
                )),
                0,
                3
            ),
        ]);
    }
}
