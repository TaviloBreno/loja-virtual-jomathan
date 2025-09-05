<?php

class CreatePaymentsTable
{
    public function up()
    {
        return "
            CREATE TABLE payments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                transaction_id VARCHAR(255) NULL,
                payment_method ENUM('credit_card', 'debit_card', 'pix', 'boleto', 'paypal', 'bank_transfer') NOT NULL,
                gateway VARCHAR(100) NULL COMMENT 'Payment gateway used (stripe, pagseguro, etc)',
                status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded', 'partially_refunded') DEFAULT 'pending',
                amount DECIMAL(10,2) NOT NULL,
                currency VARCHAR(3) DEFAULT 'BRL',
                gateway_response JSON NULL COMMENT 'Store gateway response data',
                failure_reason TEXT NULL,
                refund_amount DECIMAL(10,2) DEFAULT 0.00,
                refund_reason TEXT NULL,
                processed_at TIMESTAMP NULL,
                expires_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                INDEX idx_order_id (order_id),
                INDEX idx_transaction_id (transaction_id),
                INDEX idx_status (status),
                INDEX idx_payment_method (payment_method),
                INDEX idx_gateway (gateway),
                INDEX idx_processed_at (processed_at),
                INDEX idx_expires_at (expires_at),
                INDEX idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    }
    
    public function down()
    {
        return "DROP TABLE IF EXISTS payments;";
    }
}