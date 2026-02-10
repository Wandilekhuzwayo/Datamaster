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

$obj_pdf->Image('C:\xampp\htdocs\DataMaster\Admin_Dash-board\images\Logo.jpeg', 190, 1, 70, 30, 'jpeg', 'https://datmaster.co.za');

// DataMaster: (vertical alignment with other topics)
$obj_pdf->SetFont('helvetica', 'B', 12); // Decrease the font size to 10 for section labels
$obj_pdf->cell(10, 5, '', 0, 0);
$obj_pdf->cell(30, 5, 'DataMaster:', 0, 0);
$obj_pdf->cell(90, 5, 'Yearly Report', 0, 1);

// Reset the font for values
$obj_pdf->SetFont('helvetica', '', 10); // Decrease the font size to 10 for section values

$obj_pdf->cell(10, 5, '', 0, 0);
$obj_pdf->cell(30, 5, 'Date:', 0, 0);
$obj_pdf->cell(90, 5, $currentDatetime, 0, 1);

$obj_pdf->cell(189, 10, '', 0, 1);


$query = "SELECT YEAR(SignInTime) AS year, COUNT(*) AS visitorCount
FROM `mock_data`
GROUP BY YEAR(SignInTime)
ORDER BY YEAR(SignInTime)";

$reasonsQuery = "SELECT COUNT(CASE WHEN Reason_for_visiting = 'business' THEN 1 END) AS business_count,
   COUNT(CASE WHEN Reason_for_visiting = 'personal' THEN 1 END) AS personal_count 
   FROM mock_data";

$accessQuery = "SELECT COUNT(CASE WHEN access = 'granted' THEN 1 END) AS granted_count,
   COUNT(CASE WHEN access = 'denied' THEN 1 END) AS denied_count 
   FROM mock_data";

   
$res = mysqli_query($conn, $query);

$export = '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-left: 50px; text-align: center;">';
$export .= '
    <tr style="background-color:#3ab5e6; color: white;">
        <th style="width:10%; padding: 10px; background-color:#3ab5e6; color: white;">YEAR</th>
        <th style="width:25%; padding: 10px; background-color:#3ab5e6; color: white;">BUSINESS APPOINTMENT</th>
        <th style="width:25%; padding: 10px; background-color:#3ab5e6; color: white;">PERSONAL VISIT</th>
    </tr>';

if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_array($res)) {
        $year = $row["year"];

        // Fetch and populate data from other queries
        $reasonsQuery = "SELECT COUNT(CASE WHEN Reason_for_visiting = 'business' AND YEAR(SignInTime) = '$year' THEN 1 END) AS business_count,
                           COUNT(CASE WHEN Reason_for_visiting = 'personal' AND YEAR(SignInTime) = '$year' THEN 1 END) AS personal_count 
                           FROM mock_data";

        $accessQuery = "SELECT COUNT(CASE WHEN access = 'granted' AND YEAR(SignInTime) = '$year' THEN 1 END) AS granted_count,
                           COUNT(CASE WHEN access = 'denied' AND YEAR(SignInTime) = '$year' THEN 1 END) AS denied_count 
                           FROM mock_data";

        $reasonsResult = $conn->query($reasonsQuery);
        $accessResult = $conn->query($accessQuery);

        if ($reasonsResult && $accessResult) {
            $reasonsRow = $reasonsResult->fetch_assoc();
            $accessRow = $accessResult->fetch_assoc();

            $export .= '
            <tr>
                <td style="padding: 10px;">' . $year . '</td>
                <td style="padding: 10px;">' . $reasonsRow['business_count'] . '</td>
                <td style="padding: 10px;">' . $reasonsRow['personal_count'] . '</td>
            </tr>';
        } else {
            // Handle query errors here
            // For example: echo "Error: " . $conn->error;
        }
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

    // Calculate the X-coordinate to center the table horizontally
    $tableWidth = 100; // Adjust this value as needed to match the actual table width
    $centerX = ($obj_pdf->getPageWidth() - $tableWidth) / 2;
    
    // Set the X-coordinate for centering the table
    $obj_pdf->setX($centerX);
    
    // Finally, write the HTML content again
    $obj_pdf->writeHTML($htmlContent);
    

    $obj_pdf->Output("DataMaster - Yearly Report Table.pdf");
    echo $export;

?>