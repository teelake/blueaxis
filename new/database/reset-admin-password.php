<?php

declare(strict_types=1);

/**
 * @deprecated Use database/set-admin-password.php
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

fwrite(STDERR, 'Use: php database/set-admin-password.php <email> <password>' . PHP_EOL);
require __DIR__ . '/set-admin-password.php';
