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
            'title' => 'Contact Inquiries',
            'items' => $result['items'],
            'total' => $result['total'],
            'search' => $search,
        ], 'layouts/admin');
    }

    public function show(array $params): void
    {
        $this->authorize(Permission::LEADS_CONTACTS);
        $item = Contact::find((int) ($params['id'] ?? 0));
        if ($item) {
            Contact::markRead((int) $item['id']);
        }
        $this->view('admin/contacts/show', ['title' => 'Inquiry', 'item' => $item], 'layouts/admin');
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
