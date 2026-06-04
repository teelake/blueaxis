INSERT INTO pages (slug, title, meta_title, meta_description) VALUES
('products', 'Product Catalog', 'Wholesale Product Catalog | BlueAxis Logistics', 'B2B food and logistics product lines for wholesale partners across Manitoba and Canada.')
ON DUPLICATE KEY UPDATE title = VALUES(title), meta_title = VALUES(meta_title), meta_description = VALUES(meta_description);

INSERT INTO products (title, slug, category, sku, excerpt, description, origin_region, pack_format, storage_notes, is_featured, is_published, sort_order) VALUES
(
  'Premium Red Palm Oil',
  'premium-red-palm-oil',
  'Oils & fats',
  'BAX-PO-001',
  'Food-grade red palm oil for wholesale kitchens and retail programs. Import-coordinated with Manitoba warehousing.',
  '<p>Structured for B2B buyers who need consistent inbound timing and pallet-ready fulfillment. Suitable for grocery, food service, and specialty African food retailers.</p><ul><li>Batch traceability on request</li><li>Pallet and mixed-case programs</li><li>Coordinated import and local distribution</li></ul>',
  'West Africa',
  '20L drum / palletized',
  'Store in a cool, dry warehouse. Food-grade handling required.',
  1,
  1,
  10
),
(
  'Authentic Cassava Flour',
  'authentic-cassava-flour',
  'Flours & staples',
  'BAX-CF-010',
  'High-demand staple for African food retailers and distributors. Pack formats aligned to wholesale case counts.',
  '<p>Reliable staple SKU for partners building pantry sets across Manitoba and national accounts.</p>',
  'West Africa',
  '25kg bag / pallet',
  'Dry storage. Protect from moisture.',
  1,
  1,
  20
),
(
  'Dried Fish Assortment (Bonga)',
  'dried-fish-bonga-assortment',
  'Protein & seafood',
  'BAX-DF-020',
  'Curated dried fish program for ethnic grocery and wholesale buyers. Temperature-aware handling notes included.',
  '<p>Seasonal availability communicated at quote stage. Import and storage coordinated through BlueAxis warehousing.</p>',
  'West Africa',
  'Carton / master case',
  'Ambient dry storage with ventilation.',
  0,
  1,
  30
),
(
  'Plantain Chips — Wholesale Case',
  'plantain-chips-wholesale',
  'Snacks & packaged',
  'BAX-PC-030',
  'Shelf-ready plantain chips for retail and food service. Ideal for regional distributor rollouts.',
  '<p>Program pricing available for multi-SKU orders combined with other catalog lines.</p>',
  'West Africa',
  'Display-ready case',
  'Ambient. Rotate FIFO.',
  0,
  1,
  40
),
(
  'Spice Blend — Jollof Base',
  'jollof-spice-blend-base',
  'Spices & seasonings',
  'BAX-SP-040',
  'Signature seasoning base for food service and retail private-label discussions.',
  '<p>Formulation support and pack-size flexibility for established wholesale partners.</p>',
  'West Africa',
  '1kg pouch / case',
  'Dry, ambient storage.',
  0,
  1,
  50
);
