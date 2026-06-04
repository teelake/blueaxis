<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Models\Contact;

final class ContactAdminController extends AdminController
{
    public function index(): void
    {
        $this->authorize(Permission::LEADS_CONTACTS);
        $page = (int) ($_GET['page'] ?? 1);
        $search = trim((string) ($_GET['q'] ?? ''));
        $result = Contact::paginate($page, (int) config('app.per_page_admin'), $search);
        $this->view('admin/contacts/index', [
            'title' => 'Contact messages',
            'pageDescription' => 'Browse inquiries from the contact form. Open any row to read the full message.',
            'items' => $result['items'],
            'total' => $result['total'],
            'search' => $search,
        ], 'layouts/admin');
    }

    public function show(array $params): void
    {
        $this->authorize(Permission::LEADS_CONTACTS);
        $id = (int) ($params['id'] ?? 0);
        $item = Contact::find($id);
        if ($item) {
            Contact::markRead($id);
            $item['is_read'] = 1;
        }
        $this->view('admin/contacts/show', [
            'title' => $item ? 'Contact #' . $id : 'Message not found',
            'pageDescription' => $item ? 'Full message and contact details.' : null,
            'item' => $item,
            'success' => flash('success'),
            'error' => flash('error'),
        ], 'layouts/admin');
    }

    public function export(): void
    {
        $this->authorize(Permission::LEADS_EXPORT);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="contacts.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID', 'Name', 'Company', 'Email', 'Phone', 'Message', 'Date']);
        foreach (Contact::exportAll() as $row) {
            fputcsv($out, [
                $row['id'], $row['name'], $row['company'], $row['email'],
                $row['phone'], $row['message'], $row['created_at'],
            ]);
        }
        fclose($out);
        exit;
    }
}
