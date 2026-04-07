<?php
// admin/ajax/gallery-data.php

require_once __DIR__ . '/../../includes/auth.php';
check_login();

header('Content-Type: application/json');

$limit = isset($_GET['length']) ? intval($_GET['length']) : 10;
if ($limit < 1) $limit = 1000000; // Fix -1
$offset = isset($_GET['start']) ? intval($_GET['start']) : 0;
$total_count = $pdo->query("SELECT COUNT(*) FROM galleries")->fetchColumn();

// Simple search logic
$search = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
$where = "";
$params = [];
if ($search) {
    $where = " WHERE title LIKE :search OR category LIKE :search ";
    $params[':search'] = "%$search%";
}

$filtered_count = $total_count;
if ($search) {
    $filtered_stmt = $pdo->prepare("SELECT COUNT(*) FROM galleries $where");
    $filtered_stmt->execute($params);
    $filtered_count = $filtered_stmt->fetchColumn();
}

$stmt = $pdo->prepare("SELECT * FROM galleries $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$items = $stmt->fetchAll();

$data = [];
foreach ($items as $i) {
    $img_tag = $i['image_path'] ? '<img src="../' . $i['image_path'] . '" style="width:80px; height:50px; object-fit:cover; border-radius:5px; border:1px solid #e2e8f0;">' : 'No Image';

    $data[] = [
        "thumbnail" => $img_tag,
        "details" => '<strong>' . htmlspecialchars($i['title']) . '</strong><br><small style="color:#64748b;">' . htmlspecialchars($i['category']) . '</small>',
        "actions" => '
            <div style="display:flex; gap:5px;">
                <button class="btn-primary" onclick="editItem(' . $i['id'] . ')" style="background:#6366f1; padding:5px 10px; font-size:12px;">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-primary" onclick="deleteItem(' . $i['id'] . ')" style="background:#ef4444; padding:5px 10px; font-size:12px;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>'
    ];
}

$response = [
    "draw" => intval($_GET['draw'] ?? 1),
    "recordsTotal" => intval($total_count),
    "recordsFiltered" => intval($filtered_count),
    "data" => $data
];

echo json_encode($response);
exit;

?>
