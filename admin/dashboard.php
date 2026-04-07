<?php
// admin/dashboard.php

require_once __DIR__ . '/../includes/auth.php';
check_login();

$page_title = 'Dashboard';
$current_page = 'dashboard';

// Simple stats
$stats = [
    'services' => $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn(),
    'portfolio' => $pdo->query("SELECT COUNT(*) FROM portfolio")->fetchColumn(),
    'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'news' => $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn(),
    'messages' => $pdo->query("SELECT COUNT(*) FROM contact_leads WHERE status = 'unread'")->fetchColumn()
];

include 'header.php';
// Initial load for SPA
include 'dashboard-content.php';
include 'footer.php';
?>

