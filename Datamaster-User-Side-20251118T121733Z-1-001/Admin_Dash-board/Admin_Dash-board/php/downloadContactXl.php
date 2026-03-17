<?php
ob_clean();
ob_start();
error_reporting(0);

include('../php/connection.php');
// include("../php/auth_session.php"); // test without first

require_once('../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$spreadsheet = new Spreadsheet();
$worksheet = $spreadsheet->getActiveSheet();

/* LOGO */
$drawing = new Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo');
$drawing->setPath(__DIR__ . '/../images/logo.png'); // PNG ONLY
$drawing->setCoordinates('B1');
$drawing->setWidth(200);
$drawing->setWorksheet($worksheet);

/* QUERY */
$query = "SELECT fname, lname, mnum, contact, email FROM `user_table`";
$res = mysqli_query($conn, $query);

$startRow = 7;

$worksheet->setCellValue('A' . $startRow, 'ID');
$worksheet->setCellValue('B' . $startRow, 'First Name');
$worksheet->setCellValue('C' . $startRow, 'Last Name');
$worksheet->setCellValue('D' . $startRow, 'Mobile Number');
$worksheet->setCellValue('E' . $startRow, 'Alternate No.');
$worksheet->setCellValue('F' . $startRow, 'Email');

$rowIndex = $startRow + 1;
$no = 1;

while ($row = mysqli_fetch_assoc($res)) {
    $worksheet->setCellValue('A' . $rowIndex, $no++);
    $worksheet->setCellValue('B' . $rowIndex, $row["fname"]);
    $worksheet->setCellValue('C' . $rowIndex, $row["lname"]);
    $worksheet->setCellValue('D' . $rowIndex, $row["mnum"]);
    $worksheet->setCellValue('E' . $rowIndex, $row["contact"]);
    $worksheet->setCellValue('F' . $rowIndex, $row["email"]);
    $rowIndex++;
}

/* DOWNLOAD */
$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Registered_Visitors.xlsx"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
exit;