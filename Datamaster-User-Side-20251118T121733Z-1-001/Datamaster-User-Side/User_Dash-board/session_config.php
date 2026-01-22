<?php
/**
 * Secure Session Configuration
 * Include this file at the very top of pages that use sessions
 * BEFORE any output or session_start() calls
 * 
 * Security features:
 * - HttpOnly cookies (prevents JavaScript access)
 * - SameSite=Strict (prevents CSRF via cookies)
 * - Secure flag ready for HTTPS
 * - Session timeout enforcement
 */

// Only configure if session hasn't started
if (session_status() === PHP_SESSION_NONE) {
    
    // Session timeout in seconds (30 minutes)
    $session_timeout = 1800;
    
    // Configure session cookie parameters
    session_set_cookie_params([
        'lifetime' => $session_timeout,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),  // True if HTTPS
        'httponly' => true,                     // Prevent JavaScript access
        'samesite' => 'Strict'                  // Prevent CSRF
    ]);
    
    // Start session with secure settings
    session_start();
    
    // Check for session timeout
    if (isset($_SESSION['last_activity'])) {
        $inactive_time = time() - $_SESSION['last_activity'];
        if ($inactive_time > $session_timeout) {
            // Session expired - destroy and restart
            session_unset();
            session_destroy();
            session_start();
        }
    }
    
    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
    
    // Regenerate session ID periodically (every 5 minutes)
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 300) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}
?>
