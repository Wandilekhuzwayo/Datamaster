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
    die("User not found.");
}

$stmt->close();

/* =========================
   UPDATE PROFILE
========================= */
$success = "";
$error = "";

if (isset($_POST['update_profile'])) {

    $firstname   = trim($_POST['firstname']);
    $surname     = trim($_POST['surname']);
    $company     = trim($_POST['companyname']);
    $department  = trim($_POST['department']);

    if (empty($firstname) || empty($surname)) {
        $error = "First name and surname are required.";
    } else {

        $update = $conn->prepare("
            UPDATE admin_table 
            SET firstname = ?, surname = ?, companyname = ?, department = ?
            WHERE email = ?
        ");

        $update->bind_param("sssss", $firstname, $surname, $company, $department, $userEmail);

        if ($update->execute()) {
            $success = "Profile updated successfully.";

            // Update session name
            $_SESSION['firstname'] = $firstname;

            // Refresh data
            $user['firstname'] = $firstname;
            $user['surname'] = $surname;
            $user['companyname'] = $company;
            $user['department'] = $department;
        } else {
            $error = "Error updating profile.";
        }

        $update->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

<style>
body {background:#f8f9fa;}
.card {border-radius:15px;}
</style>
</head>

<body>

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-6">

<div class="card shadow-lg">
<div class="card-body">

<h3 class="text-center mb-4">
<i class="bi bi-pencil-square"></i> Edit Profile
</h3>

<?php if ($success): ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">

<div class="mb-3">
<label class="form-label">First Name</label>
<input type="text" name="firstname" class="form-control"
value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Surname</label>
<input type="text" name="surname" class="form-control"
value="<?php echo htmlspecialchars($user['surname']); ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Email (cannot change)</label>
<input type="email" class="form-control"
value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
</div>

<div class="mb-3">
<label class="form-label">Company</label>
<input type="text" name="companyname" class="form-control"
value="<?php echo htmlspecialchars($user['companyname']); ?>">
</div>

<div class="mb-3">
<label class="form-label">Department</label>
<input type="text" name="department" class="form-control"
value="<?php echo htmlspecialchars($user['department']); ?>">
</div>

<button type="submit" name="update_profile" class="btn btn-primary w-100">
<i class="bi bi-save"></i> Save Changes
</button>

<a href="myprofile.php" class="btn btn-secondary w-100 mt-2">
Back to Profile
</a>

</form>

</div>
</div>

</div>
</div>
</div>

</body>
</html>