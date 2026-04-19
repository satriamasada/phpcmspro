<?php
// news-detail.php
require_once 'config/database.php';
// Pastikan file yang berisi fungsi get_setting() sudah di-include
// require_once 'includes/functions.php'; 

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT n.*, u.full_name FROM news n JOIN users u ON n.author_id = u.id WHERE n.slug = ? AND n.is_published = 1");
$stmt->execute([$slug]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: index.php');
    exit;
}

// Menggunakan fungsi get_setting sesuai saran Anda
$site_name = get_setting('site_name', 'SoftCo Tech');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> | <?= htmlspecialchars($site_name) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .article-hero {
            padding: 150px 10% 80px;
            background: var(--bg-soft);
            text-align: center;
        }
        .article-content {
            max-width: 800px;
            margin: -50px auto 100px;
            background: white;
            padding: 4rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.05);
            line-height: 1.8;
            font-size: 1.1rem;
            color: var(--text-main);
        }
        /* Menghindari overflow pada konten dari editor teks */
        .tinymce-content img { max-width: 100%; height: auto; border-radius: 8px; }
    </style>
</head>
<body>
    <nav>
        <div class="logo"><?= strtoupper(htmlspecialchars($site_name)) ?></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php#news">Back to News</a></li>
        </ul>
    </nav>

    <header class="article-hero">
        <div class="fade-up">
            <span style="font-size:0.85rem; color:var(--primary); font-weight:700; text-transform:uppercase; letter-spacing:1px;">
                <?= date('M d, Y', strtotime($article['created_at'])) ?>
            </span>
            <h1 style="font-size:3rem; margin-top:1.5rem; color:var(--dark); line-height:1.2;"><?= htmlspecialchars($article['title']) ?></h1>
            <p style="margin-top:1.5rem; color:var(--text-muted);">Written by <strong><?= htmlspecialchars($article['full_name']) ?></strong></p>
        </div>
    </header>

    <article class="article-content fade-up" style="transition-delay:0.3s;">
        <?php 
            $img_src = $article['featured_image'] ? (strpos($article['featured_image'], 'http') === 0 ? $article['featured_image'] : ltrim($article['featured_image'], './')) : 'https://via.placeholder.com/800x400';
        ?>
        <img src="<?= htmlspecialchars($img_src) ?>" 
             alt="<?= htmlspecialchars($article['title']) ?>"
             style="width:100%; height:400px; object-fit:cover; border-radius:12px; margin-bottom:3rem;">
        
        <div class="tinymce-content">
            <?php 
                // Jika konten dari TinyMCE/Editor biasanya sudah ada tag HTML, 
                // maka kita tidak menggunakan htmlspecialchars agar format tetap terjaga.
                // Namun pastikan konten sudah di-sanitize saat input ke database.
                echo $article['content']; 
            ?>
        </div>
        
        <div style="margin-top:4rem; padding-top:2rem; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
            <a href="index.php#news" style="color:var(--primary); text-decoration:none; font-weight:700;">&larr; Back to Articles</a>
            <div style="display:flex; gap:1rem;">
                <a href="#" style="color:var(--text-muted);"><i class="fab fa-twitter"></i></a>
                <a href="#" style="color:var(--text-muted);"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </article>

    <footer style="padding:100px 8%; border-top:1px solid var(--border); font-size:0.9rem;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div class="logo"><?= strtoupper(htmlspecialchars($site_name)) ?></div>
            <p style="color:var(--text-muted);">&copy; <?= date('Y') ?> <?= htmlspecialchars($site_name) ?>. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.fade-up').forEach(el => el.classList.add('aos-animate'));
        });
    </script>
</body>
</html>
