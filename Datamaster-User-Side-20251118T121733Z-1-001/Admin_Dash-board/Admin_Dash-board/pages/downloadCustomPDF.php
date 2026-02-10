<?php
ob_start();

// Include necessary files and setup connections
include('../php/connection.php');
include("../php/auth_session.php");
require_once('../tcpdf/tcpdf.php');
include('../pages/customReporting.php');

// Fetch user information
$emailAddress = $_SESSION["firstname"];
$result = mysqli_query($conn, "SELECT firstname, surname, email, companyname, employeeNo, department, addresses FROM `admin_table` WHERE email ='$emailAddress'");

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $firstname = $row['firstname'];
        $lastname = $row['surname'];
        $email = $row['email'];
        $Enterprise = $row['companyname'];
        $employeeID = $row['employeeNo'];
        $department = $row['department'];
        $addresses = $row['addresses'];
    }

    $currentDatetime = date("Y-m-d H:i:s"); // Current datetime

    // Create a new TCPDF instance
    $obj_pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $obj_pdf->SetCreator(PDF_CREATOR);
    $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $obj_pdf->SetHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_DATA));
    $obj_pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
    $obj_pdf->setPrintHeader(false);
    $obj_pdf->setPrintFooter(false);
    $obj_pdf->SetAutoPageBreak(TRUE, 10);
    $obj_pdf->SetFont('helvetica', '', 12);
    $obj_pdf->AddPage();

    // Add company logo (update the file path accordingly)
    $obj_pdf->Image('C:\xampp\htdocs\DataMaster\Admin_Dash-board\images\Revamped Logo_page-0001_591x230.jpeg', 110, 0, 100, 50, 'jpeg', 'https://datmaster.co.za');

    // Report header and client information
    $obj_pdf->cell(10, 5, '', 0, 0);
    $obj_pdf->cell(90, 5, 'To: ' . $Enterprise, 0, 1);

    $obj_pdf->cell(10, 5, '', 0, 0);
    $obj_pdf->cell(90, 5, 'Date: ' . $currentDatetime, 0, 1);

    $obj_pdf->cell(10, 5, '', 0, 0);
    $obj_pdf->Cell(90, 5, 'Email: ' . $email, 0, 1);

    $obj_pdf->cell(10, 5, '', 0, 0);
    $obj_pdf->cell(90, 5, 'Address: ' . $address, 0, 1);

if (isset($_POST['fromDate']) && isset($_POST['toDate'])) {
      $fromDate = $_POST['fromDate'];
      $toDate = $_POST['toDate'];
      try {
          // ... (connection setup)
      
          // Prepare the query with placeholders
          $query = "
              SELECT
                  DATE(signInTime) AS visit_date,
                  COUNT(*) AS visit_count,
                  visitorsname 
              FROM
                  mock_data
              WHERE
                  signInTime BETWEEN :fromDate AND :toDate
              GROUP BY
                  visitorsname, visit_date
              ORDER BY
                  visit_date
          ";
      
          // Bind values to placeholders
          $stmt = $pdo->prepare($query);
          $stmt->bindValue(':fromDate', $fromDate, PDO::PARAM_STR); // Check spelling and case
          $stmt->bindValue(':toDate', $toDate, PDO::PARAM_STR);     // Check spelling and case
      
          // Debug output to show prepared query and bound parameters
          //$stmt->debugDumpParams();
      
          // Execute the query
          $stmt->execute();
      
          // Fetch the results
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (mysqli_num_rows($res) > 0) {
        $no = 1;
        $export = '
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr style="background-color:#3ab5e6; color: white;">
                    <th>ID</th>
                    <th>Date</th>
                    <th>Access Granted</th>
                    <th>Visitor`s Full Name</th>
                </tr>
        ';

        foreach ($result as $row) {
            $export .= '
                <tr>
                    <td>' . $no . '</td>
                    <td>' . $row["visit_date"] . '</td>
                    <td>' . $row["visit_count"] . '</td>
                    <td>' . $row["visitorsname"] . '</td>
                </tr>
            ';
            $no++;
        }

        $export .= '</table>';
        $htmlContent = ob_get_contents(); // Capture the buffered output
        ob_end_clean(); // Clean the output buffer
        $obj_pdf->writeHTML($export);
        $obj_pdf->writeHTML($htmlContent); // Use the captured HTML content

        // Output the PDF (inline)
        $obj_pdf->Output("DataMaster_Active_Visitors.pdf", 'I');
        exit;
    }
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Error: " . $e->getMessage();
}
  }
    }

echo "No data found.";
?>
