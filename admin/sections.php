<?php
// admin/sections.php

require_once __DIR__ . '/../includes/auth.php';
check_login();

$page_title = 'Page Sections';
$current_page = 'sections';

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_section'])) {
    $section_key = $_POST['section_key'];
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $content = $_POST['content'] ?? '';

    $stmt = $pdo->prepare("UPDATE sections SET title = ?, subtitle = ?, content = ? WHERE section_key = ?");
    $stmt->execute([$title, $subtitle, $content, $section_key]);
    
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        echo json_encode(["status" => "success"]);
        exit;
    }
}

include 'header.php';
include 'sections-content.php';
include 'footer.php';
?>
