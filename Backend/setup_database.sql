-- AgroSite Database Setup Script
-- Run this script in phpMyAdmin or MySQL command line

-- Create the database
CREATE DATABASE IF NOT EXISTS agrosite_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE agrosite_db;

-- Note: Laravel migrations will create the tables automatically
-- Just ensure this database exists before running: php artisan migrate

