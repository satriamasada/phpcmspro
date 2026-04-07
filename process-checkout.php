<?php
// process-checkout.php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment_method'];
    $total_price = $_POST['total_price'];

    try {
        $stmt = $pdo->prepare("INSERT INTO orders (product_id, customer_name, customer_email, customer_phone, payment_method, total_price, order_status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$product_id, $name, $email, $phone, $payment_method, $total_price]);
        
        $order_id = $pdo->lastInsertId();
        
        // Send Email Notification to Admin
        notify_admin_new_order($order_id, $name, $total_price);
        
        header("Location: order-success.php?id=" . $order_id);
        exit;
    } catch (PDOException $e) {
        die("Error processing order: " . $e->getMessage());
    }
}
?>
