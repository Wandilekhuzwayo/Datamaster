<?php 
  //Start the session
  session_start();

  //storing the session data
  if(isset($_POST["next"])){
    $_SESSION["firstname"] = $_POST['name'];
    $_SESSION["surname"] = $_POST['surname'];
    $_SESSION["mobile"] = $_POST['phone'];
    $_SESSION["organization"] = $_POST['company'];
    $_SESSION["emailAddress"] = $_POST['email'];
    $_SESSION["homeAddress"] = $_POST['address'];
    $_SESSION["nation"] = $_POST['country'];
    $_SESSION["province"] = $_POST['state'];
    $_SESSION["town"] = $_POST['city'];
    $_SESSION["postalCode"] = $_POST['code'];
    $_SESSION["firstName"] = $_POST['fname'];
    $_SESSION["lastname"] = $_POST['lname'];
    $_SESSION["telephone"] = $_POST['cnumber'];
    $_SESSION["subscription"] = $_POST['subscribe'];

    header("Location: http://localhost/Datamaster-User-Side/Record.html");
    exit();
  }
?>