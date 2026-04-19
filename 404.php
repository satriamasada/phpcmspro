<?php
// 404.php
http_response_code(404);

require_once __DIR__ . '/config/database.php';

$site_name = get_setting('site_name', 'SoftCo Tech');
$theme_mode = get_setting('theme_mode', 'light');
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme_mode ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | <?= $site_name ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            background: radial-gradient(circle at 30% 20%, var(--bg-blue) 0%, var(--bg-light) 60%);
        }

        [data-theme="dark"] .error-page {
            background: radial-gradient(circle at 30% 20%, #020617 0%, #0f172a 60%);
        }

        /* Floating particles */
        .error-page::before,
        .error-page::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            z-index: 0;
        }
        .error-page::before {
            width: 400px;
            height: 400px;
            background: var(--primary);
            top: -100px;
            right: -100px;
            animation: pulse-glow 4s ease-in-out infinite;
        }
        .error-page::after {
            width: 300px;
            height: 300px;
            background: var(--secondary);
            bottom: -80px;
            left: -80px;
            animation: pulse-glow 4s ease-in-out infinite 2s;
        }

        @keyframes pulse-glow {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.2); opacity: 0.5; }
        }

        .error-content {
            position: relative;
            z-index: 2;
        }

        .error-code {
            font-size: clamp(8rem, 20vw, 14rem);
            font-weight: 900;
            line-height: 1;
            margin-bottom: 1rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            animation: float-code 3s ease-in-out infinite;
        }

        @keyframes float-code {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .error-code::after {
            content: '404';
            position: absolute;
            top: 8px;
            left: 8px;
            font-size: inherit;
            font-weight: inherit;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(14, 165, 233, 0.1));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            z-index: -1;
        }

        .error-title {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .error-desc {
            font-size: 1.15rem;
            color: var(--text-muted);
            max-width: 500px;
            margin: 0 auto 2.5rem;
            line-height: 1.8;
        }

        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .error-actions .btn {
            padding: 1rem 2.5rem;
            font-size: 1rem;
            border-radius: 12px;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .error-actions .btn:hover {
            transform: translateY(-3px);
        }

        /* Floating icons decoration */
        .floating-icon {
            position: absolute;
            color: var(--primary);
            opacity: 0.08;
            z-index: 1;
            animation: floatingIcon 8s ease-in-out infinite;
        }

        @keyframes floatingIcon {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(15px, -25px) rotate(8deg); }
            50% { transform: translate(-10px, -40px) rotate(-5deg); }
            75% { transform: translate(20px, -15px) rotate(12deg); }
        }

        /* Search suggestion */
        .error-search {
            margin-top: 3rem;
            display: flex;
            justify-content: center;
            gap: 0;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
        }

        .error-search input {
            flex: 1;
            padding: 1rem 1.5rem;
            border: 2px solid var(--border);
            border-right: none;
            border-radius: 12px 0 0 12px;
            font-family: var(--font-main);
            font-size: 1rem;
            background: var(--bg-light);
            color: var(--text-main);
            outline: none;
            transition: border-color 0.3s;
        }

        .error-search input:focus {
            border-color: var(--primary);
        }

        .error-search button {
            padding: 1rem 1.5rem;
            background: var(--primary);
            color: white;
            border: 2px solid var(--primary);
            border-radius: 0 12px 12px 0;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background 0.3s;
        }

        .error-search button:hover {
            background: #0052cc;
        }

        /* Quick links */
        .quick-links {
            margin-top: 3rem;
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .quick-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--bg-light);
            transition: all 0.3s;
        }

        .quick-link:hover {
            color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.15);
        }

        .quick-link i {
            font-size: 0.85rem;
            color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 600px) {
            .error-actions {
                flex-direction: column;
                align-items: center;
            }
            .quick-links {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <!-- Floating decoration icons -->
    <div class="floating-icon" style="top:10%; left:10%; font-size:5rem; animation-delay:0s;"><i class="fas fa-satellite-dish"></i></div>
    <div class="floating-icon" style="top:25%; right:12%; font-size:3.5rem; animation-delay:1s;"><i class="fas fa-compass"></i></div>
    <div class="floating-icon" style="bottom:20%; left:8%; font-size:4rem; animation-delay:2s;"><i class="fas fa-map-signs"></i></div>
    <div class="floating-icon" style="bottom:15%; right:15%; font-size:3rem; animation-delay:3s;"><i class="fas fa-ghost"></i></div>
    <div class="floating-icon" style="top:60%; left:25%; font-size:2.5rem; animation-delay:1.5s;"><i class="fas fa-bug"></i></div>

    <div class="error-page">
        <div class="error-content">
            <div class="error-code">404</div>
            <h1 class="error-title">Oops! Halaman Tidak Ditemukan</h1>
            <p class="error-desc">
                Halaman yang Anda cari mungkin telah dipindahkan, dihapus, atau alamat URL-nya salah ketik.
            </p>

            <div class="error-actions">
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-home" style="margin-right:0.5rem;"></i> Kembali ke Beranda
                </a>
                <a href="javascript:history.back()" class="btn btn-outline">
                    <i class="fas fa-arrow-left" style="margin-right:0.5rem;"></i> Halaman Sebelumnya
                </a>
            </div>

            <div class="quick-links">
                <a href="/#services" class="quick-link"><i class="fas fa-cogs"></i> Services</a>
                <a href="/#portfolio" class="quick-link"><i class="fas fa-briefcase"></i> Portfolio</a>
                <a href="/#products" class="quick-link"><i class="fas fa-box"></i> Products</a>
                <a href="/#news" class="quick-link"><i class="fas fa-newspaper"></i> News</a>
                <a href="/#contact" class="quick-link"><i class="fas fa-envelope"></i> Contact</a>
            </div>
        </div>
    </div>

    <script>
        // Initialize theme from storage
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        }
    </script>
</body>
</html>
