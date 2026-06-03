<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Contact;
use App\Models\Page;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\Setting;
use App\Services\SeoService;
use App\Services\Validator;

final class ContactController extends Controller
{
    public function index(): void
    {
        $this->view('public/contact', [
            'seo' => SeoService::metaForPage(Page::findBySlug('contact')),
            'contact' => Setting::allByGroup('contact'),
            'services' => Service::published(),
            'success' => flash('success'),
            'error' => flash('error'),
        ]);
    }

    public function submitContact(): void
    {
        $this->validateCsrf();
        $input = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'company' => trim((string) ($_POST['company'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
            'message' => trim((string) ($_POST['message'] ?? '')),
        ];

        $v = new Validator();
        $v->required('name', $input['name'])->required('email', $input['email'])
            ->email('email', $input['email'])->required('message', $input['message']);

        if ($v->fails()) {
            Session::setOld($input);
            Session::flash('error', 'Please correct the highlighted fields.');
            redirect('contact');
        }

        Contact::create($input);
        Session::clearOld();
        Session::flash('success', 'Thank you. Your message has been received.');
        redirect('contact');
    }

    public function submitQuote(): void
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
        $v->required('name', $input['name'])->required('email', $input['email'])
            ->email('email', $input['email'])->required('service_needed', $input['service_needed']);

        if ($v->fails()) {
            Session::setOld($input);
            Session::flash('error', 'Please complete all required quote fields.');
            redirect('contact#quote');
        }

        QuoteRequest::create($input);
        Session::clearOld();
        Session::flash('success', 'Your quote request has been submitted. Our team will respond shortly.');
        redirect('contact#quote');
    }
}
