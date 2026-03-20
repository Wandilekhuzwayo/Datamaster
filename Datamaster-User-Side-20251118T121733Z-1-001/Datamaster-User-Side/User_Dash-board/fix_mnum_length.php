<?php
include('connection.php');
$sql = "ALTER TABLE user_table MODIFY mnum VARCHAR(20)";
if ($conn->query($sql) === TRUE) {
    echo "Column mnum updated to VARCHAR(20)";
} else {
    echo "Error updating column: " . $conn->error;
}
?>
