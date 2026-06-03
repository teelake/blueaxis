<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Contact;
use App\Models\Page;
use App\Models\Setting;
use App\Services\LeadNotificationService;
use App\Services\SeoService;
use App\Services\Validator;

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

        $v = new Validator();
        $v->required('name', $input['name'])->required('email', $input['email'])
            ->email('email', $input['email'])->required('message', $input['message']);

        if ($v->fails()) {
            Session::setOld($input);
            Session::flash('error', 'Please correct the highlighted fields.');
            redirect('contact');
        }

        $id = Contact::create($input);
        LeadNotificationService::contactSubmitted($input, $id);
        Session::clearOld();
        Session::flash('success', 'Thank you. Your message has been received.');
        redirect('contact');
    }

}
