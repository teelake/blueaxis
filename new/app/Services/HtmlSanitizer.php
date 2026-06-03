<?php

declare(strict_types=1);

namespace App\Services;

final class HtmlSanitizer
{
    private const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><s><h2><h3><h4><ul><ol><li><blockquote><a><span>';

    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $html = preg_replace('#<(script|style|iframe|object|embed)[^>]*>.*?</\\1>#is', '', $html) ?? $html;
        $html = strip_tags($html, self::ALLOWED_TAGS);
        $html = preg_replace('/\s(on\w+|style)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? $html;

        return self::sanitizeLinks(trim($html));
    }

    private static function sanitizeLinks(string $html): string
    {
        return (string) preg_replace_callback(
            '/<a\s+([^>]*href\s*=\s*)(["\'])(.*?)\2([^>]*)>/i',
            static function (array $m): string {
                $href = $m[3];
                if (!preg_match('#^(https?://|mailto:)#i', $href)) {
                    return '<span>';
                }
                return '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" rel="noopener noreferrer" target="_blank">';
            },
            $html
        );
    }
}
