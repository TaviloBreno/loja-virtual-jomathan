<?php

class CreateOrderItemsTable
{
    public function up()
    {
        return "
            CREATE TABLE order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                product_variant_id INT NULL,
                product_name VARCHAR(255) NOT NULL,
                product_sku VARCHAR(100) NOT NULL,
                variant_name VARCHAR(255) NULL,
                variant_sku VARCHAR(100) NULL,
                quantity INT NOT NULL,
                unit_price DECIMAL(10,2) NOT NULL,
                total_price DECIMAL(10,2) NOT NULL,
                product_snapshot JSON NULL COMMENT 'Complete product data at time of order',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
                FOREIGN KEY (product_variant_id) REFERENCES product_variants(id) ON DELETE RESTRICT,
                INDEX idx_order_id (order_id),
                INDEX idx_product_id (product_id),
                INDEX idx_product_variant_id (product_variant_id),
                INDEX idx_product_sku (product_sku),
                INDEX idx_variant_sku (variant_sku)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    }
    
    public function down()
    {
        return "DROP TABLE IF EXISTS order_items;";
    }
}