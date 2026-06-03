<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Services\HtmlSanitizer;

final class BlogAdminController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $page = (int) ($_GET['page'] ?? 1);
        $status = (string) ($_GET['status'] ?? '');
        $result = BlogPost::allAdmin($status, $page, (int) config('app.per_page_admin'));
        $this->view('admin/blog/index', [
            'title' => 'Blog Posts',
            'posts' => $result['posts'],
            'total' => $result['total'],
            'status' => $status,
            'success' => flash('success'),
        ], 'layouts/admin');
    }

    public function create(): void
    {
        Auth::requireLogin();
        $this->formView(null);
    }

    public function store(): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        BlogPost::create($this->payload());
        Session::flash('success', 'Post created.');
        redirect('admin/blog');
    }

    public function edit(array $params): void
    {
        Auth::requireLogin();
        $post = BlogPost::find((int) ($params['id'] ?? 0));
        if (!$post) {
            redirect('admin/blog');
        }
        $this->formView($post);
    }

    public function update(array $params): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        BlogPost::update((int) ($params['id'] ?? 0), $this->payload());
        Session::flash('success', 'Post updated.');
        redirect('admin/blog');
    }

    public function destroy(array $params): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        BlogPost::delete((int) ($params['id'] ?? 0));
        Session::flash('success', 'Post deleted.');
        redirect('admin/blog');
    }

    private function formView(?array $post): void
    {
        $this->view('admin/blog/form', [
            'title' => $post ? 'Edit Post' : 'Create Post',
            'post' => $post,
            'categories' => BlogCategory::all(),
        ], 'layouts/admin');
    }

    /** @return array<string, mixed> */
    private function payload(): array
    {
        $slug = slugify((string) ($_POST['slug'] ?? $_POST['title'] ?? ''));
        $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
        $publishedAt = $status === 'published'
            ? ($_POST['published_at'] ?: date('Y-m-d H:i:s'))
            : null;

        return [
            'category_id' => $_POST['category_id'] ? (int) $_POST['category_id'] : null,
            'author_id' => Auth::id(),
            'title' => trim((string) ($_POST['title'] ?? '')),
            'slug' => $slug,
            'excerpt' => trim((string) ($_POST['excerpt'] ?? '')),
            'content' => HtmlSanitizer::clean((string) ($_POST['content'] ?? '')),
            'featured_image' => trim((string) ($_POST['featured_image'] ?? '')) ?: null,
            'meta_title' => trim((string) ($_POST['meta_title'] ?? '')) ?: null,
            'meta_description' => trim((string) ($_POST['meta_description'] ?? '')) ?: null,
            'status' => $status,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'published_at' => $publishedAt,
        ];
    }
}
