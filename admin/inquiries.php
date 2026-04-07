<?php
// admin/inquiries.php

require_once __DIR__ . '/../includes/auth.php';
check_login();

$page_title = 'Project Proposals';
$current_page = 'inquiries';

// Handle Action (Change Status)
if (isset($_GET['status']) && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $id = $_GET['id'];
    $status = $_GET['status'];
    $stmt = $pdo->prepare("UPDATE service_inquiries SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    echo json_encode(["status" => "success"]);
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    header('Content-Type: application/json');
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM service_inquiries WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["status" => "success"]);
    exit;
}

include 'header.php';
include 'inquiries-content.php';
include 'footer.php';
?>
