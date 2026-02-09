<?php
require_once('../tcpdf/tcpdf.php');
// Create a new PDF instance
$pdf = new TCPDF();

// Set document information
$pdf->SetCreator('sd creatives');
$pdf->SetAuthor('sd creatives');
$pdf->SetTitle('Visitor Report PDF');
$pdf->SetSubject('Visitor Report PDF');

// Add a page
$pdf->AddPage();

// Generate content
$pdf->SetFont('times', '', 12);
$pdf->Cell(0, 10, 'Visitor Custom Report', 0, 1, 'C');
$pdf->Ln();

// Establish a PDO connection
$host = 'localhost';
$dbname = 'datamaster';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the selected year & month from the form
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];

    // Fetch data from the database
    $query = "
        SELECT DATE(signInTime) AS visit_date, COUNT(*) AS visit_count, visitorsname 
        FROM mock_data
        WHERE signInTime >= :fromDate AND vacuatingTime <= :toDate
        GROUP BY visit_date, visitorsname
        ORDER BY visit_date";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':fromDate', $fromDate);
    $stmt->bindParam(':toDate', $toDate);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Create a table in the PDF
    $html = '<table border="1">
        <tr>
            <th>Date</th>
            <th>Visit Count</th>
            <th>Visitor Name</th>
        </tr>';
    foreach ($data as $row) {
        $html .= '<tr>
            <td>' . $row['visit_date'] . '</td>
            <td>' . $row['visit_count'] . '</td>
            <td>' . $row['visitorsname'] . '</td>
        </tr>';
    }
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, false, false, '');

    // Output the content
    $pdf->Output('Visitor_Report.pdf', 'D'); // D: Download the PDF
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Error: " . $e->getMessage();
}
?>