<?php

class CreatePagesTable
{
    public function up()
    {
        return "
            CREATE TABLE pages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                slug VARCHAR(255) NOT NULL UNIQUE,
                content LONGTEXT NULL,
                excerpt TEXT NULL,
                status ENUM('published', 'draft', 'private') DEFAULT 'draft',
                template VARCHAR(100) DEFAULT 'default',
                featured_image VARCHAR(255) NULL,
                meta_title VARCHAR(255) NULL,
                meta_description TEXT NULL,
                meta_keywords TEXT NULL,
                sort_order INT DEFAULT 0,
                is_homepage BOOLEAN DEFAULT FALSE,
                show_in_menu BOOLEAN DEFAULT TRUE,
                menu_title VARCHAR(255) NULL,
                parent_id INT NULL,
                author_id INT NULL,
                published_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (parent_id) REFERENCES pages(id) ON DELETE SET NULL,
                FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
                INDEX idx_slug (slug),
                INDEX idx_status (status),
                INDEX idx_is_homepage (is_homepage),
                INDEX idx_show_in_menu (show_in_menu),
                INDEX idx_parent_id (parent_id),
                INDEX idx_author_id (author_id),
                INDEX idx_published_at (published_at),
                INDEX idx_created_at (created_at),
                INDEX idx_sort_order (sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    }
    
    public function down()
    {
        return "DROP TABLE IF EXISTS pages;";
    }
}