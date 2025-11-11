<?php
require_once 'includes/config.php';

try {
    $db = getDB();
    
    // Ta bort eventuellt gammalt adminkonto
    $db->exec("DELETE FROM users WHERE username = 'admin'");
    
    // Skapa ett nytt adminkonto med ett säkert lösenord
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute(['admin', $hashedPassword]);
    
    echo "Adminkontot har återställts!\n";
    echo "Användarnamn: admin\n";
    echo "Lösenord: admin123\n";
    
} catch(PDOException $e) {
    echo "Fel vid återställning av adminkonto: " . $e->getMessage() . "\n";
}
?>