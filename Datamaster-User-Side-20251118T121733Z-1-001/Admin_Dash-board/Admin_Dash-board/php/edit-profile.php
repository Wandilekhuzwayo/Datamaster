<?php
// Start the session if it is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'C:\xampp\htdocs\DataMaster\Admin_Dash-board\php\connection.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['firstname'])) {
    header("Location: signin.php"); // Redirect to login page if not logged in
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

// Update user's details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $surname = $_POST['surname'];
    $employeeNo = $_POST['employeeNo'];
    $companyname = $_POST['companyname'];
    $department = $_POST['department'];

    // Update the user's details in the database
    $update_query = "UPDATE admin_table SET firstname = ?, surname = ?, employeeNo = ?, companyname = ?, department = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssisss", $firstname, $surname, $employeeNo, $companyname, $department, $userEmail);
    if ($update_stmt->execute()) {
        // Redirect to profile page after successful update
        header("Location: myprofile.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $update_stmt->close();
    $conn->close();
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .form-label {
            font-weight: bold;
        }
        .form-control {
            border-radius: 20px;
            border-color: #ccc;
        }
        .mb-3 {
            margin-bottom: 20px;
        }
        .btn-primary {
            border-radius: 20px;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
    </style>
</head>
<body>
    <div class="container">
    <!-- <img src="../images/Logo.jpeg "class="img-fluid " alt="..."> -->
        <h1 class="text-center mb-4">Edit Profile</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Surname</label>
                <input type="text" class="form-control" id="surname" name="surname" value="<?php echo htmlspecialchars($user['surname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="employeeNo" class="form-label">Employee No</label>
                <input type="text" class="form-control" id="employeeNo" name="employeeNo" value="<?php echo htmlspecialchars($user['employeeNo']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="companyname" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="companyname" name="companyname" value="<?php echo htmlspecialchars($user['companyname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <input type="text" class="form-control" id="department" name="department" value="<?php echo htmlspecialchars($user['department']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
