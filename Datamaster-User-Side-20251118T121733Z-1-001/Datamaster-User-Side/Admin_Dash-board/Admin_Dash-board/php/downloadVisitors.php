<?php 
  //Get a connecion
  include('../php/connection.php');

  //Declare a variable
  $output = '';

  //Do a query to access a table in database
  $result = mysqli_query($conn, "SELECT u.fname, u.lname, u.mnum, u.email, q.timein FROM `user_table` AS u INNER JOIN `questions_table` AS q ON u.email = q.email_phone WHERE q.timeout = '"." "."' ");

  if(mysqli_num_rows($result) > 0) {
    $no=1;
    $output .= '
    <table width="107%" cellpadding="1" cellspacing="1" border="1"> 
    <tr> 
    <th>ID</th>
    <th>Name</th>
    <th>Surname</th>
    <th>Contact</th>
    <th>Email</th>
    <th>Time In</th>
    </tr>
    ';
    while($row = mysqli_fetch_array($result)){
      $output .= '
      <tr>
      <td>'.$no.'</td> 
      <td>'.$row["fname"].'</td> 
      <td>'.$row["lname"].'</td> 
      <td>'.$row["mnum"].'</td> 
      <td>'.$row["email"].'</td>  
      <td>'.$row["timein"].'</td>  
      </tr>
      ';
      $no++;
    }
    $output .= '</table>';
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=current.xls");
    echo $output;
  }
?>