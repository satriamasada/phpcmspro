<?php
// admin/ajax/products-data.php

require_once __DIR__ . '/../../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;

header('Content-Type: application/json');

$limit = isset($_GET['length']) ? intval($_GET['length']) : 10;
if ($limit < 1) $limit = 1000000; // Fix -1
$offset = isset($_GET['start']) ? intval($_GET['start']) : 0;
$total_count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

// Simple search logic
$search = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
$where = "";
$params = [];
if ($search) {
    $where = " WHERE name LIKE :search OR description LIKE :search ";
    $params[':search'] = "%$search%";
}

$filtered_count = $total_count;
if ($search) {
    $filtered_stmt = $pdo->prepare("SELECT COUNT(*) FROM products $where");
    $filtered_stmt->execute($params);
    $filtered_count = $filtered_stmt->fetchColumn();
}

$stmt = $pdo->prepare("SELECT * FROM products $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$products = $stmt->fetchAll();

$data = [];
foreach ($products as $p) {
    $img_url = $p['image_url'];
    if($img_url && strpos($img_url, 'http') === false) {
        $img_url = '../' . $img_url; // Up from ajax/
    }
    
    $img = $img_url ? '<img src="' . $img_url . '" style="width:60px; height:60px; object-fit:cover; border-radius:5px; border:1px solid #eee;">' : '<i class="fas fa-box" style="color:#cbd5e1; font-size:24px;"></i>';
    
    $data[] = [
        "thumbnail" => $img,
        "name" => "<strong>" . htmlspecialchars($p['name']) . "</strong>",
        "price" => '<span style="color:var(--primary); font-weight:700;">Rp ' . number_format($p['price'], 0, ',', '.') . '</span>',
        "actions" => '
            <div style="display:flex; gap:5px;">
                <button class="btn-primary" onclick="editProduct(' . $p['id'] . ')" style="background:#6366f1; padding:5px 10px; font-size:12px;">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-primary" onclick="deleteProduct(' . $p['id'] . ')" style="background:#ef4444; padding:5px 10px; font-size:12px;">
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
