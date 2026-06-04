<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Page;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Services\FormRules;
use App\Services\LeadNotificationService;
use App\Services\QuoteCartService;
use App\Services\SeoService;

final class QuoteController extends Controller
{
    public function index(): void
    {
        $addSlug = trim((string) ($_GET['add'] ?? $_GET['product'] ?? ''));
        if ($addSlug !== '') {
            if (QuoteCartService::addBySlug($addSlug)) {
                Session::flash('success', 'Product added to your quote list.');
            } else {
                Session::flash('error', 'That product could not be added.');
            }
            redirect('quote');
        }

        $this->view('public/quote', [
            'seo' => SeoService::metaForPage(Page::findBySlug('quote'), [
                'title' => 'Request a Quote | BlueAxis Logistics & Warehousing',
                'description' => 'Request a B2B logistics quote for importation, warehousing, and distribution services across Canada.',
            ]),
            'services' => Service::published(),
            'cartItems' => QuoteCartService::items(),
            'success' => flash('success'),
            'error' => flash('error'),
        ]);
    }

    public function addToCart(): void
    {
        $this->validateCsrf();
        $slug = trim((string) ($_POST['product_slug'] ?? ''));
        $qty = max(1, (int) ($_POST['quantity'] ?? 1));
        $redirectTo = trim((string) ($_POST['redirect'] ?? 'quote'));

        $cartValidator = FormRules::quoteCartAdd($slug, $qty);
        if ($cartValidator->fails()) {
            Session::flash('error', $cartValidator->firstError() ?? 'Could not add this product to your quote list.');
            redirect($redirectTo !== '' ? $redirectTo : 'products');
        }

        if (!QuoteCartService::addBySlug($slug, $qty)) {
            Session::flash('error', 'Could not add this product to your quote list.');
            redirect($redirectTo !== '' ? $redirectTo : 'products');
        }

        Session::flash('success', 'Added to your quote list.');
        redirect($redirectTo !== '' ? $redirectTo : 'quote');
    }

    public function removeFromCart(): void
    {
        $this->validateCsrf();
        $slug = trim((string) ($_POST['product_slug'] ?? ''));
        if ($slug !== '') {
            QuoteCartService::remove($slug);
            Session::flash('success', 'Product removed from your quote list.');
        }
        redirect('quote');
    }

    public function submit(): void
    {
        $this->validateCsrf();

        if (!empty($_POST['cart_qty']) && is_array($_POST['cart_qty'])) {
            QuoteCartService::syncQuantitiesFromPost($_POST['cart_qty']);
        }

        $cartItems = QuoteCartService::items();
        $input = [
            'name' => trim((string) ($_POST['name'] ?? '')),
            'company' => trim((string) ($_POST['company'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
            'service_needed' => trim((string) ($_POST['service_needed'] ?? '')),
            'message' => trim((string) ($_POST['message'] ?? '')),
            'products_json' => QuoteCartService::toJson(),
        ];

        if ($cartItems !== [] && $input['service_needed'] === '') {
            $input['service_needed'] = 'Product catalog / wholesale SKUs';
        }

        $this->validateOrRedirect(FormRules::quote($input), 'quote', $input);

        $id = QuoteRequest::create($input);
        LeadNotificationService::quoteSubmitted($input, $id);
        QuoteCartService::clear();
        Session::clearOld();
        Session::flash('success', 'Thank you. Your quote request has been received. Our team will respond within one business day.');
        redirect('quote');
    }
}
