<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

chdir(__DIR__ . '/Datamaster-User-Side-20251118T121733Z-1-001/Datamaster-User-Side/User_Dash-board');

echo "<h3>Database Connection Test</h3>";

try {
    include('connection.php');
    
    if ($conn) {
        echo "<p class='success'>✅ Database connected successfully</p>";
        echo "<p>Server: " . mysqli_get_server_info($conn) . "</p>";
        
        // Test user_table
        $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM user_table");
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            echo "<p>✅ user_table has " . $row['count'] . " records</p>";
        } else {
            echo "<p class='error'>❌ Error querying user_table: " . mysqli_error($conn) . "</p>";
        }
        
        // Test questions_table
        $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM questions_table");
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            echo "<p>✅ questions_table has " . $row['count'] . " records</p>";
        } else {
            echo "<p class='error'>❌ Error querying questions_table: " . mysqli_error($conn) . "</p>";
        }
        
    } else {
        echo "<p class='error'>❌ Database connection failed</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
