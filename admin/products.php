<?php
// admin/products.php

require_once __DIR__ . '/../includes/auth.php';
check_login();

$page_title = 'Manage Products';
$current_page = 'products';

// Handle Fetch for Edit
if (isset($_GET['fetch_id'])) {
    header('Content-Type: application/json');
    $id = $_GET['fetch_id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    header('Content-Type: application/json');
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["status" => "success"]);
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add_product']) || isset($_POST['edit_product']))) {
    header('Content-Type: application/json');
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $external_link = $_POST['external_link'];
    
    $image_url = $_POST['existing_image'] ?? 'https://via.placeholder.com/400x300';
    
    // File Upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/products/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $ext = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('prod_') . '.' . $ext;
        
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $upload_dir . $filename)) {
            $image_url = 'uploads/products/' . $filename;
        }
    }

    if (isset($_POST['add_product'])) {
        $stmt = $pdo->prepare("INSERT INTO products (name, price, description, external_link, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $price, $description, $external_link, $image_url]);
    } else {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ?, external_link = ?, image_url = ? WHERE id = ?");
        $stmt->execute([$name, $price, $description, $external_link, $image_url, $id]);
    }
    
    echo json_encode(["status" => "success"]);
    exit;
}

include 'header.php';
include 'products-content.php';
include 'footer.php';
?>
