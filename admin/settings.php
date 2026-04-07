<?php
// admin/settings.php

require_once __DIR__ . '/../includes/auth.php';
authorize('Super Admin');

$page_title = 'Site Settings';
$current_page = 'settings';

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    header('Content-Type: application/json');
    foreach ($_POST['settings'] as $key => $value) {
        update_setting($key, $value);
    }
    echo json_encode(["status" => "success"]);
    exit;
}

include 'header.php';
include 'settings-content.php';
include 'footer.php';
?>
