<?php
session_start();
require_once "connection.php";

// =========================
// UPDATE LAST LOGOUT TIME
// =========================
if (isset($_SESSION['id'])) {
    $stmt = $conn->prepare("
        UPDATE admin_table 
        SET last_logout = NOW() 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
}

// =========================
// CLEAR SESSION
// =========================
$_SESSION = [];
session_destroy();

// =========================
// REDIRECT TO LOGIN
// =========================
header("Location: http://localhost:8000/pages/signin.html");
exit();
?>
