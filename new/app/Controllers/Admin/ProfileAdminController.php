<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Session;
use App\Models\Admin;
use App\Services\FormRules;

final class ProfileAdminController extends AdminController
{
    public function edit(): void
    {
        $this->requireAuth();
        $admin = Admin::find(Auth::id() ?? 0);
        if (!$admin) {
            redirect('admin/login');
        }
        $this->view('admin/profile/edit', [
            'title' => 'Your profile',
            'pageDescription' => 'Update the name and email shown in the admin.',
            'admin' => $admin,
        ], 'layouts/admin');
    }

    public function update(): void
    {
        $this->requireAuth();
        $this->validateCsrf();
        $id = Auth::id() ?? 0;
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));

        $input = ['name' => $name, 'email' => $email];
        $this->validateOrRedirect(FormRules::adminProfile($input), 'admin/profile', $input);

        $existing = Admin::findByEmail($email);
        if ($existing && (int) $existing['id'] !== $id) {
            Session::flash('error', 'That email is already in use.');
            redirect('admin/profile');
        }

        Admin::updateProfile($id, $name, $email);
        $_SESSION['admin']['name'] = $name;
        $_SESSION['admin']['email'] = $email;
        Session::flash('success', 'Profile updated.');
        redirect('admin/profile');
    }

    public function passwordForm(): void
    {
        $this->requireAuth();
        $this->view('admin/profile/password', [
            'title' => 'Change password',
            'pageDescription' => 'Set a new password for your admin account.',
        ], 'layouts/admin');
    }

    public function updatePassword(): void
    {
        $this->requireAuth();
        $this->validateCsrf();
        $id = Auth::id() ?? 0;
        $current = (string) ($_POST['current_password'] ?? '');
        $new = (string) ($_POST['new_password'] ?? '');
        $confirm = (string) ($_POST['new_password_confirmation'] ?? '');

        $admin = Admin::find($id);
        if (!$admin || !password_verify($current, (string) $admin['password'])) {
            Session::flash('error', 'Current password is incorrect.');
            redirect('admin/profile/password');
        }

        $this->validateOrRedirect(
            FormRules::adminPasswordChange($current, $new, $confirm),
            'admin/profile/password'
        );

        Admin::setPassword($id, $new);
        Session::flash('success', 'Password changed successfully.');
        redirect('admin/dashboard');
    }
}
