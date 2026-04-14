<?php
// portfolio.php
require_once '../config/database.php';

$site_name = get_setting('site_name', 'SoftCo Tech');
$theme_mode = get_setting('theme_mode', 'light');

$projects = $pdo->query("SELECT * FROM portfolio ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme_mode ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Portfolio - <?= $site_name ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .page-header { padding: 160px 10% 80px; background: var(--bg-soft); text-align: center; }
        .project-grid { padding: 80px 10%; }
        .project-card { border-radius: 20px; overflow: hidden; position: relative; height: 350px; background: var(--dark); border:none; }
        .project-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s; opacity: 0.8; }
        .project-card:hover .project-img { transform: scale(1.1); opacity: 1; }
        .project-overlay { position: absolute; bottom: 0; width: 100%; padding: 2.5rem; background: linear-gradient(transparent, rgba(0,0,0,0.9)); color: white; z-index: 2; }
    </style>
</head>
<body class="bg-white">
    <nav style="position:fixed; width:100%; padding:20px 10%; background:var(--glass-bg); backdrop-filter:blur(10px); z-index:1000; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border);">
        <a href="index.php" class="logo" style="text-decoration:none; color:var(--primary); font-weight:900; font-size:1.5rem; letter-spacing:-1px;"><?= strtoupper($site_name) ?></a>
        <a href="index.php" style="text-decoration:none; color:var(--text-main); font-weight:700;">Home</a>
    </nav>

    <header class="page-header">
        <h1 style="font-size:3.5rem; color:var(--dark);">Global <span class="accent-text">Portfolio</span></h1>
        <p style="color:var(--text-muted); font-size:1.2rem; margin-top:1rem;">Documenting our successes in transforming complex ideas into digital realities.</p>
    </header>

    <section class="project-grid">
        <div class="grid-container" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap:2.5rem;">
            <?php foreach ($projects as $p): ?>
            <a href="portfolio-detail.php?id=<?= $p['id'] ?>" class="project-card fade-up">
                <img src="<?= $p['image_url'] ?>" class="project-img">
                <div class="project-overlay">
                    <span style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--primary);"><?= $p['category'] ?></span>
                    <h3 style="margin-top:0.5rem;"><?= htmlspecialchars($p['title']) ?></h3>
                    <p style="font-size:0.85rem; margin-top:0.5rem; opacity:0.8; line-height:1.6; height:45px; overflow:hidden;"><?= htmlspecialchars(substr($p['description'], 0, 80)) ?>...</p>
                    <span style="display:inline-block; margin-top:1rem; padding-bottom:5px; border-bottom:1px solid rgba(255,255,255,0.4); font-size:0.85rem; font-weight:700;">Discover Case Study &rarr;</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <footer style="background:var(--bg-soft); padding:80px 10% 40px; text-align:center; border-top:1px solid var(--border);">
        <p style="color:var(--text-muted);">&copy; <?= date('Y') ?> <?= $site_name ?>. High fidelity innovation.</p>
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
