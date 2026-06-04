<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Core\Session;
use App\Services\MailConfig;
use App\Services\MailService;

final class EmailSettingsController extends AdminController
{
    public function edit(): void
    {
        $this->authorize(Permission::SETTINGS_EMAIL);
        $settings = MailConfig::forForm();
        $this->view('admin/settings/email', [
            'title' => 'Email settings',
            'pageDescription' => 'Configure team alerts and visitor confirmation emails for all public forms.',
            'settings' => $settings,
            'hasPassword' => MailConfig::hasStoredPassword(),
            'success' => flash('success'),
            'error' => flash('error'),
        ], 'layouts/admin');
    }

    public function save(): void
    {
        $this->authorize(Permission::SETTINGS_EMAIL);
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
            'mail_notify_newsletter' => $_POST['mail_notify_newsletter'] ?? '',
            'mail_notify_comment' => $_POST['mail_notify_comment'] ?? '',
            'mail_confirm_contact' => $_POST['mail_confirm_contact'] ?? '',
            'mail_confirm_quote' => $_POST['mail_confirm_quote'] ?? '',
            'mail_confirm_newsletter' => $_POST['mail_confirm_newsletter'] ?? '',
            'mail_confirm_comment' => $_POST['mail_confirm_comment'] ?? '',
            'mail_reply_to_lead' => $_POST['mail_reply_to_lead'] ?? '',
        ]);

        Session::flash('success', 'Email settings saved.');
        redirect('admin/settings/email');
    }

    public function test(): void
    {
        $this->authorize(Permission::SETTINGS_EMAIL);
        $this->validateCsrf();

        $to = trim((string) ($_POST['test_email'] ?? ''));
        if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $to = MailConfig::notifyTo();
        }

        $ok = MailService::send(
            $to,
            'BlueAxis — test email',
            '<p>This is a test message from your BlueAxis admin email settings.</p>'
        );

        if ($ok) {
            Session::flash('success', 'Test email sent to ' . $to);
        } else {
            Session::flash('error', 'Test email failed. Check storage/logs/mail.log for details.');
        }
        redirect('admin/settings/email');
    }
}
