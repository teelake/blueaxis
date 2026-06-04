<?php

declare(strict_types=1);

return [
    'driver' => env('MAIL_DRIVER', 'mail'),
    'host' => env('MAIL_HOST', ''),
    'port' => (int) env('MAIL_PORT', 587),
    'username' => env('MAIL_USERNAME', ''),
    'password' => env('MAIL_PASSWORD', ''),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@blueaxis.com'),
    'from_name' => env('MAIL_FROM_NAME', 'BlueAxis Website'),
    'notify_to' => env('MAIL_NOTIFY_TO', env('ADMIN_EMAIL', 'info@blueaxis.com')),
    'notify_contact' => filter_var(env('MAIL_NOTIFY_CONTACT', 'true'), FILTER_VALIDATE_BOOLEAN),
    'notify_quote' => filter_var(env('MAIL_NOTIFY_QUOTE', 'true'), FILTER_VALIDATE_BOOLEAN),
    'notify_newsletter' => filter_var(env('MAIL_NOTIFY_NEWSLETTER', 'true'), FILTER_VALIDATE_BOOLEAN),
    'notify_comment' => filter_var(env('MAIL_NOTIFY_COMMENT', 'true'), FILTER_VALIDATE_BOOLEAN),
    'confirm_contact' => filter_var(env('MAIL_CONFIRM_CONTACT', 'true'), FILTER_VALIDATE_BOOLEAN),
    'confirm_quote' => filter_var(env('MAIL_CONFIRM_QUOTE', 'true'), FILTER_VALIDATE_BOOLEAN),
    'confirm_newsletter' => filter_var(env('MAIL_CONFIRM_NEWSLETTER', 'true'), FILTER_VALIDATE_BOOLEAN),
    'confirm_comment' => filter_var(env('MAIL_CONFIRM_COMMENT', 'true'), FILTER_VALIDATE_BOOLEAN),
    'reply_to_lead' => filter_var(env('MAIL_REPLY_TO_LEAD', 'true'), FILTER_VALIDATE_BOOLEAN),
];
