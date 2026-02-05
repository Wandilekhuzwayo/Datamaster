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
  <title>Details & Picture Registration</title>
  <!--Bootstrap 5.0.2-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <!--CSS-->
  <link rel="stylesheet" href="./CSS/record.css">
</head>
<body>
  <div class="form-container">
    <div class="img">
      <strong><img src="./Images/Logo.png" alt="Logo" class="responsive"/></strong>
  </div>
  <div class="title">CAPTURE AND SECURE</div>
  <form action="recordLink.php" method="POST" autocomplete="off" enctype="multipart/form-data" class="requires-validation" novalidate>
    <?php csrf_field(); ?>
    <div class="row">
      <div class="col-md-12">
        <div class="input-group date" id="datePicker">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="text-center">
          <div class="img-webcam" id="camera"></div>
          <div class="output" id="results" name="image"></div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form-button mt-3">
        <button type="button" class="btn btn-primary" name="insert" onclick="snap_img()">REGISTER</button>
      </div>
    </div>
  </form>
  </div>
  
  <!--Here is the capture code-->
  <script defar src="./assets/webcam.min.js"></script>
  <script type="text/javascript">
    Webcam.set({
        width:600,
        height:450,
        image_format:'jpeg',
        jpeg_quality:90,
    });

    Webcam.attach('#camera');
  </script>
  <script type="text/javascript"> 
    function snap_img(){
      Webcam.snap(function(data_uri){
      document.getElementById('results').innerHTML = '<img id="webcam" src="'+data_uri+'"/>';});

      Webcam.reset();
      
      var base64image = document.getElementById("webcam").src;
      
      // Upload image and submit form on success
      Webcam.upload(base64image, 'recordLink.php', function(code, text){
        // After successful upload, submit the form
        document.querySelector('form').submit();
      });
    }
  </script>
</body>
</html>