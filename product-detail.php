<?php
// product-detail.php
require_once 'config/database.php';
// Pastikan fungsi get_setting tersedia
// require_once 'includes/functions.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
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
    <title><?= htmlspecialchars($product['name']) ?> | <?= htmlspecialchars($site_name) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <nav>
        <div class="logo"><?= strtoupper(htmlspecialchars($site_name)) ?></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php#products">Products</a></li>
        </ul>
    </nav>

    <section class="hero" style="min-height:45vh; background:var(--bg-soft); text-align:center; padding-top:100px;">
        <span style="font-size:1.5rem; color:var(--primary); font-weight:800; display:block; margin-bottom:1rem;">
            Rp <?= number_format($product['price'], 0, ',', '.') ?>
        </span>
        <h1 style="font-size:3rem; margin-top:1rem;"><?= htmlspecialchars($product['name']) ?></h1>
    </section>

    <section style="padding:100px 10%;">
        <div class="card fade-up" style="max-width:1000px; margin:0 auto; padding:4rem; line-height:2;">
            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                style="width:100%; height:450px; object-fit:cover; border-radius:16px; margin-bottom:3rem; box-shadow:0 10px 30px rgba(0,0,0,0.1);">

            <div style="font-size:1.1rem; color:var(--text-main);">
                <?= nl2br(htmlspecialchars($product['description'])) ?>
            </div>

            <div style="margin-top:4rem; display:flex; gap:2rem; align-items:center; justify-content: center;">
                <a href="checkout.php?id=<?= (int) $product['id'] ?>" class="btn btn-primary"
                    style="padding:1.5rem 3rem; font-size:1.2rem; text-decoration:none;">Purchase Now</a>
                <a href="#ask-question" class="btn btn-outline"
                    style="padding:1.5rem 3rem; font-size:1.1rem; text-decoration:none;">Ask Question</a>
            </div>
        </div>
    </section>

    <section id="ask-question" class="bg-soft-emerald" style="padding:80px 10%; border-top:1px solid var(--border-light);">
        <div class="fade-up" style="max-width:800px; margin:0 auto;">
            <div class="section-head" style="margin-bottom: 3rem;">
                <h3 style="font-size:2.5rem; margin-bottom:1rem; text-align:center;">Product <span class="accent-text">Inquiry</span></h3>
                <div class="underline"></div>
                <p style="text-align:center; color: var(--text-muted); margin-top:1.5rem;">Have questions about specification or licensing? Our team is ready to help you with anything related to <?= htmlspecialchars($product['name']) ?>.</p>
            </div>

            <form action="process-inquiry.php" method="POST" class="card"
                style="box-shadow: var(--shadow-lg); max-width:700px; margin:0 auto; padding:3.5rem;">
                <input type="hidden" name="type" value="product">
                <input type="hidden" name="related_id" value="<?= (int) $product['id'] ?>">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
                    <div class="form-group">
                        <label style="display:block; margin-bottom:0.5rem; font-weight:600; font-size:0.9rem;">Full Name</label>
                        <input type="text" name="name" placeholder="John Doe" required
                            style="width:100%; padding:1rem; border:1px solid var(--border); border-radius:8px;">
                    </div>
                    <div class="form-group">
                        <label style="display:block; margin-bottom:0.5rem; font-weight:600; font-size:0.9rem;">Email Address</label>
                        <input type="email" name="email" placeholder="john@example.com" required
                            style="width:100%; padding:1rem; border:1px solid var(--border); border-radius:8px;">
                    </div>
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600; font-size:0.9rem;">Phone Number</label>
                    <input type="tel" name="phone" placeholder="+62 812..." required
                        style="width:100%; padding:1rem; border:1px solid var(--border); border-radius:8px;">
                </div>

                <div style="margin-bottom:2rem;">
                    <label style="display:block; margin-bottom:0.5rem; font-weight:600; font-size:0.9rem;">Your Question</label>
                    <textarea name="message" placeholder="What specific information do you need about <?= htmlspecialchars($product['name']) ?>?" rows="5" required
                        style="width:100%; padding:1rem; border:1px solid var(--border); border-radius:8px; line-height:1.6;"></textarea>
                </div>

                <button type="submit" class="btn btn-primary"
                    style="width:100%; border:none; cursor:pointer; padding:1.25rem; font-size:1.1rem; font-weight:700;">Send Inquiry</button>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.fade-up').forEach(el => el.classList.add('aos-animate'));
        });
    </script>
</body>

</html>