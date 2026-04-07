<?php
// process-inquiry.php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'];
    $type = $_POST['type']; // 'service', 'portfolio', 'product'
    $related_id = $_POST['related_id'] ?? NULL;

    try {
        if ($type === 'service') {
            $stmt = $pdo->prepare("INSERT INTO service_inquiries (service_id, customer_name, customer_email, customer_phone, message, inquiry_type) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$related_id, $name, $email, $phone, $message, $type]);
        } elseif ($type === 'portfolio') {
            $stmt = $pdo->prepare("INSERT INTO service_inquiries (portfolio_id, customer_name, customer_email, customer_phone, message, inquiry_type) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$related_id, $name, $email, $phone, $message, $type]);
        } elseif ($type === 'product') {
            $stmt = $pdo->prepare("INSERT INTO service_inquiries (product_id, customer_name, customer_email, customer_phone, message, inquiry_type) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$related_id, $name, $email, $phone, $message, $type]);
        }

        // Send Email Notification to Admin
        notify_admin_new_lead($type, $name, $message);

        header("Location: inquiry-success.php");
        exit;
    } catch (PDOException $e) {
        die("Error sending inquiry: " . $e->getMessage());
    }
}
?>
