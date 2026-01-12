<?php
$c = new mysqli('localhost', 'root', '@tpdT3pd', 'datamaster');
if ($c->connect_error) die("Connection failed: " . $c->connect_error);
$tables = ['questions_table', 'user_table'];
foreach($tables as $t) {
    echo "\nDESCRIBE $t:\n";
    $r = $c->query("DESCRIBE $t");
    while($row = $r->fetch_assoc()) { print_r($row); }
}
?>
