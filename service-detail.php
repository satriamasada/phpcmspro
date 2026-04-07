<?php
// service-detail.php
require_once 'config/database.php';
// Pastikan file yang berisi fungsi get_setting() sudah di-require di sini
// require_once 'includes/functions.php'; 

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch();

if (!$service) { 
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
    <title><?= htmlspecialchars($service['title']) ?> | <?= htmlspecialchars($site_name) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav>
        <div class="logo"><?= strtoupper(htmlspecialchars($site_name)) ?></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php#services">Services</a></li>
        </ul>
    </nav>

    <section class="hero" style="min-height:40vh; background:var(--bg-soft); text-align:center; padding-top:100px;">
        <i class="<?= htmlspecialchars($service['icon']) ?>" style="font-size:4rem; color:var(--primary); margin-bottom:2rem; display:block;"></i>
        <h1 style="font-size:3rem;"><?= htmlspecialchars($service['title']) ?></h1>
    </section>

    <section style="padding:80px 10%;">
        <div class="card fade-up" style="max-width:800px; margin:0 auto; padding:4rem; line-height:2;">
            <p><?= nl2br(htmlspecialchars($service['description'])) ?></p>
            
            <div style="margin-top:3rem; border-top:1px solid var(--border); padding-top:3rem;">
                <h3 style="margin-bottom:2rem; font-size:1.5rem; text-align:center;">Request a <span class="accent-text">Proposal</span></h3>
                
                <form action="process-inquiry.php" method="POST" class="card" style="box-shadow: 0 10px 40px rgba(0,0,0,0.02); max-width:600px; margin:0 auto; padding:3rem;">
                    <input type="hidden" name="type" value="service">
                    <input type="hidden" name="related_id" value="<?= (int)$service['id'] ?>">
                    
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
                        <input type="text" name="name" placeholder="Full Name" required style="width:100%; padding:1rem; border:1px solid var(--border); border-radius:8px;">
                        <input type="email" name="email" placeholder="Email Address" required style="width:100%; padding:1rem; border:1px solid var(--border); border-radius:8px;">
                    </div>
                    
                    <div style="margin-bottom:1.5rem;">
                        <input type="tel" name="phone" placeholder="Phone Number" required style="width:100%; padding:1rem; border:1px solid var(--border); border-radius:8px;">
                    </div>
                    
                    <div style="margin-bottom:2rem;">
                        <textarea name="message" placeholder="What are your goals with this project?" rows="4" required style="width:100%; padding:1rem; border:1px solid var(--border); border-radius:8px; line-height:1.6;"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width:100%; border:none; cursor:pointer; padding:1.25rem;">Submit Inquiry</button>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Memastikan AOS-like animation berjalan setelah DOM siap
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.fade-up').forEach(el => el.classList.add('aos-animate'));
        });
    </script>
</body>
</html>