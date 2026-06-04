<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Services\FormRules;

final class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            redirect('admin/dashboard');
        }
        $this->view('admin/auth/login', [
            'title' => 'Admin Login',
            'error' => flash('error'),
        ], 'layouts/admin-guest');
    }

    public function login(): void
    {
        $this->validateCsrf();
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $this->validateOrRedirect(
            FormRules::adminLogin($email, $password),
            'admin/login',
            ['email' => $email],
            'Please enter a valid email and password.'
        );

        if (!Auth::attempt($email, $password)) {
            Session::flash('error', 'Invalid credentials.');
            redirect('admin/login');
        }
        redirect('admin/dashboard');
    }

    public function logout(): void
    {
        $this->validateCsrf();
        Auth::logout();
        redirect('admin/login');
    }
}
