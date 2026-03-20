<?php
include('../php/connection.php');
include("../php/auth_session.php");

// Get logged-in admin email from session
$emailAddress = $_SESSION["admin_email"];

// Fetch admin info
$result = mysqli_query($conn, "SELECT firstname, surname, email, companyname, department 
FROM admin_table 
WHERE email = '$emailAddress'");

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $firstname   = $row['firstname'];
    $lastname    = $row['surname'];
    $email       = $row['email'];
    $Enterprise  = $row['companyname'];
    $department  = $row['department'];
}

// Current datetime
$currentDatetime = date("Y-m-d H:i:s");

// Include TCPDF
require_once('../tcpdf/tcpdf.php');

// Create PDF
$obj_pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->setPrintHeader(false);
$obj_pdf->setPrintFooter(false);
$obj_pdf->SetMargins(10, 5, 10);
$obj_pdf->SetAutoPageBreak(TRUE, 10);
$obj_pdf->SetFont('helvetica', '', 12);
$obj_pdf->AddPage();

// Add logo (use correct path)
$obj_pdf->Image('../images/Logo.jpeg', 220, 3, 55, 20);

// Title
$obj_pdf->SetFont('helvetica', 'B', 13);
$obj_pdf->cell(0, 8, 'DataMaster - Visitor History Report', 0, 1);

// Date
$obj_pdf->SetFont('helvetica', '', 11);
$obj_pdf->cell(0, 6, 'Generated on: ' . $currentDatetime, 0, 1);
$obj_pdf->Ln(3);

// Fetch visitor history from visitorstbl
$query = "SELECT name, surname, phone_number, email, visitorName, signInTime, EvacuatingTime 
          FROM visitorstbl 
          ORDER BY signInTime DESC";

$res = mysqli_query($conn, $query);

if ($res && mysqli_num_rows($res) > 0) {
    $no = 1;

    $html = '<table width="100%" cellpadding="4" cellspacing="0" border="1" style="border-collapse:collapse;">
    <tr style="background-color:#3ab5e6; color:white;">
        <th>ID</th>
        <th>Name</th>
        <th>Surname</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Visitor Purpose</th>
        <th>Time In</th>
        <th>Time Out</th>
    </tr>';

    while ($row = mysqli_fetch_assoc($res)) {

        // Show "Still Inside" if not signed out
        $timeout = $row["EvacuatingTime"] ? $row["EvacuatingTime"] : "Still Inside";

        $html .= '<tr>
            <td>'.$no.'</td>
            <td>'.$row["name"].'</td>
            <td>'.$row["surname"].'</td>
            <td>'.$row["phone_number"].'</td>
            <td>'.$row["email"].'</td>
            <td>'.$row["visitorName"].'</td>
            <td>'.$row["signInTime"].'</td>
            <td>'.$timeout.'</td>
        </tr>';
        $no++;
    }

    $html .= '</table>';

    $obj_pdf->writeHTML($html, true, false, true, false, '');
}

// Output
$obj_pdf->Output("Visitor_History_Report.pdf", 'I');
?>