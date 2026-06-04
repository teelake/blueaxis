<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;

/** Session-based quote list for B2B product inquiries (not a checkout cart). */
final class QuoteCartService
{
    private const SESSION_KEY = 'quote_cart';

    /** @return list<array{product_id: int, slug: string, title: string, sku: ?string, category: ?string, quantity: int}> */
    public static function items(): array
    {
        $raw = $_SESSION[self::SESSION_KEY] ?? [];
        return is_array($raw) ? array_values($raw) : [];
    }

    public static function count(): int
    {
        return count(self::items());
    }

    public static function addBySlug(string $slug, int $quantity = 1): bool
    {
        $product = Product::findPublishedBySlug($slug);
        if (!$product) {
            return false;
        }
        self::addProduct($product, max(1, $quantity));
        return true;
    }

    /** @param array<string, int|string> $slugQuantities slug => qty from POST */
    public static function syncQuantitiesFromPost(array $slugQuantities): void
    {
        $updated = [];
        foreach (self::items() as $item) {
            $slug = $item['slug'];
            if (!array_key_exists($slug, $slugQuantities)) {
                $updated[] = $item;
                continue;
            }
            $qty = max(0, (int) $slugQuantities[$slug]);
            if ($qty < 1) {
                continue;
            }
            $item['quantity'] = $qty;
            $updated[] = $item;
        }
        $_SESSION[self::SESSION_KEY] = $updated;
    }

    public static function remove(string $slug): void
    {
        $_SESSION[self::SESSION_KEY] = array_values(array_filter(
            self::items(),
            static fn (array $item): bool => $item['slug'] !== $slug
        ));
    }

    public static function clear(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
    }

    public static function toJson(): ?string
    {
        $items = self::items();
        if ($items === []) {
            return null;
        }
        $encoded = json_encode($items, JSON_UNESCAPED_UNICODE);
        return $encoded !== false ? $encoded : null;
    }

    /** @return list<array{product_id: int, slug: string, title: string, sku: ?string, category: ?string, quantity: int}> */
    public static function parseStored(?string $json): array
    {
        if ($json === null || trim($json) === '') {
            return [];
        }
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    /** Human-readable summary for emails and admin. */
    public static function formatLines(?string $json): string
    {
        $lines = [];
        foreach (self::parseStored($json) as $item) {
            $qty = (int) ($item['quantity'] ?? 1);
            $title = (string) ($item['title'] ?? 'Product');
            $sku = !empty($item['sku']) ? ' (' . $item['sku'] . ')' : '';
            $lines[] = $qty . '× ' . $title . $sku;
        }
        return implode("\n", $lines);
    }

    /** @param array<string, mixed> $product */
    private static function addProduct(array $product, int $quantity): void
    {
        $slug = (string) $product['slug'];
        $cart = [];
        foreach (self::items() as $item) {
            $cart[$item['slug']] = $item;
        }
        if (isset($cart[$slug])) {
            $cart[$slug]['quantity'] += $quantity;
        } else {
            $cart[$slug] = [
                'product_id' => (int) $product['id'],
                'slug' => $slug,
                'title' => (string) $product['title'],
                'sku' => $product['sku'] ?? null,
                'category' => $product['category'] ?? null,
                'quantity' => $quantity,
            ];
        }
        $_SESSION[self::SESSION_KEY] = array_values($cart);
    }
}
