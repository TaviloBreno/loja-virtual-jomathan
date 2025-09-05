<?php

class CreateCategoriesTable
{
    public function up()
    {
        return "
            CREATE TABLE categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                parent_id INT NULL,
                name VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL UNIQUE,
                description TEXT NULL,
                image VARCHAR(255) NULL,
                icon VARCHAR(100) NULL,
                color VARCHAR(7) NULL,
                sort_order INT DEFAULT 0,
                is_active BOOLEAN DEFAULT TRUE,
                meta_title VARCHAR(255) NULL,
                meta_description TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
                INDEX idx_parent_id (parent_id),
                INDEX idx_slug (slug),
                INDEX idx_is_active (is_active),
                INDEX idx_sort_order (sort_order),
                INDEX idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    }
    
    public function down()
    {
        return "DROP TABLE IF EXISTS categories;";
    }
}