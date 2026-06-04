<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Contact;
use App\Models\Page;
use App\Models\Setting;
use App\Services\FormRules;
use App\Services\LeadNotificationService;
use App\Services\SeoService;

final class ContactController extends Controller
{
    public function index(): void
    {
        $this->view('public/contact', [
            'seo' => SeoService::metaForPage(Page::findBySlug('contact'), [
                'title' => 'Contact Us | BlueAxis Logistics & Warehousing',
                'description' => 'Contact BlueAxis for general inquiries. For pricing and service quotes, use our dedicated quote request page.',
            ]),
            'contact' => Setting::allByGroup('contact'),
            'mapEmbedUrl' => map_embed_url(),
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

        $this->validateOrRedirect(FormRules::contact($input), 'contact', $input);

        $id = Contact::create($input);
        LeadNotificationService::contactSubmitted($input, $id);
        Session::clearOld();
        Session::flash('success', 'Thank you. Your message has been received.');
        redirect('contact');
    }

}
