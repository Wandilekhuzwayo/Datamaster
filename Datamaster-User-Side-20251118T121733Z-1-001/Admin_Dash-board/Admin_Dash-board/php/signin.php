<?php
session_start();
require_once "connection.php";

if (isset($_POST['login-button'])) {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // =========================
    // BASIC VALIDATION
    // =========================
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['login_error'] = "Invalid email format.";
        header("Location: ../pages/signin.html");
        exit();
    }

    // =========================
    // FETCH USER
    // =========================
    $stmt = $conn->prepare("
        SELECT id, firstname, password, role, status 
        FROM admin_table 
        WHERE email = ?
        LIMIT 1
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $_SESSION['login_error'] = "Incorrect username or password.";
        header("Location: ../pages/signin.html");
        exit();
    }

    $stmt->bind_result($id, $firstname, $hashedPassword, $role, $status);
    $stmt->fetch();

    // =========================
    // CHECK STATUS
    // =========================
    if ($status !== 'active') {
        $_SESSION['login_error'] = "Your account has been disabled. Contact Super Admin.";
        header("Location: ../pages/signin.html");
        exit();
    }

    // =========================
    // VERIFY PASSWORD
    // =========================
    if (!password_verify($password, $hashedPassword)) {
        $_SESSION['login_error'] = "Incorrect username or password.";
        header("Location: ../pages/signin.html");
        exit();
    }

    // =========================
    // UPDATE LAST LOGIN
    // =========================
    $update = $conn->prepare("
        UPDATE admin_table 
        SET last_login = NOW() 
        WHERE id = ?
    ");
    $update->bind_param("i", $id);
    $update->execute();

    // =========================
    // LOGIN SUCCESS
    // =========================
    $_SESSION['admin_email'] = $email;
    $_SESSION['id']          = $id;
    $_SESSION['firstname']  = $firstname;
    $_SESSION['role']       = $role;

    // =========================
    // ROLE-BASED REDIRECT
    // =========================
    if ($role === 'super_admin') {
        header("Location: ../superadmin/dashboard.php");
    } else {
        header("Location: ../pages/index.php");
    }
    exit();
}
?>
