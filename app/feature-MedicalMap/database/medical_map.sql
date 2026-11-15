-- =====================================================================
-- Saha+ – Medical Map module : database script
-- Import this file in phpMyAdmin (or: mysql -u root < medical_map.sql).
-- It creates the database `saha_plus` if needed, then (re)creates the two
-- tables of the module. Re-importing RESETS these two tables only.
--
-- ALL ROWS BELOW ARE FICTIONAL DEMONSTRATION DATA (is_demo = 1).
-- They are not real facilities or real doctors. Phone numbers are left
-- empty and e-mails use the reserved test domain example.com.
-- Coordinates are placed near the capital city of each governorate.
-- =====================================================================
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS saha_plus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE saha_plus;

DROP TABLE IF EXISTS doctor;
DROP TABLE IF EXISTS facility;

-- ---------------------------------------------------------------------
-- Facility (1) ---- (0..*) Doctor
-- ---------------------------------------------------------------------
CREATE TABLE facility (
    id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name             VARCHAR(120) NOT NULL,
    type             ENUM('hospital', 'clinic', 'donation_center') NOT NULL,
    address          VARCHAR(200) NOT NULL,
    city             VARCHAR(80)  NOT NULL,
    governorate      VARCHAR(40)  NOT NULL,
    latitude         DECIMAL(9,6) NOT NULL,
    longitude        DECIMAL(9,6) NOT NULL,
    phone            VARCHAR(20)  NULL,
    emergency_status TINYINT(1)   NOT NULL DEFAULT 0,
    is_demo          TINYINT(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    -- Tunisia only: the point must be inside the bounding box of the country.
    CONSTRAINT chk_facility_in_tunisia CHECK (latitude BETWEEN 30.20 AND 37.60 AND longitude BETWEEN 7.50 AND 11.65),
    -- Tunisia only: the governorate must be one of the 24.
    CONSTRAINT chk_facility_governorate CHECK (governorate IN (
        'Ariana', 'Béja', 'Ben Arous', 'Bizerte', 'Gabès', 'Gafsa', 'Jendouba', 'Kairouan',
        'Kasserine', 'Kébili', 'Le Kef', 'Mahdia', 'Manouba', 'Médenine', 'Monastir', 'Nabeul',
        'Sfax', 'Sidi Bouzid', 'Siliana', 'Sousse', 'Tataouine', 'Tozeur', 'Tunis', 'Zaghouan'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE doctor (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name  VARCHAR(60)  NOT NULL,
    last_name   VARCHAR(60)  NOT NULL,
    specialty   VARCHAR(60)  NOT NULL,
    phone       VARCHAR(20)  NULL,
    email       VARCHAR(120) NULL,
    facility_id INT UNSIGNED NOT NULL,          -- NOT NULL: a doctor belongs to exactly one facility
    PRIMARY KEY (id),
    KEY idx_doctor_specialty (specialty),
    -- RESTRICT: a facility that still has doctors cannot be deleted by mistake.
    CONSTRAINT fk_doctor_facility FOREIGN KEY (facility_id) REFERENCES facility (id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Demonstration facilities (fictional) – one hospital per governorate
-- ---------------------------------------------------------------------
INSERT INTO facility (id, name, type, address, city, governorate, latitude, longitude, phone, emergency_status, is_demo) VALUES
( 1, 'Demo Regional Hospital – Tunis',       'hospital', 'Demo address 1',  'Tunis',       'Tunis',       36.806500, 10.181500, NULL, 1, 1),
( 2, 'Demo Regional Hospital – Ariana',      'hospital', 'Demo address 2',  'Ariana',      'Ariana',      36.862500, 10.195600, NULL, 1, 1),
( 3, 'Demo Regional Hospital – Ben Arous',   'hospital', 'Demo address 3',  'Ben Arous',   'Ben Arous',   36.753100, 10.218900, NULL, 1, 1),
( 4, 'Demo Regional Hospital – Manouba',     'hospital', 'Demo address 4',  'Manouba',     'Manouba',     36.810100, 10.095600, NULL, 0, 1),
( 5, 'Demo Regional Hospital – Nabeul',      'hospital', 'Demo address 5',  'Nabeul',      'Nabeul',      36.456100, 10.737600, NULL, 1, 1),
( 6, 'Demo Regional Hospital – Zaghouan',    'hospital', 'Demo address 6',  'Zaghouan',    'Zaghouan',    36.402900, 10.142900, NULL, 0, 1),
( 7, 'Demo Regional Hospital – Bizerte',     'hospital', 'Demo address 7',  'Bizerte',     'Bizerte',     37.274400,  9.873900, NULL, 1, 1),
( 8, 'Demo Regional Hospital – Béja',        'hospital', 'Demo address 8',  'Béja',        'Béja',        36.725600,  9.181700, NULL, 1, 1),
( 9, 'Demo Regional Hospital – Jendouba',    'hospital', 'Demo address 9',  'Jendouba',    'Jendouba',    36.501100,  8.780200, NULL, 1, 1),
(10, 'Demo Regional Hospital – Le Kef',      'hospital', 'Demo address 10', 'Le Kef',      'Le Kef',      36.174200,  8.704900, NULL, 0, 1),
(11, 'Demo Regional Hospital – Siliana',     'hospital', 'Demo address 11', 'Siliana',     'Siliana',     36.084900,  9.370800, NULL, 0, 1),
(12, 'Demo Regional Hospital – Sousse',      'hospital', 'Demo address 12', 'Sousse',      'Sousse',      35.825600, 10.636900, NULL, 1, 1),
(13, 'Demo Regional Hospital – Monastir',    'hospital', 'Demo address 13', 'Monastir',    'Monastir',    35.764300, 10.811300, NULL, 1, 1),
(14, 'Demo Regional Hospital – Mahdia',      'hospital', 'Demo address 14', 'Mahdia',      'Mahdia',      35.504700, 11.062200, NULL, 1, 1),
(15, 'Demo Regional Hospital – Sfax',        'hospital', 'Demo address 15', 'Sfax',        'Sfax',        34.740600, 10.760300, NULL, 1, 1),
(16, 'Demo Regional Hospital – Kairouan',    'hospital', 'Demo address 16', 'Kairouan',    'Kairouan',    35.678100, 10.096300, NULL, 1, 1),
(17, 'Demo Regional Hospital – Kasserine',   'hospital', 'Demo address 17', 'Kasserine',   'Kasserine',   35.167600,  8.836500, NULL, 1, 1),
(18, 'Demo Regional Hospital – Sidi Bouzid', 'hospital', 'Demo address 18', 'Sidi Bouzid', 'Sidi Bouzid', 35.038200,  9.484900, NULL, 0, 1),
(19, 'Demo Regional Hospital – Gabès',       'hospital', 'Demo address 19', 'Gabès',       'Gabès',       33.881500, 10.098200, NULL, 1, 1),
(20, 'Demo Regional Hospital – Médenine',    'hospital', 'Demo address 20', 'Médenine',    'Médenine',    33.354900, 10.505500, NULL, 1, 1),
(21, 'Demo Regional Hospital – Tataouine',   'hospital', 'Demo address 21', 'Tataouine',   'Tataouine',   32.929700, 10.451800, NULL, 0, 1),
(22, 'Demo Regional Hospital – Gafsa',       'hospital', 'Demo address 22', 'Gafsa',       'Gafsa',       34.425000,  8.784200, NULL, 1, 1),
(23, 'Demo Regional Hospital – Tozeur',      'hospital', 'Demo address 23', 'Tozeur',      'Tozeur',      33.919700,  8.133500, NULL, 0, 1),
(24, 'Demo Regional Hospital – Kébili',      'hospital', 'Demo address 24', 'Kébili',      'Kébili',      33.704400,  8.969000, NULL, 0, 1),
-- Demonstration clinics (fictional)
(25, 'Demo Clinic – Tunis',    'clinic', 'Demo address 25', 'Tunis',    'Tunis',    36.818500, 10.171500, NULL, 0, 1),
(26, 'Demo Clinic – Ariana',   'clinic', 'Demo address 26', 'Ariana',   'Ariana',   36.874500, 10.185600, NULL, 0, 1),
(27, 'Demo Clinic – Sousse',   'clinic', 'Demo address 27', 'Sousse',   'Sousse',   35.837600, 10.626900, NULL, 1, 1),
(28, 'Demo Clinic – Sfax',     'clinic', 'Demo address 28', 'Sfax',     'Sfax',     34.752600, 10.750300, NULL, 0, 1),
(29, 'Demo Clinic – Nabeul',   'clinic', 'Demo address 29', 'Nabeul',   'Nabeul',   36.468100, 10.727600, NULL, 0, 1),
(30, 'Demo Clinic – Bizerte',  'clinic', 'Demo address 30', 'Bizerte',  'Bizerte',  37.286400,  9.863900, NULL, 0, 1),
(31, 'Demo Clinic – Gabès',    'clinic', 'Demo address 31', 'Gabès',    'Gabès',    33.893500, 10.088200, NULL, 0, 1),
(32, 'Demo Clinic – Médenine', 'clinic', 'Demo address 32', 'Médenine', 'Médenine', 33.366900, 10.495500, NULL, 0, 1),
-- Demonstration donation centers (fictional) – no doctors, to show the "0" of 0..*
(33, 'Demo Blood Donation Center – Tunis',  'donation_center', 'Demo address 33', 'Tunis',  'Tunis',  36.795500, 10.194500, NULL, 0, 1),
(34, 'Demo Blood Donation Center – Sousse', 'donation_center', 'Demo address 34', 'Sousse', 'Sousse', 35.814600, 10.649900, NULL, 0, 1),
(35, 'Demo Blood Donation Center – Sfax',   'donation_center', 'Demo address 35', 'Sfax',   'Sfax',   34.729600, 10.773300, NULL, 0, 1),
(36, 'Demo Blood Donation Center – Gafsa',  'donation_center', 'Demo address 36', 'Gafsa',  'Gafsa',  34.414000,  8.797200, NULL, 0, 1);

-- ---------------------------------------------------------------------
-- Demonstration doctors (fictional) – last names "Demo-NN" on purpose
-- ---------------------------------------------------------------------
INSERT INTO doctor (first_name, last_name, specialty, phone, email, facility_id) VALUES
('Amine',    'Demo-01', 'General medicine', NULL, 'demo.doctor01@example.com',  1),
('Sarra',    'Demo-02', 'Cardiology',       NULL, 'demo.doctor02@example.com',  2),
('Youssef',  'Demo-03', 'Pediatrics',       NULL, 'demo.doctor03@example.com',  3),
('Ines',     'Demo-04', 'Gynecology',       NULL, 'demo.doctor04@example.com',  4),
('Mehdi',    'Demo-05', 'Orthopedics',      NULL, 'demo.doctor05@example.com',  5),
('Rim',      'Demo-06', 'General medicine', NULL, 'demo.doctor06@example.com',  6),
('Karim',    'Demo-07', 'Neurology',        NULL, 'demo.doctor07@example.com',  7),
('Nour',     'Demo-08', 'Pediatrics',       NULL, 'demo.doctor08@example.com',  8),
('Hatem',    'Demo-09', 'General medicine', NULL, 'demo.doctor09@example.com',  9),
('Salma',    'Demo-10', 'Cardiology',       NULL, 'demo.doctor10@example.com', 10),
('Walid',    'Demo-11', 'General medicine', NULL, 'demo.doctor11@example.com', 11),
('Emna',     'Demo-12', 'Neurology',        NULL, 'demo.doctor12@example.com', 12),
('Fares',    'Demo-13', 'Ophthalmology',    NULL, 'demo.doctor13@example.com', 13),
('Asma',     'Demo-14', 'Gynecology',       NULL, 'demo.doctor14@example.com', 14),
('Nizar',    'Demo-15', 'Cardiology',       NULL, 'demo.doctor15@example.com', 15),
('Hela',     'Demo-16', 'Pediatrics',       NULL, 'demo.doctor16@example.com', 16),
('Anis',     'Demo-17', 'Orthopedics',      NULL, 'demo.doctor17@example.com', 17),
('Mariem',   'Demo-18', 'General medicine', NULL, 'demo.doctor18@example.com', 18),
('Sofiene',  'Demo-19', 'Cardiology',       NULL, 'demo.doctor19@example.com', 19),
('Olfa',     'Demo-20', 'Dermatology',      NULL, 'demo.doctor20@example.com', 20),
('Bilel',    'Demo-21', 'General medicine', NULL, 'demo.doctor21@example.com', 21),
('Yosra',    'Demo-22', 'Pediatrics',       NULL, 'demo.doctor22@example.com', 22),
('Skander',  'Demo-23', 'General medicine', NULL, 'demo.doctor23@example.com', 23),
('Dorra',    'Demo-24', 'General medicine', NULL, 'demo.doctor24@example.com', 24),
('Hichem',   'Demo-25', 'Dermatology',      NULL, 'demo.doctor25@example.com', 25),
('Amel',     'Demo-26', 'Dentistry',        NULL, 'demo.doctor26@example.com', 25),
('Zied',     'Demo-27', 'Ophthalmology',    NULL, 'demo.doctor27@example.com', 26),
('Syrine',   'Demo-28', 'Dermatology',      NULL, 'demo.doctor28@example.com', 26),
('Majdi',    'Demo-29', 'Cardiology',       NULL, 'demo.doctor29@example.com', 27),
('Lobna',    'Demo-30', 'Dentistry',        NULL, 'demo.doctor30@example.com', 27),
('Ramzi',    'Demo-31', 'Dermatology',      NULL, 'demo.doctor31@example.com', 28),
('Hajer',    'Demo-32', 'Ophthalmology',    NULL, 'demo.doctor32@example.com', 28),
('Tarek',    'Demo-33', 'Dentistry',        NULL, 'demo.doctor33@example.com', 29),
('Wafa',     'Demo-34', 'Pediatrics',       NULL, 'demo.doctor34@example.com', 29),
('Marwen',   'Demo-35', 'Cardiology',       NULL, 'demo.doctor35@example.com', 30),
('Sana',     'Demo-36', 'Neurology',        NULL, 'demo.doctor36@example.com', 30),
('Aymen',    'Demo-37', 'Dentistry',        NULL, 'demo.doctor37@example.com', 31),
('Rania',    'Demo-38', 'Dermatology',      NULL, 'demo.doctor38@example.com', 31),
('Slim',     'Demo-39', 'Orthopedics',      NULL, 'demo.doctor39@example.com', 32),
('Ghada',    'Demo-40', 'Ophthalmology',    NULL, 'demo.doctor40@example.com', 32);
