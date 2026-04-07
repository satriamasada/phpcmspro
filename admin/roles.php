<?php
// admin/roles.php

require_once __DIR__ . '/../includes/auth.php';
authorize('Super Admin');

$page_title = 'Roles & Permissions';
$current_page = 'roles';

// Handle Fetch for Edit
if (isset($_GET['fetch_id'])) {
    header('Content-Type: application/json');
    $id = $_GET['fetch_id'];
    $stmt = $pdo->prepare("SELECT * FROM roles WHERE id = ?");
    $stmt->execute([$id]);
    $role = $stmt->fetch();
    if ($role) {
        $role['permissions'] = json_decode($role['permissions'], true) ?: [];
    }
    echo json_encode($role);
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    header('Content-Type: application/json');
    $id = $_GET['delete'];
    
    // Check if any user has this role
    $count = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role_id = ?");
    $count->execute([$id]);
    if ($count->fetchColumn() > 0) {
        echo json_encode(["status" => "error", "message" => "Cannot delete role assigned to active users!"]);
        exit;
    }
    
    $stmt = $pdo->prepare("DELETE FROM roles WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(["status" => "success"]);
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['add_role']) || isset($_POST['edit_role']))) {
    header('Content-Type: application/json');
    $role_name = $_POST['role_name'];
    $perms = $_POST['perms'] ?? [];
    
    // If 'all' is checked, we can store it as {"all": true}
    $permissions_json = json_encode($perms);

    if (isset($_POST['add_role'])) {
        $stmt = $pdo->prepare("INSERT INTO roles (role_name, permissions) VALUES (?, ?)");
        $stmt->execute([$role_name, $permissions_json]);
    } else {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE roles SET role_name = ?, permissions = ? WHERE id = ?");
        $stmt->execute([$role_name, $permissions_json, $id]);
    }
    
    echo json_encode(["status" => "success"]);
    exit;
}

include 'header.php';
include 'roles-content.php';
include 'footer.php';
?>
