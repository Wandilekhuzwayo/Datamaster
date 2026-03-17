<?php
// =====================
// CONNECTION + AUTH
// =====================
include('../php/connection.php');
include("../php/auth_session.php");

// Start session safely
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// =====================
// CHECK DATE RANGE
// =====================
if (!isset($_SESSION['fromDate']) || !isset($_SESSION['toDate'])) {
    die("Date range not selected.");
}

$fromDate = $_SESSION['fromDate'];
$toDate   = $_SESSION['toDate'];

if (empty($fromDate) || empty($toDate)) {
    die("Invalid date range.");
}

// Convert to full datetime
$fromDateTime = date("Y-m-d 00:00:00", strtotime($fromDate));
$toDateTime   = date("Y-m-d 23:59:59", strtotime($toDate));

// Current datetime for PDF
$currentDatetime = date("Y-m-d H:i:s");

// =====================
// TCPDF
// =====================
require_once('../tcpdf/tcpdf.php');

$obj_pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->setPrintHeader(false);
$obj_pdf->setPrintFooter(false);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
$obj_pdf->SetAutoPageBreak(TRUE, 10);
$obj_pdf->SetFont('helvetica', '', 12);
$obj_pdf->AddPage();

// =====================
// LOGO + HEADER
// =====================
$obj_pdf->Image(
    'C:\xampp\htdocs\DataMaster\Admin_Dash-board\images\Logo.jpeg',
    120, 0, 80, 35,
    'jpeg'
);

$obj_pdf->cell(189, 10, '', 0, 1);

$obj_pdf->SetFont('helvetica', 'B', 13);
$obj_pdf->cell(100, 5, 'DataMaster : Custom Reporting', 0, 1);

$obj_pdf->SetFont('helvetica', '', 11);
$obj_pdf->cell(100, 5, 'Date : ' . $currentDatetime, 0, 1);

$obj_pdf->cell(189, 10, '', 0, 1);

// =====================
// QUERY
// =====================
$query = "SELECT 
            DATE(signInTime) AS visit_date,
            COUNT(*) AS visit_count,
            visitorName
          FROM visitorstbl
          WHERE signInTime BETWEEN '$fromDateTime' AND '$toDateTime'
          GROUP BY visitorName, visit_date
          ORDER BY visit_date";

$res = mysqli_query($conn, $query);

// =====================
// TABLE OUTPUT
// =====================
$export = '';

if ($res && mysqli_num_rows($res) > 0) {

    $export .= '
    <table width="100%" cellpadding="5" cellspacing="0" border="1">
        <tr style="background-color:#3ab5e6; color:white;">
            <th width="30%">DATE</th>
            <th width="30%">ACCESS GRANTED</th>
            <th width="40%">VISITOR NAME</th>
        </tr>
    ';

    while ($row = mysqli_fetch_assoc($res)) {
        $export .= '
        <tr>
            <td>'.$row['visit_date'].'</td>
            <td>'.$row['visit_count'].'</td>
            <td>'.$row['visitorName'].'</td>
        </tr>';
    }

    $export .= '</table>';

} else {
    $export .= '<h4>No visitor data found for selected date.</h4>';
}

// =====================
// WRITE TO PDF
// =====================
$obj_pdf->writeHTML($export);

// =====================
// OUTPUT PDF
// =====================
$obj_pdf->Output("DataMaster_Custom_Report.pdf", "I");

?>