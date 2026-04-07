<?php
// admin/users.php

require_once __DIR__ . '/../includes/auth.php';
authorize('Super Admin');

$page_title = 'User Management';
$current_page = 'users';

// Handle Fetch for Edit
if (isset($_GET['fetch_id'])) {
    header('Content-Type: application/json');
    $id = $_GET['fetch_id'];
    $stmt = $pdo->prepare("SELECT id, username, email, full_name, role_id FROM users WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch());
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    header('Content-Type: application/json');
    $id = $_GET['delete'];
    
    // Check if deleting self
    if ($id == $_SESSION['user_id']) {
        echo json_encode(["status" => "error", "message" => "Cannot delete your own account!"]);
        exit;
    }
    
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["status" => "success"]);
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add_user']) || isset($_POST['edit_user']))) {
    header('Content-Type: application/json');
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $role_id = $_POST['role_id'];
    $password = $_POST['password']; // Plain text per user request

    if (isset($_POST['add_user'])) {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, role_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $full_name, $role_id]);
    } else {
        $id = $_POST['id'];
        
        // Only update password if not empty
        if (!empty($password)) {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ?, full_name = ?, role_id = ? WHERE id = ?");
            $stmt->execute([$username, $email, $password, $full_name, $role_id, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, full_name = ?, role_id = ? WHERE id = ?");
            $stmt->execute([$username, $email, $full_name, $role_id, $id]);
        }
    }
    
    echo json_encode(["status" => "success"]);
    exit;
}

$roles = $pdo->query("SELECT * FROM roles")->fetchAll();

include 'header.php';
include 'users-content.php';
include 'footer.php';
?>
