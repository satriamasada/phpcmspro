<?php
// admin/dashboard-content.php
require_once __DIR__ . '/../includes/auth.php';
if (!isset($_SESSION['user_id'])) exit;

// Simple stats
$stats = [
    'services' => $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn(),
    'portfolio' => $pdo->query("SELECT COUNT(*) FROM portfolio")->fetchColumn(),
    'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'news' => $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn(),
    'messages' => $pdo->query("SELECT COUNT(*) FROM contact_leads WHERE status = 'unread'")->fetchColumn(),
    'visitors_total' => $pdo->query("SELECT COUNT(DISTINCT ip_address) FROM visitors")->fetchColumn() ?: 0,
    'visitors_today' => $pdo->query("SELECT COUNT(DISTINCT ip_address) FROM visitors WHERE visit_date = CURDATE()")->fetchColumn() ?: 0,
    'hits_today' => $pdo->query("SELECT SUM(hits) FROM visitors WHERE visit_date = CURDATE()")->fetchColumn() ?: 0
];
?>

<section class="grid-3" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <div class="admin-card" style="text-align: center;">
        <p style="color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Services</p>
        <h3 style="font-size: 2rem;"><?= $stats['services'] ?></h3>
    </div>
    <div class="admin-card" style="text-align: center;">
        <p style="color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Portfolio</p>
        <h3 style="font-size: 2rem;"><?= $stats['portfolio'] ?></h3>
    </div>
    <div class="admin-card" style="text-align: center;">
        <p style="color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Products</p>
        <h3 style="font-size: 2rem;"><?= $stats['products'] ?></h3>
    </div>
    <div class="admin-card" style="text-align: center;">
        <p style="color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Unread Leads</p>
        <h3 style="font-size: 2rem; color: #ef4444;"><?= $stats['messages'] ?></h3>
    </div>
</section>

<h3 style="margin-bottom: 1rem; color: #1e293b;"><i class="fas fa-chart-line"></i> Traffic Overview</h3>
<section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <div class="admin-card" style="text-align: center; border-bottom: 4px solid #3b82f6;">
        <p style="color: #64748b; font-size: 0.85rem; text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;"><i class="fas fa-globe"></i> Total Visitors</p>
        <h3 style="font-size: 2.2rem; margin: 0; color: #0f172a;"><?= number_format($stats['visitors_total']) ?></h3>
    </div>
    <div class="admin-card" style="text-align: center; border-bottom: 4px solid #10b981;">
        <p style="color: #64748b; font-size: 0.85rem; text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;"><i class="fas fa-user-clock"></i> Visitors Today</p>
        <h3 style="font-size: 2.2rem; margin: 0; color: #0f172a;"><?= number_format($stats['visitors_today']) ?></h3>
    </div>
    <div class="admin-card" style="text-align: center; border-bottom: 4px solid #f59e0b;">
        <p style="color: #64748b; font-size: 0.85rem; text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;"><i class="fas fa-mouse-pointer"></i> Page Hits Today</p>
        <h3 style="font-size: 2.2rem; margin: 0; color: #0f172a;"><?= number_format($stats['hits_today']) ?></h3>
    </div>
</section>
<div class="admin-card">
    <h2 style="margin-bottom: 1.5rem;">Recent Activity</h2>
    <p style="color: #94a3b8;">Welcome back, <?= htmlspecialchars($_SESSION['full_name']) ?>! You are using the new SPA Management interface with DataTables integration.</p>
</div>
