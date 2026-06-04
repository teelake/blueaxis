<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Core\Session;
use App\Models\Setting;
use App\Services\FormRules;

final class SocialSettingsController extends AdminController
{
    private const GROUP = 'social';

    /** @var array<string, array{label: string, placeholder: string}> */
    public const PLATFORMS = [
        'social_linkedin' => [
            'label' => 'LinkedIn',
            'placeholder' => 'https://www.linkedin.com/company/your-page',
        ],
        'social_facebook' => [
            'label' => 'Facebook',
            'placeholder' => 'https://www.facebook.com/your-page',
        ],
        'social_instagram' => [
            'label' => 'Instagram',
            'placeholder' => 'https://www.instagram.com/your-account',
        ],
        'social_x' => [
            'label' => 'X (Twitter)',
            'placeholder' => 'https://x.com/your-account',
        ],
        'social_youtube' => [
            'label' => 'YouTube',
            'placeholder' => 'https://www.youtube.com/@your-channel',
        ],
    ];

    public function edit(): void
    {
        $this->authorize(Permission::SETTINGS_SITE);
        $values = [];
        foreach (array_keys(self::PLATFORMS) as $key) {
            $raw = Setting::get($key, '');
            $values[$key] = $raw === '#' ? '' : (string) $raw;
        }

        $this->view('admin/settings/social', [
            'title' => 'Social media',
            'pageDescription' => 'Add links to your profiles. They appear in the site footer and on the contact page.',
            'platforms' => self::PLATFORMS,
            'values' => $values,
            'success' => flash('success'),
        ], 'layouts/admin');
    }

    public function save(): void
    {
        $this->authorize(Permission::SETTINGS_SITE);
        $this->validateCsrf();

        $allErrors = [];
        $urls = [];
        foreach (self::PLATFORMS as $key => $meta) {
            $urls[$key] = normalize_social_url((string) ($_POST[$key] ?? ''));
            foreach (FormRules::socialUrl($key, $urls[$key], $meta['label'])->errors() as $field => $message) {
                $allErrors[$field] = $message;
            }
        }
        if ($allErrors !== []) {
            Session::setErrors($allErrors);
            Session::setOld($_POST);
            Session::flash('error', reset($allErrors) ?: 'Please correct the social media URLs.');
            redirect('admin/settings/social');
        }
        Session::clearErrors();

        foreach ($urls as $key => $url) {
            Setting::set($key, $url, 'text', self::GROUP);
        }

        Session::flash('success', 'Social media links saved.');
        redirect('admin/settings/social');
    }
}
