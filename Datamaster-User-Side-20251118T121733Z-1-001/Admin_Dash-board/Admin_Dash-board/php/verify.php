<?php
include_once('connection.php');

if(isset($_GET['token'])) {
    $token = $_GET['token'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id FROM admin_table WHERE token = ? AND email_verified = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {

        // User found, proceed with verification
        $row = $result->fetch_assoc();
        $user_id = $row['id'];

        // Update the user's record to mark email as verified
        $update_stmt = $conn->prepare("UPDATE admin_table SET email_verified = 1 WHERE id = ?");
        $update_stmt->bind_param("i", $user_id);
        if($update_stmt->execute()) {

            echo "Your email has been successfully verified! You will be redirected to the login page in 5 seconds.";
            echo "<script>setTimeout(function(){ window.location.href = 'http://localhost/datamaster/Admin_Dash-board/pages/signin.html'; }, 5000);</script>";
        } 
        else {
            echo "An error occurred during the verification process. Please try again later.";
            echo "<script>setTimeout(function(){ window.location.href = 'http://localhost/datamaster/Admin_Dash-board/pages/signin.html'; }, 5000);</script>";
        }
    } else {
        // Check if the email was already verified
        $already_verified_stmt = $conn->prepare("SELECT id FROM admin_table WHERE token = ? AND email_verified = 1");
        $already_verified_stmt->bind_param("s", $token);
        $already_verified_stmt->execute();
        $already_verified_result = $already_verified_stmt->get_result();

        if($already_verified_result->num_rows == 1) {
            echo "Your email has already been verified. You will be redirected to the login page in 5 seconds.";
            echo "<script>setTimeout(function(){ window.location.href = 'http://localhost/datamaster/Admin_Dash-board/pages/signin.html'; }, 5000);</script>";
        } 
        else {
            echo "This verification link is invalid or expired. Please request a new verification link.";
            echo "<script>setTimeout(function(){ window.location.href = 'http://localhost/datamaster/Admin_Dash-board/pages/signin.html'; }, 5000);</script>";
        }
    }
} else {
    echo "Token not found.";
    echo "<script>setTimeout(function(){ window.location.href = 'http://localhost/datamaster/Admin_Dash-board/pages/signin.html'; }, 5000);</script>";
}
?>
