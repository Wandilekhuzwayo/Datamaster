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
if (!isset($_POST['change-password'], $_POST['password'], $_POST['password2'])) {
	// Could not get the data that should have been sent.
	exit('all field are required');
}
else{
     
    if(isset($_SESSION['token'])){
    
    
        $password = md5($_POST['password']);
        $email=$_SESSION['email'];
        $sql="UPDATE admin_table SET password='$password' Where email='$email'";
        $result= mysqli_query($conn,$sql);
        if($result){

            echo "<script>
            Swal.fire({
                icon: 'success',
                  text: 'password has been  successfully reseted',
                  confirmButtonText: 'OK',
                  confirmButtonColor: '#3085d6',
                  
              }).then(function(){
                window.location.href='../Pages/signin.html';
          
              });

       
            




            </script>";
        }
        

    }
}


?>