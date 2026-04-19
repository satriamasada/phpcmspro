<?php
// news.php
require_once 'config/database.php';

$site_name = get_setting('site_name', 'SoftCo Tech');
$theme_mode = get_setting('theme_mode', 'light');

$news = $pdo->query("SELECT n.*, u.full_name FROM news n JOIN users u ON n.author_id = u.id WHERE n.is_published = 1 ORDER BY n.created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme_mode ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News & Insights - <?= $site_name ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .page-header { padding: 160px 10% 80px; background: var(--bg-soft); text-align: center; }
        .news-grid { padding: 80px 10%; }
    </style>
</head>
<body class="bg-white">
    <nav style="position:fixed; width:100%; padding:20px 10%; background:var(--glass-bg); backdrop-filter:blur(10px); z-index:1000; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border);">
        <a href="index.php" class="logo" style="text-decoration:none; color:var(--primary); font-weight:900; font-size:1.5rem; letter-spacing:-1px;"><?= strtoupper($site_name) ?></a>
        <a href="index.php" style="text-decoration:none; color:var(--text-main); font-weight:700;">Home</a>
    </nav>

    <header class="page-header">
        <h1 style="font-size:3.5rem; color:var(--dark);">Global <span class="accent-text">Insights</span></h1>
        <p style="color:var(--text-muted); font-size:1.2rem; margin-top:1rem;">Follow our journey through the latest technology trends and company updates.</p>
    </header>

    <section class="news-grid">
        <div class="grid-container" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap:3rem;">
            <?php foreach ($news as $n): ?>
            <div class="card fade-up" style="padding:0; overflow:hidden; display:flex; flex-direction:column; justify-content:space-between;">
                <div>
                <img src="<?= $n['featured_image'] ?>" style="width:100%; height:230px; object-fit:cover;">
                <div style="padding:2.5rem;">
                    <span style="font-size:0.7rem; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:1px;"><?= date('M d, Y', strtotime($n['created_at'])) ?></span>
                    <h3 style="margin-top:1rem; margin-bottom:1.2rem; line-height:1.4;"><?= htmlspecialchars($n['title']) ?></h3>
                    <p style="color:var(--text-muted); font-size:0.95rem; line-height:1.8; margin-bottom:2rem; height:80px; overflow:hidden;">
                        <?= htmlspecialchars(substr(strip_tags($n['content']), 0, 150)) ?>...
                    </p>
                </div>
                </div>
                <div style="padding:0 2.5rem 2.5rem;">
                    <a href="news-detail.php?slug=<?= $n['slug'] ?>" style="color:var(--primary); text-decoration:none; font-weight:900; font-size:0.9rem; letter-spacing:1px; border-bottom:2px solid var(--primary); padding-bottom:5px;">READ MORE &rarr;</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer style="background:var(--bg-soft); padding:80px 10% 40px; text-align:center; border-top:1px solid var(--border);">
        <p style="color:var(--text-muted);">&copy; <?= date('Y') ?> <?= $site_name ?>. Future trends archive.</p>
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
