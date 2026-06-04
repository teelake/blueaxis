<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Core\Session;
use App\Models\QuoteRequest;
use App\Services\QuoteCartService;

final class QuoteAdminController extends AdminController
{
    public function index(): void
    {
        $this->authorize(Permission::LEADS_QUOTES);
        $page = (int) ($_GET['page'] ?? 1);
        $search = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));
        $result = QuoteRequest::paginate($page, (int) config('app.per_page_admin'), $search, $status);
        $this->view('admin/quotes/index', [
            'title' => 'Quote Requests',
            'items' => $result['items'],
            'total' => $result['total'],
            'search' => $search,
            'status' => $status,
            'success' => flash('success'),
        ], 'layouts/admin');
    }

    public function show(array $params): void
    {
        $this->authorize(Permission::LEADS_QUOTES);
        $item = QuoteRequest::find((int) ($params['id'] ?? 0));
        $this->view('admin/quotes/show', [
            'title' => 'Quote Request',
            'item' => $item,
            'success' => flash('success'),
        ], 'layouts/admin');
    }

    public function updateStatus(array $params): void
    {
        $this->authorize(Permission::LEADS_QUOTES);
        $this->validateCsrf();
        $status = (string) ($_POST['status'] ?? 'new');
        $allowed = ['new', 'in_review', 'contacted', 'closed'];
        if (!in_array($status, $allowed, true)) {
            $status = 'new';
        }
        QuoteRequest::updateStatus(
            (int) ($params['id'] ?? 0),
            $status,
            trim((string) ($_POST['admin_notes'] ?? '')) ?: null
        );
        Session::flash('success', 'Quote status updated.');
        redirect('admin/quotes/' . ($params['id'] ?? ''));
    }

    public function export(): void
    {
        $this->authorize(Permission::LEADS_EXPORT);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="quote-requests.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID', 'Name', 'Company', 'Email', 'Phone', 'Service', 'Products', 'Message', 'Status', 'Date']);
        foreach (QuoteRequest::exportAll() as $row) {
            fputcsv($out, [
                $row['id'], $row['name'], $row['company'], $row['email'],
                $row['phone'], $row['service_needed'],
                QuoteCartService::formatLines($row['products_json'] ?? null),
                $row['message'],
                $row['status'], $row['created_at'],
            ]);
        }
        fclose($out);
        exit;
    }
}
