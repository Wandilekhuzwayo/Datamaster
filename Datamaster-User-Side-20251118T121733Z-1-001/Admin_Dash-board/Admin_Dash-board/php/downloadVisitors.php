<?php 
// Get a connection
include('../php/connection.php');

// Declare a variable
$output = '';

// Query to get active visitors (timeout is empty)
$query = "SELECT u.fname, u.lname, u.mnum, u.email, q.timein 
          FROM `user_table` AS u 
          INNER JOIN `questions_table` AS q 
          ON u.email = q.email_phone 
          WHERE q.timeout = ''";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $no = 1;

    // Start building output
    $output .= '
    <style>
      @page { size: landscape; }
      table { border-collapse: collapse; width: 100%; }
      th, td { border: 1px solid #000; padding: 5px; text-align: left; }
      th { background-color: #3ab5e6; color: #fff; }
    </style>
    <table> 
      <tr> 
        <th>ID</th>
        <th>Name</th>
        <th>Surname</th>
        <th>Contact</th>
        <th>Email</th>
        <th>Time In</th>
      </tr>
    ';

    while ($row = mysqli_fetch_assoc($result)) {
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

    // Send headers to download as XLS
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Visitors_Report.xls");
    header("Cache-Control: max-age=0");

    // Output the file
    echo $output;
    exit(); // Stop further output
} else {
    echo "No records found.";
}
?>
