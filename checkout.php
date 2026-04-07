<?php
// checkout.php
require_once 'config/database.php';

$product_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) { header('Location: index.php'); exit; }
$site_name = "SoftCo.tech";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | <?= $site_name ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .checkout-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 3rem; padding: 100px 10%; align-items: start; }
        .form-card { background: white; padding: 3rem; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.05); }
        .input-group { margin-bottom: 2rem; }
        .input-group label { display: block; font-weight: 700; margin-bottom: 0.75rem; color: var(--dark); font-size: 0.9rem; }
        .input-group input, .input-group select { width: 100%; padding: 1rem; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 1rem; transition: all 0.3s; }
        .input-group input:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 10px rgba(0,102,255,0.1); }
        .payment-method { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 2rem; }
        .payment-option { border: 1.5px solid var(--border); padding: 1.5rem; border-radius: 12px; cursor: pointer; text-align: center; transition: all 0.3s; }
        .payment-option i { font-size: 1.5rem; margin-bottom: 0.75rem; display: block; color: var(--text-muted); }
        .payment-option.active { border-color: var(--primary); background: #f0f7ff; }
        .payment-option.active i { color: var(--primary); }
    </style>
</head>
<body style="background:var(--bg-soft);">
    <nav>
        <div class="logo">SOFTCO.TECH</div>
        <ul class="nav-links"><li><a href="index.php">Back to Store</a></li></ul>
    </nav>

    <div class="checkout-grid">
        <div class="form-card fade-up">
            <h2 style="margin-bottom: 2.5rem; font-size: 2rem;">Customer <span class="accent-text">Information</span></h2>
            <form action="process-checkout.php" method="POST" id="checkoutForm">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" name="total_price" value="<?= $product['price'] ?>">
                <input type="hidden" name="payment_method" id="selected_payment" value="transfer">
                
                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required placeholder="Ex: John Doe">
                </div>
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required placeholder="Ex: john@example.com">
                </div>
                <div class="input-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" required placeholder="Ex: +62 812 3456 7890">
                </div>

                <label style="font-weight: 700; color: var(--dark); font-size: 0.9rem;">Payment Method</label>
                <div class="payment-method">
                    <div class="payment-option active" data-value="transfer">
                        <i class="fas fa-university"></i>
                        <span style="font-weight:700;">Bank Transfer</span>
                    </div>
                    <div class="payment-option" data-value="va">
                        <i class="fas fa-credit-card"></i>
                        <span style="font-weight:700;">Virtual Account</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; margin-top:3rem; border:none; padding:1.5rem; font-size:1.1rem; cursor:pointer;">Pay for Product</button>
            </form>
        </div>

        <div class="fade-up" style="transition-delay:0.2s;">
            <div class="form-card" style="background:var(--dark); color:white;">
                <h3 style="margin-bottom: 2rem; color:white;">Order Summary</h3>
                <div style="display:flex; justify-content:space-between; margin-bottom:1.5rem;">
                    <span style="color:#94a3b8;"><?= htmlspecialchars($product['name']) ?></span>
                    <span style="font-weight:700;">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:1.5rem;">
                    <span style="color:#94a3b8;">Tax (VAT 0%)</span>
                    <span style="font-weight:700;">Rp 0</span>
                </div>
                <hr style="border:0; border-top:1px solid #1e293b; margin:2rem 0;">
                <div style="display:flex; justify-content:space-between; font-size:1.5rem;">
                    <span style="font-weight:800; color:white;">Total</span>
                    <span style="font-weight:800; color:var(--secondary);">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                </div>
                <div style="margin-top:2rem; font-size:0.85rem; color:#64748b; line-height:1.6;">
                    * All digital licenses are delivered via email instantly after payment verification.
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('.payment-option').on('click', function() {
            $('.payment-option').removeClass('active');
            $(this).addClass('active');
            $('#selected_payment').val($(this).data('value'));
        });
        document.querySelectorAll('.fade-up').forEach(el => el.classList.add('aos-animate'));
    </script>
</body>
</html>
