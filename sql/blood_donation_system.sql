-- SQL script to create the blood donation system database and its tables

CREATE DATABASE IF NOT EXISTS blood_donation_system;
USE blood_donation_system;

-- Table for User roles
CREATE TABLE IF NOT EXISTS roles (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT
);

-- Table for user
CREATE TABLE IF NOT EXISTS user (
    CCCD VARCHAR(12) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    email VARCHAR(100),
    role_id BIGINT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Table for User Information
CREATE TABLE IF NOT EXISTS user_info (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100),
    dob DATE,
    sex VARCHAR(10),
    address VARCHAR(255)
);

-- Table for Blood Donation History
CREATE TABLE IF NOT EXISTS blood_donation_history (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    donation_date_time DATETIME,
    blood_amount INT,
    donation_location VARCHAR(255),
    notes TEXT,
    donation_type VARCHAR(50),
    reaction_after_donation VARCHAR(255),
    user_id VARCHAR(12),
    appointment_id BIGINT,
    FOREIGN KEY (user_id) REFERENCES user(CCCD),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- Table for Appointments
CREATE TABLE IF NOT EXISTS appointments (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    event_id BIGINT,
    user_cccd VARCHAR(12),
    healthcheck_id BIGINT,
    blood_donation_history_id BIGINT,
    blood_inventory_id BIGINT,
    appointment_date_time DATETIME,
    blood_amount INT,
    next_donation_eligible_date DATETIME,
    status ENUM('PENDING', 'CONFIRMED', 'CANCELED', 'COMPLETED'),
    FOREIGN KEY (event_id) REFERENCES events(id),
    FOREIGN KEY (user_cccd) REFERENCES user(CCCD),
    FOREIGN KEY (healthcheck_id) REFERENCES healthchecks(id),
    FOREIGN KEY (blood_donation_history_id) REFERENCES blood_donation_history(id),
    FOREIGN KEY (blood_inventory_id) REFERENCES blood_inventory(id)
);

-- Table for Blood Inventory
CREATE TABLE IF NOT EXISTS blood_inventory (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    blood_type VARCHAR(10),
    quantity INT,
    last_updated DATETIME,
    expiration_date DATETIME,
    appointment_id BIGINT,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- Table for Donation Units
CREATE TABLE IF NOT EXISTS donation_units (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    location VARCHAR(255),
    phone VARCHAR(15),
    email VARCHAR(100),
    unit_photo_url VARCHAR(255)
);

-- Table for Events
CREATE TABLE IF NOT EXISTS events (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    event_date DATE,
    event_start_time TIME,
    event_end_time TIME,
    max_registrations BIGINT,
    current_registrations BIGINT,
    status ENUM('DONE', 'ACTIVE', 'FULL'),
    donation_unit_id BIGINT,
    FOREIGN KEY (donation_unit_id) REFERENCES donation_units(id)
);

-- Table for FAQs
CREATE TABLE IF NOT EXISTS faqs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table for News
CREATE TABLE IF NOT EXISTS news (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    content TEXT,
    author VARCHAR(100),
    image_url VARCHAR(255),
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table for Health Checks
CREATE TABLE IF NOT EXISTS healthchecks (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    health_metrics TEXT,
    notes TEXT,
    appointment_id BIGINT,
    result ENUM('PASS', 'FAIL'),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- Insert initial data into roles
INSERT INTO roles (name, description) VALUES 
('Admin', 'Administrator with full access'),
('User', 'Regular user with limited access');

-- Insert initial data into donation_units
INSERT INTO donation_units (name, location, phone, email) VALUES 
('Unit A', 'Location A', '123456789', 'unitA@example.com'),
('Unit B', 'Location B', '987654321', 'unitB@example.com');