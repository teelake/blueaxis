<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;

final class ProductBulkImportService
{
    /** @var list<string> */
    public const COLUMNS = [
        'sku',
        'title',
        'slug',
        'category',
        'excerpt',
        'price',
        'price_unit',
        'origin_region',
        'size',
        'pack_format',
        'storage_notes',
        'sort_order',
    ];

    public function streamTemplate(): void
    {
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="products-import-template.csv"');
        $out = fopen('php://output', 'w');
        if ($out === false) {
            return;
        }
        fprintf($out, "\xEF\xBB\xBF");
        fputcsv($out, self::COLUMNS);
        fputcsv($out, [
            'BAX-PO-001',
            'Red Palm Oil (20L)',
            'red-palm-oil-20l',
            'Oils & fats',
            'Cold-pressed bulk palm oil for wholesale partners.',
            '89.99',
            'per case',
            'West Africa',
            '20L',
            'drum / palletized',
            'Store below 25°C',
            '10',
        ]);
        fputcsv($out, [
            'BAX-RF-002',
            'Jasmine Rice (25kg)',
            '',
            'Grains & rice',
            'Premium long-grain jasmine rice for food service.',
            '',
            '',
            'Southeast Asia',
            '25kg',
            'bag',
            'Keep dry; cool storage',
            '20',
        ]);
        fclose($out);
        exit;
    }

    /**
     * @return array{rows: list<array<string, string>>, errors: list<string>}
     */
    public function parseFile(string $path): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return ['rows' => [], 'errors' => ['Could not read the uploaded file.']];
        }

        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);
            return ['rows' => [], 'errors' => ['The CSV file is empty.']];
        }

        $firstLine = $this->stripBom($firstLine);
        $headers = str_getcsv($firstLine);
        $headers = array_map(static fn (string $h): string => strtolower(trim($h)), $headers);
        $missing = array_diff(self::COLUMNS, $headers);
        if ($missing !== []) {
            fclose($handle);
            return [
                'rows' => [],
                'errors' => ['Missing required columns: ' . implode(', ', $missing) . '. Download the template and use those headers.'],
            ];
        }

        $indexes = [];
        foreach (self::COLUMNS as $column) {
            $indexes[$column] = array_search($column, $headers, true);
        }

        $rows = [];
        $errors = [];
        $line = 1;
        while (($data = fgetcsv($handle)) !== false) {
            $line++;
            if ($this->isEmptyCsvLine($data)) {
                continue;
            }
            $row = [];
            foreach (self::COLUMNS as $column) {
                $index = $indexes[$column];
                $row[$column] = trim((string) ($data[$index] ?? ''));
            }
            $rows[] = $row;
        }
        fclose($handle);

        if ($rows === [] && $errors === []) {
            $errors[] = 'No product rows found. Add at least one row below the header.';
        }

        return ['rows' => $rows, 'errors' => $errors];
    }

    /**
     * @param list<array<string, string>> $rows
     * @return array{created: int, updated: int, errors: list<array{row: int, sku: string, message: string}>}
     */
    public function import(array $rows): array
    {
        $created = 0;
        $updated = 0;
        $errors = [];
        $seenSkus = [];

        foreach ($rows as $i => $row) {
            $rowNum = $i + 2;
            $sku = $row['sku'];
            if ($sku !== '') {
                if (isset($seenSkus[$sku])) {
                    $errors[] = ['row' => $rowNum, 'sku' => $sku, 'message' => 'Duplicate SKU in this file.'];
                    continue;
                }
                $seenSkus[$sku] = true;
            }

            $existing = $sku !== '' ? Product::findBySku($sku) : null;
            if ($existing === null && trim($row['title']) === '') {
                $errors[] = ['row' => $rowNum, 'sku' => $sku, 'message' => 'Title is required for new products.'];
                continue;
            }

            if ($row['price'] !== '' && !is_numeric(str_replace(',', '', $row['price']))) {
                $errors[] = [
                    'row' => $rowNum,
                    'sku' => $sku !== '' ? $sku : ($row['title'] ?: '—'),
                    'message' => 'Price must be a number or left empty.',
                ];
                continue;
            }

            $payload = $this->buildPayload($row, $existing);
            $validation = FormRules::productBulkRow($payload, $existing !== null);
            $rowErrors = $validation->errors();
            if ($rowErrors !== []) {
                $errors[] = [
                    'row' => $rowNum,
                    'sku' => $sku !== '' ? $sku : ($row['title'] ?: '—'),
                    'message' => implode(' ', array_values($rowErrors)),
                ];
                continue;
            }

            if ($existing !== null) {
                Product::updateBulkFields((int) $existing['id'], $payload);
                $updated++;
                continue;
            }

            Product::createFromBulk($payload);
            $created++;
        }

        return ['created' => $created, 'updated' => $updated, 'errors' => $errors];
    }

    /**
     * @param array<string, string> $row
     * @param array<string, mixed>|null $existing
     * @return array<string, mixed>
     */
    private function buildPayload(array $row, ?array $existing): array
    {
        $title = trim($row['title']);
        if ($title === '' && $existing !== null) {
            $title = (string) $existing['title'];
        }

        $slugInput = trim($row['slug']);
        if ($slugInput !== '') {
            $slug = slugify($slugInput) ?: 'product';
            if ($existing === null) {
                $slug = $this->uniqueSlug($slug);
            }
        } elseif ($existing !== null) {
            $slug = (string) $existing['slug'];
        } else {
            $slug = $this->uniqueSlug(slugify($title) ?: 'product');
        }

        $payload = [
            'title' => $title,
            'slug' => $slug,
            'category' => $this->nullable($row['category']),
            'sku' => $this->nullable($row['sku']),
            'price' => $this->parsePrice($row['price']),
            'price_unit' => $this->nullable($row['price_unit']),
            'excerpt' => $this->nullable($row['excerpt']),
            'origin_region' => $this->nullable($row['origin_region']),
            'size' => $this->nullable($row['size']),
            'pack_format' => $this->nullable($row['pack_format']),
            'storage_notes' => $this->nullable($row['storage_notes']),
            'sort_order' => $this->parseSortOrder($row['sort_order']),
        ];
        if ($existing !== null) {
            $payload['id'] = (int) $existing['id'];
        }
        return $payload;
    }

    private function uniqueSlug(string $base): string
    {
        $base = trim($base, '-') ?: 'product';
        $candidate = $base;
        $suffix = 2;
        while (Product::slugExists($candidate)) {
            $candidate = $base . '-' . $suffix;
            $suffix++;
        }
        return $candidate;
    }

    private function parsePrice(string $raw): ?float
    {
        $value = trim($raw);
        if ($value === '') {
            return null;
        }
        if (!is_numeric($value)) {
            return null;
        }
        $price = round((float) $value, 2);
        return $price >= 0 ? $price : null;
    }

    private function parseSortOrder(string $raw): int
    {
        $value = trim($raw);
        if ($value === '' || !ctype_digit($value)) {
            return 0;
        }
        return min(9999, (int) $value);
    }

    private function nullable(string $value): ?string
    {
        $value = trim($value);
        return $value === '' ? null : $value;
    }

    private function stripBom(string $line): string
    {
        if (str_starts_with($line, "\xEF\xBB\xBF")) {
            return substr($line, 3);
        }
        return $line;
    }

    /** @param list<string|null> $data */
    private function isEmptyCsvLine(array $data): bool
    {
        foreach ($data as $cell) {
            if (trim((string) $cell) !== '') {
                return false;
            }
        }
        return true;
    }
}
