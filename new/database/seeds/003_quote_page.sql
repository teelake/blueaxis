-- For databases already seeded without the quote page:
INSERT IGNORE INTO pages (slug, title, meta_title, meta_description) VALUES
('quote', 'Request a Quote', 'Request a Quote | BlueAxis Logistics', 'Request a B2B quote for importation, warehousing, and distribution services.');

UPDATE content_blocks SET content = '/quote' WHERE page_slug = 'home' AND section_key = 'hero' AND block_key = 'cta_primary_url';
