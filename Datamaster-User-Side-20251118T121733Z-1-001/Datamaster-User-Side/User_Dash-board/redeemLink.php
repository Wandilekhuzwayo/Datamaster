<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redeem & Vacate</title>
  <!--JQuery-->
  <script src="jquery-3.6.1.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  
</body>
</html>

<?php 
  //Start the session
  session_start();

  //Get the connection
  include('connection.php');

  //Get values from html
  $email = $_POST['search'];
  $option = $_POST['option'];

  if(isset($_POST['vacate'])) {

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
        window.location.href='Vacate.php?redeemedData=$email';
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
        window.location.href='Redeem.php';
      });
      </script>");
    }
  } 
?>