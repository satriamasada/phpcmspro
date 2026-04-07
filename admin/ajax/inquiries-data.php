<?php
// admin/ajax/inquiries-data.php

require_once __DIR__ . '/../../includes/auth.php';
check_login();

header('Content-Type: application/json');

$total_count = $pdo->query("SELECT COUNT(*) FROM service_inquiries")->fetchColumn();

// Join with services, portfolio and products to get titles

$limit = isset($_GET['length']) ? (int)$_GET['length'] : 10;
if ($limit < 1) $limit = 1000000; // Datatables sends -1 for All
$offset = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$stmt = $pdo->prepare("
    SELECT si.*, 
           s.title as service_title, 
           p.title as portfolio_title,
           pr.name as product_name
    FROM service_inquiries si
    LEFT JOIN services s ON si.service_id = s.id
    LEFT JOIN portfolio p ON si.portfolio_id = p.id
    LEFT JOIN products pr ON si.product_id = pr.id
    ORDER BY si.created_at DESC
 LIMIT :limit OFFSET :offset");
$stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();
$inquiries = $stmt->fetchAll();

$data = [];
foreach ($inquiries as $i) {
    $status_color = '#64748b';
    if($i['status'] === 'responded') $status_color = '#10b981';
    if($i['status'] === 'deal') $status_color = '#6366f1';
    if($i['status'] === 'rejected') $status_color = '#f43f5e';
    
    $status_label = '<span class="badge" style="background:#f1f5f9; color:'.$status_color.'; border:0.5px solid '.$status_color.'; padding:4px 10px; border-radius:20px; font-weight:700; font-size:10px;">' . strtoupper($i['status']) . '</span>';
    
    if($i['inquiry_type'] === 'service') {
        $item_title = $i['service_title'];
        $source_badge = '<span style="color:#6366f1; font-weight:700;">Service</span>';
    } elseif($i['inquiry_type'] === 'portfolio') {
        $item_title = $i['portfolio_title'];
        $source_badge = '<span style="color:#f59e0b; font-weight:700;">Portfolio</span>';
    } else {
        $item_title = $i['product_name'];
        $source_badge = '<span style="color:#10b981; font-weight:700;">Product</span>';
    }

    $data[] = [
        "date" => '<span style="font-size:0.8rem; color:#64748b;">' . date('d M Y, H:i', strtotime($i['created_at'])) . '</span>',
        "client" => '<strong>' . htmlspecialchars($i['customer_name']) . '</strong><br><small style="color:#94a3b8;">' . htmlspecialchars($i['customer_email']) . '</small>',
        "item" => '<strong>' . htmlspecialchars($item_title) . '</strong>',
        "source" => $source_badge,
        "status" => $status_label,
        "actions" => '
            <div style="display:flex; gap:5px;">
                <button class="btn-primary" onclick="viewInquiry(' . $i['id'] . ')" style="background:#6366f1; padding:5px 10px; font-size:12px;">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="btn-primary" onclick="deleteInquiry(' . $i['id'] . ')" style="background:#ef4444; padding:5px 10px; font-size:12px;">
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
