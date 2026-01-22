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
  $emailAddress = sanitize_string($_GET['progressedEmail'] ?? '');
  
  // Validate input exists
  if (empty($emailAddress)) {
    header('Location: Retrieve.php');
    exit;
  }

  // Log errors instead of hiding
  error_reporting(E_ALL);
  ini_set('log_errors', 1);
  ini_set('display_errors', 0);

  // Use prepared statement
  $searchTerm = '%' . $emailAddress . '%';
  $stmt = $conn->prepare("SELECT image, fname, lname, mnum, cname, email, address, country, province, city, code FROM `user_table` WHERE mnum LIKE ? OR email LIKE ? LIMIT 1");
  $stmt->bind_param("ss", $searchTerm, $searchTerm);
  $stmt->execute();
  $result = $stmt->get_result();
  
  $userData = null;
  if($result && $result->num_rows > 0) {
    $userData = $result->fetch_assoc();
  }
  $stmt->close();
  
  // Safe email for form action
  $safeEmailUrl = urlencode($emailAddress);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checklists</title>
  <!--Bootstrap 5.0.2-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <!--CSS-->
  <link rel="stylesheet" href="./CSS/checklists.css" type="text/css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

  <script src="jquery-3.6.1.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body onload="mydate()">
  <?php if ($userData): ?>
  <div class="wrapper">
    <div class="form-left">
      <h2 class="text-uppercases">Person information</h2>
      <img src="img_Users/<?php echo htmlspecialchars($userData['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Person" width="200" height="200" 
          style="border-top-left-radius: 8px; 
          border-bottom-right-radius: 8px;
          border-top-right-radius: 8px; 
          border-bottom-left-radius: 8px;
          margin-left: 20%;">
      <div class="fname" style="text-align: center;">
        <label name="fname" id="name" style="font-size: 18px; font-weight: 700;"><?php echo htmlspecialchars($userData['fname'] . ' ' . $userData['lname'], ENT_QUOTES, 'UTF-8'); ?></label>
      </div>
      <div class="mobile">
        <label>Mobile Number :</label>
        <label name="mnum"><?php echo htmlspecialchars($userData['mnum'], ENT_QUOTES, 'UTF-8'); ?></label>
      </div>
      <div class="companyName">
        <label>Company Name :</label>
        <label name="cname"><?php echo htmlspecialchars($userData['cname'], ENT_QUOTES, 'UTF-8'); ?></label>
      </div>
      <div class="emailAddress">
        <label>Email :</label>
        <label name="email"><?php echo htmlspecialchars($userData['email'], ENT_QUOTES, 'UTF-8'); ?></label>
      </div>
      <div class="homeAddress">
        <label>Address :</label>
        <label name="address"><?php echo htmlspecialchars($userData['address'], ENT_QUOTES, 'UTF-8'); ?></label>
      </div>
      <div class="countryName">
        <label>Country :</label>
        <label name="country"><?php echo htmlspecialchars($userData['country'], ENT_QUOTES, 'UTF-8'); ?></label>
      </div>
      <div class="province">
        <label>State/Province :</label>
        <label name="province"><?php echo htmlspecialchars($userData['province'], ENT_QUOTES, 'UTF-8'); ?></label>
      </div>
      <div class="town">
        <label>City :</label>
        <label name="city"><?php echo htmlspecialchars($userData['city'], ENT_QUOTES, 'UTF-8'); ?></label>
      </div>
      <div class="zipCode">
        <label>Zip Code :</label>
        <label name="code"><?php echo htmlspecialchars($userData['code'], ENT_QUOTES, 'UTF-8'); ?></label>
      </div>
      <div class="end">
        : : : : : : : : : : : : : : : : : : : : : : : : : :  END   : : : : : : : : : : : : : : : : : : : : : : : : 
      </div>
    </div>
    <form action="checklistLink.php?uniqueEmail=<?php echo $safeEmailUrl; ?>" method="post" autocomplete="off" class="form-right requires-validation" novalidate>
      <?php csrf_field(); ?>
      <div class="img"><img src="./Images/Logo.png" alt="Logo"/></div>
      <h2 class="text-uppercase">Visitor checklist</h2>
      <div class="mb-3">
        <p>Person Visiting Information</p>
        <input type="text" class="input-field" id="personName" name="personName" placeholder="Firstname" autocomplete="off" required>
        <div class="invalid-feedback">This field cannot be blank!</div>
      </div>
      <div class="mb-3">
        <input type="text" class="input-field" id="personSurname" name="personSurname" placeholder="Lastname" autocomplete="off" required>
        <div class="invalid-feedback">This field cannot be blank!</div>
      </div>
      <div class="mb-3">
        <input type="text" class="input-field" id="personContact" name="personContact" placeholder="Contact" autocomplete="off" required>
        <div class="invalid-feedback">This field cannot be blank!</div>
      </div>
      <div class="mb-3">
        <p>Reason For Visiting & Time-in</p>
        <input type="text" class="input-field" id="reason" name="reason" placeholder="Reason For Visiting" autocomplete="off" required>
        <div class="invalid-feedback">This field cannot be blank!</div>
      </div>
      <div class="row">
        <div class="mb-3"><input type="text" id="demo" name="demo" class="input-date" required></div>
      </div>
      <div class="mb-3">
        <label class="option">I agree to the <a href="#">Terms and Conditions</a>
          <input type="checkbox" required>
          <span class="checkmark"></span>
        </label>
      </div>
      <div class="form-field">
        <input type="submit" value="SUBMIT" class="register" name="Register">
      </div>
    </form>
  </div>
  <?php else: ?>
  <div class="wrapper">
    <div class="form-left">
      <h2>User Not Found</h2>
      <p>The user could not be found. Please try again.</p>
      <a href="Retrieve.php" class="btn btn-primary">Go Back</a>
    </div>
  </div>
  <?php endif; ?>
  
  <script type="text/javascript">
    function mydate() {
      document.getElementById('demo').value = Date();
    }
  </script>
  <script defer src="./JS/validate.js"></script>
</body>
</html>