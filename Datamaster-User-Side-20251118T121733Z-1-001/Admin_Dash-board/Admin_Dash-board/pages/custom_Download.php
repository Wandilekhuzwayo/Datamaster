<?php
 // Call a connection
 include('../php/connection.php');
 // Call Auth_session on Home
 include("../php/auth_session.php");

 $emailAddres = $_SESSION["firstname"];
 // Create a Query
 $result = mysqli_query($conn, "SELECT  firstname, surname, email, companyname, employeeNo, department FROM `admin_table` WHERE email ='$emailAddres'");

 if ($result) {
     // Get data from user_table row
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

 // Check if the fromDate and toDate are set in the POST request
 // session_start();
 $fromDate = $_SESSION['fromDate'];
 $toDate = $_SESSION['toDate'];

 // Create a new PDF instance
 require_once('../tcpdf/tcpdf.php');
 $obj_pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

 $obj_pdf->SetCreator(PDF_CREATOR);

 $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);

 $obj_pdf->SetHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_DATA));

 $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
 $obj_pdf->setPrintHeader(false);
 $obj_pdf->setPrintFooter(false);
 $obj_pdf->SetAutoPageBreak(TRUE, 10);
 $obj_pdf->SetFont('helvetica', '', 12);
 $obj_pdf->AddPage();
 // Add a page to the PDF
 //$pdf->AddPage();

 $obj_pdf->Image('C:\xampp\htdocs\DataMaster\Admin_Dash-board\images\Logo.jpeg', 120, 0, 80, 35, 'jpeg', 'https://datmaster.co.za');
 $obj_pdf->cell(59, 5, '', 0, 1);

 // Empty Cell
 $obj_pdf->cell(189, 10, '', 0, 1);

 // Clients Information
 $obj_pdf->cell(100, 5, 'DataMaster     : Custom Reporting', 0, 0);
 $obj_pdf->cell(100, 5, '', 0, 1);

 $obj_pdf->cell(10, 5, '', 0, 0);
 $obj_pdf->cell(90, 5, 'Date       : ' . $currentDatetime, 0, 1);

 // Empty Cell as Vertical spacer
 $obj_pdf->cell(189, 10, '', 0, 1);
 //$obj_pdf->cell(189  ,10,'', 0,1);

 $query = "SELECT DATE(signInTime) AS visit_date, COUNT(*) AS visit_count, visitorsname FROM mock_data WHERE signInTime BETWEEN '$fromDate' AND '$toDate' GROUP BY visitorsname, visit_date ORDER BY visit_date";

 $export = '';


 $res = mysqli_query($conn, $query);
 if (mysqli_num_rows($res) > 0) {
     $no = 1;
     $export .= '
   <table width="120%" cellpadding="0" cellspacing="0" border="0" style="margin-left: 50;" > 
   <tr style="background-color:#3ab5e6; color: white; "> 
   <th style="width:25%">DATE</th>
   <th style="width:25%">ACCESS GRANTED</th>
   <th style="width:30%">VISITORS NAME</th>
   </tr>
   ';
     while ($row = mysqli_fetch_array($res)) {
         $export .= '
   <tr>
   <td>' . $row['visit_date'] . '</td>
   <td>' . $row['visit_count'] . '</td>
   <td>' . $row['visitorsname'] . '</td>
   </tr>
   ';
         $no++;
     }
     $export .= '</table>';
     $htmlContent = ob_get_contents(); // Capture the buffered output
     ob_end_clean(); // Clean the output buffer
     $obj_pdf->writeHTML($export);

     $obj_pdf->writeHTML($htmlContent); // Use the captured HTML content

     $obj_pdf->Output("DataMaster - CUSTOM REPORTING.pdf");
     echo $export;
 }
?>
