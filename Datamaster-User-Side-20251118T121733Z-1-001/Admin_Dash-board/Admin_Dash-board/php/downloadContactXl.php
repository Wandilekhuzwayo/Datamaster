<?php
// Get a connection
include('../php/connection.php');
// Call Auth_session on Home
include("../php/auth_session.php");

// Require PhpSpreadsheet classes
require_once('../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Create a new spreadsheet
$spreadsheet = new Spreadsheet();
$worksheet = $spreadsheet->getActiveSheet();

// Add a logo image
$drawing = new Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo');
//$drawing->setPath(__DIR__ .'C:/xampp/htdocs/Datamaster-main/Admin_Dash-board/images/Revamped-Logo_page-0001-_1_-_1_.svg'); // Your logo path
$drawing->setCoordinates('B1');
$drawing->setOffsetX(15);
$drawing->setOffsetY(10); // Adjusted
$drawing->setWidth(300);
$drawing->setWorksheet($worksheet);

// Query the database
$query = "SELECT fname, lname, mnum, contact, email FROM `user_table`";
$res = mysqli_query($conn, $query);

if ($res && mysqli_num_rows($res) > 0) {
    $startRow = 7; // Row for headers
    $worksheet->setCellValue('A' . $startRow, 'ID');
    $worksheet->setCellValue('B' . $startRow, 'First Name');
    $worksheet->setCellValue('C' . $startRow, 'Last Name');
    $worksheet->setCellValue('D' . $startRow, 'Mobile Number');
    $worksheet->setCellValue('E' . $startRow, 'Alternate No.');
    $worksheet->setCellValue('F' . $startRow, 'Email Address');

    $rowIndex = $startRow + 1; // Start populating data below headers
    $no = 1;

    while ($row = mysqli_fetch_assoc($res)) {
        $worksheet->setCellValue('A' . $rowIndex, $no);
        $worksheet->setCellValue('B' . $rowIndex, $row["fname"]);
        $worksheet->setCellValue('C' . $rowIndex, $row["lname"]);
        $worksheet->setCellValue('D' . $rowIndex, $row["mnum"]);
        $worksheet->setCellValue('E' . $rowIndex, $row["contact"]);
        $worksheet->setCellValue('F' . $rowIndex, $row["email"]);

        $rowIndex++;
        $no++;
    }

    // Auto-size columns for readability
    foreach (range('A', 'F') as $col) {
        $worksheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Create writer and output file
    $writer = new Xls($spreadsheet);
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="DataMaster_Registered_Visitor_Report.xls"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
} else {
    echo "No registered visitors found to export.";
}
?>
