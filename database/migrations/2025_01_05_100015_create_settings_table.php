<?php

class CreateSettingsTable
{
    public function up()
    {
        return "
            CREATE TABLE settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                `key` VARCHAR(255) NOT NULL UNIQUE,
                value TEXT NULL,
                type ENUM('string', 'integer', 'float', 'boolean', 'json', 'text') DEFAULT 'string',
                group_name VARCHAR(100) DEFAULT 'general',
                label VARCHAR(255) NULL,
                description TEXT NULL,
                is_public BOOLEAN DEFAULT FALSE COMMENT 'Whether setting can be accessed publicly',
                sort_order INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                INDEX idx_key (`key`),
                INDEX idx_group_name (group_name),
                INDEX idx_is_public (is_public),
                INDEX idx_sort_order (sort_order)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    }
    
    public function down()
    {
        return "DROP TABLE IF EXISTS settings;";
    }
}