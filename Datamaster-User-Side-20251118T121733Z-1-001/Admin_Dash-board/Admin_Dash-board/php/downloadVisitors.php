<?php 
// Get a connection
include('../php/connection.php');

// Declare a variable
$output = '';

// Correct query (NO empty string comparison)
$query = "SELECT name, surname, phone_number, email, signInTime 
          FROM visitorstbl
          WHERE EvacuatingTime IS NULL";

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
          <td>'.$row["name"].'</td> 
          <td>'.$row["surname"].'</td> 
          <td>'.$row["phone_number"].'</td> 
          <td>'.$row["email"].'</td>  
          <td>'.$row["signInTime"].'</td>  
        </tr>
        ';
        $no++;
    }

    $output .= '</table>';

    // Send headers to download as Excel
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Active_Visitors_Report.xls");
    header("Cache-Control: max-age=0");

    echo $output;
    exit();
} else {
    echo "No active visitors found.";
}
?>