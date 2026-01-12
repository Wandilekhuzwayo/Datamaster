<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Retrieve & Proceed</title>
  <!--JQuery-->
  <script src="jquery-3.6.1.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  
</body>
</html>

<?php 
  //Start the Session
  session_start();

  //Start the connection
  include('connection.php');

  //Take value from html form
  $email = $_POST['search'];
  $type = $_POST['comType'];

  if(isset($_POST['proceed'])) {
        
    //Get mobile or email from database
    $query = mysqli_query($conn, "SELECT mnum, email FROM `user_table` WHERE mnum LIKE '%{$email}%' OR email LIKE '%{$email}%'");

    $rows = mysqli_num_rows($query);
        
    //final execution
    if($rows){
      echo ("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'success',
        text: 'This Info Corresponds',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
          
      }).then(function(){
        window.location.href='Progress.php?retrievedEmail=$email';
      });
      </script>");
    }
    else {
      echo("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'error',
        text: 'This Info Provided Is Wrong!',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',

      }).then(function(){
        window.location.href='Retrieve.php';
      });
      </script>");
    }
  }
?>