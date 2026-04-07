<?php
// admin/leads.php

require_once __DIR__ . '/../includes/auth.php';
check_login();

$page_title = 'Contact Leads';
$current_page = 'leads';

// Update status to read if single view/ajax
if (isset($_GET['read'])) {
    $id = $_GET['read'];
    $stmt = $pdo->prepare("UPDATE contact_leads SET status = 'read' WHERE id = ?");
    $stmt->execute([$id]);
    
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        echo json_encode(["status" => "success"]);
        exit;
    }
}

include 'header.php';
include 'leads-content.php';
include 'footer.php';
?>
