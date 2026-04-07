<?php
// admin/testimonials.php

require_once __DIR__ . '/../includes/auth.php';
authorize('Super Admin', 'Admin');

$page_title = 'Client Testimonials';
$current_page = 'testimonials';

// Handle Fetch for Edit
if (isset($_GET['fetch_id'])) {
    header('Content-Type: application/json');
    $id = $_GET['fetch_id'];
    $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    header('Content-Type: application/json');
    $id = $_GET['delete'];
    
    // Retrieve image path for cleanup
    $stmt = $pdo->prepare("SELECT client_image FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();
    if($img && file_exists(__DIR__ . '/../' . $img)) unlink(__DIR__ . '/../' . $img);

    $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["status" => "success"]);
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add_testimonial']) || isset($_POST['edit_testimonial']))) {
    header('Content-Type: application/json');
    $name = $_POST['client_name'];
    $company = $_POST['client_company'];
    $content = $_POST['content'];
    $rating = $_POST['rating'] ?? 5;
    $id = $_POST['id'] ?? '';

    $image_path = $_POST['existing_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = __DIR__ . '/../uploads/testimonials/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('cv_') . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
        $image_path = 'uploads/testimonials/' . $filename;
    }

    if (isset($_POST['add_testimonial'])) {
        $stmt = $pdo->prepare("INSERT INTO testimonials (client_name, client_company, client_image, content, rating) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $company, $image_path, $content, $rating]);
    } else {
        $stmt = $pdo->prepare("UPDATE testimonials SET client_name = ?, client_company = ?, client_image = ?, content = ?, rating = ? WHERE id = ?");
        $stmt->execute([$name, $company, $image_path, $content, $rating, $id]);
    }
    
    echo json_encode(["status" => "success"]);
    exit;
}

include 'header.php';
include 'testimonials-content.php';
include 'footer.php';
?>
