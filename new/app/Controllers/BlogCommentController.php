<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Services\LeadNotificationService;
use App\Services\Validator;

final class BlogCommentController extends Controller
{
    public function store(array $params): void
    {
        $this->validateCsrf();
        $slug = (string) ($params['slug'] ?? '');
        $post = BlogPost::findPublishedBySlug($slug);
        if (!$post) {
            redirect('blog');
        }

        $name = trim((string) ($_POST['author_name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $body = trim((string) ($_POST['body'] ?? ''));

        $v = new Validator();
        $v->required('author_name', $name)->required('email', $email)->email('email', $email)->required('body', $body);
        if ($v->fails() || strlen($body) < 3) {
            Session::flash('comment_error', 'Please fill in all comment fields.');
            redirect('blog/' . $slug . '#comments');
        }

        $commentId = BlogComment::create([
            'post_id' => (int) $post['id'],
            'author_name' => $name,
            'email' => $email,
            'body' => $body,
            'status' => 'pending',
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        LeadNotificationService::blogCommentSubmitted(
            ['author_name' => $name, 'email' => $email, 'body' => $body],
            $post,
            $commentId
        );

        Session::flash('comment_success', 'Thank you. Your comment is awaiting moderation.');
        redirect('blog/' . $slug . '#comments');
    }
}
