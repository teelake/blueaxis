-- Run on existing databases: mysql -u root blueaxis < database/seeds/012_footer_content.sql

INSERT IGNORE INTO content_blocks (page_slug, section_key, block_key, content, content_type, sort_order) VALUES
('footer', 'brand', 'blurb', 'BlueAxis Logistics & Warehousing Ltd. — importation, warehousing, and distribution of African food products for B2B partners across Canada.', 'text', 1),
('footer', 'company_nav', 'title', 'Company', 'text', 1),
('footer', 'company_nav', 'links', '[{"label":"About","url":"/about"},{"label":"Services","url":"/services"},{"label":"Blog","url":"/blog"},{"label":"Request a Quote","url":"/quote"},{"label":"Contact","url":"/contact"}]', 'json', 2),
('footer', 'contact_col', 'title', 'Contact', 'text', 1),
('footer', 'bar', 'copyright', 'BlueAxis Logistics & Warehousing Ltd. All rights reserved.', 'text', 1),
('footer', 'bar', 'tagline', 'Manitoba · Canada-wide distribution', 'text', 2),
('footer', 'credit', 'show', '1', 'text', 1);
