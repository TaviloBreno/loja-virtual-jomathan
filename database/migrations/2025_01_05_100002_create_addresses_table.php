<?php

class CreateAddressesTable
{
    public function up()
    {
        return "
            CREATE TABLE addresses (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                type ENUM('billing', 'shipping', 'both') DEFAULT 'both',
                name VARCHAR(255) NOT NULL,
                street VARCHAR(255) NOT NULL,
                number VARCHAR(20) NOT NULL,
                complement VARCHAR(255) NULL,
                neighborhood VARCHAR(255) NOT NULL,
                city VARCHAR(255) NOT NULL,
                state VARCHAR(2) NOT NULL,
                postal_code VARCHAR(10) NOT NULL,
                country VARCHAR(2) DEFAULT 'BR',
                is_default BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                INDEX idx_user_id (user_id),
                INDEX idx_type (type),
                INDEX idx_postal_code (postal_code)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    }
    
    public function down()
    {
        return "DROP TABLE IF EXISTS addresses;";
    }
}