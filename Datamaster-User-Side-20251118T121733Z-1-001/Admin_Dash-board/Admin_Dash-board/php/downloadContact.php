<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ob_start();

// Include files
include('../php/connection.php');
include("../php/auth_session.php");

// Get user's email from session
$emailAddres = $_SESSION["firstname"];

// Ensure $currentDatetime is always defined
$currentDatetime = date("Y-m-d H:i:s");

// Query for user info (optional, if needed in PDF header)
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
$pdf->Image('../images/Logo.jpeg', 200, 3, 55, 20, 'JPEG');

// PDF title
$pdf->SetFont('helvetica', 'B', 13);
$pdf->Cell(0, 5, 'DataMaster: Registered Visitors Report', 0, 1);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(30, 5, 'Date:', 0, 0);
$pdf->Cell(50, 5, $currentDatetime, 0, 1);
$pdf->Ln(5);

// Query data
$query = "SELECT fname, lname, mnum, contact, email FROM `user_table`";
$res = mysqli_query($conn, $query);

$export = '';
if ($res && mysqli_num_rows($res) > 0) {
    $export = '<table border="1" cellpadding="4">
    <tr style="background-color:#3ab5e6; color:white;">
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Mobile Number</th>
        <th>Alternate No.</th>
        <th>Email Address</th>
    </tr>';

    $no = 1;
    while ($row = mysqli_fetch_assoc($res)) {
        $export .= '<tr>
            <td>'.$no.'</td>
            <td>'.$row["fname"].'</td>
            <td>'.$row["lname"].'</td>
            <td>'.$row["mnum"].'</td>
            <td>'.$row["contact"].'</td>
            <td>'.$row["email"].'</td>
        </tr>';
        $no++;
    }
    $export .= '</table>';
}

// Write table to PDF
$pdf->writeHTML($export, true, false, true, false, '');

// Output PDF to browser
$pdf->Output("DataMaster_RegisteredVisitors.pdf", "I");
exit();
?>
