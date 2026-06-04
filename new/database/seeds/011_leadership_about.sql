-- Run on existing databases: mysql -u root blueaxis < database/seeds/011_leadership_about.sql

INSERT IGNORE INTO content_blocks (page_slug, section_key, block_key, content, type, sort_order) VALUES
('about', 'leadership', 'title', 'Leadership', 'text', 1),
('about', 'leadership', 'lead', 'Meet the team guiding BlueAxis Logistics & Warehousing.', 'text', 2),
('about', 'leadership', 'members', '[]', 'json', 3);
