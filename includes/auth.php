<?php
// includes/auth.php

session_start();
require_once __DIR__ . '/../config/database.php';

/**
 * Checks if the user is logged in.
 * Redirects to login page if not.
 */
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Checks if the logged in user has a specific role or permission.
 * 
 * @param string|array $required_role The role name(s) required.
 */
function authorize($required_role = null) {
    check_login();
    
    // If no specific role required, just being logged in is enough
    if ($required_role === null) return true;

    $user_role = $_SESSION['role_name'];

    if (is_array($required_role)) {
        if (!in_array($user_role, $required_role)) {
            die('Access Denied: Insufficient Permissions.');
        }
    } else {
        if ($user_role !== $required_role && $user_role !== 'Super Admin') {
            die('Access Denied: Insufficient Permissions.');
        }
    }

    return true;
}

/**
 * Log out the current user.
 */
function logout() {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

/**
 * Authenticate a user.
 * 
 * @param string $username
 * @param string $password
 * @return bool|array User data on success, false on failure.
 */
function authenticate($username, $password, $pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT u.*, r.role_name, r.permissions 
            FROM users u 
            JOIN roles r ON u.role_id = r.id 
            WHERE u.username = :username OR u.email = :username
        ");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user) {
            $is_password_correct = false;
            
            // 1. Cek dengan bcrypt hash (standar keamanan modern)
            if (password_verify($password, $user['password'])) {
                $is_password_correct = true;
            } 
            // 2. Fallback: Jika masih menggunakan plain-text (saat masa transisi/dev)
            else if ($password === $user['password']) {
                $is_password_correct = true;
                
                // Auto-upgrade: Ubah plain-text menjadi hash secara otomatis di database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
                $update_stmt->execute([':password' => $hashed_password, ':id' => $user['id']]);
            }

            if ($is_password_correct) {
                // 3. Mencegah serangan Session Fixation
                session_regenerate_id(true);
                
                // Set session data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role_name'] = $user['role_name'];
                $_SESSION['permissions'] = json_decode($user['permissions'], true);
                
                return $user;
            }
        }
        
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }

    return false;
}
?>
