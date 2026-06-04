<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;

abstract class AdminController extends Controller
{
    protected function requireAuth(): void
    {
        Auth::requireLogin();
    }

    protected function authorize(string $permission): void
    {
        Auth::requirePermission($permission);
    }
}
