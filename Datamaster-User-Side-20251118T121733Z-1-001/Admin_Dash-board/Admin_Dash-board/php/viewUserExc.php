<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../php/connection.php');
require_once('../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$worksheet = $spreadsheet->getActiveSheet();

// Column headers
$worksheet->setCellValue('A1', 'ID');
$worksheet->setCellValue('B1', 'First Name');
$worksheet->setCellValue('C1', 'Last Name');
$worksheet->setCellValue('D1', 'Mobile Number');
$worksheet->setCellValue('E1', 'Alternate No.');
$worksheet->setCellValue('F1', 'Email Address');
$worksheet->setCellValue('G1', 'Enterprise');
$worksheet->setCellValue('H1', 'Address');
$worksheet->setCellValue('I1', 'City');
$worksheet->setCellValue('J1', 'Province');

// Query
$query = "SELECT id, fname, lname, mnum, contact, email, cname, addresses, city, province FROM `user_table`";
$res = mysqli_query($conn, $query);

if (!$res) {
    die("Query failed: " . mysqli_error($conn));
}

$no = 1;
while ($row = mysqli_fetch_assoc($res)) {
    $rowIndex = $no + 1;
    $worksheet->setCellValue('A' . $rowIndex, $no);
    $worksheet->setCellValue('B' . $rowIndex, $row["fname"]);
    $worksheet->setCellValue('C' . $rowIndex, $row["lname"]);
    $worksheet->setCellValue('D' . $rowIndex, $row["mnum"]);
    $worksheet->setCellValue('E' . $rowIndex, $row["contact"]);
    $worksheet->setCellValue('F' . $rowIndex, $row["email"]);
    $worksheet->setCellValue('G' . $rowIndex, $row["cname"]);
    $worksheet->setCellValue('H' . $rowIndex, $row["address"]);
    $worksheet->setCellValue('I' . $rowIndex, $row["city"]);
    $worksheet->setCellValue('J' . $rowIndex, $row["province"]);
    $no++;
}

// Create writer
$writer = new Xlsx($spreadsheet);

// Send headers before any output
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="DataMaster_Registered_Visitor_report.xlsx"');
header('Cache-Control: max-age=0');

// Save to output
$writer->save('php://output');
exit();

