<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in</title>
  <script src="jquery-3.6.1.min.js"></script>
 

  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  
</body>
</html>

<?php
  session_start();
  include_once('connection.php');
  if(isset($_POST['login-button'])) {
    // username and password sent from form 
    
    $myusername = mysqli_real_escape_string($conn,$_POST['email']);
    $mypassword = mysqli_real_escape_string($conn,$_POST['password']); 
    $password = md5($mypassword);
    
    
    
    $sql_statement = "SELECT id, firstname FROM `admin_table` WHERE email= '$myusername' AND password = '$password'";
    $result = mysqli_query($conn,$sql_statement);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
  
    
    $count = mysqli_num_rows($result);
    
    // If result matched $myusername and $mypassword, table row must be 1 row
  
    if($count == 1) {

    
    echo "<script>
    
    Swal.fire({
      icon: 'success',
      text: 'Login success',
      confirmButtonText: 'OK',
      confirmButtonColor: '#3085d6',
        
    }).then(function(){
      window.location.href='../Pages/index.php';

    });
    </script>";

    $_SESSION['firstname'] = $myusername;
    }
    
    else {
       
    echo "<script>
    Swal.fire({
      icon: 'error',
      title: 'error ',
      text: 'incorrect password or username',
      confirmButtonText: 'OK',
      confirmButtonColor: '#3085d6',

    }).then(function(){
      window.location.href='../Pages/signin.html';

    });
    
    


    </script>";
    }


    
 }
?>