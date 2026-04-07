<?php
// admin/ajax/orders-data.php

require_once __DIR__ . '/../../includes/auth.php';
authorize('Super Admin');

header('Content-Type: application/json');

$total_count = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

$limit = isset($_GET['length']) ? (int)$_GET['length'] : 10;
if ($limit < 1) $limit = 1000000; // Datatables sends -1 for All
$offset = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$stmt = $pdo->prepare("SELECT o.*, p.name as product_name FROM orders o JOIN products p ON o.product_id = p.id ORDER BY o.created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll();

$data = [];
foreach ($orders as $o) {
    $status_color = '#64748b';
    if($o['order_status'] === 'paid') $status_color = '#10b981';
    if($o['order_status'] === 'cancelled') $status_color = '#f43f5e';
    
    $status_label = '<span class="badge" style="background:#f1f5f9; color:'.$status_color.'; border:0.5px solid '.$status_color.'; padding:4px 10px; border-radius:20px; font-weight:700;">' . strtoupper($o['order_status']) . '</span>';
    
    $pm = $o['payment_method'] === 'transfer' ? '<i class="fas fa-university" style="color:#6366f1;"></i> Transfer' : '<i class="fas fa-credit-card" style="color:#f59e0b;"></i> VA';
    
    $data[] = [
        "date" => '<span style="font-size:0.8rem; color:#64748b;">' . date('d M Y, H:i', strtotime($o['created_at'])) . '</span>',
        "customer" => '<strong>' . htmlspecialchars($o['customer_name']) . '</strong><br><small style="color:#94a3b8;">' . htmlspecialchars($o['customer_email']) . '</small>',
        "product" => htmlspecialchars($o['product_name']),
        "amount" => '<span style="font-weight:700; color:var(--dark);">Rp ' . number_format($o['total_price'], 0, ',', '.') . '</span>',
        "payment" => $pm,
        "status" => $status_label,
        "actions" => '
            <div style="display:flex; gap:5px;">
                <button class="btn-primary" onclick="updateStatus(' . $o['id'] . ', \'paid\')" style="background:#10b981; padding:5px; font-size:10px;" title="Mark Paid">
                    <i class="fas fa-check"></i>
                </button>
                <button class="btn-primary" onclick="updateStatus(' . $o['id'] . ', \'cancelled\')" style="background:#f59e0b; padding:5px; font-size:10px;" title="Cancel">
                    <i class="fas fa-times"></i>
                </button>
                <button class="btn-primary" onclick="deleteOrder(' . $o['id'] . ')" style="background:#ef4444; padding:5px; font-size:10px;" title="Delete">
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
