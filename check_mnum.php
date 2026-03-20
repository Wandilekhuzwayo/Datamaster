<?php
include('connection.php');
$result = $conn->query("DESCRIBE user_table");
while($row = $result->fetch_assoc()) {
    if($row['Field'] == 'mnum') {
        echo "Column: " . $row['Field'] . " Type: " . $row['Type'] . "\n";
    }
}
?>
