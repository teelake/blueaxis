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
            $result = MediaService::upload($_FILES['file']);
            if ($this->wantsJson()) {
                $this->json($result);
                return;
            }
            Session::flash('success', 'File uploaded.');
        } catch (\Throwable $e) {
            if ($this->wantsJson()) {
                $this->json(['error' => $e->getMessage()], 422);
                return;
            }
            Session::flash('error', $e->getMessage());
        }
        redirect('admin/media');
    }

    public function listJson(): void
    {
        Auth::requireLogin();
        $items = Media::recent(48);
        $this->json([
            'items' => array_map(static fn (array $m): array => [
                'id' => (int) $m['id'],
                'path' => $m['path'],
                'url' => media_url($m['path']),
                'name' => $m['original_name'],
            ], $items),
        ]);
    }

    private function wantsJson(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $requested = $_POST['_response'] ?? $_GET['_response'] ?? '';
        return str_contains($accept, 'application/json') || $requested === 'json';
    }

    /** @param array<string, mixed> $data */
    private function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
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
