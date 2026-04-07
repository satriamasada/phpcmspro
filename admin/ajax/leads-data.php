<?php
// admin/ajax/leads-data.php

require_once __DIR__ . '/../../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;

header('Content-Type: application/json');

$total_count = $pdo->query("SELECT COUNT(*) FROM contact_leads")->fetchColumn();

$limit = isset($_GET['length']) ? (int)$_GET['length'] : 10;
if ($limit < 1) $limit = 1000000; // Datatables sends -1 for All
$offset = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$stmt = $pdo->prepare("SELECT * FROM contact_leads ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll();

$response = [
    "draw" => intval($_GET['draw'] ?? 1),
    "recordsTotal" => intval($total_count),
    "recordsFiltered" => intval($total_count),
    "data" => array_map(function($l) {
        $status_color = ($l['status'] == 'unread') ? '#ef4444' : '#10b981';
        return [
            "date" => date('d M Y, H:i', strtotime($l['created_at'])),
            "sender" => "<strong>" . htmlspecialchars($l['name']) . "</strong><br><small>" . htmlspecialchars($l['email']) . "</small>",
            "subject" => htmlspecialchars($l['subject'] ?: '(No Subject)'),
            "status" => '<span style="color:' . $status_color . '; font-weight:600;">' . ucfirst($l['status']) . '</span>',
            "actions" => '
                <button class="btn-primary" onclick="viewLead(' . $l['id'] . ')" style="padding:5px 10px; font-size:12px;">
                    <i class="fas fa-eye"></i> View
                </button>'
        ];
    }, $data)
];

echo json_encode($response);
?>
