-- Run on existing databases: mysql -u root blueaxis < database/seeds/009_mail_form_notifications.sql

INSERT IGNORE INTO settings (`key`, value, type, group_name) VALUES
('mail_notify_newsletter', '1', 'boolean', 'mail'),
('mail_notify_comment', '1', 'boolean', 'mail'),
('mail_confirm_contact', '1', 'boolean', 'mail'),
('mail_confirm_quote', '1', 'boolean', 'mail'),
('mail_confirm_newsletter', '1', 'boolean', 'mail'),
('mail_confirm_comment', '1', 'boolean', 'mail');
