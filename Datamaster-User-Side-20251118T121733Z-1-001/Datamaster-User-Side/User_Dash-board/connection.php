<?php
  //localhst connection
  $conn = mysqli_connect("localhost", "root", "", "datamaster");

  //productions connection
  //$conn = mysqli_connect("localhost", "evendico_datamaster", "Zx2gXSLMqCpsA6wP2wUK", "evendico_datamaster");

  //Check if it connects
  if(mysqli_connect_errno()){
    echo("Failed to Connect to MYSQL :" . mysqli_connect_errno());
  }
?>