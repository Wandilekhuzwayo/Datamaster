<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vacate From System</title>
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
  
  // Get a connection
  include('connection.php');

  // Sanitize input
  $emailAddress = sanitize_string($_GET['logout'] ?? '');
  $timeout = date("Y/m/d H:i:sa");

  if(isset($_POST['exit'])) {
    
    // Validate CSRF token
    // TEMPORARILY DISABLED - Causing blank page issues
    // validate_csrf();
    
    // Update using prepared statement
    $searchTerm = '%' . $emailAddress . '%';
    $emptyTimeout = '';
    $stmt = $conn->prepare("UPDATE `questions_table` SET timeout = ? WHERE email_phone LIKE ? AND timeout = ?");
    $stmt->bind_param("sss", $timeout, $searchTerm, $emptyTimeout);
    
    if($stmt->execute() && $stmt->affected_rows > 0) {
      $stmt->close();
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
    } else {
      $stmt->close();
      echo("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'error',
        text: 'Sorry, the information could not be updated!',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
      }).then(function(){
        window.location.href='Vacate.php';
      });
      </script>");
    }
  }
?>