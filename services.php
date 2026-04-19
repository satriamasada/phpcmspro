<?php
// services.php
require_once 'config/database.php';

$site_name = get_setting('site_name', 'SoftCo Tech');
$theme_mode = get_setting('theme_mode', 'light');

$services = $pdo->query("SELECT * FROM services ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme_mode ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services - <?= $site_name ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .page-header { padding: 160px 10% 80px; background: var(--bg-soft); text-align: center; }
        .service-grid { padding: 80px 10%; }
    </style>
</head>
<body class="bg-white">
    <nav style="position:fixed; width:100%; padding:20px 10%; background:var(--glass-bg); backdrop-filter:blur(10px); z-index:1000; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border);">
        <a href="index.php" class="logo" style="text-decoration:none; color:var(--primary); font-weight:900; font-size:1.5rem; letter-spacing:-1px;"><?= strtoupper($site_name) ?></a>
        <a href="index.php" style="text-decoration:none; color:var(--text-main); font-weight:700;">Home</a>
    </nav>

    <header class="page-header">
        <h1 style="font-size:3.5rem; color:var(--dark);">Professional <span class="accent-text">Services</span></h1>
        <p style="color:var(--text-muted); font-size:1.2rem; margin-top:1rem;">Scalable engineering solutions for modern digital enterprises.</p>
    </header>

    <section class="service-grid">
        <div class="grid-container" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap:2.5rem;">
            <?php foreach ($services as $s): ?>
            <div class="card fade-up">
                <i class="<?= $s['icon'] ?>"></i>
                <h3 style="margin-bottom:1.2rem;"><?= htmlspecialchars($s['title']) ?></h3>
                <p style="color:var(--text-muted); font-size:0.95rem; line-height:1.7; margin-bottom:2rem; height:80px; overflow:hidden;">
                    <?= htmlspecialchars(substr($s['description'], 0, 150)) ?>...
                </p>
                <a href="service-detail.php?id=<?= $s['id'] ?>" class="btn btn-primary" style="font-size:0.9rem;">View More</a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer style="background:var(--bg-soft); padding:80px 10% 40px; text-align:center; border-top:1px solid var(--border);">
        <p style="color:var(--text-muted);">&copy; <?= date('Y') ?> <?= $site_name ?>. Engineering Excellence.</p>
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
