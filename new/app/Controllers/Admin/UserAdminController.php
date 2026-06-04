<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Permission;
use App\Core\Session;
use App\Models\Admin;
use App\Models\Role;

final class UserAdminController extends AdminController
{
    public function index(): void
    {
        $this->authorize(Permission::USERS_MANAGE);
        $this->view('admin/users/index', [
            'title' => 'Team & roles',
            'pageDescription' => 'Manage who can sign in to the admin and what they are allowed to do.',
            'users' => Admin::allWithRoles(),
            'roles' => Role::all(),
            'permissionLabels' => Permission::LABELS,
            'roleMatrix' => Permission::matrix(),
        ], 'layouts/admin');
    }

    public function create(): void
    {
        $this->authorize(Permission::USERS_MANAGE);
        $this->formView(null);
    }

    public function store(): void
    {
        $this->authorize(Permission::USERS_MANAGE);
        $this->validateCsrf();
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $roleId = (int) ($_POST['role_id'] ?? 0);

        if ($name === '' || $email === '' || strlen($password) < 8) {
            Session::flash('error', 'Name, email, and a password (8+ characters) are required.');
            redirect('admin/users/create');
        }

        if (Admin::findByEmail($email)) {
            Session::flash('error', 'An account with that email already exists.');
            redirect('admin/users/create');
        }

        if (!Role::findBySlug($this->roleSlugFromId($roleId))) {
            Session::flash('error', 'Please select a valid role.');
            redirect('admin/users/create');
        }

        Admin::createUser($name, $email, $password, $roleId);
        Session::flash('success', 'Team member created.');
        redirect('admin/users');
    }

    public function edit(array $params): void
    {
        $this->authorize(Permission::USERS_MANAGE);
        $user = Admin::find((int) ($params['id'] ?? 0));
        if (!$user) {
            redirect('admin/users');
        }
        $this->formView($user);
    }

    public function update(array $params): void
    {
        $this->authorize(Permission::USERS_MANAGE);
        $this->validateCsrf();
        $id = (int) ($params['id'] ?? 0);
        $user = Admin::find($id);
        if (!$user) {
            redirect('admin/users');
        }

        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $roleId = (int) ($_POST['role_id'] ?? 0);
        $isActive = isset($_POST['is_active']);
        $newPassword = (string) ($_POST['password'] ?? '');

        if ($name === '' || $email === '') {
            Session::flash('error', 'Name and email are required.');
            redirect('admin/users/' . $id . '/edit');
        }

        if (Admin::emailTakenByOther($email, $id)) {
            Session::flash('error', 'That email is already used by another account.');
            redirect('admin/users/' . $id . '/edit');
        }

        $newRoleSlug = $this->roleSlugFromId($roleId);
        if ($newRoleSlug === null) {
            Session::flash('error', 'Please select a valid role.');
            redirect('admin/users/' . $id . '/edit');
        }

        if ($user['role_slug'] === 'super_admin' && $newRoleSlug !== 'super_admin' && !$isActive) {
            if (Admin::countActiveByRoleSlug('super_admin') <= 1) {
                Session::flash('error', 'You cannot deactivate or demote the last Super Admin.');
                redirect('admin/users/' . $id . '/edit');
            }
        }

        if ($user['role_slug'] === 'super_admin' && $newRoleSlug !== 'super_admin') {
            if (Admin::countActiveByRoleSlug('super_admin') <= 1) {
                Session::flash('error', 'At least one active Super Admin is required.');
                redirect('admin/users/' . $id . '/edit');
            }
        }

        if ($id === Auth::id() && !$isActive) {
            Session::flash('error', 'You cannot deactivate your own account.');
            redirect('admin/users/' . $id . '/edit');
        }

        Admin::updateUser($id, $name, $email, $roleId, $isActive);
        if ($newPassword !== '') {
            if (strlen($newPassword) < 8) {
                Session::flash('error', 'Password must be at least 8 characters.');
                redirect('admin/users/' . $id . '/edit');
            }
            Admin::setPassword($id, $newPassword);
        }

        Session::flash('success', 'Team member updated.');
        redirect('admin/users');
    }

    public function toggleActive(array $params): void
    {
        $this->authorize(Permission::USERS_MANAGE);
        $this->validateCsrf();
        $id = (int) ($params['id'] ?? 0);
        $user = Admin::find($id);
        if (!$user) {
            redirect('admin/users');
        }

        $newActive = !(bool) $user['is_active'];

        if ($user['role_slug'] === 'super_admin' && !$newActive && Admin::countActiveByRoleSlug('super_admin') <= 1) {
            Session::flash('error', 'You cannot deactivate the last Super Admin.');
            redirect('admin/users');
        }

        if ($id === Auth::id() && !$newActive) {
            Session::flash('error', 'You cannot deactivate your own account.');
            redirect('admin/users');
        }

        Admin::updateUser(
            $id,
            (string) $user['name'],
            (string) $user['email'],
            (int) $user['role_id'],
            $newActive
        );
        Session::flash('success', $newActive ? 'Account activated.' : 'Account deactivated.');
        redirect('admin/users');
    }

    private function formView(?array $user): void
    {
        $this->view('admin/users/form', [
            'title' => $user ? 'Edit team member' : 'Add team member',
            'pageDescription' => $user
                ? 'Update role and access for this account.'
                : 'Create a new login for a colleague.',
            'user' => $user,
            'roles' => Role::all(),
        ], 'layouts/admin');
    }

    private function roleSlugFromId(int $roleId): ?string
    {
        foreach (Role::all() as $role) {
            if ((int) $role['id'] === $roleId) {
                return (string) $role['slug'];
            }
        }
        return null;
    }
}
