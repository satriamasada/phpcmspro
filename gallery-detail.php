<?php
// gallery-detail.php
require_once 'config/database.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM galleries WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    header("Location: index.php");
    exit;
}

$site_name = get_setting('site_name', 'SoftCo Tech');
$theme_mode = get_setting('theme_mode', 'light');

// Fetch related items
$related = $pdo->prepare("SELECT * FROM galleries WHERE category = ? AND id != ? LIMIT 3");
$related->execute([$item['category'], $id]);
$related_items = $related->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme_mode ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($item['title']) ?> - <?= $site_name ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .detail-header {
            padding: 154px 10% 60px;
            background: var(--bg-soft);
            text-align: center;
        }
        .gallery-content {
            padding: 80px 10%;
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 5rem;
            align-items: start;
        }
        @media (max-width: 900px) {
            .gallery-content { grid-template-columns: 1fr; gap: 3rem; }
        }
    </style>
</head>
<body class="bg-white">
    <!-- Simple Navbar -->
    <nav style="position:fixed; width:100%; padding:20px 10%; background:var(--glass-bg); backdrop-filter:blur(10px); z-index:1000; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border);">
        <a href="index.php" class="logo" style="text-decoration:none; color:var(--primary); font-weight:900; font-size:1.5rem; letter-spacing:-1px;"><?= strtoupper($site_name) ?></a>
        <a href="index.php#gallery" style="text-decoration:none; color:var(--text-main); font-weight:700;">&larr; Back to Gallery</a>
    </nav>

    <header class="detail-header">
        <span style="color:var(--primary); font-weight:800; text-transform:uppercase; letter-spacing:2px; font-size:14px;"><?= htmlspecialchars($item['category']) ?></span>
        <h1 style="font-size:3.5rem; margin-top:1rem; color:var(--dark);"><?= htmlspecialchars($item['title']) ?></h1>
    </header>

    <section class="gallery-content">
        <div class="fade-up">
            <img src="<?= $item['image_path'] ?>" onerror="this.src='https://via.placeholder.com/1200x800?text=Highlight+Photo'" style="width:100%; border-radius:30px; box-shadow: 0 30px 60px rgba(0,0,0,0.1); margin-bottom:3rem;">
        </div>
        <div class="fade-up">
            <div class="card" style="padding:4rem; border-radius:24px;">
                <h3 style="margin-bottom:1.5rem; font-size:1.8rem;">About this <span class="accent-text">Activity</span></h3>
                <div style="line-height:2; color:var(--text-main); font-size:1.1rem;">
                    <?= nl2br(htmlspecialchars($item['description'] ?: 'No detailed information available for this activity yet.')) ?>
                </div>
                
                <div style="margin-top:3rem; padding-top:2rem; border-top:1px solid var(--border);">
                    <h4 style="margin-bottom:1rem;">Interested in similar events?</h4>
                    <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:2rem;">Join our community or partner with us for the next innovation summit.</p>
                    <a href="index.php#contact" class="btn btn-primary" style="width:100%; text-align:center;">Contact Us Today</a>
                </div>
            </div>
            
            <?php if ($related_items): ?>
            <div style="margin-top:4rem;">
                <h3 style="margin-bottom:2rem;">Related <span class="accent-text">Highlights</span></h3>
                <?php foreach ($related_items as $r): ?>
                <a href="gallery-detail.php?id=<?= $r['id'] ?>" style="display:flex; gap:1rem; align-items:center; text-decoration:none; margin-bottom:1.5rem; background:var(--bg-soft); padding:1rem; border-radius:15px; border:1px solid var(--border);">
                    <img src="<?= $r['image_path'] ?>" style="width:80px; height:60px; object-fit:cover; border-radius:8px;">
                    <div>
                        <h4 style="color:var(--dark); margin:0; font-size:1rem;"><?= htmlspecialchars($r['title']) ?></h4>
                        <small style="color:var(--text-muted);"><?= htmlspecialchars($r['category']) ?></small>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <footer style="background:var(--bg-soft); padding:60px 10%; text-align:center; border-top:1px solid var(--border);">
        <p style="color:var(--text-muted);">&copy; <?= date('Y') ?> <?= $site_name ?>. Visual Archive.</p>
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
