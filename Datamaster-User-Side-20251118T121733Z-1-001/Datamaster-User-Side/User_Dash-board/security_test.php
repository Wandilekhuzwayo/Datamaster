<?php
/**
 * Security Test Script
 * Tests the security implementations in User Dashboard
 * Run this in browser: http://localhost/Datamaster/.../User_Dash-board/security_test.php
 */

echo "<h1>DataMaster User Dashboard - Security Tests</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .pass { color: green; font-weight: bold; }
    .fail { color: red; font-weight: bold; }
    .test { margin: 10px 0; padding: 10px; background: #f5f5f5; border-radius: 5px; }
    h2 { color: #333; border-bottom: 2px solid #333; padding-bottom: 5px; }
</style>";

$passed = 0;
$failed = 0;

function test($name, $condition, $details = '') {
    global $passed, $failed;
    $status = $condition ? 'PASS' : 'FAIL';
    $class = $condition ? 'pass' : 'fail';
    if ($condition) $passed++; else $failed++;
    echo "<div class='test'><span class='$class'>[$status]</span> $name";
    if ($details) echo " <small>($details)</small>";
    echo "</div>";
}

// ============================================
echo "<h2>1. Helper Files Exist</h2>";
// ============================================

test("csrf.php exists", file_exists(__DIR__ . '/csrf.php'));
test("validation.php exists", file_exists(__DIR__ . '/validation.php'));
test("session_config.php exists", file_exists(__DIR__ . '/session_config.php'));

// ============================================
echo "<h2>2. CSRF Protection</h2>";
// ============================================

require_once('csrf.php');
$token = csrf_token();
test("CSRF token is generated", !empty($token), strlen($token) . " chars");
test("CSRF token is 64 characters (32 bytes hex)", strlen($token) === 64);
test("CSRF token stored in session", isset($_SESSION['csrf_token']));

// ============================================
echo "<h2>3. Input Validation Functions</h2>";
// ============================================

require_once('validation.php');

// Test sanitize_string
$dirty = "<script>alert('xss')</script>";
$clean = sanitize_string($dirty);
test("XSS sanitization works", strpos($clean, '<script>') === false, "Converted to HTML entities");

// Test email validation
test("Valid email passes", validate_email("test@example.com"));
test("Invalid email fails", !validate_email("not-an-email"));

// Test phone validation
test("Valid phone passes", validate_phone("0123456789"));
test("Phone with + passes", validate_phone("+27123456789"));
test("Short phone fails", !validate_phone("12345"));

// Test name validation
test("Valid name passes", validate_name("John Doe"));
test("Name with numbers fails", !validate_name("John123"));

// ============================================
echo "<h2>4. Session Security</h2>";
// ============================================

require_once('session_config.php');
$params = session_get_cookie_params();
test("Session HttpOnly enabled", $params['httponly'] === true);
test("Session SameSite is Strict", $params['samesite'] === 'Strict');
test("Session lifetime set", $params['lifetime'] > 0);
test("Last activity tracked", isset($_SESSION['last_activity']));

// ============================================
echo "<h2>5. File Upload Validation</h2>";
// ============================================

// Test with mock file data
$fake_php_file = [
    'name' => 'malicious.php',
    'tmp_name' => __FILE__,
    'error' => UPLOAD_ERR_OK,
    'size' => 1000
];
$result = validate_image_upload($fake_php_file);
test("PHP file upload rejected", $result['valid'] === false, $result['error'] ?? '');

// ============================================
echo "<h2>6. SQL Injection Prevention (Code Check)</h2>";
// ============================================

// Check if files use prepared statements
$files_to_check = [
    'recordLink.php',
    'checklistLink.php',
    'retrieveLink.php',
    'redeemLink.php',
    'vacateLink.php',
    'Progress.php',
    'Checklist.php'
];

foreach ($files_to_check as $file) {
    $content = file_get_contents(__DIR__ . '/' . $file);
    $has_prepare = strpos($content, '->prepare(') !== false || strpos($content, 'bind_param') !== false;
    $has_raw_concat = preg_match('/mysqli_query.*\$[a-zA-Z_]+.*%/', $content);
    test("$file uses prepared statements", $has_prepare);
}

// ============================================
echo "<h2>7. CSRF Token in Forms (Code Check)</h2>";
// ============================================

$form_files = [
    'Register.php',
    'Retrieve.php',
    'Record.php',
    'Redeem.php',
    'Checklist.php'
];

foreach ($form_files as $file) {
    $content = file_get_contents(__DIR__ . '/' . $file);
    $has_csrf = strpos($content, 'csrf_field()') !== false;
    test("$file includes CSRF token", $has_csrf);
}

// ============================================
echo "<h2>Summary</h2>";
// ============================================

$total = $passed + $failed;
$percentage = round(($passed / $total) * 100);
echo "<div style='font-size: 24px; margin: 20px 0;'>";
echo "<span class='pass'>$passed passed</span> / ";
echo "<span class='fail'>$failed failed</span> ";
echo "($percentage%)";
echo "</div>";

if ($failed === 0) {
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; color: #155724;'>";
    echo "✅ <strong>All security tests passed!</strong> The application meets security specifications.";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>";
    echo "⚠️ <strong>Some tests failed.</strong> Please review the failed items above.";
    echo "</div>";
}
?>
