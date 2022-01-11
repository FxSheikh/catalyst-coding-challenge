-- Script to create the database and give user 'user' priviliges

CREATE DATABASE IF NOT EXISTS users_database;

USE users_database;

-- CREATE USER 'user'@'localhost' IDENTIFIED BY 'password';

-- GRANT ALL PRIVILEGES ON * . * TO 'user'@'localhost';

GRANT ALL PRIVILEGES ON *.* TO 'user'@'%';

FLUSH PRIVILEGES;