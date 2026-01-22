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
  // Include security helpers
  require_once('session_config.php');
  require_once('validation.php');
  require_once('csrf.php');

  // Default timezone
  date_default_timezone_set('Africa/Johannesburg');
  
  // Get the connection
  include('connection.php');

  // Get and sanitize input
  $emailAddress = sanitize_string($_GET['uniqueEmail'] ?? '');
  $personName = sanitize_string($_POST['personName'] ?? '');
  $personSurname = sanitize_string($_POST['personSurname'] ?? '');
  $personContact = sanitize_string($_POST['personContact'] ?? '');
  $reason = sanitize_string($_POST['reason'] ?? '');
  $date = date("Y/m/d H:i:sa");

  // Log errors instead of hiding
  error_reporting(E_ALL);
  ini_set('log_errors', 1);
  ini_set('display_errors', 0);

  if(!empty($personName) && !empty($personSurname) && !empty($personContact) && !empty($reason)) {
    
    // Validate CSRF token
    validate_csrf();
    
    // Validate inputs
    if (!validate_name($personName) || !validate_name($personSurname)) {
      echo("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'error',
        text: 'Invalid name format!',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
      }).then(function(){
        window.location.href='Checklist.php';
      });
      </script>");
      exit;
    }
    
    // Insert using prepared statement
    $stmt = $conn->prepare("INSERT INTO `questions_table` (email_phone, person_name, person_surname, person_contact, reason_visit, timein, status, timeout) VALUES (?, ?, ?, ?, ?, ?, 1, '0')");
    $stmt->bind_param("ssssss", $emailAddress, $personName, $personSurname, $personContact, $reason, $date);

    if($stmt->execute()) {
      $stmt->close();
      echo ("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'success',
        text: 'Visitor checklist details inserted successfully. Obey Terms & Conditions',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
      }).then(function(){
        window.location.href='TotalExit.php';
      });
      </script>");
    } else {
      $stmt->close();
      echo ("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'error',
        text: 'Failed to save checklist. Please try again.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
      }).then(function(){
        window.location.href='Checklist.php';
      });
      </script>");
    }
  } else {
    echo("<script LANGUAGE='JavaScript'>
    Swal.fire({
      icon: 'error',
      text: 'Visitor checklist fields are empty!',
      confirmButtonText: 'OK',
      confirmButtonColor: '#3085d6',
    }).then(function(){
      window.location.href='Checklist.php';
    });
    </script>");
  }
?>