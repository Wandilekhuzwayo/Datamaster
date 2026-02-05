<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Details & Picture Registration</title>
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
  
  // Call the connection
  include('connection.php');

  // Get the session data with sanitization
  $firstname = sanitize_string($_SESSION["firstname"] ?? '');
  $lastname = sanitize_string($_SESSION["surname"] ?? '');
  $phone = sanitize_string($_SESSION["mobile"] ?? '');
  $company = sanitize_string($_SESSION["organization"] ?? '');
  $email = sanitize_string($_SESSION["emailAddress"] ?? '');
  $address = sanitize_string($_SESSION["homeAddress"] ?? '');
  $country = sanitize_string($_SESSION["nation"] ?? '');
  $state = sanitize_string($_SESSION["province"] ?? '');
  $city = sanitize_string($_SESSION["town"] ?? '');
  $code = sanitize_string($_SESSION["postalCode"] ?? '');
  $name = sanitize_string($_SESSION["firstName"] ?? '');
  $surname = sanitize_string($_SESSION["lastname"] ?? '');
  $contact = sanitize_string($_SESSION["telephone"] ?? '');
  $subscription = sanitize_string($_SESSION["subscription"] ?? '');
  $date = date("Y/m/d H:i:sa");

  // Log errors instead of hiding them
  error_reporting(E_ALL);
  ini_set('log_errors', 1);
  ini_set('display_errors', 1); // TEMPORARILY ENABLED FOR DEBUGGING

  if(isset($_POST['insert'])) {
    
    // Validate CSRF token
    // TEMPORARILY DISABLED - Causing blank page issues
    // validate_csrf();
    
    // Validate and process image upload securely
    $imageName = '';
    if (isset($_FILES["webcam"]) && $_FILES["webcam"]["error"] === UPLOAD_ERR_OK) {
      $validation = validate_image_upload($_FILES["webcam"]);
      
      if (!$validation['valid']) {
        echo ("<script LANGUAGE='JavaScript'>
        Swal.fire({
          icon: 'error',
          text: '" . addslashes($validation['error']) . "',
          confirmButtonText: 'OK',
          confirmButtonColor: '#3085d6', 
        }).then(function(){
          window.location.href='Record.php';
        });
        </script>");
        exit;
      }
      
      // Generate secure filename
      $imageName = generate_secure_filename('jpeg');
      move_uploaded_file($_FILES["webcam"]["tmp_name"], './img_Users/' . $imageName);
    }
    
    // Check for duplicate user using prepared statement
    $checkStmt = $conn->prepare("SELECT id FROM `user_table` WHERE email = ? OR mnum = ?");
    $checkStmt->bind_param("ss", $email, $phone);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
      $checkStmt->close();
      echo ("<script LANGUAGE='JavaScript'>
      Swal.fire({
        icon: 'error',
        text: 'User with this email or phone number already exists!',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6', 
      }).then(function(){
        window.location.href='Register.php';
      });
      </script>");
    } else {
      $checkStmt->close();
      
      // Insert using prepared statement
      $insertStmt = $conn->prepare("INSERT INTO `user_table` (date, image, fname, lname, mnum, cname, email, address, country, province, city, code, name, surname, contact, subscription) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $insertStmt->bind_param("ssssssssssssssss", $date, $imageName, $firstname, $lastname, $phone, $company, $email, $address, $country, $state, $city, $code, $name, $surname, $contact, $subscription);
      
      if($insertStmt->execute()) {
        $insertStmt->close();
        echo ("<script LANGUAGE='JavaScript'>
        Swal.fire({
          icon: 'success',
          text: 'User Details Inserted Successfully.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#3085d6',
        }).then(function(){
          window.location.href='Retrieve.php';
        });
        </script>");
      } else {
        $insertStmt->close();
        echo ("<script LANGUAGE='JavaScript'>
        Swal.fire({
          icon: 'error',
          text: 'User Details Inserted Unsuccessfully.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#3085d6', 
        }).then(function(){
          window.location.href='Record.php';
        });
        </script>");
      }
    }
  }
?>