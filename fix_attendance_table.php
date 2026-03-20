<?php
// Fix: Create attendance_log table if it doesn't exist
$conn = new mysqli('localhost', 'root', '@tpdT3pd', 'datamaster');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Checking and creating attendance_log table...\n\n";

// Create attendance_log table
$sql = "
CREATE TABLE IF NOT EXISTS attendance_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL COMMENT 'Foreign key to user_table.id',
  employee_id VARCHAR(50) NOT NULL COMMENT 'Employee ID for reporting',
  timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Clock time',
  action ENUM('IN', 'OUT') NOT NULL COMMENT 'Clock IN or OUT',
  status VARCHAR(50) DEFAULT 'On Time' COMMENT 'On Time, Late, etc.',
  ip_address VARCHAR(45) NULL COMMENT 'IP address for audit',
  notes TEXT NULL COMMENT 'Optional notes',
  
  INDEX idx_user_id (user_id),
  INDEX idx_employee_id (employee_id),
  INDEX idx_timestamp (timestamp),
  INDEX idx_action (action)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
";

if ($conn->query($sql) === TRUE) {
    echo "✓ Table 'attendance_log' created/verified successfully\n";
} else {
    echo "✗ Error creating table: " . $conn->error . "\n";
    exit(1);
}

// Verify table exists
$result = $conn->query("SHOW TABLES LIKE 'attendance_log'");
if ($result->num_rows > 0) {
    echo "✓ Table exists in database\n\n";
    
    // Show table structure
    echo "Table structure:\n";
    $result = $conn->query("DESCRIBE attendance_log");
    while ($row = $result->fetch_assoc()) {
        echo "  - {$row['Field']} ({$row['Type']})\n";
    }
} else {
    echo "✗ Table does not exist!\n";
    exit(1);
}

$conn->close();
echo "\n✓ Fix complete! You can now log in to the employee portal.\n";
?>
