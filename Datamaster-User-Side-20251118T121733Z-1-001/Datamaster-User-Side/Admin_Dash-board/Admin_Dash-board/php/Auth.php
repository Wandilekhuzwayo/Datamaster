<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Auth</title>

	<script src="jquery-3.6.1.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
	
</body>
</html>

<?php
session_start();

include_once("connection.php");
require_once('email.php');
// REGISTER USER
// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['firstname'], $_POST['password'], $_POST['email'])) {
	// Could not get the data that should have been sent.
	exit('Please complete the registration form!');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['firstname']) || empty($_POST['password']) || empty($_POST['email'])) {
	// One or more values are empty.
	exit('Please complete the registration form');
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	
	echo ("<script>
	Swal.fire({
		icon: 'success',
		text: 'invalid email!!!',
		confirmButtonText: 'OK',
		confirmButtonColor: '#3085d6',
		  
	}).then(function(){
		window.location.href='../Pages/signup.html';
	});
  </script>");
}



if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	echo ("<script>
	Swal.fire({
		icon: 'error',
		title: 'error ',
		text: 'password must be between 5 to 20 charactures!!!',
		confirmButtonText: 'OK',
		confirmButtonColor: '#3085d6',
		  
	}).then(function(){
		window.location.href='../Pages/signup.html';
  });
	</script>");
}
// We need to check if the account with that username exists.
if ($stmt = $conn->prepare('SELECT id, password FROM `admin_table` WHERE email = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Username already exists
		echo ("<script>
		Swal.fire({
			icon: 'error',
			title: 'error ',
			text: 'user exists!!!',
			confirmButtonText: 'OK',
			confirmButtonColor: '#3085d6',
			 
		}).then(function(){
			window.location.href='../Pages/signup.html';
	  });
    </script>");
	} else {


 
if ($stmt = $conn->prepare('INSERT INTO `admin_table` (firstname, surname, email,employeeNo, department,password,token) VALUES (?, ?, ?,?,?,?,?)')) {
	// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
	//$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $password = md5($_POST['password']);
  $token = bin2hex(random_bytes(50));
  $stmt->bind_param('sssssss', $_POST['firstname'],$_POST['lastname'],$_POST['email'],$_POST['employeeno'], $_POST['department'],$password,$token);
  $stmt->execute();
  
  verifyAccount($_POST['email'],$token);
  echo ("<script>
	Swal.fire({
		icon: 'success',
		text: 'Successfully registered!',
		confirmButtonText: 'OK',
		confirmButtonColor: '#3085d6',

	}).then(function(){
		window.location.href='http://localhost/Admin_Dash-board/pages/verify-message.html';
	});
	</script>");
  //header('location: ../pages/verify-message.html');
  exit(0);
  $_SESSION["firstname"]=$_POST['email'];

} else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
	echo 'Could not prepare statement!';
}
	}
	$stmt->close();
} else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
	echo 'Could not prepare statement!';
}


//login



$conn->close();
?>