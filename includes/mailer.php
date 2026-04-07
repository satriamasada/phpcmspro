<?php
// includes/mailer.php

function send_notification($to, $subject, $message) {
    $site_name = get_setting('site_name', 'SoftCo Tech');
    $headers = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n";
    $headers .= "Reply-To: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Log for debugging (simulating real mail sending)
    $log_dir = __DIR__ . '/../logs/';
    if(!is_dir($log_dir)) mkdir($log_dir, 0777, true);
    
    $log_entry = "[" . date('Y-m-d H:i:s') . "] TO: $to | SUBJECT: $subject\nBODY: $message\n-----------------------------------\n";
    file_put_contents($log_dir . 'emails.log', $log_entry, FILE_APPEND);

    // In local environment, mail() might not work, but we return true to simulate success
    // return mail($to, $subject, $message, $headers);
    return true;
}

function notify_admin_new_lead($type, $name, $detail) {
    $admin_email = get_setting('site_email', 'admin@softco.tech');
    $subject = "New $type Inquiry: $name";
    $body = "Hello Admin,\n\nA new $type inquiry has been submitted.\n\nFrom: $name\nDetails: $detail\n\nPlease check the admin panel for more details.";
    return send_notification($admin_email, $subject, $body);
}

function notify_admin_new_order($order_id, $customer, $total) {
    $admin_email = get_setting('site_email', 'admin@softco.tech');
    $subject = "New Product Order #$order_id";
    $body = "Hello Admin,\n\nA new order has been placed by $customer.\nOrder ID: $order_id\nTotal: " . number_format($total) . "\n\nPlease check the admin panel for payment confirmation.";
    return send_notification($admin_email, $subject, $body);
}
?>
