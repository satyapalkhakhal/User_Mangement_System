<?php
// Database configuration
$dbConfig = [
    'host' => 'localhost',
    'name' => 'user_management',
    'user' => 'magento2',
    'pass' => 'admin@123',
    'charset' => 'utf8mb4'
];

try {
    echo "Attempting to connect to MySQL...\n";
    
    // Connect without selecting database first
    $dsn = "mysql:host={$dbConfig['host']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully!\n";
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbConfig['name']}`");
    echo "Database '{$dbConfig['name']}' created or exists.\n";
    
    // Connect to the specific database
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass']);
    
    
    // Create user_data table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `user_data` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL UNIQUE,
            `phone` VARCHAR(20) NOT NULL,
            `role` VARCHAR(50) NOT NULL,
            `joined_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `address` TEXT DEFAULT NULL,
            `photo` VARCHAR(255) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "Table 'user_data' created successfully.\n";
    
    echo "Database setup completed successfully!\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Connection details used:\n";
    print_r($dbConfig);
    
    // Additional troubleshooting for common errors
    if (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "\nTROUBLESHOOTING:\n";
        echo "1. Verify username/password are correct\n";
        echo "2. Check if user '{$dbConfig['user']}' has privileges:\n";
        echo "   GRANT ALL PRIVILEGES ON {$dbConfig['name']}.* TO '{$dbConfig['user']}'@'localhost';\n";
        echo "   FLUSH PRIVILEGES;\n";
    }
}