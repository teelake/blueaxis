<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\ErrorLogger;
use App\Core\Session;

try {
    Session::start();

    /** @var \App\Core\Router $router */
    $router = require BASE_PATH . '/routes/web.php';

    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $uri = $_SERVER['REQUEST_URI'] ?? '/';

    $router->dispatch($method, $uri);
} catch (\Throwable $e) {
    ErrorLogger::logThrowable($e);

    if (filter_var(getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN)) {
        throw $e;
    }

    http_response_code(500);
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=utf-8');
    }
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Server Error</title></head><body style="font-family:system-ui,sans-serif;padding:2rem">';
    echo '<h1>Something went wrong</h1><p>Please try again later. Errors are logged to <code>storage/logs/php-error.log</code>.</p></body></html>';
}
