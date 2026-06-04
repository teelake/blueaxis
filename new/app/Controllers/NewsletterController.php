<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\NewsletterSubscriber;
use App\Services\LeadNotificationService;
use App\Services\Validator;

final class NewsletterController extends Controller
{
    public function subscribe(): void
    {
        $this->validateCsrf();
        $email = trim((string) ($_POST['email'] ?? ''));

        $v = new Validator();
        $v->required('email', $email)->email('email', $email);

        if ($v->fails()) {
            Session::flash('newsletter_error', 'Please enter a valid email address.');
            redirect('/');
        }

        $result = NewsletterSubscriber::subscribe($email);
        LeadNotificationService::newsletterSubscribed($email, $result);
        $message = $result === 'existing'
            ? 'You are already subscribed to our updates list.'
            : 'You are subscribed. Thank you for joining our updates list.';
        Session::flash('newsletter_success', $message);
        redirect('/#newsletter');
    }
}
