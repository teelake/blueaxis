<?php

declare(strict_types=1);

namespace App\Services;

final class MediaUploadHelper
{
    /** Resolve image path from hidden field and optional file upload. */
    public static function resolve(string $hiddenName, ?string $fileInputName = null): ?string
    {
        $path = trim((string) ($_POST[$hiddenName] ?? ''));
        $fileKey = $fileInputName ?? $hiddenName . '_file';
        if (!empty($_FILES[$fileKey]['tmp_name']) && is_uploaded_file($_FILES[$fileKey]['tmp_name'])) {
            $uploaded = MediaService::upload($_FILES[$fileKey]);
            return $uploaded['path'];
        }
        return $path !== '' ? $path : null;
    }
}
