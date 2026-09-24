CREATE DATABASE IF NOT EXISTS store_db;
USE store_db;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    category VARCHAR(50) NOT NULL DEFAULT 'Umum',
    price DECIMAL(12,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    colors VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO products (name, category, price, stock, colors, description) VALUES
('Pashmina', 'Hijab', 50000, 27, 'Hitam (10), Cream (10), Maroon (7)', 'Pashmina berbahan kaos rayon premium.'),
('Segi Empat', 'Hijab', 35000, 12, 'Hitam (6), Navy (6)', 'Hijab segi empat bahan Paris Premium.'),
('Bergo', 'Hijab', 30000, 2, 'Coksu (2)', 'Bergo instan rumahan menutup dada.'),
('Segi Empat Instan', 'Hijab', 40000, 24, 'Hitam (12), Nude (12)', 'Segi empat langsung pakai tanpa jarum.'),
('Kaos Lengan Panjang', 'Outfit Atasan', 75000, 43, 'Putih (20), Hitam (23)', 'Kaos combed 30s adem.'),
('Kaos Lengan Pendek', 'Outfit Atasan', 70000, 38, 'Oversized Sage (18), Hitam (20)', 'Kaos santai oversized cewek.'),
('Dress', 'Outfit Atasan', 125000, 11, 'Maroon (5), Mocca (6)', 'Gamis/Dress kondangan bahan silk.'),
('Piama', 'Outfit Atasan', 120000, 24, 'Motif Bunga (12), Polos Pink (12)', 'Setelan baju tidur bahan rayon.'),
('Blazer', 'Outfit Atasan', 170000, 9, 'Hitam (4), Ivory (5)', 'Blazer formal cocok untuk kerja.'),
('Jeans Cutbray', 'Outfit Bawahan', 220000, 26, 'Dark Blue (13), Light Blue (13)', 'Celana jeans model cutbray.'),
('Baggy Jeans', 'Outfit Bawahan', 240000, 16, 'Snow Blue (8), Black Jeans (8)', 'Jeans kekinian longgar.'),
('Kulot', 'Outfit Bawahan', 140000, 33, 'Hitam (15), Highwaist Cream (18)', 'Kulot bahan knit jatuh.'),
('Rok Span Kantor', 'Outfit Bawahan', 150000, 2, 'Hitam (2)', 'Rok span formal bahan elastis.'),
('Rok Duyung', 'Outfit Bawahan', 170000, 16, 'Maroon (8), Beige (8)', 'Rok duyung kondangan.');