<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Media;
use App\Services\MediaService;

final class MediaAdminController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $page = (int) ($_GET['page'] ?? 1);
        $result = Media::paginate($page, 24);
        $this->view('admin/media/index', [
            'title' => 'Media Library',
            'items' => $result['items'],
            'total' => $result['total'],
            'success' => flash('success'),
            'error' => flash('error'),
        ], 'layouts/admin');
    }

    public function upload(): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        try {
            if (!isset($_FILES['file'])) {
                throw new \RuntimeException('No file uploaded.');
            }
            MediaService::upload($_FILES['file']);
            Session::flash('success', 'File uploaded.');
        } catch (\Throwable $e) {
            Session::flash('error', $e->getMessage());
        }
        redirect('admin/media');
    }

    public function destroy(array $params): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        $media = Media::find((int) ($params['id'] ?? 0));
        if ($media) {
            $path = public_path($media['path']);
            if (is_file($path)) {
                unlink($path);
            }
            Media::delete((int) $media['id']);
        }
        Session::flash('success', 'Media deleted.');
        redirect('admin/media');
    }
}
