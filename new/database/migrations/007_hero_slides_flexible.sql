-- Flexible hero slides: image backgrounds or text-only with decorative pattern.

ALTER TABLE hero_slides
    MODIFY image_path VARCHAR(500) NULL,
    MODIFY subtitle VARCHAR(500) NULL,
    ADD COLUMN slide_type VARCHAR(20) NOT NULL DEFAULT 'image' AFTER id,
    ADD COLUMN eyebrow VARCHAR(120) NULL AFTER subtitle,
    ADD COLUMN cta_primary_label VARCHAR(80) NULL AFTER eyebrow,
    ADD COLUMN cta_primary_url VARCHAR(255) NULL AFTER cta_primary_label,
    ADD COLUMN cta_secondary_label VARCHAR(80) NULL AFTER cta_primary_url,
    ADD COLUMN cta_secondary_url VARCHAR(255) NULL AFTER cta_secondary_label;

UPDATE hero_slides
SET
    cta_primary_url = link_url,
    cta_primary_label = link_label,
    slide_type = IF(image_path IS NULL OR image_path = '', 'text', 'image')
WHERE cta_primary_url IS NULL AND (link_url IS NOT NULL OR link_label IS NOT NULL);

UPDATE hero_slides
SET slide_type = 'text'
WHERE image_path IS NULL OR image_path = '';
