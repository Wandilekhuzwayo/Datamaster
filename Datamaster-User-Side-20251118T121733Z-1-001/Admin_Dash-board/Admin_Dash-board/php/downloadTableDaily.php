<?php
ob_start();

include('../php/connection.php');
include("../php/auth_session.php");

$emailAddress = $_SESSION["firstname"];

$result = mysqli_query($conn, "SELECT firstname, surname, email, companyname, employeeNo, department FROM `admin_table` ");

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $firstname = $row['firstname'];
        $lastname = $row['surname'];
        $email = $row['email'];
        $Enterprise = $row['companyname'];
        $employeeID = $row['employeeNo'];
        $dapartment = $row['department'];
        
    }
    $currentTimestamp = time();
    $currentDatetime = date("Y-m-d H:i:s", $currentTimestamp);
}
require_once('../tcpdf/tcpdf.php');

$obj_pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
$obj_pdf->SetHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_DATA));
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
$obj_pdf->setPrintHeader(false);
$obj_pdf->setPrintFooter(false);
$obj_pdf->SetAutoPageBreak(TRUE, 10);
$obj_pdf->SetFont('helvetica', '', 12);
$obj_pdf->AddPage();

$obj_pdf->Image('C:\xampp\htdocs\DataMaster\Admin_Dash-board\images\Logo.jpeg', 200, 3, 55, 20, 'jpeg', 'https://datmaster.co.za');

// DataMaster: (vertical alignment with other topics)
$obj_pdf->SetFont('helvetica', 'B', 12);
$obj_pdf->cell(10, 5, '', 0, 0);
$obj_pdf->cell(30, 5, 'DataMaster:', 0, 0);
$obj_pdf->cell(90, 5, 'Daily Report', 0, 1);

// Reset the font for values

$obj_pdf->cell(10, 5, '', 0, 0);
$obj_pdf->cell(30, 5, 'Date:', 0, 0);
$obj_pdf->cell(90, 5, $currentDatetime, 0, 1);

$obj_pdf->cell(189, 10, '', 0, 1);

if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];

    $timeRanges = [
        "00:00 - 06:00",
        "06:01 - 11:59",
        "12:00 - 18:00",
        "18:01 - 23:59"
    ];

    $totalBusinessVisits = 0;
    $totalPersonalVisits = 0;
    $totalVisitors = 0;

    // Initialize an empty table
    $table = '<table width="100%" cellpadding="5" cellspacing="0">
              <tr style="background-color: #3ab5e6; color: white;">
                <th style="width:23%">HOURS</th>
                <th style="width:23%">DATE</th>
                <th style="width:23%">BUSINESS APPOINTMENT</th>
                <th style="width:23%">PERSONAL VISIT</th>
              </tr>';

              // Make sure selectedDate exists and is not empty
if (!isset($selectedDate) || empty($selectedDate)) {
    die("Error: No date selected. Please go back and select a date.");
}

    foreach ($timeRanges as $timeRange) {
        $query = "SELECT reason_visit FROM questions_table 
                WHERE DATE_FORMAT(timein, '%H:%i') >= SUBSTRING_INDEX('$timeRange', ' - ', 1)
                AND DATE_FORMAT(timein, '%H:%i') <= SUBSTRING_INDEX('$timeRange', ' - ', -1)
                AND DATE(timein) = '$selectedDate'";

        $result = mysqli_query($conn, $query);

        $businessVisitors = 0;
        $personalVisitors = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['reason_visit'] === 'business') {
                $businessVisitors++;
                $totalBusinessVisits++;
            } elseif ($row['reason_visit'] === 'personal') {
                $personalVisitors++;
                $totalPersonalVisits++;
            }
        }

        $totalVisitors += $businessVisitors + $personalVisitors;

        $table .= '<tr>';
        $table .= '<td>' . $timeRange . '</td>';
        $table .= '<td>' . $selectedDate . '</td>';
        $table .= '<td>' . $businessVisitors . '</td>';
        $table .= '<td>' . $personalVisitors . '</td>';
        $table .= '</tr>';
    }

    $table .= '<tr>';
    $table .= '<td>Total</td>';
    $table .= '<td></td>';
    $table .= '<td><strong>' . $totalBusinessVisits . '</strong></td>';
    $table .= '<td><strong>' . $totalPersonalVisits . '</strong></td>';
    $table .= '</tr>';

    $table .= '<tr>';
    $table .= '<td><strong>Total Number Of Visitors</strong></td>';
    $table .= '<td></td>';
    $table .= '<td colspan="2"><strong>' . $totalVisitors . '</strong></td>';
    $table .= '</tr>';

    $table .= '</table>';

    // Output the table in the PDF
    if ($obj_pdf->getY() + 60 > $obj_pdf->getPageHeight()) {
        $obj_pdf->AddPage();
    }

    $obj_pdf->writeHTML($table);
    $obj_pdf->Output("Daily_Report.pdf");
} else {
    echo "Error: Missing parameters in the request.";
}
?>