<?php
// Include database connection
include('../php/connection.php');

// Include PhpSpreadsheet
require_once('../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Create new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Email Address');
$sheet->setCellValue('C1', 'First Name');
$sheet->setCellValue('D1', 'Last Name');
$sheet->setCellValue('E1', 'Mobile Number');
$sheet->setCellValue('F1', 'Time In');
$sheet->setCellValue('G1', 'Time Out');

// Query the database
$query = "SELECT email_phone, person_name, person_surname, person_contact, timein, timeout FROM `questions_table`";
$res = mysqli_query($conn, $query);

if ($res && mysqli_num_rows($res) > 0) {
    $rowIndex = 2; // Start writing from the second row
    $no = 1;       // ID column

    while ($row = mysqli_fetch_assoc($res)) {
        $sheet->setCellValue('A' . $rowIndex, $no); // ID
        $sheet->setCellValue('B' . $rowIndex, $row["email_phone"]);
        $sheet->setCellValue('C' . $rowIndex, $row["person_name"]);
        $sheet->setCellValue('D' . $rowIndex, $row["person_surname"]);
        $sheet->setCellValue('E' . $rowIndex, $row["person_contact"]);
        $sheet->setCellValue('F' . $rowIndex, $row["timein"]);
        $sheet->setCellValue('G' . $rowIndex, $row["timeout"]);

        $rowIndex++;
        $no++;
    }

    // Auto-size columns for better readability
    foreach (range('A','G') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Create writer
    $writer = IOFactory::createWriter($spreadsheet, 'Xls');

    // Output to browser
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="History_Report.xls"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
} else {
    echo "No data found to export.";
}
?>
