<?php
$c = new mysqli('localhost', 'root', '@tpdT3pd', 'datamaster');
if ($c->connect_error) die("Connection failed: " . $c->connect_error);
$r = $c->query('SHOW TABLES');
echo "Tables in datamaster:\n";
while($row = $r->fetch_row()) { echo "- " . $row[0] . "\n"; }
?>
