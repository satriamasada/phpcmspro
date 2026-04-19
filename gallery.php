<?php
// gallery.php
require_once 'config/database.php';

$site_name = get_setting('site_name', 'SoftCo Tech');
$theme_mode = get_setting('theme_mode', 'light');

// Fetch all gallery items
$gallery = $pdo->query("SELECT * FROM galleries ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme_mode ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visual Archive - <?= $site_name ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .page-header {
            padding: 160px 10% 80px;
            background: var(--bg-soft);
            text-align: center;
        }
        .gallery-grid {
            padding: 80px 10%;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Navbar -->
    <nav style="position:fixed; width:100%; padding:20px 10%; background:var(--glass-bg); backdrop-filter:blur(10px); z-index:1000; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border);">
        <a href="index.php" class="logo" style="text-decoration:none; color:var(--primary); font-weight:900; font-size:1.5rem; letter-spacing:-1px;"><?= strtoupper($site_name) ?></a>
        <a href="index.php" style="text-decoration:none; color:var(--text-main); font-weight:700;">Home</a>
    </nav>

    <header class="page-header">
        <h1 style="font-size:3.5rem; color:var(--dark);">Visual <span class="accent-text">Archive</span></h1>
        <p style="color:var(--text-muted); font-size:1.2rem; margin-top:1rem;">Documenting our journey across events, innovations, and teamwork.</p>
    </header>

    <section class="gallery-grid">
        <div class="grid-container">
            <?php foreach ($gallery as $g): ?>
            <a href="gallery-detail.php?id=<?= $g['id'] ?>" class="project-card fade-up" style="display:block; text-decoration:none;">
                <img src="<?= $g['image_path'] ?>" class="project-img">
                <div class="project-overlay">
                    <span style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--secondary);"><?= htmlspecialchars($g['category']) ?></span>
                    <h3 style="margin-top:0.5rem;"><?= htmlspecialchars($g['title']) ?></h3>
                    <span style="font-size:0.85rem; color:#fff; border-bottom:1px solid rgba(255,255,255,0.3); padding-bottom:5px;">View Detail</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <footer style="background:var(--bg-soft); padding:80px 10% 40px; text-align:center; border-top:1px solid var(--border);">
        <p style="color:var(--text-muted);">&copy; <?= date('Y') ?> <?= $site_name ?>. High Fidelity Visuals.</p>
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
