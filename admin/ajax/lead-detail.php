<?php
// admin/ajax/lead-detail.php

require_once __DIR__ . '/../../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM contact_leads WHERE id = ?");
$stmt->execute([$id]);
$lead = $stmt->fetch();

if ($lead) {
    echo json_encode($lead);
} else {
    echo json_encode(["error" => "Not found"]);
}
?>
