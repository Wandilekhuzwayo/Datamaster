<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'C:\xampp\htdocs\DataMaster\Admin_Dash-board\php\connection.php';

if (!isset($_SESSION['firstname'])) {
    header("Location: ./pages/signin.html");
    exit();
}

// Get the logged-in user's email
$userEmail = $_SESSION['firstname'];

// Fetch the user's details from the database
$sql = "SELECT firstname, surname, email, employeeNo, companyname, department FROM admin_table WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No user found.";
    exit();
}


$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/css.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-card {
            border-radius: 15px;
        }
        .profile-image {
            max-width: 150px;
            border-radius: 50%;
            margin: 20px auto;
            display: block;
        }
        .profile-info {
            text-align: center;
        }
        .profile-info p {
            font-size: 1.1em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card profile-card shadow-lg">
                        <div class="card-body text-center">
                            <img src="../images/Logo.jpeg" class="img-fluid" alt="Logo">
                            <h2 class="card-title mb-3"><?php echo htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['surname']); ?></h2>
                            <div class="profile-info">
                                <p><i class="bi bi-envelope-fill"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                                <p><i class="bi bi-person-badge-fill"></i> Employee No: <?php echo htmlspecialchars($user['employeeNo']); ?></p>
                                <p><i class="bi bi-building"></i> Company: <?php echo htmlspecialchars($user['companyname']); ?></p>
                                <p><i class="bi bi-diagram-3-fill"></i> Department: <?php echo htmlspecialchars($user['department']); ?></p>
                            </div>
                            <a href="edit-profile.php" class="btn btn-primary mt-3">Edit Profile</a>
                            <a href="signout.php" class="btn btn-danger mt-3">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
