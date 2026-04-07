<?php
// admin/services.php

require_once __DIR__ . '/../includes/auth.php';
check_login();

$page_title = 'Manage Services';
$current_page = 'services';

// Handle Fetch for Edit
if (isset($_GET['fetch_id'])) {
    header('Content-Type: application/json');
    $id = $_GET['fetch_id'];
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    header('Content-Type: application/json');
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["status" => "success"]);
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add_service']) || isset($_POST['edit_service']))) {
    header('Content-Type: application/json');
    $title = $_POST['title'];
    $description = $_POST['description'];
    $icon = $_POST['icon'];

    if (isset($_POST['add_service'])) {
        $stmt = $pdo->prepare("INSERT INTO services (title, description, icon) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $icon]);
    } else {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE services SET title = ?, description = ?, icon = ? WHERE id = ?");
        $stmt->execute([$title, $description, $icon, $id]);
    }
    
    echo json_encode(["status" => "success"]);
    exit;
}

include 'header.php';
include 'services-content.php';
include 'footer.php';
?>
