-- Runtime template only.
-- Render with deploy/mysql-init-ufounk.sh so credentials come from environment variables,
-- not from plaintext values committed to git.
CREATE DATABASE IF NOT EXISTS `__UFO_DB_NAME__` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '__UFO_DB_USER__'@'127.0.0.1' IDENTIFIED BY '__UFO_DB_PASSWORD__';
CREATE USER IF NOT EXISTS '__UFO_DB_USER__'@'localhost' IDENTIFIED BY '__UFO_DB_PASSWORD__';
GRANT ALL PRIVILEGES ON `__UFO_DB_NAME__`.* TO '__UFO_DB_USER__'@'127.0.0.1';
GRANT ALL PRIVILEGES ON `__UFO_DB_NAME__`.* TO '__UFO_DB_USER__'@'localhost';
FLUSH PRIVILEGES;
