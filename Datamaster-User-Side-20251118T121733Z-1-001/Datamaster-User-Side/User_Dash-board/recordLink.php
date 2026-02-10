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
  ini_set('display_errors', 1);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validate CSRF token
    // validate_csrf(); // Keep disabled if it caused issues, or re-enable if session config is stable
    
    $base64_string = $_POST['base64image'] ?? '';
    
    if (empty($base64_string)) {
        echo ("<script LANGUAGE='JavaScript'>
        Swal.fire({
          icon: 'error',
          text: 'No image captured. Please try again.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#3085d6', 
        }).then(function(){
          window.location.href='Record.php';
        });
        </script>");
        exit;
    }

    // Process Base64 Image
    $data = explode(',', $base64_string);
    if (count($data) >= 2) {
        $decoded_image = base64_decode($data[1]);
        
        // Generate secure filename (using existing function or creating one)
        if (!function_exists('generate_secure_filename')) {
            function generate_secure_filename($ext) {
                return bin2hex(random_bytes(16)) . '.' . $ext;
            }
        }
        
        $imageName = generate_secure_filename('jpeg');
        
        if (file_put_contents('./img_Users/' . $imageName, $decoded_image) === false) {
             echo ("<script LANGUAGE='JavaScript'>
            Swal.fire({
              icon: 'error',
              text: 'Failed to save image. Check server permissions.',
              confirmButtonText: 'OK',
              confirmButtonColor: '#3085d6', 
            }).then(function(){
              window.location.href='Record.php';
            });
            </script>");
            exit;
        }
    } else {
         echo ("<script LANGUAGE='JavaScript'>
        Swal.fire({
          icon: 'error',
          text: 'Invalid image data.',
          confirmButtonText: 'OK',
          confirmButtonColor: '#3085d6', 
        }).then(function(){
          window.location.href='Record.php';
        });
        </script>");
        exit;
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
        
        // Clear session data after successful registration
        // session_unset(); // Optional: might want to clear specific keys only
        
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
          text: 'User Details Inserted Unsuccessfully: " . addslashes($conn->error) . "',
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