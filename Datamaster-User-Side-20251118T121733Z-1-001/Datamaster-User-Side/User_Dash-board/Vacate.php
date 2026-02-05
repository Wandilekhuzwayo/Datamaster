<?php
  //Start the session
  session_start();

  //Default timezone
  date_default_timezone_set('Africa/Johannesburg');
  
  //Get a connection
  include('connection.php');

  //Get a unique variable from redeem page
  $emailAddress = $_GET['redeemedData'] ?? '';

  //Create a query
  $result = mysqli_query($conn, "SELECT image, fname, lname FROM `user_table` WHERE mnum LIKE '%{$emailAddress}%' OR email LIKE '%{$emailAddress}%'");

  if($result) {
    while($row = mysqli_fetch_assoc($result)) {
      $name = $row['fname'];
      $surname = $row['lname'];
      
      echo '<div class="form-container">
      <div class="img">
        <strong><img src="./Images/Logo.png" alt="Logo" class="responsive"/></strong>
      </div>
      <div class="title">DISPLAY AND EXIT</div>
      <form action="vacateLink.php?logout='.$emailAddress.'" method="post" enctype="multipart/form-data">
      <div class="float-items">
        <div class="person-img">
          <img src="img_Users/'.$row['image'].'" alt="Person" class="reponsive"
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
          <label name="name">'.$name.' '.$surname.'</label>
        </div>
    </div>';
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vacate From Sytem</title>
  <!--Bootstrap 5.0.2-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <!--CSS-->
  <link rel="stylesheet" href="./CSS/douse.css" type="text/css">
</head>
<body>

  <div class="form-button mt-3">
    <button type="submit" class="btn btn-primary" name="exit">Check Out From The System</button>
  </div>
  </form>
  </div>
</body>
</html>