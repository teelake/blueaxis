<?php

declare(strict_types=1);

namespace App\Services;

final class MailService
{
    public static function send(string $to, string $subject, string $htmlBody, ?string $replyTo = null): bool
    {
        $fromAddress = MailConfig::fromAddress();
        $fromName = MailConfig::fromName();
        $driver = MailConfig::driver();

        try {
            if ($driver === 'smtp' && MailConfig::host() !== '') {
                return self::sendSmtp($to, $subject, $htmlBody, $fromAddress, $fromName, $replyTo);
            }
            return self::sendMail($to, $subject, $htmlBody, $fromAddress, $fromName, $replyTo);
        } catch (\Throwable $e) {
            self::log('Mail failed: ' . $e->getMessage());
            return false;
        }
    }

    private static function sendMail(
        string $to,
        string $subject,
        string $htmlBody,
        string $fromAddress,
        string $fromName,
        ?string $replyTo
    ): bool {
        $boundary = 'ba_' . bin2hex(random_bytes(8));
        $plain = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], ["\n", "\n", "\n", "\n\n"], $htmlBody));

        $headers = [
            'MIME-Version: 1.0',
            'From: ' . self::formatAddress($fromAddress, $fromName),
            'Reply-To: ' . ($replyTo ?: $fromAddress),
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
            'X-Mailer: BlueAxis-CMS',
        ];

        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n{$plain}\r\n";
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n\r\n{$htmlBody}\r\n";
        $body .= "--{$boundary}--";

        return mail($to, self::encodeSubject($subject), $body, implode("\r\n", $headers));
    }

    private static function sendSmtp(
        string $to,
        string $subject,
        string $htmlBody,
        string $fromAddress,
        string $fromName,
        ?string $replyTo
    ): bool {
        $host = MailConfig::host();
        $port = MailConfig::port();
        $encryption = MailConfig::encryption();
        $remote = ($encryption === 'ssl' ? 'ssl://' : '') . $host;
        $socket = @stream_socket_client("{$remote}:{$port}", $errno, $errstr, 15);
        if (!$socket) {
            throw new \RuntimeException("SMTP connect failed: {$errstr} ({$errno})");
        }
        stream_set_timeout($socket, 15);

        self::expect($socket, [220]);
        self::cmd($socket, 'EHLO ' . gethostname());

        if ($encryption === 'tls') {
            self::expect($socket, [250]);
            self::cmd($socket, 'STARTTLS');
            self::expect($socket, [220]);
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new \RuntimeException('STARTTLS failed.');
            }
            self::cmd($socket, 'EHLO ' . gethostname());
        }

        $user = MailConfig::username();
        $pass = MailConfig::password();
        if ($user !== '' && $pass !== '') {
            self::expect($socket, [250]);
            self::cmd($socket, 'AUTH LOGIN');
            self::expect($socket, [334]);
            self::cmd($socket, base64_encode($user), false);
            self::expect($socket, [334]);
            self::cmd($socket, base64_encode($pass), false);
            self::expect($socket, [235]);
        }

        self::cmd($socket, 'MAIL FROM:<' . $fromAddress . '>');
        self::expect($socket, [250]);
        self::cmd($socket, 'RCPT TO:<' . $to . '>');
        self::expect($socket, [250, 251]);
        self::cmd($socket, 'DATA');
        self::expect($socket, [354]);

        $message = self::buildMessage($fromAddress, $fromName, $to, $subject, $htmlBody, $replyTo);
        fwrite($socket, $message . "\r\n.\r\n");
        self::expect($socket, [250]);
        self::cmd($socket, 'QUIT');
        fclose($socket);
        return true;
    }

    private static function buildMessage(
        string $fromAddress,
        string $fromName,
        string $to,
        string $subject,
        string $htmlBody,
        ?string $replyTo
    ): string {
        $plain = strip_tags(str_replace(['<br>', '<br/>', '</p>'], ["\n", "\n", "\n\n"], $htmlBody));
        $boundary = 'ba_' . bin2hex(random_bytes(8));
        $lines = [
            'Date: ' . date('r'),
            'From: ' . self::formatAddress($fromAddress, $fromName),
            'To: <' . $to . '>',
            'Subject: ' . self::encodeSubject($subject),
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
        ];
        if ($replyTo) {
            $lines[] = 'Reply-To: <' . $replyTo . '>';
        }
        $body = implode("\r\n", $lines) . "\r\n\r\n";
        $body .= "--{$boundary}\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n{$plain}\r\n";
        $body .= "--{$boundary}\r\nContent-Type: text/html; charset=UTF-8\r\n\r\n{$htmlBody}\r\n";
        $body .= "--{$boundary}--";
        return self::dotStuff($body);
    }

    private static function dotStuff(string $message): string
    {
        return preg_replace('/^\./m', '..', $message) ?? $message;
    }

    /** @param resource $socket */
    private static function cmd($socket, string $command, bool $appendCrlf = true): void
    {
        fwrite($socket, $command . ($appendCrlf ? "\r\n" : ''));
    }

    /** @param resource $socket */
    /** @param array<int> $codes */
    private static function expect($socket, array $codes): void
    {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        $code = (int) substr($response, 0, 3);
        if (!in_array($code, $codes, true)) {
            throw new \RuntimeException('SMTP error: ' . trim($response));
        }
    }

    private static function formatAddress(string $email, string $name): string
    {
        $name = str_replace(['"', "\r", "\n"], '', $name);
        return $name !== '' ? "\"{$name}\" <{$email}>" : "<{$email}>";
    }

    private static function encodeSubject(string $subject): string
    {
        return '=?UTF-8?B?' . base64_encode($subject) . '?=';
    }

    private static function log(string $message): void
    {
        $path = storage_path('logs/mail.log');
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($path, '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL, FILE_APPEND);
    }
}
