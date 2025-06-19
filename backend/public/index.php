<?php
require_once __DIR__ . '/../routes/api.php';

// Set headers
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Parse request
$request = [
    'method' => $_SERVER['REQUEST_METHOD'],
    'path' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
    'headers' => getallheaders(),
    'body' => json_decode(file_get_contents('php://input'), true) ?? []
];

// Database connection
$dbConfig = require_once __DIR__ . '/../config/database.php';


try {
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset={$dbConfig['charset']}",
        $dbConfig['user'],
        $dbConfig['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "FAILED";
    Response::error('Database connection failed: ' . $e->getMessage(), 500);
}

// Handle request
handleRoutes($pdo, $request);