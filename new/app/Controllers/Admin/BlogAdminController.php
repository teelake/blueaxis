<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Services\HtmlSanitizer;
use App\Services\MediaService;

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
            'pendingComments' => BlogComment::countPending(),
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
        $id = BlogPost::create($this->payload());
        BlogTag::syncForPost($id, $this->tagNames());
        Session::flash('success', 'Post created.');
        redirect('admin/blog/' . $id . '/edit');
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
        $id = (int) ($params['id'] ?? 0);
        BlogPost::update($id, $this->payload());
        BlogTag::syncForPost($id, $this->tagNames());
        Session::flash('success', 'Post updated.');
        redirect('admin/blog/' . $id . '/edit');
    }

    public function destroy(array $params): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        BlogPost::delete((int) ($params['id'] ?? 0));
        Session::flash('success', 'Post deleted.');
        redirect('admin/blog');
    }

    public function approveComment(array $params): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        $comment = BlogComment::find((int) ($params['commentId'] ?? 0));
        if ($comment) {
            BlogComment::updateStatus((int) $comment['id'], 'approved');
            Session::flash('success', 'Comment approved.');
        }
        redirect('admin/blog/' . (int) ($params['id'] ?? 0) . '/edit#comments');
    }

    public function spamComment(array $params): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        $comment = BlogComment::find((int) ($params['commentId'] ?? 0));
        if ($comment) {
            BlogComment::updateStatus((int) $comment['id'], 'spam');
            Session::flash('success', 'Comment marked as spam.');
        }
        redirect('admin/blog/' . (int) ($params['id'] ?? 0) . '/edit#comments');
    }

    public function deleteComment(array $params): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        BlogComment::delete((int) ($params['commentId'] ?? 0));
        Session::flash('success', 'Comment deleted.');
        redirect('admin/blog/' . (int) ($params['id'] ?? 0) . '/edit#comments');
    }

    private function formView(?array $post): void
    {
        $tags = $post ? implode(', ', BlogTag::namesForPost((int) $post['id'])) : '';
        $comments = $post ? BlogComment::forPost((int) $post['id']) : [];
        $this->view('admin/blog/form', [
            'title' => $post ? 'Edit Post' : 'Create Post',
            'post' => $post,
            'categories' => BlogCategory::all(),
            'tags' => $tags,
            'comments' => $comments,
            'mediaItems' => \App\Models\Media::recent(24),
            'uploadUrl' => url('admin/media/upload'),
            'mediaListUrl' => url('admin/media/list'),
            'csrf' => \App\Core\Csrf::token(),
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

        $featured = trim((string) ($_POST['featured_image'] ?? '')) ?: null;
        if (!empty($_FILES['featured_image_file']['tmp_name'])) {
            $uploaded = MediaService::upload($_FILES['featured_image_file']);
            $featured = $uploaded['path'];
        }

        return [
            'category_id' => $_POST['category_id'] ? (int) $_POST['category_id'] : null,
            'author_id' => Auth::id(),
            'title' => trim((string) ($_POST['title'] ?? '')),
            'slug' => $slug,
            'excerpt' => trim((string) ($_POST['excerpt'] ?? '')),
            'content' => HtmlSanitizer::clean((string) ($_POST['content'] ?? '')),
            'featured_image' => $featured,
            'meta_title' => trim((string) ($_POST['meta_title'] ?? '')) ?: null,
            'meta_description' => trim((string) ($_POST['meta_description'] ?? '')) ?: null,
            'status' => $status,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'published_at' => $publishedAt,
        ];
    }

    /** @return list<string> */
    private function tagNames(): array
    {
        $raw = trim((string) ($_POST['tags'] ?? ''));
        if ($raw === '') {
            return [];
        }
        $parts = preg_split('/\s*,\s*/', $raw) ?: [];
        return array_values(array_filter(array_map('trim', $parts)));
    }
}
