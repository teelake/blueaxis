<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Permission;
use App\Core\Session;
use App\Models\Setting;
use App\Services\MediaUploadHelper;

final class SiteSettingsController extends AdminController
{
    private const GROUP = 'branding';

    public function edit(): void
    {
        $this->authorize(Permission::SETTINGS_SITE);
        $this->view('admin/settings/site', [
            'title' => 'Site branding',
            'pageDescription' => 'Upload the logo shown in the website header and footer.',
            'logoPath' => Setting::get('site_logo_path', ''),
            'logoAlt' => Setting::get('site_logo_alt', 'BlueAxis Logistics & Warehousing'),
            'footerInvert' => Setting::get('site_logo_footer_invert', '1') !== '0',
            'success' => flash('success'),
        ], 'layouts/admin');
    }

    public function save(): void
    {
        $this->authorize(Permission::SETTINGS_SITE);
        $this->validateCsrf();

        Setting::set(
            'site_logo_path',
            MediaUploadHelper::resolve('site_logo_path') ?? '',
            'image',
            self::GROUP
        );

        $alt = trim((string) ($_POST['site_logo_alt'] ?? ''));
        Setting::set('site_logo_alt', $alt !== '' ? $alt : 'BlueAxis Logistics & Warehousing', 'text', self::GROUP);
        Setting::set(
            'site_logo_footer_invert',
            isset($_POST['site_logo_footer_invert']) ? '1' : '0',
            'boolean',
            self::GROUP
        );

        Session::flash('success', 'Site logo saved. Refresh the public site to see changes.');
        redirect('admin/settings/site');
    }
}
