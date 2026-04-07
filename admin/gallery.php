<?php
// admin/gallery.php

require_once __DIR__ . '/../includes/auth.php';
authorize('Super Admin', 'Admin');

$page_title = 'Gallery Management';
$current_page = 'gallery';

// Handle Fetch for Edit
if (isset($_GET['fetch_id'])) {
    header('Content-Type: application/json');
    $id = $_GET['fetch_id'];
    $stmt = $pdo->prepare("SELECT * FROM galleries WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    header('Content-Type: application/json');
    $id = $_GET['delete'];
    
    // Retrieve image path for cleanup
    $stmt = $pdo->prepare("SELECT image_path FROM galleries WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();
    if($img && file_exists(__DIR__ . '/../' . $img)) unlink(__DIR__ . '/../' . $img);

    $stmt = $pdo->prepare("DELETE FROM galleries WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["status" => "success"]);
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add_gallery']) || isset($_POST['edit_gallery']))) {
    header('Content-Type: application/json');
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'] ?? '';
    $id = $_POST['id'] ?? '';

    $image_path = $_POST['existing_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = __DIR__ . '/../uploads/gallery/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('gl_') . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename);
        $image_path = 'uploads/gallery/' . $filename;
    }

    if (isset($_POST['add_gallery'])) {
        $stmt = $pdo->prepare("INSERT INTO galleries (title, image_path, category, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $image_path, $category, $description]);
    } else {
        $stmt = $pdo->prepare("UPDATE galleries SET title = ?, image_path = ?, category = ?, description = ? WHERE id = ?");
        $stmt->execute([$title, $image_path, $category, $description, $id]);
    }
    
    echo json_encode(["status" => "success"]);
    exit;
}

include 'header.php';
include 'gallery-content.php';
include 'footer.php';
?>
