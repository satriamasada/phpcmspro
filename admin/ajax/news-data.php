<?php
// admin/ajax/news-data.php

require_once __DIR__ . '/../../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;

header('Content-Type: application/json');

$limit = isset($_GET['length']) ? intval($_GET['length']) : 10;
if ($limit < 1) $limit = 1000000; // Fix -1
$offset = isset($_GET['start']) ? intval($_GET['start']) : 0;
$total_count = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();

// Simple search logic
$search = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
$where = "";
$params = [];
if ($search) {
    $where = " WHERE n.title LIKE :search OR n.content LIKE :search ";
    $params[':search'] = "%$search%";
}

$filtered_count = $total_count;
if ($search) {
    $filtered_stmt = $pdo->prepare("SELECT COUNT(*) FROM news n $where");
    $filtered_stmt->execute($params);
    $filtered_count = $filtered_stmt->fetchColumn();
}

$stmt = $pdo->prepare("SELECT n.*, u.full_name as author FROM news n LEFT JOIN users u ON n.author_id = u.id $where ORDER BY n.created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$articles = $stmt->fetchAll();

$data = [];
foreach ($articles as $n) {
    $status_label = ($n['is_published']) ? '<span style="color:#10b981;">Published</span>' : '<span style="color:#64748b;">Draft</span>';
    
    $thumb_url = $n['featured_image'];
    if($thumb_url && strpos($thumb_url, 'http') === false) {
        $thumb_url = '../' . $thumb_url; // Go up from ajax/ to root
    }
    
    $thumb = $thumb_url ? '<img src="' . $thumb_url . '" style="width:60px; height:40px; object-fit:cover; border-radius:5px; border:1px solid #eee;">' : '<i class="far fa-image" style="color:#cbd5e1; font-size:24px;"></i>';
    
    $data[] = [
        "thumbnail" => $thumb,
        "title" => "<strong>" . htmlspecialchars($n['title']) . "</strong>",
        "status" => $status_label,
        "actions" => '
            <div style="display:flex; gap:5px;">
                <button class="btn-primary" onclick="editNews(' . $n['id'] . ')" style="background:#6366f1; padding:5px 10px; font-size:12px;">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn-primary" onclick="deleteNews(' . $n['id'] . ')" style="background:#ef4444; padding:5px 10px; font-size:12px;">
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
