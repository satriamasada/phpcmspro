<?php
// admin/news.php

require_once __DIR__ . '/../includes/auth.php';
check_login();

$page_title = 'Manage News & Articles';
$current_page = 'news';

// Handle Fetch for Edit
if (isset($_GET['fetch_id'])) {
    header('Content-Type: application/json');
    $id = $_GET['fetch_id'];
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    header('Content-Type: application/json');
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["status" => "success"]);
    exit;
}

// Handle Save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add_news']) || isset($_POST['edit_news']))) {
    header('Content-Type: application/json');
    $title = $_POST['title'];
    $content = $_POST['content'];
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    
    // Cleanup slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    
    $thumbnail_url = $_POST['existing_thumbnail'] ?? '';
    
    // File Upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/news/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('news_') . '.' . $ext;
        
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $filename)) {
            $thumbnail_url = 'uploads/news/' . $filename;
        }
    }

    if (isset($_POST['add_news'])) {
        $stmt = $pdo->prepare("INSERT INTO news (title, slug, content, is_published, author_id, featured_image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $content, $is_published, $_SESSION['user_id'], $thumbnail_url]);
    } else {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE news SET title = ?, slug = ?, content = ?, is_published = ?, featured_image = ? WHERE id = ?");
        $stmt->execute([$title, $slug, $content, $is_published, $thumbnail_url, $id]);
    }
    
    echo json_encode(["status" => "success"]);
    exit;
}

include 'header.php';
include 'news-content.php';
include 'footer.php';
?>
