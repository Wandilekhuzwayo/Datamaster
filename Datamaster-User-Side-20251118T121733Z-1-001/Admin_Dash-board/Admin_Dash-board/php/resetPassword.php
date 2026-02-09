<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>

    <script src="jquery-3.6.1.min.js"></script>
 

  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
</body>
</html>

<?php
session_start();
include_once('connection.php');

// Check if all required fields are set
if (!isset($_POST['change-password'], $_POST['password'], $_POST['password2'])) {
    exit('All fields are required');
} else {
    // Validate password length
    if (strlen($_POST['password']) < 8) {
        exit('Password must be at least 8 characters long');
    }

    // Check if passwords match
    if ($_POST['password'] !== $_POST['password2']) {
        exit('Passwords do not match');
    }

    // Check if session token is set
    if(isset($_SESSION['token'])){
        $password = md5($_POST['password']);
        $email= $_SESSION['email'];
        
        // Update the password
        $sql="UPDATE admin_table SET password='$password' Where email='$email'";
        $result= mysqli_query($conn,$sql);

        // Check if password update was successful
        if($result){
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    text: 'Password has been successfully reset',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                }).then(function(){
                    window.location.href='../Pages/signin.html';
                });
            </script>";
        } else {
            // If password update fails, display error message
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    text: 'Error resetting password. Please try again later.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                }).then(function(){
                    window.location.href='../Pages/reset_password.html';
                });
            </script>";
        }
    } else {
        // If session token is not set, display error message
        echo "<script>
            Swal.fire({
                icon: 'error',
                text: 'Session expired. Please try resetting the password again.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
            }).then(function(){
                window.location.href='../Pages/reset_password.html';
            });
        </script>";
    }
}
?>
