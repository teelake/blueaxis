<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Admin;
use App\Services\Validator;

final class ProfileAdminController extends Controller
{
    public function edit(): void
    {
        Auth::requireLogin();
        $admin = Admin::find(Auth::id() ?? 0);
        if (!$admin) {
            redirect('admin/login');
        }
        $this->view('admin/profile/edit', [
            'title' => 'Edit profile',
            'admin' => $admin,
        ], 'layouts/admin');
    }

    public function update(): void
    {
        Auth::requireLogin();
        $this->validateCsrf();
        $id = Auth::id() ?? 0;
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));

        $v = new Validator();
        $v->required('name', $name)->required('email', $email)->email('email', $email);
        if ($v->fails()) {
            Session::flash('error', 'Please provide a valid name and email.');
            redirect('admin/profile');
        }

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
        Auth::requireLogin();
        $this->view('admin/profile/password', [
            'title' => 'Change password',
        ], 'layouts/admin');
    }

    public function updatePassword(): void
    {
        Auth::requireLogin();
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

        if (strlen($new) < 8) {
            Session::flash('error', 'New password must be at least 8 characters.');
            redirect('admin/profile/password');
        }
        if ($new !== $confirm) {
            Session::flash('error', 'New passwords do not match.');
            redirect('admin/profile/password');
        }

        Admin::setPassword($id, $new);
        Session::flash('success', 'Password changed successfully.');
        redirect('admin/dashboard');
    }
}
