<?php
  //Create a connection
  $conn = mysqli_connect("localhost", "root", "@tpdT3pd", "datamaster");
  
  //Check if it connects
  if(mysqli_connect_errno()){
    echo("Failed to Connect to MYSQL :" . mysqli_connect_errno());
  }
?>