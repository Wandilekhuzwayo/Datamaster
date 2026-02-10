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
  <title>Registration of Details</title>
  <!--Bootstrap 5.0.2-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

  <!--CSS-->
  <link rel="stylesheet" href="./CSS/register.css">
</head>
<body>
  <?php include('navbar.php'); ?>
  <div class="form-body">
    <div class="row">
        <div class="form-holder">
            <div class="form-content">
                <div class="form-items">
                    <div class="img"><img src="./Images/Logo.png" alt="Logo"/></div>
                    <br/>
                    <p>PROVIDE THE REQUIRED PERSONAL DETAILS BELOW</p>
                    <form action="registerLink.php" method="post" autocomplete="off" class="requires-validation" novalidate>
                        <?php csrf_field(); ?>

                        <div class="col-md-12">
                           <input class="form-control" type="text" name="name" placeholder="First Name" required>
                           <div class="invalid-feedback">This field cannot be blank!</div>
                        </div>


                        <div class="col-md-12">
                            <input class="form-control" type="text" name="surname" placeholder="Last Name" required>
                            <div class="invalid-feedback">This field cannot be blank!</div>
                        </div>

                        <div class="col-md-12">
                            <input class="form-control" type="tel" name="phone" id="mobileNum" placeholder="Phone Number" required>
                            <div class="invalid-feedback">This field cannot be blank!</div>
                        </div>

                        <div class="col-md-12">
                            <input class="form-control" type="text" name="company" placeholder="Company Name" required>
                            <div class="invalid-feedback">This field cannot be blank!</div>
                        </div>

                        <div class="col-md-12">
                            <input class="form-control" type="email" name="email" id="email" placeholder="E-mail Address" required>
                             <div class="invalid-feedback">This field cannot be blank!</div>
                        </div>

                        <div class="col-md-12">
                            <input class="form-control" type="text" name="address" placeholder="Home/Physical Address" required>
                            <div class="invalid-feedback">This field cannot be blank!!</div>
                        </div>

                        <div class="col-md-12">
                            <input class="form-control" type="text" name="country" id="input" placeholder="Country" required>
                            <div class="invalid-feedback">This field cannot be blank!</div>
                        </div>

                        <ul class="list"></ul>

                        <div class="col-md-12">
                            <input class="form-control" type="text" name="state" id="insert" placeholder="Province/State" required>
                            <div class="invalid-feedback">This field cannot be blank!</div>
                        </div>

                        <ul class="list2"></ul>

                        <div class="col-md-12">
                            <input class="form-control" type="text" name="city" placeholder="Town/City" required>
                            <div class="invalid-feedback">This field cannot be blank!</div>
                        </div>

                        <div class="col-md-12">
                            <input class="form-control" type="text" name="code" placeholder="Postal Code" required>
                            <div class="invalid-feedback">This field cannot be blank!</div>
                        </div><br/>
                        <div class="col-md-12">
                            <p>ENTER DETAILS OF PERSON TO CONTACT IN CASE OF EMERGENCY</p>
                        </div>
                        <div class="col-md-12">
                            <input class="form-control" type="text" name="fname" placeholder="First Name" required>
                            <div class="invalid-feedback">This field cannot be blank!</div>
                        </div>
                        <div class="col-md-12">
                            <input class="form-control" type="text" name="lname" placeholder="Last Name" required>
                            <div class="invalid-feedback">This field cannot be blank!</div>
                        </div>
                        <div class="col-md-12">
                            <input class="form-control" type="tel" name="cnumber" placeholder="Contact Number" required>
                            <div class="invalid-feedback">This field cannot be blank!</div>
                        </div>

                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                      <label class="form-check-label">I confirm all information is correct</label>
                     <div class="invalid-feedback">Please confirm that the entered information is all correct!</div>
                    </div>

                    <div class="check-box">
                        <input type="checkbox" name="subscribe" class="form-check-input" id="subscribe" value="Subscribed!">
                        <label class="form-check-label">Tick the box if you would like to receive <em>Newsleter</em>&nbsp; from us</label>
                    </div>
              
                    <div class="form-button mt-3">
                        <button id="submit" type="submit" class="btn btn-primary" name="next">Next >>></button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 
<script src="./Scripts/Countries.js"></script>
<script defer src="./Scripts/index.js"></script>
<script defer src="./JS/validate.js"></script>
</body>
</html>