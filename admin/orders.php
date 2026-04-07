<?php
// admin/orders.php

require_once __DIR__ . '/../includes/auth.php';
authorize('Super Admin');

$page_title = 'Product Orders';
$current_page = 'orders';

// Handle Action (Change Status)
if (isset($_GET['status']) && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $id = $_GET['id'];
    $status = $_GET['status'];
    $stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    echo json_encode(["status" => "success"]);
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    header('Content-Type: application/json');
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["status" => "success"]);
    exit;
}

include 'header.php';
include 'orders-content.php';
include 'footer.php';
?>
