-- Tạo CSDL
CREATE DATABASE blood_donation_db;
USE blood_donation_db;

-- Bảng Roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT
);

-- Bảng User Info
CREATE TABLE user_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    dob DATE,
    sex VARCHAR(10),
    address TEXT
);

-- Bảng user
CREATE TABLE user (
    cccd VARCHAR(12) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(100),
    user_info_id INT,
    role_id INT NOT NULL,
    FOREIGN KEY (user_info_id) REFERENCES user_info(id),
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Bảng Donation Unit
CREATE TABLE donation_units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location TEXT,
    phone VARCHAR(15),
    email VARCHAR(100),
    unit_photo_url VARCHAR(255)
);

-- Bảng Events
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    event_date DATE,
    event_start_time TIME,
    event_end_time TIME,
    max_registrations INT,
    current_registrations INT DEFAULT 0,
    status ENUM('DONE', 'ACTIVE', 'FULL') DEFAULT 'ACTIVE',
    donation_unit_id INT NOT NULL,
    FOREIGN KEY (donation_unit_id) REFERENCES donation_units(id)
);

-- Bảng Appointments
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT,
    user_cccd VARCHAR(12),
    appointment_date_time DATETIME,
    blood_amount INT,
    next_donation_eligible_date DATE,
    status ENUM('PENDING', 'CONFIRMED', 'CANCELED', 'COMPLETED') DEFAULT 'PENDING',
    FOREIGN KEY (event_id) REFERENCES events(id),
    FOREIGN KEY (user_cccd) REFERENCES user(cccd)
);

-- Bảng Healthcheck
CREATE TABLE healthchecks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT UNIQUE,
    health_metrics JSON,
    notes TEXT,
    result ENUM('PASS', 'FAIL'),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- Bảng Blood Donation History
CREATE TABLE blood_donation_histories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_cccd VARCHAR(12),
    donation_date_time DATETIME,
    blood_amount INT,
    donation_location VARCHAR(100),
    notes TEXT,
    donation_type VARCHAR(50),
    reaction_after_donation VARCHAR(100),
    appointment_id INT,
    FOREIGN KEY (user_cccd) REFERENCES user(cccd),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- Bảng Blood Inventory
CREATE TABLE blood_inventories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blood_type VARCHAR(10),
    quantity INT,
    last_updated DATETIME,
    expiration_date DATETIME,
    appointment_id INT,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- Bảng Password Reset Token
CREATE TABLE password_reset_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_cccd VARCHAR(12),
    token VARCHAR(255) NOT NULL,
    expiry_date DATETIME,
    FOREIGN KEY (user_cccd) REFERENCES user(cccd)
);