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
  // Include security helpers
  require_once('session_config.php');
  require_once('validation.php');
  require_once('csrf.php');

  // Get the connection
  include('connection.php');

  // Sanitize input
  $email = sanitize_string($_POST['search'] ?? '');
  $option = sanitize_string($_POST['option'] ?? '');

  if(isset($_POST['vacate'])) {
    
    // Validate CSRF token
    // TEMPORARILY DISABLED - Causing blank page issues
    // validate_csrf();
    
    // Validate input
    if (empty($email)) {
      echo("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'error',
        text: 'Please enter an email or phone number!',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
      }).then(function(){
        window.location.href='Redeem.php';
      });
      </script>");
      exit;
    }

    // Search using prepared statement
    $searchTerm = '%' . $email . '%';
    $stmt = $conn->prepare("SELECT mnum, email FROM `user_table` WHERE mnum LIKE ? OR email LIKE ?");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->num_rows;
    $stmt->close();

    if($rows > 0) {
      $safeEmail = urlencode($email);
      echo ("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'success',
        text: 'This Info Corresponds',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
      }).then(function(){
        window.location.href='Vacate.php?redeemedData=$safeEmail';
      });
      </script>");
    } else {
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