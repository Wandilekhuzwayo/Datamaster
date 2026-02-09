<?php 
   session_start();
   if(!isset($_SESSION["firstname"] )) {

      $_SESSION["firstname"];
      $_SESSION["surname"];
      $_SESSION["email"];
      $_SESSION["companyname"];
      $_SESSION["employeeno"];
      $_SESSION["department"];
      
      header("Location: signin.html");
      exit();
   }
?>