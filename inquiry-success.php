<?php
// inquiry-success.php
$site_name = "SoftCo.tech";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inquiry Sent | <?= $site_name ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background:var(--bg-soft); min-height:100vh; display:flex; align-items:center; justify-content:center;">
    <div class="card fade-up" style="max-width:500px; text-align:center; padding:4rem; box-shadow:0 30px 80px rgba(0,0,0,0.1);">
        <i class="fas fa-paper-plane" style="font-size:4rem; color:var(--primary); margin-bottom:2rem;"></i>
        <h1 style="font-size:2.5rem; margin-bottom:1rem;">We Got <span class="accent-text">It!</span></h1>
        <p style="color:var(--text-muted); font-size:1.1rem; margin-bottom:3rem; line-height:1.6;">Thank you for your interest. Our consultants will review your request and contact you within 24 business hours.</p>
        <a href="index.php" class="btn btn-primary" style="padding:1.25rem 3rem;">Home Page</a>
    </div>
    <script>document.querySelectorAll('.fade-up').forEach(el => el.classList.add('aos-animate'));</script>
</body>
</html>
