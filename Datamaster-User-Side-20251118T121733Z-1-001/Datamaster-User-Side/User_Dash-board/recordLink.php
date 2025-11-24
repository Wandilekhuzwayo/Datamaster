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
  //Start the session
  session_start();
  
  //Default timezone
  date_default_timezone_set('Africa/Johannesburg');
  
  //Call the connection
  include('connection.php');

  //Get the session 
  $firstname = $_SESSION["firstname"];
  $lastname = $_SESSION["surname"];
  $phone = $_SESSION["mobile"];
  $company = $_SESSION["organization"];
  $email = $_SESSION["emailAddress"];
  $address = $_SESSION["homeAddress"];
  $country = $_SESSION["nation"];
  $state = $_SESSION["province"];
  $city = $_SESSION["town"];
  $code = $_SESSION["postalCode"];
  $name = $_SESSION["firstName"];
  $surname = $_SESSION["lastname"];
  $contact = $_SESSION["telephone"];
  $subscription = $_SESSION["subscription"];
  $date = date("Y/m/d H:i:sa");

  //Hide warnings
  error_reporting(E_ERROR | E_PARSE);

  //Store image to folder
  $tmpName = $_FILES["webcam"]["tmp_name"];
  $imageName = date("Y.m.d"). " - ". date("h.i.sa") . ' .jpeg';
  move_uploaded_file($tmpName, './img_Users/' . $imageName);

  if(isset($_POST['insert'])) {

  //Insert into user table
  $query = "INSERT INTO `user_table`(date, image, fname, lname, mnum, cname, email, address, country, province, city, code, name, surname, contact, subscription)VALUES('$date', '".$imageName."', '".$firstname."', '".$lastname."', '".$phone."', '".$company."', '".$email."', '".$address."', '".$country."', '".$state."', '".$city."', '".$code."', '".$name."', '".$surname."', '".$contact."', '".$subscription."')";

  //Execution
  $result = mysqli_query($conn, $query);

  if($result){
    echo ("<script LANGUAGE='JavaScript'>
    Swal.fire({
      icon: 'success',
      text: 'User Details Inserted Successfully.',
      confirmButtonText: 'OK',
      confirmButtonColor: '#3085d6',
          
    }).then(function(){
      window.location.href='http://localhost/Datamaster-User-Side/Retrieve.html';
    });
    </script>");
  }
  else {
    echo ("<script LANGUAGE='JavaScript'>
    Swal.fire({
      icon: 'error',
      text: 'User Details Inserted Unsuccessfully.',
      confirmButtonText: 'OK',
      confirmButtonColor: '#3085d6', 
          
    }).then(function(){
      window.location.href='http://localhost/Datamaster-User-Side/Record.html';
    });
    </script>");
  }
}
?>