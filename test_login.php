<?php
require_once 'includes/config.php';

$password = 'test123';
$hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "Testing password verification:\n";
echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";
echo "Verification result: " . (password_verify($password, $hash) ? "TRUE" : "FALSE") . "\n";

// Skapa ett nytt hash för jämförelse
$newHash = password_hash($password, PASSWORD_DEFAULT);
echo "\nNew hash generated: " . $newHash . "\n";
echo "Verification with new hash: " . (password_verify($password, $newHash) ? "TRUE" : "FALSE") . "\n";