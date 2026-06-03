<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Page;
use App\Services\SeoService;

final class BlogController extends Controller
{
    public function index(): void
    {
        $page = (int) ($_GET['page'] ?? 1);
        $search = trim((string) ($_GET['q'] ?? ''));
        $categorySlug = trim((string) ($_GET['category'] ?? ''));
        $categoryId = null;
        if ($categorySlug !== '') {
            $cat = BlogCategory::findBySlug($categorySlug);
            $categoryId = $cat ? (int) $cat['id'] : null;
        }
        $perPage = (int) config('app.per_page_blog');
        $result = BlogPost::paginatePublished($page, $perPage, $search ?: null, $categoryId);
        $totalPages = (int) ceil($result['total'] / $perPage);

        $this->view('public/blog/index', [
            'seo' => SeoService::metaForPage(Page::findBySlug('blog')),
            'posts' => $result['posts'],
            'featured' => BlogPost::featured(),
            'categories' => BlogCategory::all(),
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'categorySlug' => $categorySlug,
            'total' => $result['total'],
        ]);
    }

    public function show(array $params): void
    {
        $post = BlogPost::findPublishedBySlug($params['slug'] ?? '');
        if (!$post) {
            http_response_code(404);
            $this->view('public/errors/404', ['title' => 'Article Not Found']);
            return;
        }
        $related = $post['category_id']
            ? BlogPost::related((int) $post['category_id'], (int) $post['id'])
            : [];

        $this->view('public/blog/show', [
            'seo' => SeoService::metaForPage(null, [
                'title' => $post['meta_title'] ?: $post['title'],
                'description' => $post['meta_description'] ?: $post['excerpt'],
                'og_image' => $post['featured_image'] ? media_url($post['featured_image']) : null,
            ]),
            'post' => $post,
            'tags' => BlogTag::forPost((int) $post['id']),
            'comments' => BlogComment::approvedForPost((int) $post['id']),
            'commentSuccess' => flash('comment_success'),
            'commentError' => flash('comment_error'),
            'related' => $related,
        ]);
    }
}
