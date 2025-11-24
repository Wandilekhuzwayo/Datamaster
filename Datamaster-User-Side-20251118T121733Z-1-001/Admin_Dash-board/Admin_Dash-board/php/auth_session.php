<?php 
   session_start();
   if(!isset($_SESSION["firstname"])) {
      header("Location: signin.html");
      exit();
   }
?>