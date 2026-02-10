<?php
  // Include security helpers
  require_once('session_config.php');
  require_once('validation.php');

  // Get the connection
  include('connection.php');

  // Sanitize input
  $email = sanitize_string($_GET['retrievedEmail'] ?? '');
  
  // Validate input exists
  if (empty($email)) {
    header('Location: Retrieve.php');
    exit;
  }

  // Use prepared statement for query
  $searchTerm = '%' . $email . '%';
  $stmt = $conn->prepare("SELECT image, fname, lname FROM `user_table` WHERE mnum LIKE ? OR email LIKE ? LIMIT 1");
  $stmt->bind_param("ss", $searchTerm, $searchTerm);
  $stmt->execute();
  $result = $stmt->get_result();

  $userData = null;
  if($result && $result->num_rows > 0) {
    $userData = $result->fetch_assoc();
  }
  $stmt->close();
  
  // URL encode email for safe use in links
  $safeEmail = htmlspecialchars(urlencode($email), ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Progress To Checklist</title>
  <!--Bootstrap 5.0.2-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

  <!--CSS-->
  <link rel="stylesheet" href="./CSS/progress.css" type="text/css">
  <script src="jquery-3.6.1.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 
</head>
<body>
<?php include('navbar.php'); ?>
  <?php if ($userData): ?>
  <div class="form-container">
    <div class="img">
      <strong><img src="./Images/Logo.png" alt="Logo" class="responsive"/></strong>
    </div>
    <div class="title">DISPLAY AND PROGRESS</div>
    <form action="" enctype="multipart/form-data">
      <div class="float-items">
        <div class="person-img">
          <img src="img_Users/<?php echo htmlspecialchars($userData['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Person" class="responsive" 
          style=" 
          display: inline-block;
          height: 280px;
          width: 280px;
          border-top-left-radius: 12px;
          border-bottom-right-radius: 12px;
          border-top-right-radius: 12px;
          border-bottom-left-radius: 12px;
          margin-left: 1.0px;
          "/>
        </div>
        <div class="name-lbl">
          <label name="name"><?php echo htmlspecialchars($userData['fname'] . ' ' . $userData['lname'], ENT_QUOTES, 'UTF-8'); ?></label>
        </div>
      </div>
      <div class="form-button mt-3">
        <button type="button" class="btn btn-primary" onclick="goChecklists()">Proceed To Visitors Checklist</button>
      </div>
    </form>
  </div>
  <?php else: ?>
  <div class="form-container">
    <div class="img">
      <strong><img src="./Images/Logo.png" alt="Logo" class="responsive"/></strong>
    </div>
    <div class="title">User Not Found</div>
    <p>The user could not be found. Please try again.</p>
    <a href="Retrieve.php" class="btn btn-primary">Go Back</a>
  </div>
  <?php endif; ?>
  
  <script>
    function goChecklists() {
      Swal.fire({
        icon: 'success',
        text: 'Thank you for providing corresponding details. Please continue to checklist',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
      }).then(function(){
        window.location.href = 'Checklist.php?progressedEmail=<?php echo $safeEmail; ?>';
      });
    }
  </script>
</body>
</html>