<?php
// your database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datamaster";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['year']) && isset($_POST['month']) && isset($_POST['week'])) {
        $selectedYear = $_POST['year'];
        $selectedMonth = $_POST['month'];
        $selectedWeek = $_POST['week'];

        // Calculate start and end dates for the selected week
        $startDate = date("Y-m-d", strtotime("{$selectedYear}-W{$selectedWeek}"));
        $endDate = date("Y-m-d", strtotime("{$selectedYear}-W{$selectedWeek}-7"));

        // Fetch data from the database based on selected year, month, and week using signInTime
        $sql = "SELECT DAYNAME(signInTime) as day, COUNT(*) as visitors 
                FROM mock_data 
                WHERE YEAR(signInTime) = ? 
                AND MONTH(signInTime) = ? 
                AND signInTime BETWEEN ? AND ?
                GROUP BY day";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $selectedYear, $selectedMonth, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        $visitData = array();

        while ($row = $result->fetch_assoc()) {
            $visitData[$row['day']] = $row['visitors'];
        }

        // Return the visit data in JSON format for the chart
        header('Content-Type: application/json');
        echo json_encode($visitData);
        exit;
    }
}

// Close the database connection
$conn->close();
?>
