CREATE DATABASE library_db;

USE library_db;

CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255),
    year_published INT,
    status ENUM('available', 'checked_out') DEFAULT 'available',
    checked_out_by VARCHAR(100) NULL );