<?php

class CreateProductVariantsTable
{
    public function up()
    {
        return "
            CREATE TABLE product_variants (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                sku VARCHAR(100) NOT NULL UNIQUE,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                compare_price DECIMAL(10,2) NULL,
                cost_price DECIMAL(10,2) NULL,
                weight DECIMAL(8,3) NULL,
                stock_quantity INT DEFAULT 0,
                min_stock_level INT DEFAULT 0,
                is_default BOOLEAN DEFAULT FALSE,
                is_active BOOLEAN DEFAULT TRUE,
                attributes JSON NULL COMMENT 'Store variant attributes like size, color, etc.',
                image VARCHAR(255) NULL,
                sort_order INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                INDEX idx_product_id (product_id),
                INDEX idx_sku (sku),
                INDEX idx_is_default (is_default),
                INDEX idx_is_active (is_active),
                INDEX idx_stock_quantity (stock_quantity),
                INDEX idx_price (price)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    }
    
    public function down()
    {
        return "DROP TABLE IF EXISTS product_variants;";
    }
}