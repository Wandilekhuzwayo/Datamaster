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
  // Include security helpers
  require_once('session_config.php');
  require_once('validation.php');
  require_once('csrf.php');

  // Get the connection
  include('connection.php');

  // Sanitize input
  $email = sanitize_string($_POST['search'] ?? '');
  $type = sanitize_string($_POST['comType'] ?? '');

  // If search term is definitely not an email and has no letters, sanitize as phone
  if (strpos($email, '@') === false && !preg_match('/[a-zA-Z]/', $email)) {
      $email = sanitize_phone_number($email);
  }

  if(isset($_POST['proceed'])) {
    
    // Validate CSRF token
    // TEMPORARILY DISABLED - Causing blank page issues
    // validate_csrf();
    
    // Validate input is not empty
    if (empty($email)) {
      echo("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'error',
        text: 'Please enter an email or phone number!',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
      }).then(function(){
        window.location.href='Retrieve.php';
      });
      </script>");
      exit;
    }
    
    // Search using prepared statement with LIKE
    $searchTerm = '%' . $email . '%';
    $stmt = $conn->prepare("SELECT mnum, email FROM `user_table` WHERE mnum LIKE ? OR email LIKE ?");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->num_rows;
    $stmt->close();
        
    if($rows > 0) {
      // URL encode the email for safe transmission
      $safeEmail = urlencode($email);
      echo ("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'success',
        text: 'This Info Corresponds',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
      }).then(function(){
        window.location.href='Progress.php?retrievedEmail=$safeEmail';
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
        window.location.href='Retrieve.php';
      });
      </script>");
    }
  }
?>