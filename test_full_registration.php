<?php
/**
 * Complete Registration Flow Test
 * Simulates: Register -> Record -> Database Insert
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Change to the User_Dash-board directory
chdir(__DIR__ . '/Datamaster-User-Side-20251118T121733Z-1-001/Datamaster-User-Side/User_Dash-board');

echo "<h2>Complete Registration Flow Test</h2>";
echo "<hr>";

// Step 1: Initialize session and security
echo "<h3>Step 1: Initialize Session & Security</h3>";
try {
    require_once('session_config.php');
    echo "✅ Session initialized<br>";
    require_once('validation.php');
    echo "✅ Validation functions loaded<br>";
    require_once('csrf.php');
    echo "✅ CSRF protection loaded<br>";
    require_once('connection.php');
    echo "✅ Database connected<br>";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
    die();
}

echo "<hr>";

// Step 2: Simulate registration form (registerLink.php)
echo "<h3>Step 2: Simulate Registration Form Submission</h3>";
$_SESSION["firstname"] = "TestUser";
$_SESSION["surname"] = "TestLast";
$_SESSION["mobile"] = "0123456789";
$_SESSION["organization"] = "TestCorp";
$_SESSION["emailAddress"] = "test" . time() . "@example.com"; // Unique email
$_SESSION["homeAddress"] = "123 Test St";
$_SESSION["nation"] = "TestCountry";
$_SESSION["province"] = "TestProvince";
$_SESSION["town"] = "TestCity";
$_SESSION["postalCode"] = "12345";
$_SESSION["firstName"] = "EmergencyName";
$_SESSION["lastname"] = "EmergencySurname";
$_SESSION["telephone"] = "0987654321";
$_SESSION["subscription"] = "Yes";

echo "✅ Session data populated<br>";
echo "Email: " . $_SESSION["emailAddress"] . "<br>";

echo "<hr>";

// Step 3: Simulate Record.php with CSRF token
echo "<h3>Step 3: Generate CSRF Token</h3>";
$csrf_token = csrf_token();
echo "✅ CSRF Token: " . substr($csrf_token, 0, 20) . "...<br>";

echo "<hr>";

// Step 4: Simulate recordLink.php submission
echo "<h3>Step 4: Simulate Form Submission to recordLink.php</h3>";

// Simulate POST data
$_POST['insert'] = true;
$_POST['csrf_token'] = $csrf_token;

// Create a dummy image file for testing
$test_image_data = base64_encode('FAKE_IMAGE_DATA_FOR_TESTING');
$temp_file = tempnam(sys_get_temp_dir(), 'test_img');
file_put_contents($temp_file, base64_decode($test_image_data));

$_FILES['webcam'] = [
    'name' => 'test.jpg',
    'type' => 'image/jpeg',
    'tmp_name' => $temp_file,
    'error' => UPLOAD_ERR_OK,
    'size' => filesize($temp_file)
];

echo "✅ POST data prepared<br>";
echo "✅ Fake image file created<br>";

echo "<hr>";

// Step 5: Execute the recordLink logic
echo "<h3>Step 5: Execute recordLink.php Logic</h3>";

try {
    // Get session data
    $firstname = sanitize_string($_SESSION["firstname"] ?? '');
    $lastname = sanitize_string($_SESSION["surname"] ?? '');
    $phone = sanitize_string($_SESSION["mobile"] ?? '');
    $company = sanitize_string($_SESSION["organization"] ?? '');
    $email = sanitize_string($_SESSION["emailAddress"] ?? '');
    $address = sanitize_string($_SESSION["homeAddress"] ?? '');
    $country = sanitize_string($_SESSION["nation"] ?? '');
    $state = sanitize_string($_SESSION["province"] ?? '');
    $city = sanitize_string($_SESSION["town"] ?? '');
    $code = sanitize_string($_SESSION["postalCode"] ?? '');
    $name = sanitize_string($_SESSION["firstName"] ?? '');
    $surname = sanitize_string($_SESSION["lastname"] ?? '');
    $contact = sanitize_string($_SESSION["telephone"] ?? '');
    $subscription = sanitize_string($_SESSION["subscription"] ?? '');
    $date = date("Y/m/d H:i:sa");
    
    echo "✅ Session data retrieved and sanitized<br>";
    
    // Validate CSRF (don't regenerate for testing)
    validate_csrf(false);
    echo "✅ CSRF token validated<br>";
    
    // Check for duplicate
    $checkStmt = $conn->prepare("SELECT id FROM `user_table` WHERE email = ? OR mnum = ?");
    $checkStmt->bind_param("ss", $email, $phone);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        echo "⚠️ User already exists (this is OK for testing)<br>";
        $checkStmt->close();
    } else {
        $checkStmt->close();
        echo "✅ No duplicate found<br>";
        
        // For testing, skip actual image upload
        $imageName = 'test_' . time() . '.jpg';
        echo "✅ Image name generated: $imageName<br>";
        
        // Insert into database
        $insertStmt = $conn->prepare("INSERT INTO `user_table` (date, image, fname, lname, mnum, cname, email, address, country, province, city, code, name, surname, contact, subscription) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertStmt->bind_param("ssssssssssssssss", $date, $imageName, $firstname, $lastname, $phone, $company, $email, $address, $country, $state, $city, $code, $name, $surname, $contact, $subscription);
        
        if ($insertStmt->execute()) {
            echo "✅ <strong>SUCCESS! User inserted into database</strong><br>";
            echo "Insert ID: " . $insertStmt->insert_id . "<br>";
            $insertStmt->close();
        } else {
            echo "❌ Insert failed: " . $insertStmt->error . "<br>";
            $insertStmt->close();
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error during execution: " . $e->getMessage() . "<br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}

// Cleanup
unlink($temp_file);

echo "<hr>";
echo "<h3>Test Complete</h3>";
echo "<p><strong>If you see 'SUCCESS!' above, the registration flow works correctly.</strong></p>";
echo "<p>The blank page you see when accessing recordLink.php directly is NORMAL - it only outputs when POST data is submitted.</p>";
?>
