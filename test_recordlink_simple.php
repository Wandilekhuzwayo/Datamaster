<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing recordLink.php Access</h2>";

// Test direct access
echo "<h3>Test 1: Direct File Access</h3>";
$file = __DIR__ . '/Datamaster-User-Side-20251118T121733Z-1-001/Datamaster-User-Side/User_Dash-board/recordLink.php';
if (file_exists($file)) {
    echo "✅ File exists<br>";
    echo "File size: " . filesize($file) . " bytes<br>";
} else {
    echo "❌ File not found<br>";
}

// Test if we can read it
echo "<h3>Test 2: Attempting to Include (without POST)</h3>";
echo "<p>This will show any PHP errors that occur when the file is loaded...</p>";

// Capture output
ob_start();
try {
    include($file);
    $output = ob_get_clean();
    if (empty($output)) {
        echo "⚠️ File loaded but produced NO output (blank page)<br>";
        echo "This means the file runs but doesn't output anything when accessed directly.<br>";
    } else {
        echo "✅ File produced output:<br>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    }
} catch (Throwable $e) {
    ob_end_clean();
    echo "❌ Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}

echo "<hr>";
echo "<h3>Conclusion</h3>";
echo "<p>recordLink.php is designed to only output content when POST data is submitted.</p>";
echo "<p>A blank page when accessed directly is NORMAL behavior.</p>";
echo "<p>The issue is likely:</p>";
echo "<ul>";
echo "<li>Form is not submitting POST data correctly</li>";
echo "<li>CSRF token validation is failing</li>";
echo "<li>Session data is missing</li>";
echo "</ul>";
?>
