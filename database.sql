CREATE DATABASE IF NOT EXISTS comshop;
USE comshop;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);


INSERT INTO products (product, price) VALUES
('Apple', 10.00),
('Banana', 5.00),
('Mango', 15.00);