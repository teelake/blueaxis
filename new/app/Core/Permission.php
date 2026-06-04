<?php

declare(strict_types=1);

namespace App\Core;

/** Role-based permissions for the admin area. */
final class Permission
{
    public const DASHBOARD = 'dashboard.view';
    public const CONTENT = 'content.edit';
    public const SERVICES = 'services.manage';
    public const BLOG = 'blog.manage';
    public const MEDIA = 'media.manage';
    public const LEADS_CONTACTS = 'leads.contacts';
    public const LEADS_QUOTES = 'leads.quotes';
    public const LEADS_EXPORT = 'leads.export';
    public const SETTINGS_EMAIL = 'settings.email';
    public const USERS_MANAGE = 'users.manage';
    public const PRODUCTS = 'products.manage';

    /** @var array<string, list<string>> */
    private const ROLE_PERMISSIONS = [
        'super_admin' => [
            self::DASHBOARD,
            self::CONTENT,
            self::SERVICES,
            self::BLOG,
            self::MEDIA,
            self::PRODUCTS,
            self::LEADS_CONTACTS,
            self::LEADS_QUOTES,
            self::LEADS_EXPORT,
            self::SETTINGS_EMAIL,
            self::USERS_MANAGE,
        ],
        'content_manager' => [
            self::DASHBOARD,
            self::CONTENT,
            self::SERVICES,
            self::BLOG,
            self::MEDIA,
            self::PRODUCTS,
            self::LEADS_CONTACTS,
            self::LEADS_QUOTES,
            self::LEADS_EXPORT,
        ],
    ];

    /** @var array<string, string> */
    public const LABELS = [
        self::DASHBOARD => 'View dashboard',
        self::CONTENT => 'Edit pages (home & about)',
        self::SERVICES => 'Manage services',
        self::BLOG => 'Manage blog & comments',
        self::MEDIA => 'Media library',
        self::LEADS_CONTACTS => 'View contact inquiries',
        self::LEADS_QUOTES => 'View quote requests',
        self::LEADS_EXPORT => 'Export leads (CSV)',
        self::SETTINGS_EMAIL => 'Email settings',
        self::USERS_MANAGE => 'Manage admin users',
        self::PRODUCTS => 'Manage product catalog',
    ];

    /** @return list<string> */
    public static function forRole(string $roleSlug): array
    {
        return self::ROLE_PERMISSIONS[$roleSlug] ?? [];
    }

    public static function roleCan(string $roleSlug, string $permission): bool
    {
        if ($roleSlug === 'super_admin') {
            return true;
        }
        return in_array($permission, self::forRole($roleSlug), true);
    }

    /** @return array<string, list<string>> */
    public static function matrix(): array
    {
        return self::ROLE_PERMISSIONS;
    }
}
