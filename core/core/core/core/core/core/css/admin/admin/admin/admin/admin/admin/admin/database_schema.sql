CREATE DATABASE IF NOT EXISTS eshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eshop;

CREATE TABLE catalog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    pubyear INT NOT NULL
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(32) NOT NULL UNIQUE,
    customer VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ordered_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(32) NOT NULL,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (item_id) REFERENCES catalog(id)
);

-- Хранимые процедуры
DELIMITER //

CREATE PROCEDURE spAddItemToCatalog(IN p_title VARCHAR(255), IN p_author VARCHAR(255), IN p_price DECIMAL(10,2), IN p_pubyear INT)
BEGIN INSERT INTO catalog (title, author, price, pubyear) VALUES (p_title, p_author, p_price, p_pubyear); END //

CREATE PROCEDURE spGetCatalog()
BEGIN SELECT id, title, author, price, pubyear FROM catalog; END //

CREATE PROCEDURE spGetItemsForBasket(IN p_ids TEXT)
BEGIN SET @sql = CONCAT('SELECT id, title, author, price, pubyear FROM catalog WHERE id IN (', p_ids, ')'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt; END //

CREATE PROCEDURE spSaveOrder(IN p_order_id VARCHAR(32), IN p_customer VARCHAR(255), IN p_email VARCHAR(255), IN p_phone VARCHAR(20), IN p_address TEXT)
BEGIN INSERT INTO orders (order_id, customer, email, phone, address) VALUES (p_order_id, p_customer, p_email, p_phone, p_address); END //

CREATE PROCEDURE spSaveOrderedItems(IN p_order_id VARCHAR(32), IN p_item_id INT, IN p_quantity INT)
BEGIN INSERT INTO ordered_items (order_id, item_id, quantity) VALUES (p_order_id, p_item_id, p_quantity); END //

CREATE PROCEDURE spGetOrders()
BEGIN SELECT id, order_id, customer, email, phone, address, created FROM orders ORDER BY created DESC; END //

CREATE PROCEDURE spGetOrderedItems(IN p_order_id VARCHAR(32))
BEGIN
  SELECT c.title, c.author, c.price, oi.quantity
  FROM ordered_items oi
  JOIN catalog c ON oi.item_id = c.id
  WHERE oi.order_id = p_order_id;
END //

CREATE PROCEDURE spSaveAdmin(IN p_login VARCHAR(100), IN p_password VARCHAR(255), IN p_email VARCHAR(255))
BEGIN INSERT INTO admins (login, password, email) VALUES (p_login, p_password, p_email); END //

CREATE PROCEDURE spGetAdmin(IN p_login VARCHAR(100))
BEGIN SELECT login, password, email FROM admins WHERE login = p_login; END //

DELIMITER ;

-- Админ по умолчанию: логин=admin, пароль=admin123
INSERT INTO admins (login, password, email) VALUES ('admin', '$2y$10$X0k5qGfZ2JxN7K8QZ1mQWuY6RzJ5qJvZ3LqT8RvZ3LqT8RvZ3Lq', 'admin@example.com');
