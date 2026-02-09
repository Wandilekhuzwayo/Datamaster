<?php
session_start();
require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/signup.php");
    exit();
}

// =========================
// COLLECT & SANITIZE INPUT
// =========================
$companyname = trim($_POST['companyname']);
$firstname   = trim($_POST['firstname']);
$lastname    = trim($_POST['lastname']);
$email       = trim($_POST['email']);
$department  = trim($_POST['department']);
$password    = $_POST['password'];
$invite_code = strtoupper(trim($_POST['invite_code']));

// =========================
// BASIC VALIDATION
// =========================
if (
    empty($companyname) || empty($firstname) || empty($lastname) ||
    empty($email) || empty($department) || empty($password) || empty($invite_code)
) {
    header("Location: ../pages/signup.php?error=All fields are required");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../pages/signup.php?error=Invalid email address");
    exit();
}

if (strlen($password) < 6) {
    header("Location: ../pages/signup.php?error=Password must be at least 6 characters");
    exit();
}

// =========================
// CHECK IF EMAIL EXISTS
// =========================
$stmt = $conn->prepare("SELECT id FROM admin_table WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    header("Location: ../pages/signup.php?error=Email already registered");
    exit();
}
$stmt->close();

// =========================
// VALIDATE INVITE CODE (24 HOURS)
// =========================
$stmt = $conn->prepare("
    SELECT id 
    FROM admin_invite_codes 
    WHERE code = ?
      AND used = 0
      AND created_at >= (NOW() - INTERVAL 24 HOUR)
");
$stmt->bind_param("s", $invite_code);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    header("Location: ../pages/signup.php?error=Invalid, expired, or already used invitation code");
    exit();
}

$stmt->bind_result($invite_id);
$stmt->fetch();
$stmt->close();

// =========================
// HASH PASSWORD
// =========================
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// =========================
// INSERT ADMIN (FIXED ✅)
// =========================
$role = "admin";

$stmt = $conn->prepare("
    INSERT INTO admin_table 
    (companyname, firstname, surname, email, department, password, role, invite_code)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssssss",
    $companyname,
    $firstname,
    $lastname,
    $email,
    $department,
    $hashedPassword,
    $role,
    $invite_code
);

if (!$stmt->execute()) {
    header("Location: ../pages/signup.php?error=Registration failed");
    exit();
}

$stmt->close();

// =========================
// MARK INVITE CODE AS USED
// =========================
$update = $conn->prepare("
    UPDATE admin_invite_codes 
    SET used = 1 
    WHERE id = ?
");
$update->bind_param("i", $invite_id);
$update->execute();
$update->close();

// =========================
// REDIRECT TO LOGIN
// =========================
header("Location: ../pages/signin.html?success=Account created successfully");
exit();
