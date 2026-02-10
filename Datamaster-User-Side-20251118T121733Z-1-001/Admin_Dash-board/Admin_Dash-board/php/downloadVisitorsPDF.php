<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE); // Suppress deprecated warnings
ob_start();

include('../php/connection.php');
include("../php/auth_session.php");

// Get user's email from session
$emailAddres = $_SESSION["firstname"];

// Always define current date/time
$currentDatetime = date("Y-m-d H:i:s");

// Query user info (optional)
$result = mysqli_query($conn, "SELECT firstname, surname, email, companyname, employeeNo, department FROM `admin_table` WHERE email ='$emailAddres'");
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $firstname = $row['firstname'];
    $lastname = $row['surname'];
    $email = $row['email'];
    $Enterprise = $row['companyname'];
    $employeeID = $row['employeeNo'];
    $department = $row['department'];
}

// Include TCPDF
require_once('../tcpdf/tcpdf.php');

// Create PDF
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->SetFont('helvetica', '', 12);
$pdf->AddPage();

// Add logo
$pdf->Image('../images/Logo.jpeg', 220, 3, 55, 20, 'JPEG');

// PDF title
$pdf->SetFont('helvetica', 'B', 13);
$pdf->Cell(0, 5, 'DataMaster: Active Visitor Report', 0, 1);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(30, 5, 'Date:', 0, 0);
$pdf->Cell(50, 5, $currentDatetime, 0, 1);
$pdf->Ln(5);

// Query active visitors
$query = "SELECT id, email_phone, person_name, person_surname, person_contact, DATE(timein) AS date_in, TIME(timein) AS time_in 
          FROM questions_table WHERE timeout = ''";
$res = mysqli_query($conn, $query);

$export = '';
if ($res && mysqli_num_rows($res) > 0) {
    $export = '<table border="1" cellpadding="4" width="100%">
        <tr style="background-color:#3ab5e6; color:white;">
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Mobile No.</th>
            <th>Email Address</th>
            <th>Date In</th>
            <th>Time In</th>
        </tr>';

    $no = 1;
    while ($row = mysqli_fetch_assoc($res)) {
        $export .= '<tr>
            <td>'.$no.'</td>
            <td>'.$row["person_surname"].'</td>
            <td>'.$row["person_name"].'</td>
            <td>'.$row["person_contact"].'</td>
            <td>'.$row["email_phone"].'</td>
            <td>'.$row["date_in"].'</td>
            <td>'.$row["time_in"].'</td>
        </tr>';
        $no++;
    }

    $export .= '</table>';
}

// Write table to PDF
$pdf->writeHTML($export, true, false, true, false, '');

// Output PDF to browser
$pdf->Output("DataMaster_ActiveVisitors.pdf", "I");
exit();
?>
