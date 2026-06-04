<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;

final class MailConfig
{
    public const GROUP = 'mail';

    /** @var array<string, array{default: string, type: string}> */
    private const FIELDS = [
        'mail_driver' => ['default' => 'mail', 'type' => 'text'],
        'mail_host' => ['default' => '', 'type' => 'text'],
        'mail_port' => ['default' => '587', 'type' => 'text'],
        'mail_username' => ['default' => '', 'type' => 'text'],
        'mail_password' => ['default' => '', 'type' => 'text'],
        'mail_encryption' => ['default' => 'tls', 'type' => 'text'],
        'mail_from_address' => ['default' => 'noreply@blueaxis.com', 'type' => 'text'],
        'mail_from_name' => ['default' => 'BlueAxis Website', 'type' => 'text'],
        'mail_notify_to' => ['default' => 'info@blueaxis.com', 'type' => 'text'],
        'mail_notify_contact' => ['default' => '1', 'type' => 'boolean'],
        'mail_notify_quote' => ['default' => '1', 'type' => 'boolean'],
        'mail_notify_newsletter' => ['default' => '1', 'type' => 'boolean'],
        'mail_notify_comment' => ['default' => '1', 'type' => 'boolean'],
        'mail_confirm_contact' => ['default' => '1', 'type' => 'boolean'],
        'mail_confirm_quote' => ['default' => '1', 'type' => 'boolean'],
        'mail_confirm_newsletter' => ['default' => '1', 'type' => 'boolean'],
        'mail_confirm_comment' => ['default' => '1', 'type' => 'boolean'],
        'mail_reply_to_lead' => ['default' => '1', 'type' => 'boolean'],
    ];

    public static function driver(): string
    {
        return self::get('mail_driver');
    }

    public static function host(): string
    {
        return self::get('mail_host');
    }

    public static function port(): int
    {
        return (int) self::get('mail_port');
    }

    public static function username(): string
    {
        return self::get('mail_username');
    }

    public static function password(): string
    {
        return self::get('mail_password');
    }

    public static function encryption(): string
    {
        return self::get('mail_encryption');
    }

    public static function fromAddress(): string
    {
        return self::get('mail_from_address');
    }

    public static function fromName(): string
    {
        return self::get('mail_from_name');
    }

    public static function notifyTo(): string
    {
        return self::get('mail_notify_to');
    }

    public static function notifyContact(): bool
    {
        return self::bool('mail_notify_contact');
    }

    public static function notifyQuote(): bool
    {
        return self::bool('mail_notify_quote');
    }

    public static function replyToLead(): bool
    {
        return self::bool('mail_reply_to_lead');
    }

    public static function notifyNewsletter(): bool
    {
        return self::bool('mail_notify_newsletter');
    }

    public static function notifyComment(): bool
    {
        return self::bool('mail_notify_comment');
    }

    public static function confirmContact(): bool
    {
        return self::bool('mail_confirm_contact');
    }

    public static function confirmQuote(): bool
    {
        return self::bool('mail_confirm_quote');
    }

    public static function confirmNewsletter(): bool
    {
        return self::bool('mail_confirm_newsletter');
    }

    public static function confirmComment(): bool
    {
        return self::bool('mail_confirm_comment');
    }

    public static function bool(string $key): bool
    {
        return in_array(self::get($key), ['1', 'true', 'yes', 'on'], true);
    }

    public static function get(string $key): string
    {
        if (!isset(self::FIELDS[$key])) {
            return '';
        }

        $db = Setting::get($key);
        if ($db !== null && $db !== '') {
            return $db;
        }

        return self::envDefault($key);
    }

    /** @return array<string, string> */
    public static function forForm(): array
    {
        $out = [];
        foreach (array_keys(self::FIELDS) as $key) {
            $out[$key] = self::get($key);
        }
        return $out;
    }

    public static function hasStoredPassword(): bool
    {
        $db = Setting::get('mail_password');
        return $db !== null && $db !== '';
    }

    /** @param array<string, string> $input */
    public static function save(array $input): void
    {
        foreach (self::FIELDS as $key => $meta) {
            if ($key === 'mail_password') {
                $newPass = trim($input['mail_password'] ?? '');
                if ($newPass === '') {
                    continue;
                }
                Setting::set($key, $newPass, $meta['type'], self::GROUP);
                continue;
            }

            if ($meta['type'] === 'boolean') {
                $value = isset($input[$key]) ? '1' : '0';
            } else {
                $value = trim($input[$key] ?? '');
            }
            Setting::set($key, $value, $meta['type'], self::GROUP);
        }
    }

    private static function envDefault(string $key): string
    {
        $map = [
            'mail_driver' => 'mail.driver',
            'mail_host' => 'mail.host',
            'mail_port' => 'mail.port',
            'mail_username' => 'mail.username',
            'mail_password' => 'mail.password',
            'mail_encryption' => 'mail.encryption',
            'mail_from_address' => 'mail.from_address',
            'mail_from_name' => 'mail.from_name',
            'mail_notify_to' => 'mail.notify_to',
            'mail_notify_contact' => 'mail.notify_contact',
            'mail_notify_quote' => 'mail.notify_quote',
            'mail_notify_newsletter' => 'mail.notify_newsletter',
            'mail_notify_comment' => 'mail.notify_comment',
            'mail_confirm_contact' => 'mail.confirm_contact',
            'mail_confirm_quote' => 'mail.confirm_quote',
            'mail_confirm_newsletter' => 'mail.confirm_newsletter',
            'mail_confirm_comment' => 'mail.confirm_comment',
            'mail_reply_to_lead' => 'mail.reply_to_lead',
        ];

        $configKey = $map[$key] ?? null;
        if ($configKey) {
            $val = config($configKey);
            if ($val !== null && $val !== '') {
                if (is_bool($val)) {
                    return $val ? '1' : '0';
                }
                return (string) $val;
            }
        }

        return self::FIELDS[$key]['default'];
    }
}
