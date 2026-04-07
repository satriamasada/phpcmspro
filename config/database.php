<?php
// config/database.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'belajarphpcms');
define('DB_USER', 'root');
define('DB_PASS', '');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Default fetch mode as associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("A database connection error occurred. Please contact the administrator.");
}

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/visitor_tracker.php';
?>
