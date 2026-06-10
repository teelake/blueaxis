<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;

final class ProductBulkImportService
{
    /** @var list<string> */
    public const COLUMNS = [
        'title',
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
            'Red Palm Oil (20L)',
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
            'Jasmine Rice (25kg)',
            'Flours & staples',
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
        while (($data = fgetcsv($handle)) !== false) {
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
     * @return array{created: int, updated: int, errors: list<array{row: int, title: string, message: string}>}
     */
    public function import(array $rows): array
    {
        $created = 0;
        $updated = 0;
        $errors = [];
        $seenTitles = [];

        foreach ($rows as $i => $row) {
            $rowNum = $i + 2;
            $title = trim($row['title']);
            if ($title === '') {
                $errors[] = ['row' => $rowNum, 'title' => '—', 'message' => 'Title is required.'];
                continue;
            }

            $titleKey = mb_strtolower($title);
            if (isset($seenTitles[$titleKey])) {
                $errors[] = ['row' => $rowNum, 'title' => $title, 'message' => 'Duplicate product title in this file.'];
                continue;
            }
            $seenTitles[$titleKey] = true;

            $existing = Product::findByTitleExact($title);

            if ($row['price'] !== '' && !is_numeric(str_replace(',', '', $row['price']))) {
                $errors[] = [
                    'row' => $rowNum,
                    'title' => $title,
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
                    'title' => $title,
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
        $identifiers = Product::resolveIdentifiers($title, $existing);

        $payload = [
            'title' => $title,
            'slug' => $identifiers['slug'],
            'category' => $this->nullable($row['category']),
            'sku' => $identifiers['sku'],
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
