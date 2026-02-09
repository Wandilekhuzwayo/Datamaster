<?php
ob_start();

include('../php/connection.php');
include("../php/auth_session.php");

$emailAddres = $_SESSION["firstname"];

$result = mysqli_query($conn, "SELECT firstname, surname, email, companyname, employeeNo, department FROM `admin_table` WHERE email ='$emailAddres'");

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
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
$obj_pdf->setPrintHeader(false);
$obj_pdf->setPrintFooter(false);
$obj_pdf->SetAutoPageBreak(TRUE, 10);
$obj_pdf->SetFont('helvetica', '', 12);
$obj_pdf->AddPage();

$obj_pdf->Image('C:\xampp\htdocs\DataMaster\Admin_Dash-board\images\Logo.jpeg', 220, 3, 55, 20, 'jpeg', 'https://datmaster.co.za');

// Set the font for labels
$obj_pdf->SetFont('helvetica', 'B', 13);

// DataMaster: (vertical alignment with other topics)
$obj_pdf->cell(10, 5, '', 0, 0);
$obj_pdf->cell(30, 5, 'DataMaster:', 0, 0); // Note the colon (":") is placed here
$obj_pdf->cell(90, 5, 'Active Visitor Report', 0, 1); // Move to the next line

// Reset the font for values
$obj_pdf->SetFont('helvetica', '', 12);


$obj_pdf->cell(10, 5, '', 0, 0);
$obj_pdf->cell(30, 5, 'Date:', 0, 0); // Note the colon (":") is placed here
$obj_pdf->cell(90, 5, $currentDatetime, 0, 1); // Move to the next line

$obj_pdf->cell(10, 5, '', 0, 0);
$obj_pdf->cell(30, 5, 'Address:', 0, 0); // Note the colon (":") is placed here
$obj_pdf->cell(90, 5, $address, 0, 1); // Move to the next line

$obj_pdf->cell(189, 10, '', 0, 1);

$query = "SELECT SUBSTRING(visitorsname, 1, 4) AS year, COUNT(*) AS visitor_count
          FROM mock_data
          GROUP BY year
          ORDER BY year DESC";

$res = mysqli_query($conn, $query);

if (mysqli_num_rows($res) > 0) {
    $export = '
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-left: 50;">
    <tr style="background-color:#3ab5e6; color: white;">
    <th style="width:3%; padding: 5px; background-color:#3ab5e6; color: white;">YEAR</th>
    <th style="width:25%; padding: 10px; background-color:#3ab5e6; color: white;">BUSINESS APPOINTMENT</th>
    <th style="width:25%; padding: 10px; background-color:#3ab5e6; color: white;">PERSONAL VISIT</th>
    </tr>';

    while ($row = mysqli_fetch_array($res)) {
        $export .= '
        <tr>
        <td style="padding: 5px; background-color:#f2f2f2; color: #333;">' . $row["year"] . '</td>
        <td style="padding: 5px; background-color:#f2f2f2; color: #333;">' . $row["visitor_count"] . '</td>
        </tr>
        ';
    }
}

    $export .= '</table>';
    $htmlContent = ob_get_contents();
    ob_end_clean();
    
    // Check if there is enough space for the table on the current page
    if ($obj_pdf->getY() + 60 > $obj_pdf->getPageHeight()) {
        // If not enough space, add a new page
        $obj_pdf->AddPage();
    }
    
    $obj_pdf->writeHTML($export);
    $obj_pdf->writeHTML($htmlContent);
    $obj_pdf->Output("DataMaster - yearly report.pdf");
    echo $export;
 
?>