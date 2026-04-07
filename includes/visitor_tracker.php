<?php
// includes/visitor_tracker.php

/**
 * Visitor Tracking System
 * This script runs globally to track unique visitors per IP per day.
 * It ignores the /admin directory to keep statistics clean.
 */

// We use strpos to check if the path contains '/admin'
// Also avoid tracking in CLI mode
$request_uri = $_SERVER['REQUEST_URI'] ?? '';
if ($request_uri && strpos($request_uri, '/admin') === false) {
    // Make sure $pdo is available (it should be since this is included after database connection)
    global $pdo;
    
    if (isset($pdo)) {
        try {
            // Get visitor IP
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            // If using a reverse proxy (like Cloudflare), this might be needed:
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip_address = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            
            $visit_date = date('Y-m-d');
            
            // Insert new record or increment hits if already visited today
            $stmt = $pdo->prepare("
                INSERT INTO visitors (ip_address, visit_date, hits, created_at, updated_at) 
                VALUES (:ip, :vdate, 1, NOW(), NOW()) 
                ON DUPLICATE KEY UPDATE hits = hits + 1, updated_at = NOW()
            ");
            
            $stmt->execute([
                'ip' => $ip_address,
                'vdate' => $visit_date
            ]);
            
        } catch (PDOException $e) {
            // Log error silently so we don't break the frontend if the db tracking fails
            error_log("Visitor Tracker Error: " . $e->getMessage());
        }
    }
}
