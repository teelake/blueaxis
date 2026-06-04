<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Core\Session;
use App\Services\FormRules;
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

        $data = [
            'mail_driver' => trim((string) ($_POST['mail_driver'] ?? 'mail')),
            'mail_host' => trim((string) ($_POST['mail_host'] ?? '')),
            'mail_port' => (int) ($_POST['mail_port'] ?? 587),
            'mail_from_address' => trim((string) ($_POST['mail_from_address'] ?? '')),
            'mail_from_name' => trim((string) ($_POST['mail_from_name'] ?? '')),
            'mail_notify_to' => trim((string) ($_POST['mail_notify_to'] ?? '')),
        ];
        $driver = in_array($data['mail_driver'], ['mail', 'smtp'], true) ? $data['mail_driver'] : 'mail';
        $this->validateOrRedirect(FormRules::emailSettings($data), 'admin/settings/email', $_POST);

        MailConfig::save([
            'mail_driver' => $driver,
            'mail_host' => (string) ($_POST['mail_host'] ?? ''),
            'mail_port' => (string) ($_POST['mail_port'] ?? '587'),
            'mail_username' => (string) ($_POST['mail_username'] ?? ''),
            'mail_password' => (string) ($_POST['mail_password'] ?? ''),
            'mail_encryption' => (string) ($_POST['mail_encryption'] ?? 'tls'),
            'mail_from_address' => $data['mail_from_address'],
            'mail_from_name' => (string) ($_POST['mail_from_name'] ?? ''),
            'mail_notify_to' => $data['mail_notify_to'],
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
        if ($to !== '') {
            $testV = FormRules::emailTest($to);
            if ($testV->fails()) {
                Session::flash('error', $testV->firstError() ?? 'Invalid test email.');
                redirect('admin/settings/email');
            }
        } else {
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
