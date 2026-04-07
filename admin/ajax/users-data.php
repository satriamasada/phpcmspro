<?php
// admin/ajax/users-data.php

require_once __DIR__ . '/../../includes/auth.php';
authorize('Super Admin');

header('Content-Type: application/json');

$total_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

$limit = isset($_GET['length']) ? (int)$_GET['length'] : 10;
if ($limit < 1) $limit = 1000000; // Datatables sends -1 for All
$offset = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$stmt = $pdo->prepare("SELECT u.id, u.username, u.email, u.full_name, r.role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

$data = [];
foreach ($users as $u) {
    $role_color = '#64748b';
    if($u['role_name'] === 'Super Admin') $role_color = '#6366f1';
    if($u['role_name'] === 'Admin') $role_color = '#10b981';
    
    $role_badge = '<span class="badge" style="background:#f1f5f9; color:'.$role_color.'; border:0.5px solid '.$role_color.'; padding:4px 10px; border-radius:20px; font-weight:700; font-size:10px;">' . strtoupper($u['role_name']) . '</span>';
    
    $data[] = [
        "full_name" => "<strong>" . htmlspecialchars($u['full_name']) . "</strong>",
        "username" => '<code style="background:#f8fafc; padding:2px 5px; border-radius:4px; font-size:11px;">@' . htmlspecialchars($u['username']) . '</code>',
        "email" => '<span style="font-size:0.85rem; color:#64748b;">' . htmlspecialchars($u['email']) . '</span>',
        "role" => $role_badge,
        "actions" => '
            <div style="display:flex; gap:5px;">
                <button class="btn-primary" onclick="editUser(' . $u['id'] . ')" style="background:#6366f1; padding:5px 10px; font-size:12px;">
                    <i class="fas fa-user-edit"></i>
                </button>
                <button class="btn-primary" onclick="deleteUser(' . $u['id'] . ')" style="background:#ef4444; padding:5px 10px; font-size:12px;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>'
    ];
}

$response = [
    "draw" => intval($_GET['draw'] ?? 1),
    "recordsTotal" => intval($total_count),
    "recordsFiltered" => intval($total_count),
    "data" => $data
];

echo json_encode($response);
?>
