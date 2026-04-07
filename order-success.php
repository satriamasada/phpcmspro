<?php
// order-success.php
require_once 'config/database.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT o.*, p.name as product_name FROM orders o JOIN products p ON o.product_id = p.id WHERE o.id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) { header('Location: index.php'); exit; }
$site_name = "SoftCo.tech";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success | <?= $site_name ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background:var(--bg-soft); min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; padding-top:50px;">
    <div class="card fade-up" style="max-width:600px; text-align:center; padding:4rem; box-shadow:0 30px 80px rgba(0,0,0,0.1);">
        <i class="fas fa-check-circle" style="font-size:5rem; color:#10b981; margin-bottom:2rem;"></i>
        <h1 style="font-size:2.5rem; margin-bottom:1rem;">Thank <span class="accent-text">You!</span></h1>
        <p style="color:var(--text-muted); font-size:1.1rem; margin-bottom:3rem;">Your order #INV-<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?> has been received.</p>
        
        <div style="background:#f8fafc; border:1.5px solid var(--border); border-radius:12px; padding:2rem; text-align:left; margin-bottom:3rem;">
            <h4 style="margin-bottom:1.5rem; color:var(--dark);">Payment Instruction:</h4>
            
            <?php if ($order['payment_method'] === 'transfer'): ?>
                <p style="font-size:0.9rem; color:#64748b; margin-bottom:1rem;">Please transfer the total amount to our bank account:</p>
                <div style="background:white; padding:1.5rem; border-radius:8px; border:1px solid var(--border);">
                    <p style="font-weight:800; color:var(--dark);">Bank Central Asia (BCA)</p>
                    <p style="font-size:1.5rem; color:var(--primary); font-weight:800; letter-spacing:2px; margin:0.5rem 0;">8840 1234 5678</p>
                    <p style="font-size:0.8rem; color:var(--text-muted);">Account Name: <strong>SOFTCO TECHNOLOGIES INDONESIA</strong></p>
                </div>
            <?php else: ?>
                <p style="font-size:0.9rem; color:#64748b; margin-bottom:1rem;">Your Virtual Account number (BCA Virtual Account):</p>
                <div style="background:white; padding:1.5rem; border-radius:8px; border:1px solid var(--border);">
                    <p style="font-weight:800; color:var(--dark);">BCA Virtual Account</p>
                    <p style="font-size:1.5rem; color:var(--secondary); font-weight:800; letter-spacing:2px; margin:0.5rem 0;">12200<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></p>
                    <p style="font-size:0.8rem; color:var(--text-muted);">Please complete payment before 24 hours.</p>
                </div>
            <?php endif; ?>
        </div>

        <p style="font-size:0.9rem; color:#64748b; margin-bottom:3rem; line-height:1.6;">Once payment is confirmed, the digital license will be sent to <strong><?= htmlspecialchars($order['customer_email']) ?></strong>.</p>
        <a href="index.php" class="btn btn-primary" style="padding:1.25rem 3rem;">Home Page</a>
    </div>
    <script>document.querySelectorAll('.fade-up').forEach(el => el.classList.add('aos-animate'));</script>
</body>
</html>
