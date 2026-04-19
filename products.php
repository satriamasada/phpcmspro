<?php
// products.php
require_once 'config/database.php';

$site_name = get_setting('site_name', 'SoftCo Tech');
$theme_mode = get_setting('theme_mode', 'light');

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme_mode ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog - <?= $site_name ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .page-header { padding: 160px 10% 80px; background: var(--bg-soft); text-align: center; }
        .product-grid { padding: 80px 10%; }
    </style>
</head>
<body class="bg-white">
    <nav style="position:fixed; width:100%; padding:20px 10%; background:var(--glass-bg); backdrop-filter:blur(10px); z-index:1000; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border);">
        <a href="index.php" class="logo" style="text-decoration:none; color:var(--primary); font-weight:900; font-size:1.5rem; letter-spacing:-1px;"><?= strtoupper($site_name) ?></a>
        <a href="index.php" style="text-decoration:none; color:var(--text-main); font-weight:700;">Home</a>
    </nav>

    <header class="page-header">
        <h1 style="font-size:3.5rem; color:var(--dark);">Software <span class="accent-text">Solutions</span></h1>
        <p style="color:var(--text-muted); font-size:1.2rem; margin-top:1rem;">Ready-to-deploy products built with enterprise-grade quality.</p>
    </header>

    <section class="product-grid">
        <div class="grid-container" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:2.5rem;">
            <?php foreach ($products as $pr): ?>
            <div class="card fade-up" style="padding:0; overflow:hidden; display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                <img src="<?= $pr['image_url'] ?>" style="width:100%; height:200px; object-fit:cover;">
                <div style="padding:2.5rem;">
                    <span style="font-size:0.75rem; text-transform:uppercase; color:var(--primary); font-weight:800;"><?= $pr['category'] ?></span>
                    <h3 style="margin-top:0.5rem; margin-bottom:1rem;"><?= htmlspecialchars($pr['name']) ?></h3>
                    <p style="color:var(--text-muted); font-size:0.9rem; line-height:1.7; margin-bottom:1.5rem; height:75px; overflow:hidden;"><?= htmlspecialchars(substr($pr['description'], 0, 120)) ?>...</p>
                    <div style="font-size:1.5rem; font-weight:900; color:var(--primary); margin-bottom:2rem;">Rp <?= number_format($pr['price'], 0, ',', '.') ?></div>
                </div>
                </div>
                <div style="padding:0 2.5rem 2.5rem; display:flex; gap:1rem; justify-content: center;">
                    <a href="product-detail.php?id=<?= $pr['id'] ?>" class="btn btn-outline" style="flex:1; text-align:center; padding:10px; font-size:0.9rem;">Details</a>
                    <a href="checkout.php?id=<?= $pr['id'] ?>" class="btn btn-primary" style="flex:1; text-align:center; padding:10px; font-size:0.9rem;">Purchase</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer style="background:var(--bg-soft); padding:80px 10% 40px; text-align:center; border-top:1px solid var(--border);">
        <p style="color:var(--text-muted);">&copy; <?= date('Y') ?> <?= $site_name ?>. Future-proof tech.</p>
    </footer>

    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('aos-animate');
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
    </script>
</body>
</html>
