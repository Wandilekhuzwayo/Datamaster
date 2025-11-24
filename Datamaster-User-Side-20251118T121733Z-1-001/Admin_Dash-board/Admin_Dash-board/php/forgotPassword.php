<?php

session_start();
include_once('connection.php');
require_once('email.php');
if(isset($_POST['reset-password']) && $_POST['email'])
{
  $email=$_POST['email'];
$sql = "SELECT * FROM admin_table WHERE email='$email' LIMIT 1";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
$token = $user['token'];
$_SESSION["email"]=$user['email'];
$_SESSION["token"]=$token;

sendEmail($email,$token);
header('location: ../pages/reset-notification.html');
exit(0);


}

?>