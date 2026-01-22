<?php
require_once('session_config.php');
require_once('csrf.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redeem & Vacate</title>
  <!--Bootstrap 5.0.2-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <!--CSS-->
  <link rel="stylesheet" href="./CSS/redeem.css" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <div class="form-container">
    <div class="img">
      <strong><img src="./Images/Logo.png" alt="Logo" class="responsive"/></strong>
  </div>
  <div class="title">RETRIEVE AND VACATE</div>
  <form action="redeemLink.php" method="post" autocomplete="off" enctype="multipart/form-data" class="requires-validation" novalidate>
      <?php csrf_field(); ?>
      <div class="d-flex">
          <div class="form-group">
              <select id="select" onchange="selectChange(this.value)" class="form-select mt-3" style="width: 200px;" name="option" required>
                  <option selected disabled value="">-Choose Type-</option>
                  <option value="phone">Phone Number</option>
                  <option value="email">E-mail Address</option>
              </select>
              <div class="invalid-feedback">Please select your type!</div> 
          </div>
          <div class="form-group">
              <input id="input" onkeyup="inputChange(this.value)" class="form-control" type="text" name="search"  style="width: 400px;" required minlength="10">
              <button type="submit" class="btn btn-primary" name="vacate" style="width: 100px;"><i class="fa fa-search" aria-hidden="true"></i></button>
              <div class="invalid-feedback">Email or Phone field cannot be blank!</div>
              <div class="invalid-feedback">Email or Phone field must have a required!</div>
          </div>
      </div>
  </form>
  </div>
  <script defer src="./JS/restrict.js"></script>
</body>
</html>