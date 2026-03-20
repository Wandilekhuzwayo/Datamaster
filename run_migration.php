<?php
// Direct database migration via PHP
$host = 'localhost';
$user = 'root';
$pass = '@tpdT3pd';
$dbname = 'datamaster';

echo "=== DataMaster Database Migration ===\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n\n";

// Connect to database
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("✗ Connection failed: " . $conn->connect_error . "\n");
}
echo "✓ Connected to database: $dbname\n\n";

// Read and execute SQL migration
$sql_file = __DIR__ . '/database_migration_employee_system.sql';
$sql_content = file_get_contents($sql_file);

// Remove comments and split by semicolon
$sql_content = preg_replace('/--.*$/m', '', $sql_content); // Remove single-line comments
$sql_content = preg_replace('/\/\*.*?\*\//s', '', $sql_content); // Remove multi-line comments
$statements = array_filter(array_map('trim', explode(';', $sql_content)));

$success_count = 0;
$error_count = 0;

echo "Executing migration statements...\n";
foreach ($statements as $stmt) {
    if (empty($stmt)) continue;
    
    // Show first 80 chars of statement
    $preview = substr(preg_replace('/\s+/', ' ', $stmt), 0, 80) . '...';
    echo "  → $preview\n";
    
    if ($conn->query($stmt) === TRUE) {
        $success_count++;
    } else {
        // Some errors are expected (like DROP IF NOT EXISTS for new tables)
        if (strpos($stmt, 'DROP') === false && strpos($stmt, 'CREATE OR REPLACE') === false) {
            echo "    ✗ Error: " . $conn->error . "\n";
            $error_count++;
        } else {
            $success_count++;
        }
    }
}

echo "\n=== Migration Summary ===\n";
echo "✓ Successful statements: $success_count\n";
echo "✗ Errors: $error_count\n";

// Verify migration
echo "\n=== Verification ===\n";

// Check new columns in user_table
$result = $conn->query("SHOW COLUMNS FROM user_table LIKE 'user_type'");
echo ($result->num_rows > 0 ? "✓" : "✗") . " Column 'user_type' exists\n";

$result = $conn->query("SHOW COLUMNS FROM user_table LIKE 'employee_id'");
echo ($result->num_rows > 0 ? "✓" : "✗") . " Column 'employee_id' exists\n";

// Check attendance_log table
$result = $conn->query("SHOW TABLES LIKE 'attendance_log'");
echo ($result->num_rows > 0 ? "✓" : "✗") . " Table 'attendance_log' exists\n";

// Count user_types
$result = $conn->query("SELECT user_type, COUNT(*) as count FROM user_table GROUP BY user_type");
echo "\nUser Type Distribution:\n";
while ($row = $result->fetch_assoc()) {
    echo "  - " . ($row['user_type'] ?: 'NULL') . ": " . $row['count'] . "\n";
}

$conn->close();
echo "\n✓ Migration complete!\n";
?>
