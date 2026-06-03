<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Page;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Services\LeadNotificationService;
use App\Services\SeoService;
use App\Services\Validator;

final class QuoteController extends Controller
{
    public function index(): void
    {
        $this->view('public/quote', [
            'seo' => SeoService::metaForPage(Page::findBySlug('quote'), [
                'title' => 'Request a Quote | BlueAxis Logistics & Warehousing',
                'description' => 'Request a B2B logistics quote for importation, warehousing, and distribution services across Canada.',
            ]),
            'services' => Service::published(),
            'success' => flash('success'),
            'error' => flash('error'),
        ]);
    }

    public function submit(): void
    {
        $this->validateCsrf();
        $input = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'company' => trim((string) ($_POST['company'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
            'service_needed' => trim((string) ($_POST['service_needed'] ?? '')),
            'message' => trim((string) ($_POST['message'] ?? '')),
        ];

        $v = new Validator();
        $v->required('name', $input['name'])
            ->required('company', $input['company'], 'Company name is required for quote requests.')
            ->required('email', $input['email'])
            ->email('email', $input['email'])
            ->required('service_needed', $input['service_needed']);

        if ($v->fails()) {
            Session::setOld($input);
            Session::flash('error', 'Please complete all required fields.');
            redirect('quote');
        }

        $id = QuoteRequest::create($input);
        LeadNotificationService::quoteSubmitted($input, $id);
        Session::clearOld();
        Session::flash('success', 'Thank you. Your quote request has been received. Our team will respond within one business day.');
        redirect('quote');
    }
}
