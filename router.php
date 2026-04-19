<?php
/**
 * Router minimal untuk PHP Built-in Development Server
 * 
 * Jalankan dengan:
 *   php -S localhost:8080 router.php
 * 
 * Router ini HANYA menangani halaman 404.
 * Semua file yang ada di disk akan dilayani langsung oleh server.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$filePath = __DIR__ . $uri;

// Blokir akses ke dotfiles (.env, .git, dll)
$basename = basename($uri);
if (strpos($basename, '.') === 0 && $basename !== '.') {
    http_response_code(403);
    echo '403 Forbidden';
    return true;
}

// Jika file ada di disk, biarkan server handle
if (is_file($filePath)) {
    return false;
}

// Jika direktori dengan index.php, biarkan server handle
if (is_dir($filePath)) {
    if (is_file(rtrim($filePath, '/') . '/index.php')) {
        return false;
    }
    return false;
}

// File tidak ditemukan → tampilkan halaman 404
include __DIR__ . '/404.php';
return true;
