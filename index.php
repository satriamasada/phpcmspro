<?php
// index.php

require_once 'config/database.php';

// Fetch Data
$services = $pdo->query("SELECT * FROM services ORDER BY id DESC LIMIT 6")->fetchAll();
$projects = $pdo->query("SELECT * FROM portfolio ORDER BY id DESC LIMIT 3")->fetchAll();
$products = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 3")->fetchAll();
$news = $pdo->query("SELECT n.*, u.full_name FROM news n JOIN users u ON n.author_id = u.id WHERE n.is_published = 1 ORDER BY n.created_at DESC LIMIT 3")->fetchAll();

// Fetch Static Texts from sections table
$hero_text = $pdo->query("SELECT * FROM sections WHERE section_key = 'hero'")->fetch();
$about_text = $pdo->query("SELECT * FROM sections WHERE section_key = 'about'")->fetch();

$gallery = $pdo->query("SELECT * FROM galleries ORDER BY created_at DESC LIMIT 3")->fetchAll();
$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 10")->fetchAll();

$site_name = get_setting('site_name', 'SoftCo Tech');
$theme_mode = get_setting('theme_mode', 'light');
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme_mode ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_name ?> | Innovating Software Solutions</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="logo"><?= strtoupper($site_name) ?></div>
        <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#portfolio">Our Work</a></li>
            <li><a href="#products">Products</a></li>
            <li><a href="#news">News</a></li>
            <li><a href="admin/login.php" style="color: var(--primary); border: 2px solid var(--primary); padding: 0.5rem 1.25rem; border-radius: 8px;">Login</a></li>
            <li><button onclick="toggleTheme()" style="background:none; border:none; cursor:pointer; color:var(--text-main); font-size:1.2rem;"><i class="fas fa-adjust"></i></button></li>
        </ul>
    </nav>

    <!-- Hero -->
    <section class="hero" id="home">
        <!-- Floating Elements -->
        <i class="fas fa-code hero-icon" style="top:20%; right:15%; font-size:4rem; animation-delay: 1s;"></i>
        <i class="fas fa-cloud hero-icon" style="top:50%; right:25%; font-size:3rem; animation-delay: 2s;"></i>
        <i class="fas fa-microchip hero-icon" style="bottom:15%; right:10%; font-size:5rem; animation-delay: 3s;"></i>
        
        <div style="position:relative; z-index:2;">
            <h1 class="fade-up"><?= $hero_text['title'] ?? 'Innovating the <span class="accent-text">Future</span> of Software.' ?></h1>
            <p class="fade-up" style="transition-delay:0.2s;"><?= $hero_text['subtitle'] ?? 'We engineer state-of-the-art digital ecosystems for visionaries and industry leaders.' ?></p>
            <div class="cta-group fade-up" style="transition-delay:0.4s;">
                <a href="#portfolio" class="btn btn-primary">View Our Work</a>
                <a href="#contact" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="bg-soft-slate parallax-section" style="background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1920&q=20'); background-blend-mode: overlay;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:4rem; align-items:center;">
            <div class="fade-up">
                <span style="color:var(--primary); font-weight:700; letter-spacing:1px; text-transform:uppercase; font-size:0.85rem;">Who We Are</span>
                <h2 style="font-size:2.5rem; margin-top:1rem; margin-bottom:1.5rem; color:var(--dark);"><?= $about_text['title'] ?? 'We are SoftCo.' ?></h2>
                <p style="color:var(--text-muted); font-size:1.1rem; margin-bottom:1.5rem;"><?= $about_text['subtitle'] ?? 'A team of passionate engineers, designers, and thinkers.' ?></p>
                <p style="color:var(--text-muted); line-height:1.8;"><?= $about_text['content'] ?? 'Founded in 2020, SoftCo has helped over 50+ companies worldwide to scale their digital infrastructure.' ?></p>
            </div>
            <div class="fade-up floating" style="transition-delay:0.3s; position:relative;">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80" style="width:100%; border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,0.1);">
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="bg-white">
        <div class="section-head">
            <h2 class="fade-up">Exceptional <span class="accent-text">Services</span></h2>
            <div class="underline fade-up" style="transition-delay:0.2s;"></div>
        </div>
        <div class="grid-container">
            <?php foreach ($services as $s): ?>
            <div class="card fade-up">
                <i class="<?= $s['icon'] ?>"></i>
                <h3 style="margin-bottom:1rem;"><?= htmlspecialchars($s['title']) ?></h3>
                <p style="color:var(--text-muted); font-size:0.95rem; margin-bottom:1.5rem;"><?= htmlspecialchars(substr($s['description'],0,100)) ?>...</p>
                <a href="service-detail.php?id=<?= $s['id'] ?>" style="color:var(--primary); text-decoration:none; font-weight:700; font-size:0.85rem; text-transform:uppercase; letter-spacing:1px;">Learn More &rarr;</a>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top:4rem;" class="fade-up">
            <a href="services.php" class="btn btn-outline" style="padding:1rem 3rem;">Explore All Services</a>
        </div>
    </section>

    <!-- Our Work (Portfolio) -->
    <section id="portfolio" class="bg-soft-blue">
        <div class="section-head">
            <h2 class="fade-up">Our <span class="accent-text">Latest Work</span></h2>
            <p class="fade-up" style="color:var(--text-muted); margin-top:1rem;">Building scalable solutions across all sectors.</p>
        </div>
        <div class="grid-container">
            <?php foreach ($projects as $p): ?>
            <a href="portfolio-detail.php?id=<?= $p['id'] ?>" class="project-card fade-up" style="display:block; text-decoration:none;">
                <img src="<?= $p['image_url'] ?>" class="project-img">
                <div class="project-overlay">
                    <span style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--secondary);"><?= $p['category'] ?></span>
                    <h3 style="margin-top:0.5rem;"><?= htmlspecialchars($p['title']) ?></h3>
                    <span style="font-size:0.85rem; color:#fff; border-bottom:1px solid rgba(255,255,255,0.3); padding-bottom:5px;">View Detail</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top:4rem;" class="fade-up">
            <a href="portfolio.php" class="btn btn-outline" style="padding:1rem 3rem;">View Full Portfolio</a>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="bg-white">
        <div class="section-head">
            <h2 class="fade-up">Visual <span class="accent-text">Showcase</span></h2>
            <p class="fade-up" style="color:var(--text-muted); margin-top:1rem;">Highlights from our latest events and office life.</p>
        </div>
        <div class="grid-container" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:1.5rem;">
            <?php foreach ($gallery as $g): ?>
            <a href="gallery-detail.php?id=<?= $g['id'] ?>" class="fade-up" style="display:block; text-decoration:none; position:relative; height:350px; border-radius:20px; overflow:hidden; border:1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                <img src="<?= $g['image_path'] ?>" style="width:100%; height:100%; object-fit:cover; transition:transform 0.8s cubic-bezier(0.23, 1, 0.32, 1);" onmouseover="this.style.transform='scale(1.15) rotate(1deg)'" onmouseout="this.style.transform='scale(1)'">
                <div style="position:absolute; bottom:0; left:0; width:100%; padding:2.5rem; background:linear-gradient(transparent, rgba(0,0,0,0.9)); color:white; transform: translateY(10px); transition: transform 0.3s;">
                    <span style="font-size:0.75rem; text-transform:uppercase; letter-spacing:3px; color:var(--primary); font-weight:800;"><?= htmlspecialchars($g['category']) ?></span>
                    <h4 style="font-size:1.25rem; margin-top:0.5rem; font-weight:700;"><?= htmlspecialchars($g['title']) ?></h4>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top:4rem;" class="fade-up">
            <a href="gallery.php" class="btn btn-outline" style="padding:1rem 3rem;">View Extended Gallery</a>
        </div>
    </section>

    <!-- Products -->
    <section id="products" class="bg-white">
        <div class="section-head">
            <h2 class="fade-up">Featured <span class="accent-text">Products</span></h2>
            <p class="fade-up" style="color:var(--text-muted); margin-top:1rem;">Ready-to-use software for your business growth.</p>
        </div>
        <div class="grid-container">
            <?php 
                foreach ($products as $pr): 
                $img_src = $pr['image_url'] ?: 'https://via.placeholder.com/400x300';
                // No prefix needed for root relative uploads
            ?>
            <div class="card fade-up product-card" style="padding:0; overflow:hidden;">
                <img src="<?= $img_src ?>" style="width:100%; height:200px; object-fit:cover;">
                <div style="padding:2rem;">
                <h3 style="margin-bottom:1rem;"><?= htmlspecialchars($pr['name']) ?></h3>
                <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:1.5rem;"><?= htmlspecialchars(substr($pr['description'],0,100)) ?>...</p>
                <div class="product-price">Rp <?= number_format($pr['price'], 0, ',', '.') ?></div>
                <div style="display:flex; gap:1rem; justify-content: center;">
                    <a href="product-detail.php?id=<?= $pr['id'] ?>" class="btn btn-primary" style="font-size:0.8rem; padding:8px 15px;">Detail</a>
                    <a href="checkout.php?id=<?= $pr['id'] ?>" class="btn btn-outline" style="font-size:0.8rem; padding:8px 15px;">Buy Now</a>
                </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top:4rem;" class="fade-up">
            <a href="products.php" class="btn btn-outline" style="padding:1rem 3rem;">Browse All Products</a>
        </div>
    </section>

    <!-- Blog/News -->
    <section id="news" class="bg-soft-indigo">
        <div class="section-head">
            <h2 class="fade-up">Insights & <span class="accent-text">News</span></h2>
            <p class="fade-up" style="color:var(--text-muted); margin-top:1rem;">Latest updates from the tech world.</p>
        </div>
        <div class="grid-container">
            <?php 
                foreach ($news as $n): 
                $img_src = $n['featured_image'] ?: 'https://via.placeholder.com/800x400';
            ?>
            <div class="card fade-up" style="padding:0; overflow:hidden;">
                <img src="<?= $img_src ?>" style="width:100%; height:200px; object-fit:cover;">
                <div style="padding:2.5rem;">
                    <span style="font-size:0.7rem; color:var(--text-muted); font-weight:700;"><?= date('M d, Y', strtotime($n['created_at'])) ?></span>
                    <h3 style="margin:1rem 0;"><?= htmlspecialchars($n['title']) ?></h3>
                    <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:1.5rem;"><?= htmlspecialchars(substr(strip_tags($n['content']),0,100)) ?>...</p>
                    <a href="news-detail.php?slug=<?= $n['slug'] ?>" style="color:var(--primary); text-decoration:none; font-weight:700; font-size:0.9rem;">Read More &rarr;</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top:4rem;" class="fade-up">
            <a href="news.php" class="btn btn-outline" style="padding:1rem 3rem;">View All News</a>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="bg-soft-slate" style="position:relative; overflow:hidden;">
        <div class="section-head">
            <h2 class="fade-up">Trusted by <span class="accent-text">Visionaries</span></h2>
            <p class="fade-up" style="color:var(--text-muted); margin-top:1rem;">What our industry partners say about our engineering excellence.</p>
        </div>
        
        <!-- Modern Scroll Snap Slider -->
        <div class="fade-up" style="display:flex; overflow-x:auto; gap:2.5rem; padding:1rem 2rem 4rem; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; scrollbar-width: none;">
            <style>
                #testimonials::-webkit-scrollbar { display:none; }
                .testimonial-card {
                    flex: 0 0 calc(33.333% - 2rem);
                    min-width: 350px;
                    scroll-snap-align: start;
                    background: var(--bg-light);
                    padding: 3.5rem;
                    border-radius: 24px;
                    border: 1px solid var(--border);
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }
                @media (max-width: 900px) { .testimonial-card { flex: 0 0 100%; min-width: 100%; } }
            </style>
            <?php foreach ($testimonials as $t): ?>
            <div class="testimonial-card">
                <div>
                    <div style="margin-bottom:2rem; display:flex; gap:5px;">
                        <?php for($i=0; $i<$t['rating']; $i++): ?><i class="fas fa-star" style="color:#f59e0b; font-size:14px;"></i><?php endfor; ?>
                    </div>
                    <p style="font-size:1.15rem; color:var(--text-main); line-height:1.8; font-weight:500; font-style:italic;">"<?= htmlspecialchars($t['content']) ?>"</p>
                </div>
                <div style="display:flex; align-items:center; gap:1.5rem; margin-top:3rem; padding-top:2rem; border-top:1px solid var(--border);">
                    <img src="<?= $t['client_image'] ?>" onerror="this.src='https://via.placeholder.com/80?text=Client'" style="width:65px; height:65px; border-radius:50%; object-fit:cover; border:3px solid var(--primary);">
                    <div>
                        <h4 style="font-size:1.25rem; color:var(--dark); margin:0; font-weight:800;"><?= htmlspecialchars($t['client_name']) ?></h4>
                        <small style="color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:1px; font-size:0.75rem;"><?= htmlspecialchars($t['client_company']) ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="bg-soft-slate parallax-section" style="background-image: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=1920&q=20'); background-blend-mode: overlay;">
        <div style="max-width:900px; margin:0 auto; display:grid; grid-template-columns:1fr 1fr; gap:5rem; align-items:center;">
            <div class="fade-up">
                <h2 style="font-size:2.5rem; margin-bottom:1.5rem;">Let's Build <span class="accent-text">Something</span> Together.</h2>
                <p style="color:var(--text-muted); font-size:1.1rem; margin-bottom:2rem;">Contact us today to start your digital transformation journey.</p>
                <div style="color:var(--dark); font-weight:700;">
                    <p><i class="fas fa-envelope" style="color:var(--primary); margin-right:1rem;"></i> <?= get_setting('site_email', 'hello@softco.tech') ?></p>
                    <p style="margin-top:1rem;"><i class="fas fa-phone" style="color:var(--primary); margin-right:1rem;"></i> <?= get_setting('site_phone', '+62 812 3456 7890') ?></p>
                </div>
            </div>
            <div class="fade-up" style="transition-delay:0.3s;">
                <form action="submit-form.php" method="POST" class="card" style="box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                    <div style="margin-bottom:1.5rem;">
                        <input type="text" name="name" placeholder="Full Name" required style="width:100%; padding:1rem; border:1px solid var(--border); border-radius:8px;">
                    </div>
                    <div style="margin-bottom:1.5rem;">
                        <input type="email" name="email" placeholder="Email Address" required style="width:100%; padding:1rem; border:1px solid var(--border); border-radius:8px;">
                    </div>
                    <div style="margin-bottom:1.5rem;">
                        <textarea name="message" placeholder="Project Details" rows="4" required style="width:100%; padding:1rem; border:1px solid var(--border); border-radius:8px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%; border:none; cursor:pointer;">Send Inquiry</button>
                    <?php if (isset($_GET['success'])): ?>
                        <p style="color:var(--accent); font-weight:700; margin-top:1rem; text-align:center;">Message sent successfully!</p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background:var(--bg-soft); padding:80px 10% 40px; border-top:1px solid var(--border);">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:4rem; margin-bottom:60px;">
            <div>
                <div class="logo" style="margin-bottom:2rem;"><?= strtoupper($site_name) ?></div>
                <p style="color:var(--text-muted); line-height:1.8;"><?= get_setting('site_motto', 'Innovating the Future of Software.') ?></p>
            </div>
            <div>
                <h4 style="margin-bottom:2rem; color:var(--dark);">Quick Links</h4>
                <ul style="list-style:none; line-height:2.5;">
                    <li><a href="#about" style="text-decoration:none; color:var(--text-muted);">About Us</a></li>
                    <li><a href="#services" style="text-decoration:none; color:var(--text-muted);">Our Services</a></li>
                    <li><a href="#products" style="text-decoration:none; color:var(--text-muted);">Product Catalog</a></li>
                </ul>
            </div>
            <div>
                <h4 style="margin-bottom:2rem; color:var(--dark);">Stay Connected</h4>
                <p style="color:var(--text-muted); margin-bottom:1rem;"><i class="fas fa-envelope" style="margin-right:10px;"></i> <?= get_setting('site_email') ?></p>
                <p style="color:var(--text-muted); margin-bottom:1rem;"><i class="fas fa-phone" style="margin-right:10px;"></i> <?= get_setting('site_phone') ?></p>
                <p style="color:var(--text-muted);"><i class="fas fa-map-marker-alt" style="margin-right:10px;"></i> <?= get_setting('site_address') ?></p>
            </div>
        </div>
        <div style="text-align:center; padding-top:40px; border-top:1px solid var(--border); color:var(--text-muted); font-size:0.9rem;">
            <?= get_setting('footer_text', '&copy; ' . date('Y') . ' SoftCo Tech Solutions. All Rights Reserved.') ?>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            const target = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', target);
            localStorage.setItem('theme', target);
        }

        // Initialize theme from storage if exists
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        }

        // AOS-like fade up animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('aos-animate');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
    </script>
</body>
</html>
