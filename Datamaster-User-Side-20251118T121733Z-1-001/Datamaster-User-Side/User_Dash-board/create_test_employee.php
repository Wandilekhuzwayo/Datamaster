<?php
// Simple script to register a test employee
// In production, this would be an admin-only page

require_once('connection.php');

// Test employee data
$email = 'john.doe@company.com';
$fname = 'John';
$lname = 'Doe';
$employee_id = 'EMP001';
$department = 'IT Department';
$password = 'Test123!'; // In production, user would set this
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Check if employee already exists
$stmt = $conn->prepare("SELECT id FROM user_table WHERE email = ? OR employee_id = ?");
$stmt->bind_param("ss", $email, $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "✗ Employee already exists with this email or employee ID!\n";
    exit(1);
}
$stmt->close();

// Insert test employee with all required fields
$stmt = $conn->prepare("
    INSERT INTO user_table 
    (user_type, email, fname, lname, mnum, employee_id, department, password_hash, date,
     cname, address, country, province, city, code, name, surname, contact, subscription, image) 
    VALUES ('employee', ?, ?, ?, '', ?, ?, ?, NOW(), 
            '', '', '', '', '', 0, '', '', '', '', '')
");
$stmt->bind_param("ssssss", $email, $fname, $lname, $employee_id, $department, $password_hash);

if ($stmt->execute()) {
    echo "✓ Test employee created successfully!\n\n";
    echo "=== Login Credentials ===\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
    echo "Employee ID: $employee_id\n";
    echo "Department: $department\n\n";
    echo "Access the employee portal at:\n";
    echo "http://localhost/Datamaster/Datamaster-User-Side-20251118T121733Z-1-001/Datamaster-User-Side/User_Dash-board/employee/login.php\n";
} else {
    echo "✗ Failed to create employee: " . $stmt->error . "\n";
}

$stmt->close();
$conn->close();
?>
