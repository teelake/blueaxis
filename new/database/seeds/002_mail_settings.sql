-- Run once on existing databases that were seeded before mail settings existed:
-- mysql -u root blueaxis < database/seeds/002_mail_settings.sql

INSERT IGNORE INTO settings (`key`, value, type, group_name) VALUES
('mail_driver', 'mail', 'text', 'mail'),
('mail_host', '', 'text', 'mail'),
('mail_port', '587', 'text', 'mail'),
('mail_username', '', 'text', 'mail'),
('mail_password', '', 'text', 'mail'),
('mail_encryption', 'tls', 'text', 'mail'),
('mail_from_address', 'noreply@blueaxis.com', 'text', 'mail'),
('mail_from_name', 'BlueAxis Website', 'text', 'mail'),
('mail_notify_to', 'info@blueaxis.com', 'text', 'mail'),
('mail_notify_contact', '1', 'boolean', 'mail'),
('mail_notify_quote', '1', 'boolean', 'mail'),
('mail_notify_newsletter', '1', 'boolean', 'mail'),
('mail_notify_comment', '1', 'boolean', 'mail'),
('mail_confirm_contact', '1', 'boolean', 'mail'),
('mail_confirm_quote', '1', 'boolean', 'mail'),
('mail_confirm_newsletter', '1', 'boolean', 'mail'),
('mail_confirm_comment', '1', 'boolean', 'mail'),
('mail_reply_to_lead', '1', 'boolean', 'mail');
