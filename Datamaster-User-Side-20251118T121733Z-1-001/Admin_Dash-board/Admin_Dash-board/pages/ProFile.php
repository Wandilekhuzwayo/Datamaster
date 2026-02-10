<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checklists</title>
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

  //Default timezone
  date_default_timezone_set('Africa/Johannesburg');
  
  //Get the connection
  include('connection.php');

  $emailAddress = $_GET['uniqueEmail'];

  //Store values into variables
  $firstame = $_POST['firstname'];
  $surname = $_POST['surname'];
  $email = $_POST['email'];
  $department = $_POST['department'];
  $employeeNo = date("employeeNo");

  //Hide warnings
  error_reporting(E_ERROR | E_PARSE); 

  if(!empty($firstname) && !empty($surname) && !empty($email) && !empty($department) && !empty($employeeNo)) {
    
      echo ("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'success',
        text: 'Vistor checklist details inserted successfully. Obey Terms & Conditions',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
          
      }).then(function(){
        window.location.href='http://localhost/DataMaster/Datamaster-User-Side/TotalExit.php';
      });
      </script>");
    
    
  }
  else {
    echo("<script LANGUAGE='JavaScript'>
    Swal.fire({
      icon: 'error',
      text: 'Vistor checklist fields are empty!',
      confirmButtonText: 'OK',
      confirmButtonColor: '#3085d6',

    }).then(function(){
      window.location.href='http://localhost/DataMaster/Datamaster-User-Side/Checklist.php';
    });
    </script>");
  }
?>