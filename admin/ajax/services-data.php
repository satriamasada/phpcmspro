<?php
// admin/ajax/services-data.php

require_once __DIR__ . '/../../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;

header('Content-Type: application/json');

$total_count = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();

$limit = isset($_GET['length']) ? (int)$_GET['length'] : 10;
if ($limit < 1) $limit = 1000000; // Datatables sends -1 for All
$offset = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$stmt = $pdo->prepare("SELECT * FROM services ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();
$services = $stmt->fetchAll();

$data = [];
foreach ($services as $s) {
    $data[] = [
        "icon" => '<i class="' . ($s['icon'] ?: 'fas fa-cog') . '" style="font-size:1.5rem; color:var(--primary);"></i>',
        "title" => "<strong>" . htmlspecialchars($s['title']) . "</strong>",
        "desc_short" => '<span style="font-size:0.8rem; color:#64748b;">' . htmlspecialchars(substr($s['description'], 0, 80)) . '...</span>',
        "actions" => '
            <div style="display:flex; gap:5px;">
                <button class="btn-primary" onclick="editService(' . $s['id'] . ')" style="background:#6366f1; padding:5px 10px; font-size:12px;">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-primary" onclick="deleteService(' . $s['id'] . ')" style="background:#ef4444; padding:5px 10px; font-size:12px;">
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
