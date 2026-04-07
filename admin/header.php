<?php 
    $site_name = get_setting('site_name', 'SoftCo CMS'); 
    $theme_mode = get_setting('theme_mode', 'light');
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme_mode ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Dashboard' ?> | <?= $site_name ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Third Party Libraries -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        #content-area { transition: opacity 0.3s; }
        .loading { opacity: 0.5; pointer-events: none; }
    </style>
</head>
<body class="admin-body">
    <aside class="sidebar">
        <h2><?= strtoupper($site_name) ?></h2>
        <nav>
            <ul id="main-nav">
                <li><a href="dashboard.php" class="<?= $current_page == 'dashboard' ? 'active' : '' ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="sections.php" class="<?= $current_page == 'sections' ? 'active' : '' ?>"><i class="fas fa-layer-group"></i> Page Elements</a></li>
                <li><a href="services.php" class="<?= $current_page == 'services' ? 'active' : '' ?>"><i class="fas fa-concierge-bell"></i> Services</a></li>
                <li><a href="portfolio.php" class="<?= $current_page == 'portfolio' ? 'active' : '' ?>"><i class="fas fa-briefcase"></i> Portfolio</a></li>
                <li><a href="products.php" class="<?= $current_page == 'products' ? 'active' : '' ?>"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="news.php" class="<?= $current_page == 'news' ? 'active' : '' ?>"><i class="fas fa-newspaper"></i> News & Articles</a></li>
                <li><a href="inquiries.php" class="<?= $current_page == 'inquiries' ? 'active' : '' ?>"><i class="fas fa-file-invoice"></i> Project Proposals</a></li>
                <li><a href="leads.php" class="<?= $current_page == 'leads' ? 'active' : '' ?>"><i class="fas fa-envelope"></i> Contact Leads</a></li>
                <li><a href="gallery.php" class="<?= $current_page == 'gallery' ? 'active' : '' ?>"><i class="fas fa-images"></i> Photo Gallery</a></li>
                <li><a href="testimonials.php" class="<?= $current_page == 'testimonials' ? 'active' : '' ?>"><i class="fas fa-comment-dots"></i> Testimonials</a></li>
                <?php if ($_SESSION['role_name'] === 'Super Admin'): ?>
                    <li><a href="orders.php" class="<?= $current_page == 'orders' ? 'active' : '' ?>"><i class="fas fa-shopping-cart"></i> Product Orders</a></li>
                    <li><a href="users.php" class="<?= $current_page == 'users' ? 'active' : '' ?>"><i class="fas fa-users-cog"></i> User Management</a></li>
                    <li><a href="roles.php" class="<?= $current_page == 'roles' ? 'active' : '' ?>"><i class="fas fa-user-shield"></i> Roles & Permissions</a></li>
                    <li><a href="settings.php" class="<?= $current_page == 'settings' ? 'active' : '' ?>"><i class="fas fa-cog"></i> Web Settings</a></li>
                <?php endif; ?>
                <li style="margin-top: 3rem;"><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>
    <main class="main-wrapper">
        <header class="admin-header">
            <div id="page-indicator">
                <h1 style="font-size: 1.5rem;" id="current-page-title"><?= $page_title ?? 'Dashboard' ?></h1>
            </div>
            <div style="font-size: 0.9rem; font-weight: 600;">
                <span style="background: #e0e7ff; color: #4338ca; padding: 0.5rem 1rem; border-radius: 20px;">
                    <?= htmlspecialchars($_SESSION['full_name']) ?>
                </span>
            </div>
        </header>
        <div id="content-area">
