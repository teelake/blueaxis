-- Run on existing databases: mysql -u root blueaxis < database/seeds/013_product_categories.sql

CREATE TABLE IF NOT EXISTS product_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL,
    sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_product_categories_name (name),
    UNIQUE KEY uk_product_categories_slug (slug),
    INDEX idx_sort (sort_order, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO product_categories (name, slug, sort_order) VALUES
('Oils & fats', 'oils-fats', 10),
('Flours & staples', 'flours-staples', 20),
('Protein & seafood', 'protein-seafood', 30),
('Snacks & packaged', 'snacks-packaged', 40),
('Spices & seasonings', 'spices-seasonings', 50);
