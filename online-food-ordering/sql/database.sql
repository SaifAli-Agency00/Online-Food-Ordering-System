-- Online Food Ordering System Database
-- Database name: food_ordering_system

DROP DATABASE IF EXISTS food_ordering_system;
CREATE DATABASE food_ordering_system;
USE food_ordering_system;

-- Users table stores both customers and admins.
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Foods table stores menu items managed by the admin.
CREATE TABLE IF NOT EXISTS foods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('available', 'unavailable') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table stores one record for each checkout.
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(30) NOT NULL,
    status ENUM('pending', 'confirmed', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items table stores the foods inside each order.
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    food_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (food_id) REFERENCES foods(id) ON DELETE RESTRICT
);

-- Default admin account.
-- Email: admin@foodorder.com
-- Password: admin123
INSERT INTO users (name, email, phone, password, role, last_login) VALUES
('Admin User', 'admin@foodorder.com', '+10000000000', '$2y$12$biSGfoRto/MYSoMNG54LceZtzCn8VkIfDQ9PQcFNTkUWKOefvxfdG', 'admin', NULL),
('Ayesha Khan', 'ayesha@example.com', '+1555010101', '$2y$12$qIvH8HNPanamHVAHlbG/.u0J9DtCuK.vXAJBNPdht787W9BMRFHfi', 'user', NULL),
('Ali Raza', 'ali@example.com', '+1555020202', '$2y$12$qIvH8HNPanamHVAHlbG/.u0J9DtCuK.vXAJBNPdht787W9BMRFHfi', 'user', NULL);

-- Sample food records for the public menu.
INSERT INTO foods (name, description, price, status) VALUES
('Margherita Pizza', 'Classic pizza with tomato sauce, mozzarella cheese, and fresh basil.', 9.99, 'available'),
('Cheese Burger', 'Juicy beef patty with cheese, lettuce, tomato, and house sauce.', 7.49, 'available'),
('Chicken Biryani', 'Fragrant rice cooked with spiced chicken and herbs.', 11.99, 'available'),
('Veggie Pasta', 'Pasta tossed with fresh vegetables and creamy tomato sauce.', 8.99, 'available'),
('Chocolate Brownie', 'Warm chocolate brownie served as a sweet dessert.', 4.99, 'available');
