<?php
  //Start the session
  session_start();
  
  //Default timezone
  date_default_timezone_set('Africa/Johannesburg');
  
  //Get a connection
  include('connection.php');

  //Call a unique variable from progress page
  $emailAddress = $_GET['progressedEmail'];

  //Hide warning errors
  error_reporting(E_ERROR | E_PARSE);

  //Create a Query
  $result = mysqli_query($conn, "SELECT image, fname, lname, mnum, cname, email, address, country, province, city, code FROM `user_table` WHERE mnum LIKE '%{$emailAddress}%' OR email LIKE '%{$emailAddress}%'");

  if($result) {
    //Get a data from user_table row
    while($row = mysqli_fetch_assoc($result)) {
      $image = $row["image"]; 
      $firstname = $row['fname'];
      $lastname = $row['lname'];
      $mobile = $row['mnum'];
      $company = $row['cname'];
      $email = $row['email'];
      $address = $row['address'];
      $country = $row['country'];
      $state = $row['province'];
      $town = $row['city'];
      $code = $row['code'];
      
      echo '<div class="wrapper">
      <div class="form-left">
        <h2 class="text-uppercases">Person information</h2>
        <img src="img_Users/'.$image.'" alt="Person" width="200" height="200" 
            style="border-top-left-radius: 8px; 
            border-bottom-right-radius: 8px;
            border-top-right-radius: 8px; 
            border-bottom-left-radius: 8px;
            margin-left: 20%;">
            <div class="fname" style="text-align: center;">
                <label name="fname" id="name" style="font-size: 18px; font-weight: 700;" >'.$firstname.' '.$lastname.'</label>
            </div>
            <div class="mobile">
                <label>Mobile Number :</label>
                <label name="mnum">'.$mobile.'</label>
            </div>
            <div class="companyName">
                <label>Company Name :</label>
                <label name="cname">'.$company.'</label>
            </div>
            <div class="emailAddress">
                <label>Email :</label>
                <label name="email">'.$email.'</label>
            </div>
            <div class="homeAddress">
                <label>Address :</label>
                <label name="address">'.$address.'</label>
            </div>
            <div class="countryName">
                <label>Country :</label>
                <label name="country">'.$country.'</label>
            </div>
            <div class="province">
                <label>State/Province :</label>
                <label name="province">'.$state.'</label>
            </div>
            <div class="town">
                <label>City :</label>
                <label name="city">'.$town.'</label>
            </div>
            <div class="zipCode">
                <label>Zip Code :</label>
                <label name="code">'.$code.'</label>
            </div>
            <div class="end">
                : : : : : : : : : : : : : : : : : : : : : : : : : :  END   : : : : : : : : : : : : : : : : : : : : : : : : 
            </div>
          </div>
          <form action="checklistLink.php?uniqueEmail='.$emailAddress.'" method="post" autocoplete="off" class="form-right" class="requires-validation" novalidate>';
    }
  }
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

  <style>

    
  </style>
</head>
<body onload="mydate()">
  
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
            <input type="text" class="input-field" id="reason" name="reason" placeholder="Reason For Visiting"  autocomplete="off" required>
            <div class="invalid-feedback">This field cannot be blank!</div>
          </div>
          <div class="row">
            <div class="mb-3"><input type="text" id="demo" name="demo" class="input-date" required></div>
          </div>
          <div class="mb-3">
            <label class="option">I agree to the <a href="#">Terms and Conditions</a>
                <input type="checkbox">
                <span class="checkmark"></span>
            </label>
          </div>
          <div class="form-field">
            <input type="submit" value="SUBMIT" class="register" name="Register">
          </div>
        </form>
    </div>
  </div>
  <script type="text/javascript">
    function mydate() {
      document.getElementById('demo').value = Date();
    }
  </script>
  <script defer src="./JS/validate.js"></script>
</body>
</html>