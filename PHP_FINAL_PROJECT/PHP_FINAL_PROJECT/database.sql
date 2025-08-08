-- PHP CRUD Application Database Schema
-- This file contains the SQL commands to create the required database structure

-- Create database (uncomment if needed)
-- CREATE DATABASE IF NOT EXISTS php_crud_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE php_crud_app;

-- Table structure for users (admin data)
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `first_name` varchar(50) NOT NULL,
    `last_name` varchar(50) NOT NULL,
    `email` varchar(100) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL,
    `profile_image` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_email` (`email`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for content (website content)
CREATE TABLE IF NOT EXISTS `content` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `page` varchar(50) NOT NULL,
    `title` varchar(255) NOT NULL,
    `content` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_page` (`page`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default content for homepage
INSERT INTO `content` (`page`, `title`, `content`, `created_at`, `updated_at`) VALUES
('home', 'Welcome to Our Platform', 'Experience the power of modern web development with our comprehensive PHP-based platform. Built with security, scalability, and user experience in mind, our application showcases the best practices in CRUD operations, user authentication, and content management.', NOW(), NOW()),
('about', 'About Our Platform', 'This platform demonstrates a complete web application built with PHP, MySQL, and Bootstrap. It features secure user authentication, comprehensive CRUD operations, file upload capabilities, and responsive design.\n\nKey technical features include:\n- Password hashing with PHP password_hash()\n- Prepared statements for SQL injection prevention\n- Input validation and sanitization\n- Session management\n- File upload security\n- Responsive Bootstrap design\n- Cross-browser compatibility\n\nThe application serves as an excellent foundation for building blogs, content management systems, or social media platforms.', NOW(), NOW());

-- Insert a default admin user (password: admin123)
-- Note: In production, you should change this password immediately
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `created_at`, `updated_at`) VALUES
('Admin', 'User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW());

-- Create indexes for better performance
CREATE INDEX `idx_users_email_password` ON `users` (`email`, `password`);
CREATE INDEX `idx_content_page_updated` ON `content` (`page`, `updated_at`);

-- Create a view for user statistics (optional)
CREATE OR REPLACE VIEW `user_stats` AS
SELECT 
    COUNT(*) as total_users,
    COUNT(CASE WHEN profile_image IS NOT NULL THEN 1 END) as users_with_images,
    MIN(created_at) as first_registration,
    MAX(created_at) as latest_registration
FROM `users`;

-- Create a view for content statistics (optional)
CREATE OR REPLACE VIEW `content_stats` AS
SELECT 
    COUNT(*) as total_content,
    COUNT(DISTINCT page) as unique_pages,
    MIN(created_at) as oldest_content,
    MAX(updated_at) as latest_update
FROM `content`;

-- Add constraints for data integrity
ALTER TABLE `users` 
ADD CONSTRAINT `chk_email_format` CHECK (email LIKE '%@%.%'),
ADD CONSTRAINT `chk_name_length` CHECK (LENGTH(first_name) >= 1 AND LENGTH(last_name) >= 1);

ALTER TABLE `content`
ADD CONSTRAINT `chk_page_values` CHECK (page IN ('home', 'about', 'contact', 'services', 'blog')),
ADD CONSTRAINT `chk_content_length` CHECK (LENGTH(title) >= 1 AND LENGTH(content) >= 1);

-- Create a procedure to clean up old sessions (if using database sessions)
DELIMITER //
CREATE PROCEDURE CleanupOldSessions()
BEGIN
    -- This would be used if storing sessions in database
    -- DELETE FROM sessions WHERE last_activity < DATE_SUB(NOW(), INTERVAL 1 HOUR);
    SELECT 'Session cleanup completed' as message;
END //
DELIMITER ;

-- Create a function to get user count
DELIMITER //
CREATE FUNCTION GetUserCount() 
RETURNS INT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE user_count INT DEFAULT 0;
    SELECT COUNT(*) INTO user_count FROM users;
    RETURN user_count;
END //
DELIMITER ;

-- Create a trigger to log user registrations (optional)
CREATE TABLE IF NOT EXISTS `user_activity_log` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) DEFAULT NULL,
    `action` varchar(50) NOT NULL,
    `description` text,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_action` (`action`),
    KEY `idx_created_at` (`created_at`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Trigger for user registration logging
DELIMITER //
CREATE TRIGGER user_registration_log
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    INSERT INTO user_activity_log (user_id, action, description, created_at)
    VALUES (NEW.id, 'REGISTER', CONCAT('User registered: ', NEW.email), NOW());
END //
DELIMITER ;

-- Trigger for user update logging
DELIMITER //
CREATE TRIGGER user_update_log
AFTER UPDATE ON users
FOR EACH ROW
BEGIN
    INSERT INTO user_activity_log (user_id, action, description, created_at)
    VALUES (NEW.id, 'UPDATE', CONCAT('User profile updated: ', NEW.email), NOW());
END //
DELIMITER ;

-- Add comments to tables for documentation
ALTER TABLE `users` COMMENT = 'Stores user account information including authentication data';
ALTER TABLE `content` COMMENT = 'Stores website content for different pages';
ALTER TABLE `user_activity_log` COMMENT = 'Logs user activities for audit purposes';

-- Display table information
SHOW TABLES;
DESCRIBE users;
DESCRIBE content;
DESCRIBE user_activity_log;

-- Show the current database schema
SELECT 
    TABLE_NAME,
    TABLE_COMMENT,
    TABLE_ROWS,
    CREATE_TIME
FROM 
    INFORMATION_SCHEMA.TABLES 
WHERE 
    TABLE_SCHEMA = DATABASE()
ORDER BY 
    TABLE_NAME;
