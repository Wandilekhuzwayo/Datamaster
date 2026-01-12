<?php
  // Starting session
  session_start();
 
  // Destroying session
  if(session_destroy()) {
    unset($_SESSION['firstname']);
    unset($_SESSION["surname"]);
    unset($_SESSION["mobile"]);
    unset($_SESSION["contact"]);
    unset($_SESSION["organization"]);
    unset($_SESSION["emailAddress"]);
    unset($_SESSION["homeAddress"]);
    unset($_SESSION["nation"]);
    unset($_SESSION["province"]);
    unset($_SESSION["town"]);
    unset($_SESSION["postalCode"]);
    unset($_SESSION["firstName"]);
    unset($_SESSION["lastname"]);
    unset($_SESSION["telephone"]);
    unset($_SESSION["subscription"]);
    header("Location: Dashboard.html"); 
  }
?>