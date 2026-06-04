<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\NewsletterSubscriber;
use App\Services\FormRules;
use App\Services\LeadNotificationService;

final class NewsletterController extends Controller
{
    public function subscribe(): void
    {
        $this->validateCsrf();
        $email = trim((string) ($_POST['email'] ?? ''));

        $v = FormRules::newsletter($email);
        if ($v->fails()) {
            Session::setErrors($v->errors());
            Session::flash('newsletter_error', $v->firstError() ?? 'Please enter a valid email address.');
            redirect('/#newsletter');
        }
        Session::clearErrors();

        $result = NewsletterSubscriber::subscribe($email);
        LeadNotificationService::newsletterSubscribed($email, $result);
        $message = $result === 'existing'
            ? 'You are already subscribed to our updates list.'
            : 'You are subscribed. Thank you for joining our updates list.';
        Session::flash('newsletter_success', $message);
        redirect('/#newsletter');
    }
}
