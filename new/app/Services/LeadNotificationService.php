<?php

declare(strict_types=1);

namespace App\Services;

final class LeadNotificationService
{
    public static function contactSubmitted(array $lead, int $id): void
    {
        self::adminContact($lead, $id);
        self::visitorContact($lead, $id);
    }

    public static function quoteSubmitted(array $lead, int $id): void
    {
        self::adminQuote($lead, $id);
        self::visitorQuote($lead, $id);
    }

    public static function newsletterSubscribed(string $email, string $result): void
    {
        if ($result === 'created' || $result === 'reactivated') {
            self::adminNewsletter($email, $result);
            self::visitorNewsletter($email, $result);
        }
    }

    /** @param array<string, mixed> $comment */
    public static function blogCommentSubmitted(array $comment, array $post, int $commentId): void
    {
        self::adminBlogComment($comment, $post, $commentId);
        self::visitorBlogComment($comment, $post);
    }

    private static function adminContact(array $lead, int $id): void
    {
        if (!MailConfig::notifyContact()) {
            return;
        }

        $adminUrl = url('admin/contacts/' . $id);
        $html = self::wrap(
            'New contact inquiry',
            self::rows([
                'Reference' => '#' . $id,
                'Name' => $lead['name'] ?? '',
                'Company' => $lead['company'] ?? '—',
                'Email' => $lead['email'] ?? '',
                'Phone' => $lead['phone'] ?? '—',
                'Message' => nl2br(e($lead['message'] ?? '')),
            ]) . self::adminLink($adminUrl)
        );

        self::sendAdmin(
            '[BlueAxis] New contact from ' . ($lead['name'] ?? 'Website'),
            $html,
            MailConfig::replyToLead() ? ($lead['email'] ?? null) : null
        );
    }

    private static function visitorContact(array $lead, int $id): void
    {
        if (!MailConfig::confirmContact()) {
            return;
        }

        $email = trim((string) ($lead['email'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $name = trim((string) ($lead['name'] ?? ''));
        $greeting = $name !== '' ? 'Hi ' . e($name) . ',' : 'Hello,';

        $html = self::wrap(
            'We received your message',
            '<p style="color:#334155;line-height:1.6">' . $greeting . '</p>'
            . '<p style="color:#334155;line-height:1.6">Thank you for contacting BlueAxis Logistics & Warehousing. '
            . 'Your inquiry has been received and our team will respond as soon as possible, usually within one business day.</p>'
            . self::rows([
                'Reference' => '#' . $id,
                'Submitted' => date('M j, Y g:i A'),
            ])
            . '<p style="color:#334155;line-height:1.6;margin-top:20px">If your request is urgent, you can reply to this email or call us directly.</p>'
            . self::visitorFooter()
        );

        self::sendVisitor($email, 'We received your message — BlueAxis', $html);
    }

    private static function adminQuote(array $lead, int $id): void
    {
        if (!MailConfig::notifyQuote()) {
            return;
        }

        $adminUrl = url('admin/quotes/' . $id);
        $productLines = QuoteCartService::formatLines($lead['products_json'] ?? null);
        $rows = [
            'Reference' => '#' . $id,
            'Name' => $lead['name'] ?? '',
            'Company' => $lead['company'] ?? '—',
            'Email' => $lead['email'] ?? '',
            'Phone' => $lead['phone'] ?? '—',
            'Service' => $lead['service_needed'] ?? '',
        ];
        if ($productLines !== '') {
            $rows['Products'] = nl2br(e($productLines));
        }
        $rows['Details'] = nl2br(e($lead['message'] ?? '—'));

        $subjectService = $lead['service_needed'] ?? 'General';
        if ($productLines !== '') {
            $subjectService = 'Products + ' . $subjectService;
        }

        $html = self::wrap('New quote request', self::rows($rows) . self::adminLink($adminUrl));

        self::sendAdmin(
            '[BlueAxis] Quote request: ' . $subjectService,
            $html,
            MailConfig::replyToLead() ? ($lead['email'] ?? null) : null
        );
    }

    private static function visitorQuote(array $lead, int $id): void
    {
        if (!MailConfig::confirmQuote()) {
            return;
        }

        $email = trim((string) ($lead['email'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $name = trim((string) ($lead['name'] ?? ''));
        $greeting = $name !== '' ? 'Hi ' . e($name) . ',' : 'Hello,';
        $productLines = QuoteCartService::formatLines($lead['products_json'] ?? null);

        $summary = self::rows([
            'Reference' => '#' . $id,
            'Company' => $lead['company'] ?? '—',
            'Service' => $lead['service_needed'] ?? '—',
            'Submitted' => date('M j, Y g:i A'),
        ]);
        if ($productLines !== '') {
            $summary .= '<p style="margin-top:16px;font-weight:600;color:#102A56">Products on your quote list:</p>'
                . '<p style="color:#334155;white-space:pre-line">' . e($productLines) . '</p>';
        }

        $html = self::wrap(
            'Quote request received',
            '<p style="color:#334155;line-height:1.6">' . $greeting . '</p>'
            . '<p style="color:#334155;line-height:1.6">Thank you for your wholesale quote request. '
            . 'Our logistics team is reviewing your requirements and will follow up with a tailored proposal.</p>'
            . $summary
            . '<p style="color:#334155;line-height:1.6;margin-top:20px">Please keep this email for your records. Reply if you need to add details.</p>'
            . self::visitorFooter()
        );

        self::sendVisitor($email, 'Your quote request was received — BlueAxis', $html);
    }

    private static function adminNewsletter(string $email, string $result): void
    {
        if (!MailConfig::notifyNewsletter()) {
            return;
        }

        $html = self::wrap(
            'New newsletter subscriber',
            self::rows([
                'Email' => $email,
                'Status' => $result === 'reactivated' ? 'Re-subscribed' : 'New subscription',
                'Time' => date('M j, Y g:i A'),
            ])
        );

        self::sendAdmin('[BlueAxis] Newsletter signup: ' . $email, $html, $email);
    }

    private static function visitorNewsletter(string $email, string $result): void
    {
        if (!MailConfig::confirmNewsletter()) {
            return;
        }

        $intro = $result === 'reactivated'
            ? 'Welcome back. You are subscribed again to BlueAxis logistics insights and updates.'
            : 'Thank you for subscribing. You will receive B2B logistics insights, industry updates, and company news from BlueAxis.';

        $html = self::wrap(
            'Subscription confirmed',
            '<p style="color:#334155;line-height:1.6">Hello,</p>'
            . '<p style="color:#334155;line-height:1.6">' . e($intro) . '</p>'
            . '<p style="color:#334155;line-height:1.6">We respect your inbox — messages are intended for wholesale and logistics partners, not consumer marketing.</p>'
            . self::visitorFooter()
        );

        self::sendVisitor($email, 'You are subscribed — BlueAxis', $html);
    }

    /** @param array<string, mixed> $comment */
    private static function adminBlogComment(array $comment, array $post, int $commentId): void
    {
        if (!MailConfig::notifyComment()) {
            return;
        }

        $adminUrl = url('admin/blog/' . (int) $post['id'] . '/edit#comments');
        $html = self::wrap(
            'New blog comment (pending)',
            self::rows([
                'Article' => $post['title'] ?? '',
                'Author' => $comment['author_name'] ?? '',
                'Email' => $comment['email'] ?? '',
                'Comment' => nl2br(e($comment['body'] ?? '')),
            ]) . self::adminLink($adminUrl)
        );

        self::sendAdmin(
            '[BlueAxis] Blog comment on: ' . ($post['title'] ?? 'Article'),
            $html,
            MailConfig::replyToLead() ? ($comment['email'] ?? null) : null
        );
    }

    /** @param array<string, mixed> $comment */
    private static function visitorBlogComment(array $comment, array $post): void
    {
        if (!MailConfig::confirmComment()) {
            return;
        }

        $email = trim((string) ($comment['email'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $name = trim((string) ($comment['author_name'] ?? ''));
        $greeting = $name !== '' ? 'Hi ' . e($name) . ',' : 'Hello,';

        $html = self::wrap(
            'Comment received',
            '<p style="color:#334155;line-height:1.6">' . $greeting . '</p>'
            . '<p style="color:#334155;line-height:1.6">Thank you for commenting on <strong>' . e($post['title'] ?? 'our article') . '</strong>. '
            . 'Your comment has been received and is awaiting moderation. It will appear on the site once approved by our team.</p>'
            . self::visitorFooter()
        );

        self::sendVisitor($email, 'Your comment was received — BlueAxis', $html);
    }

    private static function sendAdmin(string $subject, string $html, ?string $replyTo = null): void
    {
        $to = MailConfig::notifyTo();
        if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            self::log('Admin notification skipped: invalid mail_notify_to.');
            return;
        }
        if (!MailService::send($to, $subject, $html, $replyTo)) {
            self::log('Admin notification failed: ' . $subject);
        }
    }

    private static function sendVisitor(string $to, string $subject, string $html): void
    {
        if (!MailService::send($to, $subject, $html, MailConfig::notifyTo())) {
            self::log('Visitor confirmation failed: ' . $subject . ' to ' . $to);
        }
    }

    private static function adminLink(string $url): string
    {
        return '<p style="margin-top:20px"><a href="' . e($url) . '" style="color:#102A56;font-weight:600">View in admin →</a></p>';
    }

    private static function visitorFooter(): string
    {
        $site = url('/');
        return '<p style="margin-top:24px;padding-top:16px;border-top:1px solid #e2e8f0;font-size:13px;color:#64748b">'
            . 'BlueAxis Logistics & Warehousing Ltd.<br>'
            . '<a href="' . e($site) . '" style="color:#102A56">' . e($site) . '</a></p>';
    }

    /** @param array<string, string> $rows */
    private static function rows(array $rows): string
    {
        $out = '<table cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse">';
        foreach ($rows as $label => $value) {
            $out .= '<tr><td style="padding:8px 12px 8px 0;font-weight:600;color:#102A56;vertical-align:top;width:120px">'
                . e($label) . '</td><td style="padding:8px 0;color:#334155">' . $value . '</td></tr>';
        }
        return $out . '</table>';
    }

    private static function wrap(string $title, string $body): string
    {
        return '<!DOCTYPE html><html><body style="font-family:DM Sans,Arial,sans-serif;background:#f8fafc;padding:24px">'
            . '<div style="max-width:560px;margin:0 auto;background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:28px">'
            . '<p style="margin:0 0 8px;font-size:12px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:#C59E5F">BlueAxis</p>'
            . '<h1 style="margin:0 0 20px;font-size:20px;color:#102A56">' . e($title) . '</h1>'
            . $body
            . '</div></body></html>';
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
