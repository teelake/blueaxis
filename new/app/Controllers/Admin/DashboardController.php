<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Services\DashboardService;

final class DashboardController extends AdminController
{
    public function index(): void
    {
        $this->authorize(Permission::DASHBOARD);
        $this->view('admin/dashboard', [
            'title' => 'Dashboard',
            'pageDescription' => 'Leads, inquiries, and content at a glance — from your live website data.',
            'stats' => DashboardService::stats(),
            'leadChart' => DashboardService::leadChart(6),
        ], 'layouts/admin');
    }
}
