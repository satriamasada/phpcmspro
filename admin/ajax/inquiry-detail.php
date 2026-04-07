<?php
// admin/ajax/inquiry-detail.php

require_once __DIR__ . '/../../includes/auth.php';
check_login();

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;
// Join with services, portfolio and products to get titles
$stmt = $pdo->prepare("
    SELECT si.*, 
           s.title as service_title, 
           p.title as portfolio_title,
           pr.name as product_name
    FROM service_inquiries si
    LEFT JOIN services s ON si.service_id = s.id
    LEFT JOIN portfolio p ON si.portfolio_id = p.id
    LEFT JOIN products pr ON si.product_id = pr.id
    WHERE si.id = ?
");
$stmt->execute([$id]);
$inquiry = $stmt->fetch();

if ($inquiry) {
    if($inquiry['inquiry_type'] === 'service') $inquiry['interest_title'] = $inquiry['service_title'];
    elseif($inquiry['inquiry_type'] === 'portfolio') $inquiry['interest_title'] = $inquiry['portfolio_title'];
    else $inquiry['interest_title'] = $inquiry['product_name'];
    
    echo json_encode($inquiry);
} else {
    echo json_encode(["error" => "Inquiry not found"]);
}
?>
