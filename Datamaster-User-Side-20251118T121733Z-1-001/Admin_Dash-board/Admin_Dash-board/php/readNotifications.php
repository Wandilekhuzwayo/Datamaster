<?php

include("../php/connection.php");

$sql = "UPDATE `questions_table` SET status='1' WHERE timeout != '".""."'";
$res = mysqli_query($conn, $sql);
if ($res) {
  echo "Success";
} else {
  echo "Failed";
}
?>