<?php 
// process_query.php

// Database connection parameters
$host = 'localhost';
$dbname = 'datamaster';
$username = 'root';
$password = '';

// Establish the database connection
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the data sent via AJAX
$selectedYear = $_POST['year'] ?? ''; // Set default value to empty string
$selectedMonth = $_POST['month'] ?? ''; // Set default value to empty string
$selectedWeek = $_POST['week'] ?? ''; // Set default value to empty string

// Query to get distinct years
$yearQuery = "SELECT YEAR(SignInTime) AS year FROM `mock_data` GROUP BY YEAR(SignInTime)";
$yearResult = mysqli_query($conn, $yearQuery);

// Initialize monthResult and weekResult with an empty result set
$monthResult = [];
$weekResult = [];

// Query to get distinct months
if (!empty($selectedYear)) {
    $monthQuery = "SELECT MONTH(SignInTime) AS month FROM `mock_data` WHERE YEAR(SignInTime) = $selectedYear GROUP BY MONTH(SignInTime)";
    $monthResult = mysqli_query($conn, $monthQuery);
}

// Query to get distinct weeks
if (!empty($selectedYear) && !empty($selectedMonth)) {
    $weekQuery = "SELECT WEEK(SignInTime) AS week FROM `mock_data` WHERE YEAR(SignInTime) = $selectedYear AND MONTH(SignInTime) = $selectedMonth GROUP BY WEEK(SignInTime)";
    $weekResult = mysqli_query($conn, $weekQuery);
}

// Close the database connection
mysqli_close($conn);

// Return the options for the select elements
$response = [
    'yearOptions' => getSelectOptions($yearResult, $selectedYear),
    'monthOptions' => getSelectOptions($monthResult, $selectedMonth),
    'weekOptions' => getSelectOptions($weekResult, $selectedWeek),
];

echo json_encode($response);

// Function to generate the select options based on the query result
function getSelectOptions($queryResult, $selectedValue)
{
    $options = '';
    if ($queryResult) {
        while ($row = mysqli_fetch_assoc($queryResult)) {
            $optionValue = $row['year'];
            $selected = ($selectedValue == $optionValue) ? 'selected' : '';
            $options .= "<option value='$optionValue' $selected>$optionValue</option>";
        }
    }
    return $options;
}
?>
