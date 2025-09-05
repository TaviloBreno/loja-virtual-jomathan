<?php

class CreateCouponsTable
{
    public function up()
    {
        return "
            CREATE TABLE coupons (
                id INT AUTO_INCREMENT PRIMARY KEY,
                code VARCHAR(50) NOT NULL UNIQUE,
                name VARCHAR(255) NOT NULL,
                description TEXT NULL,
                type ENUM('fixed', 'percentage') NOT NULL,
                value DECIMAL(10,2) NOT NULL,
                minimum_amount DECIMAL(10,2) NULL,
                maximum_discount DECIMAL(10,2) NULL,
                usage_limit INT NULL,
                usage_limit_per_user INT NULL,
                used_count INT DEFAULT 0,
                is_active BOOLEAN DEFAULT TRUE,
                starts_at TIMESTAMP NULL,
                expires_at TIMESTAMP NULL,
                applicable_to ENUM('all', 'categories', 'products') DEFAULT 'all',
                applicable_ids JSON NULL COMMENT 'Category or product IDs when applicable_to is not all',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                INDEX idx_code (code),
                INDEX idx_is_active (is_active),
                INDEX idx_starts_at (starts_at),
                INDEX idx_expires_at (expires_at),
                INDEX idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    }
    
    public function down()
    {
        return "DROP TABLE IF EXISTS coupons;";
    }
}