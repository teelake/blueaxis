<?php

declare(strict_types=1);

namespace App\Services;

final class LeadNotificationService
{
    public static function contactSubmitted(array $lead, int $id): void
    {
        if (!MailConfig::notifyContact()) {
            return;
        }

        $to = MailConfig::notifyTo();
        $replyTo = MailConfig::replyToLead() ? ($lead['email'] ?? null) : null;
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
            ]) . '<p style="margin-top:20px"><a href="' . e($adminUrl) . '" style="color:#102A56;font-weight:600">View in admin →</a></p>'
        );

        MailService::send($to, '[BlueAxis] New contact from ' . ($lead['name'] ?? 'Website'), $html, $replyTo);
    }

    public static function quoteSubmitted(array $lead, int $id): void
    {
        if (!MailConfig::notifyQuote()) {
            return;
        }

        $to = MailConfig::notifyTo();
        $replyTo = MailConfig::replyToLead() ? ($lead['email'] ?? null) : null;
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

        $html = self::wrap(
            'New quote request',
            self::rows($rows) . '<p style="margin-top:20px"><a href="' . e($adminUrl) . '" style="color:#102A56;font-weight:600">View in admin →</a></p>'
        );

        $subjectService = $lead['service_needed'] ?? 'General';
        if ($productLines !== '') {
            $subjectService = 'Products + ' . $subjectService;
        }

        MailService::send(
            $to,
            '[BlueAxis] Quote request: ' . $subjectService,
            $html,
            $replyTo
        );
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
            . '<p style="margin-top:28px;font-size:12px;color:#94a3b8">Automated notification from your website.</p>'
            . '</div></body></html>';
    }
}
