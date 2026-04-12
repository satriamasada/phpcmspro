<?php
// process-checkout.php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $total_price = $_POST['total_price'];
    $payment_method = $_POST['payment_method']; // Capture the selected method code (e.g. VC, BC, etc)

    try {
        $stmt = $pdo->prepare("INSERT INTO orders (product_id, customer_name, customer_email, customer_phone, payment_method, total_price, order_status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$product_id, $name, $email, $phone, $payment_method, $total_price]);
        
        $order_id = $pdo->lastInsertId();
        
        // --- DUITKU LIBRARY INTEGRATION ---
        
        $merchantCode = 'DS29592';
        $apiKey = '01a86731deccee01823c1735b1f5c357';
        $isSandbox = true; // Set to false if production
        
        // Setup Config
        $duitkuConfig = new \Duitku\Config($apiKey, $merchantCode);
        $duitkuConfig->setSandboxMode($isSandbox);
        
        $domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $baseFolder = rtrim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');
        
        $callbackUrl = $domain . $baseFolder . "/callback-duitku.php";
        $returnUrl = $domain . $baseFolder . "/order-success.php?id=" . $order_id;
        
        $merchantOrderId = "ORD-" . time() . "-" . $order_id;
        $paymentAmount = (int)$total_price;

        // Fetch product name
        $stmtProd = $pdo->prepare("SELECT name FROM products WHERE id = ?");
        $stmtProd->execute([$product_id]);
        $prod = $stmtProd->fetch();
        $productName = $prod ? $prod['name'] : 'Produk di SoftCo';

        // Item Details
        $itemDetails = array(
            array(
                'name' => $productName,
                'price' => $paymentAmount,
                'quantity' => 1
            )
        );

        // Customer Detail
        $customerDetail = array(
            'firstName' => $name,
            'lastName' => '',
            'email' => $email,
            'phoneNumber' => $phone
        );
        
        $params = array(
            'paymentAmount' => $paymentAmount,
            'paymentMethod' => $payment_method, // Use the selected code from checkout page
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => $productName,
            'additionalParam' => '',
            'merchantUserInfo' => $email,
            'customerVaName' => $name,
            'email' => $email,
            'phoneNumber' => $phone,
            'itemDetails' => $itemDetails,
            'customerDetail' => $customerDetail,
            'callbackUrl' => $callbackUrl,
            'returnUrl' => $returnUrl,
            'expiryPeriod' => 1440
        );

        try {
            // Request Transaction through Library
            $response = \Duitku\Api::createInvoice($params, $duitkuConfig);
            $result = json_decode($response);

            if (isset($result->paymentUrl)) {
                 // Send Email Notification to Admin
                notify_admin_new_order($order_id, $name, $total_price);
                
                // Redirect
                header('Location: ' . $result->paymentUrl);
                exit;
            } else {
                die("Duitku Error: " . ($result->statusMessage ?? $response));
            }
        } catch (Exception $e) {
             die("Library Error: " . $e->getMessage());
        }
    } catch (PDOException $e) {
        die("Error processing order: " . $e->getMessage());
    }
}
?>
