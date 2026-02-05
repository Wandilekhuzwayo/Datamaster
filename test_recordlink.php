<?php
/**
 * Diagnostic Test for recordLink.php
 * Tests all dependencies and simulates registration flow
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>recordLink.php Diagnostic Test</h2>";
echo "<hr>";

// Test 1: Check required files exist
echo "<h3>1. Checking Required Files</h3>";
$required_files = [
    'session_config.php',
    'validation.php',
    'csrf.php',
    'connection.php'
];

foreach ($required_files as $file) {
    $path = __DIR__ . '/Datamaster-User-Side-20251118T121733Z-1-001/Datamaster-User-Side/User_Dash-board/' . $file;
    if (file_exists($path)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file MISSING at: $path<br>";
    }
}

echo "<hr>";

// Test 2: Try to include files
echo "<h3>2. Testing File Includes</h3>";
try {
    require_once(__DIR__ . '/Datamaster-User-Side-20251118T121733Z-1-001/Datamaster-User-Side/User_Dash-board/session_config.php');
    echo "✅ session_config.php loaded<br>";
} catch (Exception $e) {
    echo "❌ session_config.php failed: " . $e->getMessage() . "<br>";
}

try {
    require_once(__DIR__ . '/Datamaster-User-Side-20251118T121733Z-1-001/Datamaster-User-Side/User_Dash-board/validation.php');
    echo "✅ validation.php loaded<br>";
} catch (Exception $e) {
    echo "❌ validation.php failed: " . $e->getMessage() . "<br>";
}

try {
    require_once(__DIR__ . '/Datamaster-User-Side-20251118T121733Z-1-001/Datamaster-User-Side/User_Dash-board/csrf.php');
    echo "✅ csrf.php loaded<br>";
} catch (Exception $e) {
    echo "❌ csrf.php failed: " . $e->getMessage() . "<br>";
}

try {
    include(__DIR__ . '/Datamaster-User-Side-20251118T121733Z-1-001/Datamaster-User-Side/User_Dash-board/connection.php');
    echo "✅ connection.php loaded<br>";
    if (isset($conn) && $conn) {
        echo "✅ Database connection established<br>";
    } else {
        echo "❌ Database connection failed<br>";
    }
} catch (Exception $e) {
    echo "❌ connection.php failed: " . $e->getMessage() . "<br>";
}

echo "<hr>";

// Test 3: Check session data
echo "<h3>3. Checking Session Data</h3>";
if (isset($_SESSION)) {
    echo "Session variables:<br>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
} else {
    echo "⚠️ No session data found<br>";
}

echo "<hr>";

// Test 4: Check img_Users directory
echo "<h3>4. Checking Upload Directory</h3>";
$img_dir = __DIR__ . '/Datamaster-User-Side-20251118T121733Z-1-001/Datamaster-User-Side/User_Dash-board/img_Users';
if (is_dir($img_dir)) {
    echo "✅ img_Users directory exists<br>";
    if (is_writable($img_dir)) {
        echo "✅ img_Users is writable<br>";
    } else {
        echo "❌ img_Users is NOT writable<br>";
    }
} else {
    echo "❌ img_Users directory does not exist<br>";
}

echo "<hr>";

// Test 5: Check PHP extensions
echo "<h3>5. Checking PHP Extensions</h3>";
if (function_exists('finfo_open')) {
    echo "✅ fileinfo extension enabled<br>";
} else {
    echo "❌ fileinfo extension NOT enabled<br>";
}

if (function_exists('mysqli_connect')) {
    echo "✅ mysqli extension enabled<br>";
} else {
    echo "❌ mysqli extension NOT enabled<br>";
}

echo "<hr>";

// Test 6: Simulate CSRF token check
echo "<h3>6. Testing CSRF Functions</h3>";
if (function_exists('csrf_token')) {
    $token = csrf_token();
    echo "✅ CSRF token generated: " . substr($token, 0, 20) . "...<br>";
} else {
    echo "❌ csrf_token() function not available<br>";
}

echo "<hr>";

// Test 7: Test validation functions
echo "<h3>7. Testing Validation Functions</h3>";
if (function_exists('sanitize_string')) {
    $test = sanitize_string("<script>alert('test')</script>");
    echo "✅ sanitize_string() works: " . htmlspecialchars($test) . "<br>";
} else {
    echo "❌ sanitize_string() function not available<br>";
}

if (function_exists('validate_image_upload')) {
    echo "✅ validate_image_upload() function available<br>";
} else {
    echo "❌ validate_image_upload() function not available<br>";
}

echo "<hr>";
echo "<h3>Diagnostic Complete</h3>";
echo "<p>If all checks pass, the issue might be with the form submission or POST data.</p>";
?>
