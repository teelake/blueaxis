<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BlogCategory;
use App\Models\Product;
use App\Models\Role;
use App\Models\Service;

/** Shared server-side validation rules for public and admin forms. */
final class FormRules
{
    public static function contact(array $data): Validator
    {
        return (new Validator())
            ->required('name', $data['name'] ?? null)
            ->maxLength('name', $data['name'] ?? null, 120)
            ->maxLength('company', $data['company'] ?? null, 200)
            ->required('email', $data['email'] ?? null)
            ->email('email', $data['email'] ?? null)
            ->maxLength('email', $data['email'] ?? null, 255)
            ->phone('phone', $data['phone'] ?? null)
            ->required('message', $data['message'] ?? null)
            ->minLength('message', $data['message'] ?? null, 10, 'Please enter at least 10 characters.')
            ->maxLength('message', $data['message'] ?? null, 5000);
    }

    public static function quote(array $data): Validator
    {
        return (new Validator())
            ->required('name', $data['name'] ?? null)
            ->maxLength('name', $data['name'] ?? null, 120)
            ->required('company', $data['company'] ?? null, 'Company name is required for quote requests.')
            ->maxLength('company', $data['company'] ?? null, 200)
            ->required('email', $data['email'] ?? null)
            ->email('email', $data['email'] ?? null)
            ->maxLength('email', $data['email'] ?? null, 255)
            ->phone('phone', $data['phone'] ?? null)
            ->required('service_needed', $data['service_needed'] ?? null)
            ->maxLength('service_needed', $data['service_needed'] ?? null, 200)
            ->maxLength('message', $data['message'] ?? null, 5000);
    }

    public static function newsletter(string $email): Validator
    {
        return (new Validator())
            ->required('email', $email)
            ->email('email', $email)
            ->maxLength('email', $email, 255);
    }

    public static function blogComment(array $data): Validator
    {
        return (new Validator())
            ->required('author_name', $data['author_name'] ?? null)
            ->maxLength('author_name', $data['author_name'] ?? null, 120)
            ->required('email', $data['email'] ?? null)
            ->email('email', $data['email'] ?? null)
            ->maxLength('email', $data['email'] ?? null, 255)
            ->required('body', $data['body'] ?? null)
            ->minLength('body', $data['body'] ?? null, 3, 'Comment must be at least 3 characters.')
            ->maxLength('body', $data['body'] ?? null, 2000);
    }

    public static function adminLogin(string $email, string $password): Validator
    {
        return (new Validator())
            ->required('email', $email)
            ->email('email', $email)
            ->required('password', $password, 'Password is required.');
    }

    public static function adminUserCreate(array $data): Validator
    {
        $v = (new Validator())
            ->required('name', $data['name'] ?? null)
            ->maxLength('name', $data['name'] ?? null, 120)
            ->required('email', $data['email'] ?? null)
            ->email('email', $data['email'] ?? null)
            ->maxLength('email', $data['email'] ?? null, 255)
            ->required('password', $data['password'] ?? null, 'Password is required (8+ characters).')
            ->password('password', $data['password'] ?? null, 8);

        self::assertRoleId($v, (int) ($data['role_id'] ?? 0));

        return $v;
    }

    public static function adminUserUpdate(array $data, bool $changingPassword): Validator
    {
        $v = (new Validator())
            ->required('name', $data['name'] ?? null)
            ->maxLength('name', $data['name'] ?? null, 120)
            ->required('email', $data['email'] ?? null)
            ->email('email', $data['email'] ?? null)
            ->maxLength('email', $data['email'] ?? null, 255);

        if ($changingPassword) {
            $v->password('password', $data['password'] ?? null, 8);
        }

        self::assertRoleId($v, (int) ($data['role_id'] ?? 0));

        return $v;
    }

    public static function adminProfile(array $data): Validator
    {
        return (new Validator())
            ->required('name', $data['name'] ?? null)
            ->maxLength('name', $data['name'] ?? null, 120)
            ->required('email', $data['email'] ?? null)
            ->email('email', $data['email'] ?? null)
            ->maxLength('email', $data['email'] ?? null, 255);
    }

    public static function adminPasswordChange(string $current, string $new, string $confirm): Validator
    {
        return (new Validator())
            ->required('current_password', $current, 'Current password is required.')
            ->required('new_password', $new, 'New password is required.')
            ->password('new_password', $new, 8)
            ->confirmed('new_password', $new, $confirm, 'New passwords do not match.');
    }

    public static function product(array $data): Validator
    {
        $v = (new Validator())
            ->required('title', $data['title'] ?? null)
            ->maxLength('title', $data['title'] ?? null, 200)
            ->slug('slug', $data['slug'] ?? null)
            ->maxLength('slug', $data['slug'] ?? null, 220)
            ->maxLength('category', $data['category'] ?? null, 100)
            ->maxLength('sku', $data['sku'] ?? null, 80)
            ->maxLength('excerpt', $data['excerpt'] ?? null, 500)
            ->maxLength('price_unit', $data['price_unit'] ?? null, 60)
            ->maxLength('origin_region', $data['origin_region'] ?? null, 120)
            ->maxLength('pack_format', $data['pack_format'] ?? null, 120)
            ->maxLength('storage_notes', $data['storage_notes'] ?? null, 255)
            ->maxLength('meta_title', $data['meta_title'] ?? null, 70)
            ->maxLength('meta_description', $data['meta_description'] ?? null, 320)
            ->integer('sort_order', $data['sort_order'] ?? 0, 0, 9999);

        if (isset($data['price']) && $data['price'] !== null) {
            $v->min('price', $data['price'], 0)->max('price', $data['price'], 9999999.99);
        }

        return $v;
    }

    public static function service(array $data): Validator
    {
        return (new Validator())
            ->required('title', $data['title'] ?? null)
            ->maxLength('title', $data['title'] ?? null, 200)
            ->slug('slug', $data['slug'] ?? null)
            ->maxLength('slug', $data['slug'] ?? null, 220)
            ->maxLength('excerpt', $data['excerpt'] ?? null, 500)
            ->maxLength('icon', $data['icon'] ?? null, 80)
            ->maxLength('meta_title', $data['meta_title'] ?? null, 70)
            ->maxLength('meta_description', $data['meta_description'] ?? null, 320)
            ->integer('sort_order', $data['sort_order'] ?? 0, 0, 9999);
    }

    public static function blogPost(array $data): Validator
    {
        $v = (new Validator())
            ->required('title', $data['title'] ?? null)
            ->maxLength('title', $data['title'] ?? null, 200)
            ->slug('slug', $data['slug'] ?? null)
            ->maxLength('slug', $data['slug'] ?? null, 220)
            ->maxLength('excerpt', $data['excerpt'] ?? null, 500)
            ->maxLength('meta_title', $data['meta_title'] ?? null, 70)
            ->maxLength('meta_description', $data['meta_description'] ?? null, 320)
            ->in('status', $data['status'] ?? 'draft', ['draft', 'published']);

        $categoryId = $data['category_id'] ?? null;
        if ($categoryId !== null && $categoryId !== '' && (int) $categoryId > 0) {
            $exists = BlogCategory::find((int) $categoryId);
            $v->custom('category_id', $exists === null, 'Please select a valid category.');
        }

        return $v;
    }

    public static function quoteCartAdd(string $slug, int $qty): Validator
    {
        $v = new Validator();
        $v->custom('product_slug', $slug === '', 'Product is required.');
        if ($slug !== '' && Product::findPublishedBySlug($slug) === null) {
            $v->custom('product_slug', true, 'This product is not available.');
        }
        $v->integer('quantity', $qty, 1, 999);
        return $v;
    }

    public static function quoteStatus(string $status, ?string $notes): Validator
    {
        return (new Validator())
            ->in('status', $status, ['new', 'in_review', 'contacted', 'closed'])
            ->maxLength('admin_notes', $notes, 5000);
    }

    public static function siteBranding(string $alt): Validator
    {
        return (new Validator())
            ->required('site_logo_alt', $alt, 'Logo alt text is required for accessibility.')
            ->maxLength('site_logo_alt', $alt, 200);
    }

    public static function socialUrl(string $field, string $url, string $label): Validator
    {
        $v = new Validator();
        if ($url !== '') {
            $v->url($field, $url, true, "Please enter a valid URL for {$label}.");
        }
        return $v;
    }

    public static function emailSettings(array $data): Validator
    {
        $v = (new Validator())
            ->required('mail_notify_to', $data['mail_notify_to'] ?? null, 'Notification email is required.')
            ->email('mail_notify_to', $data['mail_notify_to'] ?? null)
            ->required('mail_from_address', $data['mail_from_address'] ?? null, 'From email is required.')
            ->email('mail_from_address', $data['mail_from_address'] ?? null)
            ->maxLength('mail_from_name', $data['mail_from_name'] ?? null, 120)
            ->in('mail_driver', $data['mail_driver'] ?? 'mail', ['mail', 'smtp'])
            ->integer('mail_port', $data['mail_port'] ?? 587, 1, 65535);

        if (($data['mail_driver'] ?? '') === 'smtp') {
            $v->required('mail_host', $data['mail_host'] ?? null, 'SMTP host is required when using SMTP.');
            $v->maxLength('mail_host', $data['mail_host'] ?? null, 255);
        }

        return $v;
    }

    public static function emailTest(string $email): Validator
    {
        return (new Validator())
            ->email('test_email', $email, 'Please enter a valid test email address.');
    }

    private static function assertRoleId(Validator $v, int $roleId): void
    {
        $valid = false;
        foreach (Role::all() as $role) {
            if ((int) $role['id'] === $roleId) {
                $valid = true;
                break;
            }
        }
        $v->custom('role_id', !$valid, 'Please select a valid role.');
    }

    /** @return list<string> */
    public static function publishedServiceTitles(): array
    {
        return array_map(
            static fn (array $s): string => (string) $s['title'],
            Service::published()
        );
    }

    /** @param array{company_address?: string, company_email?: string, company_phone?: string} $data */
    public static function footerContact(array $data): Validator
    {
        return (new Validator())
            ->required('company_address', $data['company_address'] ?? null)
            ->maxLength('company_address', $data['company_address'] ?? null, 500)
            ->required('company_email', $data['company_email'] ?? null)
            ->email('company_email', $data['company_email'] ?? null)
            ->maxLength('company_email', $data['company_email'] ?? null, 255)
            ->phone('company_phone', $data['company_phone'] ?? null);
    }
}
