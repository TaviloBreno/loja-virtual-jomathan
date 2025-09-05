<?php

class CreateProductsTable
{
    public function up()
    {
        return "
            CREATE TABLE products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                category_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL UNIQUE,
                description TEXT NULL,
                short_description TEXT NULL,
                sku VARCHAR(100) NOT NULL UNIQUE,
                price DECIMAL(10,2) NOT NULL,
                compare_price DECIMAL(10,2) NULL,
                cost_price DECIMAL(10,2) NULL,
                weight DECIMAL(8,3) NULL,
                dimensions VARCHAR(100) NULL,
                status ENUM('active', 'inactive', 'draft') DEFAULT 'draft',
                is_featured BOOLEAN DEFAULT FALSE,
                is_digital BOOLEAN DEFAULT FALSE,
                requires_shipping BOOLEAN DEFAULT TRUE,
                track_inventory BOOLEAN DEFAULT TRUE,
                stock_quantity INT DEFAULT 0,
                min_stock_level INT DEFAULT 0,
                max_stock_level INT NULL,
                brand VARCHAR(255) NULL,
                model VARCHAR(255) NULL,
                tags TEXT NULL,
                meta_title VARCHAR(255) NULL,
                meta_description TEXT NULL,
                sort_order INT DEFAULT 0,
                views_count INT DEFAULT 0,
                sales_count INT DEFAULT 0,
                rating_average DECIMAL(3,2) DEFAULT 0.00,
                rating_count INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
                INDEX idx_category_id (category_id),
                INDEX idx_slug (slug),
                INDEX idx_sku (sku),
                INDEX idx_status (status),
                INDEX idx_is_featured (is_featured),
                INDEX idx_price (price),
                INDEX idx_stock_quantity (stock_quantity),
                INDEX idx_created_at (created_at),
                INDEX idx_rating (rating_average, rating_count),
                FULLTEXT idx_search (name, description, tags)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    }
    
    public function down()
    {
        return "DROP TABLE IF EXISTS products;";
    }
}