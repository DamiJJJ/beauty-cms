-- Beauty Client Management System - Database Structure
-- Uruchom ten plik w phpMyAdmin lub MySQL Workbench

CREATE DATABASE IF NOT EXISTS beauty_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE beauty_cms;

-- Tabela klientów
CREATE TABLE clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(255),
    birth_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela wizyt/zabiegów
CREATE TABLE appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    treatment_type VARCHAR(100) NOT NULL,
    appointment_date DATETIME NOT NULL,
    notes TEXT,
    price DECIMAL(8,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- Tabela zdjęć klientów
CREATE TABLE client_photos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    appointment_id INT,
    photo_path VARCHAR(255) NOT NULL,
    photo_type ENUM('before', 'after') NOT NULL DEFAULT 'before',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL
);

-- Przykładowe dane testowe
INSERT INTO clients (first_name, last_name, phone, email, birth_date, notes) VALUES
('Anna', 'Kowalska', '+48 123 456 789', 'anna.kowalska@email.com', '1990-05-15', 'Skóra wrażliwa, unika produktów z alkoholem'),
('Maria', 'Nowak', '+48 987 654 321', 'maria.nowak@email.com', '1985-08-22', 'Regularna klientka, preferuje zabiegi przeciwstarzeniowe'),
('Katarzyna', 'Wiśniewska', '+48 555 123 456', 'k.wisniewska@email.com', '1992-12-03', 'Pierwsza wizyta, zainteresowana peelingiem');

INSERT INTO appointments (client_id, treatment_type, appointment_date, notes, price) VALUES
(1, 'Oczyszczanie twarzy', '2024-03-15 14:00:00', 'Zabieg przeszedł bez powikłań', 150.00),
(1, 'Peeling kawitacyjny', '2024-04-20 15:30:00', 'Delikatna reakcja skóry, zastosowano łagodniejszy preparat', 200.00),
(2, 'Mezoterapia', '2024-03-22 10:00:00', 'Bardzo zadowolona z efektów', 350.00),
(3, 'Masaż twarzy', '2024-04-01 16:00:00', 'Pierwszy zabieg, skóra dobrze zareagowała', 120.00);