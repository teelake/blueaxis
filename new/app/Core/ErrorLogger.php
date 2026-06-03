<?php

declare(strict_types=1);

namespace App\Core;

final class ErrorLogger
{
    private static bool $registered = false;

    private static ?string $logFile = null;

    public static function register(): void
    {
        if (self::$registered) {
            return;
        }
        self::$registered = true;

        self::$logFile = rtrim(STORAGE_PATH, '/\\') . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'php-error.log';
        self::ensureLogDirectory();

        ini_set('log_errors', '1');
        ini_set('error_log', self::$logFile);

        $debug = filter_var(getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN);
        ini_set('display_errors', $debug ? '1' : '0');
        ini_set('display_startup_errors', $debug ? '1' : '0');
        error_reporting(E_ALL);

        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function logFile(): string
    {
        if (self::$logFile === null) {
            self::$logFile = rtrim(STORAGE_PATH, '/\\') . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'php-error.log';
        }
        return self::$logFile;
    }

    public static function log(string $level, string $message, ?\Throwable $throwable = null): void
    {
        self::ensureLogDirectory();
        $file = self::logFile();

        $entry = sprintf(
            "[%s] %s: %s",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message
        );

        if ($throwable !== null) {
            $entry .= PHP_EOL . $throwable->getFile() . ':' . $throwable->getLine();
            $entry .= PHP_EOL . $throwable->getTraceAsString();
        }

        $entry .= PHP_EOL . str_repeat('-', 72) . PHP_EOL;

        @file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
    }

    public static function logThrowable(\Throwable $throwable): void
    {
        self::log(
            'exception',
            $throwable->getMessage() . ' [' . $throwable::class . ']',
            $throwable
        );
    }

    public static function handleError(int $severity, string $message, string $file, int $line): bool
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }

        self::log('error', "{$message} in {$file} on line {$line}");

        return true;
    }

    public static function handleException(\Throwable $throwable): void
    {
        self::logThrowable($throwable);
        self::renderFatalResponse($throwable);
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error === null) {
            return;
        }

        $fatal = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
        if (!in_array($error['type'], $fatal, true)) {
            return;
        }

        self::log(
            'fatal',
            ($error['message'] ?? 'Unknown fatal error') . ' in ' . ($error['file'] ?? '?') . ':' . ($error['line'] ?? '?')
        );
        self::renderFatalResponse(null);
    }

    private static function renderFatalResponse(?\Throwable $throwable): void
    {
        if (headers_sent()) {
            return;
        }

        $debug = filter_var(getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN);

        http_response_code(500);

        if ($debug && $throwable !== null) {
            header('Content-Type: text/plain; charset=utf-8');
            echo "Application error (APP_DEBUG is on):\n\n";
            echo $throwable;
            return;
        }

        header('Content-Type: text/html; charset=utf-8');
        $logHint = self::logFile();
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Server Error</title></head><body style="font-family:system-ui,sans-serif;padding:2rem;max-width:36rem;margin:auto;color:#334155">';
        echo '<h1>Something went wrong</h1>';
        echo '<p>We are unable to complete your request. The technical team has been notified.</p>';
        echo '<p style="font-size:0.875rem;color:#64748b">Error details are recorded in <code>storage/logs/php-error.log</code> on the server.</p>';
        echo '</body></html>';
        exit;
    }

    private static function ensureLogDirectory(): void
    {
        $dir = dirname(self::logFile());
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
}
