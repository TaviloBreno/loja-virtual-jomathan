<?php

class CreateCartItemsTable
{
    public function up()
    {
        return "
            CREATE TABLE cart_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                cart_id INT NOT NULL,
                product_id INT NOT NULL,
                product_variant_id INT NULL,
                quantity INT NOT NULL DEFAULT 1,
                unit_price DECIMAL(10,2) NOT NULL,
                total_price DECIMAL(10,2) NOT NULL,
                product_snapshot JSON NULL COMMENT 'Store product data at time of adding to cart',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
                FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
                FOREIGN KEY (product_variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
                INDEX idx_cart_id (cart_id),
                INDEX idx_product_id (product_id),
                INDEX idx_product_variant_id (product_variant_id),
                UNIQUE KEY unique_cart_product_variant (cart_id, product_id, product_variant_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    }
    
    public function down()
    {
        return "DROP TABLE IF EXISTS cart_items;";
    }
}