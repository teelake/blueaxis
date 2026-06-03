<?php

declare(strict_types=1);

return [
    'name' => 'BlueAxis Logistics & Warehousing Ltd.',
    'url' => getenv('APP_URL') ?: 'http://localhost',
    'env' => getenv('APP_ENV') ?: 'local',
    'debug' => filter_var(getenv('APP_DEBUG') ?: 'true', FILTER_VALIDATE_BOOLEAN),
    'timezone' => 'America/Winnipeg',
    'locale' => 'en_CA',
    'session_name' => 'blueaxis_session',
    'upload_max_mb' => 5,
    'allowed_image_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
    'per_page_blog' => 9,
    'per_page_admin' => 20,
];
