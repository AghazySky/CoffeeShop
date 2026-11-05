SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT /;
/!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS /;
/!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION /;
/!40101 SET NAMES utf8mb4 */;

--
-- Database: db_kopipiro

--
-- Struktur dari tabel users

CREATE TABLE users (
id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
username varchar(50) NOT NULL UNIQUE,
password varchar(255) NOT NULL,
role enum('admin','owner','kasir','customer') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel users
-- (Password untuk semua akun: password123)

INSERT INTO users (id, username, password, role) VALUES
(1, 'admin', '$2y$10$wH28l87K56k0g/QWl18Cieo8r6GgN1w6kXzOqE8R9jV8b6wG7dKfe', 'admin'), (2, 'owner', '$2y$10$wH28l87K56k0g/QWl18Cieo8r6GgN1w6kXzOqE8R9jV8b6wG7dKfe', 'owner'),
(3, 'kasir1', '$2y$10$wH28l87K56k0g/QWl18Cieo8r6GgN1w6kXzOqE8R9jV8b6wG7dKfe', 'kasir'), (4, 'gazi', '$2y$10$wH28l87K56k0g/QWl18Cieo8r6GgN1w6kXzOqE8R9jV8b6wG7dKfe', 'customer');

--
-- Struktur dari tabel product

CREATE TABLE product (
id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
name varchar(100) NOT NULL UNIQUE,
description text DEFAULT NULL,
rating decimal(2,1) DEFAULT 4.8,
price int(11) NOT NULL,
url_image varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel product

INSERT INTO product (id, name, description, rating, price, url_image) VALUES
(1, 'Cappuccino', 'Espresso klasik dengan perpaduan susu panas dan foam lembut tebal.', 4.8, 25000, 'https://images.unsplash.com/photo-1506372023823-741c83b836fe?w=800&auto=format&fit=crop&q=60'),
(2, 'Americano', 'Espresso pekat berpadu dengan air panas untuk rasa kopi murni yang segar.', 4.5, 15000, 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=800&auto=format&fit=crop&q=60'),
(3, 'Caramel Latte', 'Espresso berpadu dengan susu creamy dan sirup karamel gurih manis.', 4.9, 20000, 'https://images.unsplash.com/photo-1570968915860-54d5c301fa9f?w=800&auto=format&fit=crop&q=60'),
(4, 'Butterscotch Toast', 'Roti panggang renyah beraroma gurih dengan baluran saus butterscotch legit.', 4.7, 30000, 'https://images.unsplash.com/photo-1484723091739-30a097e8f929?w=800&auto=format&fit=crop&q=60');

--
-- Struktur dari tabel orders

CREATE TABLE orders (
id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
customer_id int(11) DEFAULT NULL,
cashier_id int(11) DEFAULT NULL,
total_price int(11) NOT NULL,
discount_applied tinyint(1) DEFAULT 0,
order_date datetime DEFAULT CURRENT_TIMESTAMP,
status enum('pending','processed','completed') DEFAULT 'pending',
CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES users (id) ON DELETE SET NULL,
CONSTRAINT fk_orders_cashier FOREIGN KEY (cashier_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Struktur dari tabel order_items

CREATE TABLE order_items (
id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
order_id int(11) NOT NULL,
product_id int(11) NOT NULL,
qty int(11) NOT NULL,
price_at_checkout int(11) NOT NULL,
CONSTRAINT fk_items_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
CONSTRAINT fk_items_product FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT /;
/!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS /;
/!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
