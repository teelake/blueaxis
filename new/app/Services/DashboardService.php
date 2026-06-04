<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\Contact;
use App\Models\NewsletterSubscriber;
use App\Models\QuoteRequest;

final class DashboardService
{
    /** @return array<string, int> */
    public static function stats(): array
    {
        $leads30 = self::leadCountSinceDays(30);
        $leads7 = self::leadCountSinceDays(7);

        return [
            'quotes' => QuoteRequest::count(),
            'quotes_new' => QuoteRequest::countByStatus('new'),
            'contacts' => Contact::count(),
            'contacts_unread' => Contact::countUnread(),
            'posts' => BlogPost::countPublished(),
            'newsletter' => NewsletterSubscriber::countActive(),
            'pending_comments' => BlogComment::countPending(),
            'leads_30d' => $leads30,
            'leads_7d' => $leads7,
            'needs_attention' => QuoteRequest::countByStatus('new')
                + Contact::countUnread()
                + BlogComment::countPending(),
        ];
    }

    /**
     * Monthly quote + contact submissions for the last N months (oldest first).
     *
     * @return array{labels: list<string>, quotes: list<int>, contacts: list<int>, totals: list<int>}
     */
    public static function leadChart(int $months = 6): array
    {
        $months = max(3, min(12, $months));
        $keys = [];
        $labels = [];
        $now = new \DateTimeImmutable('first day of this month');

        for ($i = $months - 1; $i >= 0; $i--) {
            $d = $now->modify("-{$i} months");
            $keys[] = $d->format('Y-m');
            $labels[] = $d->format('M Y');
        }

        $quotes = self::countsByMonth('quote_requests', $keys);
        $contacts = self::countsByMonth('contacts', $keys);
        $totals = [];
        foreach ($keys as $i => $key) {
            $totals[] = ($quotes[$key] ?? 0) + ($contacts[$key] ?? 0);
        }

        return [
            'labels' => $labels,
            'quotes' => array_values(array_map(static fn (string $k): int => $quotes[$k] ?? 0, $keys)),
            'contacts' => array_values(array_map(static fn (string $k): int => $contacts[$k] ?? 0, $keys)),
            'totals' => $totals,
        ];
    }

    public static function leadCountSinceDays(int $days): int
    {
        $since = (new \DateTimeImmutable())->modify("-{$days} days")->format('Y-m-d H:i:s');
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'SELECT (
                (SELECT COUNT(*) FROM quote_requests WHERE created_at >= :since) +
                (SELECT COUNT(*) FROM contacts WHERE created_at >= :since)
            ) AS total'
        );
        $stmt->execute(['since' => $since]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * @param list<string> $monthKeys Y-m format
     * @return array<string, int>
     */
    private static function countsByMonth(string $table, array $monthKeys): array
    {
        if ($monthKeys === []) {
            return [];
        }
        $allowed = ['quote_requests', 'contacts'];
        if (!in_array($table, $allowed, true)) {
            return [];
        }

        $start = $monthKeys[0] . '-01 00:00:00';
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month_key, COUNT(*) AS cnt
             FROM {$table}
             WHERE created_at >= :start
             GROUP BY month_key"
        );
        $stmt->execute(['start' => $start]);
        $out = array_fill_keys($monthKeys, 0);
        foreach ($stmt->fetchAll() as $row) {
            $key = (string) $row['month_key'];
            if (isset($out[$key])) {
                $out[$key] = (int) $row['cnt'];
            }
        }
        return $out;
    }
}
