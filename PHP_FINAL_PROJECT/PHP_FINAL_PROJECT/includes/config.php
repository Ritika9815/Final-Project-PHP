<?php
/**
 * Database Configuration
 * This file contains database connection settings and initializes PDO connection
 */

// Database configuration for SQLite (file-based database)
$database_path = __DIR__ . '/../database.sqlite';

try {
    // Create PDO connection with SQLite
    $pdo = new PDO("sqlite:" . $database_path);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Enable foreign key constraints
    $pdo->exec("PRAGMA foreign_keys = ON");
    
    // Create tables if they don't exist
    createTables($pdo);
    
} catch (PDOException $e) {
    // Log error and show user-friendly message
    error_log("Database Connection Error: " . $e->getMessage());
    die("Database connection failed. Please contact the administrator.");
}

/**
 * Create database tables if they don't exist
 */
function createTables($pdo) {
    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            profile_image TEXT DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Create content table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS content (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            page TEXT NOT NULL,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Check if default content exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM content");
    $stmt->execute();
    $contentCount = $stmt->fetchColumn();
    
    // Insert default content if table is empty
    if ($contentCount == 0) {
        $pdo->exec("
            INSERT INTO content (page, title, content, created_at, updated_at) VALUES
            ('home', 'Welcome to Our Platform', 'Experience the power of modern web development with our comprehensive PHP-based platform. Built with security, scalability, and user experience in mind, our application showcases the best practices in CRUD operations, user authentication, and content management.', datetime('now'), datetime('now')),
            ('about', 'About Our Platform', 'This platform demonstrates a complete web application built with PHP, SQLite, and Bootstrap. It features secure user authentication, comprehensive CRUD operations, file upload capabilities, and responsive design.\n\nKey technical features include:\n- Password hashing with PHP password_hash()\n- Prepared statements for SQL injection prevention\n- Input validation and sanitization\n- Session management\n- File upload security\n- Responsive Bootstrap design\n- Cross-browser compatibility\n\nThe application serves as an excellent foundation for building blogs, content management systems, or social media platforms.', datetime('now'), datetime('now'))
        ");
    }
    
    // Check if default admin user exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute(['admin@example.com']);
    $userCount = $stmt->fetchColumn();
    
    // Insert default admin user if it doesn't exist
    if ($userCount == 0) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, created_at) VALUES (?, ?, ?, ?, datetime('now'))");
        $stmt->execute(['Admin', 'User', 'admin@example.com', $hashedPassword]);
    }
}

// Create uploads directory if it doesn't exist
$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}
?>
