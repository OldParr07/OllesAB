<?php
require_once 'includes/config.php';

try {
    $db = getDB();
    
    // Skapa users-tabellen om den inte finns
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    
    // Kolla om det finns något adminkonto
    $stmt = $db->query("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $adminExists = $stmt->fetchColumn() > 0;
    
    // Om det inte finns något adminkonto, skapa ett
    if (!$adminExists) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute(['admin', $hashedPassword]);
        echo "Adminkonto har skapats med användarnamn 'admin' och lösenord 'admin123'\n";
    } else {
        echo "Adminkontot finns redan.\n";
    }
    
    echo "Användarhantering är konfigurerad!\n";
    
} catch(PDOException $e) {
    echo "Fel vid konfiguration av användarhantering: " . $e->getMessage() . "\n";
}
?>