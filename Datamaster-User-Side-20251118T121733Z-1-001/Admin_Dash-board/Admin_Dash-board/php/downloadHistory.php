<?php
include('../php/connection.php');
include("../php/auth_session.php");

// Get user's email from session
$emailAddress = $_SESSION["firstname"];

// Fetch admin info
$result = mysqli_query($conn, "SELECT firstname, surname, email, companyname, employeeNo, department FROM `admin_table` WHERE email ='$emailAddress'");
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $firstname   = $row['firstname'];
    $lastname    = $row['surname'];
    $email       = $row['email'];
    $Enterprise  = $row['companyname'];
    $employeeID  = $row['employeeNo'];
    $department  = $row['department'];
}

// Current datetime
$currentDatetime = date("Y-m-d H:i:s");

// Include TCPDF library
require_once('../tcpdf/tcpdf.php');

// Create PDF
$obj_pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
$obj_pdf->setPrintHeader(false);
$obj_pdf->setPrintFooter(false);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
$obj_pdf->SetAutoPageBreak(TRUE, 10);
$obj_pdf->SetFont('helvetica', '', 12);
$obj_pdf->AddPage();

// Add logo
$obj_pdf->Image('C:\xampp\htdocs\DataMaster\Admin_Dash-board\images\Logo.jpeg', 220, 3, 55, 20, 'jpeg', 'https://datmaster.co.za');

// Add title
$obj_pdf->SetFont('helvetica', 'B', 13);
$obj_pdf->cell(10, 5, '', 0, 0);
$obj_pdf->cell(30, 5, 'DataMaster:', 0, 0);
$obj_pdf->cell(90, 5, 'History Report', 0, 1);

// Date
$obj_pdf->SetFont('helvetica', '', 12);
$obj_pdf->cell(10, 5, '', 0, 0);
$obj_pdf->cell(30, 5, 'Date:', 0, 0);
$obj_pdf->cell(90, 5, $currentDatetime, 0, 1);
$obj_pdf->cell(189, 10, '', 0, 1);

// Fetch visitor history
$query = "SELECT email_phone, person_name, person_surname, person_contact, timein, timeout FROM `questions_table`";
$res = mysqli_query($conn, $query);

if ($res && mysqli_num_rows($res) > 0) {
    $no = 1;

    $html = '<table width="100%" cellpadding="4" cellspacing="0" border="1" style="border-collapse:collapse;">
    <tr style="background-color:#3ab5e6; color:white;">
        <th>ID</th>
        <th>Email Address</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Mobile Number</th>
        <th>Time In</th>
        <th>Time Out</th>
    </tr>';

    while ($row = mysqli_fetch_assoc($res)) {
        $html .= '<tr>
            <td>'.$no.'</td>
            <td>'.$row["email_phone"].'</td>
            <td>'.$row["person_name"].'</td>
            <td>'.$row["person_surname"].'</td>
            <td>'.$row["person_contact"].'</td>
            <td>'.$row["timein"].'</td>
            <td>'.$row["timeout"].'</td>
        </tr>';
        $no++;
    }

    $html .= '</table>';

    // Write HTML to PDF
    $obj_pdf->writeHTML($html, true, false, true, false, '');
}

// Output PDF to browser
$obj_pdf->Output("History_Report.pdf", 'I'); // 'I' = inline display, use 'D' to force download
?>
