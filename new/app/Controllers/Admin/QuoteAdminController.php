<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Core\Session;
use App\Models\QuoteRequest;
use App\Services\FormRules;
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
            'title' => 'Quote requests',
            'pageDescription' => 'B2B quote submissions from your website. Open any request for full details and status.',
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
        $id = (int) ($params['id'] ?? 0);
        $item = QuoteRequest::find($id);
        $this->view('admin/quotes/show', [
            'title' => $item ? 'Quote #' . $id : 'Quote not found',
            'pageDescription' => $item ? 'Full quote request, products, and follow-up.' : null,
            'item' => $item,
            'success' => flash('success'),
            'error' => flash('error'),
        ], 'layouts/admin');
    }

    public function updateStatus(array $params): void
    {
        $this->authorize(Permission::LEADS_QUOTES);
        $this->validateCsrf();
        $status = (string) ($_POST['status'] ?? 'new');
        $notes = trim((string) ($_POST['admin_notes'] ?? ''));
        $id = (int) ($params['id'] ?? 0);
        $this->validateOrRedirect(FormRules::quoteStatus($status, $notes), 'admin/quotes/' . $id);
        QuoteRequest::updateStatus($id, $status, $notes !== '' ? $notes : null);
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
