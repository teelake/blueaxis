<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Auth;
use App\Models\Media;

final class MediaService
{
    public static function upload(array $file): array
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload failed.');
        }

        $maxBytes = (int) config('app.upload_max_mb') * 1024 * 1024;
        if ($file['size'] > $maxBytes) {
            throw new \RuntimeException('File exceeds maximum size.');
        }

        $mime = mime_content_type($file['tmp_name']) ?: $file['type'];
        if (!in_array($mime, config('app.allowed_image_types'), true)) {
            throw new \RuntimeException('Invalid file type.');
        }

        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'bin',
        };

        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $relative = 'uploads/' . date('Y/m') . '/' . $filename;
        $destDir = public_path($relative);
        $dir = dirname($destDir);
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new \RuntimeException('Could not create upload directory.');
        }
        if (!move_uploaded_file($file['tmp_name'], $destDir)) {
            throw new \RuntimeException('Could not save file.');
        }

        $id = Media::create([
            'filename' => $filename,
            'original_name' => $file['name'],
            'mime_type' => $mime,
            'file_size' => (int) $file['size'],
            'path' => $relative,
            'alt_text' => pathinfo($file['name'], PATHINFO_FILENAME),
            'uploaded_by' => Auth::id(),
        ]);

        return [
            'id' => $id,
            'url' => media_url($relative),
            'path' => $relative,
        ];
    }
}
