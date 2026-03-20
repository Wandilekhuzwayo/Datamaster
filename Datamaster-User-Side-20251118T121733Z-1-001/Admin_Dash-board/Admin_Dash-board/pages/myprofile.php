<?php
session_start();
require '../php/connection.php';

/* =========================
   CHECK LOGIN
========================= */
if (!isset($_SESSION['admin_email'])) {
    header("Location: ../pages/signin.html");
    exit();
}

/* =========================
   GET LOGGED-IN USER
========================= */
$userEmail = $_SESSION['admin_email'];

/* =========================
   FETCH USER DETAILS
========================= */
$sql = "SELECT firstname, surname, email, companyname, department 
        FROM admin_table 
        WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

<style>
body {background:#f8f9fa;}
.profile-card {border-radius:15px;}
.profile-image {max-width:120px;border-radius:50%;margin:auto;}
</style>
</head>

<body>
<section class="vh-100">
<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-6">

<div class="card shadow-lg profile-card">
<div class="card-body text-center">

<img src="../images/Logo.jpeg" class="profile-image">

<h3 class="mt-3">
<?php echo htmlspecialchars($user['firstname'].' '.$user['surname']); ?>
</h3>

<p><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($user['email']); ?></p>
<p><i class="bi bi-building"></i> <?php echo htmlspecialchars($user['companyname']); ?></p>
<p><i class="bi bi-diagram-3"></i> <?php echo htmlspecialchars($user['department']); ?></p>

<a href="edit-profile.php" class="btn btn-primary w-100 mt-3">
<i class="bi bi-pencil-square"></i> Edit Profile
</a>

<a href="../php/signout.php" class="btn btn-danger w-100 mt-2">
Logout
</a>

</div>
</div>

</div>
</div>
</div>
</section>

</body>
</html>