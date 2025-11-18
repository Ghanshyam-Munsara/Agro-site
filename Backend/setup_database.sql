-- AgroSite Database Setup Script
-- Run this script in PostgreSQL (psql command line or pgAdmin)

-- Create the database
CREATE DATABASE agrosite_db 
WITH ENCODING 'UTF8'
LC_COLLATE='en_US.UTF-8'
LC_CTYPE='en_US.UTF-8';

-- Note: Laravel migrations will create the tables automatically
-- Just ensure this database exists before running: php artisan migrate
-- 
-- To connect to the database:
-- psql -h localhost -U your_username -d agrosite_db

