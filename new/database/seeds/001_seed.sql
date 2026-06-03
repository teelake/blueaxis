INSERT INTO roles (name, slug, description) VALUES
('Super Admin', 'super_admin', 'Full system access'),
('Content Manager', 'content_manager', 'Manage content and leads');

INSERT INTO admins (role_id, name, email, password, is_active) VALUES
(1, 'System Administrator', 'admin@blueaxis.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

INSERT INTO blog_categories (name, slug, description) VALUES
('Supply Chain', 'supply-chain', 'Logistics and supply chain insights'),
('Industry News', 'industry-news', 'Market and industry updates'),
('Company Updates', 'company-updates', 'BlueAxis announcements');

INSERT INTO pages (slug, title, meta_title, meta_description) VALUES
('home', 'Home', 'BlueAxis Logistics & Warehousing | Import, Storage & Distribution', 'Canadian logistics partner for African food importation, warehousing, and wholesale distribution across Manitoba and Canada.'),
('about', 'About Us', 'About BlueAxis | Logistics & Warehousing Canada', 'Learn about BlueAxis mission, vision, and values serving grocery, retail, and wholesale partners.'),
('services', 'Services', 'Logistics Services | Import, Warehousing & Distribution', 'Importation, warehousing, and fulfillment services for B2B food distribution.'),
('blog', 'Blog', 'Insights & News | BlueAxis Logistics', 'Expert articles on logistics, warehousing, and African food supply chains.'),
('contact', 'Contact', 'Contact BlueAxis | Request a Quote', 'Contact BlueAxis for wholesale distribution, warehousing, and import partnership inquiries.');

INSERT INTO services (title, slug, excerpt, description, benefits, sort_order, is_published) VALUES
('Importation & Sourcing', 'importation-sourcing',
 'Strategic sourcing and importation of quality African food products for the Canadian market.',
 '<p>BlueAxis coordinates end-to-end importation of African food products, managing supplier relationships, compliance, and inbound logistics so your inventory arrives reliably and ready for market.</p>',
 '["Verified supplier networks across Africa","Regulatory and documentation support","Quality-focused inbound coordination","Scalable import programs for wholesalers"]',
 1, 1),
('Warehousing & Storage', 'warehousing-storage',
 'Secure, organized warehousing with inventory visibility for wholesale and retail partners.',
 '<p>Our warehousing solutions provide climate-appropriate storage, systematic inventory handling, and operational discipline designed for food-grade distribution requirements.</p>',
 '["Dedicated storage capacity in Manitoba","Inventory organization and rotation support","Flexible storage terms for partners","Operational reporting and coordination"]',
 2, 1),
('Distribution & Fulfillment', 'distribution-fulfillment',
 'Reliable distribution and fulfillment across Manitoba and Canada-wide networks.',
 '<p>From pick-and-pack to route coordination, BlueAxis delivers dependable fulfillment that keeps your customers stocked and your supply chain moving.</p>',
 '["Regional and national distribution capability","Wholesale fulfillment workflows","Partner-focused delivery coordination","Supply chain visibility at every stage"]',
 3, 1);

INSERT INTO settings (`key`, value, type, group_name) VALUES
('company_phone', '+1 (204) 000-0000', 'text', 'contact'),
('company_email', 'info@blueaxis.com', 'text', 'contact'),
('company_address', 'Winnipeg, Manitoba, Canada', 'text', 'contact'),
('social_linkedin', '#', 'text', 'social'),
('social_facebook', '#', 'text', 'social');

INSERT INTO content_blocks (page_slug, section_key, block_key, content, content_type, sort_order) VALUES
('home', 'hero', 'eyebrow', 'Canadian Logistics Partner', 'text', 1),
('home', 'hero', 'title', 'Connecting African Food Supply to Canadian Markets', 'text', 2),
('home', 'hero', 'lead', 'BlueAxis Logistics & Warehousing Ltd. delivers importation, warehousing, and distribution solutions for grocery retailers, wholesalers, and food service partners across Manitoba and Canada.', 'text', 3),
('home', 'hero', 'cta_primary_label', 'Request a Quote', 'text', 4),
('home', 'hero', 'cta_primary_url', '/contact#quote', 'text', 5),
('home', 'hero', 'cta_secondary_label', 'Our Services', 'text', 6),
('home', 'hero', 'cta_secondary_url', '/services', 'text', 7),
('home', 'about', 'title', 'Your Partner in Food Logistics', 'text', 1),
('home', 'about', 'body', '<p>Founded to bridge quality African food products with Canadian wholesale demand, BlueAxis combines disciplined warehousing, import coordination, and B2B distribution expertise.</p><p>We serve partners who need reliability, transparency, and scale—not consumer retail.</p>', 'html', 2),
('home', 'cta', 'title', 'Ready to strengthen your supply chain?', 'text', 1),
('home', 'cta', 'body', 'Speak with our team about import programs, storage capacity, or distribution partnerships.', 'text', 2),
('home', 'cta', 'button_label', 'Get in Touch', 'text', 3),
('home', 'cta', 'button_url', '/contact', 'text', 4),
('home', 'trust', 'items', '[{"stat":"B2B","label":"Wholesale focus"},{"stat":"MB + CA","label":"Regional & national reach"},{"stat":"3-in-1","label":"Import · Store · Distribute"},{"stat":"Food-grade","label":"Disciplined operations"}]', 'json', 1),
('about', 'overview', 'title', 'Company Overview', 'text', 1),
('about', 'overview', 'body', '<p>BlueAxis Logistics & Warehousing Ltd. is a Manitoba-based company specializing in the importation, storage, and distribution of African food products for B2B partners across Canada.</p>', 'html', 2),
('about', 'mission', 'title', 'Mission', 'text', 1),
('about', 'mission', 'body', '<p>To deliver dependable logistics infrastructure that connects African food producers with Canadian wholesale markets through integrity, operational excellence, and long-term partnerships.</p>', 'html', 2),
('about', 'vision', 'title', 'Vision', 'text', 1),
('about', 'vision', 'body', '<p>To become Canada''s most trusted B2B logistics partner for African food supply chains, with scalable operations across North America.</p>', 'html', 2),
('about', 'values', 'content', '[{"title":"Integrity","description":"Transparent operations and honest partner relationships."},{"title":"Reliability","description":"Consistent fulfillment you can plan your business around."},{"title":"Excellence","description":"Disciplined processes across import, storage, and distribution."},{"title":"Partnership","description":"We grow when our wholesale and retail partners grow."}]', 'json', 3);

INSERT INTO blog_posts (category_id, author_id, title, slug, excerpt, content, status, is_featured, published_at, meta_title, meta_description) VALUES
(1, 1, 'Building Resilient Food Supply Chains in Manitoba', 'resilient-food-supply-chains-manitoba',
 'How wholesale partners can reduce risk with structured warehousing and distribution partners.',
 '<p>Wholesale food distribution in Manitoba demands predictable inventory flow and partner accountability. Structured warehousing and coordinated distribution reduce stockouts and strengthen retailer relationships.</p><p>BlueAxis supports B2B partners with import coordination, dedicated storage, and fulfillment programs designed for scale.</p>',
 'published', 1, NOW(), 'Resilient Food Supply Chains | BlueAxis', 'Strategies for Manitoba wholesale partners to strengthen supply chain resilience.'),
(2, 1, 'What Canadian Buyers Should Know About African Food Importation', 'african-food-importation-canada',
 'Key considerations for grocery and wholesale buyers evaluating import partners.',
 '<p>Importing African food products requires documentation discipline, supplier verification, and logistics partners who understand wholesale timelines.</p><p>BlueAxis coordinates sourcing and inbound logistics so buyers can focus on market growth.</p>',
 'published', 0, DATE_SUB(NOW(), INTERVAL 7 DAY), 'African Food Importation Canada | BlueAxis', 'Guide for Canadian buyers on importation partnerships and logistics.'),
(3, 1, 'Expanding Distribution Across Canada', 'expanding-distribution-canada',
 'BlueAxis continues to grow Canada-wide fulfillment capabilities for partner networks.',
 '<p>From our Manitoba base, BlueAxis is expanding distribution reach to support grocery, retail, and food service partners nationally.</p>',
 'published', 0, DATE_SUB(NOW(), INTERVAL 14 DAY), 'Canada Distribution Expansion | BlueAxis', 'BlueAxis announces growing Canada-wide distribution support.');
