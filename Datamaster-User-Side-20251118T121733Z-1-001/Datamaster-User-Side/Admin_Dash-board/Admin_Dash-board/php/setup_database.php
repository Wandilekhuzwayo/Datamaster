<?php
// setup_database.php

$host = 'localhost';
$user = 'root';
$password = $argv[1] ?? ''; // Get password from command line argument
$dbname = 'datamaster';
$dumpFile = __DIR__ . '/../../../../Datamaster(database)/Datamaster(database)/datamaster.sql';

echo "Attempting to connect to MySQL server...\n";

// Connect to MySQL server (no database selected yet)
$conn = new mysqli($host, $user, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}

echo "Connected successfully.\n";

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' created successfully or already exists.\n";
} else {
    die("Error creating database: " . $conn->error . "\n");
}

// Select the database
$conn->select_db($dbname);

// Read SQL dump file
if (!file_exists($dumpFile)) {
    die("Error: SQL dump file not found at $dumpFile\n");
}

echo "Reading SQL dump from $dumpFile...\n";
$sqlContent = file_get_contents($dumpFile);

// Execute multi-query
echo "Importing tables and data...\n";
if ($conn->multi_query($sqlContent)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            $result->free();
        }
        // Check if there are more results
    } while ($conn->more_results() && $conn->next_result());
    echo "Database setup completed successfully.\n";
} else {
    echo "Error importing database: " . $conn->error . "\n";
}

$conn->close();
?>
