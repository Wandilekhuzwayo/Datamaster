<?php
// Include database connection
include('../php/connection.php');
include("../php/auth_session.php");

// Include PhpSpreadsheet
require_once('../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ===== HEADERS =====
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'Surname');
$sheet->setCellValue('D1', 'Phone');
$sheet->setCellValue('E1', 'Email');
$sheet->setCellValue('F1', 'Visitor Name');
$sheet->setCellValue('G1', 'Sign In Time');
$sheet->setCellValue('H1', 'Sign Out Time');

// ===== FETCH DATA FROM visitorstbl =====
$query = "SELECT name, surname, phone_number, email, visitorName, signInTime, EvacuatingTime 
          FROM visitorstbl 
          ORDER BY signInTime DESC";

$res = mysqli_query($conn, $query);

if ($res && mysqli_num_rows($res) > 0) {

    $rowIndex = 2;
    $no = 1;

    while ($row = mysqli_fetch_assoc($res)) {

        // Handle visitors still inside
        $timeout = $row["EvacuatingTime"] ? $row["EvacuatingTime"] : "Still Inside";

        $sheet->setCellValue('A' . $rowIndex, $no);
        $sheet->setCellValue('B' . $rowIndex, $row["name"]);
        $sheet->setCellValue('C' . $rowIndex, $row["surname"]);
        $sheet->setCellValue('D' . $rowIndex, $row["phone_number"]);
        $sheet->setCellValue('E' . $rowIndex, $row["email"]);
        $sheet->setCellValue('F' . $rowIndex, $row["visitorName"]);
        $sheet->setCellValue('G' . $rowIndex, $row["signInTime"]);
        $sheet->setCellValue('H' . $rowIndex, $timeout);

        $rowIndex++;
        $no++;
    }

    // Auto-size columns
    foreach (range('A', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // ===== OUTPUT =====
    $writer = IOFactory::createWriter($spreadsheet, 'Xls');

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="Visitor_History_Report.xls"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;

} else {
    echo "No visitor records found.";
}
?><?php
// Include database connection
include('../php/connection.php');
include("../php/auth_session.php");

// Include PhpSpreadsheet
require_once('../vendor/autoload.php');

// Create spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ===== HEADERS =====
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'Surname');
$sheet->setCellValue('D1', 'Phone');
$sheet->setCellValue('E1', 'Email');
$sheet->setCellValue('F1', 'Visitor Name');
$sheet->setCellValue('G1', 'Sign In Time');
$sheet->setCellValue('H1', 'Sign Out Time');

// ===== FETCH DATA FROM visitorstbl =====
$query = "SELECT name, surname, phone_number, email, visitorName, signInTime, EvacuatingTime 
          FROM visitorstbl 
          ORDER BY signInTime DESC";

$res = mysqli_query($conn, $query);

if ($res && mysqli_num_rows($res) > 0) {

    $rowIndex = 2;
    $no = 1;

    while ($row = mysqli_fetch_assoc($res)) {

        // Handle visitors still inside
        $timeout = $row["EvacuatingTime"] ? $row["EvacuatingTime"] : "Still Inside";

        $sheet->setCellValue('A' . $rowIndex, $no);
        $sheet->setCellValue('B' . $rowIndex, $row["name"]);
        $sheet->setCellValue('C' . $rowIndex, $row["surname"]);
        $sheet->setCellValue('D' . $rowIndex, $row["phone_number"]);
        $sheet->setCellValue('E' . $rowIndex, $row["email"]);
        $sheet->setCellValue('F' . $rowIndex, $row["visitorName"]);
        $sheet->setCellValue('G' . $rowIndex, $row["signInTime"]);
        $sheet->setCellValue('H' . $rowIndex, $timeout);

        $rowIndex++;
        $no++;
    }

    // Auto-size columns
    foreach (range('A', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // ===== OUTPUT =====
    $writer = IOFactory::createWriter($spreadsheet, 'Xls');

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="Visitor_History_Report.xls"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;

} else {
    echo "No visitor records found.";
}
?>