<?php

// localhost connection
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'datamaster';


// productions connection
// $DATABASE_HOST = 'localhost';
// $DATABASE_USER = 'evendico_datamaster';
// $DATABASE_PASS = 'Zx2gXSLMqCpsA6wP2wUK';
// $DATABASE_NAME = 'evendico_datamaster';




$conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
?>