<?php
// Simple backup script using PHP
$host = 'localhost';
$user = 'root';
$pass = '@tpdT3pd';
$dbname = 'datamaster';
$backup_file = __DIR__ . '/backup_datamaster_' . date('Ymd_His') . '.sql';

$command = "\"C:\\wamp64\\bin\\mysql\\mysql8.0.31\\bin\\mysqldump.exe\" -h {$host} -u {$user} -p{$pass} {$dbname} > \"{$backup_file}\" 2>&1";

echo "Creating database backup...\n";
echo "Command: " . str_replace($pass, '****', $command) . "\n";

exec($command, $output, $return_var);

if ($return_var === 0 && file_exists($backup_file)) {
    echo "✓ Backup created successfully: " . basename($backup_file) . "\n";
    echo "File size: " . round(filesize($backup_file) / 1024, 2) . " KB\n";
} else {
    echo "✗ Backup failed!\n";
    echo "Output: " . implode("\n", $output) . "\n";
    exit(1);
}
?>
