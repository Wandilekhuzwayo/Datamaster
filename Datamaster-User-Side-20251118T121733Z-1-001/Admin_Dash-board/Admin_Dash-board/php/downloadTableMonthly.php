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

$obj_pdf->Image('C:\xampp\htdocs\DataMaster\Admin_Dash-board\images\Logo.jpeg', 190, 1, 70, 30, 'png', 'https://datmaster.co.za');

// Set the font for labels
$obj_pdf->SetFont('helvetica', 'B', 13);

// DataMaster: (vertical alignment with other topics)
$obj_pdf->cell(10, 5, '', 0, 0);
$obj_pdf->cell(30, 5, 'DataMaster:', 0, 0); // Note the colon (":") is placed here
$obj_pdf->cell(90, 5, 'Daily Report', 0, 1); // Move to the next line

// Reset the font for values
$obj_pdf->SetFont('helvetica', '', 12);


$obj_pdf->cell(10, 5, '', 0, 0);
$obj_pdf->cell(30, 5, 'Date:', 0, 0); // Note the colon (":") is placed here
$obj_pdf->cell(90, 5, $currentDatetime, 0, 1); // Move to the next line

$obj_pdf->cell(189, 10, '', 0, 1);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['months']) && isset($_GET['counts'])) {
    $months = json_decode($_GET['months']);
    $export = '
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-left: 50; text-align: center;">
    <tr style="background-color:#3ab5e6; color: white;">
    <th style="width:10%; padding: 5px; background-color:#3ab5e6; color: white;">MONTHS</th>
    <th style="width:25%; padding: 5px; background-color:#3ab5e6; color: white;"> BUSINESS APPOINTMENT</th>
    <th style="width:25%; padding: 5px; background-color:#3ab5e6; color: white;">PERSONAL VISIT</th>
    </tr>
    ';

    $grantedTotal = $deniedTotal = $businessTotal = $personalTotal = 0; // Initialize totals

    foreach ($months as $month) {
        $monthName = date('F', strtotime("2023-" . $month . "-01"));

        

        // SQL query to count 'personal' and 'business' reasons for visiting
        $reasonsQuery = "SELECT COUNT(CASE WHEN reason_for_visiting = 'business' THEN 1 END) AS business_count,
                        COUNT(CASE WHEN reason_for_visiting = 'personal' THEN 1 END) AS personal_count 
                        FROM mock_data 
                        WHERE MONTH(signInTime) = $month";

        $reasonsResult = $conn->query($reasonsQuery);
        $reasonsRow = $reasonsResult->fetch_assoc();

        // SQL query to count 'denied' and 'granted' access
         $accessQuery = "SELECT COUNT(CASE WHEN access = 'granted' THEN 1 END) AS granted_count,
                         COUNT(CASE WHEN access = 'denied' THEN 1 END) AS denied_count 
                         FROM mock_data 
                         WHERE MONTH(signInTime) = $month";

           $accessResult = $conn->query($accessQuery);
           $accessRow = $accessResult->fetch_assoc();

        // Format the numbers to display as two digits
    // $grantedCount = sprintf("%02d", $accessRow['granted_count']);
    // $deniedCount = sprintf("%02d", $accessRow['denied_count']);
    $businessCount = sprintf("%02d", $reasonsRow['business_count']);
    $personalCount = sprintf("%02d", $reasonsRow['personal_count']);

    // $grantedTotal += $accessRow['granted_count'];
    // $deniedTotal += $accessRow['denied_count'];
    $businessTotal += $reasonsRow['business_count'];
    $personalTotal += $reasonsRow['personal_count'];

    $export .= '
    <tr>
        <td style="padding: 10px;">' . $monthName . '</td>

        <td style="padding: 10px;">' . $businessCount . '</td>
        <td style="padding: 10px;">' . $personalCount . '</td>
    </tr>
    <tr style="height: 10px;"><td colspan="5"></td></tr>'; // Adding empty row with height to create space
    }
    $export .= '
    <tr style="background-color:#3ab5e6; color: white;">
        <td style="padding: 10px;"><strong>Total</strong></td>

        <td style="padding: 10px;"><strong>' . sprintf("%02d", $businessTotal) . '</strong></td>
        <td style="padding: 10px;"><strong>' . sprintf("%02d", $personalTotal) . '</strong></td>
    </tr>
    <tr style="height: 10px;"><td colspan="5"></td></tr>';
    
    $export .= '</table>';
}

$htmlContent = ob_get_contents();
ob_end_clean();

if ($obj_pdf->getY() + 60 > $obj_pdf->getPageHeight()) {
    $obj_pdf->AddPage();
}

$obj_pdf->writeHTML($export);

$tableWidth = 100;
$centerX = ($obj_pdf->getPageWidth() - $tableWidth) / 2;
$obj_pdf->setX($centerX);
$obj_pdf->writeHTML($htmlContent);

$obj_pdf->Output("DataMaster-Yearly-Report-Table.pdf", 'I'); // 'I' to display PDF in the browser
?>
