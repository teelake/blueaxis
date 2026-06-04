-- Run on existing databases: mysql -u root blueaxis < database/seeds/010_social_settings.sql

INSERT IGNORE INTO settings (`key`, value, type, group_name) VALUES
('social_instagram', '', 'text', 'social'),
('social_x', '', 'text', 'social'),
('social_youtube', '', 'text', 'social');

-- Clear placeholder hashes so empty fields hide icons on the site
UPDATE settings SET value = '' WHERE `key` IN ('social_linkedin', 'social_facebook') AND value = '#';
