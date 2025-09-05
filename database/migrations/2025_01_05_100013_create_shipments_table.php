<?php

class CreateShipmentsTable
{
    public function up()
    {
        return "
            CREATE TABLE shipments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                tracking_number VARCHAR(255) NULL,
                carrier VARCHAR(100) NULL,
                service_type VARCHAR(100) NULL,
                status ENUM('pending', 'processing', 'shipped', 'in_transit', 'delivered', 'failed', 'returned') DEFAULT 'pending',
                shipping_cost DECIMAL(10,2) DEFAULT 0.00,
                weight DECIMAL(8,3) NULL,
                dimensions VARCHAR(100) NULL,
                shipping_address JSON NOT NULL,
                estimated_delivery_date DATE NULL,
                shipped_at TIMESTAMP NULL,
                delivered_at TIMESTAMP NULL,
                notes TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                INDEX idx_order_id (order_id),
                INDEX idx_tracking_number (tracking_number),
                INDEX idx_status (status),
                INDEX idx_carrier (carrier),
                INDEX idx_shipped_at (shipped_at),
                INDEX idx_delivered_at (delivered_at),
                INDEX idx_estimated_delivery_date (estimated_delivery_date)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    }
    
    public function down()
    {
        return "DROP TABLE IF EXISTS shipments;";
    }
}