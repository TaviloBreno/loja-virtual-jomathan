<?php

class CreateOrderStatusHistoryTable
{
    public function up()
    {
        return "
            CREATE TABLE order_status_history (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                from_status VARCHAR(50) NULL,
                to_status VARCHAR(50) NOT NULL,
                comment TEXT NULL,
                notify_customer BOOLEAN DEFAULT FALSE,
                created_by INT NULL COMMENT 'User ID who made the change',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
                INDEX idx_order_id (order_id),
                INDEX idx_to_status (to_status),
                INDEX idx_created_at (created_at),
                INDEX idx_created_by (created_by)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    }
    
    public function down()
    {
        return "DROP TABLE IF EXISTS order_status_history;";
    }
}