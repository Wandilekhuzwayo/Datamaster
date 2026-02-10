<?php
ob_start();

include('../php/connection.php');
include("../php/auth_session.php");

$emailAddres = $_SESSION["firstname"];

$result = mysqli_query($conn, "SELECT firstname, surname, email, companyname, employeeNo, department FROM admin_table WHERE email ='$emailAddres'");


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

$obj_pdf->Image('C:\xampp\htdocs\DataMaster\Admin_Dash-board\images\Logo.jpeg', 200, 3, 55, 20, 'jpeg', 'https://datmaster.co.za');


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



if (isset($_GET['month']) && isset($_GET['year']) && isset($_GET['week'])   ) {
    $selectedMonth = mysqli_real_escape_string($conn, $_GET['month']);
    $selectedYear = mysqli_real_escape_string($conn, $_GET['year']);

    // Your database query to retrieve data for the selected month
    $query = "SELECT WEEK(SignInTime) AS week, COUNT(*) AS count,
              SUM(CASE WHEN access = 'Granted' THEN 1 ELSE 0 END) AS accessGranted,
              SUM(CASE WHEN access = 'Denied' THEN 1 ELSE 0 END) AS accessDenied,
              SUM(CASE WHEN reason_for_visiting = 'business' THEN 1 ELSE 0 END) AS businessVisit,
              SUM(CASE WHEN reason_for_visiting = 'personal' THEN 1 ELSE 0 END) AS personalVisit
              FROM `mock_data`
              WHERE MONTH(SignInTime) = $selectedMonth AND YEAR(SignInTime) = $selectedYear
              GROUP BY week";

    // Execute the query
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Create a PDF document
        require_once('../tcpdf/tcpdf.php');

        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetHeaderData('', '', 'Monthly Report', '');
        $pdf->SetHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->AddPage();

        // Define the table HTML
        $table = '<table width="100%" cellpadding="5" cellspacing="0">
                  <tr style="background-color: #3ab5e6; color: white;">
                    <th style="width:15%">Week</th>
                    <th style="width:15%">Number of visitors</th>
                    <th style="width:15%">Access Granted</th>
                    <th style="width:15%">Access Denied</th>
                    <th style="width:15%">Business Visit</th>
                    <th style="width:15%">Personal Visit</th>
                  </tr>';


                  $graphDataResult = mysqli_query($conn,  $query );
                
        while ($row = mysqli_fetch_assoc($graphDataResult)) {
            $weekNumber = sprintf("Week %02d", $row['week']);
            $visitWeek[] = $weekNumber;
            $table .= '<tr>';
            $table .= '<td>' . $weekNumber  . '</td>';
            $table .= '<td>' . $row['count'] . '</td>';
            $table .= '<td>' . $row['accessGranted'] . '</td>';
            $table .= '<td>' . $row['accessDenied'] . '</td>';
            $table .= '<td>' . $row['businessVisit'] . '</td>';
            $table .= '<td>' . $row['personalVisit'] . '</td>';
            $table .= '</tr>';
        }

        $table .= '</table>';
        $htmlContent = ob_get_contents();
        ob_end_clean();
        // Check if there is enough space for the table on the current page
      // Check if there is enough space for the table on the current page
    if ($obj_pdf->getY() + 60 > $obj_pdf->getPageHeight()) {
        // If not enough space, add a new page
        $obj_pdf->AddPage();
    }
    
    $obj_pdf->writeHTML( $table);
    $obj_pdf->writeHTML($htmlContent);
    $obj_pdf->Output("Monthly_Report.pdf.pdf");
    echo  $table;
    

      
    } else {
        echo "Error: Unable to fetch data from the database.";
    }
} else {
    echo "Error: Missing parameters in the request.";
}
?>