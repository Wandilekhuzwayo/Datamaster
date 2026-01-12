<?php
  //Start the session
  session_start();

  //Get the connection
  include('connection.php');

  //Call Unique variable from retrieve page
  $email = $_GET['retrievedEmail'];

  //Create a query
  $result = mysqli_query($conn, "SELECT image, fname, lname FROM `user_table` WHERE mnum LIKE '%{$email}%' OR email LIKE '%{$email}%' LIMIT 1");

  if($result) {
    //Get data from table user_table row
    while($row = mysqli_fetch_assoc($result)) {
      $name = $row['fname'];
      $surname = $row['lname'];
      
      echo'<div class="form-container">
      <div class="img">
        <strong><img src="./Images/Logo.png" alt="Logo" class="responsive"/></strong>
    </div>
    <div class="title">DISPLAY AND PROGRESS</div>
    <form action="" enctype="multipart/form-data">
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
  
      <div class="form-button mt-3">
        <button type="button" class="btn btn-primary" onclick="goChecklists()">Proceed To Visitors Checklist</button>
      </div>
      </form>
  </div>
  <script LANGUAGE="javascript">
    function goChecklists() {
      <?php 
        //Again access variable
        $emailAddress = $_GET['retrievedEmail'];
        
      ?>

    Swal.fire({
        icon: 'success',
        text: 'Thank you for providing corresponding details. Please continue to checklist',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6',
          
      }).then(function(){
        <?php
          //Again access variable
          $emailAddress = $_GET['retrievedEmail'];

          echo "window.location.href='Checklist.php?progressedEmail=$emailAddress'";
        ?>

      });
    }
  </script>
</body>
 
</html>