<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\NewsletterSubscriber;
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

        NewsletterSubscriber::subscribe($email);
        Session::flash('newsletter_success', 'You are subscribed. Thank you for joining our updates list.');
        redirect('/#newsletter');
    }
}
