<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Services\MailConfig;
use App\Services\MailService;

final class EmailSettingsController extends Controller
{
    public function edit(): void
    {
        $this->requireSuperAdmin();
        $settings = MailConfig::forForm();
        $this->view('admin/settings/email', [
            'title' => 'Email settings',
            'pageDescription' => 'Configure how form submissions are emailed to your team.',
            'settings' => $settings,
            'hasPassword' => MailConfig::hasStoredPassword(),
            'success' => flash('success'),
            'error' => flash('error'),
        ], 'layouts/admin');
    }

    public function save(): void
    {
        $this->requireSuperAdmin();
        $this->validateCsrf();

        $driver = trim((string) ($_POST['mail_driver'] ?? 'mail'));
        if (!in_array($driver, ['mail', 'smtp'], true)) {
            $driver = 'mail';
        }

        $notifyTo = trim((string) ($_POST['mail_notify_to'] ?? ''));
        if ($notifyTo === '' || !filter_var($notifyTo, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please enter a valid notification email address.');
            redirect('admin/settings/email');
        }

        $from = trim((string) ($_POST['mail_from_address'] ?? ''));
        if ($from === '' || !filter_var($from, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please enter a valid from email address.');
            redirect('admin/settings/email');
        }

        if ($driver === 'smtp' && trim((string) ($_POST['mail_host'] ?? '')) === '') {
            Session::flash('error', 'SMTP host is required when using SMTP driver.');
            redirect('admin/settings/email');
        }

        MailConfig::save([
            'mail_driver' => $driver,
            'mail_host' => (string) ($_POST['mail_host'] ?? ''),
            'mail_port' => (string) ($_POST['mail_port'] ?? '587'),
            'mail_username' => (string) ($_POST['mail_username'] ?? ''),
            'mail_password' => (string) ($_POST['mail_password'] ?? ''),
            'mail_encryption' => (string) ($_POST['mail_encryption'] ?? 'tls'),
            'mail_from_address' => $from,
            'mail_from_name' => (string) ($_POST['mail_from_name'] ?? ''),
            'mail_notify_to' => $notifyTo,
            'mail_notify_contact' => $_POST['mail_notify_contact'] ?? '',
            'mail_notify_quote' => $_POST['mail_notify_quote'] ?? '',
            'mail_reply_to_lead' => $_POST['mail_reply_to_lead'] ?? '',
        ]);

        Session::flash('success', 'Email settings saved.');
        redirect('admin/settings/email');
    }

    public function test(): void
    {
        $this->requireSuperAdmin();
        $this->validateCsrf();

        $to = trim((string) ($_POST['test_email'] ?? ''));
        if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $to = MailConfig::notifyTo();
        }

        $html = '<p style="font-family:sans-serif;color:#334155">This is a test email from <strong>BlueAxis CMS</strong>.</p>'
            . '<p style="color:#64748b;font-size:14px">If you received this, your mail configuration is working.</p>';

        if (MailService::send($to, '[BlueAxis] Test email', $html)) {
            Session::flash('success', 'Test email sent to ' . $to . '.');
        } else {
            Session::flash('error', 'Test email failed. Check storage/logs/mail.log for details.');
        }
        redirect('admin/settings/email');
    }

    private function requireSuperAdmin(): void
    {
        Auth::requireLogin();
        if (!Auth::hasRole('super_admin')) {
            http_response_code(403);
            Session::flash('error', 'Only Super Admins can manage email settings.');
            redirect('admin/dashboard');
        }
    }
}
