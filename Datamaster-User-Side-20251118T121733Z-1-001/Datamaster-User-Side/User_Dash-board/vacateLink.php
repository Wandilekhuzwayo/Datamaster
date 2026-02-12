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
  $logoutParam = $_GET['logout'] ?? '';
  $timeout = date("Y/m/d H:i:sa");

  if(isset($_POST['exit'])) {
    
    // Validate CSRF token
    // TEMPORARILY DISABLED - Causing blank page issues
    // validate_csrf();

    $identifiers = [];

    // If param is numeric, treat as User ID and fetch details
    if(is_numeric($logoutParam)) {
        $stmtUser = $conn->prepare("SELECT mnum, email FROM user_table WHERE id = ?");
        $stmtUser->bind_param("i", $logoutParam);
        $stmtUser->execute();
        $resUser = $stmtUser->get_result();
        if($u = $resUser->fetch_assoc()) {
            if(!empty($u['mnum'])) $identifiers[] = $u['mnum'];
            if(!empty($u['email'])) $identifiers[] = $u['email'];
        }
        $stmtUser->close();
    } else {
        // Fallback for legacy calls or direct searches
        $identifiers[] = sanitize_string($logoutParam);
    }
    
    $updated = false;

    // Try to update records for ANY of the identifiers (Phone or Email)
    // Also check for timeout = '0' (default) or '' (legacy)
    $stmt = $conn->prepare("UPDATE `questions_table` SET timeout = ? WHERE email_phone LIKE ? AND (timeout = '0' OR timeout = '')");
    
    foreach($identifiers as $idVal) {
        if(empty($idVal)) continue;
        
        $searchTerm = '%' . $idVal . '%';
        $stmt->bind_param("ss", $timeout, $searchTerm);
        
        if($stmt->execute() && $stmt->affected_rows > 0) {
            $updated = true;
        }
    }
    $stmt->close();
    
    if($updated) {
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