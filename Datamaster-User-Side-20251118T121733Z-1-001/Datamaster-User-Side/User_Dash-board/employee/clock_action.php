<?php
require_once('../session_config.php');
require_once('../csrf.php');
require_once('../connection.php');

// Check if user is logged in as employee
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'employee') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit();
}

// Validate CSRF token
if (!validate_csrf()) {
    $_SESSION['error'] = 'Security token validation failed. Please try again.';
    header('Location: dashboard.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$action = strtoupper($_POST['action'] ?? '');

if ($action !== 'IN' && $action !== 'OUT') {
    $_SESSION['error'] = 'Invalid action.';
    header('Location: dashboard.php');
    exit();
}

// Check current status to prevent double clock-in/out
$stmt = $conn->prepare("SELECT action FROM attendance_log WHERE user_id = ? ORDER BY timestamp DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$last_action = $result->fetch_assoc();
$stmt->close();

// Validation: Prevent duplicate actions
if ($last_action) {
    if ($action === 'IN' && $last_action['action'] === 'IN') {
        $_SESSION['error'] = 'You are already clocked in. Please clock out first.';
        header('Location: dashboard.php');
        exit();
    }
    if ($action === 'OUT' && $last_action['action'] === 'OUT') {
        $_SESSION['error'] = 'You are already clocked out. Please clock in first.';
        header('Location: dashboard.php');
        exit();
    }
}

// Determine status based on time (simple logic - can be enhanced)
$current_hour = (int)date('H');
$status = 'On Time';

if ($action === 'IN') {
    // Clock-in after 9:00 AM is considered late
    if ($current_hour >= 9) {
        $status = 'Late';
    }
} elseif ($action === 'OUT') {
    // Clock-out before 5:00 PM is early departure
    if ($current_hour < 17) {
        $status = 'Early Departure';
    }
}

// Get user's IP address for audit trail
$ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

// Insert clock action
$stmt = $conn->prepare("INSERT INTO attendance_log (user_id, employee_id, action, status, ip_address) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $user_id, $employee_id, $action, $status, $ip_address);

if ($stmt->execute()) {
    $action_text = $action === 'IN' ? 'clocked in' : 'clocked out';
    $_SESSION['success'] = "Successfully $action_text at " . date('g:i A') . "!";
} else {
    $_SESSION['error'] = 'Failed to record action. Please try again.';
}

$stmt->close();
header('Location: dashboard.php');
exit();
?>
