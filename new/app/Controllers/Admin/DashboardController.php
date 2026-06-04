<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Services\DashboardService;

final class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $this->view('admin/dashboard', [
            'title' => 'Dashboard',
            'pageDescription' => 'Leads, inquiries, and content at a glance — from your live website data.',
            'stats' => DashboardService::stats(),
            'leadChart' => DashboardService::leadChart(6),
        ], 'layouts/admin');
    }
}
