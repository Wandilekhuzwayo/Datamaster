<?php

// Change this to your connection info.
$DATABASE_HOST = 'localhost';
//datmacki_datamaster
$DATABASE_USER = 'root';
//datmacki_root
$DATABASE_PASS = '';
$DATABASE_NAME = 'datamaster';



$conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
?>