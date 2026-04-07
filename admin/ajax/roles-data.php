<?php
// admin/ajax/roles-data.php

require_once __DIR__ . '/../../includes/auth.php';
authorize('Super Admin');

header('Content-Type: application/json');

$total_count = $pdo->query("SELECT COUNT(*) FROM roles")->fetchColumn();

$limit = isset($_GET['length']) ? (int)$_GET['length'] : 10;
if ($limit < 1) $limit = 1000000; // Datatables sends -1 for All
$offset = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$stmt = $pdo->prepare("SELECT * FROM roles ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();
$roles = $stmt->fetchAll();

$data = [];
foreach ($roles as $r) {
    $perms = json_decode($r['permissions'], true) ?? [];
    $perm_list = [];
    foreach ($perms as $key => $val) {
        if ($val) $perm_list[] = '<span style="font-size:9px; background:#e0e7ff; color:#4338ca; padding:2px 6px; border-radius:4px; margin-right:3px;">' . $key . '</span>';
    }
    
    $display_perms = empty($perm_list) ? '<span style="color:#cbd5e1; font-size:10px;">No explicit perms</span>' : implode(' ', array_slice($perm_list, 0, 4)) . (count($perm_list) > 4 ? '...' : '');

    $data[] = [
        "role_name" => "<strong>" . htmlspecialchars($r['role_name']) . "</strong>",
        "perms" => $display_perms,
        "actions" => '
            <div style="display:flex; gap:5px;">
                <button class="btn-primary" onclick="editRole(' . $r['id'] . ')" style="background:#6366f1; padding:5px 10px; font-size:12px;">
                    <i class="fas fa-lock-open"></i>
                </button>
                <button class="btn-primary" onclick="deleteRole(' . $r['id'] . ')" style="background:#ef4444; padding:5px 10px; font-size:12px;">
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
