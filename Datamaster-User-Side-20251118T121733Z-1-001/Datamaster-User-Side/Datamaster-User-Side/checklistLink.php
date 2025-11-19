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
  $personName = $_POST['personName'];
  $personSurname = $_POST['personSurname'];
  $personContact = $_POST['personContact'];
  $reason = $_POST['reason'];
  $date = date("Y/m/d H:i:sa");

  //Hide warnings
  error_reporting(E_ERROR | E_PARSE); 

  if(!empty($personName) && !empty($personSurname) && !empty($personContact) && !empty($reason)) {
    //insert into questions_table into databse
    $result = mysqli_query($conn, "INSERT INTO `questions_table`(email_phone, person_name, person_surname, person_contact, reason_visit, timein)VALUES('".$emailAddress."', '".$personName."', '".$personSurname."', '".$personContact."', '".$reason."', '".$date."') ");

    if($result) {
      echo ("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'success',
        text: 'Vistor checklist details inserted successfully. Obey Terms & Conditions',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
          
      }).then(function(){
        window.location.href='http://localhost/Datamaster-User-Side/TotalExit.php';
      });
      </script>");
    }
    
  }
  else {
    echo("<script LANGUAGE='JavaScript'>
    Swal.fire({
      icon: 'error',
      text: 'Vistor checklist fields are empty!',
      confirmButtonText: 'OK',
      confirmButtonColor: '#3085d6',

    }).then(function(){
      window.location.href='http://localhost/Datamaster-User-Side/Checklist.php';
    });
    </script>");
  }
?>