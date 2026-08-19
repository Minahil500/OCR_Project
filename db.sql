CREATE DATABASE IF NOT EXISTS ocr_db;

USE ocr_db;

CREATE TABLE IF NOT EXISTS document_ocr_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NULL,
    document_type VARCHAR(100) NULL,
    extracted_text LONGTEXT NULL,
    extracted_json JSON NULL,
    ocr_applied TINYINT(1) DEFAULT 0,
    ocr_status VARCHAR(50) NULL,
    error_message TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);