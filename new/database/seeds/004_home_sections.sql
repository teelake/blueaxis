-- Testimonials + newsletter blocks for databases seeded before these sections existed.
INSERT INTO content_blocks (page_slug, section_key, block_key, content, content_type, sort_order) VALUES
('home', 'testimonials', 'eyebrow', 'Partner feedback', 'text', 1),
('home', 'testimonials', 'title', 'Trusted by wholesale partners', 'text', 2),
('home', 'testimonials', 'lead', 'B2B teams across Manitoba and Canada rely on disciplined import, storage, and distribution.', 'text', 3),
('home', 'testimonials', 'items', '[{"quote":"BlueAxis gave us predictable inbound timing and clear inventory visibility—exactly what our wholesale program needed.","name":"Sarah M.","role":"Procurement Director","company":"Regional Grocery Group"},{"quote":"From import coordination to Manitoba fulfillment, their team operates with the discipline we expect from a long-term logistics partner.","name":"James O.","role":"Operations Manager","company":"African Foods Wholesale"},{"quote":"Transparent communication at every stage. We scaled storage and distribution without disrupting our retail network.","name":"Priya K.","role":"Supply Chain Lead","company":"National Food Distributor"}]', 'json', 4),
('home', 'newsletter', 'eyebrow', 'Stay informed', 'text', 1),
('home', 'newsletter', 'title', 'Logistics insights for your inbox', 'text', 2),
('home', 'newsletter', 'lead', 'Industry updates, supply chain perspectives, and company news for B2B partners.', 'text', 3)
ON DUPLICATE KEY UPDATE content = VALUES(content), content_type = VALUES(content_type);
