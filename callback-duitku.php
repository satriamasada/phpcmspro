<?php
// callback-duitku.php
require_once 'config/database.php';

$merchantCode = 'DS29592';
$apiKey = '01a86731deccee01823c1735b1f5c357';
$isSandbox = true; // Set to false if production

// Log file for debug
$logFile = __DIR__ . '/callback_duitku_log.txt';

try {
    // Setup Config
    $duitkuConfig = new \Duitku\Config($apiKey, $merchantCode);
    $duitkuConfig->setSandboxMode($isSandbox);

    // Use Library to handle callback validation
    $callback = \Duitku\Api::callback($duitkuConfig);
    $notif = json_decode($callback);

    // Log raw incoming
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Library Callback: " . $callback . "\n", FILE_APPEND | LOCK_EX);

    if ($notif->resultCode == "00") {
        // Success payment
        $merchantOrderId = $notif->merchantOrderId;
        
        // Extract original Order ID (Format: ORD-[TIME]-[ORDER_ID])
        $parts = explode('-', $merchantOrderId);
        $order_id = end($parts);
        
        $stmt = $pdo->prepare("UPDATE orders SET order_status = 'success' WHERE id = ?");
        $stmt->execute([$order_id]);
        
        file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Order $order_id Updated to SUCCESS\n\n", FILE_APPEND | LOCK_EX);
        
        http_response_code(200);
        echo "SUCCESS";
    } else {
        // Failed payment
        file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Payment Failed for Order: " . ($notif->merchantOrderId ?? 'N/A') . "\n\n", FILE_APPEND | LOCK_EX);
        echo "FAILED";
    }

} catch (Exception $e) {
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] Library Callback Error: " . $e->getMessage() . "\n\n", FILE_APPEND | LOCK_EX);
    http_response_code(400);
    echo "Bad Request: " . $e->getMessage();
}
?>
