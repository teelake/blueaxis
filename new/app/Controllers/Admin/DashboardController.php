<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\BlogPost;
use App\Models\Contact;
use App\Models\QuoteRequest;

final class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();
        $this->view('admin/dashboard', [
            'title' => 'Dashboard',
            'stats' => [
                'quotes' => QuoteRequest::count(),
                'quotes_new' => QuoteRequest::countByStatus('new'),
                'contacts' => Contact::count(),
                'contacts_unread' => Contact::countUnread(),
                'posts' => BlogPost::countPublished(),
                'visits' => 1240,
                'pageviews' => 4820,
            ],
        ], 'layouts/admin');
    }
}
