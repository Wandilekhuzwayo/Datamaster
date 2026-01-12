<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vacate From Sytem</title>
  <!--JQuery-->
  <script src="jquery-3.6.1.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  
</body>
</html>

<?php 
  //Start the seesion
  session_start();

  //Default timezone
  date_default_timezone_set('Africa/Johannesburg');
  
  //Get a connection
  include('connection.php');

  //Get a unique value from vacate page
  $emailAddress = $_GET['logout'];

  $timeout = date("Y/m/d H:i:sa");

  if(isset($_POST['exit'])) {
    $result = mysqli_query($conn, "UPDATE `questions_table` SET timeout = '".$timeout."' WHERE email_phone LIKE '%{$emailAddress}%' AND timeout = '".""."'");

    if($result) {
      echo ("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'success',
        text: 'Thank You For Visiting Us. Till Next Time.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
          
      }).then(function(){
        window.location.href='TotalExit.php';
      });
      </script>");
    }
    else {
      echo("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'error',
        text: 'Sorry The Info Didn't Updated!',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',

      }).then(function(){
        window.location.href='Vacate.php';
      });
      </script>");
    }
  }
?>