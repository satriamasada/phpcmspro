<?php
// admin/portfolio.php

require_once __DIR__ . '/../includes/auth.php';
check_login();

$page_title = 'Manage Portfolio';
$current_page = 'portfolio';

// Handle Fetch for Edit
if (isset($_GET['fetch_id'])) {
    header('Content-Type: application/json');
    $id = $_GET['fetch_id'];
    $stmt = $pdo->prepare("SELECT * FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    header('Content-Type: application/json');
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["status" => "success"]);
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add_portfolio']) || isset($_POST['edit_portfolio']))) {
    header('Content-Type: application/json');
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    
    $image_url = $_POST['existing_image'] ?? 'https://via.placeholder.com/400x300';
    
    // File Upload
    if (isset($_FILES['portfolio_image']) && $_FILES['portfolio_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/portfolio/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $ext = pathinfo($_FILES['portfolio_image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('port_') . '.' . $ext;
        
        if (move_uploaded_file($_FILES['portfolio_image']['tmp_name'], $upload_dir . $filename)) {
            $image_url = 'uploads/portfolio/' . $filename;
        }
    }

    if (isset($_POST['add_portfolio'])) {
        $stmt = $pdo->prepare("INSERT INTO portfolio (title, category, description, image_url) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $category, $description, $image_url]);
    } else {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE portfolio SET title = ?, category = ?, description = ?, image_url = ? WHERE id = ?");
        $stmt->execute([$title, $category, $description, $image_url, $id]);
    }
    
    echo json_encode(["status" => "success"]);
    exit;
}

include 'header.php';
include 'portfolio-content.php';
include 'footer.php';
?>
