<?php
// admin/ajax/portfolio-data.php

require_once __DIR__ . '/../../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;

header('Content-Type: application/json');

$limit = isset($_GET['length']) ? intval($_GET['length']) : 10;
if ($limit < 1) $limit = 1000000; // Fix -1
$offset = isset($_GET['start']) ? intval($_GET['start']) : 0;
$total_count = $pdo->query("SELECT COUNT(*) FROM portfolio")->fetchColumn();

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
    $filtered_stmt = $pdo->prepare("SELECT COUNT(*) FROM portfolio $where");
    $filtered_stmt->execute($params);
    $filtered_count = $filtered_stmt->fetchColumn();
}

$stmt = $pdo->prepare("SELECT * FROM portfolio $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$projects = $stmt->fetchAll();

$data = [];
foreach ($projects as $p) {
    $img_url = $p['image_url'];
    if($img_url && strpos($img_url, 'http') === false) {
        $img_url = '../' . $img_url; // Go up from ajax/ to root
    }
    
    $img = $img_url ? '<img src="' . $img_url . '" style="width:70px; height:45px; object-fit:cover; border-radius:6px; border:1px solid #eee;">' : '<i class="far fa-image" style="color:#cbd5e1; font-size:24px;"></i>';
    
    $data[] = [
        "image" => $img,
        "title" => "<strong>" . htmlspecialchars($p['title']) . "</strong>",
        "category" => '<span class="badge" style="background:#f1f5f9; color:#475569; padding:4px 10px; border-radius:20px; font-size:11px;">' . htmlspecialchars($p['category']) . '</span>',
        "actions" => '
            <div style="display:flex; gap:5px;">
                <button class="btn-primary" onclick="editPortfolio(' . $p['id'] . ')" style="background:#6366f1; padding:5px 10px; font-size:12px;">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-primary" onclick="deletePortfolio(' . $p['id'] . ')" style="background:#ef4444; padding:5px 10px; font-size:12px;">
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
