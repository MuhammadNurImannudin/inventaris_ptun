<?php
/**
 * config/database.php
 * Database Configuration - PTUN Banjarmasin Inventory System
 * 
 * LOKASI FILE: config/database.php (REPLACE)
 */

// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$db   = "inventaris_ptun";

// Create connection with error handling
try {
    $conn = new mysqli($host, $user, $pass, $db);
    
    // Check connection
    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }
    
    // Set charset to UTF8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Error koneksi database: " . $e->getMessage());
}

// Helper function for safe database queries
function executeQuery($query, $params = [], $types = '') {
    global $conn;
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare statement failed: " . $conn->error);
    }
    
    if ($params && $types) {
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    
    return $stmt;
}

// Helper function for safe select queries
function selectQuery($query, $params = [], $types = '') {
    $stmt = executeQuery($query, $params, $types);
    return $stmt->get_result();
}

// Base URL configuration - PENTING!
define('BASE_URL', 'http://localhost/inventaris-ptun/');

// App configuration
define('APP_NAME', 'Sistem Inventaris PTUN Banjarmasin');
define('APP_VERSION', '1.0.0');

// Upload configuration  
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB

// Create necessary directories
$uploadDirs = [
    UPLOAD_PATH . 'barang',
    UPLOAD_PATH . 'users',
    UPLOAD_PATH . 'documents'
];

foreach ($uploadDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Helper function to get base URL
function base_url($path = '') {
    return BASE_URL . $path;
}
?>