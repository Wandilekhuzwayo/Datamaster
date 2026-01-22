<?php
/**
 * CSRF Protection Helper
 * Prevents Cross-Site Request Forgery attacks
 * 
 * Usage:
 * - Include this file in pages with forms
 * - Call csrf_field() inside forms to add hidden token
 * - Call validate_csrf() at start of form handlers
 */

/**
 * Initialize CSRF token in session if not exists
 */
function csrf_init(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

/**
 * Get the current CSRF token
 * @return string The CSRF token
 */
function csrf_token(): string {
    csrf_init();
    return $_SESSION['csrf_token'];
}

/**
 * Output a hidden form field with CSRF token
 * Call this inside your <form> tags
 */
function csrf_field(): void {
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

/**
 * Validate the CSRF token from form submission
 * Call this at the start of form processing scripts
 * 
 * @param bool $regenerate Whether to regenerate token after validation
 * @return bool True if valid, exits with error if invalid
 */
function validate_csrf(bool $regenerate = true): bool {
    csrf_init();
    
    $submitted_token = $_POST['csrf_token'] ?? '';
    $session_token = $_SESSION['csrf_token'] ?? '';
    
    if (empty($submitted_token) || empty($session_token)) {
        http_response_code(403);
        exit('Security Error: Missing CSRF token.');
    }
    
    if (!hash_equals($session_token, $submitted_token)) {
        http_response_code(403);
        exit('Security Error: Invalid CSRF token.');
    }
    
    // Regenerate token after successful validation
    if ($regenerate) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return true;
}
?>
