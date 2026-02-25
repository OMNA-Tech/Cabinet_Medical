-- Create Database
CREATE DATABASE IF NOT EXISTS cabinet_medical;
USE cabinet_medical;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('patient', 'admin') DEFAULT 'patient',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Appointments Table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason VARCHAR(50) NOT NULL,
    message TEXT,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Services Table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50) DEFAULT 'fa-stethoscope'
);

-- Settings Table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(50) NOT NULL UNIQUE,
    value TEXT
);

-- Insert Default Settings
INSERT INTO settings (key_name, value) VALUES 
('site_title', 'Cabinet Médical Santé Plus'),
('doctor_name', 'Dr. Jean Dupont'),
('doctor_specialty', 'Médecine Générale'),
('address', '123 Rue de la Santé, 75000 Paris'),
('phone', '01 23 45 67 89'),
('email', 'contact@santeplus.fr'),
('hero_title', 'Votre santé, notre priorité'),
('hero_text', 'Bienvenue au Cabinet du Dr. Dupont. Une prise en charge humaine et personnalisée pour toute la famille.'),
('about_text', 'Le Cabinet Médical Santé Plus vous accueille pour des consultations de médecine générale. Le Dr. Dupont assure le suivi des nourrissons, enfants et adultes.'),
('hero_image', 'assets/img/hero-bg.jpg'),
('about_image', 'assets/img/about.jpg');

-- Insert Default Services
INSERT INTO services (title, description, icon) VALUES 
('Consultation Générale', 'Examen complet pour diagnostiquer et traiter les maladies courantes.', 'fa-user-md'),
('Suivi Pédiatrique', 'Consultations pour nourrissons et enfants : vaccins, croissance, maladies infantiles.', 'fa-baby'),
('Certificats Médicaux', 'Délivrance de certificats pour le sport, le travail ou autres besoins administratifs.', 'fa-file-medical');

-- Insert Default Admin User (Password: admin123)
-- Hash generated using password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (name, email, password, role) VALUES 
('Dr. Jean Dupont', 'admin@santeplus.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
